<?php
	// Page de login.
	// Vérifie les informations de connexion et interroge la BD.
	if (session_status() == PHP_SESSION_NONE){
		session_start();
	}
	include ('dbcommon.php');
	include ('database.php');

	// On initialise d'abord les variables de session.
	$_SESSION['admin']= false;
	$_SESSION['connection']= false;
	$_SESSION['message']= "";
	$_SESSION["GroupID"] = "";
	$_SESSION["UserID"] = "";
	$_SESSION["AccessLevel"] = "";
	$_SESSION["OwnerID"]= "";

	$user_name ="";
	$pass_word ="";
	$verite = true;

	// Si on a envoyé le formulaire de connexion
	if($_POST['connection']) {
		// On interroge la BD pour obtenir le nom de passe.
		#$username ="fichierori"; # $_POST["username"];
		#$password = "$2y$10\$qnX15WeqmfxqtoDyq0dAS.F2Q.cK2w14x4yTNUAwulrZ7B00h3DY6";#$_POST["password"];
		#$credentials['username'] = $_POST["username"];
		#$credentials['password'] = $_POST["password"];
		$username = $_POST["username"];
		$password = $_POST["password"];


		$conn = connexionBD();
		$sql = "USE fqsg_db";
		$conn->query($sql);

		$sql = "select id, password, username from fich_users where username = '$username'";
		$result = mysqli_query($conn, $sql);
		if ($result) {
			while ($result1 = mysqli_fetch_assoc($result)){
				$user_id = $result1["id"];
				$user_name = $result1["username"];
				$pass_word = $result1["password"];
			}
			$valid = password_verify('Fqsg1986', $pass_word);
			if($valid){
				$sql2 = "select id, group_id from fich_users join fich_user_usergroup_map on id = user_id where id = $user_id";

				$result2 = mysqli_query($conn, $sql2);
				mysqli_close($conn);
				while($result3 = mysqli_fetch_assoc($result2)){
					$group_id = $result3['group_id'];
				}
				if (($group_id == 8) || ($group_id == 11)) {
					$_SESSION["GroupID"] = 'admin';
				} elseif ($group_id == 10) {
					$_SESSION["GroupID"] = 'redacteur';
				} elseif ($group_id == 9) {
					$_SESSION["GroupID"] = 'voir';
				}
				$_SESSION["UserID"] = $user_id;
				$_SESSION["AccessLevel"] = ACCESS_LEVEL_USER;;
				$_SESSION["OwnerID"] = $user_name;
				$_SESSION['connection'] = true;
				$_SESSION['message'] = "OK.";
			}
			else{
				$_SESSION['connection'] = false;
				$_SESSION['message'] = "Compte inexistant ou mot de passe erroné.";
			}
		}
		else {
		$_SESSION['connection'] = false;
		$_SESSION['message'] = "Compte inexistant ou mot de passe erroné.";
		}
	}

	// Avant de continuer, on déréférence les variables de session associées à la recherche
	//	pour éviter des cas ou on se reconnecte sans qu'elles aient été remises à zéro
	unset($_SESSION['publie']);
	unset($_SESSION['andOr']);
	unset($_SESSION['recherche']);
	unset($_SESSION['rechercheChamp']);
	// On est redirigé vers liste.php.
	// Si les informations de connexion étaient invalides, liste.php redirige vers index.php
	header("Location: liste.php", true, 301);
?>
