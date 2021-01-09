<?php
session_start();
require_once('../db/db.php');
require_once('../db/connect_db.php');
require_once('../display_function.php');
require_once('../function.php');

//TODO : Création de regex !

//vérification de l'existance de l'id d'un admin connecté
if (isset($_SESSION['admin_id'])){ 
    $admin = getIsalemDatabaseHandler()->getAdminByid($_SESSION['admin_id']);

    if (isset($admin)){
         //vérification de l'existance d'un id cible de la modification. 
        if (isset($_SESSION['editId'])){

            $id = $_SESSION['editId'];
            //récupération de l'objet admin instancié qui sera la cible de la modification
            $adminEdit = getIsalemDatabaseHandler()->getAdminByid($id);
            $password = $_POST['last_password'];

            //vérification que le mot de passe est bien le bon
            if (password_verify($password, $adminEdit->password)){

                //tous les champs doivent être remplis
                if (!empty($_POST['roles_admin']) && !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['email']) && !empty($_POST['new_password'])){

                    //vérification que le nouveau et correctement répété
                    if ($_POST['new_password'] === $_POST['repeat_password']){

                        //Tentative de connection par l'adresse mail renseignée..
                        $adminVerif = getIsalemDatabaseHandler()->getAdminByEmailAddress($_POST['email']);
                        //Si la tentative échoue ou si l'id de l'admin cible de la modification est le même que
                        //l'id de l'admin trouvé lors de cette vérification, la création du nouvel admin peut se faire,
                        if (!$adminVerif || $adminVerif->id === $adminEdit->id){
                            //si la case contact mail est cochée ou si l'admin est le contact mail alors avant modification, appel de la méthode suivante pour
                            //supprimer le mail de contact actuel, ainsi cet admin modifié devient le seul mail de contact.
                            if (!empty($_POST['contact_mail']) || $adminEdit->mail_contact == 1){
                                getIsalemDatabaseHandler()->updateAdminContact();
                                $contactMail = 1;
                            } else {
                                $contactMail = 0;
                            }
                            //la modification peut maintenant se faire
                            $roles_admin = $_POST['roles_admin'];
                            $firstname = $_POST['firstname'];
                            $lastname = $_POST['lastname'];
                            $emailAddress = $_POST['email'];
                            $contactMail;
                            $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                            $firstname = cleanData($firstname);
                            $lastname = cleanData($lastname);
                            $emailAddress = cleanData($emailAddress);

                            getIsalemDatabaseHandler()->updateAdmin(
                                                                    $id, 
                                                                    $roles_admin, 
                                                                    $contactMail, 
                                                                    $firstname, 
                                                                    $lastname, 
                                                                    $emailAddress, 
                                                                    htmlspecialchars($password));
                            
                            $_SESSION["update"] = true;
                            header('Location: ../admin/admins.php');
                        } else {
                            //si l'adresse mail renseignée existe déjà alors redirection vers la page de création avec
                            //la clé $_SESSION à true pour informer de l'erreur.
                            $_SESSION["know_email_address"] = true;
                            header('Location: ../admin/updateAdmin.php?u='.$id);
                        }
                    } else {
                        //si les mots de passe ne correspondent pas, redirection vers la page d'update, un message d'information s'affichera.
                        $_SESSION["error_repeat_password"] = true;
                        header('Location: ../admin/updateAdmin.php?u='.$id);
                    }
                } else {
                    // si un champs n'est pas rempli alors redirection vers la page de modification de l'admin cible
                    // la valeur booléenne à true va permettre de déclencher un message d'information.
                    $_SESSION["error_input_empty"] = true;
                    header('Location: ../admin/updateAdmin.php?u='.$id);
                }
            } else {
                //si le mot de passe du SUPER_ADMIN n'est pas le bon, redirection vers la page du formulaire de modification
                //admin avec message d'indication de mot de passe erroné
                $_SESSION["error_password"] = true;
                header('Location: ../admin/updateAdmin.php?u='.$id);                                                    
            }
        } else {
            //si $_SESSION['editId'] n'existe pas redirection vers l'accueil.
            header('Location: ../isalem/index.php');
        } 
    } else {
        header('Location: ../isalem/index.php');
    }   
} else { 
    //si $_SESSION['admin_id'] n'existe pas alors redirection vers la page d'accueil sans message particulier.
    header('Location: ../isalem/index.php');
}



