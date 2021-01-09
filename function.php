<?php

//Retourne une donnée "nettoyée" c'est à dire :
//- sans espace au début et à la fin de la chaîne
//- en supprimant les antislashes
//- et convertissant les caractères spéciaux
function cleanData($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

//Retourne un tableau associatif du total des entrées existantes mois par mois
function graphDataYear(string $date){
    //tableau permettant l'abbréviation en français des mois de l'année
    $months = ["Jan","Fevr","Mar","Avr","Mai","Juin","Juil","Août","Sep","Oct","Nov","Dec"];
    //récupération de la timestamp
    $timestamp = strtotime($date);
    //récupération de l'année
    $year = date("Y", $timestamp);
    //le tableau va renfermer les 12 données et sera retouré
    $datas = [];
    //unité necessaire au bon fonctionnement du carrousel
    $number = 1;
    //boucle initiée dont chaque tour correspond à un mois dans l'année
    for ($i=1 ; $i<=12; $i++){
        //si $i est inférieur à 10 alors rajout d'un zéro pour obtenir un format de date conforme à l'arrivée
        if ($i <= 9){
            $i = '0' . $i;
        }
        $number++;
        //récupération du nombre maximum de jours dans le mois du tour de boucle en cours
        $maxDay = date("t",mktime(0,0,0,$i,1,$year));
        //deux variables servant de borne de départ et d'arrivée dans la récupération du nombre de données
        $firstDay = date('Y-'.$i.'-01', strtotime($date));
        $LastDay = date('Y-'.$i.'-'. $maxDay , strtotime($date));
        //$count va renfermer le nombre d'entrées existantes du 1er jour au dernier jour du mois
        $count = getIsalemDatabaseHandler()->getNumberOfDates($firstDay, $LastDay);
        //récupération de l'abbréviation en français du mois du tour
        $month = $months[$i-1];
        //ajout au tableau de l'objet dont les valeurs des propriétés seront utilisées pour la création du graphique
        //et du carrousel
        array_push($datas, (object)['slide'=>$number, 'label'=>$month, 'dateComplement'=>$year, 'data_number'=>$count]);
    }
    return $datas;
}

//Retourne un tableau associatif du total des entrées existantes jour par jour
//2 paramètres : la date au format YYYY-MM-JJ et le nombre de jours dans MM
function graphDataMonth(string $date, int $maxDays){
    //récupération de la timestamp
    $timestamp = strtotime($date);
    //permettant la récupération de l'année
    $year = date("Y", $timestamp);
    //et du mois
    $month = date("m", $timestamp);
    //la date réécrite au format MM/YYYY
    $dateComplement = $month.'/'.$year;
    //initialisation du tableau qui sera retournée avec les données à interprétées
    $datas = [];
    //la variable number utilisée pour numéroter les pages du carrousel Bootstrap
    //permettant leur affichage
    $number = 1;
    //boucle initiée partant du 1er jour du mois demandé au dernier jour
    for ($i=1 ; $i<=$maxDays; $i++){
        //pour un format de jour valide, rajout d'un 0 si l'index est inférieur à 10
        if ($i <= 9){
            $i = '0' . $i;
        }
        $number++;
        //remplacement du jour initial de $date par l'index du tour
        $dateData = date('Y-m-'.$i, strtotime($date));
        //récupération du nombre d'entrées de la table date_of_test identiques à $dateData
        $count = getIsalemDatabaseHandler()->getDateData($dateData);
        //la donnée est ajoutée sous forme d'objet dans le tableau à retourner
        //les propriétés de chaque objets sont nécessaires au fonctionnement du carrousel et de la création du graphique
        array_push($datas, (object)['slide'=>$number,'label'=>$i,'dateComplement'=>$dateComplement, "data_number"=>$count]);
    }
    return $datas;
}