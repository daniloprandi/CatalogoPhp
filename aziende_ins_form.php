<html>
<head>
<link rel='stylesheet' href='file_css.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

$id_Aziende = $_GET["id_Aziende"];
$controllo_modifica = $_GET["controllo_modifica"];

// tabella AZIENDE
$ragione_sociale_Aziende = ""; $partita_iva_Aziende = ""; $codice_fiscale_Aziende = ""; 
$tipologie_Aziende = array(); // or //$tipologie_Aziende = []; // like in javascript 
$note_Aziende = ""; $attivo_Aziende = "";

// tabella INDIRIZZI
$id_IndirizziTipologie = ""; $via_Indirizzi = ""; $numero_civico_Indirizzi = ""; $cap_Indirizzi = ""; $citta_Indirizzi = ""; $provincia_Indirizzi = "";
$stato_Indirizzi = "";

if(isset($_POST["submit"]))
{
	$validation = true;
	
	if(!isset($_POST["ragione_sociale_Aziende"]) || $_POST["ragione_sociale_Aziende"] === "") 
	{
		$validation = false;
		echo "The field <b>'RAGIONE SOCIALE'</b> is required<br>";
	}
	else
	{
		$ragione_sociale_Aziende = $_POST["ragione_sociale_Aziende"];
		$ragione_sociale_Aziende = str_replace("'", "''", $ragione_sociale_Aziende);
	}
	
	if(!isset($_POST["partita_iva_Aziende"]) || $_POST["partita_iva_Aziende"] === "") 
	{
		$validation = false;
		echo "The field <b>'PARTITA IVA'</b> is required<br>";
	}
	else
		$partita_iva_Aziende = $_POST["partita_iva_Aziende"];
	
	if(isset($_POST["codice_fiscale_Aziende"]) || $_POST["codice_fiscale_Aziende"] === "")
		$codice_fiscale_Aziende = $_POST["codice_fiscale_Aziende"];
	
	if(!isset($_POST["tipologie_Aziende"]) || !is_array($_POST["tipologie_Aziende"]) || count($_POST["tipologie_Aziende"]) === 0)
	{
		$validation = false;
		echo "- The field <b>'TIPOLOGIA AZIENDA'</b> is required<br>";
	}
	else
		$tipologie_Aziende = $_POST["tipologie_Aziende"];
	
	if(isset($_POST["note_Aziende"]) || $_POST["note_Aziende"] === "")
		$note_Aziende = $_POST["note_Aziende"];
	
	if(!isset($_POST["attivo_Aziende"]) || $_POST["attivo_Aziende"] === "")
	{
		$validation = false;
		echo "- The field <b>'ATTIVO'</b> is required<br>";
	}
	else
		$attivo_Aziende = $_POST["attivo_Aziende"];
	
	if(isset($_POST["id_IndirizziTipologie"]) || $_POST["id_IndirizziTipologie"] === "")
		$id_IndirizziTipologie = $_POST["id_IndirizziTipologie"];
	
	if(!isset($_POST["via_Indirizzi"]) || $_POST["via_Indirizzi"] === "") 
	{
		$validation = false;
		echo "The field <b>'VIA'</b> is required<br>";
	}
	else
	{
		$via_Indirizzi = $_POST["via_Indirizzi"];
		$via_Indirizzi = str_replace("'", "''", $via_Indirizzi);
	}
	
	if(isset($_POST["numero_civico_Indirizzi"]) || $_POST["numero_civico_Indirizzi"] === "") 
		$numero_civico_Indirizzi = $_POST["numero_civico_Indirizzi"];
	
	if(!isset($_POST["cap_Indirizzi"]) || $_POST["cap_Indirizzi"] === "") 
	{
		$validation = false;
		echo "The field <b>'CAP'</b> is required<br>";
	}
	else
		$cap_Indirizzi = $_POST["cap_Indirizzi"];
	
	if(!isset($_POST["citta_Indirizzi"]) || $_POST["citta_Indirizzi"] === "") 
	{
		$validation = false;
		echo "The field <b>'CITTA''</b> is required<br>";
	}
	else
	{
		$citta_Indirizzi = $_POST["citta_Indirizzi"];
		$citta_Indirizzi = str_replace("'", "''", $citta_Indirizzi);
	}
	
	if(isset($_POST["provincia_Indirizzi"]) || $_POST["provincia_Indirizzi"] === "") 
		$provincia_Indirizzi = $_POST["provincia_Indirizzi"];
	
	if(isset($_POST["stato_Indirizzi"]) || $_POST["stato_Indirizzi"] === "") 
		$stato_Indirizzi = $_POST["stato_Indirizzi"];

	if($validation)
	{
		$query = "SELECT * FROM Aziende WHERE partita_iva_Aziende = '$partita_iva_Aziende'";
		$params = array();
		$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
		$res = sqlsrv_query($conn, $query, $params, $options);	
		$num_rows = sqlsrv_num_rows($res);
		$a = 0;
		while($item = sqlsrv_fetch_array($res))
		{
		 $id_Aziende = $item["id_Aziende"];
		 $ragione_sociale_Aziende = $item["ragione_sociale_Aziende"];
		 $partita_iva_Aziende = $item["partita_iva_Aziende"];
		 $codice_fiscale_Aziende = $item["codice_fiscale_Aziende"];
		 $note_Aziende = $item["note_Aziende"];
		 $attivo_Aziende = $item["attivo_Aziende"];
		 $a++;
		}		
		
		if($num_rows > 0)
		{
			echo "Aziende trovate: $num_rows. <b>'PARTITA IVA'</b> già presente nel database.<br>";
				while($item = sqlsrv_fetch_array($res))
					echo "Azienda non inserita:<br>".$ragione_sociale_Aziende." ".$partita_iva_Aziende."<br><br>";
		}
		else
		{
			$query = "INSERT INTO Aziende(ragione_sociale_Aziende, partita_iva_Aziende, codice_fiscale_Aziende, note_Aziende, attivo_Aziende) 
			VALUES(?, ?, ?, ?, ?)";
			$params = array($ragione_sociale_Aziende, $partita_iva_Aziende, $codice_fiscale_Aziende, $note_Aziende, $attivo_Aziende);
			$res = sqlsrv_query($conn, $query, $params);
			$rows_affected = sqlsrv_rows_affected($res);
			
			if($rows_affected === false) // in caso di errore viene restituito FALSE 
				die(print_r(sqlsrv_errors(), true));
			elseif($rows_affected == -1) // -1 = il numero di record interessati non può essere restituito  
				echo "Nessuna info disponibile.<br>";
			else
				echo $rows_affected." record inserito:<br />".$ragione_sociale_Aziende." ".$partita_iva_Aziende."<br/><br />";
			
			$query = "SELECT * FROM Aziende WHERE partita_iva_Aziende = '$partita_iva_Aziende'";
			$params = array();
			$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
			$res = sqlsrv_query($conn, $query, $params, $options);	
			$num_rows = sqlsrv_num_rows($res);
			$a = 0;
			
			while($item = sqlsrv_fetch_array($res))
			{
			$id_Aziende = $item["id_Aziende"];
			$ragione_sociale_Aziende = $item["ragione_sociale_Aziende"];
			$partita_iva_Aziende = $item["partita_iva_Aziende"];
			$codice_fiscale_Aziende = $item["codice_fiscale_Aziende"];
			$note_Aziende = $item["note_Aziende"];
			$attivo_Aziende = $item["attivo_Aziende"];
			$a++;
			}		
			
			$query = "SELECT * FROM [Aziende_Aziende.Tipologie] WHERE id_Aziende = '$id_Aziende'";
			$params = array();
			$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
			$res = sqlsrv_query($conn, $query, $params, $options);	
			$num_rows = sqlsrv_num_rows($res);
			
			if($num_rows > 0)
				echo "Tabella join non aggiornata<br>";
			else
			{
				for($i = 0; $i < count($tipologie_Aziende); $i++)
				{
					$query = "INSERT INTO [Aziende_Aziende.Tipologie](id_Aziende, [id_Aziende.Tipologie]) VALUES(?, ?)";
					$params = array($id_Aziende, $tipologie_Aziende[$i]);
					$res = sqlsrv_query($conn, $query, $params);
					$rows_affected = sqlsrv_rows_affected($res);
				}
					
				if($rows_affected === false) // in caso di errore viene restituito FALSE 
				die(print_r(sqlsrv_errors(), true));
				elseif($rows_affected == -1) // -1 = il numero di record interessati non può essere restituito  
					echo "Nessuna info disponibile.<br>";
				else
					echo "Tabella join aggiornata<br><br>";
			}	
			// guardare https://www.youtube.com/watch?v=rVmZXJj5lH0
			// https://www.youtube.com/watch?v=HKQeqsibcR8
			$query = "SELECT * FROM Indirizzi WHERE id_Aziende = '$id_Aziende'";
			$params = array();
			$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
			$res = sqlsrv_query($conn, $query, $params, $options);	
			$num_rows = sqlsrv_num_rows($res);
			
			if($num_rows > 0)
				echo "Tabella '<b>Indirizzi</b>' non aggiornata<br>";
			else
			{
				$query = "INSERT INTO Indirizzi (id_Aziende, [id_Indirizzi.Tipologie], via_Indirizzi, numero_civico_Indirizzi, cap_Indirizzi, citta_Indirizzi, 
				provincia_Indirizzi, stato_Indirizzi) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
				$params = array($id_Aziende, $id_IndirizziTipologie, $via_Indirizzi, $numero_civico_Indirizzi, $cap_Indirizzi, $citta_Indirizzi, $provincia_Indirizzi, 
				$stato_Indirizzi);
				$res = sqlsrv_query($conn, $query, $params);	
				$rows_affected = sqlsrv_rows_affected($res);		
				if($rows_affected === false) // in caso di errore viene restituito FALSE 
					die(print_r(sqlsrv_errors(), true));
				elseif($rows_affected == -1) // -1 = il numero di record interessati non può essere restituito  
					echo "Nessuna info disponibile.<br>";
				else
					echo "Tabella Indirizzi aggiornata<br><br>";
			}
		}	
	}
}
if($controllo_modifica == "s")
{	
	$query = "SELECT * FROM Aziende WHERE id_Aziende = '$id_Aziende'";
	$params = array();
	$res = sqlsrv_query($conn, $query);
	while($item = sqlsrv_fetch_array($res))
	{
		$id_Aziende = $item["id_Aziende"]; 											
		$ragione_sociale_Aziende = $item["ragione_sociale_Aziende"];
		$partita_iva_Aziende = $item["partita_iva_Aziende"];
		$codice_fiscale_Aziende = $item["codice_fiscale_Aziende"];
		$note_Aziende = $item["note_Aziende"];
		$attivo_Aziende = $item["attivo_Aziende"];
	}
	
	$query = "SELECT [id_Aziende.Tipologie] FROM [Aziende_Aziende.Tipologie] WHERE id_Aziende = '$id_Aziende'";
	$params = array();
	$res = sqlsrv_query($conn, $query);
	while($item = sqlsrv_fetch_array($res)) 
		$tipologie_Aziende[] = $item["id_Aziende.Tipologie"];
	
	$query = "SELECT * FROM Indirizzi WHERE id_Aziende = '$id_Aziende'";
	$params = array();
	$res = sqlsrv_query($conn, $query);
	while($item = sqlsrv_fetch_array($res)) 
	{
		$id_IndirizziTipologie = $item["id_Indirizzi.Tipologie"];
		$via_Indirizzi = $item["via_Indirizzi"];
		$numero_civico_Indirizzi = $item["numero_civico_Indirizzi"];
		$cap_Indirizzi = $item["cap_Indirizzi"];
		$citta_Indirizzi = $item["citta_Indirizzi"];
		$provincia_Indirizzi = $item["provincia_Indirizzi"];
		$stato_Indirizzi = $item["stato_Indirizzi"];
	}
	
	echo "<form action=aziende_modifica.php method=post>";
	$inserimento_modifica = "Modifica";
	echo"<input type=hidden name=id_Aziende value=$id_Aziende>";
}
else
{
	echo "<form action=aziende_ins_form.php method=post>";
	$inserimento_modifica = "Inserimento";
}
echo "<div class=titolo_pagina>$inserimento_modifica AZIENDA</div><br><br>";
?>
<form action="" method="post">
<!-- <div class="titolo_pagina">$inserimento_modifica AZIENDA</div><br><br> -->
<b>RAGIONE SOCIALE:</b> <input type="text" name="ragione_sociale_Aziende" placeholder="ragione sociale" 
value="<?php echo htmlspecialchars($ragione_sociale_Aziende, ENT_QUOTES);?>"size=50><br><br>
<b>PARTITA IVA:</b> <input type="text" name="partita_iva_Aziende" placeholder="partita iva" 
value="<?php echo htmlspecialchars($partita_iva_Aziende, ENT_QUOTES);?>" size=20><br><br>
<b>CODICE FISCALE:</b> <input type="text" name="codice_fiscale_Aziende" placeholder="codice fiscale" 
value="<?php echo htmlspecialchars($codice_fiscale_Aziende, ENT_QUOTES);?>"size=20><br><br>
<b>TIPOLOGIA AZIENDA:</b><br>
	<select name="tipologie_Aziende[]" multiple size="3">
		<option value="">Please select one or more</option>
		<option value="CL"<?php if(in_array("CL", $tipologie_Aziende)) echo " selected"; ?>>Cliente</option>
		<option value="FO"<?php if(in_array("FO", $tipologie_Aziende)) echo " selected"; ?>>Fornitore</option>
		<option value="CA"<?php if(in_array("CA", $tipologie_Aziende)) echo " selected"; ?>>Centro Assistenza</option>
	</select><br><br>
<b>NOTE:</b><br><textarea name="note_Aziende" placeholder="inserire qui maggiori informazioni..." cols=50 rows=3>
<?php echo htmlspecialchars($note_Aziende, ENT_QUOTES); ?></textarea><br><br>	
<b>ATTIVO:</b> 
	<input type="radio" name="attivo_Aziende" value="s"<?php if($attivo_Aziende === "s") echo " checked"; ?>> SI
	<input type="radio" name="attivo_Aziende" value="n"<?php if($attivo_Aziende === "n") echo " checked"; ?>> NO<br><br><br><br>
<b>TIPOLOGIA INDIRIZZO</b>
	<select name="id_IndirizziTipologie">
		<option value="">Please select one</option>
		<option value="HQ" <?php if($id_IndirizziTipologie === "HQ") echo " selected"; ?>>Sede Legale</option>
		<option value="SO" <?php if($id_IndirizziTipologie === "SO") echo " selected"; ?>>Sede Operativa</option>
		<option value="MG" <?php if($id_IndirizziTipologie === "MG") echo " selected"; ?>>Magazzino </option>
		<option value="CA" <?php if($id_IndirizziTipologie === "CA") echo " selected"; ?>>Casa </option>
		<option value="UF" <?php if($id_IndirizziTipologie === "UF") echo " selected"; ?>>Ufficio </option>
		<option value="AL" <?php if($id_IndirizziTipologie === "AL") echo " selected"; ?>>Altro </option>
	</select><br><br>
<b>INDIRIZZO</b> <input type="text" name="via_Indirizzi" placeholder="via/piazza/viale"
value="<?php echo htmlspecialchars($via_Indirizzi, ENT_QUOTES);?>"size=50>
<b>CIVICO</b> <input type="text" name="numero_civico_Indirizzi" placeholder="civ"
value="<?php echo htmlspecialchars($numero_civico_Indirizzi, ENT_QUOTES);?>"size=3><br><br>
<b>CAP</b> <input type="text" name="cap_Indirizzi" placeholder="cap"
value="<?php echo htmlspecialchars($cap_Indirizzi, ENT_QUOTES);?>"size=3>
<b>CITTA'</b> <input type="text" name="citta_Indirizzi" placeholder="città"
value="<?php echo htmlspecialchars($codice_fiscale_Aziende, ENT_QUOTES);?>"size=20>
<b>PROVINCIA</b>
	<select name="provincia_Indirizzi">
		<option value="">Please select one</option>
		<option value="AT" <?php if($provincia_Indirizzi === "AT") echo  " selected"; ?>> AT </option>
		<option value="BG" <?php if($provincia_Indirizzi === "BG") echo  " selected"; ?>> BG </option>
		<option value="BS" <?php if($provincia_Indirizzi === "BS") echo  " selected"; ?>> BS </option>
		<option value="CN" <?php if($provincia_Indirizzi === "CN") echo  " selected"; ?>> CN </option>
		<option value="FI" <?php if($provincia_Indirizzi === "FI") echo  " selected"; ?>> FI </option>
		<option value="GE" <?php if($provincia_Indirizzi === "GE") echo  " selected"; ?>> GE </option>
		<option value="IM" <?php if($provincia_Indirizzi === "IM") echo  " selected"; ?>> IM </option>
		<option value="LO" <?php if($provincia_Indirizzi === "LO") echo  " selected"; ?>> LO </option>
		<option value="MB" <?php if($provincia_Indirizzi === "MB") echo  " selected"; ?>> MB </option>
		<option value="MI" <?php if($provincia_Indirizzi === "MI") echo  " selected"; ?>> MI </option>
		<option value="PO" <?php if($provincia_Indirizzi === "PO") echo  " selected"; ?>> PO </option>
		<option value="RM" <?php if($provincia_Indirizzi === "RM") echo  " selected"; ?>> RM </option>
		<option value="SO" <?php if($provincia_Indirizzi === "SO") echo  " selected"; ?>> SO </option>
		<option value="SP" <?php if($provincia_Indirizzi === "SP") echo  " selected"; ?>> SP </option>
		<option value="SV" <?php if($provincia_Indirizzi === "SV") echo  " selected"; ?>> SV </option>
		<option value="TO" <?php if($provincia_Indirizzi === "TO") echo  " selected"; ?>> TO </option>
		<option value="VE" <?php if($provincia_Indirizzi === "VE") echo  " selected"; ?>> VE </option>
	</select>
<b>STATO</b>
	<select name="stato_Indirizzi">
		<option value="">Please select one</option>
		<option value="IT" <?php if($stato_Indirizzi === "IT") echo " selected"; ?>> ITALIA </option>
		<option value="AU" <?php if($stato_Indirizzi === "AU") echo " selected"; ?>> AUSTRIA </option>
		<option value="CH" <?php if($stato_Indirizzi === "CH") echo " selected"; ?>> SVIZZERA </option>
		<option value="DE" <?php if($stato_Indirizzi === "DE") echo " selected"; ?>> GERMANIA </option>
		<option value="FR" <?php if($stato_Indirizzi === "FR") echo " selected"; ?>> FRANCIA </option>
		<option value="ES" <?php if($stato_Indirizzi === "ES") echo " selected"; ?>> SPAGNA </option>
		<option value="SE" <?php if($stato_Indirizzi === "SE") echo " selected"; ?>> SVEZIA </option>
	</select><br><br><br><br>
	<input type="submit" name="submit" value="Register"><br>
</form>
</body>
</html>