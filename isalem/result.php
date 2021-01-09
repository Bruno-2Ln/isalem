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
        <link rel="stylesheet" href="../css/main.css">
        <link rel="stylesheet" href="../css/result.css">
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

        echo displayHeader('Résultat');

        if (!empty($_SESSION['result'])){
            $messages = getIsalemDatabaseHandler()->getAllMessages();
            foreach ($messages as $message){
                if (!array_key_exists("$message->object", $_SESSION)){
                    $_SESSION["$message->object"] = "";
                }
                echo displayMessage($message, $_SESSION["$message->object"]);
                $_SESSION["$message->object"] = "";
            }
        ?>  

            <div class="container d-lg-flex mt-5 div-hover" id="container_card_graph">
                <div id="result_card" class="container text-justify mr-5">

                <?php
                $resultTest = getIsalemDatabaseHandler()->getResultOfTest($_SESSION['result']);
                echo displayResult($resultTest);
                $strongs = getIsalemDatabaseHandler()->getPointOfCategory($_SESSION['result'], 'strong');
                $weaks = getIsalemDatabaseHandler()->getPointOfCategory($_SESSION['result'], 'weak'); 
                ?>

                    <h4>Vos points forts</h4>
                    <p>Vous êtes particulièrement doué pour :</p>
                    <ul>

                    <?php
                    foreach ($strongs as $strong){
                        echo displayPersonality($strong);
                    }
                    ?>

                    </ul>

                    <h4>Vos points faibles</h4>
                    <p>Vous auriez tendance à :</p>
                    <ul>

                    <?php
                    foreach ($weaks as $weak){
                        echo displayPersonality($weak);
                    }
                }

                ?>
                  
                </div>

                <div id="result_graph" class="d-flex flex-column justify-content-center">

                    <canvas id="graphic" width="380" height="380"></canvas>

                    <div class="d-flex justify-content-end coordinates">
                    <p class="m-1 coordinate font-italic">x : 
                        <span id="x">

                        <?php
                        echo($_SESSION["x"]);
                        ?>

                        </span>

                    </p>
                    <p class="m-1 coordinate font-italic">y :
                        <span id="y">

                        <?php
                        echo($_SESSION["y"]);
                        ?>

                        </span>
                    </p>
                    </div>

                </div>
            </div>

            <div class="container mt-5 div-hover" id="container-form-contact">
                <form method="POST" action="../process/tr_sendMailResult.php">
                    <legend class="text-center"><b>Recevez ou envoyez vos résultats par email</b></legend>
                <p class="info text-center font-italic">Les données inscrites n'ont pour seul but l'envoi du résultat et ne sont en aucun cas conservées</p> 

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
                        <input type="email" name="email" class="form-control" value="<?php echo $_SESSION['email']?>" pattern="[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+.[a-zA-Z.]{2,15}" required>
                    </div>

                    <div class="form-group">
                        <label>Adresse email en copie (facultatif)</label>
                        <input type="email" name="email_copy" class="form-control" pattern="[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+.[a-zA-Z.]{2,15}">
                    </div>

                    <div class="d-flex justify-content-center">
                        <input type="submit" value="Envoyer" class="m-1 btn btn-lg form-control w-75">
                    </div>

                </form>
            </div>

            <script src="../js/result.js"></script>

            <?php
            echo displayFooter();
            $_SESSION['firstname'] = '';
            $_SESSION['lastname'] = '';
            $_SESSION['email'] = '';
            ?>
        </div>
    </body>
</html>
