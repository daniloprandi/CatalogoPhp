<head>
<link rel='stylesheet' href='stile_base.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php 
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

$id_ArticoliFamiglie = $_GET["id_ArticoliFamiglie"];
$attivo_ArticoliFamiglie = $_GET["id_ArticoliFamiglie"];

$query = "UPDATE ArticoliFamiglie SET attivo_ArticoliFamiglie = 'n' FROM ArticoliFamiglie WHERE id_ArticoliFamiglie = '$id_ArticoliFamiglie'";
$params = array($attivo_ArticoliFamiglie, $id_ArticoliFamiglie);
$res = sqlsrv_query($conn, $query, $params);
$rows_affected = sqlsrv_rows_affected($res);

if($rows_affected > 0)
	echo "CANCELLATO";
else
	echo"ERRORE";
?>
</body>