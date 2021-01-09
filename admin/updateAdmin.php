<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/admins.css">
        <link rel="stylesheet" href="../css/main.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
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
            echo displayHeader('Modifier Admin');

            if(isset($_GET['u'])){
                $id = $_GET['u'];
                $_SESSION['editId'] = $id;
                $adminEdit = getIsalemDatabaseHandler()->getAdminByid($id);

                $messages = getIsalemDatabaseHandler()->getAllMessages();
                foreach ($messages as $message){
                    echo displayMessage($message, $_SESSION["$message->object"]);
                    $_SESSION["$message->object"] = '';
                }
            ?>

            <div class="container-divs-update-and-create">

                <div class="w-100 h-25 mt-5 d-flex justify-content-center">
                    <a href="admins.php" class="mb-3 w-25 d-flex justify-content-center">
                        <button class="btn btn-fa">
                            <i class="fas fa-backward"></i>
                        </button>
                    </a>
                </div>

                <div class="container div-hover div-form mt-2 pb-5">
                    <form method="POST" action="../process/tr_updateAdmin.php">

                        <div class="form-group">
                            <label>Prénom</label>
                            <input type="text" class="form-control" name="firstname" value="<?php echo $adminEdit->firstname ?>" required>
                        </div>
                
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" class="form-control" name="lastname" value="<?php echo $adminEdit->lastname ?>" required>
                        </div>
                    
                        <div class="form-group">
                            <label>Rôle</label>
                            <select class="form-control" name="roles_admin" id="" required>
                                <option value=""></option>
                                <option value="1">SUPER_ADMIN</option>
                                <option value="2">ADMIN</option>
                            </select>  
                        </div>
                    
                        <div class="form-group">
                            <label>Adresse email</label>
                                <input type="mail" class="form-control" name="email" value="<?php echo $adminEdit->emailAddress ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Email de contact</label>
                                <input type="checkbox" id="yes" name="contact_mail">
                        </div>

                        <div class="form-group">
                            <label>Mot de passe actuel</label>
                            <input type="password" class="form-control" name="last_password" required>
                        </div>

                        <div class="form-group">
                            <label>Nouveau mot de passe</label>
                            <input type="password" class="form-control" name="new_password" required minlength="8">
                        </div>

                        <div class="form-group">
                            <label>Répéter Mot de passe</label>
                            <input type="password" class="form-control" name="repeat_password" required>
                        </div>
                    
                        <input type="submit" class="btn btn-lg form-control mt-4" value="Enregistrer">

                    </form>
                </div>

            </div>

            <?php
            echo displayFooter($admin);
            } else {
                header('Location: ../admin/admins.php');
            }
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