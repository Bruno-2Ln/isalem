<?php
session_start();
require_once('../db/db.php');
require_once('../db/connect_db.php');
require_once('../function.php');

if (isset($_SESSION['admin_id'])){
    // si la clé/valeur existe je récupère l'objet admin correspondant à l'id
    $admin = getIsalemDatabaseHandler()->getAdminByid($_SESSION['admin_id']);

    if (isset($admin)){

        if (isset($_POST['orderOfAppearance']) && isset($_POST['content'])){
    
            if (!empty($_POST['orderOfAppearance']) && !empty($_POST['content'])){

                if (!preg_match("/script/", $_POST['content']) && !preg_match("/script/", $_POST['orderOfAppearance'])){
        
                    $orderOfAppearance = $_POST['orderOfAppearance'];
                    $content = $_POST['content'];

                    $content = cleanData($content);
                    $orderOfAppearance = cleanData($orderOfAppearance);
                
                    getIsalemDatabaseHandler()->createInstruction(
                                                        $orderOfAppearance, 
                                                        $content);
                        
                    $_SESSION["create_instruction"] = true;
                    header('Location: ../admin/instructions.php');  

                } else {
                    header('Location: ../isalem/index.php');
                }  
            } else {
            //si un champs n'est pas rempli, redirection vers la page de création avec le clé prennant true comme valeur permettant un message d'info
            $_SESSION["error_input_empty"] = true;
            header('Location: ../admin/createInstruction.php');
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