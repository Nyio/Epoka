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
				<p style="font-size: 65px; padding: 2%; text-align: center;">Validation des missions</p>
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
			$connexion = new mysqli($server, $username, $password, $base);
			$finaldate = "";

			//Envoi d'une requête SQL pour afficher les données des visiteurs de la base de données.
			try {
				$stmt = $pdo->prepare("SELECT * FROM salarie, mission, ville WHERE sal_idResponsable = '".$_SESSION["numero"] ."' AND mis_idSalarie = sal_id AND mis_idDestination = vil_id ORDER BY mis_dateDebut");
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
								<th><h1>Validation</h1></th>
							</tr>
						</thead>
						<tbody>');
				foreach ($stmt->fetchAll() as $ligne) {

					if ($ligne['mis_validation'] == 0) {
						$validation = '<form action="../Controleur/ActionValider.php" method="post">
						<button value="'.$ligne["mis_id"].'" name="valider" type="submit" >VALIDER</button>
						</form>';
					}
					else {
						$validation = 'Validée';
					}

					if ($ligne['mis_paiement'] != 0) {
						$paiement = ', remboursée';
					}
					else {
						$paiement = '';
					}

					//Affiche le résultat de la requête SQL ligne par ligne.
					echo ('<tr><td>' . $ligne["sal_nom"] . '</td>
					<td>' . $ligne["sal_prenom"] . '</td>
					<td>' . $ligne["mis_dateDebut"] . '</td>
					<td>' . $ligne["mis_dateFin"] . '</td>
					<td>' . $ligne["vil_nom"] . ' ('. $ligne["vil_cp"] .')</td><td>'.$validation.$paiement.'</td></tr>');
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