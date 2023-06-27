<?php
	// Interface de visionnement des détails d'une fiche donnée.
	// Cette interface ne permet pas de faire des changements dans les fiches,
	//	et est accessible aux utilisateurs ne pouvant pas faire de modifications.
	if (session_status() == PHP_SESSION_NONE){
		session_start();
	}
	include('database.php');
	include('fonctions.php');

	// Vérifications de connexion et de permissions.
	if(!$_SESSION["UserID"])
	{ 
		$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
		header("Location: index.php?message=expired"); 
		return;
	}
	if(!CheckSecurity("Search"))
	{
		echo "<p>Votre compte n'a pas l'autorisation d'accès à cette page. <a href=\"index.php\">Retour à la page d’ouverture de session</a></p>";
		return;
	}

	// Si on a un ID de fiche dans l'url, on va le chercher
	if (isset($_GET['id'])){
		$id = $_GET['id'];
	}
	// Si on a pas d'ID, on retourne immédiatement à la liste
	else {
		header("Location: liste.php");
	}

	// Connexion à la base de données. On va chercher toutes les informations relatives à l'ID donné.
	$link = connexionBD();
	$q = "SELECT * FROM origine where id = $id";
	$r = mysqli_query($link, $q);
	deconnexionBD($link);

	// Si on trouve les informations, on les met dans des variables.
	if ($row = mysqli_fetch_array($r)){
		$migrant = $row["migrant"];
		$sexe = ($row['sexe']=='F')?"Femme":"Homme";	
		$lieudeces = ($row['lieudeces']=='x')?"":$row['lieudeces']; 
		$datedeces = ($row['datedeces']=='x')?"":$row['datedeces']; 
		$naissance = ($row['naissance']=='x')?"":$row['naissance']; 
		$bapteme = ($row['bapteme']=='x')?"":$row['bapteme']; 
		$commune = ($row['commune']=='x')?"":$row['commune'];
		$pays = ($row['pays']=='x')?"":$row['pays'];
		$code1 = ($row['code1']=='x')?"":$row['code1'];
		$pere = ($row['pere']=='x')?"":$row['pere'];
		$mere = ($row['mere']=='x')?"":$row['mere'];
		$datemariage = ($row['datemariage']=='x')?"":$row['datemariage'];
		$lieumariage = ($row['lieumariage']=='x')?"":$row['lieumariage'];
		$code2 = ($row['code2']=='x')?"":$row['code2'];
		$datecontrat = ($row['datecontrat']=='x')?"":$row['datecontrat'];
		$notaire = ($row['notaire']=='x')?"":$row['notaire'];
		$lieucontrat = ($row['lieucontrat']=='x')?"":$row['lieucontrat'];
		$metier = ($row['metier']=='x')?"":$row['metier'];
		$mention = ($row['mention']=='x')?"":$row['mention'];
		$occupation = ($row['occupation']=='x')?"":$row['occupation'];
		$status = ($row['status']=='x')?"":$row['status'];
		$mdatemariage = ($row['mdatemariage']=='x')?"":$row['mdatemariage'];
		$mlieumariage = ($row['mlieumariage']=='x')?"":$row['mlieumariage'];
		$conjoint = ($row['conjoint']=='x')?"":$row['conjoint'];
		$annotation = ($row['annotation']=='x')?"":$row['annotation'];
		$identification = ($row['identification']=='x')?"":$row['identification'];
		$chercheur = ($row['chercheur']=='x')?"":$row['chercheur'];
		$reference = ($row['reference']=='x')?"":$row['reference'];
		$copie = ($row['copie']=='x')?"":$row['copie'];
		$numero = ($row['numero']=='x')?"":$row['numero'];
		$dossiers = ($row['dossiers']=='x')?"":$row['dossiers'];
		$nom = ($row['nom']=='x')?"":$row['nom'];
		$surnom = ($row['surnom']=='x')?"":$row['surnom'];
		$surnom1 = ($row['surnom1']=='x')?"":$row['surnom1'];
		$localite = ($row['localite']=='x')?"":$row['localite'];
		$paroisse = ($row['paroisse']=='x')?"":$row['paroisse'];
		$NOTES = ($row['NOTES']=='x')?"":$row['NOTES'];		
		$datemodif = ($row['datemodif']=='x')?"":$row['datemodif'];
		$archive = ($row['archive']=='x')?"":$row['archive'];
		$dateajout = ($row['dateajout']=='x')?"":$row['dateajout'];
		$publication = ($row['publication']=='x')?"":$row['publication'];
		$actenumerises = ($row['actenumerises']=='x')?"":$row['actenumerises'];
	}
	// Si la fiche n'existe pas, on renvoie l'utilisateur à la liste des fiches.
	else{
		header("Location: liste.php");
	}
		
// Début du HTML
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="Interface de visionnement d'une fiche">
		<meta name="author" content="Marc-Albert Fournier">
		<title>Informations de la fiche</title>
		<link rel="stylesheet" media="(min-width: 720px)" href="style.css">
		<link rel="stylesheet" media="(max-width: 720px)"href="styleNarrow.css">
	</head>
	<body>
		<?php 
		// Pour chaque champ, on affiche la valeur trouvée.
		?>
		<h1>Informations de la fiche</h1>
			<div class="gauche">
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Publié:</div>
					<div class="champVoir">
						<?php if ($publication == '') echo "Oui"; else echo "Non" ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Numéro d'identification:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($numero); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Migrant:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($migrant); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Sexe:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($sexe); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Lieu d'origine:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($commune); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Département ou pays:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($pays); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Code INSEE:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($code1); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Date de naisance:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($naissance); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Date de baptème:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($bapteme); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Lieu de décès ou inhumation:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($lieudeces); ?><br/>
					</div>
				</div>
			
				<div class="donneeVoir">
					<div class="titreChampVoir">Date de décès ou inhumation:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($datedeces); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Première mention au pays:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($mention); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Occupation à l'arrivée:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($occupation); ?><br/>
					</div>
				</div>
				
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Statut matrimonial:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($status); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Premier(e) conjoint(e):</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($conjoint); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Date de mariage:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($mdatemariage); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Lieu du mariage:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($mlieumariage); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Date du contrat de mariage:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($datecontrat); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Nom du notaire:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($notaire); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Lieu du contrat:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($lieucontrat); ?><br/>
					</div>
				</div>
				

				</div>
				<div class="droite">

				
				<div class="donneeVoir">
					<div class="titreChampVoir">Nom du père:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($pere); ?><br/>
					</div>
				</div>
				
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Nom de la mère:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($mere); ?><br/>
					</div>
				</div>
				
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Date de mariage des parents:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($datemariage); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Lieu du mariage des parents:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($lieumariage); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Code INSEE:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($code2); ?><br/>
					</div>
				</div>
				

				
				<div class="donneeVoir">
					<div class="titreChampVoir">Profession du père:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($metier); ?><br/>
					</div>
				</div>
				

				
				<div class="donneeVoir">
					<div class="titreChampVoir">Remarques:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($annotation); ?>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Identification du migrant:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($identification); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Chercheur(s):</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($chercheur); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Référence:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($reference); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Copie d'acte:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($copie); ?>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Acte numérisé:</div>
					<div class="champVoir">
						<?php echo getUrl($actenumerises); ?>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Dossier d'archive:</div>
					<div class="champVoir">
						<?php echo getUrl($archive); ?>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Premier nom:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($nom); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Premier surnom:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($surnom); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Deuxième surnom:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($surnom1); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Localité actuelle:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($localite); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Paroisse religieuse:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($paroisse); ?><br/>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">NOTES:</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($NOTES); ?>
					</div>
				</div>
				
				<div class="donneeVoir">
					<div class="titreChampVoir">Action (champ requis?):</div>
					<div class="champVoir">
						<?php echo htmlspecialchars($dossiers); ?><br/>
					</div>
				</div>
			</div>
		<div class="datesVoir">
			Ajouté le: <?php echo $dateajout; ?><br/>
			Dernière modification le: <?php echo $datemodif; ?>
		</div>
		<a href="liste.php" class="bouton">Retour</a>
	</body>
</html>
