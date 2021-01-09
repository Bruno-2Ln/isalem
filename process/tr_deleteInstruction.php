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

        //si la clé/valeur $_GET['d'] est valide
        if (isset($_GET['d'])){

            $id = $_GET['d']; 
            //suppression de l'objet instruction correspondant à l'id
            getIsalemDatabaseHandler()->deleteInstruction($id);
            //$_SESSION["deleteInstruction"] passe à true permettant à la redirection qui suivra un message de confirmation
            $_SESSION["delete"] = true;
            header('Location: ../admin/instructions.php');

        } else {
            header('Location: ../admin/instructions.php');
        } 
    } else {
        header('Location: ../isalem/index.php');
    }
} else { 
   header('Location: ../isalem/index.php');
}

