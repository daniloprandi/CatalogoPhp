<html>
<head>
<link rel='stylesheet' href='file_css.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

echo"
<b>TIPOLOGIA INDIRIZZO</b>
	<select name=tipologia_Indirizzi>
		<option>Please select one</option>
		<option value=hq>Sede</option>
		<option value=ma>Magazzino</option>
		<option value=ca>Casa</option>
		<option value=uf>Ufficio</option>
		<option value=al>Altro</option>
	</select><br><br>
<b>INDIRIZZO</b> <input type=text name=via_Indirizzi placeholder=via/piazza/viale size=100>
<b>CIVICO</b> <input type=text name=numero_civico_Indirizzi placeholder=civ size=5>
<b>CAP</b> <input type=text name=cap_Indirizzi placeholder=cap size=5><br><br>
<b>CITTA'</b> <input type=text name=citta_Indirizzi placeholder=città size=20>
<b>PROVINCIA</b>
	<select name=provincia_Indirizzi>
		<option>Please select one</option>
		<option value=at>AT</option>
		<option value=bg>BG</option>
		<option value=bs>BS</option>
		<option value=cn>CN</option>
		<option value=fi>FI</option>
		<option value=ge>GE</option>
		<option value=im>IM</option>
		<option value=lo>LO</option>
		<option value=mb>MB</option>
		<option value=mi>MI</option>
		<option value=po>PO</option>
		<option value=rm>RM</option>
		<option value=so>SO</option>
		<option value=sp>SP</option>
		<option value=sv>SV</option>
		<option value=to>TO</option>
		<option value=ve>VE</option>
	</select>
<b>STATO</b>
	<select name=stato_Indirizzi>
		<option>Please select one</option>
		<option value=it>ITALIA</option>
		<option value=au>AUSTRIA</option>
		<option value=ch>SVIZZERA</option>
		<option value=de>GERMANIA</option>
		<option value=fr>FRANCIA</option>
		<option value=es>SPAGNA</option>
		<option value=se>SVEZIA</option>
";
?>
</body>
</html>