<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "angel";

try{
	$pdo = new PDO("mysql:dbname=".$db.";host=".$host, $user, $pass);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
	echo "ERROR TO CONNECT ON DB: ".$e->getMessage();
	exit;
}
?>