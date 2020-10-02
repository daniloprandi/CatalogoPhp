<html>
<head>
<link rel='stylesheet' href='file_css.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

//variabili superglobali (url)
$id_ArticoliFamiglie = $_GET["id_ArticoliFamiglie"];
$controllo_modifica = $_GET["controllo_modifica"];

// inizializzazione variabili per inserimento nel form
$nome_ArticoliFamiglie = "";
$note_ArticoliFamiglie = "";
$attivo_ArticoliFamiglie = "";

if(isset($_POST["submit"])) 
{
	$validation = true;
	
	if(!isset($_POST["nome_ArticoliFamiglie"]) || $_POST["nome_ArticoliFamiglie"] === "") 
	{
		$validation = false;
		echo "The field <b>'NOME FAMIGLIA'</b> is required<br>";
	}
	else
	{
		$nome_ArticoliFamiglie = $_POST["nome_ArticoliFamiglie"];
		$nome_ArticoliFamiglie = str_replace("'", "''", $nome_ArticoliFamiglie);
	}
	
	if(isset($_POST["note_ArticoliFamiglie"]) || $_POST["note_ArticoliFamiglie"] === "")
	{
		$note_ArticoliFamiglie = $_POST["note_ArticoliFamiglie"];
		$note_ArticoliFamiglie = str_replace("'", "''", $note_ArticoliFamiglie);
	}
	
	if(!isset($_POST["attivo_ArticoliFamiglie"]) || $_POST["attivo_ArticoliFamiglie"] === "")
	{
		$validation = false;
		echo "- The field <b>'ATTIVO'</b> is required<br>";
	}
	else
		$attivo_ArticoliFamiglie = $_POST["attivo_ArticoliFamiglie"]; 
	
	if($validation)
	{
		$query = "SELECT * FROM ArticoliFamiglie WHERE nome_ArticoliFamiglie = '$nome_ArticoliFamiglie'";
		$params = array();
		$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
		$res = sqlsrv_query($conn, $query, $params, $options);	
		$num_rows = sqlsrv_num_rows($res);
	
		if($num_rows > 0)
		{
			echo "$num_rows famiglie trovate.<br>";
				while($item = sqlsrv_fetch_array($res))
					echo "".$nome_ArticoliFamiglie.", ".$note_ArticoliFamiglie."<br><b>'FAMIGLIA'</b> non inserita nel database.<br><br>";
		}
		else
		{
			$query = "INSERT INTO ArticoliFamiglie(nome_ArticoliFamiglie, note_ArticoliFamiglie, attivo_ArticoliFamiglie) VALUES(?, ?, ?)";
			$params = array($nome_ArticoliFamiglie, $note_ArticoliFamiglie, $attivo_ArticoliFamiglie);
			$res = sqlsrv_query($conn, $query, $params);
			$rows_affected = sqlsrv_rows_affected($res);
			
			if($rows_affected === false) // in caso di errore viene restituito FALSE 
				die(print_r(sqlsrv_errors(), true));
			elseif($rows_affected == -1) // -1 = il numero di record interessati non può essere restituito  
				echo "Nessuna info disponibile.<br />";
			else 
			{
				echo $rows_affected." record inserito:<br />".$nome_ArticoliFamiglie." ".$note_ArticoliFamiglie."<br/><br />";
				$nome_ArticoliFamiglie = "";
				$note_ArticoliFamiglie = "";
				echo"<form action=articolifamiglie_elenco.php method=post>";
				$nome_ArticoliFamiglie = "";
				$note_ArticoliFamiglie = "";
				$attivo_ArticoliFamiglie = "";
			}
		}
	}
}
if($controllo_modifica == "s")
{
	$query = "SELECT * FROM ArticoliFamiglie WHERE id_ArticoliFamiglie = '$id_ArticoliFamiglie'";
	$params = array();
	$res = sqlsrv_query($conn, $query);
	
	while($item = sqlsrv_fetch_array($res))
	{
		$id_ArticoliFamiglie = $item["id_ArticoliFamiglie"]; 											
		$nome_ArticoliFamiglie = $item["nome_ArticoliFamiglie"];
		$note_ArticoliFamiglie = $item["note_ArticoliFamiglie"];
		$attivo_ArticoliFamiglie = $item["attivo_ArticoliFamiglie"];
	}
	
	echo "<form action=articolifamiglie_modifica.php method=post>";
	$inserimento_modifica = "Modifica";
	echo"<input type=hidden name=id_ArticoliFamiglie value=$id_ArticoliFamiglie>";
}
else
{
	echo "<form action=articolifamiglie_ins_form.php method=post>";
	$inserimento_modifica = "Inserimento";
}

echo "<div class=titolo_pagina>$inserimento_modifica FAMIGLIA</div><br><br>";

?>
<form action="" method="post">
<b>NOME:</b> <input type="text" name="nome_ArticoliFamiglie" placeholder="nome famiglia" 
value="<?php echo htmlspecialchars($nome_ArticoliFamiglie, ENT_QUOTES);?>" size=20><br><br>
<b>NOTE:</b><br><textarea name="note_ArticoliFamiglie" placeholder="note" cols=100 rows=5>
<?php echo htmlspecialchars($note_ArticoliFamiglie, ENT_QUOTES);?></textarea><br><br>
<b>ATTIVO:</b> 
	<input type="radio" name="attivo_ArticoliFamiglie" value="s"<?php if($attivo_ArticoliFamiglie === "s") echo " checked"; ?>> SI
	<input type="radio" name="attivo_ArticoliFamiglie" value="n"<?php if($attivo_ArticoliFamiglie === "n") echo " checked"; ?>> NO<br><br>
<input type="submit" name="submit" value="Inserisci" action="articolifamiglie_ins_form.php">

</body>
</html>