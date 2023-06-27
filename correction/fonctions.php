<?php

#include ('uri.php');
	// Fichier contenant diverses fonctions utiles.

// Fonction renvoyant un lien vers le fichier donné en paramètre
// Le lien est ouvert dans une autre fenêtre.


function getUrl($file){
	if ($file != ''){
		return "<a href=https://www.fichierorigine.com/dossiers/$file onclick=\"window.open(this.href, 'exemple', 'height=400, width=750, top=0, left=0, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=yes, status=no'); return false;\">numérisé</a>";
	}else{
		return "";
	}
}


// Fonction retournant le chemin vers le dossier d'images servant aux archives et actes numérisés.
function imageFolder(){
	return dirname(__DIR__)."/dossiers/";
}

// Fonction retournant une string de la forme "(where|and|or) champ operateur valeur"
// Sert pour la recherche avancée lorsque appelée par POST.
function addToWhere($field, $andOr, &$compteur, $link){
	$where = "";
	if ($_POST[$field] != ""){
		if ($compteur == 1){
			$where .= " $andOr ";
		}
		else
			$where .= " where ";
		
		$value = htmlspecialchars(mysqli_real_escape_string($link, $_POST[$field]));
		$not = (isset($_POST['non_'.$field])) ? " not": "";
		$operator = $_POST['menu_'.$field];

		if ($operator == "Contient") $value = "$not like '%$value%'";
		else if ($operator == "DebuterAvec") $value = "$not like '$value%'";
		else if ($operator == "Egal"){
			if ($not == " not")
				$value = " != '$value'";
			else
				$value = " = '$value'";
		}
		else if ($operator == "PlusQue") {
			if ($not == " not")
				$value = " <= '$value'";
			else
				$value = " > '$value'";
		}
		else if ($operator== "MoinsQue") {
			if ($not == " not")
				$value = " >= '$value'";
			else
				$value = " < '$value'";
		}
		else if ($operator == "EgalOuPlus") {
			if ($not == " not")
				$value = " < '$value'";
			else
				$value = " >= '$value'";
		}
		else if ($operator == "EgalOuMoins") {
			if ($not == " not")
				$value = " > '$value'";
			else
				$value = " <= '$value'";
		}
		else if ($operator == "Entre") $value = "$not like '%$value%'";
		else if ($operator == "Vide"){
			if ($not == " not")
				$value = " = 'x'";
			else
				$value = " != 'x'";
		}
		else
			$value = "$not like '%$value%'";

		$where .= ($field.$value);
		$compteur = 1;
	}
	$_SESSION[$field] = $_POST[$field];
	$_SESSION['menu_'.$field] = $_POST['menu_'.$field];
	$_SESSION['non_'.$field] = (isset($_POST['non_'.$field]))?$_POST['non_'.$field]:"";
	
	return $where;	
}

// Fonction retournant une string de la forme "(where|and|or) champ operateur valeur"
// Sert pour la recherche avancée lorsque appelée par SESSION.
function addToWhereSess($field, $andOr, &$compteur, $link){
	$where = "";
	if ($_SESSION[$field] != ""){
		if ($compteur == 1){
			$where .= " $andOr ";
		}
		else
			$where .= " where ";
		
		$value = htmlspecialchars(mysqli_real_escape_string($link, $_SESSION[$field]));
		$not = ($_SESSION['non_'.$field] != '') ? " not": "";
		$operator = $_SESSION['menu_'.$field];

		if ($operator == "Contient") $value = "$not like '%$value%'";
		else if ($operator == "DebuterAvec") $value = "$not like '$value%'";
		else if ($operator == "Egal"){
			if ($not == " not")
				$value = " != '$value'";
			else
				$value = " = '$value'";
		}
		else if ($operator == "PlusQue") {
			if ($not == " not")
				$value = " <= '$value'";
			else
				$value = " > '$value'";
		}
		else if ($operator== "MoinsQue") {
			if ($not == " not")
				$value = " >= '$value'";
			else
				$value = " < '$value'";
		}
		else if ($operator == "EgalOuPlus") {
			if ($not == " not")
				$value = " < '$value'";
			else
				$value = " >= '$value'";
		}
		else if ($operator == "EgalOuMoins") {
			if ($not == " not")
				$value = " > '$value'";
			else
				$value = " <= '$value'";
		}
		else if ($operator == "Entre") $value = "$not like '%$value%'";
		else if ($operator == "Vide"){
			if ($not == " not")
				$value = " = ''";
			else
				$value = " != ''";
		}
		else
			$value = "$not like '%$value%'";

		$where .= ($field.$value);
		$compteur = 1;
	}
	
	return $where;	
}

// Fonction retournant une string de la forme "(where|and|or) champ operateur valeur"
// Sert pour la recherche normale utilisant tous les champs.
function addToWhereAll($field, $value, &$compteur, $link){
	$where = "";
	if ($compteur == 1){
		$where .= " or ";
	}
	$value = mysqli_real_escape_string($link, $value);
	
	$value = " like '%$value%'";

	$where .= ($field.$value);
	$compteur = 1;
	
	return $where;
}


// Fonction qui vérifie si la permission donnée en paramètre est accordée à l'utilisateur courant
function CheckSecurity($strAction)
{
	if($_SESSION['AccessLevel']=='ACCESS_LEVEL_ADMIN')
		return true;

	// On va chercher toutes les permissions que l'utilisateur possède.
	$strPerm = GetUserPermissions();
	
	// On vérifie si la permission est accordée
	if($strAction=="Add" && !(strpos($strPerm, "A")===false) ||
	   $strAction=="Edit" && !(strpos($strPerm, "E")===false) ||
	   $strAction=="Delete" && !(strpos($strPerm, "D")===false) ||
	   $strAction=="Search" && !(strpos($strPerm, "S")===false) ||
	   $strAction=="Export" && !(strpos($strPerm, "P")===false) ||
	   $strAction=="Publish" && !(strpos($strPerm, "B")===false) )
		return true;
	else
		return false;
}

// Fonction qui retourne les permissions de l'utilisateur sur la table passée en paramètre.
// Cette fonction gère seulement la table "origine" pour le moment.
// Les permissions possibles sont
// 		A - Add - Ajouter des nouvelles fiches.
// 		D - Delete - Supprimer des fiches existantes.
// 		E - Edit - Éditer des fiches existantes.
// 		S - Search - Voir et faire des recherches sur les fiches.
// 		P - Export - Exporter les données des fiches.
// 		B - Publish - Publier les fiches, les rendant visibles aux utilisateurs du site.
// Retourne une string contenant chaque permission permise
function GetUserPermissions($table="origine")
{
	// Si le niveau d'accès est celui d'admin, on donne toutes les permissions.
	if($_SESSION['AccessLevel'] == 'ACCESS_LEVEL_ADMIN')
			return "ADESPB";

	$sUserGroup=$_SESSION["GroupID"];
	if($table=="origine" && $sUserGroup=="admin")
		return "AEDSPB"; // Toutes les permissions
	if($table=="origine" && $sUserGroup=="redacteur")
		return "AESP"; // Ajout, édition, recherche et exportation
	if($table=="origine" && $sUserGroup=="voir")
		return "SP"; // Recherche et exportation
	
	// Permissions par défaut
	if($table=="origine")
		return "";	// Aucune permission.
}
