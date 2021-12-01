<?php

    session_start();
    require_once("/modelo/consultaSQL.php");
	require_once("/modelo/conectarBD.php");
    
    //echo $_SESSION['sesion_matriz'];
    //echo  "2".sizeof($_SESSION['sesion_matriz']);

    $qwery = "SELECT * FROM `vcdials`";
    $Respuesta = mysql_query($qwery, $conexion) or die(mysql_error());

    $queryresul = "SELECT * FROM `juicios_dato_inicial`";

    $resulsetJuicios = mysql_query($queryresul, $conexion) or die(mysql_error());

    $cont = 0;
    while ($item = mysql_fetch_assoc($Respuesta))
    {
        $c = array(
            ('VCDIAL') => $item["cod_vcdial"],
            ('CodigoAccion') => $item["cod_accion"],
            ('CodGestion') => $item["cod_respuesta"],
            ('Descrip') => $item["nom_respuesta"]
        );
        $GLOBALS["DIALSBDD"][$cont] = $c;
        $cont ++;
    }

    $fechas = $_POST['fechas'];
    $confirmo = $_POST['confirmo'];

    $array_fechas = preg_split('/,/',$fechas);
    $matriz = array();

    $tabla_no = array();

    $valores = array();

    $lineas = array();

    //$tabla_no2 =  array();

    $lineas = $_SESSION['sesion_matriz'];
    //echo  sizeof($matriz);
    
    foreach($lineas as $lineaarray){
        $parts = preg_split('/\t/', $lineaarray);
        
        $rutCortado =  preg_split('/-/',trim(substr($parts[5],0,12)));
        //echo $fech ."----".$rutCortado[0]."-";
        $addres2 = substr($parts[17],0,16);
        
        foreach($array_fechas as $fech){
            $rut = ""; 
            $var = 0;     
            if(trim(substr($parts[2],0,10)) == $fech){  
                

                //echo json_encode($resulsetJuicios);
                //echo gettype($row['rut']);
                //echo $fech ."----".$rutCortado[0]."-";
                $rut = $rutCortado[0];
                
                while($row = mysql_fetch_assoc($resulsetJuicios)){ 
                    $rows[] = $row;
          
                }
                //echo count($rows);
                foreach($rows as $row)
                {
                    //echo $row['rut']."*".$rut;
                    //echo $row['cuenta']."*".$addres2."<br>";
                    //if ($addres2 == "304111453" && $row['cuenta'] == "00304111453"){
                        //echo $row['cuenta']."<br>";
                    //}
                    //if($row['rut'] == $rut && $row['cuenta'] == $addres2){
                    if(strcmp($row['rut'],$rut) == 0 && strcmp($row['cuenta'],$addres2) == 0){
                        $reg = llenartrans($parts);                        
                        array_push($matriz,$reg);   
                        $var++;       
                        break;
                    }
                    
                }
                //echo $var;
                if($var == 0){
                    $cc = array(
                        'rut' => $rut,
                        'cuenta' => $addres2
                    );
                    array_push($tabla_no,$cc);
                }

               
            }   
        }        
    }
    //echo json_encode($resulsetJuicios);
    //echo sizeof($tabla_no2);
    //echo sizeof($matriz)." Tabla  ".sizeof($tabla_no2);
    //echo var_dump($tabla_no);
    
    foreach($tabla_no as $t){
         $result .='<tr><td>CUENTA Y RUT NO EXISTE EN SISTEMA</td><td>'.$t['cuenta'].'</td>'.
            '<td>'.$t['rut'].'</td></tr>';
    }
    
    //*/
    
    $accionNoEncontrada = GenerarPlano200($matriz, $conexion,$confirmo);
    $result .= $accionNoEncontrada;
    GenerarPlano600($matriz, $conexion,$confirmo);
    GenerarPlano700($matriz, $conexion,$confirmo);
    GenerarPlano800($matriz, $conexion,$confirmo);
    
    session_reset();

    echo $result;

    function GenerarPlano200($transacciones, $conexion,$confirmo)
    {
        try
        {
            $accionNoEncontrada = "";
            $listaplanos = array();
            $f=strval(date('Y-m-d H:i:s'));
            $f2 = substr($f,5,2) . substr($f,8,2) . substr($f,0,4);
            $f3 = substr($f,11,10);
            foreach ( $transacciones AS $item )
            {
                $plano = new Plano200;
                $plano->Valorconstante = "200"; 
                $plano->Grupo= "6"; 
                $plano->Fecha=  $f2;
                $plano->Cuenta = $item->Cuenta;
                $plano->Hora= $f3;
                
                $plano->Secuencia= "001";
                
                if(strlen($item->CodigoAccion) != ""){
                    $plano->CodigoAccion= $item->CodigoAccion;
                }
                else{
                    $accionNoEncontrada .='<tr><td>CODIGO VCDIAL NO EXISTE EN SISTEMA</td><td>'.$plano->Cuenta.'</td>'.
                    '<td>'.$item->Rut.'</td></tr>';
                }
                if (strlen($item->Respuesta) == ""){
                    continue;
                }
                if (strlen($item->CodigoResultado) != ""){
                   $plano->CodigoResultado= $item->CodigoResultado;
                }
                else{
                    continue;
                }
                    
                if (strlen($item->Contingencia) == ""){
                $item->codcontingencia = "VX";
                $plano->CodigoCarta = "VX";
                }
                else if (strtoupper($item->Contingencia) == "MOLESTO GENERAL"){
                $item->codcontingencia = "VY";
                $plano->CodigoCarta = "VY";
                }
                else if (strtoupper($item->Contingencia) == "MOLESTO POR CONTINGENCIA"){
                $item->codcontingencia = "VQ";
                $plano->CodigoCarta = "VQ";
                } 
                $plano->IdEmpex= "SERVICOB";
                if (strlen($item->Observaciones) != ""){}
                $plano->Comentario= str_pad($item->Descripcion,56," ", STR_PAD_RIGHT);
                if (strlen($item->Telefono) != ""){}
                $plano->telefono= substr($item->Telefono,0,8);
                if (strlen($item->Gestor) != ""){
                    $plano->IdGestor= strtoupper(str_pad($item->Gestor, 8, " ", STR_PAD_RIGHT));
                } else {
                    $plano->IdGestor= strtoupper(str_pad("MARIBEL", 8, " ", STR_PAD_RIGHT));
                }
                

                $plano->VCDIAL = $item->Status;   
                array_push($listaplanos, $plano);

            }

            Guardar200($listaplanos, $conexion, $f2,$confirmo);
            return $accionNoEncontrada;
        }
        catch (Exception $ex)
        {
            // log.Error("GenerarPlano200", $ex);
            // throw;
        }
    
    }
    function GenerarPlano600($transacciones, $conexion,$confirmo)
    {
        try
        {
            $listaplanos = array();
            $transacciones = array_filter($transacciones, function ($transacciones) { 
                if ( $transacciones->Descripcion != null && $transacciones->Descripcion == "COMPROMISO DE PAGO" ){
                    return true;
                }else{
                    return false;
                }
            },null);
            $f=strval(date('Y-m-d H:i:s'));
            $f2 = substr($f,5,2) . substr($f,8,2) . substr($f,0,4);
            foreach ( $transacciones AS $item )
            {
                $plano = new Plano600;
                if ($item->Cuenta == null) continue;
                $plano-> Valorconstante="600";
                $plano->Grupo= "6";
                $plano->Cuenta =strtoupper(str_pad($item->Rut . $item->DV, 25, " ", STR_PAD_RIGHT));
                $plano->IdEmpex= "SERVICOB";
                if (strlen($item->CodigoAccion) == ""){
                    continue;
                }
                if(strlen($item->CodigoAccion) != ""){
                    $plano->CodigoAccion = $item->CodigoAccion;
                }
                else{
                    continue;
                }
                if (strlen($item->Respuesta) != ""){
                    $plano->res = $item->Respuesta;
                }
                else{
                    continue;
                }
                if (strlen($item->CodigoResultado) != ""){
                    $plano->CodigoResultado = $item->Respuesta;
                }
                else{
                    continue;
                }
                $plano->Fecha= $f2;
                $plano->Promno = "000";
                $plano->Promai = "000";
                $plano->FechaVencProm = $item->FechaCompromiso;
                $plano->PromMonto = "000000000000000";
                $plano->VCDIAL = $item->Status;
                $plano->CodigoAccion= $item->CodigoAccion;
                $plano->CodigoResultado= $item->CodigoResultado;
                array_push($listaplanos, $plano);
            }
                Guardar600($listaplanos, $conexion, $f2,$confirmo);
        }
        catch (Exception $ex)
        {
            //log.Error("GenerarPlano600", ex);
            //throw;
        }
    }
    
    function GenerarPlano700($transacciones, $conexion,$confirmo)
    {
        try
        {
            $listaplanos = array();
            $i = 2;
            $f=strval(date('Y-m-d H:i:s'));
            $f2 = substr($f,0,4)."/".substr($f,5,2)."/".substr($f,8,2);
            foreach ( $transacciones AS $item )
            {
                $plano = new Plano700;
                if ($item->Cuenta == null) continue;
                $plano->Valorconstante= "700";
                $plano->Grupo="6";
                $plano->Cuenta = strtoupper(str_pad($item->Rut . $item->DV, 25, " ", STR_PAD_RIGHT));
                $plano->IdCliente = strtoupper(str_pad($item->IdCliente, 25, " ", STR_PAD_RIGHT));
                $plano->TipoTelefono="";
                $plano->Areacode="";
                $plano->Telefono = strtoupper(str_pad($item->Telefono, 13, " ", STR_PAD_RIGHT));
                $plano->Fonoexten="";
                $plano->IdEmpex = "SERVICOB";
                $plano->VCDIAL = $item->Status;
                $plano->CodigoAccion= $item->CodigoAccion;
                $plano->CodigoResultado= $item->CodigoResultado;
                array_push($listaplanos, $plano);
                $i++;
            }
                Guardar700($listaplanos, $conexion, $f2,$confirmo);
        }
        catch (Exception $ex)
        {
            //log.Error("GenerarPlano700", ex);
           // throw;
        }
    }
    function GenerarPlano800($transacciones, $conexion,$confirmo)
    {
        try
        {
            $listaplanos = array();
            $f=strval(date('Y-m-d H:i:s'));
            $f2 = substr($f,0,4)."/".substr($f,5,2)."/".substr($f,8,2);
            foreach ( $transacciones AS $item )
            {
                $plano = new Plano800;
                if ($item->Cuenta == null) continue;
                $plano->Valorconstante = "800";
                $plano->Grupo ="6";
                $plano->Cuenta = strtoupper(str_pad($item->Cuenta, 25, " ", STR_PAD_RIGHT));
                $plano->IdCliente = strtoupper(str_pad($item->IdCliente, 25, " ", STR_PAD_RIGHT));
                $plano->TipoDirecc ="";
                $plano->Domicilio = str_pad($item->Domicilio, 80, " ", STR_PAD_RIGHT);
                $plano->Comuna = str_pad($item->Comuna, 80, " ", STR_PAD_RIGHT);
                $plano->direccion3 =str_pad("", 80, " ", STR_PAD_RIGHT);
                $plano->Ciudad =str_pad("", 40, " ", STR_PAD_RIGHT);
                $plano->DirEstado = str_pad("", 15, " ", STR_PAD_RIGHT);
                $plano->PostalCode =str_pad("", 10, " ", STR_PAD_RIGHT);
                $plano->IdEmpex = "SERVICOB";
                $plano->Estado ="A";
                $plano->VCDIAL = $item->Status;
                $plano->CodigoAccion= $item->CodigoAccion;
                $plano->CodigoResultado= $item->CodigoResultado;
                array_push($listaplanos, $plano);
            }
                Guardar800($listaplanos, $conexion, $f2,$confirmo);
        }
        catch (Exception $ex)
        {
           // log.Error("GenerarPlano800", ex);
            //throw;
        }
    }


    function llenartrans($reg) {
        $t = new transaccion;
        $P = codigos($reg[3]);
        $t->IdCliente = $reg[0];
        $t->Rut = substr($reg[5], 0, -2);
        $t->DV = substr($reg[5], -1);
        $t->Nombre =  $reg[16];
        $t->Telefono = $reg[11];
        $t->Accion = "";
        $t->Status = $reg[3];
        $t->Respuesta = $reg[6];
        $t->Contingencia = "";//contingrncia siempre esta vacio
        $t->Observaciones = "";
        $t->Fecha = substr($reg[1],3,2) . substr($reg[1],0,2) . substr($reg[1],6,4);
        $t->MontoCompromiso ="";
        $t->FechaCompromiso = "";
        $t->Domicilio = "";
        $t->Comuna = "";
        $t->Gestor = $reg[4];
        if (substr(trim($reg[17]), -1, 1) == "A" ){
            $t->Cuenta = substr(trim($reg[17]), 0, -1);
        } else{
            $t->Cuenta = $reg[17];
        }
        $t->CodigoAccion = $P->CodigoAccion;// CodigoAccion($reg[5]);
        $t->Acciones = "";
        $t->CodigoResultado = $P->CodGestion;// codgestion($reg[5]);
        $t->Resultados =    "";
        $t->Codigodecarta = "";
        $t->Idempex = "";
        $t->Comentario = "";
        $t->Descripcion = $P->Descrip;//Descrip($reg[5]);

        return $t;
    }
    function codigos($reg){
        $c = new codes;
        $key = array_keys(array_column($GLOBALS["DIALSBDD"], 'VCDIAL'),$reg);
        foreach( $key AS $item ){
            $c->CodigoAccion = $GLOBALS["DIALSBDD"][$item]['CodigoAccion'] ;
            $c->CodGestion = $GLOBALS["DIALSBDD"][$item]['CodGestion'];
            $c->Descrip = $GLOBALS["DIALSBDD"][$item]['Descrip'];
        }
        return $c;
    }
    class codes{
        public $VCDIAL;
        public $CodigoAccion;
        public $CodGestion;
        public $Descrip;
    }
    class transaccion{
        public $IdCliente;
        public $Rut;
        public $DV;
        //fecha
        //hora
        public $Cuenta;
        public $Nombre;
        public $Accion;
        public $CodigoAccion;
        public $Respuesta;
        public $Observaciones;
        public $Telefono;
        public $Domicilio;
        public $Comuna;
        public $Gestor;
        public $MontoCompromiso;
        public $Fecha;
        public $FechaCompromiso;
        public $Contingencia;
        public $codcontingencia;
        public $Resultados;
        public $CodigoResultado;
        public $Codigodecarta;
        public $Idempex;
        public $Comentario;
        public $Status;
    }

    function Guardar200( $listaplano, $conexion, $f2,$c){
        $regs ="";
        $first = true;
        $i = 0; 
        if (empty($listaplano)) { return; }
        if($c == 1){
            $sql = "DELETE FROM plano200 WHERE FECHA = '{$f2}';";
            mysql_query($sql, $conexion) or die(mysql_error());
        }
        foreach ( $listaplano AS $plano  ){
            $i++;
            if ($first == true){
                $first = false;
            }
            else{
                $regs = $regs . ",";
            }
            $regs = $regs . "(\"".$plano->Valorconstante."\",\"".$plano->Grupo."\",\"".$plano->Cuenta."\",\"".$plano->Fecha."\",\"".$plano->Hora."\",\"".$plano->Secuencia."\",\"".$plano->CodigoAccion."\",\"".$plano->CodigoResultado."\",\"".$plano->CodigoCarta."\",\"".$plano->IdEmpex."\",\"".$plano->Comentario."\",\"".$plano->telefono."\",\"".$plano->IdGestor."\",\"".$plano->VCDIAL."\")";
            if ($i == 400){
                $qwery = "INSERT INTO `plano200`( `VALORCONSTATE`, `GRUPO`, `CUENTA`, `FECHA`, `HORA`, `SECUENCIA`, `CODIGOACCION`, `RESULTADO`, `CODIGOCARTA`, `IDEMPEX`, `COMENTARIO`, `TELEFONO`, `IDGESTOR`, `VCDIAL`) VALUES" . $regs .";";
                $resultados = mysql_query($qwery, $conexion) or die(mysql_error());
                //echo $qwery;
                $first = true;
                $i = 0; 
                $qwery = "";
                $regs = "";
            }
        }
        $qwery = "INSERT INTO `plano200`( `VALORCONSTATE`, `GRUPO`, `CUENTA`, `FECHA`, `HORA`, `SECUENCIA`, `CODIGOACCION`, `RESULTADO`, `CODIGOCARTA`, `IDEMPEX`, `COMENTARIO`, `TELEFONO`, `IDGESTOR`, `VCDIAL`) VALUES" . $regs .";";
        //$Respuesta = mysqli_query($conexion ,$qwery);
                $resultados = mysql_query($qwery, $conexion) or die(mysql_error());
        //echo $qwery;
    }
    class Plano200
    {
        public  $Valorconstante = "200"; 
        public  $Grupo= "6"; 
        public  $Cuenta;
        public  $Fecha;
        public  $Hora; 
        public  $Secuencia; 
        public  $CodigoAccion;
        public  $CodigoResultado;
        public  $CodigoCarta;
        public  $IdEmpex;
        public  $Comentario;
        public  $telefono;
        public  $IdGestor;
        Public  $VCDIAL;
    }
    function Guardar600($listaplano, $conexion, $f2,$confirmo){
        $regs ="";
        $first = true;
        $i = 0; 
        if (empty($listaplano)) { return; }
        if($confirmo == 1){
            $sql = "DELETE FROM plano600 WHERE FECHA = '{$f2}';";
            mysql_query($sql, $conexion) or die(mysql_error());
        }
        foreach ( $listaplano AS $plano  ){
            $i++;
            if ($first == true){
                $first = false;
            }
            else{
                $regs = $regs . ",";
            }
            $regs = $regs . "(\"".$plano->Valorconstante."\",\"".$plano->Grupo."\",\"".$plano->Cuenta."\",\"".$plano->IdEmpex."\",\"".$plano->CodigoAccion."\",\"".$plano->Fecha."\",\"".$plano->Promno."\",\"".$plano->Promai."\",\"".$plano->FechaVencProm."\",\"".$plano->PromMonto."\",\"".$plano->VCDIAL."\",\"".$plano->CodigoResultado."\")";
            if ($i == 400){
                $qwery = "INSERT INTO `plano600`(`VALORCONSTANTE`, `GRUPO`, `CUENTA`, `IDEMPEX`, `ACCION`, `FECHA`, `PROMNO`, `PROMAI`, `FECHAVENCPROM`, `PROMMONTO`, `VCDIAL`, `RESULTADO`) VALUES ". $regs .";";
                //$Respuesta = mysqli_query($conexion ,$qwery);
                $resultados = mysql_query($qwery, $conexion) or die(mysql_error());
                //echo $qwery;
                //echo $Respuesta;
                $first = true;
                $i = 0; 
                $qwery = "";
                $regs = "";
            }
        }
        $qwery = "INSERT INTO `plano600`(`VALORCONSTANTE`, `GRUPO`, `CUENTA`, `IDEMPEX`, `ACCION`, `FECHA`, `PROMNO`, `PROMAI`, `FECHAVENCPROM`, `PROMMONTO`, `VCDIAL`, `RESULTADO`) VALUES ". $regs .";";
        //$Respuesta = mysqli_query($conexion ,$qwery);
        $resultados = mysql_query($qwery, $conexion) or die(mysql_error());
        //echo $qwery;
        //echo $Respuesta;
    }
    class Plano600
    {
       
        public  $Valorconstante = "600"; 
        public  $Grupo= "6"; 
        public  $Cuenta;
        public  $IdEmpex;
        public  $CodigoAccion;
        public  $Fecha;
        Public  $Promno;
        Public  $Promai;
        Public  $FechaVencProm;
        Public  $PromMonto;
        Public  $VCDIAL;
        public  $CodigoResultado;
       
    }
    
    function Guardar700($listaplano, $conexion, $f2,$c){
        $regs ="";
        $first = true;
        $i = 0; 
        if (empty($listaplano)) { return; }

        if($c == 1){
            $sql = "DELETE FROM plano700 WHERE DATE_FORMAT(FECHINGRESO, '%Y%m%d') = DATE_FORMAT('{$f2}', '%Y%m%d');";
            mysql_query($sql, $conexion) or die(mysql_error());
        }
        foreach ( $listaplano AS $plano  ){
            $i++;
            if ($first == true){
                $first = false;
            }
            else{
                $regs = $regs . ",";
            }
            $regs = $regs . "(\"".$plano->Valorconstante."\",\"".$plano->Grupo."\",\"".$plano->Cuenta."\",\"".$plano->IdCliente."\",\"".$plano->TipoTelefono."\",\"".$plano->Areacode."\",\"".$plano->Telefono."\",\"".$plano->Fonoexten."\",\"".$plano->IdEmpex."\",\"".$plano->VCDIAL."\",\"".$plano->CodigoAccion."\",\"".$plano->CodigoResultado."\")";
            if ($i == 400){
                $qwery = "INSERT INTO `plano700`(`VALORCONSTANTE`, `GRUPO`, `CUENTA`, `IDCLIENTE`, `TIPOTELEFONO`, `AREACODE`, `TELEFONO`, `FONOEXTEN`, `IDEMPEX`, `VCDIAL`, `CODIGOACCION`, `RESULTADO`) VALUES ". $regs .";";
                //$Respuesta = mysqli_query($conexion ,$qwery);
                $resultados = mysql_query($qwery, $conexion) or die(mysql_error());
                //echo $qwery;
                //echo $Respuesta;
                $first = true;
                $i = 0; 
                $qwery = "";
                $regs = "";
            }
        }
        $qwery = "INSERT INTO `plano700`(`VALORCONSTANTE`, `GRUPO`, `CUENTA`, `IDCLIENTE`, `TIPOTELEFONO`, `AREACODE`, `TELEFONO`, `FONOEXTEN`, `IDEMPEX`, `VCDIAL`, `CODIGOACCION`, `RESULTADO`) VALUES ". $regs .";";
        //$Respuesta = mysqli_query($conexion ,$qwery);
        $resultados = mysql_query($qwery, $conexion) or die(mysql_error());
        //echo $qwery;
        //echo $Respuesta;
    }
    class Plano700
    {
        public  $Valorconstante = "700"; 
        public  $Grupo= "6"; 
        public  $Cuenta;
        public  $IdCliente; 
        public  $TipoTelefono; 
        public  $Areacode;
        public  $Telefono; 
        public  $Fonoexten; 
        public  $IdEmpex;
        public  $VCDIAL;     
        public  $CodigoAccion;
        public  $CodigoResultado;     
    }
    function Guardar800($listaplano, $conexion, $f2,$c){
        $regs ="";
        $first = true;
        $i = 0; 
        if (empty($listaplano)) { return; }
        if($c == 1){
            $sql = "DELETE FROM plano800 WHERE DATE_FORMAT(FECHINGRESO, '%Y%m%d') = DATE_FORMAT('{$f2}', '%Y%m%d');";
            mysql_query($sql, $conexion) or die(mysql_error());
        }
        foreach ( $listaplano AS $plano  ){
            $i++;
            if ($first == true){
                $first = false;
            }
            else{
                $regs = $regs . ",";
            }
            $regs = $regs . "(\"".$plano->Valorconstante."\",\"".$plano->Grupo."\",\"".$plano->Cuenta."\",\"".$plano->IdCliente."\",\"".$plano->TipoDirecc."\",\"".$plano->Domicilio."\",\"".$plano->Comuna."\",\"".$plano->region."\",\"".$plano->Ciudad."\",\"".$plano->DirEstado."\",\"".$plano->PostalCode."\",\"".$plano->IdEmpex."\",\"".$plano->Estado."\",\"".$plano->VCDIAL."\",\"".$plano->CodigoAccion."\",\"".$plano->CodigoResultado."\")";
            if ($i == 400){
                $qwery = "INSERT INTO `plano800`( `VALORCONSTANTE`, `GRUPO`, `CUENTA`, `IDCLIENTE`, `TIPDIRECC`, `DOMICILIO`, `COMUNA`, `REGION`, `CIUDAD`, `DIRESTADO`, `POSTALCODE`, `IDEMPREX`, `ESTADO`, `VCDIAL`, `CODIGOACCION`, `RESULTADO`) VALUES" . $regs .";";
                //$Respuesta = mysqli_query($conexion ,$qwery);$regs ="";
                $resultados = mysql_query($qwery, $conexion) or die(mysql_error());
                //echo $qwery;
                //echo $Respuesta;
                $first = true;
                $i = 0; 
                $qwery = "";
                $regs = "";
            }
        }
        $qwery = "INSERT INTO `plano800`( `VALORCONSTANTE`, `GRUPO`, `CUENTA`, `IDCLIENTE`, `TIPDIRECC`, `DOMICILIO`, `COMUNA`, `REGION`, `CIUDAD`, `DIRESTADO`, `POSTALCODE`, `IDEMPREX`, `ESTADO`, `VCDIAL`, `CODIGOACCION`, `RESULTADO`) VALUES" . $regs .";";
        //$Respuesta = mysqli_query($conexion ,$qwery);
        $resultados = mysql_query($qwery, $conexion) or die(mysql_error());
        //echo $qwery;
        //echo $Respuesta;
    }
    class Plano800
    {
        public  $Valorconstante = "800"; 
        public  $Grupo= "6"; 
        public  $Cuenta;
        public  $IdCliente; 
        public  $TipoDirecc; 
        public  $Domicilio;
        public  $Comuna;
        public  $region;
        public  $Ciudad;
        public  $DirEstado; 
        public  $PostalCode; 
        public  $IdEmpex;
        public  $Estado;
        public  $VCDIAL;   
        public  $CodigoAccion;
        public  $CodigoResultado;   
    }
	
?>