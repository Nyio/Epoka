<?php
  // Initialiser la session
  session_start();
  // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
  if(!isset($_SESSION["numero"])){
    header("Location: login.php");
    exit(); 
  }
?>
<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">	
			<title>Validation des missions</title>
	<link rel="stylesheet" href="../CSS/stylereq.css" />
		</head>
		<body id="bodyadmin">
		<?php include "header.php"?>
			<div style="margin-bottom: 2%;">
				<p style="font-size: 65px; padding: 2%; text-align: center;">Remboursement des missions</p>
			</div>
			<div id="bloctextadmin">
			<?php
			//Déclaration des variables PHP.
			$server = "localhost";
			$base = "epoka";
			$username = "root";
			$password = "";
			$table = "rdv_jpo";
			$pdo = new PDO("mysql:host=$server;dbname=$base;charset=utf8", $username , $password);
			$finaldate = "";

			//Envoi d'une requête SQL pour afficher les données des visiteurs de la base de données.
			try {
				$stmt = $pdo->prepare("SELECT * FROM salarie, mission, ville WHERE mis_validation = 1 AND mis_idSalarie = sal_id AND mis_idDestination = vil_id ORDER BY mis_dateDebut");
				$stmt->execute();
				//Affichage des données dans un tableau avec un style en CSS.
				echo ('<table class="container">
						<thead>
							<tr>
								<th><h1>Nom du salarié</h1></th>
								<th><h1>Prénom du salarié</h1></th>
								<th><h1>Début de la mission</h1></th>
								<th><h1>Fin de la mission</h1></th>
								<th><h1>Lieu de la mission</h1></th>
								<th><h1>Montant</h1></th>
								<th><h1>Paiement</h1></th>
							</tr>
						</thead>
						<tbody>');
				foreach ($stmt->fetchAll() as $ligne) {

					

					if($ligne["mis_montant"] == null){
						$montant = calculMontant($ligne["mis_id"],$ligne["sal_idAgence"]);
					}else{
						$montant = $ligne["mis_montant"]."€";

					}

					if(stristr($montant, 'Distance')==true){
						$disabled="disabled";
							
					}else{
						$disabled="";
					}

					if ($ligne['mis_paiement'] == 0) {
						$validation = '<td>
						<form action="../Controleur/ActionPayer.php" method="post">
						<input type="hidden" name="montant" value="'.$montant.'"/>
						<button value="'.$ligne["mis_id"].'" name="payer" type="submit"'.$disabled.' >PAYER</button>
						</form>';
					}
					else {
						$validation = '<td>Remboursée';
					}				

					//Affiche le résultat de la requête SQL ligne par ligne.
					echo ('<tr><td>' . $ligne["sal_nom"] . '</td>
					<td>' . $ligne["sal_prenom"] . '</td>
					<td>' . $ligne["mis_dateDebut"] . '</td>
					<td>' . $ligne["mis_dateFin"] . '</td>
					<td>' . $ligne["vil_nom"] . ' ('. $ligne["vil_cp"] .')
					<td>' . $montant .'</td>
					</td>'.$validation.'</tr>');
				}
				echo ('</tbody></table>');
			}
			catch (exception $e) {
				die("Erreur de type ".$e->getMessage());
			}
			?>
			</div>
		</body>
</html>

<?php

function calculMontant($idMission, $salarieIdAgence){
	//récupération de la ville de l'agence
	$pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "");
	$stmt = $pdo->prepare ("SELECT age_ville, vil_nom FROM agence, ville WHERE age_ville = vil_id AND age_id = :idAgence");
	$stmt->bindParam ("idAgence", $salarieIdAgence,PDO::PARAM_INT);
	$stmt->execute ();
	$stmtVille = $stmt -> fetch();

	$ville1 = $stmtVille['age_ville'];
	$nomVille1 = $stmtVille['vil_nom'];

	//récupération de la ville de destination
	$stmt = $pdo->prepare ("SELECT mis_idDestination, vil_nom FROM mission, ville WHERE mis_idDestination = vil_id AND mis_id = :idMission");
	$stmt->bindParam ("idMission", $idMission,PDO::PARAM_INT);
	$stmt->execute ();
	$stmtVille = $stmt -> fetch();

	$ville2 = $stmtVille['mis_idDestination'];
	$nomVille2 = $stmtVille['vil_nom'];

	//récupération de la distance entre les deux villes
	$stmt = $pdo->prepare ("SELECT dis_km FROM distance WHERE dis_idVilleDepart = :ville1 AND dis_idVilleArrivee = :ville2");
	$stmt->bindParam ("ville1", $ville1,PDO::PARAM_INT);
	$stmt->bindParam ("ville2", $ville2,PDO::PARAM_INT);
	$stmt->execute ();
	$stmtKm = $stmt -> fetch();

	$distance = $stmtKm['dis_km'];

	if (!isset($distance)){
		return("Distance entre $nomVille1 et <br />$nomVille2 non renseignée");
	}

	//nombre de jours de la mission
	$stmt = $pdo->prepare ("SELECT DATEDIFF(mis_dateFin, mis_dateDebut) + 1 as dateDiff FROM mission WHERE mis_id = :idMission");
	$stmt->bindParam ("idMission", $idMission,PDO::PARAM_INT);
	$stmt->execute ();
	$stmtJour = $stmt -> fetch();

	$nbJours = $stmtJour['dateDiff'];



	//récupération des paramètres
	$stmt = $pdo->prepare ("SELECT * FROM param");
	$stmt->execute ();
	$stmtParam = $stmt -> fetch();

	$prixKm = $stmtParam['prixKm'];
	$prixJournee = $stmtParam['prixJournee'];



	//calcul
	$montant = ($distance * $prixKm) * 2 + ($nbJours * $prixJournee);

	return(number_format($montant, 2, '.', '')."€");
}

?>