<?php
	// Interface d'ajout ou de modification d'une fiche.
	// Cette page gère l'ajout de nouvelles fiches dans la base de données,
	//	ainsi que l'édition et la suppression de fiches existantes.

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
	// Vérification préliminaire des permissions. Si on ne peut ni ajouter ni éditer, on est redirigé à l'accueil.
	if(!CheckSecurity("Edit") && !CheckSecurity("Add"))
	{
		echo "<p>Votre compte n'a pas l'autorisation d'accès à cette page. <a href=\"index.php\">Retour à la page d’ouverture de session</a></p>";
		return;
	}

	// Si on a soumis un ajout ou une modification
	if (isset($_POST['bouton_submit'])){
		// On détermine s'il s'agit d'un ajout ou d'une modification
		$update = $_POST['update']; 
		$update = ($update == "true")? true: false;
		
		// On stocke les informations de la fiche dans des variables, échappant les caractères sensibles.
		$link = connexionBD();
		$id = mysqli_real_escape_string($link, $_POST['id']);
		$acronyme = mysqli_real_escape_string($link, $_POST['acronyme']);
		$definition = mysqli_real_escape_string($link, $_POST['definition']);
		
		
		
		// Création de la requête SQL.
		if ($update)
			$q = "UPDATE acronyme set acronyme='$acronyme', definition='$definition' where id=$id";
		else
			$q = "INSERT INTO acronyme (acronyme, definition) values ('$acronyme','$definition')";
		
		if($update){
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
		else {
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
			
			$q = "DELETE FROM acronyme WHERE id='$id'";
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
		else {
			$message = "Impossible d'exécuter la requête de suppression - Permission non accordée.";
			$good = false;
		}
	} // Fin traitement bouton_delete
	
	// Si on a un ID de fiche dans l'url, on va le chercher
	if (isset($_GET['id'])){
		$id = $_GET['id'];
	}
	else if (isset($justAdded)){
		$id = $id;
	}
	
	if ($id != 0 && !isset($_POST['bouton_delete'])){
		$link = connexionBD();
		$q = "SELECT * FROM acronyme where id = '".mysqli_real_escape_string($link, $id)."'";
		$r = mysqli_query($link, $q);
		deconnexionBD($link);
		
		if ($row = mysqli_fetch_array($r)){
			$acronyme = $row['acronyme'];
			$definition = $row['definition'];
			
			$update = true;
		}
		else{
			header("Location: listeAcro.php");
		}
		
	}
	else{
		$acronyme = "";
		$definition = "";
		
		$update = false;
	}
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Fichier Origine - Acronymes</title>
		<link rel="stylesheet" media="(min-width: 720px)" href="style.css">
		<link rel="stylesheet" media="(max-width: 720px)" href="styleNarrow.css">
		<meta name="description" content="Page d'ajout / modification d'acronyme">
		<meta name="author" content="Marc-Albert Fournier">
	</head>
	<body>
		<h1>Informations de l'acronyme</h1>
		<form action="modifierAcro.php<?php if ($update) echo "?id=$id"?>" method="post">
			<div class="donneeAcro">
					<div class="titreChampAcro">Acronyme:</div>
					<div class="champAcro">
						<input type="text" name="acronyme" value="<?php echo htmlspecialchars($acronyme); ?>"><br/>
					</div>
				</div>
				
			<div class="donneeAcro">
				<div class="titreChampAcro">Définition:</div>
				<div class="champAcro">
					<textarea name="definition"><?php echo htmlspecialchars($definition); ?></textarea>
				</div>
			</div>
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<input type="hidden" name="update" value="<?php echo ($update) ? "true" : "false"; ?>">
			<?php
			// Si on a un message, on l'affiche en bas de la page.
			if (isset($message)) {
				if ($good)
					echo "<div class='rempMessageGood'>$message</div>";
				else
					echo "<div class='rempMessageBad'>$message</div>";
			}
			?>
			<div class="submitButton"><input type="submit" name="bouton_submit" value="Sauvegarder"></div>
			<?php 
			// Si on a la permission de supprimer les fiches, on affiche l'option.
			if ($update && CheckSecurity("Delete")) {?>
			<div class="deleteButton">
				<input type="submit" name="bouton_delete" value="Supprimer cet enregistrement" class="redButton"><br>
			</div><?php } ?>
		</form><br/><br/>
		<a href="listeAcro.php" class="bouton">Retour</a>
	</body>
</html>