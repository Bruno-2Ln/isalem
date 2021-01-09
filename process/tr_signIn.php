<?php
session_start();
require_once('../db/db.php');
require_once('../db/connect_db.php');

//si les variables existent et contiennent une valeur
if (isset($_POST['email']) && isset($_POST['password']) ){ 
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    //..récupération de l'admin par l'adresse mail renseignée précédemment
    $admin = getIsalemDatabaseHandler()->getAdminByEmailAddress($email);

    //initialisation de la clé suivante avant toute connection, permettant d'afficher un message d'info sans qu'un admin ne soit encore connecté.
    $_SESSION["error_password_mail"] = '';
    
    //..si l'admin est trouvé
    if ($admin){
        //..vérification que le mot de passe renseigné et le même que celui enregistré
        if(password_verify($password, $admin->password)){ 

            //.. récupération de l'id de l'admin pour la clé $_SESSION['admin_id']
            $_SESSION['admin_id'] = $admin->id;

            // initialisation de toutes les clés qui permetteront d'afficher un message d'information dans la navigation
            $_SESSION["delete_admin"] = '';
            $_SESSION["create_admin"] = ''; 
            $_SESSION["error_repeat_password"] = '';
            $_SESSION["error_password"] = '';
            $_SESSION["error_input_empty"] = '';
            $_SESSION["update"] = '';
            $_SESSION["delete"] = '';
            $_SESSION["create_instruction"] = '';
            $_SESSION['message_sent'] = '';
            $_SESSION["error_message_sent"] = '';

            //...et redirection vers la page statistiques.php..
            header('Location: ../admin/statistiques.php');
            
        } else {          
            //... les mots de passe ne correspondent pas, redirection vers page de connection avec message d'information
            $_SESSION['error_password_mail'] = true;                                   
            header('Location: ../admin/signin-admin3459.php');
        }
    } else {
        //.. l'adresse mail n'existe pas, redirection vers page de connection avec message d'information
        $_SESSION['error_password_mail'] = true;
        header('Location: ../admin/signin-admin3459.php');
    }    
} else {      
    // si le formulaire est invalide alors redirection vers accueil sans explication.            
    header('Location: ../isalem/index.php');
}
