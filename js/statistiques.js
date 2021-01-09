document.addEventListener('DOMContentLoaded', () => {
    
    let labels = document.getElementsByClassName("labels");
    let data_numbers = document.getElementsByClassName("data_numbers");
    let labelGraph = document.getElementById("labelGraph");
    let labelsArray = [];
    let data_numbersArray = [];
    //une boucle pour récupérer le contenu des éléments de la classe labels et ajouter ces valeurs
    //dans un tableau
    for (let i=0;i<labels.length;i++){
        label = labels[i].innerHTML;
        labelsArray.push(label);
    }

    //même procédé pour le contenu des éléments de data_numbers
    for (let i=0;i<data_numbers.length;i++){
        data_number = data_numbers[i].innerHTML;
        data_numbersArray.push(data_number);
    }
    //maintenant que ces deux tableaux sont remplis, il y a donc les labels et leurs données
    //qui sont utilisables à la création du graphique et ainsi obtenir les données souhaitées
    //systématiquement

    let ctx = document.getElementById('canvas').getContext('2d');

    let chart = new Chart(ctx, {

    type: 'bar',

    data: {

        labels: labelsArray,
        datasets: [{
            label: labelGraph.textContent,
            backgroundColor: 'rgb(244, 129, 32)',
            borderColor: 'rgb(244, 129, 32)',
            data: data_numbersArray
        }]
    },

    options: {}
    });
                          
})
    