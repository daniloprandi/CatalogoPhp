<head>
<link rel='stylesheet' href='stile_base.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php 
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

$id_ArticoliModelli = $_GET["id_ArticoliModelli"];
$attivo_ArticoliModelli = $_GET["id_ArticoliModelli"];

$query = "UPDATE ArticoliModelli SET attivo_ArticoliModelli = 'n' FROM ArticoliModelli WHERE id_ArticoliModelli = '$id_ArticoliModelli'";
$params = array($attivo_ArticoliModelli, $id_ArticoliModelli);
$res = sqlsrv_query($conn, $query, $params);
$rows_affected = sqlsrv_rows_affected($res);

if($rows_affected > 0)
	echo "CANCELLATO";
else
	echo"ERRORE";
?>
</body>