<?php
session_start();
require_once('../db/db.php');
require_once('../db/connect_db.php');
require_once('../function.php');

if (isset($_SESSION['admin_id'])){
    // si la clé/valeur existe je récupère l'objet admin correspondant à l'id
    $admin = getIsalemDatabaseHandler()->getAdminByid($_SESSION['admin_id']);

    if (isset($admin)){
        //si la variable existe, vérification qu'il s'agit bien d'un SUPER_ADMIN
        if ($admin->roles_admin === 1){

            if (isset($_POST['firstname']) 
                && isset($_POST['lastname']) 
                && isset($_POST['roles_admin']) 
                && isset($_POST['email']) 
                && isset($_POST['password']) 
                && isset($_POST['repeat_password'])){
                //Préparation des clés de $_SESSION à recevoir les valeurs correspondantes 
                //de $_POST et ainsi même si le formulaire n'est pas correctement
                //rempli, il n'apparaitra pas vierge, n'obligeant pas le SUPER_ADMIN à 
                //tout re-remplir mais plutôt à compléter son formulaire.
                $_SESSION['firstname'] = $_POST['firstname'];
                $_SESSION['lastname'] = $_POST['lastname'];
                $_SESSION['email'] = $_POST['email'];

                // tous les champs doivent être remplis
                if (!empty($_POST['roles_admin']) 
                    && !empty($_POST['firstname']) 
                    && !empty($_POST['lastname']) 
                    && !empty($_POST['email']) 
                    && !empty($_POST['password'])){ 
                
                    if ($_POST['password'] == $_POST['repeat_password']){

                        //Tentative de connection par l'adresse mail renseignée afin d'éviter 2 profils admins avec une même adresse..
                        $adminVerif = getIsalemDatabaseHandler()->getAdminByEmailAddress($_POST['email']);
                        //Si la tentative échoue, la création du nouvel admin peut se faire
                        if (!$adminVerif){
                            //si la case contact mail est cochée alors avant modification, appel de la méthode suivante pour
                            //supprimer le mail de contact actuel, et $contactMail sera égale à 1
                            //ainsi cet admin modifié devient le seul mail de contact.
                            if (!empty($_POST['contact_mail'])){
                                getIsalemDatabaseHandler()->updateAdminContact();
                                $contactMail = 1;
                                //sinon il sera égale à 0 et ne sera donc pas le contact mail administrateur.
                            } else {
                                $contactMail = 0; 
                            }

                            $firstname = $_POST['firstname'];
                            $lastname = $_POST['lastname'];
                            $roles = $_POST['roles_admin'];
                            $email = $_POST['email'];
                            $contactMail;
                            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                            $firstname = cleanData($firstname);
                            $lastname = cleanData($lastname);
                            $email = cleanData($email);


                            getIsalemDatabaseHandler()->createAdmin(
                                                            $contactMail, 
                                                            $firstname, 
                                                            $lastname, 
                                                            $roles, 
                                                            $email, 
                                                            htmlspecialchars($password));
                            
                            $_SESSION["create_admin"] = true;
                            header('Location: ../admin/admins.php');

                        } else {
                        //si l'adresse mail renseignée existe déjà alors redirection vers la page de création avec
                        //la clé $_SESSION à true pour informer de l'erreur.
                        $_SESSION["know_email_address"] = true;
                        header('Location: ../admin/signup.php');
                        }
                    } else {
                            //si les mots de passe ne correspondent pas, redirection vers la page de création pour afficher message d'info.
                            $_SESSION["error_repeat_password"] = true;
                            header('Location: ../admin/signup.php');
                    }
                } else {
                    // si un champs n'est pas rempli alors redirection vers la page de création
                    // la valeur booléenne à true va permettre de déclencher un message d'information.
                    $_SESSION["error_input_empty"] = true;
                    header('Location: ../admin/signup.php');
                }
            } else {
                header('Location: ../isalem/index.php');
            }
        } else {
            header('Location: ../isalem/index.php');
        }
    } else {
        header('Location: ../isalem/index.php');
    }
} else {
    header('Location: ../isalem/index.php');
}