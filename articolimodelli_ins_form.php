<html>
<head>
<link rel='stylesheet' href='file_css.css'>
<meta http-equiv=”content-type” content=”text/html; charset=utf-8”/>
</head>
<body>
<?php
include_once("connessione_sqlsrv.php");
$conn = sqlsrv_connect($serverName, $connectionInfo);

$id_ArticoliModelli = $_GET["id_ArticoliModelli"];
$controllo_modifica = $_GET["controllo_modifica"]; 

$nome_ArticoliModelli = "";
$note_ArticoliModelli = "";
$attivo_ArticoliModelli = "";
$id_ArticoliFamiglie = "";

if(isset($_POST["submit"])) 
{
	$validation = true;
	
	if(!isset($_POST["nome_ArticoliModelli"]) || $_POST["nome_ArticoliModelli"] === "") 
	{
		$validation = false;
		echo "The field <b>'NOME MODELLO'</b> is required<br>";
	}
	else
	{
		$nome_ArticoliModelli = $_POST["nome_ArticoliModelli"];
		$nome_ArticoliModelli = str_replace("'", "''", $nome_ArticoliModelli);
	}
	
	if(isset($_POST["note_ArticoliModelli"]) || $_POST["note_ArticoliModelli"] === "")
	{
		$note_ArticoliModelli = $_POST["note_ArticoliModelli"];
		$note_ArticoliModelli = str_replace("'", "''", $note_ArticoliModelli);
	}
	
	if(!isset($_POST["attivo_ArticoliModelli"]) || $_POST["attivo_ArticoliModelli"] === "") 
	{
		$validation = false;
		echo "The field <b>'ATTIVO'</b> is required<br>";
	}
	else
		$attivo_ArticoliModelli = $_POST["attivo_ArticoliModelli"];
	
	if(isset($_POST["id_ArticoliFamiglie"]) || $_POST["id_ArticoliFamiglie"] === "")
		$id_ArticoliFamiglie = $_POST["id_ArticoliFamiglie"];
	
	if($validation)
	{
		$query = "SELECT * FROM ArticoliModelli WHERE nome_ArticoliModelli = '$nome_ArticoliModelli'";	// vedo se il modello è presente
		$params = array();
		$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
		$res = sqlsrv_query($conn, $query, $params, $options);	
		$num_rows = sqlsrv_num_rows($res);
		
		if($num_rows > 0)		// se il modello è presente non lo inserisco 
		{
			echo "$num_rows modelli trovati.<br>";
				while($item = sqlsrv_fetch_array($res))
					echo "".$nome_ArticoliModelli.", ".$note_ArticoliModelli."<br><b>'FAMIGLIA'</b> non inserita nel database.<br><br>";
		}
		else	// altrimenti lo inserisco
		{
			$query = "INSERT INTO ArticoliModelli(nome_ArticoliModelli, note_ArticoliModelli, attivo_ArticoliModelli, id_ArticoliFamiglie) VALUES(?, ?, ?, ?)";
			$params = array($nome_ArticoliModelli, $note_ArticoliModelli, $attivo_ArticoliModelli, $id_ArticoliFamiglie);
			$res = sqlsrv_query($conn, $query, $params);
			$rows_affected = sqlsrv_rows_affected($res);
			
			if($rows_affected === false) // in caso di errore viene restituito FALSE 
			die(print_r(sqlsrv_errors(), true));
			elseif($rows_affected == -1) // -1 = il numero di record interessati non può essere restituito  
				echo "Nessuna info disponibile.<br />";
			else 
			{
				echo $rows_affected." record inserito:<br />".$nome_ArticoliModelli." ".$note_ArticoliModelli."<br/><br />";
				$nome_ArticoliModelli = "";
				$note_ArticoliModelli = "";
				echo"<form action=articolifamiglie_elenco.php method=post>";
			}	
		}
	}
}
if($controllo_modifica == 's')
{
	$query = "SELECT * FROM ArticoliModelli WHERE id_ArticoliModelli = '$id_ArticoliModelli'";
	$params = array();
	$res = sqlsrv_query($conn, $query);
	
	while($item = sqlsrv_fetch_array($res))
	{
		$id_ArticoliModelli = $item["id_ArticoliModelli"]; 											
		$nome_ArticoliModelli = $item["nome_ArticoliModelli"];
		$note_ArticoliModelli = $item["note_ArticoliModelli"];
		$attivo_ArticoliModelli = $item["attivo_ArticoliModelli"];
		$id_ArticoliFamiglie = $item["id_ArticoliFamiglie"];
	}
	
	echo "<form action=articolimodelli_modifica.php method=post>";
	$inserimento_modifica = "Modifica";
	echo"<input type=hidden name=id_ArticoliModelli value=$id_ArticoliModelli>";
}
else
{
	echo "<form action=articolimodelli_ins_form.php method=post>";
	$inserimento_modifica = "Inserimento";
}
echo "<div class=titolo_pagina>$inserimento_modifica MODELLO</div><br><br>";
?>
<!-- FORM INSERIMENTO MODELLO -->
<form action="" method="post">
<b>NOME:</b> <input type="text" name="nome_ArticoliModelli" placeholder="nome modello" 
value="<?php echo htmlspecialchars($nome_ArticoliModelli, ENT_QUOTES);?>" size=20><br><br>
<b>NOTE:</b><br><textarea name="note_ArticoliModelli" placeholder="note" cols=100 rows=5>
<?php echo htmlspecialchars($note_ArticoliModelli, ENT_QUOTES);?></textarea><br><br>
<b>ATTIVO:</b> 
	<input type="radio" name="attivo_ArticoliModelli" value="s"<?php if($attivo_ArticoliModelli === "s") echo " checked"; ?>> SI
	<input type="radio" name="attivo_ArticoliModelli" value="n"<?php if($attivo_ArticoliModelli === "n") echo " checked"; ?>> NO<br><br>
<b>FAMIGLIA: </b>
<select name="id_ArticoliFamiglie"> 
<option value="Please select one">Please select one</option>
<?php
$query = "SELECT * FROM ArticoliFamiglie WHERE attivo_ArticoliFamiglie = 's'";
$params = array();
$res = sqlsrv_query($conn, $query, $params);
$id_ArticoliFamiglie = array();
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
for($i = 0; $i < $a; $i++)
	echo "<option value=$id_ArticoliFamiglie[$i]>$nome_ArticoliFamiglie[$i]</option>";
?>
</select><br><br>
<input type="submit" name="submit" value="Inserisci" action="articolifamiglie_ins_form.php">
</body>
</html>