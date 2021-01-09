document.addEventListener('DOMContentLoaded', () => {

    //récupération de la collection de toutes les divs enfermant les questions et leurs réponses
    let question_answers = document.getElementsByClassName("question-answers")

    //pour chaque div enfermant question + réponses...
    for (let i=0; i< question_answers.length; i++){
        //récupération du lien de la div
        let a_eraser = question_answers[i].getElementsByClassName("a-eraser")
        //et de ses champs input
        let input = question_answers[i].getElementsByClassName("input_answer")
        
        //sur le lien, pour chaque click, une fonction est initialisée afin d'effacer la valeur la plus grande des champs input...
        a_eraser[0].addEventListener('click',eraser)

        function eraser(){
            //..pour ce faire, initialisation d'une variable qui sera un tableau récupérant
            //sous forme de tableau chaque valeur des inputs.
            let values = [input[0].value, input[1].value, input[2].value, input[3].value]
            //puis classement de ces valeurs par ordre décroissant..
            values = values.sort().reverse()
            //récupération de la valeurs la plus grande qui est donc la première du tableau
            let maxValue = values[0]

            //enfin une bloucle sur les valeurs des champs input actuelles va permettre de trouver la valeur correspondante à maxValue
            //et effacer la valeur équivalente du champ input.
            for (let i=0; i< input.length; i++){  
                if (maxValue == input[i].value){
                    input[i].value = ""
                }
            }
        }

        //fonction test auto-invoquée uniquement utilisée pour générer aléatoirement les réponses au questionnaire et effectuer rapidement des tests
        ;(function testRandom(){
            //récupération de la collection d'objet de class input-radio (les deux réponses possibles à la question
            //avez-vous plus de 18 ans ?)
            let radio = document.getElementsByClassName("input-radio")

            //sélection aléatoire entre 0 et 1
            radio_checked = Math.floor(Math.random() * 2)

            //la sélection précédente devient l'index de la collection radio et prend l'attribut checked déterminant la réponse
            //à la question posée
            radio[radio_checked].setAttribute("checked","")
     
            //ici est déterminé dans un tableau les réponses possibles aux inputs
            let results = [1,2,3,4]
            
            //initialisation d'une boucle sur la collection input afin de donner une valeur à chacun
            for (let i=0; i< input.length; i++){
            //on détermine indexTab, un nombre aléatoire entre 0 et, comme borne maximale exclue, la taille du tableau results ([0, result.lenght[) 
            let indexTab = Math.floor(Math.random() * results.length)
            //et on donne sa valeur à input[i]..
            input[i].value = results[indexTab]
            //..on supprime la valeur trouvée du tableau de résultats possibles afin qu'elle ne se répète pas
            results.splice(indexTab,1)        
            } 
        })()
  
        for (let i=0; i< input.length; i++){ 
       
            input[i].addEventListener('change', noIdenticalValue);

            function noIdenticalValue(){
                for (let j=0; j< input.length; j++){
                    
                    if (input[j].value == input[i].value && input[i] != input[j]){
                        input[j].value = ""
                    }
                }
            }
            //Pour chaque input, un évènement est associé afin de permettre au click d'afficher les valeurs 1, 2, 3 ou 4
            //en fonction de celles déjà présentes dans les champs inputs associé à la même question
            input[i].addEventListener('click',ranking)

            function ranking(e){
                //récupération des valeurs des inputs dans un tableau
                let arrayOfValues = [input[0].value, input[1].value, input[2].value, input[3].value]
                        
                for (let i=0; i< arrayOfValues.length; i++){
                    //si la valeur du champ du click est vide alors..    
                    if (e.target.value == ""){
                        //vérification si la valeur 1 existe déjà, sinon la 2, la 3. A chaque fois si la valeur 
                        //n'existe pas encore, le champs input ciblé prend cette valeur.
                        //Et si 1, 2 et 3 existent alors il prend la valeur de 4.
                        if (arrayOfValues[0] != "1" && arrayOfValues[1] != "1" && arrayOfValues[2] != "1" && arrayOfValues[3] != "1"){
                            e.target.value = 1

                        } else if (arrayOfValues[0] != "2" && arrayOfValues[1] != "2" && arrayOfValues[2] != "2" && arrayOfValues[3] != "2"){
                            e.target.value = 2

                        } else if (arrayOfValues[0] != "3" && arrayOfValues[1] != "3" && arrayOfValues[2] != "3" && arrayOfValues[3] != "3"){
                            e.target.value = 3

                        } else {
                            e.target.value = 4
                        }  
                    }
                }
            }

        }
    } 

})