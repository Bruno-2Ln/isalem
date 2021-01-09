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
            } 
        }
        ?>

            <div class="d-flex justify-content-center align-items-center mt-5">
                <div class="container div-form div-hover mt-5">

                <?php
                $messages = getIsalemDatabaseHandler()->getAllMessages();
                foreach ($messages as $message){
                    if (!array_key_exists("$message->object", $_SESSION)){
                        $_SESSION["$message->object"] = "";
                    }
                    echo displayMessage($message, $_SESSION["$message->object"]);
                }
                ?>

                    <form method="POST" action="../process/tr_signIn.php">

                        <div class="form-group">
                            <label>Adresse mail</label>
                            <input type="mail" name="email" class="form-control">  
                        </div> 

                        <div class="form-group">
                            <label>Mot de passe</label>
                            <input type="password" name="password" class="form-control">   
                        </div>

                        <input type="submit" class="btn btn-lg form-control">

                    </form>
                </div>
            </div>
        </div>
    </body>
</html>