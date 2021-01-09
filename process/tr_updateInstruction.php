<?php
session_start();
require_once('../db/db.php');
require_once('../db/connect_db.php');
require_once('../display_function.php');
require_once('../function.php');

//vérification de l'existance de l'id cible de la modification et de l'id de l'admin
if (isset($_SESSION['idInstruction']) && isset($_SESSION['admin_id'])){
    $admin = getIsalemDatabaseHandler()->getAdminByid($_SESSION['admin_id']);

    if (isset($admin)){

        if (isset($_POST['orderOfAppearance']) && isset($_POST['content'])){
    
            $idInstruction = $_SESSION['idInstruction'];

            if (!empty($_POST['orderOfAppearance']) && !empty($_POST['content'])){

                if (!preg_match("/script/", $_POST['content']) && !preg_match("/script/", $_POST['orderOfAppearance'])){
                    //récupération de l'objet instruction instancié qui sera la cible de la modification
                    $resultEdit = getIsalemDatabaseHandler()->getInstruction($idInstruction); 

                    $content = $_POST['content'];
                    $orderOfAppearance = $_POST['orderOfAppearance'];
                    
                    
                    $content = cleanData($content);
                    $orderOfAppearance = cleanData($orderOfAppearance);

                    //modification du contenu de l'instruction
                    getIsalemDatabaseHandler()->updateInstruction($idInstruction, $content, $orderOfAppearance);
                    //passage à true pour permettre le message de confirmation après la redirection
                    $_SESSION["update"] = true;
                    header('Location: ../admin/instructions.php');
                    
                } else {
                    header('Location: ../isalem/index.php');
                }
            } else {
                //si un champs n'est pas rempli, redirection vers la page de création avec le clé prennant true comme valeur permettant un message d'info
                $_SESSION["error_input_empty"] = true;
                header('Location: ../admin/updateInstruction.php?u='.$idInstruction);
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