document.addEventListener('DOMContentLoaded', () => {

    //récupération de mes éléments de ma classe deleteInstruction dans un tableau
    let deleteInstruction = document.getElementsByClassName("deleteInstruction")

    for (let i=0; i<= deleteInstruction.length;i++){
        //pour chaque incrémentation j'applique une fonction deleteConfirm au click
        deleteInstruction[i].addEventListener("click", deleteInstructionConfirm)
    }

    function deleteInstructionConfirm(e){ 
        //la variable va contenir la valeur de l'attribut value de l'évènement qui se trouve être l'id de l'instruction ciblée.
        let id = parseInt(e.target.getAttribute("value")) 

        if (window.confirm("Souhaitez-vous effacer cette instruction ?")){ 
            //si confirmation de l'admin alors redirection vers le traitement de la suppression d'une instruction.
            window.location = "../process/tr_deleteInstruction.php?d=" + id
        }
    }

})