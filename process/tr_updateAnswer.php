<?php
session_start();
require_once('../db/db.php');
require_once('../db/connect_db.php');
require_once('../display_function.php');
require_once('../function.php');

//vérification de l'existance de l'id cible de la modification, de l'id de l'admin et de l'id de la question
if (isset($_GET['u']) && isset($_SESSION['admin_id']) && isset($_SESSION['idQuestion'])){
    $admin = getIsalemDatabaseHandler()->getAdminByid($_SESSION['admin_id']);

    if (isset($admin)){
        $idQuestion = $_SESSION['idQuestion'];
        $idAnswer = $_GET['u'];
        
        if (!preg_match("/script/", $_POST['content'])){

            //récupération de l'objet admin instancié qui sera la cible de la modification
            $resultEdit = getIsalemDatabaseHandler()->getAnswer($idAnswer);

            $content = $_POST['content'];
            $content = cleanData($content);

            //modification de la réponse ciblée
            getIsalemDatabaseHandler()->updateAnswer($idAnswer, $content);

            //avant la redirection, je passe à true la clé pour permettre l'affiche d'un message d'info
            $_SESSION["update"] = true;
            //la redirection se fait vers la page précédente pour permettre d'autres modifications sur la question ou ses autres réponses
            header('Location: ../admin/updateQuestionAnswers.php?u='."$idQuestion");

        } else {
            header('Location: ../isalem/index.php');
        }
    } else {
        header('Location: ../isalem/index.php');
    }
} else {
    header('Location: ../isalem/index.php');
}