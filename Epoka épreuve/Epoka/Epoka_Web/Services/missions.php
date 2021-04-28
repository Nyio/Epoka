<?php
$server = "localhost";
$base = "epoka";
$username = "root";
$password = "";
$pdo = new PDO("mysql:host=$server;dbname=$base;charset=utf8", $username, $password);
$dateDebut = $_GET["dateDebut"];
$dateFin = $_GET["dateFin"];
$destination = $_GET["destination"];
$salarie = $_GET["salarie"];
$stmt = $pdo->prepare ("INSERT INTO `mission`(`mis_dateDebut`, `mis_dateFin`, `mis_idDestination`, `mis_idSalarie`) VALUES (:dateDebut, :dateFin, :destination, :salarie)"); 
$stmt->bindParam(":dateDebut", $dateDebut,PDO::PARAM_STR);
$stmt->bindParam(":dateFin", $dateFin,PDO::PARAM_STR);
$stmt->bindParam(":destination", $destination,PDO::PARAM_STR);
$stmt->bindParam(":salarie", $salarie,PDO::PARAM_STR);
$stmt->execute ();
?>

