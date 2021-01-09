<?php
session_start();
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/questionnaire.css">
        <link rel="stylesheet" href="../css/main.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>  
        <script src="../js/questionnaire.js"></script>
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
        
        echo displayHeader("Questionnaire");
        ?>

        <div class="container w-50 mt-5 mb-5" id="instructions">
            <h3 class="mb-5 text-center">
                Quelques consignes avant de vous lancer
            </h3>

            <ul class="list-unstyled">

            <?php
            $instructions = getIsalemDatabaseHandler()->getAllInstructions();
            foreach ($instructions as $instruction){
                echo displayInstruction($instruction);
            }
            ?>

            </ul>
        </div>

        <div class="container" id="container-test">

            <h3 class="text-center">
                Maintenant, c'est à vous
            </h3>

            <form method="POST" action="../process/tr_questionnaire.php">

                <div class="container mb-3 text-center">
                    
                    <label class="mt-4" for="age">Avez-vous plus de 18 ans ?</label>
                    <div>
                        <input class="mr-1 ml-2 input-radio" type="radio" name="age" value="over18" required autocomplete="off">oui
                        <input  class="mr-1 ml-2 input-radio" type="radio" name="age" value="under18" required autocomplete="off">non 
                    </div>

                </div>

                <div class="container">

                <?php

                for ($i = 1; $i <= 12; $i ++){
                ?> 

                    <div class="question-answers"> 
                        
                    <?php
                    $question = getIsalemDatabaseHandler()->getQuestion($i);
                    echo displayQuestion($question);
                    $answers = getIsalemDatabaseHandler()->getAnswersForOneQuestion($i);
                    foreach ($answers as $answer){
                        echo displayAnswer($answer);
                    }
                    ?> 

                    </div> 

                <?php
                }
                ?>

                </div>

                <div class="container text-justify font-italic" id="statRequest">
                    <label for="statistique" >La validation de votre questionnaire nous permet d'établir des statistiques qui ont pour unique but de connaître le
                        nombre de questionnaires réalisés sur une période précise (jour, mois, année). La seule donnée conservée est la date à laquelle la validation a été faîte, cette donnée est strictement
                        anonyme. 
                    </label>
                    <div>
                        <input class="mr-1 ml-2 input-radio" type="radio" name="statistique" value="agree" checked> J'accepte
                        <input class="mr-1 ml-2 input-radio" type="radio" name="statistique" value="desagree"> Je refuse
                    </div>
                </div> 

                <div class="container w-50 mt-4">
                    <button type="submit" class="btn btn-lg btn-block text-white" id="btn-validation">Envoyer</button>
                </div>

            </form>

        </div>

        <?php
        echo displayFooter();
        ?>

        </div>
    </body>
</html>