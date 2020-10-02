<html>
<head>
<link rel='stylesheet' href='file_css.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

$query = "SELECT * FROM ArticoliFamiglie";
$params = array();
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
$res = sqlsrv_query($conn, $query, $params, $options);	
$num_rows = sqlsrv_num_rows($res);

$nome_ArticoliFamiglie = array();
$note_ArticoliFamiglie = array();
$attivo_ArticoliFamiglie = array();
$a = 0; 

while($item = sqlsrv_fetch_array($res))
{
	$id_ArticoliFamiglie[$a] = $item["id_ArticoliFamiglie"];
	$nome_ArticoliFamiglie[$a] = $item["nome_ArticoliFamiglie"];
	$note_ArticoliFamiglie[$a] = $item["note_ArticoliFamiglie"];
	$attivo_ArticoliFamiglie[$a] = $item["attivo_ArticoliFamiglie"];
	$a++;
}
?>
<table width=100% class="tabella">
	<tr>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($id_ArticoliFamiglie[$i], ENT_QUOTES);?>">ID</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($nome_ArticoliFamiglie[$i], ENT_QUOTES);?>">NOME</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($note_ArticoliFamiglie[$i], ENT_QUOTES);?>">NOTE</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($attivo_ArticoliFamiglie[$i], ENT_QUOTES);?>">ATTIVO</td>
		<td class="cella_intestazione">MODIFICA</td>
		<td class="cella_intestazione">CANCELLA</td>
	</tr>
</form>
<?php

for($i = 0; $i < $a; $i++)
{
	echo"
	<tr>
		<td class=cella_testo_centro>$id_ArticoliFamiglie[$i]</td>
		<td class=cella_valori>$nome_ArticoliFamiglie[$i]</td>
		<td class=cella_valori>$note_ArticoliFamiglie[$i]</td> 
		<td class=cella_valori>$attivo_ArticoliFamiglie[$i]</td>
		<td class=cella_testo_centro>
			<a href='articolifamiglie_ins_form.php?id_ArticoliFamiglie=$id_ArticoliFamiglie[$i]&controllo_modifica=s';>MOD</a>
		</td>
		<td class=cella_testo_centro>
			<a href='articolifamiglie_cancella.php?id_ArticoliFamiglie=$id_ArticoliFamiglie[$i]'>CANC</a>
		</td>
	</tr>
"; 
}	
echo"</table><br>";
echo "Famiglie trovate con 'a': $a.<br>Famiglie trovate con 'num_rows': $num_rows.<br>";
?>
</body>
</html>