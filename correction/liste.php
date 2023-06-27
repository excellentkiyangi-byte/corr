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

	// Si on a cliqué sur le logo, on réinitialise les recherches.
	if (isset($_GET['reset'])){
		unset($_SESSION['publie']);
		unset($_SESSION['andOr']);
		unset($_SESSION['recherche']);
		unset($_SESSION['rechercheChamp']);
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
		$nbPerPage = 20;
	
	// Si on a cliqué sur "Recherche", on assigne les variables de valeur et de champ.
	if (isset($_GET['recherche'])){
		$recherche = $_GET['recherche'];
		$rechercheChamp = $_GET['rechercheChamp'];
		$_SESSION['recherche'] = $recherche;
		$_SESSION['rechercheChamp'] = $rechercheChamp;
	}
	// Si on a cliqué sur le bouton "Recherche", on déréférence la recherche avancée et le fait qu'on veuille les fiches non publiées.
	if (isset($_GET['bouton_recherche'])){
		unset($_SESSION['andOr']);
		unset($_SESSION['publie']);
	}
	// Si on avait cliqué sur "Recherche", puis changé de page, on prend les valeurs des variables de session assignées plus haut.
	else if (isset($_SESSION['recherche'])){
		$recherche = $_SESSION['recherche'];
		$rechercheChamp = $_SESSION['rechercheChamp'];
	}
	// Si on a jamais cliqué sur "Recherche", on prend la valeur par défaut qui affiche tous les résultats.
	else{
		$recherche = "";
		$rechercheChamp = "all";
	}
	$rechercheChamp = preg_replace('#[^a-z]#', '', $rechercheChamp);

	unset($_SESSION['rechercheB']);
	
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
		<h1>Édition de la base de données</h1>
		
		<?php
		// On définit les variables utilisées pour limiter le nombre de résultats par page.
		$limit = $nbPerPage;
		$offset = ($page-1) * $nbPerPage;

		// Booléen servant à l'affichage alterné entre deux couleurs sur la page.
		$darker = true;		
		
		$link = connexionBD();

		// Si on a cliqué sur "Fiches à valider"
		if (isset($_GET['publie'])){
			$query = "select migrant, numero, id from origine where publication !=''";
			$qcount = "select count(*) from origine where publication !=''";
			$_SESSION['publie'] = $_GET['publie'];
			unset($_SESSION['andOr']); 
			unset($_SESSION['recherche']);
			unset($_SESSION['rechercheChamp']);
			//$query .= $where."LIMIT $limit OFFSET $offset";
		}
		// Si on arrive de la "Recherche avancée"
		else if (isset($_POST['andOr'])){
			$andOr = $_POST['andOr'];
			if ($andOr != "and" && $andOr != "or") $andOr = "and";
			$_SESSION['andOr'] = $andOr;
			unset($_SESSION['publie']);
			unset($_SESSION['recherche']);
			unset($_SESSION['rechercheChamp']);
			$query = "select migrant, numero, id from origine";
			$qcount = "select count(*) from origine";
			
			$where = "";
			$compteur = 0;
			
			// addToWhere ajoute à la chaîne entrante une suite de forme "(where|and|or) champ operateur valeur"
			$where .= addToWhere("dossiers", $andOr, $compteur, $link);
			$where .= addToWhere("numero", $andOr, $compteur, $link);
			$where .= addToWhere("migrant", $andOr, $compteur, $link);
			$where .= addToWhere("sexe", $andOr, $compteur, $link);
			$where .= addToWhere("naissance", $andOr, $compteur, $link);
			$where .= addToWhere("bapteme", $andOr, $compteur, $link);
			$where .= addToWhere("commune", $andOr, $compteur, $link);
			$where .= addToWhere("pays", $andOr, $compteur, $link);
			$where .= addToWhere("datemariage", $andOr, $compteur, $link);
			$where .= addToWhere("datecontrat", $andOr, $compteur, $link);
			$where .= addToWhere("lieucontrat",$andOr, $compteur, $link);
			$where .= addToWhere("metier", $andOr, $compteur, $link);
			$where .= addToWhere("mention", $andOr, $compteur, $link);
			$where .= addToWhere("occupation", $andOr, $compteur, $link);
			$where .= addToWhere("status", $andOr, $compteur, $link);
			$where .= addToWhere("mdatemariage", $andOr, $compteur, $link);
			$where .= addToWhere("mlieumariage", $andOr, $compteur, $link);
			$where .= addToWhere("conjoint", $andOr, $compteur, $link);
			$where .= addToWhere("lieudeces", $andOr, $compteur, $link);
			$where .= addToWhere("datedeces", $andOr, $compteur, $link);
			$where .= addToWhere("chercheur", $andOr, $compteur, $link);
			$where .= addToWhere("copie", $andOr, $compteur, $link);
			$where .= addToWhere("archive", $andOr, $compteur, $link);
			$where .= addToWhere("publication", $andOr, $compteur, $link);
			$where .= addToWhere("nom", $andOr, $compteur, $link);
			$where .= addToWhere("surnom", $andOr, $compteur, $link);
			$where .= addToWhere("surnom1", $andOr, $compteur, $link);
			$where .= addToWhere("localite", $andOr, $compteur, $link);
			$where .= addToWhere("paroisse", $andOr, $compteur, $link);
			$where .= addToWhere("pere", $andOr, $compteur, $link);
			$where .= addToWhere("mere", $andOr, $compteur, $link);
			$where .= addToWhere("lieumariage", $andOr, $compteur, $link);
			$where .= addToWhere("identification", $andOr, $compteur, $link);
			$where .= addToWhere("reference", $andOr, $compteur, $link);
			
			$query .= $where." LIMIT $limit OFFSET $offset";
			$qcount .= $where;
			
			//echo $query;
		}
		// Sinon, si on a changé de page pendant qu'on navigue les "Fiches à valider"
		else if(isset($_SESSION['publie'])){
			$publie = $_SESSION['publie'];
			$query = "select migrant, numero, id from origine where publication !=''";
			$qcount = "select count(*) from origine where publication !=''";
			$query .= $where." LIMIT $limit OFFSET $offset";
		}
		// Sinon, si on a changé de page pendant une "Recherche avancée"
		else if (isset($_SESSION['andOr'])){
			$andOr = $_SESSION['andOr'];
			if ($andOr != "and" && $andOr != "or") $andOr = "and";
			$query = "select migrant, numero, id from origine";
			$qcount = "select count(*) from origine";
			
			$where = "";
			$compteur = 0;
			
			// Même chose que addToWhere, mais en utilisant les variables _SESSION au lieu des _POST
			$where .= addToWhereSess("dossiers", $andOr, $compteur, $link);
			$where .= addToWhereSess("numero", $andOr, $compteur, $link);
			$where .= addToWhereSess("migrant", $andOr, $compteur, $link);
			$where .= addToWhereSess("sexe", $andOr, $compteur, $link);
			$where .= addToWhereSess("naissance", $andOr, $compteur, $link);
			$where .= addToWhereSess("bapteme", $andOr, $compteur, $link);
			$where .= addToWhereSess("commune", $andOr, $compteur, $link);
			$where .= addToWhereSess("pays", $andOr, $compteur, $link);
			$where .= addToWhereSess("datemariage", $andOr, $compteur, $link);
			$where .= addToWhereSess("datecontrat", $andOr, $compteur, $link);
			$where .= addToWhereSess("lieucontrat",$andOr, $compteur, $link);
			$where .= addToWhereSess("metier", $andOr, $compteur, $link);
			$where .= addToWhereSess("mention", $andOr, $compteur, $link);
			$where .= addToWhereSess("occupation", $andOr, $compteur, $link);
			$where .= addToWhereSess("status", $andOr, $compteur, $link);
			$where .= addToWhereSess("mdatemariage", $andOr, $compteur, $link);
			$where .= addToWhereSess("mlieumariage", $andOr, $compteur, $link);
			$where .= addToWhereSess("conjoint", $andOr, $compteur, $link);
			$where .= addToWhereSess("lieudeces", $andOr, $compteur, $link);
			$where .= addToWhereSess("datedeces", $andOr, $compteur, $link);
			$where .= addToWhereSess("chercheur", $andOr, $compteur, $link);
			$where .= addToWhereSess("copie", $andOr, $compteur, $link);
			$where .= addToWhereSess("archive", $andOr, $compteur, $link);
			$where .= addToWhereSess("publication", $andOr, $compteur, $link);
			$where .= addToWhereSess("nom", $andOr, $compteur, $link);
			$where .= addToWhereSess("surnom", $andOr, $compteur, $link);
			$where .= addToWhereSess("surnom1", $andOr, $compteur, $link);
			$where .= addToWhereSess("localite", $andOr, $compteur, $link);
			$where .= addToWhereSess("paroisse", $andOr, $compteur, $link);
			$where .= addToWhereSess("pere", $andOr, $compteur, $link);
			$where .= addToWhereSess("mere", $andOr, $compteur, $link);
			$where .= addToWhereSess("lieumariage", $andOr, $compteur, $link);
			$where .= addToWhereSess("identification", $andOr, $compteur, $link);
			$where .= addToWhereSess("reference", $andOr, $compteur, $link);
			
			$query .= $where." LIMIT $limit OFFSET $offset";
			$qcount .= $where;
			
			//echo "sess : ".$query;
		}
		// Sinon, si on est en mode "Recherche" utilisant tous les champs.
		else if ($rechercheChamp == "all") {
			$query = "select migrant, numero, id from origine where ";
			$qcount = "select count(*) from origine where ";
			
			$where = "";
			$compteur = 0;
			
			$where .= addToWhereAll("dossiers", $recherche, $compteur, $link);
			$where .= addToWhereAll("numero", $recherche, $compteur, $link);
			$where .= addToWhereAll("migrant", $recherche, $compteur, $link);
			$where .= addToWhereAll("sexe", $recherche, $compteur, $link);
			$where .= addToWhereAll("naissance", $recherche, $compteur, $link);
			$where .= addToWhereAll("bapteme", $recherche, $compteur, $link);
			$where .= addToWhereAll("commune", $recherche, $compteur, $link);
			$where .= addToWhereAll("pays", $recherche, $compteur, $link);
			$where .= addToWhereAll("datemariage", $recherche, $compteur, $link);
			$where .= addToWhereAll("lieumariage", $recherche, $compteur, $link);
			$where .= addToWhereAll("datecontrat", $recherche, $compteur, $link);
			$where .= addToWhereAll("lieucontrat", $recherche, $compteur, $link);
			$where .= addToWhereAll("metier", $recherche, $compteur, $link);
			$where .= addToWhereAll("mention", $recherche, $compteur, $link);
			$where .= addToWhereAll("occupation", $recherche, $compteur, $link);
			$where .= addToWhereAll("status", $recherche, $compteur, $link);
			$where .= addToWhereAll("mdatemariage", $recherche, $compteur, $link);
			$where .= addToWhereAll("mlieumariage", $recherche, $compteur, $link);
			$where .= addToWhereAll("conjoint", $recherche, $compteur, $link);
			$where .= addToWhereAll("lieudeces", $recherche, $compteur, $link);
			$where .= addToWhereAll("datedeces", $recherche, $compteur, $link);
			$where .= addToWhereAll("chercheur", $recherche, $compteur, $link);
			$where .= addToWhereAll("copie", $recherche, $compteur, $link);
			$where .= addToWhereAll("actenumerises", $recherche, $compteur, $link);
			$where .= addToWhereAll("archive", $recherche, $compteur, $link);
			$where .= addToWhereAll("publication", $recherche, $compteur, $link);
			$where .= addToWhereAll("nom", $recherche, $compteur, $link);
			$where .= addToWhereAll("surnom", $recherche, $compteur, $link);
			$where .= addToWhereAll("surnom1", $recherche, $compteur, $link);
			$where .= addToWhereAll("localite", $recherche, $compteur, $link);
			$where .= addToWhereAll("paroisse", $recherche, $compteur, $link);
			
			$query .= $where." LIMIT $limit OFFSET $offset";
			$qcount .= $where;
			
			// echo "all : ".$query;
		}
		// Si on a aucun des cas ci-dessus, on assume une recherche avec un seul champ
		// (Si on avait pas lancé de recherche, les valeurs par défaut assurent qu'on affichera tous les éléments)
		else{
			$query = "select migrant, numero, id from origine where ".mysqli_real_escape_string($link,$rechercheChamp)." like '%".mysqli_real_escape_string($link, $recherche)."%' LIMIT $limit OFFSET $offset";
			$qcount = "select count(*) from origine where ".mysqli_real_escape_string($link,$rechercheChamp)." like '%".mysqli_real_escape_string($link, $recherche)."%'";
		}

		// Les requêtes ont été créées par le bloc de conditions précédent. On peut maintenant les exécuter.
		$c = mysqli_query($link,$qcount);
		$r = mysqli_query($link,$query);
		deconnexionBD($link);


		// Barre de texte au dessus du menu. Contient:
		//	- Un lien pour terminer la session et se déconnecter.
		// 	- Un lien vers le remplacement de texte (si on a accès à la modification)
		// 	- Un lien pour afficher les fiches non publiées (si on a accès à la modification)
		?>
		<p> <a href="index.php?a=logout">Fin de session</a> <?php if (CheckSecurity("Edit")) { ?> | <a href="remplacerChaine.php">Remplacement de texte</a> | <a href="liste.php?publie=1">Fiches non publiées</a> <?php } ?> | <a href="listeAcro.php">Gestion des acronymes</a> | <a href="listeBibliographie.php">Gestion des bibliographies</a><?php if (CheckSecurity("Export")) { ?> | <a href="excel.php?recherche=<?php echo $recherche;?>&rechercheChamp=<?php echo $rechercheChamp;?>">Exporter les données</a><?php } ?></p>
		<?php
		// Début du menu
		?>
		<div class="menuIndex">
			<?php
			// Si on peut créer des nouvelles fiches, le bouton menant vers l'ajout apparait
			if (CheckSecurity("Add")) { ?> <div class="menuNouveau">
				<a href="modifierFiche.php">Ajouter nouveau</a>
			</div><?php } 
			// Menu de recherche. Utilise la variable $rechercheChamp pour déterminer quelle option est sétectionnée.
			?><div class="menuRecherche">
				Recherche :
				<form action="liste.php" method="get">
					<select name="rechercheChamp" class="menuRechInput" onchange="document.getElementById('recherche').value=''">
						<option value="all" <?php if ($rechercheChamp == "all") echo "selected";?>>
							N'importe quel champ</option>
						<option value="dossiers" <?php if ($rechercheChamp == "dossiers") echo "selected";?>>
							Action</option>
						<option value="numero" <?php if ($rechercheChamp == "numero") echo "selected";?>>
							Numéro d'identification</option>
						<option value="migrant" <?php if ($rechercheChamp == "migrant") echo "selected";?>>
							Migrant</option>
						<option value="sexe" <?php if ($rechercheChamp == "sexe") echo "selected";?>>
							Sexe</option>
						<option value="naissance" <?php if ($rechercheChamp == "naissance") echo "selected";?>>
							Date de naissance</option>
						<option value="bapteme" <?php if ($rechercheChamp == "bapteme") echo "selected";?>>
							Date de baptême</option>
						<option value="commune" <?php if ($rechercheChamp == "commune") echo "selected";?>>
							Lieu d'origine</option>
						<option value="pays" <?php if ($rechercheChamp == "pays") echo "selected";?>>
							Département ou pays</option>
						<option value="datemariage" <?php if ($rechercheChamp == "datemariage") echo "selected";?>>
							Date de mariage des parents</option>
						<option value="lieumariage" <?php if ($rechercheChamp == "lieumariage") echo "selected";?>>
							Lieu de mariage des parents</option>
						<option value="datecontrat" <?php if ($rechercheChamp == "datecontrat") echo "selected";?>>
							Date du contrat</option>
						<option value="lieucontrat" <?php if ($rechercheChamp == "lieucontrat") echo "selected";?>>
							Lieu du contrat</option>
						<option value="metier" <?php if ($rechercheChamp == "metier") echo "selected";?>>
							Profession du père</option>
						<option value="mention" <?php if ($rechercheChamp == "mention") echo "selected";?>>
							Première mention au pays</option>
						<option value="occupation" <?php if ($rechercheChamp == "occupation") echo "selected";?>>
							Occupation à l'arrivée</option>
						<option value="status" <?php if ($rechercheChamp == "status") echo "selected";?>>
							Statut matrimonial</option>
						<option value="mdatemariage" <?php if ($rechercheChamp == "mdatemariage") echo "selected";?>>
							Date de mariage</option>
						<option value="mlieumariage" <?php if ($rechercheChamp == "mlieumariage") echo "selected";?>>
							Lieu de mariage</option>
						<option value="conjoint" <?php if ($rechercheChamp == "conjoint") echo "conjoint";?>>
							Premier conjoint</option>
						<option value="lieudeces" <?php if ($rechercheChamp == "lieudeces") echo "selected";?>>
							Lieu de décès ou inhumation</option>
						<option value="datedeces" <?php if ($rechercheChamp == "datedeces") echo "selected";?>>
							Date de décès ou inhumation</option>
						<option value="annotation" <?php if ($rechercheChamp == "annotation") echo "selected";?>>
							Remarques</option>
						<option value="chercheur" <?php if ($rechercheChamp == "chercheur") echo "selected";?>>
							Chercheur(s)</option>
						<option value="copie" <?php if ($rechercheChamp == "copie") echo "selected";?>>
							Copie d'acte</option>
						<option value="actenumerises" <?php if ($rechercheChamp == "actenumerises") echo "selected";?>>
							Acte numérisé</option>
						<option value="archive" <?php if ($rechercheChamp == "archive") echo "selected";?>>
							Dossier d'archive</option>
						<option value="publication" <?php if ($rechercheChamp == "publication") echo "selected";?>>
							Publication</option>
						<option value="nom" <?php if ($rechercheChamp == "nom") echo "selected";?>>
							Premier nom</option>
						<option value="surnom" <?php if ($rechercheChamp == "surnom") echo "selected";?>>
							Premier surnom</option>
						<option value="surnom1" <?php if ($rechercheChamp == "surnom1") echo "selected";?>>
							Deuxième surnom</option>
						<option value="localite" <?php if ($rechercheChamp == "localite") echo "selected";?>>
							Localité actuelle</option>
						<option value="paroisse" <?php if ($rechercheChamp == "paroisse") echo "selected";?>>
							Paroisse religieuse</option>
					</select>
					<input type="text" id="recherche" name="recherche" class="menuRechInput" value="<?php echo htmlspecialchars($recherche); ?>">
					<input type="submit" name="bouton_recherche" value="Recherche" class="menuRechInput">
				</form>
				<a href="rechercheAvancee.php">Recherche avancée</a>
			</div>
			<div class="menuNbPage">
				Nb. par page:<select name="nbPerPage" onchange="location = 'liste.php?recherche=<?php echo $recherche;?>&rechercheChamp=<?php echo $rechercheChamp;?>&nbPerPage='+this.value">
				<option value="10" <?php if ($nbPerPage == 10) echo "selected";?>>10</option>
				<option value="20" <?php if ($nbPerPage == 20) echo "selected";?>>20</option>
				<option value="30" <?php if ($nbPerPage == 30) echo "selected";?>>30</option>
				<option value="50" <?php if ($nbPerPage == 50) echo "selected";?>>50</option>
				<option value="100" <?php if ($nbPerPage == 100) echo "selected";?>>100</option>
				</select>
			</div>
		</div>
		<?php // Fin du menu, début du tableau de fiches.
		?>
		<div class="indexTab">
			<div class="tabTitles">
				<?php 
				// Si on a accès à la modification, on affiche la colonne "Éditer"
				if (CheckSecurity("Edit")) { ?><div class="migrantEdit"><img src="Images/icon_edit.gif" alt="Éditer"></div><?php } ?>
				<div class="migrantID">ID</div>
				<div class="migrantNumero">Numéro d'identification</div>
				<div class="migrantNom">Migrant</div>
			</div>
			<?php
			while($row = mysqli_fetch_row($r)){
				$migrant = htmlspecialchars($row[0]);
				$numero = htmlspecialchars($row[1]);
				$id = htmlspecialchars($row[2]);
				?>
			<div class="tabMigrant<?php if ($darker) echo " migrantDarker"; ?>">
				<?php 
				// Si on a accès à la modification, on affiche la colonne "Éditer". Sinon, on offre un lien sur le nom du migrant pour voir la fiche.
				// On a pas laissé le lien sur le nom du migrant quand la fiche peut être modifiée pour raisons d'esthétisme
				if (CheckSecurity("Edit")) { ?><div class="migrantEdit"><a href="modifierFiche.php?id=<?php echo $id; ?>">Éditer</a></div><?php } ?>
				<div class="migrantID"><?php echo $id; ?></div>
				<div class="migrantNumero"><?php echo $numero; ?></div>
				<div class="migrantNom"><?php if (!CheckSecurity("Edit"))echo "<a href='voirFiche.php?id=".$id."'>$migrant</a>"; else echo $migrant; ?></div>
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
			<form action="liste.php" method="get">
				<input type="hidden" name="page" value="1">
				<input type="hidden" name="recherche" value="<?php echo htmlspecialchars($recherche); ?>">
				<input type="hidden" name="rechercheChamp" value="<?php echo $rechercheChamp; ?>">
				<input type="hidden" name="nbPerPage" value="<?php echo $nbPerPage; ?>">
				<input type="submit" value="1" class="pager">
			</form>
			<div class="pagerPadding">...</div>
			<?php
		}
		// Affiche une dizaine de pages autour de la page actuelle.
		for($j=$i;$j<=$lastCount;$j++){
			?>
			<form action="liste.php" method="get">
				<input type="hidden" name="page" value="<?php echo $j; ?>">
				<input type="hidden" name="recherche" value="<?php echo htmlspecialchars($recherche); ?>">
				<input type="hidden" name="rechercheChamp" value="<?php echo $rechercheChamp; ?>">
				<input type="hidden" name="nbPerPage" value="<?php echo $nbPerPage; ?>">
				<input type="submit" <?php if($j==$page) echo 'id="currentPage"'?> class="pager" value="<?php echo $j; ?>">
			</form>
			<?php
		}
		// Si on est loin de la dernière page, elle s'affiche en dernier, à part.
		if ($lastCount < $lastPage){
			?>
			<div class="pagerPadding">...</div>
			<form action="liste.php" method="get">
				<input type="hidden" name="page" value="<?php echo $lastPage; ?>">
				<input type="hidden" name="recherche" value="<?php echo htmlspecialchars($recherche); ?>">
				<input type="hidden" name="rechercheChamp" value="<?php echo $rechercheChamp; ?>">
				<input type="hidden" name="nbPerPage" value="<?php echo $nbPerPage; ?>">
				<input type="submit" value="<?php echo $lastPage; ?>" class="pager">
			</form>
			<?php
		}
		?>
	</body>
</html>
