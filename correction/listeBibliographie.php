<?php
	// Page principale de l'interface de gestion.
	// Cette page affiche une liste des fiches présentes dans la BD.
	// Elle gère également les différents modes de recherche.

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

	// On détermine la page sur laquelle on est. Si aucune, on assume la première.
	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;

	// On prend le nombre d'enregistrements par page.
	// Fonctionnalité non dévelopée, mais gérée pour le futur.
	if (isset($_GET['nbPerPage']))
		$nbPerPage = $_GET['nbPerPage'];
	else
		$nbPerPage = 24;
	
	// Si on a cliqué sur "Recherche", on assigne les variables de valeur et de champ.
	if (isset($_GET['recherche'])){
		$recherche = $_GET['recherche'];
		$_SESSION['rechercheB'] = $recherche;
	}
	// Si on avait cliqué sur "Recherche", puis changé de page, on prend les valeurs des variables de session assignées plus haut.
	else if (isset($_SESSION['rechercheB'])){
		$recherche = $_SESSION['rechercheB'];
	}
	// Si on a jamais cliqué sur "Recherche", on prend la valeur par défaut qui affiche tous les résultats.
	else{
		$recherche = "";
	}
	
	unset($_SESSION['publie']);
	unset($_SESSION['andOr']);
	unset($_SESSION['recherche']);
	unset($_SESSION['rechercheChamp']);
// Début du HTML
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Fichier Origine</title>
		<link rel="stylesheet" media="(min-width: 720px)" href="style.css">
		<link rel="stylesheet" media="(max-width: 720px)" href="styleNarrow.css">
		<meta name="description" content="Page d'accueil du site de modifications">
		<meta name="author" content="Marc-Albert Fournier">
	</head>
	<body>
		<a href="liste.php?reset=1"><img src="Images/logo.png" alt="Fichier origine" style="width:350px;height:100px;"></a>
		<h1>Bibliographie</h1>
		<p> <a href="index.php?a=logout">Fin de session</a> | <a href="listeAcro.php">Gestion des acronymes</a> | <a href="liste.php">Retour à la liste des migrants</a><?php if (CheckSecurity("Export")) { ?> | <a href="excel.php?recherche=<?php echo $recherche;?>&pagId=2">Exporter les données</a><?php } ?></p>
		<?php
		// On définit les variables utilisées pour limiter le nombre de résultats par page.
		$limit = $nbPerPage;
		$offset = ($page-1) * $nbPerPage;

		// Booléen servant à l'affichage alterné entre deux couleurs sur la page.
		$darker = true;		
		
		$link = connexionBD();

		$query = "select id, auteur, titre from bibliographie where id like '%".mysqli_real_escape_string($link, $recherche)."%' or auteur like '%".mysqli_real_escape_string($link, $recherche)."%' or titre like '%".mysqli_real_escape_string($link, $recherche)."%' LIMIT $limit OFFSET $offset";
		$qcount = "select count(*) from bibliographie where id like '%".mysqli_real_escape_string($link, $recherche)."%' or auteur like '%".mysqli_real_escape_string($link, $recherche)."%' or titre like '%".mysqli_real_escape_string($link, $recherche)."%'";
		
		// Les execute les requêtes.
		$c = mysqli_query($link,$qcount);
		$r = mysqli_query($link,$query);
		deconnexionBD($link);


		
		// Début du menu
		?>
		<div class="menuIndex">
			<?php
			// Si on peut créer des nouvelles fiches, le bouton menant vers l'ajout apparait
			if (CheckSecurity("Add")) { ?> <div class="menuNouveau">
				<a href="modifierBibliographie.php">Ajouter nouveau</a>
			</div><?php } 
			// Menu de recherche. Utilise la variable $rechercheChamp pour déterminer quelle option est sétectionnée.
			?><div class="menuRecherche">
				Recherche :
				<form action="listeBibliographie.php" method="get">
					
					<input type="text" name="recherche" class="menuRechInput" value="<?php echo htmlspecialchars($recherche); ?>">
					<input type="submit" value="Recherche" class="menuRechInput">
				</form>
			</div>
			<div class="menuNbPage">
				Nb. par page: <?php echo $nbPerPage; ?>
			</div>
		</div>
		<?php // Fin du menu, début du tableau de fiches.
		?>
		<div class="indexTab">
			<div class="tabTitles">
				<?php 
				// Si on a accès à la modification, on affiche la colonne "Éditer"
				if (CheckSecurity("Edit")) { ?><div class="bibliographieEdit"><img src="Images/icon_edit.gif" alt="Éditer"></div><?php } ?>
				<div class="bibliographieID">ID</div>
				<div class="bibliographieNumero">Auteur</div>
				<div class="bibliographieNom">Titre</div>
			</div>
			<?php
			while($row = mysqli_fetch_row($r)){
				$id = htmlspecialchars($row[0]);
				$auteur = htmlspecialchars($row[1]);
				$titre = htmlspecialchars($row[2]);
				?>
			<div class="tabBibliographie<?php if ($darker) echo " bibliographieDarker"; ?>">
				<?php 
				// Si on a accès à la modification, on affiche la colonne "Éditer". Sinon, on offre un lien sur le nom du migrant pour voir la fiche.
				// On a pas laissé le lien sur le nom du migrant quand la fiche peut être modifiée pour raisons d'esthétisme
				if (CheckSecurity("Edit")) { ?><div class="bibliographieEdit"><a href="modifierBibliographie.php?id=<?php echo $id; ?>">Éditer</a></div><?php } ?>
				<div class="bibliographieID"><?php echo $id; ?></div>
				<div class="bibliographieNumero"><?php echo $auteur; ?></div>
				<div class="bibliographieNom"><?php echo $titre; ?></div>
			</div>
			<?php
			// À chaque migrant affiché, on alterne entre sombre et clair comme couleur de fond.
			$darker = !$darker;
			}
			?>
		</div>

		<?php
		// On crée les variables associées au pager.
		$count = mysqli_fetch_row($c);
		$count = $count[0];
		$de = $offset+1;
		$a = ($offset+$nbPerPage > $count)? $count : $offset+$nbPerPage;
		$lastPage = ceil($count/$nbPerPage);
		
		if ($count > 0)
			echo "<p>Affichage des éléments $de à $a sur $count de la base de données.</p>";
		else
			echo "<p>Aucun résultat ne correspond à votre recherche.</p>";

		// D'autres variables pour le pager
		$i = ($page < 5)? 1 : $page - 4 ;
		$i = ($i+10 > $count / $nbPerPage) ? max(1, $lastPage - 9) : $i;
		$lastCount = ($i+10 < $lastPage) ? $i+10: $lastPage;
		
		// Création du pager. Si on est loin de la première page, elle s'affiche en premier, à part.
		if ($i > 1){
			?>
			<form action="listeBibliographie.php" method="get">
				<input type="hidden" name="page" value="1">
				<input type="hidden" name="recherche" value="<?php echo htmlspecialchars($recherche); ?>">
				<input type="hidden" name="nbPerPage" value="<?php echo $nbPerPage; ?>">
				<input type="submit" value="1" class="pager">
			</form>
			<div class="pagerPadding">...</div>
			<?php
		}
		// Affiche une dizaine de pages autour de la page actuelle.
		for($j=$i;$j<=$lastCount;$j++){
			?>
			<form action="listeBibliographie.php" method="get">
				<input type="hidden" name="page" value="<?php echo $j; ?>">
				<input type="hidden" name="recherche" value="<?php echo htmlspecialchars($recherche); ?>">
				<input type="hidden" name="nbPerPage" value="<?php echo $nbPerPage; ?>">
				<input type="submit" <?php if($j==$page) echo 'id="currentPage"'?> class="pager" value="<?php echo $j; ?>">
			</form>
			<?php
		}
		// Si on est loin de la dernière page, elle s'affiche en dernier, à part.
		if ($lastCount < $lastPage){
			?>
			<div class="pagerPadding">...</div>
			<form action="listeBibliographie.php" method="get">
				<input type="hidden" name="page" value="<?php echo $lastPage; ?>">
				<input type="hidden" name="recherche" value="<?php echo htmlspecialchars($recherche); ?>">
				<input type="hidden" name="nbPerPage" value="<?php echo $nbPerPage; ?>">
				<input type="submit" value="<?php echo $lastPage; ?>" class="pager">
			</form>
			<?php
		}
		?>
	</body>
</html>
