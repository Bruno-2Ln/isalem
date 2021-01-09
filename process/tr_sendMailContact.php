<?php
session_start();

require_once('../db/db.php');
require_once('../db/connect_db.php');
require_once('../display_function.php');

//Vérification de l'existence des clés/valeurs
if(isset($_POST['email']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['message']) && isset($_POST['captcha'])){

    //Préparation des clés de $_SESSION a recevoir les valeurs correspondantes de $_POST et ainsi même si le formulaire n'est pas correctement
    //rempli, il n'apparaitra pas vierge, n'obligeant pas l'utilisateur à tout re-remplir mais plutôt à compléter son formulaire.
    $_SESSION['firstname'] = $_POST['firstname'];
    $_SESSION['lastname'] = $_POST['lastname'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['message'] = $_POST['message'];

    //vérification si le captcha rentré côté formulaire est le même que celui généré et enregistré dans $_SESSION.
    if($_POST['captcha'] == $_SESSION['captcha']){

        //vérification qu'elles ne soient pas vide
        if(!empty($_POST['email']) && !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['message'])){
            
            //vérifications que les champs remplis respectent les regex établies
            if (preg_match("/^[A-Za-z '-]+$/", $_POST['firstname']) && preg_match("/^[A-Za-z '-]+$/", $_POST['lastname']) && preg_match("/[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+.[a-zA-Z.]{2,15}/", $_POST['email'])){
                //Récupération de l'adresse mail admin enregistrée comme contact actuellement.
                $mail = getIsalemDatabaseHandler()->getAdminContact();
            
                $dest = displayAdmin($mail);
                $object = "Contact ISALEM";
                $text.= $_POST['firstname'] . " " . $_POST['lastname'] . " \n";
                $text.= $_POST['email'] . " \n";
                $text.= $_POST['message'];

                if (mail($dest, $object, htmlspecialchars($text))){
                    //si la fonction est validée alors la valeur true va permettre un message de succès à la redirection
                    $_SESSION['message_sent'] = true;
                    //Le mail étant envoyé, les valeurs des champs n'ont plus besoin de retour et la redirection se fait sur un formulaire vierge
                    $_SESSION['firstname'] = '';
                    $_SESSION['lastname'] = '';
                    $_SESSION['email'] = '';
                    $_SESSION['message'] = '';
                } else {
                    //sinon un message d'echec
                    $_SESSION['error_message_sent'] = true;
                }   
                //dans les deux cas la redirection se fait sur la page contact
                header('Location: ../isalem/contact.php');
            } else {
                //si l'un des champs ne respectent pas le format des regex, redirection vers la page contact avec message d'information.
                $_SESSION['error_format'] = true;
                header('Location: ../isalem/contact.php');
            }
        } else {
            //si un des champs est vide, la valeur true pour afficher un message après la redirection sur la page contact
            $_SESSION["error_input_empty"] = true;
            header('Location: ../isalem/contact.php');
        }
    } else {
        //si les captcha ne correspondent pas alors redirection vers la page contact avec $_SESSION['error_captcha] à true pour afficher
        //un message d'info.
        $_SESSION["error_captcha"] = true;
        header('Location: ../isalem/contact.php');
    }
//si les clés n'existent pas alors redirection vers l'accueil sans message d'information
} else {
    header('Location: ../isalem/index.php');  
}