<html>
<head>
<link rel='stylesheet' href='file_css.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

$query = "SELECT id_Aziende, ragione_sociale_Aziende, partita_iva_Aziende, codice_fiscale_Aziende, note_Aziende, attivo_Aziende FROM Aziende";
$params = array();
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
$res = sqlsrv_query($conn, $query, $params, $options);	
$num_rows = sqlsrv_num_rows($res);
$a = 0;
while($item = sqlsrv_fetch_array($res))
{
	$id_Aziende[$a] = $item["id_Aziende"];
	$ragione_sociale_Aziende[$a] = $item["ragione_sociale_Aziende"];
	$partita_iva_Aziende[$a] = $item["partita_iva_Aziende"];
	$codice_fiscale_Aziende[$a] = $item["codice_fiscale_Aziende"];
	$note_Aziende[$a] = $item["note_Aziende"];
	$attivo_Aziende[$a] = $item["attivo_Aziende"];
	$a++;
}
?>
<table width=100% class="tabella">
	<tr>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($id_Aziende[$i], ENT_QUOTES);?>">ID</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($ragione_sociale_Aziende[$i], ENT_QUOTES);?>">RAGIONE SOCIALE</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($partita_iva_Aziende[$i], ENT_QUOTES);?>">PARTITA IVA</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($codice_fiscale_Aziende[$i], ENT_QUOTES);?>">CODICE FISCALE</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($note_Aziende[$i], ENT_QUOTES);?>">NOTE</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($attivo_Aziende[$i], ENT_QUOTES);?>">ATTIVO</td>
		<td class="cella_intestazione">MODIFICA</td>
		<td class="cella_intestazione">CANCELLA</td>
	</tr>
</form>
<?php

for($i = 0; $i < $a; $i++)
{
	echo"
	<tr>
		<td class=cella_testo_centro>$id_Aziende[$i]</td>
		<td class=cella_valori>$ragione_sociale_Aziende[$i]</td>
		<td class=cella_valori>$partita_iva_Aziende[$i]</td> 
		<td class=cella_valori>$codice_fiscale_Aziende[$i]</td>
		<td class=cella_valori>$note_Aziende[$i]</td>
		<td class=cella_valori>$attivo_Aziende[$i]</td>
		<td class=cella_testo_centro>
			<a href='aziende_ins_form.php?id_Aziende=$id_Aziende[$i]&controllo_modifica=s';>MOD</a>
		</td>
		<td class=cella_testo_centro>
			<a href='aziende_cancella.php?id_Aziende=$id_Aziende[$i]'>CANC</a>
		</td>
	</tr>
"; 
}	
echo"</table><br>";
echo "Aziende trovate con 'a': $a.<br>Aziende trovate con 'num_rows': $num_rows.<br>";
?>
</body>
</html>