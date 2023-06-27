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
	if(!CheckSecurity("Search"))
	{
		echo "<p>Votre compte n'a pas l'autorisation d'accès à cette page. <a href=\"index.php\">Retour à la page d’ouverture de session</a></p>";
		return;
	}

	// Si on a cliqué sur le bouton "Réinitialiser"
	if(isset($_POST['reset'])){
		// On déréférence la variable de session andOr
		// Puisque c'est la seule qui est vérifiée pour savoir si on a déjà une recherche en cours, il suffit
		// 	de la déréférencer pour réinitialiser le tout.
		unset($_SESSION['andOr']);
	}
	
	// Si on a des valeurs de recherche avancée déjà définies, on va chercher les valeurs dans les variables de session.
 	if (isset($_SESSION['andOr'])){
		$andOr = $_SESSION['andOr'];
		$dossiers = $_SESSION['dossiers'];
		$menu_dossiers = $_SESSION['menu_dossiers'];
		$non_dossiers = $_SESSION['non_dossiers'];
		$numero = $_SESSION['numero'];
		$menu_numero = $_SESSION['menu_numero'];
		$non_numero = $_SESSION['non_numero'];
		$migrant = $_SESSION['migrant'];
		$menu_migrant = $_SESSION['menu_migrant'];
		$non_migrant = $_SESSION['non_migrant'];
		$sexe = $_SESSION['sexe'];
		$menu_sexe = $_SESSION['menu_sexe'];
		$non_sexe = $_SESSION['non_sexe'];		
		$naissance = $_SESSION['naissance'];
		$menu_naissance = $_SESSION['menu_naissance'];
		$non_naissance = $_SESSION['non_naissance'];
		$bapteme = $_SESSION['bapteme'];
		$menu_bapteme = $_SESSION['menu_bapteme'];
		$non_bapteme = $_SESSION['non_bapteme'];
		$commune = $_SESSION['commune'];
		$menu_commune = $_SESSION['menu_commune'];
		$non_commune = $_SESSION['non_commune'];
		$pays = $_SESSION['pays'];
		$menu_pays = $_SESSION['menu_pays'];
		$non_pays = $_SESSION['non_pays'];
		$datemariage = $_SESSION['datemariage'];
		$menu_datemariage = $_SESSION['menu_datemariage'];
		$non_datemariage = $_SESSION['non_datemariage'];
		$datecontrat = $_SESSION['datecontrat'];
		$menu_datecontrat = $_SESSION['menu_datecontrat'];
		$non_datecontrat = $_SESSION['non_datecontrat'];
		$lieucontrat = $_SESSION['lieucontrat'];
		$menu_lieucontrat = $_SESSION['menu_lieucontrat'];
		$non_lieucontrat = $_SESSION['non_lieucontrat'];
		$metier = $_SESSION['metier'];
		$menu_metier = $_SESSION['menu_metier'];
		$non_metier = $_SESSION['non_metier'];
		$mention = $_SESSION['mention'];
		$menu_mention = $_SESSION['menu_mention'];
		$non_mention = $_SESSION['non_mention'];
		$occupation = $_SESSION['occupation'];
		$menu_occupation = $_SESSION['menu_occupation'];
		$non_occupation = $_SESSION['non_occupation'];
		$status = $_SESSION['status'];
		$menu_status = $_SESSION['menu_status'];
		$non_status = $_SESSION['non_status'];
		$mdatemariage = $_SESSION['mdatemariage'];
		$menu_mdatemariage = $_SESSION['menu_mdatemariage'];
		$non_mdatemariage = $_SESSION['non_mdatemariage'];
		$mlieumariage = $_SESSION['mlieumariage'];
		$menu_mlieumariage = $_SESSION['menu_mlieumariage'];
		$non_mlieumariage = $_SESSION['non_mlieumariage'];
		$lieumariage = $_SESSION['lieumariage'];
		$menu_lieumariage = $_SESSION['menu_lieumariage'];
		$non_lieumariage = $_SESSION['non_lieumariage'];
		$identification = $_SESSION['identification'];
		$menu_identification = $_SESSION['menu_identification'];
		$non_identification = $_SESSION['non_identification'];
		$conjoint = $_SESSION['conjoint'];
		$menu_conjoint = $_SESSION['menu_conjoint'];
		$non_conjoint = $_SESSION['non_conjoint'];
		$lieudeces = $_SESSION['lieudeces'];
		$menu_lieudeces = $_SESSION['menu_lieudeces'];
		$non_lieudeces = $_SESSION['non_lieudeces'];
		$datedeces = $_SESSION['datedeces'];
		$menu_datedeces = $_SESSION['menu_datedeces'];
		$non_datedeces = $_SESSION['non_datedeces'];
		$chercheur = $_SESSION['chercheur'];
		$menu_chercheur = $_SESSION['menu_chercheur'];
		$non_chercheur = $_SESSION['non_chercheur'];
		$copie = $_SESSION['copie'];
		$menu_copie = $_SESSION['menu_copie'];
		$non_copie = $_SESSION['non_copie'];
		$archive = $_SESSION['archive'];
		$menu_archive = $_SESSION['menu_archive'];
		$non_archive = $_SESSION['non_archive'];
		$publication = $_SESSION['publication'];
		$menu_publication = $_SESSION['menu_publication'];
		$non_publication = $_SESSION['non_publication'];
		$nom = $_SESSION['nom'];
		$menu_nom = $_SESSION['menu_nom'];
		$non_nom = $_SESSION['non_nom'];
		$surnom = $_SESSION['surnom'];
		$menu_surnom = $_SESSION['menu_surnom'];
		$non_surnom = $_SESSION['non_surnom'];
		$surnom1 = $_SESSION['surnom1'];
		$menu_surnom1 = $_SESSION['menu_surnom1'];
		$non_surnom1 = $_SESSION['non_surnom1'];
		$pere = $_SESSION['pere'];
		$menu_pere = $_SESSION['menu_pere'];
		$non_pere = $_SESSION['non_pere'];
		$mere = $_SESSION['mere'];
		$menu_mere = $_SESSION['menu_mere'];
		$non_mere = $_SESSION['non_mere'];
		$localite = $_SESSION['localite'];
		$menu_localite = $_SESSION['menu_localite'];
		$non_localite = $_SESSION['non_localite'];
		$paroisse = $_SESSION['paroisse'];
		$menu_paroisse = $_SESSION['menu_paroisse'];
		$non_paroisse = $_SESSION['non_paroisse'];
		$reference = $_SESSION['reference'];
		$menu_reference = $_SESSION['menu_reference'];
		$non_reference = $_SESSION['non_reference'];
		
	}
	
	// Si on a pas de recherche avancée déjà définie, on initialise nos variables vides.
	else{
		$andOr = "and";
		$dossiers = "";
		$menu_dossiers = "";
		$non_dossiers = "";
		$numero = "";
		$menu_numero = "";
		$non_numero = "";
		$migrant = "";
		$menu_migrant = "";
		$non_migrant = "";
		$sexe = "";
		$menu_sexe = "";
		$non_sexe = "";
		$naissance = "";
		$menu_naissance = "";
		$non_naissance = "";
		$bapteme = "";
		$menu_bapteme = "";
		$non_bapteme = "";
		$commune = "";
		$menu_commune = "";
		$non_commune = "";
		$pays = "";
		$menu_pays = "";
		$non_pays = "";
		$datemariage = "";
		$menu_datemariage = "";
		$non_datemariage = "";
		$datecontrat = "";
		$menu_datecontrat = "";
		$non_datecontrat = "";
		$metier = "";
		$menu_metier = "";
		$non_metier = "";
		$mention = "";
		$menu_mention = "";
		$non_mention = "";
		$occupation = "";
		$menu_occupation = "";
		$non_occupation = "";
		$status = "";
		$menu_status = "";
		$non_status = "";
		$mdatemariage = "";
		$menu_mdatemariage = "";
		$non_mdatemariage = "";
		$mlieumariage = "";
		$menu_mlieumariage = "";
		$non_mlieumariage = "";
		$lieumariage = "";
		$menu_lieumariage = "";
		$non_lieumariage = "";
		$identification = "";
		$menu_identification = "";
		$non_identification = "";
		$conjoint = "";
		$menu_conjoint = "";
		$non_conjoint = "";
		$lieudeces = "";
		$menu_lieudeces = "";
		$non_lieudeces = "";
		$datedeces = "";
		$menu_datedeces = "";
		$non_datedeces = "";
		$chercheur = "";
		$menu_chercheur = "";
		$non_chercheur = "";
		$copie = "";
		$menu_copie = "";
		$non_copie = "";
		$archive = "";
		$menu_archive = "";
		$non_archive = "";
		$publication = "";
		$menu_publication = "";
		$non_publication = "";
		$nom = "";
		$menu_nom = "";
		$non_nom = "";
		$surnom = "";
		$menu_surnom = "";
		$non_surnom = "";
		$surnom1 = "";
		$menu_surnom1 = "";
		$non_surnom1 = "";
		$pere = "";
		$menu_pere = "";
		$non_pere = "";
		$mere = "";
		$menu_mere = "";
		$non_mere = "";
		$localite = "";
		$menu_localite = "";
		$non_localite = "";
		$paroisse = "";
		$menu_paroisse = "";
		$non_paroisse = "";
		$lieucontrat = "";
		$menu_lieucontrat = "";
		$non_lieucontrat = "";
		$reference = "";
		$menu_reference = "";
		$non_reference = "";
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
		<a href="liste.php"><img src="Images/logo.png" alt="Fichier origine" style="width:350px;height:100px;"></a>
		<h1>Recherche avancée</h1>
	<div class="pageRA">
				<?php
				// Formulaire de recherche avancée. Pour chaque champ, détermine l'opérateur de recherche et la valeur.
				// On peut également définir l'option NOT, qui inverse la valeur recherchée (ex. NOT = devient !=, NOT < devient >=)
				// Pour chaque champ, si on a déjà une recherche en cours, on affiche la valeur déjà présente
				?>
				<form action="liste.php" method="post">
					<h1>Rechercher pour: </h1>
					<input type="radio" name="andOr" value="and" <?php if ($andOr == 'and') echo 'checked="checked"';?> /> <label for="and">Toutes les conditions</label>
					<input type="radio" name="andOr" value="or" <?php if($andOr == 'or') echo 'checked="checked"'?> /> <label for="or">Une ou l'autre condition</label>
					<div class="donnee">
					<div class="titreChamp2">Action</div>
					<input type="checkbox" name="non_dossiers" value="not" <?php if ($non_dossiers == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_dossiers" class="rechercheAvancee">
						<option value="Contient" <?php if ($menu_dossiers == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_dossiers == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_dossiers == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_dossiers == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_dossiers == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_dossiers == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_dossiers == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_dossiers == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_dossiers == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="dossiers" value="<?php echo htmlspecialchars($dossiers); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Numéro d'identification</div>
					<input type="checkbox" name="non_numero" value="not" <?php if ($non_numero == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_numero" class="rechercheAvancee">
						<option value="Contient" <?php if ($menu_numero == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_numero == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_numero == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_numero == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_numero == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_numero == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_numero == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_numero == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_numero == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="numero" value="<?php echo htmlspecialchars($numero); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Migrant</div>
					<input type="checkbox" name="non_migrant" value="not" <?php if ($non_migrant == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_migrant" class="rechercheAvancee">
						<option value="Contient" <?php if ($menu_migrant == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_migrant == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_migrant == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_migrant == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_migrant == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_migrant == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_migrant == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_migrant == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_migrant == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="migrant" value="<?php echo htmlspecialchars($migrant); ?>">
				</div>
				
				<div class="donnee">
					<div class="titreChamp2">Sexe</div>
					<input type="checkbox" name="non_sexe" value="not" <?php if ($non_sexe == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_sexe">
						<option value="Contient" <?php if ($menu_sexe == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_sexe == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_sexe == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_sexe == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_sexe == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_sexe == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_sexe == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_sexe == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_sexe == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="sexe" value="<?php echo htmlspecialchars($sexe); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Date de naissance</div>
					<input type="checkbox" name="non_naissance" value="not" <?php if ($non_naissance == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_naissance">
						<option value="Contient" <?php if ($menu_naissance == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_naissance == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_naissance == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_naissance == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_naissance == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_naissance == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_naissance == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_naissance == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_naissance == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="naissance" value="<?php echo htmlspecialchars($naissance); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Date de baptème</div>
					<input type="checkbox" name="non_bapteme" value="not" <?php if ($non_bapteme == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_bapteme">
						<option value="Contient" <?php if ($menu_bapteme == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_bapteme == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_bapteme == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_bapteme == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_bapteme == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_bapteme == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_bapteme == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_bapteme == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_bapteme == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="bapteme" value="<?php echo htmlspecialchars($bapteme); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Lieu d'origine</div>
					<input type="checkbox" name="non_commune" value="not" <?php if ($non_commune == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_commune">
						<option value="Contient" <?php if ($menu_commune == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_commune == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_commune == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_commune == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_commune == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_commune == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_commune == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_commune == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_commune == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="commune" value="<?php echo htmlspecialchars($commune); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Département ou pays</div>
					<input type="checkbox" name="non_pays" value="not" <?php if ($non_pays == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_pays">
						<option value="Contient" <?php if ($menu_pays == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_pays == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_pays == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_pays == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_pays == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_pays == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_pays == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_pays == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_pays == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="pays" value="<?php echo htmlspecialchars($pays); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Date de mariage des parents</div>
					<input type="checkbox" name="non_datemariage" value="not" <?php if ($non_datemariage == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_datemariage">
						<option value="Contient" <?php if ($menu_datemariage == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_datemariage == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_datemariage == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_datemariage == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_datemariage == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_datemariage == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_datemariage == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_datemariage == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_datemariage == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="datemariage" value="<?php echo htmlspecialchars($datemariage); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Date du contrat de mariage</div>
					<input type="checkbox" name="non_datecontrat" value="not" <?php if ($non_datecontrat == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_datecontrat">
						<option value="Contient" <?php if ($menu_datecontrat == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_datecontrat == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_datecontrat == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_datecontrat == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_datecontrat == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_datecontrat == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_datecontrat == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_datecontrat == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_datecontrat == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="datecontrat" value="<?php echo htmlspecialchars($datecontrat); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Lieu du contrat</div>
					<input type="checkbox" name="lieucontrat" value="not" <?php if ($non_lieucontrat == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_lieucontrat">
						<option value="Contient" <?php if ($menu_lieucontrat == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_lieucontrat == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_lieucontrat == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_lieucontrat == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_lieucontrat == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_lieucontrat == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_lieucontrat == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_lieucontrat == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_lieucontrat == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="lieucontrat" value="<?php echo htmlspecialchars($lieucontrat); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Profession du père</div>
					<input type="checkbox" name="non_metier" value="not" <?php if ($non_metier == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_metier">
						<option value="Contient" <?php if ($menu_metier == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_metier == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_metier == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_metier == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_metier == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_metier == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_metier == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_metier == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_metier == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="metier" value="<?php echo htmlspecialchars($metier); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Première mention au pays</div>
					<input type="checkbox" name="non_mention" value="not" <?php if ($non_mention == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_mention">
						<option value="Contient" <?php if ($menu_mention == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_mention == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_mention == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_mention == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_mention == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_mention == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_mention == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_mention == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_mention == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="mention" value="<?php echo htmlspecialchars($mention); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Occupation à l'arrivée</div>
					<input type="checkbox" name="non_occupation" value="not" <?php if ($non_occupation == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_occupation">
						<option value="Contient" <?php if ($menu_occupation == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_occupation == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_occupation == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_occupation == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_occupation == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_occupation == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_occupation == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_occupation == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_occupation == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="occupation" value="<?php echo htmlspecialchars($occupation); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Statut matrimonial</div>
					<input type="checkbox" name="non_status" value="not" <?php if ($non_status == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_status">
						<option value="Contient" <?php if ($menu_status == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_status == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_status == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_status == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_status == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_status == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_status == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_status == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_status == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="status" value="<?php echo htmlspecialchars($status); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Date de mariage</div>
					<input type="checkbox" name="non_mdatemariage" value="not" <?php if ($non_mdatemariage == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_mdatemariage">
						<option value="Contient" <?php if ($menu_mdatemariage == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_mdatemariage == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_mdatemariage == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_mdatemariage == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_mdatemariage == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_mdatemariage == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_mdatemariage == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_mdatemariage == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_mdatemariage == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="mdatemariage" value="<?php echo htmlspecialchars($mdatemariage); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Lieu de mariage</div>
					<input type="checkbox" name="non_mlieumariage" value="not" <?php if ($non_mlieumariage == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_mlieumariage">
						<option value="Contient" <?php if ($menu_mlieumariage == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_mlieumariage == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_mlieumariage == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_mlieumariage == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_mlieumariage == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_mlieumariage == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_mlieumariage == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_mlieumariage == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_mlieumariage == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="mlieumariage" value="<?php echo htmlspecialchars($mlieumariage); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Lieu de mariage des parents</div>
					<input type="checkbox" name="non_lieumariage" value="not" <?php if ($non_lieumariage == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_lieumariage">
						<option value="Contient" <?php if ($menu_lieumariage == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_lieumariage == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_lieumariage == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_lieumariage == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_lieumariage == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_lieumariage == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_lieumariage == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_lieumariage == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_lieumariage == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="lieumariage" value="<?php echo htmlspecialchars($lieumariage); ?>">
				</div>
					<div class="donnee">
					<div class="titreChamp2">Identification du migrant</div>
					<input type="checkbox" name="non_identification" value="not" <?php if ($non_identification == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_identification">
						<option value="Contient" <?php if ($menu_identification == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_identification == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_identification == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_identification == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_identification == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_identification == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_identification == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_identification == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_identification == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="identification" value="<?php echo htmlspecialchars($identification); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Premier conjoint</div>
					<input type="checkbox" name="non_conjoint" value="not" <?php if ($non_conjoint == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_conjoint">
						<option value="Contient" <?php if ($menu_conjoint == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_conjoint == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_conjoint == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_conjoint == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_conjoint == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_conjoint == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_conjoint == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_conjoint == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_conjoint == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="conjoint" value="<?php echo htmlspecialchars($conjoint); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Lieu de décès ou inhumation</div>
					<input type="checkbox" name="non_lieudeces" value="not" <?php if ($non_lieudeces == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_lieudeces">
						<option value="Contient" <?php if ($menu_lieudeces == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_lieudeces == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_lieudeces == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_lieudeces == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_lieudeces == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_lieudeces == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_lieudeces == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_lieudeces == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_lieudeces == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="lieudeces" value="<?php echo htmlspecialchars($lieudeces); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Date de décès ou inhumation</div>
					<input type="checkbox" name="non_datedeces" value="not" <?php if ($non_datedeces == "not") echo "checked='checked'"; ?> /> <label for="not">NOT</label>
					<select name="menu_datedeces">
						<option value="Contient" <?php if ($menu_datedeces == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_datedeces == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_datedeces == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_datedeces == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_datedeces == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_datedeces == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_datedeces == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_datedeces == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_datedeces == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="datedeces" value="<?php echo htmlspecialchars($datedeces); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Chercheur(s)</div>
					<input type="checkbox" name="non_chercheur" value="not" <?php if ($non_chercheur == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_chercheur">
						<option value="Contient" <?php if ($menu_chercheur == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_chercheur == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_chercheur == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_chercheur == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_chercheur == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_chercheur == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_chercheur == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_chercheur == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_chercheur == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="chercheur" value="<?php echo htmlspecialchars($chercheur); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Copie d'acte</div>
					<input type="checkbox" name="non_copie" value="not" <?php if ($non_copie == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_copie">
						<option value="Contient" <?php if ($menu_copie == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_copie == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_copie == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_copie == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_copie == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_copie == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_copie == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_copie == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_copie == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="copie" value="<?php echo htmlspecialchars($copie); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Dossier d'archive</div>
					<input type="checkbox" name="non_archive" value="not" <?php if ($non_archive == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_archive">
						<option value="Contient" <?php if ($menu_archive == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_archive == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_archive == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_archive == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_archive == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_archive == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_archive == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_archive == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_archive == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="archive" value="<?php echo htmlspecialchars($archive); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Publication</div>
					<input type="checkbox" name="non_publication" value="not" <?php if ($non_publication == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_publication">
						<option value="Contient" <?php if ($menu_publication == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_publication == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_publication == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_publication == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_publication == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_publication == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_publication == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_publication == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_publication == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="publication" value="<?php echo htmlspecialchars($publication); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Premier nom</div>
					<input type="checkbox" name="non_nom" value="not" <?php if ($non_nom == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_nom">
						<option value="Contient" <?php if ($menu_nom == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_nom == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_nom == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_nom == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_nom == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_nom == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_nom == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_nom == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_nom == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="nom" value="<?php echo htmlspecialchars($nom); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Premier surnom</div>
					<input type="checkbox" name="non_surnom" value="not" <?php if ($non_surnom == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_surnom">
						<option value="Contient" <?php if ($menu_surnom == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_surnom == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_surnom == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_surnom == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_surnom == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_surnom == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_surnom == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_surnom == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_surnom == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="surnom" value="<?php echo htmlspecialchars($surnom); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Deuxième surnom</div>
					<input type="checkbox" name="non_surnom1" value="not" <?php if ($non_surnom1 == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_surnom1">
						<option value="Contient" <?php if ($menu_surnom1 == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_surnom1 == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_surnom1 == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_surnom1 == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_surnom1 == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_surnom1 == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_surnom1 == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_surnom1 == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_surnom1 == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="surnom1" value="<?php echo htmlspecialchars($surnom1); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Nom père</div>
					<input type="checkbox" name="non_pere" value="not" <?php if ($non_pere == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_pere">
						<option value="Contient" <?php if ($menu_pere == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_pere == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_pere == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_pere == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_pere == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_pere == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_pere == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_pere == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_pere == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="pere" value="<?php echo htmlspecialchars($pere); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Nom mère</div>
					<input type="checkbox" name="non_mere" value="not" <?php if ($non_mere == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_mere">
						<option value="Contient" <?php if ($menu_mere == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_mere == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_mere == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_mere == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_mere == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_mere == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_mere == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_mere == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_mere == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="mere" value="<?php echo htmlspecialchars($mere); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Localité actuelle</div>
					<input type="checkbox" name="non_localite" value="not" <?php if ($non_localite == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_localite">
						<option value="Contient" <?php if ($menu_localite == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_localite == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_localite == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_localite == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_localite == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_localite == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_localite == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_localite == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_localite == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="localite" value="<?php echo htmlspecialchars($localite); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Paroisse religieuse</div>
					<input type="checkbox" name="non_paroisse" value="not" <?php if ($non_paroisse == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_paroisse">
						<option value="Contient" <?php if ($menu_paroisse == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_paroisse == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_paroisse == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_paroisse == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_paroisse == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_paroisse == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_paroisse == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_paroisse == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_paroisse == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="paroisse" value="<?php echo htmlspecialchars($paroisse); ?>">
				</div>
				<div class="donnee">
					<div class="titreChamp2">Référence</div>
					<input type="checkbox" name="non_reference" value="not" <?php if ($non_reference == "not") echo "checked='checked'"; ?>/> <label for="not">NOT</label>
					<select name="menu_reference">
						<option value="Contient" <?php if ($menu_reference == "Contient") echo "selected"; ?>>Contient</option>
						<option value="Egal" <?php if ($menu_reference == "Egal") echo "selected"; ?>>Égal</option>
						<option value="DebuterAvec" <?php if ($menu_reference == "DebuterAvec") echo "selected"; ?>>Débuter avec...</option>
						<option value="PlusQue" <?php if ($menu_reference == "PlusQue") echo "selected"; ?>>Plus que...</option>
						<option value="MoinsQue" <?php if ($menu_reference == "MoinsQue") echo "selected"; ?>>Moins que...</option>
						<option value="EgalOuPlus" <?php if ($menu_reference == "EgalOuPlus") echo "selected"; ?>>Égal ou plus que...</option>
						<option value="EgalOuMoins" <?php if ($menu_reference == "EgalOuMoins") echo "selected"; ?>>Égal ou moins que...</option>
						<option value="Entre" <?php if ($menu_reference == "Entre") echo "selected"; ?>>Entre</option>
						<option value="Vide" <?php if ($menu_reference == "Vide") echo "selected"; ?>>Vide</option>
					</select>
					<input class="rechercheAvancee" type="text" name="reference" value="<?php echo htmlspecialchars($reference); ?>">
				</div>
				
					<input type="submit" value="Chercher">
					</form>
					<form action="rechercheAvancee.php" method="post">
					<input type="submit" name="reset" value="Réinitialiser" />
					</form>
			<p><a href="liste.php">Retour au menu principal</a></p>
		</div>
	</body>
</html>
	
