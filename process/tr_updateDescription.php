<?php
session_start();
require_once('../db/db.php');
require_once('../db/connect_db.php');
require_once('../display_function.php');
require_once('../function.php');

//si les clés renfermant l'id du test et de l'admin existent..
if (isset($_GET['u']) && isset($_SESSION['admin_id'])){
    $idTest = $_GET['u'];
    $admin = getIsalemDatabaseHandler()->getAdminByid($_SESSION['admin_id']);

    if (isset($admin)){

        if (!preg_match("/script/", $_POST['description'])){

            //..récupération de l'objet Test par son id
            $resultEdit = getIsalemDatabaseHandler()->getTestById($idTest);
            $description = $_POST['description'];

            $decription = cleanData($decription);

            //puis modification, passage de la clé "update" à true pour message de confirmation après redirection.
            getIsalemDatabaseHandler()->updateTest($idTest, $description);
            $_SESSION["update"] = true;
            header('Location: ../admin/updateDescription.php');
            
        } else {
            header('Location: ../isalem/index.php');
        }
    } else {
        header('Location: ../isalem/index.php');
    }
} else {
    header('Location: ../isalem/index.php');
}