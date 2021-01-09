<?php
session_start();
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/contact.css">
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
        }

        if (isset($admin)){
            echo displayNavAdmin($admin);
        } else { 
            echo displaynav();
        }

        echo displayHeader('Nous contacter');

        $messages = getIsalemDatabaseHandler()->getAllMessages();
        foreach ($messages as $message){

            if (!array_key_exists("$message->object", $_SESSION)){
                $_SESSION["$message->object"] = "";
            }
            echo displayMessage($message, $_SESSION["$message->object"]);
            $_SESSION["$message->object"] = "";
            }
            ?>

            <div class="container mt-5 div-hover div-form">
                <form method="POST" action="../process/tr_sendMailContact.php">

                    <div class="form-group"> 
                        <label>Prénom <span class="asterix">*</span></label>
                        <input type="text" name="firstname" class="form-control" value="<?php echo $_SESSION['firstname']?>" required pattern="^[A-Za-z '-]+$">
                    </div>

                    <div class="form-group">
                        <label>Nom <span class="asterix">*</span></label>
                        <input type="text" name="lastname" class="form-control" value="<?php echo $_SESSION['lastname']?>" required pattern="^[A-Za-z '-]+$">
                    </div>

                    <div class="form-group">
                        <label>Adresse email <span class="asterix">*</span></label>
                        <input type="email" name="email" class="form-control"  value="<?php echo $_SESSION['email']?>" required pattern="[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+.[a-zA-Z.]{2,15}">
                    </div>

                    <div class="form-group">
                        <label>Message <span class="asterix">*</span></label>
                        <textarea name="message" class="form-control" id="" cols="30" rows="10" required><?php echo $_SESSION['message']?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Captcha <span class="asterix">*</span></label>
                        <div>
                            <div id="div-input-captcha" class="d-flex">
                                <input type="text" name="captcha" id="input-captcha" class="form-control" required autocomplete="off">
                                <div class="p-1">
                                    <img src="../process/captcha.php" onclick="this.src='../process/captcha.php?' + Math.random();" alt="captcha" style="cursor:pointer;"> 
                                </div>
                            </div>  
                        </div>
                    </div>

                    <input type="submit" class="btn form-control">
                    <p class="font-italic text-center">Les données inscrites n'ont pour seul but de vous répondre et ne seront en aucun cas conservées</p>
                </form>
            </div>
        
        <?php
        echo displayFooter();
        $_SESSION['firstname'] = '';
        $_SESSION['lastname'] = '';
        $_SESSION['email'] = '';
        $_SESSION['message'] = '';
        ?>

        </div>
    </body>
</html>