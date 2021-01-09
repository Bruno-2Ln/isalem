<?php
session_start();
require_once('../db/db.php');
require_once('../db/connect_db.php');
require_once('../display_function.php');

// vérification de l'existence' de la clé/valeur $_SESSION['admin_id']
if (isset($_SESSION['admin_id'])){
   //récupération de l'objet admin par la valeur de $_SESSION['admin_id']
   $admin = getIsalemDatabaseHandler()->getAdminByid($_SESSION['admin_id']);

   //si la variable existe..
   if (isset($admin)){

      //..vérification qu'il s'agit bien d'un SUPER_ADMIN
      if ($admin->roles_admin === 1){

         //si la clé/valeur $_GET['d'] est valide
         if (isset($_GET['d'])){
            $emailAddress = $_GET['d']; 
            //récupération de l'admin ciblé par la suppression
            $adminDelete = getIsalemDatabaseHandler()->getAdminByEmailAddress($emailAddress);
            //si l'admin n'a pas son adresse email enregistrée comme celle qui reçoit les messages des utilisateurs alors..
            if ($adminDelete->mail_contact == 0){
            //...suppression de l'objet admin correspondant à l'id
               getIsalemDatabaseHandler()->deleteAdmin($adminDelete->id);

               //$_SESSION["deleteAdmin"] passe à true permettant à la redirection qui suivra un message de confirmation
               $_SESSION["delete"] = true;
               header('Location: ../admin/admins.php');

            } else { 
               //si cet admin ciblé par la suppression est actuellement celui qui reçoit les messages d'utilisateurs alors redirection vers
               //la page des admins avec préparation d'un message d'information invitant le SUPER_ADMIN à d'abord définir un nouvel admin
               //comme contact avant la suppression de celui-ci.
               $_SESSION['contact_email_address'] = true;
               header('Location: ../admin/admins.php');
            }
         } else {
            header('Location: ../admin/admins.php');
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

