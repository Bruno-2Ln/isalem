<?php
session_start();
require_once('../db/db.php');
require_once('../db/connect_db.php');
require_once('../display_function.php');
require_once('../function.php');

//vérification de l'existance de l'id cible de la modification et de l'id de l'admin
if (isset($_SESSION['idQuestion']) && isset($_SESSION['admin_id'])){
    $admin = getIsalemDatabaseHandler()->getAdminByid($_SESSION['admin_id']);

    if (isset($admin)){

        if (!preg_match("/script/", $_POST['content'])){ 

            $idQuestion = $_SESSION['idQuestion'];
            //récupération de l'objet admin instancié qui sera la cible de la modification
            $resultEdit = getIsalemDatabaseHandler()->getQuestion($idQuestion);

            $content = $_POST['content'];
            $content = cleanData($content);

            //modification de la question cible
            getIsalemDatabaseHandler()->updateQuestion($idQuestion, $content);
            //la clé suivante prend la valeur true afin qu'à la redirection un message de confirmation s'affiche
            $_SESSION["update"] = true;
            //redirection vers la page précédente afin de peut être modifier les réponses de cette question
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