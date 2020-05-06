<?php 
	session_start();
	require 'includes/header_start.php'; 
	require 'includes/header_end.php'; 
	require 'mail.php'; 
	require_once("/modelo/consultaSQL.php");
	require_once("/modelo/conectarBD.php");
	require_once("/PHPExcel.php");
	require_once("controlador/script_general.php");
	
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //$conexion = mysqli_connect("localhost","root","12345678","servicobranza");
    //if(!$conexion){
    //    echo "error al conectarse a la base de datos";
    //}
   // else {
        //echo "conexion exitosa";
    //}
    $tipo = $_FILES['file']['type'];
    $error = $_FILES['file']['error'];
    $name       = $_FILES['file']['name'];  
    $temp_name  = $_FILES['file']['tmp_name'];  
    //echo $tipo;
    //echo $temp_name;
    $lineas = array();
    $fp = fopen($temp_name, "rb");
    while (!feof($fp)){
        array_push($lineas,fgets($fp));
    }
    fclose($fp);
    $matriz = array();
    foreach ($lineas as $lineaarray){
        $parts = preg_split('/\t/', $lineaarray);
        if ($parts[0] == "lead_id" ||$parts[0] == "") continue;
        $reg = llenartrans($parts);
        array_push($matriz,$reg);
        //echo $lineaarray . "<br>";
    }
    GenerarPlano200($matriz, $conexion);
    GenerarPlano600($matriz, $conexion);
    GenerarPlano700($matriz, $conexion);
    GenerarPlano800($matriz, $conexion);
    mysqli_close($conexion);
}

function  GenerarPlano200($transacciones, $conexion)
    {
        try
        {
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
                    continue;
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
                if (strlen($item->Observaciones) == ""){}
                $plano->Comentario= str_pad($item->Descripcion,56," ", STR_PAD_RIGHT);
                if (strlen($item->Telefono) == ""){}
                $plano->telefono= substr($item->Telefono,8);
                if (strlen($item->Gestor) == ""){}
                $plano->IdGestor= strtoupper(str_pad($item->Gestor, 8, " ", STR_PAD_RIGHT));

                $plano->VCDIAL = $item->Status;   
                array_push($listaplanos, $plano);

            }
            Guardar200($listaplanos, $conexion);
        }
        catch (Exception $ex)
        {
            // log.Error("GenerarPlano200", $ex);
            // throw;
        }
    
    }
    function GenerarPlano600($transacciones, $conexion)
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
                $plano->Fecha= $item->Fecha;
                $plano->Promno = "000";
                $plano->Promai = "000";
                $plano->FechaVencProm = $item->FechaCompromiso;
                $plano->PromMonto = "000000000000000";
                $plano->VCDIAL = $item->Status;
                $plano->CodigoAccion= $item->CodigoAccion;
                $plano->CodigoResultado= $item->CodigoResultado;
                array_push($listaplanos, $plano);
            }
                Guardar600($listaplanos, $conexion);
        }
        catch (Exception $ex)
        {
            //log.Error("GenerarPlano600", ex);
            //throw;
        }
    }
    
    function GenerarPlano700($transacciones, $conexion)
    {
        try
        {
            $listaplanos = array();
            $i = 2;
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
                Guardar700($listaplanos, $conexion);
        }
        catch (Exception $ex)
        {
            //log.Error("GenerarPlano700", ex);
           // throw;
        }
    }
    function GenerarPlano800($transacciones, $conexion)
    {
        try
        {
            $listaplanos = array();
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
                Guardar800($listaplanos, $conexion);
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
        $t->Cuenta = $reg[17];
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
        switch ($reg){
        case "A": 
            $c->CodigoAccion = "LN" ;
            $c->CodGestion ="TP";
            $c->Descrip ="TELEFONO OCUPADO";
        break;
        case "ADC":
            $c->CodigoAccion =  "LN" ;
            $c->CodGestion ="TN";
            $c->Descrip ="TELEFONO NO CONTESTA";
        break;
        case "B": 
            $c->CodigoAccion =  "LT" ;
            $c->CodGestion ="IT";
            $c->Descrip ="INDICA QUE REALIZO REPACTACION";
        break;
        case "CALLBK": 
            $c->CodigoAccion =  "L3" ;
            $c->CodGestion ="LT";
            $c->Descrip ="LLAMAR MAS TARDE";
        break;
        case "CBHOLD": 
            $c->CodigoAccion =  "L3" ;
            $c->CodGestion ="LT";
            $c->Descrip ="LLAMAR MAS TARDE";
        break;
        case "DC": 
            $c->CodigoAccion =  "L3" ;
            $c->CodGestion ="TC";
            $c->Descrip ="TELEFONO NO CORRESPONDE";
        break;
        case "DEC": 
            $c->CodigoAccion =  "L3" ;
            $c->CodGestion ="IR";
            $c->Descrip ="INDICA QUE REALIZO REPACTACION";
        break;
        case "DNC": 
            $c->CodigoAccion =  "LN" ;
            $c->CodGestion ="TV";
            $c->Descrip ="TELEFONO VACANTE";
        break;
        case "DROP": 
            $c->CodigoAccion =  "LF" ;
            $c->CodGestion ="LT";
            $c->Descrip ="LLAMAR MAS TARDE";
        break;
        case "ERI": 
            $c->CodigoAccion =  "LF" ;
            $c->CodGestion ="DR";
            $c->Descrip ="SE DEJA RECADO";
        break;
        case "INCALL": 
            $c->CodigoAccion =  "LN" ;
            $c->CodGestion ="TN";
            $c->Descrip ="TELEFONO NO CONTESTA";
        break;
        case "LSMERG": 
            $c->CodigoAccion =  "LT" ;
            $c->CodGestion ="CL";
            $c->Descrip ="CLIENTE CONCURRIRA A OFICINA CLA";
        break;
        case "N": 
            $c->CodigoAccion =  "LT" ;
            $c->CodGestion ="IA";
            $c->Descrip ="INDICA QUE ABONO";
        break;
        case "NA": 
            $c->CodigoAccion =  "LN" ;
            $c->CodGestion ="NT";
            $c->Descrip ="TELEFONO NO CONTESTA";
        break;
        case "NEW": 
            $c->CodigoAccion =  "LN" ;
            $c->CodGestion ="NT";
            $c->Descrip ="TELEFONO NO CONTESTA";
        break;
        case "IN": 
            $c->CodigoAccion =  "LT" ;
            $c->CodGestion ="NP";
            $c->Descrip ="NO QUIERE O NO PUEDE PAGAR";
        break;
        case "NP": 
            $c->CodigoAccion =  "LN" ;
            $c->CodGestion ="NT";
            $c->Descrip ="TELEFONO NO CONTESTA";
        break;
        case "PM": 
            $c->CodigoAccion =  "CL" ;
            $c->CodGestion ="LT";
            $c->Descrip ="CLIENTE CONCURRIRA A OFICINA CLA";
        break;
        case "SALE": 
            $c->CodigoAccion =  "LT" ;
            $c->CodGestion ="PP";
            $c->Descrip ="COMPROMISO DE PAGO";
        break;
        case "XFER": 
            $c->CodigoAccion =  "LT" ;
            $c->CodGestion ="RN";
            $c->Descrip ="RECLAMO POR NORMALIZAR";
        break;
        case "YP": 
            $c->CodigoAccion =  "LT" ;
            $c->CodGestion ="IA";
            $c->Descrip ="INDICA QUE ABONO";
        break;
        }
        return $c;
    }
    class codes{
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

    function Guardar200( $listaplano, $conexion){
        $regs ="";
        $first = true;
        $i = 0; 
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
                //$Respuesta = mysqli_query($conexion ,$qwery);
                $resultados = mysql_query($qwery, $conexion) or die(mysql_error());
                //echo $Respuesta;
                $first = true;
                $i = 0; 
                $qwery = "";
                $regs = "";
            }
        }
        $qwery = "INSERT INTO `plano200`( `VALORCONSTATE`, `GRUPO`, `CUENTA`, `FECHA`, `HORA`, `SECUENCIA`, `CODIGOACCION`, `RESULTADO`, `CODIGOCARTA`, `IDEMPEX`, `COMENTARIO`, `TELEFONO`, `IDGESTOR`, `VCDIAL`) VALUES" . $regs .";";
        //$Respuesta = mysqli_query($conexion ,$qwery);
                $resultados = mysql_query($qwery, $conexion) or die(mysql_error());
        //echo $Respuesta;
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
    function Guardar600($listaplano, $conexion){
        $regs ="";
        $first = true;
        $i = 0; 
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
    
    function Guardar700($listaplano, $conexion){
        $regs ="";
        $first = true;
        $i = 0; 
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
    function Guardar800($listaplano, $conexion){
        $regs ="";
        $first = true;
        $i = 0; 
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


<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->

<form action="?" method="post" enctype="multipart/form-data">
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h3 class="page-title">Archivos Extrajudiciales Cargar</h3>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

        	<div class="row">
            	<div class="col-sm-12">
					<div class="card">
						<div class="card-header">Cargar Archivo</div>						
							<div class="card-block">
								<div class="row">
									<div class="form-group col-sm-6">
											<label>Archivo</label><br>
            								<input type="file" id="file" name="file" /><br>
									</div>

								</div>
								<div class="row">
									<div class="form-group col-sm-6">
											<button type="submit" class="btn btn-rounded btn-primary">Subir</button>
											<button type="button" class="btn btn-rounded btn-danger" onClick="location='principal.php'">Volver</button>
									</div>
								</div>
							</div>
						</div>
        			</div>
				</div>	
			</div>
			<!-- end row -->
        </div> <!-- container -->
    </div> <!-- content -->
</div>
<!-- End content-page -->
</form>

<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php require 'includes/footer_start.php' ?>
<!-- extra js -->
<?php require 'includes/footer_end.php' ?>

<script type="text/javascript">

</script>