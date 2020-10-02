<html>
<head>
<link rel='stylesheet' href='file_css.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

$id_Articoli = $_POST["id_Articoli"];
$codice_Articoli = $_POST["codice_Articoli"];
if($codice_Articoli == "")$codice_Articoli = "";
$descrizione_Articoli = $_POST["descrizione_Articoli"];
if($descrizione_Articoli == "")$descrizione_Articoli = "";
$prezzo_Articoli = $_POST["prezzo_Articoli"];
$prezzo_Articoli =  str_replace(",", ".", $prezzo_Articoli);
$note_Articoli = $_POST["note_Articoli"];
$attivo_Articoli = $_POST["attivo_Articoli"];
/* if($attivo_Articoli != "")
	$attivo_Articoli = "s";		// importante
else
	$attivo_Articoli = "n"; */


$query = "UPDATE Articoli SET codice_Articoli= ?, descrizione_Articoli = ?, prezzo_Articoli = ?, note_Articoli = ?, attivo_Articoli = ? 
WHERE id_Articoli = ?";
$params = array($codice_Articoli, $descrizione_Articoli, $prezzo_Articoli, $note_Articoli, $attivo_Articoli, $id_Articoli);
$res = sqlsrv_query($conn, $query, $params);
$rows_affected = sqlsrv_rows_affected($res);
// comando che estrapola l'id per capire se è stato inserito
if($rows_affected > 0)
	echo "MODIFICATO";
else
	echo"ERRORE";
?>