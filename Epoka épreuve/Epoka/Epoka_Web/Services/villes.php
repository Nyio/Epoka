<?php
$server = "localhost";
$base = "epoka";
$username = "root";
$password = "";
$pdo = new PDO("mysql:host=$server;dbname=$base;charset=utf8", $username, $password);
$stmt = $pdo->prepare ("SELECT * FROM ville WHERE vil_categorie < 3 ORDER BY vil_categorie, vil_nom");
$stmt->execute ();
while($ligne = $stmt ->fetch()) {
$table[] = $ligne;
}
echo(json_encode($table));
?>