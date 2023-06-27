<?php
	if (session_status() == PHP_SESSION_NONE){
		session_start();
	}	
	include('fonctions.php');
	include('database.php');
	// Vérifications de connexion et de permissions.
	if(!$_SESSION["UserID"])
	{ 
		$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
		header("Location: index.php?message=expired"); 
		return;
	}
	if(!CheckSecurity("Export"))
	{
		echo "<p>Votre compte n'a pas l'autorisation d'accès à cette page. <a href=\"index.php\">Retour à la page d’ouverture de session</a></p>";
		return;
	}
	
	//Fonctions de remplacement pour les fct enlever en php 7
	function mysqli_field_name($result, $field_offset)
	{
		$properties = mysqli_fetch_field_direct($result, $field_offset);
		return is_object($properties) ? $properties->name : null;
	}
	
	//Établissement de la connection a la bd
	
	error_reporting(E_ERROR | E_PARSE); 		//les warning sont turnés à off
	
	$link = connexionBDEx();

	
	$recherche =  mysqli_real_escape_string($link, $_GET['recherche']);
	$PagId = mysqli_real_escape_string($link, $_GET['pagId']);
	$rechercheChamp=mysqli_real_escape_string($link, $_GET['rechercheChamp']);

 	
 switch ($PagId) {
	case "1":
        //Acro
	//$sql = "select id, acronyme, definition from acronyme where acronyme like '%".mysqli_real_escape_string($link, $recherche)."%' or definition like '%".mysqli_real_escape_string($link, $recherche)."%' or id = '".mysqli_real_escape_string($link, $recherche)."' limit $limit offset $offset";
	$sql = "select * from acronyme where acronyme like '%".mysqli_real_escape_string($link, $recherche)."%' or definition like '%".mysqli_real_escape_string($link, $recherche)."%' or id = '".mysqli_real_escape_string($link, $recherche)."%'";
        	break;
	case "2":
        //Biblio
	$sql = "select * from bibliographie where id like '%".mysqli_real_escape_string($link, $recherche)."%' or auteur like '%".mysqli_real_escape_string($link, $recherche)."%' or titre like '%".mysqli_real_escape_string($link, $recherche)."%'";
        	break;
	default:
	//migrant
	if($rechercheChamp == "all")
	{
	$sql = "SELECT id, migrant, sexe, naissance, bapteme, commune, pays, code1, pere, mere, datemariage, lieumariage, code2, datecontrat, notaire, metier, mention, occupation, status, mdatemariage, mlieumariage, conjoint, annotation, identification, chercheur, reference, copie, numero, dossiers, nom, surnom, surnom1, localite, paroisse, datemodif, mariagerech, archive, dateajout, publication, datedeces, lieudeces, lieucontrat, actenumerises FROM origine WHERE id like '%".mysqli_real_escape_string($link, $recherche).	"%' OR migrant like '%".mysqli_real_escape_string($link, $recherche)."%' OR sexe like '%".mysqli_real_escape_string($link, $recherche)."%' OR naissance like '%".mysqli_real_escape_string($link, $recherche)."%' OR bapteme like '%".mysqli_real_escape_string($link, $recherche)."%' OR commune like '%".mysqli_real_escape_string($link, $recherche)."%' OR pays like '%".mysqli_real_escape_string($link, $recherche)."%' OR code1 like '%".mysqli_real_escape_string($link, $recherche)."%' OR pere like '%".mysqli_real_escape_string($link, $recherche)."%' OR mere like '%".mysqli_real_escape_string($link, $recherche)."%' OR datemariage like '%".mysqli_real_escape_string($link, $recherche)."%' OR lieumariage like '%".mysqli_real_escape_string($link, $recherche)."%' OR code2 like '%".mysqli_real_escape_string($link, $recherche)."%' OR datecontrat like '%".mysqli_real_escape_string($link, $recherche)."%' OR notaire like '%".mysqli_real_escape_string($link, $recherche)."%' OR metier like '%".mysqli_real_escape_string($link, $recherche)."%' OR mention like '%".mysqli_real_escape_string($link, $recherche)."%' OR occupation like '%".mysqli_real_escape_string($link, $recherche)."%' OR status like '%".mysqli_real_escape_string($link, $recherche)."%' OR mdatemariage like '%".mysqli_real_escape_string($link, $recherche)."%' OR mlieumariage like '%".mysqli_real_escape_string($link, $recherche)."%' OR conjoint like '%".mysqli_real_escape_string($link, $recherche)."%' OR identification like '%".mysqli_real_escape_string($link, $recherche)."%' OR chercheur like '%".mysqli_real_escape_string($link, $recherche)."%' OR reference like '%".mysqli_real_escape_string($link, $recherche)."%' OR copie like '%".mysqli_real_escape_string($link, $recherche)."%' OR numero like '%".mysqli_real_escape_string($link, $recherche)."%' OR dossiers like '%".mysqli_real_escape_string($link, $recherche)."%' OR nom like '%".mysqli_real_escape_string($link, $recherche)."%' OR surnom like '%".mysqli_real_escape_string($link, $recherche)."%' OR surnom1 like '%".mysqli_real_escape_string($link, $recherche)."%' OR localite like '%".mysqli_real_escape_string($link, $recherche)."%' OR paroisse like '%".mysqli_real_escape_string($link, $recherche)."%' OR datemodif like '%".mysqli_real_escape_string($link, $recherche)."%' OR mariagerech like '%".mysqli_real_escape_string($link, $recherche)."%' OR archive like '%".mysqli_real_escape_string($link, $recherche)."%' OR dateajout like '%".mysqli_real_escape_string($link, $recherche)."%' OR publication like '%".mysqli_real_escape_string($link, $recherche)."%' OR datedeces like '%".mysqli_real_escape_string($link, $recherche)."%' OR lieudeces like '%".mysqli_real_escape_string($link, $recherche)."%' OR lieucontrat like '%".mysqli_real_escape_string($link, $recherche)."%' OR actenumerises like '%".mysqli_real_escape_string($link, $recherche)."%'";
	}else {
	$sql = "SELECT id, migrant, sexe, naissance, bapteme, commune, pays, code1, pere, mere, datemariage, lieumariage, code2, datecontrat, notaire, metier, mention, occupation, status, mdatemariage, mlieumariage, conjoint, annotation, identification, chercheur, reference, copie, numero, dossiers, nom, surnom, surnom1, localite, paroisse, datemodif, mariagerech, archive, dateajout, publication, datedeces, lieudeces, lieucontrat, actenumerises FROM origine where ".mysqli_real_escape_string($link,$rechercheChamp)." like '%".mysqli_real_escape_string($link, $recherche)."%'";
	}
 }
$rec = mysqli_query($link,$sql) or die (mysqli_error());

    $num_fields = mysqli_num_fields($rec);

    for($i = 0; $i < $num_fields; $i++ )
    {
        $header .= mysqli_field_name($rec,$i)."\t";
    }
    while($row = mysqli_fetch_row($rec))
    {
        $line = '';
        foreach($row as $value)
        {                                            
            if((!isset($value)) || ($value == ""))
            {
                $value = "\t";
            }
            else
            {
                $value = str_replace( '"' , '""' , $value );
                $value = '"' . $value . '"' . "\t";
            }
            $line .= $value;
        }
        $data .= trim( $line ) . "\n";
    }
    
    $data = str_replace("\r" , "" , $data);
    
    if ($data == "")
    {
        $data = "\n No Record Found!\n";                        
    }

	header('Content-Encoding: UTF-8');
   	header("Cache-Control: must-revalidate");
	header("Pragma: must-revalidate");
	header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: attachment; filename=Excel.csv");
	echo "\xEF\xBB\xBF"; // UTF-8 BOM

    print "sep= \n$header\n$data";
?>
