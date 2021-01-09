<?php
session_start();
require_once('../db/db.php');
require_once('../db/connect_db.php');
require_once('../display_function.php');

//vérification de l'existance de la clé avec l'id cible de la modification et de la clé renfermant l'id de l'admin
if (isset($_SESSION['category']) && isset($_SESSION['admin_id'])){
    $admin = getIsalemDatabaseHandler()->getAdminByid($_SESSION['admin_id']);

    if (isset($admin)){

        if (!preg_match("/script/", $_POST['title']) && !preg_match("/script/", $_POST['content'])){

            $category = $_SESSION['category'];
            //récupération de l'objet admin instancié qui sera la cible de la modification
            $resultEdit = getIsalemDatabaseHandler()->getResultOfTest($category);

            $title = $_POST['title'];
            $content = $_POST['content'];

            $title = cleanData($title);
            $content = cleanData($content);

            getIsalemDatabaseHandler()->updateResult(htmlspecialchars($category), htmlspecialchars($title), htmlspecialchars($content));
            //la modification faite, avant redirection, la clé suivante passe à true ce qui permmettra l'affichage d'un message de confirmation.
            $_SESSION["update"] = true;
            header('Location: ../admin/results.php');

        } else {
            header('Location: ../isalem/index.php');
        }
    } else {
    header('Location: ../isalem/index.php');
    }
} else {
    header('Location: ../isalem/index.php');
}

