<?php
    // Interface d'ajout ou de modification d'une fiche.
    // Cette page gère l'ajout de nouvelles fiches dans la base de données,
    //	ainsi que l'édition et la suppression de fiches existantes.

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
    // Vérification préliminaire des permissions. Si on ne peut ni ajouter ni éditer, on est redirigé à l'accueil.
    if(!CheckSecurity("Edit") && !CheckSecurity("Add"))
    {
        echo "<p>Votre compte n'a pas l'autorisation d'accès à cette page. <a href=\"index.php\">Retour à la page d’ouverture de session</a></p>";
        return;
    }

    //endroit où mettre la fonction du bouton valider ID
    if (isset($_POST['bouton_valider'])){

        //function valider(){

        $link = connexionBD();

        $numero = ($_POST['numero']=='')? "x" : $_POST['numero'];

        $q = "SELECT numero FROM fiches_archivees where numero = $numero";
        $r = mysqli_query($link, $q);

        if ($r->num_rows == 1) {echo "<p> <font color=red font face='arial' size='2pt'>Cet ID est déjà utilisé</font> </p>";}
        else {echo "<p> <font color=green font face='arial' size='2pt'>ID valide</font> </p>";}

        //$numero = ($_POST['numero']=='')? "x" : $_POST['numero'];
        //$numero = mysqli_real_escape_string($link, $numero);
    }


    // Si on a soumis un ajout ou une modification
    if (isset($_POST['bouton_submit'])){
        // On détermine s'il s'agit d'un ajout ou d'une modification
        $update = $_POST['update'];
        $update = ($update == "true")? true: false;

        // On stocke les informations de la fiche dans des variables, échappant les caractères sensibles.
        $link = connexionBD();
        $id = mysqli_real_escape_string($link, $_POST['id']);

        $migrant = mysqli_real_escape_string($link, $_POST['migrant']);

        $sexe = mb_strtoupper(mb_substr($_POST['sexe'],0,1));
        if ($sexe == 'H') $sexe = 'M';

        $lieudeces = ($_POST['lieudeces']=='')? "x" : $_POST['lieudeces'];
        $lieudeces = mysqli_real_escape_string($link, $lieudeces);

        $datedeces = ($_POST['datedeces']=='')? "x" : $_POST['datedeces'];
        $datedeces = mysqli_real_escape_string($link, $datedeces);

        $naissance = ($_POST['naissance']=='')? "x" : $_POST['naissance'];
        $naissance = mysqli_real_escape_string($link, $naissance);

        $bapteme = ($_POST['bapteme']=='')? "x" : $_POST['bapteme'];
        $bapteme = mysqli_real_escape_string($link, $bapteme);

        $commune = ($_POST['commune']=='')? "x" : $_POST['commune'];
        $commune = mysqli_real_escape_string($link, $commune);

        $annotation = ($_POST['annotation']=='')? "x" : $_POST['annotation'];
        $annotation = mysqli_real_escape_string($link, $annotation);

        $pays = ($_POST['pays']=='')? "x" : $_POST['pays'];
        $pays = mysqli_real_escape_string($link, $pays);

        $code1 = ($_POST['code1']=='')? "x" : $_POST['code1'];
        $code1 = mysqli_real_escape_string($link, $code1);

        $pere = ($_POST['pere']=='')? "x" : $_POST['pere'];
        $pere = mysqli_real_escape_string($link, $pere);

        $mere = ($_POST['mere']=='')? "x" : $_POST['mere'];
        $mere = mysqli_real_escape_string($link, $mere);

        $datemariage = ($_POST['datemariage']=='')? "x" : $_POST['datemariage'];
        $datemariage = mysqli_real_escape_string($link, $datemariage);

        $lieumariage = ($_POST['lieumariage']=='')? "x" : $_POST['lieumariage'];
        $lieumariage = mysqli_real_escape_string($link, $lieumariage);

        $code2 = ($_POST['code2']=='')? "x" : $_POST['code2'];
        $code2 = mysqli_real_escape_string($link, $code2);

        $datecontrat = ($_POST['datecontrat']=='')? "x" : $_POST['datecontrat'];
        $datecontrat = mysqli_real_escape_string($link, $datecontrat);

        $notaire = ($_POST['notaire']=='')? "x" : $_POST['notaire'];
        $notaire = mysqli_real_escape_string($link, $notaire);

        $lieucontrat = ($_POST['lieucontrat']=='')? "x" : $_POST['lieucontrat'];
        $lieucontrat = mysqli_real_escape_string($link, $lieucontrat);

        $metier = ($_POST['metier']=='')? "x" : $_POST['metier'];
        $metier = mysqli_real_escape_string($link, $metier);

        $mention = ($_POST['mention']=='')? "x" : $_POST['mention'];
        $mention = mysqli_real_escape_string($link, $mention);

        $occupation = ($_POST['occupation']=='')? "x" : $_POST['occupation'];
        $occupation = mysqli_real_escape_string($link, $occupation);

        $status = mb_strtoupper(mb_substr($_POST['status'],0,1));
        $status = ($status != 'M' && $status != 'C' && $status != 'R')?'x':$status;

        $mdatemariage = ($_POST['mdatemariage']=='')? "x" : $_POST['mdatemariage'];
        $mdatemariage = mysqli_real_escape_string($link, $mdatemariage);

        $mlieumariage = ($_POST['mlieumariage']=='')? "x" : $_POST['mlieumariage'];
        $mlieumariage = mysqli_real_escape_string($link, $mlieumariage);

        $conjoint = ($_POST['conjoint']=='')? "x" : $_POST['conjoint'];
        $conjoint = mysqli_real_escape_string($link, $conjoint);

        $identification = ($_POST['identification']=='')? "x" : $_POST['identification'];
        $identification = mysqli_real_escape_string($link, $identification);

        $chercheur = ($_POST['chercheur']=='')? "x" : $_POST['chercheur'];
        $chercheur = mysqli_real_escape_string($link, $chercheur);

        $reference = ($_POST['reference']=='')? "x" : $_POST['reference'];
        $reference = mysqli_real_escape_string($link, $reference);

        $copie = ($_POST['copie']=='')? "x" : $_POST['copie'];
        $copie = mysqli_real_escape_string($link, $copie);

        $numero = ($_POST['numero']=='')? "x" : $_POST['numero'];
        $numero = mysqli_real_escape_string($link, $numero);

        $userid = $_SESSION["UserID"];
        $row = mysqli_fetch_array(mysqli_query($link, "select name from fich_users where username='$userid'"));
        $dossiers = $row['name'];

        $nom = ($_POST['nom']=='')? "x" : $_POST['nom'];
        $nom = mysqli_real_escape_string($link, $nom);

        $surnom = ($_POST['surnom']=='')? "x" : $_POST['surnom'];
        $surnom = mysqli_real_escape_string($link, $surnom);

        $surnom1 = ($_POST['surnom1']=='')? "x" : $_POST['surnom1'];
        $surnom1 = mysqli_real_escape_string($link, $surnom1);

        $localite = ($_POST['localite']=='')? "x" : $_POST['localite'];
        $localite = mysqli_real_escape_string($link, $localite);

        $paroisse = ($_POST['paroisse']=='')? "x" : $_POST['paroisse'];
        $paroisse = mysqli_real_escape_string($link, $paroisse);

        $NOTES = ($_POST['NOTES']=='')? "x" : $_POST['NOTES'];
        $NOTES = mysqli_real_escape_string($link, $NOTES);

        $datemodif = date('Y-m-d H:i:s');

        if (file_exists($_FILES['fileDossier']['tmp_name'])){
            $archive = trim(basename($_FILES['fileDossier']['name']));
            $newFileD = true;
        }
        else{
            $archive = ($_POST['archive'] == '')? "x": $_POST['archive'];
            $archive = mysqli_real_escape_string($link, $archive);
            $newFileD = false;
        }

        if(file_exists($_FILES['fileActe']['tmp_name'])){
            $actenumerises = trim(basename($_FILES['fileActe']['name']));
            $newFileA = true;
        }
        else{
            $actenumerises = ($_POST['actenumerises'] == "")? "x" : $_POST['actenumerises'];
            $actenumerises = mysqli_real_escape_string($link, $actenumerises);
            $newFileA = false;
        }
        // On vérifie si on a le droit de publier la fiche. Si oui, on prend le paramètre donné.
        // Sinon, on la fiche sera non publiée.
        // Si une personne n'étant pas autorisée à publier une fiche fait des modifications sur une fiche déjà publiée,
        // 	elle sera alors dépubliée en attendant confirmation pour les modifications par quelqu'un pouvant publier.
        if (CheckSecurity("Publish")){
            $publication = (isset($_POST['publication']))? "":"N";
        }
        else
            $publication = "N";

        $mariagerech = "";

        // Création de la requête SQL.
        if ($update)
            $q = "UPDATE fiches_archivees SET migrant='$migrant', sexe='$sexe', lieudeces='$lieudeces', datedeces='$datedeces', naissance='$naissance', bapteme='$bapteme', commune='$commune', pays='$pays', code1='$code1', pere='$pere', mere='$mere', datemariage='$datemariage', lieumariage='$lieumariage', code2='$code2', datecontrat='$datecontrat', notaire='$notaire', metier='$metier', mention='$mention', occupation='$occupation', status='$status', mdatemariage='$mdatemariage', mlieumariage='$mlieumariage', conjoint='$conjoint', annotation='$annotation', identification='$identification', chercheur='$chercheur', reference='$reference', copie='$copie', numero='$numero', dossiers='$dossiers', nom='$nom', surnom='$surnom', surnom1='$surnom1', localite='$localite', paroisse='$paroisse', NOTES='$NOTES', datemodif='$datemodif', archive='$archive', publication='$publication', lieucontrat='$lieucontrat', actenumerises='$actenumerises' where id=$id";
        else
            $q = "INSERT INTO fiches_archivees (migrant, sexe, lieudeces, datedeces, naissance, bapteme, commune, pays, code1, pere, mere, datemariage, lieumariage, code2, datecontrat, notaire, metier, mention, occupation, status, mdatemariage, mlieumariage, conjoint, annotation, identification, chercheur, reference, copie, numero, dossiers, nom, surnom, surnom1, localite, paroisse, NOTES, datemodif, dateajout, archive, publication, mariagerech, lieucontrat, actenumerises) VALUES ('$migrant', '$sexe', '$lieudeces','$datedeces','$naissance','$bapteme','$commune','$pays','$code1','$pere','$mere','$datemariage','$lieumariage','$code2','$datecontrat','$notaire','$metier','$mention','$occupation','$status','$mdatemariage','$mlieumariage','$conjoint','$annotation','$identification','$chercheur','$reference','$copie','$numero','$dossiers','$nom','$surnom','$surnom1','$localite','$paroisse','$NOTES','$datemodif','$datemodif','$archive','$publication', '$mariagerech', '$lieucontrat', '$actenumerises')";

        // On vérifie les permissions de l'utilisateur.
        // Dans tous les cas, on obtient un message à afficher

        // Si on veut éditer une fiche
        if($update){
            // Si on a la permission d'éditer, on lance la requête.
            if (CheckSecurity("Edit")){
                if (mysqli_query($link, $q)){
                    $message = "Mise à jour de la fiche réussie.";
                    $good = true;
                    // Si l'update est réussi, on ajoute les fichiers fournis, s'il y a lieu
                    if ($newFileD){
                        if (preg_match("#^(image/|application/pdf$)#",mime_content_type($_FILES['fileDossier']['tmp_name']))){
                            $uploadFile = imageFolder().$archive;
                            if (move_uploaded_file($_FILES['fileDossier']['tmp_name'], $uploadFile)){
                                $good = true;
                                echo "well";
                            }
                            else{
                                echo "wait";
                                $message .= " Par contre, le dossier numérisé ne s'est pas ajouté correctement.";
                                $good = false;
                                mysqli_query($link, "update fiches_archivees set archive='x' where id='$id'");
                            }
                        }
                        else{
                            echo "invalide";
                            $message .= " Par contre, le dossier numérisé n'a pas un format de fichier valide et n'a pas été ajouté.";
                            $good = false;
                            mysqli_query($link, "update fiches_archivees set archive='x' where id='$id'");
                        }
                    }
                    if ($newFileA){
                        if (preg_match("#^(image/|application/pdf$)#",mime_content_type($_FILES['fileActe']['tmp_name']))){
                            $uploadFile = imageFolder().$actenumerises;
                            if (move_uploaded_file($_FILES['fileActe']['tmp_name'], $uploadFile)){
                                $good = ($good && true);
                            }
                            else{
                                $message .= " Par contre, l'acte numérisé ne s'est pas ajouté correctement.";
                                $good = false;
                                mysqli_query($link, "update origine set actenumerises='x' where id='$id'");
                            }
                        }
                        else{
                            $message .= " Par contre, l'acte numérisé n'a pas un format de fichier valide et n'a pas été ajouté.";
                            $good = false;
                            mysqli_query($link, "update fiches_archivees set actenumerises='x' where id='$id'");
                        }
                    }
                }
                else{
                    $message = "Échec de la requête à la base de données: ".preg_replace("#Duplicate entry(.*)for key \'numero\'#","Le numéro d'identification $1 est déjà utilisé.",mysqli_error($link));
                    $good = false;
                }
            }
            else {
                $message = "Impossible d'exécuter la requête de modification - Permission non accordée.";
                $good = false;
            }
        }
        // Sinon, on veut insérer une nouvelle fiche
        else {
            // Si on a la permission d'ajouter, on lance la requête
            if (CheckSecurity("Add")){
                if (mysqli_query($link, $q)){
                    $message = "Ajout de la nouvelle fiche réussi.";
                    $good = true;
                    $justAdded = true;
                    $id = mysqli_insert_id($link);
                    // Si l'ajout est réussi, on ajoute aussi les fichiers fournis, s'il y a lieu
                    if ($newFileD){
                        if (preg_match("#^(image/|application/pdf$)#",mime_content_type($_FILES['fileDossier']['tmp_name']))){
                            $uploadFile = imageFolder().$archive;
                            if (move_uploaded_file($_FILES['fileDossier']['tmp_name'], $uploadFile)){
                                $good = true;
                            }
                            else{
                                $message .= " Par contre, le dossier numérisé ne s'est pas ajouté correctement.";
                                $good = false;
                                mysqli_query($link, "update fiches_archivees set archive='x' where id='$id'");
                            }
                        }
                        else{
                            $message .= " Par contre, le dossier numérisé n'a pas un format de fichier valide et n'a pas été ajouté.";
                            $good = false;
                            mysqli_query($link, "update fiches_archivees set archive='x' where id='$id'");
                        }
                    }
                    if ($newFileA){
                        if (preg_match("#^(image/|application/pdf$)#",mime_content_type($_FILES['fileActe']['tmp_name']))){
                            $uploadFile = imageFolder().$actenumerises;
                            if (move_uploaded_file($_FILES['fileActe']['tmp_name'], $uploadFile)){
                                $good = ($good && true);
                            }
                            else{
                                $message .= " Par contre, l'acte numérisé ne s'est pas ajouté correctement.";
                                $good = false;
                                mysqli_query($link, "update fiches_archivees set actenumerises='x' where id='$id'");
                            }
                        }
                        else{
                            $message .= " Par contre, l'acte numérisé n'a pas un format de fichier valide et n'a pas été ajouté.";
                            $good = false;
                            mysqli_query($link, "update fiches_archivees set actenumerises='x' where id='$id'");
                        }
                    }
                }
                else{
                    $message = "Échec de la requête à la base de données: ".preg_replace("#Duplicate entry(.*)for key \'numero\'#","Le numéro d'identification $1 est déjà utilisé.",mysqli_error($link));
                }
            }
            else{
                $message = "Impossible d'exécuter la requête d'ajout - Permission non accordée.";
                $good = false;
            }
        }

        deconnexionBD($link);
    } // Fin traitement bouton_submit

    // Si on a soumis une suppression d'une fiche
    // (On obtient dans tous les cas un message à afficher)
    else if (isset($_POST['bouton_delete'])){
        // Si on a la permission, on effectue la suppression.
        if (CheckSecurity("Delete")) {
            $link = connexionBD();
            $id = mysqli_real_escape_string($link, $_POST['id']);
            $qdel = "SELECT archive, actenumerises from fiches_archivees where id='$id'";
            $row = mysqli_fetch_row(mysqli_query($link, $qdel));
            $fileD = imageFolder() . $row[0];
            $fileA = imageFolder() . $row[1];

            $q = "DELETE FROM fiches_archivees WHERE id='$id'";
            if (mysqli_query($link, $q)) {
                $message = "Suppression de la fiche réussie.";
                $good = true;
                if (file_exists($fileD)) {
                    if (unlink($fileD))
                        $message .= " (fichier dossier inclu)";
                }
                if (file_exists($fileA)) {
                    if (unlink($fileA))
                        $message .= " (fichier acte inclu)";
                }
            } else {
                $message = "Échec de la suppression: " . mysqli_error($link);
                $good = false;
            }
            deconnexionBD($link);
        }
        else {
            $message = "Impossible d'exécuter la requête de suppression - Permission non accordée.";
            $good = false;
        }
    } // Fin traitement bouton_delete

    // Si on a soumis un archivage  d'une fiche
    // (On obtient dans tous les cas un message à afficher)
    else if (isset($_POST['bouton_publier'])){
        // Si on a la permission, on effectue la suppression.
        if (CheckSecurity("Delete")) {
            $link = connexionBD();
            $id = mysqli_real_escape_string($link, $_POST['id']);
            $qdel = "SELECT archive, actenumerises from fiches_archivees where id='$id'";
            $row = mysqli_fetch_row(mysqli_query($link, $qdel));
            $fileD = imageFolder() . $row[0];
            $fileA = imageFolder() . $row[1];

            $row_to_delete = "SELECT * from fiches_archivees where id='$id'";
            $result = mysqli_query($link, $row_to_delete);

            while ($result1 = mysqli_fetch_assoc($result)) {
                $migrant = $result1['migrant'];
                $sexe = $result1['sexe'];
                $lieudeces = $result1['lieudeces'];
                $datedeces = $result1['datedeces'];
                $naissance = $result1['naissance'];
                $bapteme = $result1['bapteme'];
                $commune = $result1['commune'];
                $annotation = $result1['annotation'];
                $pays = $result1['pays'];
                $code1 = $result1['code1'];
                $pere = $result1['pere'];
                $mere = $result1['mere'];
                $datemariage = $result1['datemariage'];
                $lieumariage = $result1['lieumariage'];
                $code2 = $result1['code2'];
                $datecontrat = $result1['datecontrat'];
                $notaire = $result1['notaire'];
                $lieucontrat = $result1['lieucontrat'];
                $metier = $result1['metier'];
                $mention = $result1['mention'];
                $occupation = $result1['occupation'];
                $status = $result1['status'];
                $mdatemariage = $result1['mdatemariage'];
                $mlieumariage = $result1['mlieumariage'];
                $conjoint = $result1['conjoint'];
                $identification = $result1['identification'];
                $chercheur = $result1['chercheur'];
                $reference = $result1['reference'];
                $copie = $result1['copie'];
                $numero = $result1['numero'];
                $dossiers = $result1['dossiers'];;
                $nom = $result1['nom'];
                $surnom = $result1['surnom'];
                $surnom1 = $result1['surnom1'];
                $localite = $result1['localite'];;
                $paroisse = $result1['paroisse'];
                $NOTES = $result1['NOTES'];
                $datemodif = $result1['datemodif'];
                $archive = $result1['datemodif'];
                $actenumerises = $result1['actenumerises'];
                $publication = $result1['publication'];
                $mariagerech = $result1['mariagerech'];


                $message = $nom;
                $q = "DELETE FROM fiches_archivees WHERE id='$id'";

                if (mysqli_query($link, $q)) {
                    $ajout_dans_fiches_archiveees = "INSERT INTO origine(migrant, sexe, lieudeces, datedeces, naissance, bapteme, commune, pays, code1, pere, mere, datemariage, lieumariage, code2, datecontrat, notaire, metier, mention, occupation, status, mdatemariage, mlieumariage, conjoint, annotation, identification, chercheur, reference, copie, numero, dossiers, nom, surnom, surnom1, localite, paroisse, NOTES, datemodif, dateajout, archive, publication, mariagerech, lieucontrat, actenumerises) VALUES ('$migrant', '$sexe', '$lieudeces','$datedeces','$naissance','$bapteme','$commune','$pays','$code1','$pere','$mere','$datemariage','$lieumariage','$code2','$datecontrat','$notaire','$metier','$mention','$occupation','$status','$mdatemariage','$mlieumariage','$conjoint','$annotation','$identification','$chercheur','$reference','$copie','$numero','$dossiers','$nom','$surnom','$surnom1','$localite','$paroisse','$NOTES','$datemodif','$datemodif','$archive','$publication', '$mariagerech', '$lieucontrat', '$actenumerises')";
                    mysqli_query($link, $ajout_dans_fiches_archiveees);
                    $message = "archivage de la fiche réussie.";
                    $good = true;
                    if (file_exists($fileD)) {
                        if (unlink($fileD))
                            $message .= " (fichier dossier inclu)";
                    }
                    if (file_exists($fileA)) {
                        if (unlink($fileA))
                            $message .= " (fichier acte inclu)";
                    }
                } else {
                    $message = "Échec de l'archivage: " . mysqli_error($link);
                    $good = false;
                }
                deconnexionBD($link);
            }
        }
        else {
            $message = "Impossible d'exécuter la requête d' archivage - Permission non accordée.";
            $good = false;
        }
    } // Fin traitement bouton_delete

    // Si on a soumis la suppression de l'acte numérisé
    // (Dans tous les cas, on obtient un message à afficher)
    else if (isset($_POST['bouton_del_acte'])){
        // Si on a la permission, on effectue la suppression.
        if (CheckSecurity("Delete")){
            $link = connexionBD();
            $id = mysqli_real_escape_string($link, $_POST['id']);
            $qdel = "SELECT actenumerises from fiches_archivees where id='$id'";
            $row = mysqli_fetch_row(mysqli_query($link, $qdel));
            $fileA = imageFolder().$row[0];

            if (file_exists($fileA)){
                if (unlink($fileA)){
                    $message = "Fichier d'acte numérisé supprimé avec succès.";
                    $good = true;
                }
                else{
                    $message = "Erreur lors de la suppression.";
                    $good = false;
                }
            }
            else{
                $message = "Le fichier d'acte numérisé n'a pas été trouvé.";
                $good = true;
            }

            // Si le fichier à été supprimé correctement (ou était inexistant), on met le champ de la BD à jour.
            if($good){
                if (mysqli_query($link, "UPDATE fiches_archivees SET actenumerises='x' WHERE id='$id'")){
                    $message .= " (BD mise à jour)";
                }
                else{
                    $message .= " (erreur dans la mise à jour de la BD: ".mysqli_error($link).")";
                }
            }

            deconnexionBD($link);
        }
        else {
            $message = "Impossible d'exécuter la requête de suppression - Permission non accordée.";
            $good = false;
        }
    } // Fin traitement bouton_del_acte

    // Si on a soumis la suppression de l'archive numérisée
    // (Dans tous les cas, on obtient un message à afficher)
    else if (isset($_POST['bouton_del_doss'])){
        // Si on a la permission, on effectue la suppression.
        if (CheckSecurity("Delete")){
            $link = connexionBD();
            $id = mysqli_real_escape_string($link, $_POST['id']);
            $qdel = "SELECT archive from fiches_archivees where id='$id'";
            $row = mysqli_fetch_row(mysqli_query($link, $qdel));
            $fileD = imageFolder().$row[0];

            if (file_exists($fileD)){
                if (unlink($fileD)){
                    $message = "Fichier d'archive numérisée supprimé avec succès.";
                    $good = true;
                }
                else{
                    $message = "Erreur lors de la suppression.";
                    $good = false;
                }
            }
            else{
                $message = "Le fichier d'acte numérisé n'a pas été trouvé.";
                $good = true;
            }

            // Si le fichier à été supprimé correctement (ou était inexistant), on met le champ de la BD à jour.
            if($good){
                if (mysqli_query($link, "UPDATE fiches_archivees SET archive='x' WHERE id='$id'")){
                    $message .= " (BD mise à jour)";
                }
                else{
                    $message .= " (erreur dans la mise à jour de la BD: ".mysqli_error($link).")";
                }
            }

            deconnexionBD($link);
        }
        else {
            $message = "Impossible d'exécuter la requête de suppression - Permission non accordée.";
            $good = false;
        }
    } // Fin traitement bouton_del_doss

    // Si on a un ID de fiche dans l'url, on va le chercher
    if (isset($_GET['id'])){
        $id = $_GET['id'];
    }
    else if (isset($justAdded)){
        $id = $id;
    }
    else $id = 0;

    // Si on a bien obtenu un ID dans l'url, et qu'on ne vient pas de supprimer la fiche.
    if ($id != 0 && !isset($_POST['bouton_delete'])){

        // On va chercher les informations de la fiche dans la BD et on les stocke dans des variables.
        $link = connexionBD();
        $q = "SELECT * FROM fiches_archivees where id = '".mysqli_real_escape_string($link, $id)."'";
        $r = mysqli_query($link, $q);
        deconnexionBD($link);

        if ($row = mysqli_fetch_array($r)){
            $migrant = $row["migrant"];
            $sexe = $row['sexe'];
            $lieudeces = ($row['lieudeces']=='x')?"":$row['lieudeces'];
            $datedeces = ($row['datedeces']=='x')?"":$row['datedeces'];
            $naissance = ($row['naissance']=='x')?"":$row['naissance'];
            $bapteme = ($row['bapteme']=='x')?"":$row['bapteme'];
            $commune = ($row['commune']=='x')?"":$row['commune'];
            $pays = ($row['pays']=='x')?"":$row['pays'];
            $code1 = ($row['code1']=='x')?"":$row['code1'];
            $pere = ($row['pere']=='x')?"":$row['pere'];
            $mere = ($row['mere']=='x')?"":$row['mere'];
            $datemariage = ($row['datemariage']=='x')?"":$row['datemariage'];
            $lieumariage = ($row['lieumariage']=='x')?"":$row['lieumariage'];
            $code2 = ($row['code2']=='x')?"":$row['code2'];
            $datecontrat = ($row['datecontrat']=='x')?"":$row['datecontrat'];
            $notaire = ($row['notaire']=='x')?"":$row['notaire'];
            $lieucontrat = ($row['lieucontrat']=='x')?"":$row['lieucontrat'];
            $metier = ($row['metier']=='x')?"":$row['metier'];
            $mention = ($row['mention']=='x')?"":$row['mention'];
            $occupation = ($row['occupation']=='x')?"":$row['occupation'];
            $status = ($row['status']=='x')?"":$row['status'];
            $mdatemariage = ($row['mdatemariage']=='x')?"":$row['mdatemariage'];
            $mlieumariage = ($row['mlieumariage']=='x')?"":$row['mlieumariage'];
            $conjoint = ($row['conjoint']=='x')?"":$row['conjoint'];
            $annotation = ($row['annotation']=='x')?"":$row['annotation'];
            $identification = ($row['identification']=='x')?"":$row['identification'];
            $chercheur = ($row['chercheur']=='x')?"":$row['chercheur'];
            $reference = ($row['reference']=='x')?"":$row['reference'];
            $copie = ($row['copie']=='x')?"":$row['copie'];
            $numero = ($row['numero']=='x')?"":$row['numero'];
            $dossiers = ($row['dossiers']=='x')?"":$row['dossiers'];
            $nom = ($row['nom']=='x')?"":$row['nom'];
            $surnom = ($row['surnom']=='x')?"":$row['surnom'];
            $surnom1 = ($row['surnom1']=='x')?"":$row['surnom1'];
            $localite = ($row['localite']=='x')?"":$row['localite'];
            $paroisse = ($row['paroisse']=='x')?"":$row['paroisse'];
            $NOTES = ($row['NOTES']=='x')?"":$row['NOTES'];
            $datemodif = ($row['datemodif']=='x')?"":$row['datemodif'];
            $archive = ($row['archive']=='x')?"":$row['archive'];
            $dateajout = ($row['dateajout']=='x')?"":$row['dateajout'];
            $publication = ($row['publication']=='x')?"":$row['publication'];
            $actenumerises = ($row['actenumerises']=='x')?"":$row['actenumerises'];

            // Puisqu'on affiche une fiche existante, on est en mode d'édition
            $update = true;
        }
        // Si on a un ID de fiche, mais que la fiche n'existe pas, on renvoie l'utilisateur à la liste des fiches.
        else{
            header("Location: listeFichesArchivees.php");
        }
    }

    // Si on a pas d'ID dans l'url, ou qu'on vient de supprimer la fiche
    else{
        // On initialise nos variables vides.
        $migrant = "";
        $sexe = "";
        $lieudeces = "";
        $datedeces = "";
        $naissance = "";
        $bapteme = "";
        $commune = "";
        $pays = "";
        $code1 = "";
        $pere = "";
        $mere = "";
        $datemariage = "";
        $lieumariage = "";
        $code2 = "";
        $datecontrat = "";
        $notaire = "";
        $lieucontrat = "";
        $metier = "";
        $mention = "";
        $occupation = "";
        $status = "";
        $mdatemariage = "";
        $mlieumariage = "";
        $conjoint = "";
        $annotation = "";
        $identification = "";
        $chercheur = "";
        $reference = "";
        $copie = "";
        $numero = "";
        $dossiers = "";
        $nom = "";
        $surnom = "";
        $surnom1 = "";
        $localite = "";
        $paroisse = "";
        $NOTES = "";
        $datemodif = "";
        $archive = "";
        $dateajout = "";
        $publication = "";
        $actenumerises = "";

        // Puisqu'on a pas de fiche, on est en mode d'ajout.
        $update = false;
    }

    // Si on est en mode d'édition sans la permission d'éditer, on renvoie à la page de connexion
    // Si on est en mode d'ajout sans la permission d'ajouter, on renvoie à la page de connexion
    if (($update && !CheckSecurity("Edit")) || (!$update && !CheckSecurity("Add"))) {
        echo "<p>Votre compte n'a pas l'autorisation d'accès à cette page. <a href=\"index.php\">Retour à la page d’ouverture de session</a></p>";
        return;
    }

    // Début du HTML
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Interface de modification d'une fiche">
        <meta name="author" content="Marc-Albert Fournier">
        <title><?php if ($update) echo "Modification d'une fiche"; else echo "Ajout d'une fiche"; ?></title>
        <link rel="stylesheet" media="(min-width: 720px)" href="style.css">
        <link rel="stylesheet" media="(max-width: 720px)" href="styleNarrow.css">
        <?php
        // Script qui empêche la touche Entrée d'envoyer le formulaire d'ajout ou de modification.
        // Un script faisant ce type d'action n'est pas conforme avec les standards d'ergonomie et
        // 	d'accessibilité du Web, mais ça faisait partie des exigences du client.
        // Pour rétablir la fonctionnalité, il suffit de retirer le bloc "script" suivant
        ?>
        <script type="text/javascript">
            function stopRKey(evt) {
                var evt = (evt) ? evt : ((event) ? event : null);
                var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
                if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
            }

            document.onkeypress = stopRKey;
        </script>
    </head>
    <body>
    <h1>Informations de la fiche</h1>
    <?php
    // Formulaire d'ajout/modification de la fiche.
    // En mode d'édition, chaque champ est rempli par la valeur existant déjà dans la BD.
    // En mode d'ajout, tous les champs sont vides.
    ?>
    <form enctype="multipart/form-data" action="modifierFicheArchivee.php<?php if ($update) echo "?id=$id" ?>" method="post">
        <div class="submitButton"><input type="submit" name="bouton_submit" value="Sauvegarder"></div>
        <a href="listeFichesArchivees.php" class="bouton">Retour</a><br/>
        <?php
        // Si on a la permission de publier des fiches, on affiche l'option.
        if (CheckSecurity("Publish")) { ?>
            <div class="donnee">
                <div class="titreChamp">Publié:</div>
                <div class="champ">
                    <input type="checkbox" name="publication" <?php if ($update && $publication == '') echo "checked"; ?>><br/>
                </div>
            </div>
        <?php } ?>

        <div class="gauche">

            <div class="donnee">
                <div class="titreChamp">Numéro d'identification:</div>
                <div class="champ">
                    <input type="text" name="numero" value="<?php echo htmlspecialchars($numero); ?>"><br/>
                </div>
            </div>

            <!--<div class="submitButton"><input type="submit" name="bouton_valider" value="Valider ID"></div>-->

            <div class="submitButton"><input type="submit" name="bouton_valider" value="Valider ID"></div>

            <div class="donnee">
                <div class="titreChamp">Migrant:</div>
                <div class="champ">
                    <input type="text" name="migrant" value="<?php echo htmlspecialchars($migrant); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Sexe:</div>
                <div class="champ" style="font-size:11pt;">
                    <input type="radio" name="sexe" value="M" <?php if ($sexe == 'M') echo "checked";?>>Masculin
                    <input type="radio" name="sexe" value="F" <?php if ($sexe == 'F') echo "checked";?>>Féminin
                </div>
            </div>
            <br>

            <div class="donnee">
                <div class="titreChamp">Lieu d'origine:</div>
                <div class="champ">
                    <input type="text" name="commune" value="<?php echo htmlspecialchars($commune); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Département ou pays:</div>
                <div class="champ">
                    <input type="text" name="pays" value="<?php echo htmlspecialchars($pays); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Code INSEE:</div>
                <div class="champ">
                    <input type="text" name="code1" value="<?php echo htmlspecialchars($code1); ?>"><br/>
                </div>
            </div>
            <br>
            <br>

            <div class="donnee">
                <div class="titreChamp">Date de naissance:</div>
                <div class="champ">
                    <input type="text" name="naissance" value="<?php echo htmlspecialchars($naissance); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Date de baptème:</div>
                <div class="champ">
                    <input type="text" name="bapteme" value="<?php echo htmlspecialchars($bapteme); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Lieu de décès ou inhumation:</div>
                <div class="champ">
                    <input type="text" name="lieudeces" value="<?php echo htmlspecialchars($lieudeces); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Date de décès ou inhumation:</div>
                <div class="champ">
                    <input type="text" name="datedeces" value="<?php echo htmlspecialchars($datedeces); ?>"><br/>
                </div>
            </div>
            <br>
            <br>

            <div class="donnee">
                <div class="titreChamp">Première mention au pays:</div>
                <div class="champ">
                    <input type="text" name="mention" value="<?php echo htmlspecialchars($mention); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Occupation à l'arrivée:</div>
                <div class="champ">
                    <input type="text" name="occupation" value="<?php echo htmlspecialchars($occupation); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Statut matrimonial:</div>
                <div class="champ" style="font-size:11pt;">
                    <input type="radio" name="status" value="M" <?php if ($status == 'M') echo "checked";?>>Marié(e)
                    <input type="radio" name="status" value="C" <?php if ($status == 'C') echo "checked";?>>Célibataire
                    <input type="radio" name="status" value="R" <?php if ($status == 'R') echo "checked";?>>Religieux(se)
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Premier(e) conjoint(e):</div>
                <div class="champ">
                    <input type="text" name="conjoint" value="<?php echo htmlspecialchars($conjoint); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Date de mariage:</div>
                <div class="champ">
                    <input type="text" name="mdatemariage" value="<?php echo htmlspecialchars($mdatemariage); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Lieu du mariage:</div>
                <div class="champ">
                    <input type="text" name="mlieumariage" value="<?php echo htmlspecialchars($mlieumariage); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Date du contrat de mariage:</div>
                <div class="champ">
                    <input type="text" name="datecontrat" value="<?php echo htmlspecialchars($datecontrat); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Nom du notaire:</div>
                <div class="champ">
                    <input type="text" name="notaire" value="<?php echo htmlspecialchars($notaire); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Lieu du contrat:</div>
                <div class="champ">
                    <input type="text" name="lieucontrat" value="<?php echo htmlspecialchars($lieucontrat); ?>"><br/>
                </div>
            </div>

        </div>
        <div class="droite">

            <div class="donnee">
                <div class="titreChamp">Nom du père:</div>
                <div class="champ">
                    <input type="text" name="pere" value="<?php echo htmlspecialchars($pere); ?>"><br/>
                </div>
            </div>


            <div class="donnee">
                <div class="titreChamp">Nom de la mère:</div>
                <div class="champ">
                    <input type="text" name="mere" value="<?php echo htmlspecialchars($mere); ?>"><br/>
                </div>
            </div>


            <div class="donnee">
                <div class="titreChamp">Date de mariage des parents:</div>
                <div class="champ">
                    <input type="text" name="datemariage" value="<?php echo htmlspecialchars($datemariage); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Lieu du mariage des parents:</div>
                <div class="champ">
                    <input type="text" name="lieumariage" value="<?php echo htmlspecialchars($lieumariage); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Code INSEE:</div>
                <div class="champ">
                    <input type="text" name="code2" value="<?php echo htmlspecialchars($code2); ?>"><br/>
                </div>
            </div>



            <div class="donnee">
                <div class="titreChamp">Profession du père:</div>
                <div class="champ">
                    <input type="text" name="metier" value="<?php echo htmlspecialchars($metier); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Remarques:</div>
                <div class="champ">
                    <textarea name="annotation"><?php echo htmlspecialchars($annotation); ?></textarea>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Identification du migrant:</div>
                <div class="champ">
                    <input type="text" name="identification" value="<?php echo htmlspecialchars($identification); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Chercheur(s):</div>
                <div class="champ">
                    <input type="text" name="chercheur" value="<?php echo htmlspecialchars($chercheur); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Référence:</div>
                <div class="champ">
                    <input type="text" name="reference" value="<?php echo htmlspecialchars($reference); ?>"><br/>
                </div>
            </div>
            <br>

            <div class="donnee">
                <div class="titreChamp">Copie d'acte:</div>
                <div class="champ">
                    <input type="text" name="copie" value="<?php echo htmlspecialchars($copie); ?>"><br>
                </div>
            </div>


            <input type="hidden" name="actenumerises" value="<?php echo htmlspecialchars($actenumerises); ?>">
            <?php if ($actenumerises != ""){ ?>
                <div class="donnee">
                    <div class="titreChamp">Acte numérisé:</div>
                    <div class="champ">
                        <span style="font-size:14px; color: black; padding: 4px;"><?php echo getUrl($actenumerises);  if (CheckSecurity("Delete")) echo " <input type='submit' name='bouton_del_acte' value=\"Supprimer l'acte numérisé\" class='redButton smallButton'>"; ?></span><br>
                    </div>
                </div>
            <?php } ?>

            <div class="donnee" style="padding: 4px 0;">
                <div class="titreChamp"><?php if ($actenumerises == '') echo "Acte numérisé:"; else echo "Nouveau fichier d'acte:"; ?></div>
                <div class="champ">
                    <input type="file" name="fileActe">
                </div>
            </div>


            <input type="hidden" name="archive" value ="<?php echo htmlspecialchars($archive); ?>">
            <?php if ($archive != ""){ ?>
                <div class="donnee" style="padding: 4px 0;">
                    <div class="titreChamp">Dossier d'archive:</div>
                    <div class="champ">
                        <span style="font-size:14px; color: black; padding: 4px;"><?php echo getUrl($archive); if (CheckSecurity("Delete")) echo " <input type='submit' name='bouton_del_doss' value='Supprimer le dossier numérisé' class='redButton smallButton'>"; ?></span><br>
                    </div>
                </div>
            <?php } ?>

            <div class="donnee" style="padding: 4px 0;">
                <div class="titreChamp"><?php if ($archive == '') echo "Dossier d'archive:"; else echo "Nouveau fichier d'archive:"; ?></div>
                <div class="champ">
                    <input type="file" name="fileDossier">
                </div>
            </div>
            <br>

            <div class="donnee">
                <div class="titreChamp">Premier nom:</div>
                <div class="champ">
                    <input type="text" name="nom" value="<?php echo htmlspecialchars($nom); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Premier surnom:</div>
                <div class="champ">
                    <input type="text" name="surnom" value="<?php echo htmlspecialchars($surnom); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Deuxième surnom:</div>
                <div class="champ">
                    <input type="text" name="surnom1" value="<?php echo htmlspecialchars($surnom1); ?>"><br/>
                </div>
            </div>

            <div class="donnee">
                <div class="titreChamp">Localité actuelle:</div>
                <div class="champ">
                    <input type="text" name="localite" value="<?php echo htmlspecialchars($localite); ?>"><br/>
                </div>
            </div>


            <div class="donnee">
                <div class="titreChamp">Paroisse religieuse:</div>
                <div class="champ">
                    <input type="text" name="paroisse" value="<?php echo htmlspecialchars($paroisse); ?>"><br/>
                </div>
            </div>


            <div class="donnee">
                <div class="titreChamp">NOTES:</div>
                <div class="champ">
                    <textarea name="NOTES"><?php echo htmlspecialchars($NOTES); ?></textarea>
                </div>
            </div>

        </div>
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <input type="hidden" name="update" value="<?php echo ($update) ? "true" : "false"; ?>">
        <div class="datesModif">
            Ajouté le : <?php echo $dateajout; ?><br/>
            Dernière modification le : <?php echo $datemodif; ?><br/>
            Traité par : <?php echo $dossiers; ?>
        </div>
        <?php
        // Si on a un message, on l'affiche en bas de la page.
        if (isset($message)) {
            if ($good)
                echo "<div class='rempMessageGood'>$message</div>";
            else
                echo "<div class='rempMessageBad'>$message</div>";
        }
        ?>
        <div class="submitButton"><input type="submit" name="bouton_submit" value="Sauvegarder"></div>
        <?php
        // Si on a la permission de supprimer les fiches, on affiche l'option.
        if ($update && CheckSecurity("Delete")) {?>
            <div class="deleteButton">
                <input type="submit" name="bouton_delete" value="Supprimer cet enregistrement" class="redButton"><br>
            </div>
            <div class="deleteButton">
                <input type="submit" name="bouton_publier" value="publier cet archive" class="yellowButton"><br>
            </div><?php } ?>

    </form><br/><br/>
    <a href="listeFichesArchivees.php" class="bouton">Retour</a>
    </body>
</html>
