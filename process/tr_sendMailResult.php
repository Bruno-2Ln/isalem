<?php
session_start();

require_once('../db/db.php');
require_once('../db/connect_db.php');

if(isset($_POST['email']) && isset($_POST['firstname']) && isset($_POST['lastname'])){
    //Préparation des clés de $_SESSION a recevoir les valeurs correspondantes de $_POST et ainsi même si le formulaire n'est pas correctement
    //rempli, il n'apparaitra pas vierge, n'obligeant pas l'utilisateur à tout re-remplir mais plutôt à compléter son formulaire.
    $_SESSION['firstname'] = $_POST['firstname'];
    $_SESSION['lastname'] = $_POST['lastname'];
    $_SESSION['email'] = $_POST['email'];

    if(!empty($_POST['email']) && !empty($_POST['firstname']) && !empty($_POST['lastname'])){

         //vérifications que les champs remplis respectent les regex établies
        if (preg_match("/^[A-Za-z '-]+$/", $_POST['firstname']) && preg_match("/^[A-Za-z '-]+$/", $_POST['lastname']) && preg_match("/[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+.[a-zA-Z.]{2,15}/", $_POST['email'])){
            //la variable récupère le destinataire du mail
            $dest = trim($_POST['email']);
            //la variable enferme l'objet mail relatif au résultat obtenu (deux tables relationnelles)
            $mail = getIsalemDatabaseHandler()->getMail($_SESSION['result']);
            //la variable prépare la propriété object de $mail
            $mailObject = html_entity_decode($mail->object, ENT_HTML5);
            //la variable qui sera utilisée comme paramètre d'objet du mail dans la fonction mail()
            $object = $mailObject . " " . htmlspecialchars($_POST['firstname']) . " " . htmlspecialchars($_POST['lastname']);
            //le corps du mail
            $body = html_entity_decode($mail->body, ENT_HTML5);

            $entete = 'MIME-Version: 1.0' . "\r\n";
            $entete .= 'Content-type: text/html; charset=UTF-8' . "\n";
            
            $entete .= "From:ISALEM <isalem@test-isalem.fr>". "\r\n";
            $entete .= "Date:".date("D, j M Y H:i:s +0100")."\r\n";
    
            if (!empty($_POST['email_copy']) && preg_match("/[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+.[a-zA-Z.]{2,15}/", $_POST['email_copy'])){
                $entete .= "cc:".trim($_POST['email_copy']);
            }

            if(mail($dest,$object,$body,$entete)){
                //si la fonction est validée alors la valeur true va permettre un message de succès à la redirection
                $_SESSION['message_sent'] = true;
                //Le mail étant envoyé, les valeurs des champs n'ont plus besoin de retour et la redirection se fait sur un formulaire vierge
                $_SESSION['firstname'] = '';
                $_SESSION['lastname'] = '';
                $_SESSION['email'] = '';
            
            } else {
                //sinon un message d'echec
                $_SESSION['error_message_sent'] = true;
            }
            //dans les deux cas la redirection se fait sur la page result.php
            header('Location: ../isalem/result.php');
        } else {
            //si l'un des champs ne respectent pas le format des regex, redirection vers la page contact avec message d'information.
            $_SESSION['error_format'] = true;
            header('Location: ../isalem/result.php');
        }
    } else {
        //si un des champs est vide, la valeur true pour afficher un message après la redirection sur la page résultat
        $_SESSION["error_input_empty"] = true;
        header('Location: ../isalem/result.php');
    }
} else {
    //si les clés n'existent pas alors redirection vers l'accueil sans message d'information
    header('Location: ../isalem/index.php');
}