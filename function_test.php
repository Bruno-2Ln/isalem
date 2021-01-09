<?php
require_once('db/db.php');
require_once('db/connect_db.php');

//génère des dates aléatoires dans la table date_of_test
//les 3 paramètres sont : la borne minimale et la borne maximale 
//(ces deux premiers paramètres aux formats AAAA-MM-JJ) 
//et le nombre d'entrées souhaitées dans la table
function test_createDates($date1, $date2, $x){
    //les données actuelles de date_of_test sont d'abord effacées
    getIsalemDatabaseHandler()->deleteDatas('date_of_test');
    //les bornes min et max sont transformées en timestamp
    $start = strtotime($date1);
    $end = strtotime($date2);
    //une boucle for prenant comme fin le 3ème paramètre de la fonction
    //est initialisée
    for ($i = 0; $i < $x; $i++){
        //pour chaque tour une timestamp aléatoire est générée entre les
        //deux bornes
        $timestamp = mt_rand($start, $end);
        //puis celle-ci est traduite dans un format AAAA-MM-JJ
        $date = date("Y-m-d", $timestamp);
        //pour ensuite servir de paramètre à une fonction d'insertion de date
        //dans la base de données
        getIsalemDatabaseHandler()->createDate($date);
    }
}

