<html>
<head>
<link rel='stylesheet' href='file_css.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

$id_ArticoliFamiglie = $_POST["id_ArticoliFamiglie"];
$nome_ArticoliFamiglie = $_POST["nome_ArticoliFamiglie"];
$note_ArticoliFamiglie = $_POST["note_ArticoliFamiglie"];
$attivo_ArticoliFamiglie = $_POST["attivo_ArticoliFamiglie"];

$query = "UPDATE ArticoliFamiglie SET nome_ArticoliFamiglie = ?, note_ArticoliFamiglie = ?, attivo_ArticoliFamiglie = ? WHERE id_ArticoliFamiglie = ?";
$params = array($nome_ArticoliFamiglie, $note_ArticoliFamiglie, $attivo_ArticoliFamiglie, $id_ArticoliFamiglie);
$res = sqlsrv_query($conn, $query, $params);
$rows_affected = sqlsrv_rows_affected($res);
// comando che estrapola l'id per capire se è stato inserito
if($rows_affected > 0)
	echo "MODIFICATO";
else
	echo"ERRORE";
?>
</body>
</html>