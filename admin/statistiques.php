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
        <link rel="stylesheet" href="../css/statistiques.css">
        <link rel="stylesheet" href="../css/main.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        <script src="../js/statistiques.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
        <title>ISALEM</title>
    </head>
    <body>
        <div class="main">

        <?php
        require_once('../db/db.php');
        require_once('../db/connect_db.php');
        require_once('../function_test.php');
        require_once('../display_function.php');
        require_once('../function.php');

        if (isset($_SESSION['admin_id'])){
            $admin = getIsalemDatabaseHandler()->getAdminByid($_SESSION['admin_id']);

            if (isset($admin)){
                echo displayNavAdmin($admin);
                echo displayHeader('Statistiques');

                $format_db = date('Y-m-d');
                $count = getIsalemDatabaseHandler()->getDateData($format_db);
            ?> 

            <div class="container-divs">

                <div class="container text-center div-hover div-form" id="div-1">
                    <p class="d-flex flex-column">
                    <span class="font-italic">Nombre total de tests réalisés aujourd'hui : </span>
                    <span class="display-4"><?php echo $count; ?></span> 
                    </p> 
                </div>

                <div class="container div-hover div-form mt-2 pb-5 d-flex flex-column-reverse">
                
                    <form method="POST" action="" id="form" class="w-100 mt-3">
                        <div class="w-75 form-group container container-form-group d-flex justify-content-around" id="div-labels">
                
                            <label class="form-label" id="label-month">Mois 
                                <select class="form-control" name="month">
                                    <option value="" class="months-option"></option>
                                    <option value="01" class="months-option">Janvier</option>
                                    <option value="02" class="months-option">Février</option>
                                    <option value="03" class="months-option">Mars</option>
                                    <option value="04" class="months-option">Avril</option>
                                    <option value="05" class="months-option">Mai</option>
                                    <option value="06" class="months-option">Juin</option>
                                    <option value="07" class="months-option">Juillet</option>
                                    <option value="08" class="months-option">Août</option>
                                    <option value="09" class="months-option">Septembre</option>
                                    <option value="10" class="months-option">Octobre</option>
                                    <option value="11" class="months-option">Novembre</option>
                                    <option value="12" class="months-option">Décembre</option>
                                </select>
                            </label>

                            <label class="form-label" id="label-year">Année <input type="number" class="form-control" name="year" value="<?php echo date("Y");?>" required autocomplete="off"></label>

                            <input type="submit" class="btn btn-fa mt-2 mt-4" id="createGraph" value="Go">

                        </div>
                    </form>

                <?php
                if (isset($_POST['month']) && isset($_POST['year'])){

                    if (!empty($_POST['year'])){

                        if (!empty($_POST['month'])){
                            //si un mois est renseigné alors création d'une date au format YYYY-MM-01
                            $date = $_POST['year']."-".$_POST['month']."-01";
                            //afin d'obtenir la timestamp de la date souhaitée
                            $timestamp = strtotime($date);
                            //d'en récupérer le titre (avec l'abbréviation du mois)
                            //et afficher dans le carrousel qui sera également
                            //repris comme titre du graphique avec Chart.js
                            $labelGraph = date("M-Y", $timestamp);
                            //récupération du nombre de jours maximum du mois de l'année demandé
                            $maxDays = date("t", mktime(0, 0, 0, $_POST['month'], 1, $_POST['year']));
                            //puis récupération du total des entrées jour par jour dans le tableau $datas
                            //prenant comme paramètres la date au format YYYY-MM-01 et le maximum de jours dans MM
                            $datas = graphDataMonth($date, $maxDays);
                            
                        } else {
                            //sinon récupération de l'année pour une date au format YYYY-01-01
                            $date = $_POST['year']."-01-01";
                            //et récupération du total des entrées mois par mois dans un tableau             
                            $datas = graphDataYear($date);
                            //ici le titre du carrousel et du graphique sera l'année renseignée
                            $labelGraph = $_POST['year'];
                        }
                    ?>

                    <div class="container mt-5 pb-5">


                        <div class="container div-hover div-form mt-2 div-slide">
                            <div id="statistiques" class="container carousel slide d-flex" id="carousel" data-ride="carousel">
                                <div class="carousel-inner h-75 m-75" id="carousel-inner">
                                    <div class="carousel-item active h-100" id="slide-1">
                                        <p class='text-center font-italic'> Statistiques : <span id="labelGraph"><?php echo $labelGraph;?></span>
</p> 
                                    </div>

                                <?php    
                                foreach($datas as $data){
                                    echo displayDataDate($data);    
                                }
                                ?>

                                </div>

                                <a href="#statistiques" class="carousel-control-prev" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Précédent</span>
                                </a>

                                <a href="#statistiques" class="carousel-control-next" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Suivant</span>
                                </a>
                            </div>
                        </div>

                    </div>

                    <div class="container w-100">
                        <canvas id="canvas"></canvas>
                    </div>
            

                    <?php
                    } else {
                        header('Location: statistiques.php');
                    }
                } else {
                    header('Location: ../isalem/index.php');
                }
                ?>
                </div>
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