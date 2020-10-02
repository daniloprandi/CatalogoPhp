<html>
<head>
<link rel='stylesheet' href='file_css.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php
include_once("connessione_sqlsrv.php");
include_once("menu.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

$query = "SELECT id_Articoli, codice_Articoli, descrizione_Articoli, prezzo_Articoli, note_Articoli, attivo_Articoli, nome_ArticoliFamiglie, nome_ArticoliModelli
FROM Articoli INNER JOIN ArticoliFamiglie ON Articoli.id_ArticoliFamiglie = ArticoliFamiglie.id_ArticoliFamiglie INNER JOIN ArticoliModelli 
ON Articoli.id_ArticoliModelli = ArticoliModelli.id_ArticoliModelli ORDER BY attivo_Articoli DESC, descrizione_Articoli";
$params = array();
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
$res_query = sqlsrv_query($conn, $query, $params, $options);	
$res_query_num_rows = sqlsrv_num_rows($res_query);

$id_Articoli = array();
$codice_Articoli = array();
$descrizione_Articoli = array();
$prezzo_Articoli = array();
$note_Articoli = array();
$attivo_Articoli = array();
$nome_ArticoliFamiglie = array();
$nome_ArticoliModelli = array();
$a = 0; 

while($item = sqlsrv_fetch_array($res_query))
{
	$id_Articoli[$a] = $item["id_Articoli"];
	$codice_Articoli[$a] = $item["codice_Articoli"];
	$descrizione_Articoli[$a] = $item["descrizione_Articoli"];
	$prezzo_Articoli[$a] = $item["prezzo_Articoli"];
	$note_Articoli[$a] = $item["note_Articoli"];
	$attivo_Articoli[$a] = $item["attivo_Articoli"];
	$nome_ArticoliFamiglie[$a] = $item["nome_ArticoliFamiglie"];
	$nome_ArticoliModelli[$a] = $item["nome_ArticoliModelli"];
	$a++;
}
?>
<table width=100% class=tabella>
	<tr>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($id_Articoli[i], ENT_QUOTES);?>">ID</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($codice_Articoli[i], ENT_QUOTES);?>">CODICE</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($descrizione_Articoli[i], ENT_QUOTES);?>">DESCRIZIONE</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($prezzo_Articoli[i], ENT_QUOTES);?>">PREZZO</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($note_Articoli[i], ENT_QUOTES);?>">NOTE</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($attivo_Articoli[i], ENT_QUOTES);?>">ATTIVO</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($nome_ArticoliFamiglie[i], ENT_QUOTES);?>">FAMIGLIA</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($nome_ArticoliModelli[i], ENT_QUOTES);?>">MODELLO</td>
		<td class="cella_intestazione">MODIFICA</td>
		<td class="cella_intestazione">CANCELLA</td>
	</tr>
</form>
<?php

for($i = 0; $i < $a; $i++)
{
	echo"
	<tr>
		<td class=cella_testo_centro>$id_Articoli[$i]</td>
		<td class=cella_valori>$codice_Articoli[$i]</td>
		<td class=cella_valori>$descrizione_Articoli[$i]</td> 
		<td class=cella_testo_centro>$prezzo_Articoli[$i]</td>
		<td class=cella_valori>$note_Articoli[$i]</td>
		<td class=cella_testo_centro>$attivo_Articoli[$i]</td>
		<td class=cella_testo_centro>$nome_ArticoliFamiglie[$i]</td>
		<td class=cella_testo_centro>$nome_ArticoliModelli[$i]</td>
		<td class=cella_testo_centro>
			<a href='articoli_ins_form.php?id_Articoli=$id_Articoli[$i]&controllo_modifica=s';>MOD</a>
		</td>
		<td class=cella_testo_centro>
			<a href='articoli_cancella.php?id_Articoli=$id_Articoli[$i]'>CANC</a>
		</td>
	</tr>
"; 
}	
echo"</table><br>";
echo "Articoli trovati con 'a': $a.<br>Articoli trovati con 'res_query_num_rows': $res_query_num_rows.<br>";
?>
</body>
</html>