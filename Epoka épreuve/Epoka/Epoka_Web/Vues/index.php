<!DOCTYPE html>
<html lang="fr">
<head>
    <title>log</title>

</head>
 <link rel="stylesheet" href="../CSS/style.css" />

<body id="bodyadmin">
<?php include "header.php"?>
<?php
require('../Controleur/config.php');
session_start();
if (isset($_POST['username'])){
  $username = stripslashes($_REQUEST['username']);
  $password = stripslashes($_REQUEST['password']);
  $stmt = $pdo->prepare ("SELECT * FROM salarie WHERE sal_id=:user AND sal_mdp=PASSWORD(:mdp)");
  $stmt->bindParam (":user", $username,PDO::PARAM_STR);
  $stmt->bindParam (":mdp", $password,PDO::PARAM_STR);
  $stmt->execute ();

  if ($ligne = $stmt->fetch()){
      $_SESSION['numero'] = $username;
      $_SESSION['valider'] = $ligne['sal_autorValidation'];
      $_SESSION['rembourser'] = $ligne['sal_autorRemboursement'];
      header("Location: connecte.php");
  } else {
    $message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
  }
}
?>
<div id="container">
<form class="box" action="" method="post" name="login">
<center><h1 class="box-title">Connexion</h1></center>
<center><input type="text" class="box-input" name="username" placeholder="Nom d'utilisateur"></center>
</br>
<center><input type="password" class="box-input" name="password" placeholder="Mot de passe"></center>
</br>
<center><input type="submit" value="Connexion " name="submit" class="box-button"></center>
<?php if (! empty($message)) { ?>
    <p class="errorMessage"><?php echo $message; ?></p>
<?php } ?>
</form>
</div>
</body>
</html>

