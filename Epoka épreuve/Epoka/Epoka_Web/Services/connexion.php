<?php
$server = "localhost";
$base = "epoka";
$username = "root";
$password = "";
$pdo = new PDO("mysql:host=$server;dbname=$base;charset=utf8", $username, $password);
$user = $_GET["user"];
$mdp = $_GET["mdp"];
$stmt = $pdo->prepare ("SELECT * FROM salarie WHERE sal_id=:user AND sal_mdp=PASSWORD(:mdp)");
$stmt->bindParam (":user", $user,PDO::PARAM_STR);
$stmt->bindParam (":mdp", $mdp,PDO::PARAM_STR);
$stmt->execute ();
if ($ligne = $stmt ->fetch()){
    $table[] = $ligne;
}
else {
    $table[] = array("erreur" => "erreur de connexion");
}
echo(json_encode($table));
?>