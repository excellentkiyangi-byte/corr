<?php 
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

	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;

	if (isset($_GET['nbPerPage']))
		$nbPerPage = $_GET['nbPerPage'];
	else
		$nbPerPage = 24;

	if (isset($_GET['recherche']))
		$recherche = $_GET['recherche'];
	else
		$recherche = '';

	unset($_SESSION['publie']);
	unset($_SESSION['andOr']);
	unset($_SESSION['recherche']);
	unset($_SESSION['rechercheChamp']);
	unset($_SESSION['rechercheB']);
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Fichier Origine - Acronymes</title>
		<link rel="stylesheet" media="(min-width: 720px)" href="style.css">
		<link rel="stylesheet" media="(max-width: 720px)" href="styleNarrow.css">
		<meta name="description" content="Page listant les acronymes des références">
		<meta name="author" content="Marc-Albert Fournier">
	</head>
	<body>
		<a href="liste.php"><img src="Images/logo.png" alt="Fichier origine" style="width:350px;height:100px;"></a>
		<h1>Liste des acronymes</h1>
		
		<?php

		// On définit les variables utilisées pour limiter le nombre de résultats par page.
		$limit = $nbPerPage;
		$offset = ($page-1) * $nbPerPage;
	
		
		// Booléen servant à l'affichage alterné entre deux couleurs sur la page.
		$darker = true;
		
		$link = connexionBD();
		$query = "select id, acronyme, definition from acronyme where acronyme like '%".mysqli_real_escape_string($link, $recherche)."%' or definition like '%".mysqli_real_escape_string($link, $recherche)."%' or id = '".mysqli_real_escape_string($link, $recherche)."' limit $limit offset $offset";
		$qcount = "select count(*) from acronyme where acronyme like '%".mysqli_real_escape_string($link, $recherche)."%' or definition like '%".mysqli_real_escape_string($link, $recherche)."%' or id = '".mysqli_real_escape_string($link, $recherche)."'";
		$r = mysqli_query($link, $query);
		$c = mysqli_query($link, $qcount);
		
		
		// Barre de texte au dessus du menu. Contient:
		//	- Un lien pour terminer la session et se déconnecter.
		// 	- Un lien vers le remplacement de texte (si on a accès à la modification)
		// 	- Un lien pour afficher les fiches non publiées (si on a accès à la modification)
		?>
		<p> <a href="index.php?a=logout">Fin de session</a> | <a href="listeBibliographie.php">Gestion des bibliographies</a> | <a href="liste.php">Retour à la liste des migrants</a><?php if (CheckSecurity("Export")) { ?> | <a href="excel.php?recherche=<?php echo $recherche;?>&pagId=1">Exporter les données</a><?php } ?></p>
		<?php

		// Si on peut créer des nouvelles fiches, le bouton menant vers l'ajout apparait
			?>
		<div class="menuIndex">
			<?php if (CheckSecurity("Add")) { ?>
			<div class="menuNouveau">
				<a href="modifierAcro.php">Ajouter nouveau</a>
			</div><?php }
			// Menu de recherche. Utilise la variable $rechercheChamp pour déterminer quelle option est sétectionnée.
			?><div class="menuRechercheA">
				Recherche :
				<form action="listeAcro.php" method="get">
					<input type="text" name="recherche" class="menuRechInputA" value="<?php echo htmlspecialchars($recherche); ?>">
					<input type="submit" value="Recherche" class="menuRechInputA">
				</form>
			</div>
			<div class="menuNbPage">
				Nb. par page: <?php echo $nbPerPage; ?>
			</div>
		</div>
		
		<div class="acroTab">
			<div class="tabTitlesAcro">
				<?php 
				// Si on a accès à la modification, on affiche la colonne "Éditer"
				if (CheckSecurity("Edit")) { ?>
				<div class="acroEdit"><img src="Images/icon_edit.gif" alt="Éditer"></div><?php } ?>
				<div class="acroID">ID</div>
				<div class="acroAcro">Acronyme</div>
				<div class="acroDefinition">Définition</div>
			</div>
			<?php
			while($row = mysqli_fetch_row($r)){
				$id = htmlspecialchars($row[0]);
				$acronyme = htmlspecialchars($row[1]);
				$definition = htmlspecialchars($row[2]);
				?>
			<div class="tabAcro<?php if ($darker) echo " acroDarker"; ?>">
				<?php 
				// Si on a accès à la modification, on affiche la colonne "Éditer"
				if (CheckSecurity("Edit")) { ?> 
				<div class="acroEdit"><a href="modifierAcro.php?id=<?php echo $id; ?>">Éditer</a></div><?php } ?>
				<div class="acroID"><?php echo $id; ?></div>
				<div class="acroAcro"><?php echo $acronyme; ?></div>
				<div class="acroDefinition"><?php echo $definition; ?></div>
			</div>
			<?php 
			$darker = !$darker;
			} ?>
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
			<form action="listeAcro.php" method="get">
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
			<form action="listeAcro.php" method="get">
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
			<form action="listeAcro.php" method="get">
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
