<?php

include ('database.php');

$link = connexionBDEx();


$recherche =  mysqli_real_escape_string($link, $_GET['recherche']);
$PagId = mysqli_real_escape_string($link, $_GET['pagId']);
$rechercheChamp=mysqli_real_escape_string($link, $_GET['rechercheChamp']);