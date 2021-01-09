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
                echo displayHeader('Modifier Instruction');
                
                if(isset($_GET['u'])){
                    $idInstruction = $_GET['u'];
                    $_SESSION['idInstruction'] = $idInstruction;
                    $instructionEdit = getIsalemDatabaseHandler()->getInstruction($idInstruction);
            
                    $messages = getIsalemDatabaseHandler()->getAllMessages();
                    foreach ($messages as $message){
                        echo displayMessage($message, $_SESSION["$message->object"]);
                        $_SESSION["$message->object"] = '';
                    }
                ?>

            <div class="container-divs">

                <div class="w-100 h-25 mt-5 d-flex justify-content-center">
                    <a href="instructions.php" class="mb-3 w-25 d-flex justify-content-center">
                    <button class="btn btn-fa">
                        <i class="fas fa-backward"></i>
                    </button>
                    </a>
                </div>

                <div class="container div-hover div-form mt-2 pb-5">
                    <form method="POST" action="../process/tr_updateInstruction.php">

                        <div class="form-group">
                            <label>Ordre d'apparition</label>
                            <input name="orderOfAppearance" type="number" class="form-control" value="<?php echo $instructionEdit->orderOfAppearance?>">
                        </div>

                        <div class="form-group">
                            <label>Contenu</label>
                            <textarea name="content" class="form-control textarea-content" id="" cols="30" rows="10"><?php echo $instructionEdit->content?></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            <input type="submit" class="btn btn-lg form-control" value="Enregistrer">
                        </div>

                    </form> 
                </div>

            </div>

                <?php
                echo displayFooter($admin);
                } else {
                    header('Location: instructions.php');
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