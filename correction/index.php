<?php
	// Page d'accueil et d'authentification de l'interface de gestion.
	// Le design de cette page est minimaliste afin de pouvoir l'appeler au coeur de la page fichierorigine/correction
	if (session_status() == PHP_SESSION_NONE){
		session_start();
	}

	// On vide certaines variables de session
	$_SESSION["GroupID"] = "";
	$_SESSION["UserID"] = "";
	$_SESSION["AccessLevel"] = "";
	$_SESSION["OwnerID"] = "";

	header("Content-type: text/html; charset=utf-8");

	// Si on vient de se déconnecter, on réinitialise toutes les variables de session.
	if(isset($_GET["a"]))
	{
		if($_GET["a"]=="logout")
		{
			session_unset();
			session_destroy();
			session_start();
			$_SESSION['connection'] = false;
		}
	}
	
	// On confirme la connexion
	if (isset($_SESSION['connection'])){
		if ($_SESSION['connection'] == true)
		{
			$_SESSION['connection'] = true;
		}
		else
		{
			if(isset($_SESSION['message'])){
				$connection = $_SESSION['message'];
			}
			$_SESSION['connection'] = false;
		}
	}

	
// Début du HTML
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Fichier Origine - Gestion</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<meta name="description" content="Page d'authentification - Gestion Fichier Origine">
		<meta name="author" content="Marc-Albert Fournier">
	</head>
	
	<body class="login">
	<h1>Authentification - Gestion</h1>
	<div>

	<?php
	// Formulaire de connexion.
	?>
		<form action="login.php" method="post" id="login-form" target="_parent">
			<table>
				<tr>
					<td>
						<label for="modlgn-username">Identifiant</label>
					</td>
					<td>
						<input id="modlgn-username" type="text" name="username" size="18">
					<td>
				</tr>
				<tr>
					<td>
						<label for="modlgn-passwd">Mot de passe</label>
					</td>
					<td>
						<input id="modlgn-passwd" type="password" name="password" size="18">
					<td>
				</tr>
			</table>
			<br>
			<input type="submit" name="connection" value="Connexion">	
			<br>
			<div style="clear:both; height:0px"></div>
			<a href="https://www.fichierorigine.com/reinitialisation-du-mot-de-passe" target="_top" >Mot de passe oublié ?</a>
			<br>
			<a href="https://www.fichierorigine.com/rappel-de-l-identifiant" target="_top">Identifiant oublié ?</a>
			<br>
			<br>
		</form>
	</div>
	<?php
		if(isset($_SESSION['message'])){
			if($_SESSION['message'] != 'OK.')
			{echo $_SESSION['message'];}
		}
	?>
	</body>
</html>