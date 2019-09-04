<?php

ini_set('memory_limit','512M');
error_reporting(0);
date_default_timezone_set('America/Santiago');
session_start();

require 'vendor/autoload.php';

include("modelo/conectarBD.php");

?>
<pre>
<?php

// Informacion de Juicios

// No enviado TXT -> CESENDDT = 0
$sql = "SELECT * FROM op_info_juicios WHERE CESENDDT = 0";
$q = mysql_query($sql, $conexion) or die(mysql_error());
$rows = mysql_num_rows($q);

echo "\nInformación de Juicios\n";
echo "--------------------------------------------------------------------\n\n";

// [id] => 9979
// [IDENTIFICADOR] => 902
// [CEDOSSIERID] => C-12289-2013
// [CNCASENO] => 238404
// [CESSNUM] => 151328164
// [CECOMM] => 
// [CECRTID] => 17JUZCIVSANTI
// [CELASTAC] => IJ
// [CELASTRC] => MA
// [CELSTACT] => 0000-00-00
// [CELSTCON] => 0000-00-00
// [CELWSTDT] => 2018-11-21
// [CETYPE] => EJCPMM1
// [CESENDDT] => 0000-00-00
// [USUSUARIO] => KNOCK

while($row = mysql_fetch_assoc($q)) { 
    print_r($row);
}

// Etapas Procesales

// [id] => 17302
// [CSCASENO] => 448564
// [CSTYPE] => EJCPMM-5
// [CSSTGID] => 2
// [CSSTDT] => 2018-09-17
// [CSENDDT] => 2018-10-10
// [CSSNDT] => 0000-00-00
// [USUSUARIO] => AALVAREZ
// [CT_DESC] => Juicio ejecutivo de cobro MM$1.000 - MM$5.000
// [CD_DESC] => Notificacion de la demanda

echo "\nEtapas Procesales\n";
echo "--------------------------------------------------------------------\n\n";

$sql = "SELECT a.*, b.CT_DESC, c.CD_DESC ";
$sql .= "FROM ";
$sql .= "    op_eta_proce a, ";
$sql .= "    tipos_juicio b, ";
$sql .= "    etapas_procesales c ";
$sql .= "WHERE a.CSSNDT = 0 ";
$sql .= "    AND (a.CSTYPE=b.CT_TYPE) ";
$sql .= "    AND (a.CSTYPE=c.CD_TYPE) ";
$sql .= "    AND (a.CSSTGID=c.CD_STGID)";

$q = mysql_query($sql, $conexion) or die(mysql_error());
$rows = mysql_num_rows($q);

while($row = mysql_fetch_assoc($q)) { 
    print_r($row);
}

// "200" Gestiones

// [id] => 36341
// [CONSTANTE] => 200
// [ACACCT] => J448564
// [DATE] => 2018-10-10
// [ACSEQNUM] => 2
// [ACACCODE] => EI
// [ACRCCODE] => DI
// [ACLCCODE] => EP
// [ACCIDMAN] => SERVICOB
// [ACCOMN] => DEUDOR NOT. ART. 44
// [ACPHONE] => 
// [ACEXT] => 
// [ACSNDATE] => 0000-00-00
// [USUSUARIO] => AALVAREZ

echo "\n\"200\" Gestiones\n";
echo "--------------------------------------------------------------------\n\n";


$sql = "SELECT * FROM op_200_gestiones  WHERE ACSNDATE = 0";

$q = mysql_query($sql, $conexion) or die(mysql_error());
$rows = mysql_num_rows($q);

while($row = mysql_fetch_assoc($q)) { 
    print_r($row);
}

// Gastos Judiciales

// [id] => 13389
// [CSCASENO] => 448564
// [CETYPE] => EJCPMM-5
// [EXSTGID] => 2
// [EXSUPPLIER] => Nestor Zamora
// [EXINVOICE] => 
// [EXAGENCY] => SERVICOB
// [EXAMT] => 30000
// [EXAUTDT] => 2018-10-10
// [EXCOLLID] => SBERTERO
// [EXCOLSUP] => SBERTERO
// [EXDESC] => Notificacion Art. 44 y Requerimiento de Pago
// [EXIDESC] => 
// [EXTYPE] => EXPTYPE1
// [EXSTYPE] => EXP1G
// [EXSENDDT] => 0000-00-00
// [USUSUARIO] => AALVAREZ

echo "\nGastos Judiciales\n";
echo "--------------------------------------------------------------------\n\n";

$sql = "SELECT * FROM op_gastos WHERE EXSENDDT = 0";

$q = mysql_query($sql, $conexion) or die(mysql_error());
$rows = mysql_num_rows($q);

while($row = mysql_fetch_assoc($q)) { 
    print_r($row);
}

?>
<pre>