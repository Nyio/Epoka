    <?php
    if(isset($_SESSION["numero"])){
    ?>
<link rel="stylesheet" href="../CSS/styleheader.css" />

<header>
<ul>
    <li><a href="../Controleur/logout.php">Déconnexion</a></li>
    <li><a href="valider.php">Validation des missions</a></li>
    <li><a href="payer.php">Paiement des frais</a></li>
    <li><a href="parametres.php">Paramétrage</a></li>
</ul>
</header>
<?php }?>