<html>
<head>
<link rel='stylesheet' href='file_css.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

// variabili superglobali (url)
$id_Articoli = $_GET["id_Articoli"];
$controllo_modifica = $_GET["controllo_modifica"];

// INIZIALIZZO IL FORM DI INSERIMENTO (CASELLE E TEXT BOX VUOTE) 
$codice_Articoli = "";
$descrizione_Articoli = "";
$prezzo_Articoli = 0.00;
$note_Articoli = "";
$attivo_Articoli = "";
$id_ArticoliFamiglie = "";
$id_ArticoliModelli = "";

if(isset($_POST["submit"])) 
{
	$validation = true;
	
	if(isset($_POST["codice_Articoli"]) || $_POST["codice_Articoli"] === "")
		$codice_Articoli = $_POST["codice_Articoli"];
	
	if(!isset($_POST["descrizione_Articoli"]) || $_POST["descrizione_Articoli"] === "") 
	{
		$validation = false;
		echo "The field <b>'DESCRIZIONE'</b> is required<br>";
	}
	else
	{
		$descrizione_Articoli = $_POST["descrizione_Articoli"];
		$descrizione_Articoli = str_replace("'", "''", $descrizione_Articoli);
	}
	
	/* if(isset($_POST["prezzo_Articoli"]) || $_POST["codice_Articoli"] === "")
		$prezzo_Articoli = $_POST["prezzo_Articoli"]; */
		
	if(!isset($_POST["prezzo_Articoli"]) || $_POST["prezzo_Articoli"] === "") 
	{
		$validation = false;
		echo "The field <b>'PREZZO'</b> is required<br>";
	}
	else
	{
		$prezzo_Articoli = $_POST["prezzo_Articoli"];
		$prezzo_Articoli = str_replace(",", ".", $prezzo_Articoli);
	}
	
	if(isset($_POST["note_Articoli"]) || $_POST["note_Articoli"] === "")
		$note_Articoli = $_POST["note_Articoli"];
	
	if(!isset($_POST["attivo_Articoli"]) || $_POST["attivo_Articoli"] === "")
	{
		$validation = false;
		echo "- The field <b>'ATTIVO'</b> is required<br>";
	}
	else
		$attivo_Articoli = $_POST["attivo_Articoli"]; 

	if(isset($_POST["id_ArticoliFamiglie"]) || $_POST["id_ArticoliFamiglie"] === "")
		$id_ArticoliFamiglie = $_POST["id_ArticoliFamiglie"];
	
	if(isset($_POST["id_ArticoliModelli"]) || $_POST["id_ArticoliModelli"] === "")
		$id_ArticoliModelli = $_POST["id_ArticoliModelli"];

	if($validation)
	{
		$query = "SELECT * FROM Articoli WHERE codice_Articoli = '$codice_Articoli' OR descrizione_Articoli = '$descrizione_Articoli'";
		$params = array();
		$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
		$res = sqlsrv_query($conn, $query, $params, $options);	
		$num_rows = sqlsrv_num_rows($res);
		if($num_rows > 0)
			while($item = sqlsrv_fetch_array($res))
				echo "<br><br>Articolo non inserito perchè già presente nel database:<br>".$codice_Articoli." ".$descrizione_Articoli." ".$prezzo_Articoli." 
				".$note_Articoli." ".$attivo_Articoli."<br><br>";
		else
		{
			$query = "INSERT INTO Articoli(codice_Articoli, descrizione_Articoli, prezzo_Articoli, note_Articoli, attivo_Articoli, id_ArticoliFamiglie, 
			id_ArticoliModelli)VALUES(?, ?, ?, ?, ?, ?, ?)";
			$params = array($codice_Articoli, $descrizione_Articoli, $prezzo_Articoli, $note_Articoli, $attivo_Articoli, $id_ArticoliFamiglie, $id_ArticoliModelli);
			$res = sqlsrv_query($conn, $query, $params);
			$rows_affected = sqlsrv_rows_affected($res);
			
			if($rows_affected === false) // in caso di errore viene restituito FALSE 
				die(print_r(sqlsrv_errors(), true));
			elseif($rows_affected == -1) // -1 = il numero di record interessati non può essere restituito  
				echo "Nessuna info disponibile.<br />";
			else 
				echo $rows_affected." record inserito:<br />".$codice_Articoli." ".$descrizione_Articoli."<br/><br />";
			
			$query = "SELECT id_Articoli, id_ArticoliFamiglie, id_ArticoliModelli FROM Articoli WHERE codice_Articoli = '$codice_Articoli' 
			AND descrizione_Articoli = '$descrizione_Articoli'";
			$params = array();
			$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
			$res = sqlsrv_query($conn, $query, $params, $options);
			$id_Articoli = array();
			$id_ArticoliFamiglie = array();
			$id_ArticoliModelli = array();
			$a = 0;
			while($item = sqlsrv_fetch_array($res))
			{
				$id_Articoli[$a] = $item["id_Articoli"];
				$id_ArticoliFamiglie[$a] = $item["id_ArticoliFamiglie"];
			  $id_ArticoliModelli[$a] = $item["id_ArticoliModelli"];   
				$a++;
			}
			$query = "INSERT INTO Articoli_ArticoliFamiglie_ArticoliModelli(id_Articoli, id_ArticoliFamiglie, id_ArticoliModelli) 
			VALUES(?, ?, ?)";
			$params = array($id_Articoli, $id_ArticoliFamiglie, $id_ArticoliModelli);
			$res = sqlsrv_query($conn, $query, $params);
			$rows_affected = sqlsrv_rows_affected($res);
			if($rows_affected === false) // in caso di errore viene restituito FALSE
				die(print_r(sqlsrv_errors(), true));				
			elseif($rows_affected == -1) // -1 = il numero di record interessati non può essere restituito  
				echo "Nessuna info disponibile.<br />";
			else 
			{
				$codice_Articoli = "";
				$descrizione_Articoli = "";
				$prezzo_Articoli = 0.00;
				$note_Articoli = "";
				$attivo_Articoli = "";
				$id_ArticoliFamiglie = "";
				$id_ArticoliModelli = "";
				echo $rows_affected." tabella join aggiornata";	
			}		
		}
	}
}
//	questo codice viene eseguito quando voglio modificare un articolo.  Esempio di url dell'articolo specifico:
//	http://localhost/_progetti/erp/articoli_ins_form.php?id_Articoli=4&controllo_modifica=s
if($controllo_modifica == "s")
{
	$query = "SELECT * FROM Articoli WHERE id_Articoli = '$id_Articoli'";
	$params = array();
	$res = sqlsrv_query($conn, $query);
	
	while($item = sqlsrv_fetch_array($res))
	{
		$id_Articoli = $item["id_Articoli"]; 											
		$codice_Articoli = $item["codice_Articoli"];
		$descrizione_Articoli = $item["descrizione_Articoli"];
		$prezzo_Articoli = $item["prezzo_Articoli"];
		$note_Articoli = $item["note_Articoli"];
		$attivo_Articoli = $item["attivo_Articoli"];
		$id_ArticoliFamiglie = $item["id_ArticoliFamiglie"];
		$id_ArticoliModelli = $item["id_ArticoliModelli"];
	}
	echo "<form action=articoli_modifica.php method=post>";
	$inserimento_modifica = "Modifica";
	echo"<input type=hidden name=id_Articoli value=$id_Articoli>";
}
else
{
	echo "<form action=articoli_ins_form.php method=post>";
	$inserimento_modifica = "Inserimento";
}
echo "<div class=titolo_pagina>$inserimento_modifica ARTICOLO</div><br><br>";
?>
<form action="" method="post"><br>
<b>CODICE:</b> <input type="text" name="codice_Articoli" value="<?php echo htmlspecialchars($codice_Articoli, ENT_QUOTES);?>"size=10><br><br>
<b>DESCRIZIONE:</b> <input type="text" name="descrizione_Articoli" VALUE="<?php echo htmlspecialchars($descrizione_Articoli, ENT_QUOTES);?>"size=50><br><br>
<b>PREZZO:</b> <input type="text" name="prezzo_Articoli" value="<?php echo htmlspecialchars($prezzo_Articoli, ENT_QUOTES);?>"size=10><br><br>
<b>NOTE:</b><br><textarea name="note_Articoli" cols=100 rows=5><?php echo htmlspecialchars($note_Articoli, ENT_QUOTES);?></textarea><br><br>
<b>ATTIVO:</b> 
	<input type="radio" name="attivo_Articoli" value="s"<?php if($attivo_Articoli === "s") echo " checked"; ?>> SI
	<input type="radio" name="attivo_Articoli" value="n"<?php if($attivo_Articoli === "n") echo " checked"; ?>> NO<br><br>
<b>FAMIGLIA: </b>
<select name="id_ArticoliFamiglie"> 
<option value="Please select one">Please select one</option>
<?php
$query = "SELECT * FROM ArticoliFamiglie WHERE attivo_ArticoliFamiglie = 's' ORDER BY nome_ArticoliFamiglie";
$params = array();
$res = sqlsrv_query($conn, $query, $params);
/* $id_ArticoliFamiglie = array();
$nome_ArticoliFamiglie = array();
$note_ArticoliFamiglie = array();
$attivo_ArticoliFamiglie = array(); */
$a = 0;
while($item = sqlsrv_fetch_array($res))
{
	$id_ArticoliFamiglie[$a] = $item["id_ArticoliFamiglie"];
	$nome_ArticoliFamiglie[$a] = $item["nome_ArticoliFamiglie"];
	$note_ArticoliFamiglie[$a] = $item["note_ArticoliFamiglie"];
	$attivo_ArticoliFamiglie[$a] = $item["attivo_ArticoliFamiglie"];
	$a++;	
}
for($i = 0; $i < $a; $i++)
	echo "<option value=$id_ArticoliFamiglie[$i] selected>$id_ArticoliFamiglie[$i]</option>";
/* for($i = 0; $i < $a; $i++)
	echo "<option value=$nome_ArticoliFamiglie[$i] selected>$nome_ArticoliFamiglie[$i]</option>"; */
?>
</select><br><br>
<b>MODELLO: </b>
<select name="id_ArticoliModelli"> 
<option value="Please select one">Please select one</option>
<?php
$query = "SELECT * FROM ArticoliModelli WHERE attivo_ArticoliModelli = 's' ORDER BY nome_ArticoliModelli";
$params = array();
$res = sqlsrv_query($conn, $query, $params);
$id_ArticoliModelli = array();
$nome_ArticoliModelli = array();
$note_ArticoliModelli = array();
$attivo_ArticoliModelli = array();
$id_ArticoliFamiglie = array();
$a = 0;
while($item = sqlsrv_fetch_array($res))
{
	$id_ArticoliModelli[$a] = $item["id_ArticoliModelli"];
	$nome_ArticoliModelli[$a] = $item["nome_ArticoliModelli"];
	$note_ArticoliModelli[$a] = $item["note_ArticoliModelli"];
	$attivo_ArticoliModelli[$a] = $item["attivo_ArticoliModelli"];
	$id_ArticoliFamiglie[$a] = $item["id_ArticoliFamiglie"];
	$a++;
}
for($i = 0; $i < $a; $i++)
	echo "<option value=$id_ArticoliModelli[$i]>$nome_ArticoliModelli[$i]</option>";
?>
</select><br><br>
	<input type="submit" name="submit" value="Register"><br>
</form>
</body>
</html>