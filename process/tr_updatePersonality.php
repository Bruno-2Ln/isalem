<?php
session_start();
require_once('../db/db.php');
require_once('../db/connect_db.php');
require_once('../display_function.php');
require_once('../function.php');

//vérification de l'existance de l'id cible de la modification, de l'id de l'admin et de l'id de la question
if (isset($_GET['u']) && isset($_SESSION['admin_id']) && isset($_SESSION['category'])){
    $admin = getIsalemDatabaseHandler()->getAdminByid($_SESSION['admin_id']);

    if (isset($admin)){

        if (!preg_match("/script/", $_POST['content'])){

            $category = $_SESSION['category'];
            $idPersonality = $_GET['u'];
            $content = $_POST['content'];

            $content = cleanData($content);

            //modification de la réponse ciblée
            getIsalemDatabaseHandler()->updatePersonality($idPersonality, $content);

            //avant la redirection, je passe à true la clé pour permettre l'affiche d'un message d'info
            $_SESSION["update"] = true;
            //la redirection se fait vers la page précédente pour permettre d'autres modifications sur la question ou ses autres réponses
            header('Location: ../admin/updatePersonality.php?u='."$category");

        } else {
            header('Location: ../isalem/index.php');
        }
    } else {
        header('Location: ../isalem/index.php');
    }
} else {
    header('Location: ../isalem/index.php');
}