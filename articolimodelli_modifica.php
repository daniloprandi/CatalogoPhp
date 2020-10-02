<html>
<head>
<link rel='stylesheet' href='file_css.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

$id_ArticoliModelli = $_POST["id_ArticoliModelli"];
$nome_ArticoliModelli = $_POST["nome_ArticoliModelli"];
$note_ArticoliModelli = $_POST["note_ArticoliModelli"];
$attivo_ArticoliModelli = $_POST["attivo_ArticoliModelli"];
$id_ArticoliFamiglie = $_POST["id_ArticoliFamiglie"];

$query = "UPDATE ArticoliModelli SET nome_ArticoliModelli = ?, note_ArticoliModelli = ?, attivo_ArticoliModelli = ?,
id_ArticoliFamiglie = ? WHERE id_ArticoliModelli = ?";
$params = array($nome_ArticoliModelli, $note_ArticoliModelli, $attivo_ArticoliModelli, $id_ArticoliFamiglie, $id_ArticoliModelli);
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