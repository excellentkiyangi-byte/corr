<?php
	if (session_status() == PHP_SESSION_NONE){
		session_start();
	}
	include('database.php');
	include('fonctions.php');
	
	if(!$_SESSION["UserID"])
	{ 
		$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
		header("Location: index.php?message=expired"); 
		return;
	}
	if(!CheckSecurity("Edit"))
	{
		echo "<p>Votre compte n'a pas l'autorisation d'accès à cette page. <a href=\"index.php\">Retour à la page d’ouverture de session</a></p>";
		return;
	}

	if (isset($_POST['ancienTexte']) && isset($_POST['nouveauTexte']) && isset($_POST['champ'])){
		$link = connexionBD();
		$ancienTexte = mysqli_real_escape_string($link, $_POST['ancienTexte']);
		$nouveauTexte = mysqli_real_escape_string($link, $_POST['nouveauTexte']);
		$champ = mysqli_real_escape_string($link, $_POST['champ']);
		$champ = preg_replace('#[^a-z]#', '', $champ);
		$q = "update origine set $champ=Replace($champ, '$ancienTexte', '$nouveauTexte')";
		if (mysqli_query($link, $q)){
			$nb = mysqli_affected_rows($link);
			$message = "Remplacement effectué avec succès. ($nb fiches mises à jour)";
			$good = true;
		}
		else{
			$message = "Échec de la requête à la base de données: ".mysqli_error($link);
			$good = false;
		}
		deconnexionBD($link);
	}
	else{
		$message = "";
	}
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="Interface de remplacement d'une chaîne de caractères">
		<meta name="author" content="Marc-Albert Fournier">
		<title>Remplacement d'une chaîne de charactères</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<a href="liste.php"><img src="Images/logo.png" alt="Fichier origine" style="width:350px;height:100px;"></a>
		<?php if ($message != "") {
			if ($good)
				echo "<div class='rempMessageGood'>$message</div>";
			else
				echo "<div class='rempMessageBad'>$message</div>";
		} ?>
		<h1>Remplacement d'une chaîne de caractères</h1>
		<form action="remplacerChaine.php" method="post">
			<div class="rempLabel">De:</div>
			<div class="rempChamp">
				<input type="text" name="ancienTexte" value="">
			</div>
			<div class="rempLabel">À:</div>
			<div class="rempChamp">
				<input type="text" name="nouveauTexte" value="">
			</div><br>
			Champ à traiter :
			<select name="champ" class="rempSelect">
				<option value="migrant" selected>Migrant</option>
				<option value="dossiers">Action</option>
				<option value="numero">Numéro d'identification</option>
				<option value="sexe">Sexe</option>
				<option value="naissance">Date de naissance</option>
				<option value="bapteme">Date de baptême</option>
				<option value="commune">Lieu d'origine</option>
				<option value="pays">Département ou pays</option>
				<option value="code1">Code INSEE (Lieu d'origine)</option>
				<option value="pere">Nom du père</option>
				<option value="mere">Nom de la mère</option>
				<option value="datemariage">Date de mariage des parents</option>
				<option value="lieumariage">Lieu de mariage des parents</option>
				<option value="code2">Code INSEE (Mariage des parents)</option>
				<option value="datecontrat">Date du contrat de mariage</option>
				<option value="notaire">Nom du notaire</option>
				<option value="lieucontrat">Lieu du contrat de mariage</option>
				<option value="metier">Profession du père</option>
				<option value="mention">Première mention au pays</option>
				<option value="occupation">Occupation à l'arrivée</option>
				<option value="status">Statut matrimonial</option>
				<option value="mdatemariage">Date de mariage</option>
				<option value="mlieumariage">Lieu de mariage</option>
				<option value="conjoint">Premier conjoint</option>
				<option value="lieudeces">Lieu de décès ou inhumation</option>
				<option value="datedeces">Date de décès ou inhumation</option>
				<option value="annotation">Remarques</option>
				<option value="identification">Identification du migrant</option>
				<option value="chercheur">Chercheur(s)</option>
				<option value="reference">Référence</option>
				<option value="copie">Copie d'acte</option>
				<option value="nom">Premier nom</option>
				<option value="surnom">Premier surnom</option>
				<option value="surnom1">Deuxième surnom</option>
				<option value="localite">Localité actuelle</option>
				<option value="paroisse">Paroisse religieuse</option>
			</select>
			<input type="submit" value="Remplacer">
		</form>
		<div><a href="liste.php">Retour au menu principal</a></div>
	</body>
</html>