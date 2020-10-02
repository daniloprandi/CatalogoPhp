<?php
$serverName = "LAPTOP-OKKRFVI2\SQLEXPRESS";
$connectionInfo = array("Database"=>"CatalogoPhp");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if(!$conn)
{
	echo "Problemi di connessione<br>";
	die( print_r(sqlsrv_errors(), true));
}	
?>