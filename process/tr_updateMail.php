<?php
session_start();
require_once('../db/db.php');
require_once('../db/connect_db.php');
require_once('../display_function.php');
require_once('../function.php');

//vérification de l'existance de la clé renfermant l'id cible de la modification et de la clé renfermant celle de l'admin
if ($_SESSION['idMail'] && isset($_SESSION['admin_id'])){
    $idMail = $_SESSION['idMail'];
    $admin = getIsalemDatabaseHandler()->getAdminByid($_SESSION['admin_id']);
        
    if (isset($admin)){

        if (!preg_match("/script/", $_POST['object']) && !preg_match("/script/", $_POST['body'])){

            //récupération de l'objet mail qui sera la cible de la modification
            $mailEdit = getIsalemDatabaseHandler()->getMailById($idMail);

            $object = $_POST['object'];
            $body = $_POST['body'];


            $object = cleanData($object);
            $body = cleanData($body);

            getIsalemDatabaseHandler()->updateMail($idMail, $object, $body);
            //après modification, la clé suivante passe à true afin d'afficher un message de confirmation après redirection
            $_SESSION["update"] = true;
            header('Location: ../admin/mail.php?u='.$mailEdit->id);
        } else {
            header('Location: ../isalem/index.php');
        }
    } else {
        header('Location: ../isalem/index.php');
    }
} else {
    header('Location: ../isalem/index.php');
}