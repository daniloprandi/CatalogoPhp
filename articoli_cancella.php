<head>
<link rel='stylesheet' href='stile_base.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php 
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);
$id_Articoli = $_GET["id_Articoli"];
$attivo_Articoli = $_GET["id_Articoli"];
$query = "UPDATE Articoli SET attivo_Articoli = 'n' FROM Articoli WHERE id_Articoli = '$id_Articoli'";
$params = array($attivo_Articoli, $id_Articoli);
$res = sqlsrv_query($conn, $query, $params);
$rows_affected = sqlsrv_rows_affected($res);
if($rows_affected > 0)
	echo "CANCELLATO";
else
	echo"ERRORE";
?>
</body>