<?php
  // Initialiser la session.
  session_start();
  // Vérifie si l'utilisateur est connecté, sinon le redirige vers la page de connexion.
  if(!isset($_SESSION["numero"])){
    header("Location: index.php");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="fr">
<link rel="stylesheet" href="../CSS/style.css" />
  <head>
    <title></title>
  </head>
  <body id="body">
    <?php include "header.php"?>
    <div id="vueadmin" class="sucess">
        <p style="font-size: 70px; padding-bottom: 10%; text-align: center;">Vous êtes bien connecté</p>
    </div>
  </body>
</html>