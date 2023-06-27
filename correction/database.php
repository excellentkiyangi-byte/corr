<?php

	// Fonction de connexion à la base de données
	// Retourne un lien vers la Base de donnée
	function connexionBD(){
		$configs = include('config.php');
		$conn = new mysqli($configs['host'],$configs['username'],$configs['password'],$configs['db']);
		mysqli_set_charset($conn, 'utf8');
		mysqli_query($conn, 'SET NAMES "utf8"');
		return $conn;
	}
	
	// Fichier contenant les fonctions de communication avec la base de données. 
	
	// Fonction de connexion à la base de données pour excel
	// Retourne un lien vers la BD
	function connexionBDEx(){

		$configs = include('config.php');
		print($configs['host']);
		$link = mysqli_connect($configs['host'],$configs['username'],$configs['password'],$configs['db']);
		return $link;
	}
	// Fonction de déconnexion de la base de données
	function deconnexionBD($conn){
		mysqli_close($conn);
	}

?>
