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
                echo displayHeader('Mail type');

                $messages = getIsalemDatabaseHandler()->getAllMessages();
                foreach ($messages as $message){
                    echo displayMessage($message, $_SESSION["$message->object"]);
                    $_SESSION["$message->object"] = '';
                }
        
                if(isset($_GET['u'])){
                    $idMail = $_GET['u'];
                    
                    $_SESSION['idMail'] = $idMail;
                    $mail = getIsalemDatabaseHandler()->getMailById($idMail);
                ?>

            <div class="container-divs-mail">

                <div class="mt-5 d-flex justify-content-center container-btn">
                    <div class="w-25 h-25 d-flex justify-content-center">
                        <a href="mails.php" class="mb-3 w-25 d-flex justify-content-center">
                            <button class="btn btn-fa">
                                <i class="fas fa-backward"></i>
                            </button>
                        </a>
                    </div>

                    <div class="w-25 h-25 d-flex justify-content-center">
                        <a href="updateMail.php?u=<?php echo $mail->id?>" class="mb-3 w-100 d-flex text-decoration-none justify-content-center">
                            <button class="btn">
                                <span>Modifier</span>
                            </button>
                        </a>
                    </div>
                </div>

                <div class="container div-form text-left">           
                    <p class="w-50 h-25 mt-2">Objet :</p>
                    <div class="container div-hover div-form mt-2 pb-5">
                        <div>
                            <?php echo html_entity_decode($mail->object, ENT_HTML5)?> 
                            <span class="font-italic text-muted">Prénom Nom</span>
                        </div>
                    </div>
                </div>

                <div class="container div-form text-left container-btn">
                    <div class="mt-4 d-flex">   

                        <?php
                        if($mail->id > 1){
                            $previous = ($mail->id)-1;
                        ?>

                        <div class="container d-flex justify-content-start">
                            <a href="mail.php?u=<?php echo $previous?>" class="align-self-start mb-3 text-decoration-none">
                                <button class="btn btn-fa">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                            </a>
                        </div>

                    <?php
                    }

                    if($mail->id < 4){
                        $next = ($mail->id)+1;
                    ?>

                        <div class="container d-flex justify-content-end">
                            <a href="mail.php?u=<?php echo $next?>" class="align-self-end mb-3 text-decoration-none">
                                <button class="btn btn-fa">
                                <i class="fas fa-chevron-right"></i>
                                </button>
                            </a>
                        </div>
                
                    <?php
                    }
                    ?>    

                    </div>   
                </div>

                <div class="container div-form text-left">
                    <p class="w-50 h-25 mt-2">Corps :</p>
                </div>

                <div class="container div-hover div-form mt-2 pb-5">
                    <div>
                        <?php echo html_entity_decode($mail->body, ENT_HTML5)?>
                    </div>
                </div>

            </div>

                    <?php
                    echo displayFooter($admin);

                } else {
                    header('Location: ../admin/mails.php');
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