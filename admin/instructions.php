<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/main.css">
        <link rel="stylesheet" href="../css/instructions.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
        <script src="../js/deleteInstruction.js"></script>  
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>  
        <title>ISALEM</title>
    </head>
    <body>
        <div class="main">

        <?php 
        require_once('../db/db.php');
        require_once('../db/connect_db.php');
        require_once('../display_function.php');

        if (isset($_SESSION['admin_id'])){
            $admin = getIsalemDatabaseHandler()->getAdminByid($_SESSION['admin_id']);
    
            if (isset($admin)){
                echo displayNavAdmin($admin);
                echo displayHeader('Instructions');

                $messages = getIsalemDatabaseHandler()->getAllMessages();
                foreach ($messages as $message){
                    echo displayMessage($message, $_SESSION["$message->object"]);
                    $_SESSION["$message->object"] = '';
                }
                ?>

            <div class="container mt-5 pb-5 pt-4 d-flex flex-column align-items-center">

                <div class="w-100 h-25 d-flex justify-content-center">
                    <a href="createInstruction.php" class="mb-4 w-25 d-flex justify-content-center">
                        <button class="btn btn-fa">
                            <i class="fas fa-plus-square"></i>
                        </button>
                    </a>
                </div>

                <table class="table table-data">
                    <thead>
                        <th class="text-center">#</th>
                        <th class="text-center">Texte</th>
                        <th class="text-center">Modifier</th>
                        <th class="text-center">Effacer</th>
                    </thead>
                    <tbody>
        
                        <?php 
                        $instructions = getIsalemDatabaseHandler()->getAllInstructions();

                        foreach ($instructions as $instruction){
                            echo displayInstruction($instruction, $admin);
                        }
                        ?>    

                    </tbody>
                </table>
            </div>

        <?php 
        echo displayFooter($admin);
            } else {
                header('Location: ../isalem/index.php');
            } 
        } else {
            header('Location: ../isalem/index.php');
        }
        ?>

        </div>
    </body>
</html>