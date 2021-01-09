document.addEventListener('DOMContentLoaded', () => {

//récupération des éléments de la classe deleteAdmin
let deleteAdmin = document.getElementsByClassName("deleteAdmin") 

//Pour chaque incrémentation j'applique une fonction deleteConfirm au click
for (let i=0; i<= deleteAdmin.length;i++){

    deleteAdmin[i].addEventListener("click", deleteConfirm)

}

function deleteConfirm(e){ 
    

    //récupération du parent de son parent et des noeuds enfant de ce dernier afin d'obtenir toutes les informations necessaires
    let targetAdmin = e.target.parentNode.parentNode
    
    //la suivante récupère le prénom et le nom
    let name = targetAdmin.childNodes[3].textContent 
    //et l'adresse email  
    let email_address = targetAdmin.childNodes[5].textContent

    if (window.confirm("Souhaitez-vous effacer " + name + " de la liste d'admins ?")){ 
        // si confirmation du SUPER_ADMIN alors redirection vers le traitement de la suppresion admin.
        window.location = "../process/tr_deleteAdmin.php?d=" + email_address
    }
}

})