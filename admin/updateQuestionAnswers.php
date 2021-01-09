<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/questionnaire.css">
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
                echo displayHeader('Modifier Question et Réponses');

                if(isset($_GET['u'])){
                    $idQuestion = $_GET['u'];
                    $_SESSION['idQuestion'] = $idQuestion;
                    $questionEdit = getIsalemDatabaseHandler()->getQuestion($idQuestion);
                    $answersEdit = getIsalemDatabaseHandler()->getAnswersForOneQuestion($idQuestion);

                    $messages = getIsalemDatabaseHandler()->getAllMessages();
                    foreach ($messages as $message){
                        echo displayMessage($message, $_SESSION["$message->object"]);
                        $_SESSION["$message->object"] = '';
                    }
                ?>

            <div class="container-divs">

                <div class="w-100 h-25 mt-4 d-flex justify-content-center">
                    <a href="questionnaire.php" class="mb-3 w-25 d-flex justify-content-center">
                        <button class="btn  btn-fa">
                            <i class="fas fa-backward"></i>
                        </button>
                    </a>
                </div>

                <div class="container div-hover div-form mt-2 pb-5">  
                <label>Question :</label>

                    <form method="POST" action="../process/tr_updateQuestion.php" class="form-update-question-answers d-flex justify-content-between">
                
                        <div class="form-group form-container w-100 mr-1 mr-md-2 mr-lg-5">
                            <textarea name="content" class="form-control textarea-content-small" id="" cols="30" rows="2"><?php echo $questionEdit->content?></textarea>
                        </div>

                        <div class="d-flex justify-content-center align-items-center pb-1">
                            <button type="submit" class="btn btn-submit d-flex justify-content-center align-items-center" >
                                <i class='fas fa-save'></i>
                            </button>
                        </div>
                    </form>

                <label>Réponses :</label>

                <?php   
                foreach ($answersEdit as $answer){
                $idAnswer = $answer->id;
                ?>   

                    <form method="POST" action="../process/tr_updateAnswer.php?u=<?php echo $idAnswer ?>" class="form-update-question-answers d-flex justify-content-between">
            
                        <div class="form-group w-100 form-container w-100 mr-1 mr-md-2 mr-lg-5">
                            <textarea name="content" class="form-control textarea-content-small" id="" cols="30" rows="2"><?php echo $answer->content?></textarea>   
                        </div>

                        <div class="d-flex justify-content-center align-items-center">
                            <button type="submit" class="btn h-50 btn-submit d-flex justify-content-center align-items-center" >
                                <i class='fas fa-save'></i>
                            </button>
                        </div>

                    </form>

                <?php            
                }
                ?>

                </div>  

            </div>

                <?php
                echo displayFooter($admin);
                } else {
                    header('Location: ../admin/questionnaire.php');
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