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
        <link rel="stylesheet" href="../css/index.css">
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

        $messages = getIsalemDatabaseHandler()->getAllMessages();
        foreach ($messages as $message){
            if (!array_key_exists("$message->object", $_SESSION)){
                $_SESSION["$message->object"] = "";
            }
            echo displayMessage($message, $_SESSION["$message->object"]);
            $_SESSION["$message->object"] = "";
        }
        ?>

        <header>

            <div id="carouselAccueil" class="carousel slide w-75 d-flex" id="carousel" data-ride="carousel">
                <div class="carousel-inner h-75 m-75" id="carousel-inner">
                    <div class="carousel-item active h-100" id="slide-1">
                        <div class="row w-100 h-100 col-10 offset-1 justify-content-center">
                            <div class="row align-self-center">
                                <div class="col">
                                    <p class="title-header text-center">Isalem.. Mais pourquoi faire ?</p>
                                    <div class="row justify-content-center">
                                    <a href="#link-en-savoir-plus"> 
                                        <button class="btn btn-lg btn-go">En savoir plus</button>
                                    </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item h-100" id="slide-2">
                        <div class="row w-100 h-100 col-10 offset-1 justify-content-center">
                            <div class="row align-self-center">
                                <div class="col">
                                    <p class="title-header text-center">Découvrez votre profil d'apprentissage</p>
                                    <div class="row justify-content-center">
                                    <a href="questionnaire.php" class>
                                        <button class="btn btn-lg btn-go" id="">Commencer</button>
                                    </a>
                                    </div>
                                </div> 
                            </div>    
                        </div>
                    </div>
                </div>

                <a href="#carouselAccueil" class="carousel-control-prev" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Précédent</span>
                </a>

                <a href="#carouselAccueil" class="carousel-control-next" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Suivant</span>
                </a>

            </div>

        </header>

        <div class="container w-75 mb-5 pb-3 text-justify" id="link-en-savoir-plus">

        <?php
        $test = getIsalemDatabaseHandler()->getTestByTitle("ISALEM-97");

        echo displayTest($test, "description");
        ?>

        </div>

        <div class="container w-50 mt-3 pb-5" id="container-btn-lg">
            <a href="questionnaire.php">
                <button class="btn btn-lg btn-block">Remplir le questionnaire</button>
            </a>
        </div>

        <?php
            echo displayFooter();
        ?>
    
        <script>
        $('.carousel').carousel({
            pause: "null",
            interval: 7000
        })</script>
    
        </div>
    </body>
</html>