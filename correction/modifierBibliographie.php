<?php
	// Interface de recherche avancée.
	// Envoie les valeurs des champs de recherche voulus a liste.php

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
	// Vérification préliminaire de permissions. On refuse l'accès si l'utilisateur ne peut ni ajouter, ni modifier de données.
	if(!CheckSecurity("Add") && !CheckSecurity("Edit"))
	{
		echo "<p>Votre compte n'a pas l'autorisation d'accès à cette page. <a href=\"index.php\">Retour à la page d’ouverture de session</a></p>";
		return;
	}
	
	if (isset($_POST['bouton_submit'])){
		// On détermine s'il s'agit d'un ajout ou d'une modification
		$update = $_POST['update']; 
		$update = ($update == "true")? true: false;
		
		// On stocke les informations de la fiche dans des variables, échappant les caractères sensibles.
		$link = connexionBD();
		$id = mysqli_real_escape_string($link, $_POST['id']);
		$auteur = mysqli_real_escape_string($link, $_POST['auteur']);
		$titre = mysqli_real_escape_string($link, $_POST['titre']);
		$reference = mysqli_real_escape_string($link, $_POST['reference']); 
		$editeur = mysqli_real_escape_string($link, $_POST['editeur']);
		$anneeEdition = mysqli_real_escape_string($link, $_POST['anneeEdition']);
		$nombrePages = mysqli_real_escape_string($link, $_POST['nombrePages']);
		
		
		// Création de la requête SQL.
		if ($update)
			$q = "UPDATE bibliographie SET auteur='$auteur', titre='$titre', reference='$reference', editeur='$editeur', anneeEdition='$anneeEdition', nombrePages='$nombrePages' where id=$id";
		else
			$q = "INSERT INTO bibliographie (auteur, titre, reference, editeur, anneeEdition, nombrePages) VALUES ('$auteur', '$titre', '$reference', '$editeur','$anneeEdition','$nombrePages')";
		
		// On vérifie les permissions de l'utilisateur.
		// Dans tous les cas, on obtient un message à afficher
		
		// Si on veut éditer une fiche
		if($update){
			// Si on a la permission d'éditer, on lance la requête.
			if (CheckSecurity("Edit")){
				if (mysqli_query($link, $q)){
					$message = "Mise à jour de la fiche réussie.";
					$good = true;
				}
				else{
					$message = "Échec de la requête à la base de données: ".mysqli_error($link);
					$good = false;
				}
			}
			else {
				$message = "Impossible d'exécuter la requête de modification - Permission non accordée.";
				$good = false;
			}
		}
		// Sinon, on veut insérer une nouvelle fiche
		else {
			// Si on a la permission d'ajouter, on lance la requête
			if (CheckSecurity("Add")){
				if (mysqli_query($link, $q)){
					$message = "Ajout de la nouvelle fiche réussi.";
					$good = true;
					$justAdded = true;
					$id = mysqli_insert_id($link);
				}
				else{
					$message = "Échec de la requête à la base de données: ".mysqli_error($link);
					$good = false;
				}
			}	
			else{
				$message = "Impossible d'exécuter la requête d'ajout - Permission non accordée.";
				$good = false;
			}
		}
		deconnexionBD($link);
	} // Fin traitement bouton_submit
			
			
	else if (isset($_POST['bouton_delete'])){
		// Si on a la permission, on effectue la suppression.
		if (CheckSecurity("Delete")){
			$link = connexionBD();
			$id = mysqli_real_escape_string($link, $_POST['id']);
			
			$q = "DELETE FROM bibliographie WHERE id='$id'";
			if (mysqli_query($link, $q)){
				$message = "Suppression de la fiche réussie.";
				$good = true;
			}
			else{
				$message = "Échec de la suppression: ".mysqli_error($link);
				$good = false;
			}
			deconnexionBD($link);
		}
		else{
			$message = "Impossible d'exécuter la requête d'ajout - Permission non accordée.";
			$good = false;
		}
	} //Fin traitement bouton_delete
		

	
	// Si on a un ID de fiche dans l'url, on va le chercher
	if (isset($_GET['id'])){
		$id = $_GET['id'];
	}
	else if (isset($justAdded)){
		$id = $id;
	}
	else $id = 0;
	
	// Si on a bien obtenu un ID dans l'url, et qu'on ne vient pas de supprimer la fiche.
	if ($id != 0 && !isset($_POST['bouton_delete'])){
		
		// On va chercher les informations de la fiche dans la BD et on les stocke dans des variables.
		$link = connexionBD();
		$q = "SELECT * FROM bibliographie where id = '".mysqli_real_escape_string($link, $id)."'";
		$r = mysqli_query($link, $q);
		deconnexionBD($link);
		
		if ($row = mysqli_fetch_array($r)){
			$auteur = $row["auteur"];
			$titre = $row['titre'];	
			$reference = $row['reference']; 
			$editeur = $row['editeur'];
			$anneeEdition = $row['anneeEdition'];
			$nombrePages = $row['nombrePages'];
			
			// Puisqu'on affiche une fiche existante, on est en mode d'édition
			$update = true;
		}
		// Si on a un ID de fiche, mais que la fiche n'existe pas, on renvoie l'utilisateur à la liste des fiches.
		else{
			header("Location: listeBibliographie.php");
		}
		
	}
	// Si on a pas d'ID dans l'url, ou qu'on vient de supprimer la fiche
	else{
		// On initialise nos variables vides.
		$auteur = "";
		$titre = "";
		$reference = ""; 	
		$editeur = "";
		$anneeEdition = "";
		$nombrePages = ""; 
		// Puisqu'on a pas de fiche, on est en mode d'ajout.
		$update = false;
	}

	// Si on est en mode d'édition sans la permission d'éditer, on renvoie à la page de connexion
	// Si on est en mode d'ajout sans la permission d'ajouter, on renvoie à la page de connexion
	if (($update && !CheckSecurity("Edit")) || (!$update && !CheckSecurity("Add"))) {
		echo "<p>Votre compte n'a pas l'autorisation d'accès à cette page. <a href=\"index.php\">Retour à la page d’ouverture de session</a></p>";
		return;
	}


// Début du HTML
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Fichier Origine</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<meta name="description" content="Page d'accueil du site de modifications">
		<meta name="author" content="Jean-Emmanuel St-Amour">
	</head>
	<body>
		<script type="text/javascript"> 
			function stopRKey(evt) { 
			  var evt = (evt) ? evt : ((event) ? event : null); 
			  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
			  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
			} 

			document.onkeypress = stopRKey;
		</script>
		<a href="liste.php"><img src="Images/logo.png" alt="Fichier origine" style="width:350px;height:100px;"></a>
		<h1>Gestion des bibliographies</h1>
		<div class="pageRA">
			<form action="modifierBibliographie.php<?php if ($update) echo "?id=$id" ?>" method="post">
				<div class="donnee">
					<div class="titreChamp2">Auteur</div>
					<input class="modifierBiblio" type="text" name="auteur" value="<?php echo htmlspecialchars($auteur); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Titre</div>
					<input class="modifierBiblio" type="text" name="titre" value="<?php echo htmlspecialchars($titre); ?>">
				</div>
				
				<div class="donnee"> 
					<div class="titreChamp2">Référence</div>
					<input class="modifierBiblio" type="text" name="reference" value="<?php echo htmlspecialchars($reference); ?>">
				</div>
				
				<div class="donnee">
					<div class="titreChamp2">Éditeur</div>
					<input class="modifierBiblio" type="text" name="editeur" value="<?php echo htmlspecialchars($editeur); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Année d'édition</div>
					<input class="modifierBiblio" type="text" name="anneeEdition" value="<?php echo htmlspecialchars($anneeEdition); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Nombre de page</div>
					<input class="modifierBiblio" type="text" name="nombrePages" value="<?php echo htmlspecialchars($nombrePages); ?>">
				</div>
				<input type="hidden" name="id" value="<?php echo $id; ?>">
				<input type="hidden" name="update" value="<?php echo ($update)? "true":"false"; ?>">
				<?php
				// Si on a un message, on l'affiche en bas de la page.
				if (isset($message)) {
					if ($good)
						echo "<div class='rempMessageGood'>$message</div>";
					else
						echo "<div class='rempMessageBad'>$message</div>";
				}
				?>
				<input type="submit" name="bouton_submit" value="Sauvegarder">
				<?php if ($update && CheckSecurity("Delete")){ ?>
					<input type="submit" name="bouton_delete" value="Supprimer la bibliographie" class="redButton"><br>
				<?php } ?>
			</form><br><br>
			<a href="listeBibliographie.php" class="bouton">Retour</a>
		</div>
	</body>
</html>
