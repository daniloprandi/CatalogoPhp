<html>
<head>
<link rel='stylesheet' href='file_css.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

$query = "SELECT id_ArticoliModelli, nome_ArticoliModelli, note_ArticoliModelli, attivo_ArticoliModelli, ArticoliFamiglie.nome_ArticoliFamiglie
FROM ArticoliModelli INNER JOIN ArticoliFamiglie ON ArticoliModelli.id_ArticoliFamiglie = ArticoliFamiglie.id_ArticoliFamiglie ORDER BY 
attivo_ArticoliModelli DESC, nome_ArticoliModelli";

$params = array();
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
$res = sqlsrv_query($conn, $query, $params, $options);	
$num_rows = sqlsrv_num_rows($res);

$id_ArticoliModelli = array();
$nome_ArticoliModelli = array();
$note_ArticoliModelli = array();
$attivo_ArticoliModelli = array();
$nome_ArticoliFamiglie = array();
$a = 0; 

while($item = sqlsrv_fetch_array($res))
{
	$id_ArticoliModelli[$a] = $item["id_ArticoliModelli"];
	$nome_ArticoliModelli[$a] = $item["nome_ArticoliModelli"];
	$note_ArticoliModelli[$a] = $item["note_ArticoliModelli"];
	$attivo_ArticoliModelli[$a] = $item["attivo_ArticoliModelli"];
	$nome_ArticoliFamiglie[$a] = $item["nome_ArticoliFamiglie"];
	$a++;
}
?>
<table width=100% class="tabella">
	<tr>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($id_ArticoliModelli[$i], ENT_QUOTES);?>">ID</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($nome_ArticoliModelli[$i], ENT_QUOTES);?>">NOME</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($note_ArticoliModelli[$i], ENT_QUOTES);?>">NOTE</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($attivo_ArticoliModelli[$i], ENT_QUOTES);?>">ATTIVO</td>
		<td class="cella_intestazione" value="<?php echo htmlspecialchars($nome_ArticoliFamiglie[$i], ENT_QUOTES);?>">FAMIGLIA</td>
		<td class="cella_intestazione">MODIFICA</td>
		<td class="cella_intestazione">CANCELLA</td>
	</tr>
</form>
<?php

for($i = 0; $i < $a; $i++)
{
	echo"
	<tr>
		<td class=cella_testo_centro>$id_ArticoliModelli[$i]</td>
		<td class=cella_valori>$nome_ArticoliModelli[$i]</td>
		<td class=cella_valori>$note_ArticoliModelli[$i]</td> 
		<td class=cella_valori>$attivo_ArticoliModelli[$i]</td>
		<td class=cella_valori>$nome_ArticoliFamiglie[$i]</td>
		<td class=cella_testo_centro>
			<a href='articolimodelli_ins_form.php?id_ArticoliModelli=$id_ArticoliModelli[$i]&controllo_modifica=s';>MOD</a>
		</td>
		<td class=cella_testo_centro>
			<a href='articolimodelli_cancella.php?id_ArticoliModelli=$id_ArticoliModelli[$i]'>CANC</a>
		</td>
	</tr>
	"; 
}	
echo"</table><br>";
echo "Modelli trovati con 'a': $a.<br>Modelli trovati con 'num_rows': $num_rows.<br>";
?>
</body>
</html>