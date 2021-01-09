<?php
session_start();

require_once('../db/db.php');
require_once('../db/connect_db.php');
var_dump($_POST);
if(isset($_POST['age']) && count($_POST) === 50){ //le tableau doit contenir 50 associations clé/valeur (48 réponses avec notations 
  //plus la clé/valeur à la question si l'utilisateur à + ou - de 18 ans et l'autorisation d'être intégrer dans les statistiques) 
  //pour effectuer le traitement des réponses.
  $x = 0; // va renfermer le point d'ordonnée du graphique final et permettre d'obtenir le profil de l'apprenant. 
  $y = 0; // va renfermer le point d'abscisse de ce même graphique.
  $result = ""; // va renfermer la position positive ou négative de $x et $y sous forme d'une string qui permettra ensuite de récupérer
  //le profil de l'utilisateur dans la base de données.

  //Initialisation de 4 variables nécessaires au calcul final, chacune d'elle va renfermer
  //un tableau devant accueillir 12 valeurs obligatoirement.
  $Ab = [];
  $Ac = [];
  $I = [];
  $R = [];

  //le foreach permet d'accéder à chaque clé valeur de $_POST..
  foreach($_POST as $key => $value){
    
    if ($value === 'under18' || $value === 'over18' || $value === 'agree' || $value === 'desagree'){ 
      // quand la valeur under18 ou over18 est rencontrée on passe à la suivante sans rien faire de plus
      //.. traitement des valeurs numériques, elles doivent être un chiffre supérieur à 0 et inférieur à 5.
    } else if(is_numeric($value) && $value > 0 && $value < 5){

      if ($key[1] === "b"){
        $Ab[] = $value;

      } else if ($key[1] === "i"){
        $I[] = $value;

      } else if ($key[1] === "c"){
        $Ac[] = $value;

      } else if ($key[1] === "r"){
        $R[] = $value;
      }

    } else { 
      header('Location: ../isalem/questionnaire.php');
    } 
  }

  //Vérification de chaque tableau, ils doivent contenir obligatoirement 12 valeurs
  if(count($I) === 12 && count($Ab) === 12 && count($R) === 12 && count($Ac) === 12){
    //Vérification également que la somme de toutes les réponses est bien égale à 120
    if(array_sum($I)+array_sum($Ab)+array_sum($Ac)+array_sum($R) == 120){
    
      if ($_POST["age"] === "over18"){ //si plus de 18 ans ce calcul est fait à partir de la somme de chaque tableau
        $x = array_sum($I) - array_sum($Ab) - 8;
        $y = array_sum($Ac) - array_sum($R) - 5;
      } else if ($_POST["age"] === "under18"){ //..si moins de 18 ans c'est ce calcul qui est fait
        $x = array_sum($I) - array_sum($Ab) - 2; 
        $y = array_sum($Ac) - array_sum($R) - 2;
      }

    } else {
      header('Location: ../isalem/questionnaire.php');
    }
  } else { 
    header('Location: ../isalem/questionnaire.php');
  }

  //Vérification de la nature positive ou négative de $x...
  if ($x >= 0){ 
      $result = "x";
  } else if ($x < 0) {
      $result = "-x";
  } 
  //...ainsi que celle de $y...
  if ($y >= 0){
      $result =$result."y";
  } else if ($y < 0){
      $result = $result."-y";
  }

  //...à présent la variable $result renfermant l'un des quatre 
  //indicateurs de profil existant du test ISALEM
  //à savoir -xy, xy, x-y ou -x-y

  //Les 3 variables $result, $x, $y sont préparées pour être 
  //interprétées dans la page de résultat.
  $_SESSION['result'] = $result;
  $_SESSION['x'] = $x;
  $_SESSION['y'] = $y;

  //Chaque fois qu'un test est réalisé, récupération de la date du jour si acceptation par l'utilisateur..
  if ($_POST['statistique'] === 'agree'){
    $date = date("Y-m-d");
    //puis appel de la méthode createDate($date) pour rajouter une ligne dans la table date_of_test
    getIsalemDatabaseHandler()->createDate($date);
  }
} else {
  header('Location: ../isalem/index.php');
}
header('Location: ../isalem/result.php');
?>
