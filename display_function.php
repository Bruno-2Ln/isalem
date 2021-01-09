<?php

//en fonction de la connection d'un admin et de son rôle le menu diffère
function displayNavAdmin($admin){ // si c'est un SUPER_ADMIN on prépare une variable $gestion contenant l'accès à la gestion de la table admin dans
                                  // le menu (donc la gestion des admins)
    if ($admin->roles_admin === 1){
        $gestion = "<a href='../admin/admins.php' class='dropdown-item a-menu-navbar'>Admins</a>";

    } else if ($admin->roles_admin <= 2) { // affichage du menu commun à tous les admins
        $gestion = ""; // $gestion sera alors vide car simple ADMIN.
    }

    return sprintf("
        <nav class='navbar navbar-expand-sm navbar-light menu'>

            <div class='container' id='menu-options'>
                <a class='navbar-brand a-menu' href='../isalem/index.php'>ISALEM</a>

                <button class='navbar-toggler' data-toggle='collapse' data-target='#navbarNav'>
                    <span class='navbar-toggler-icon'></span>
                </button>

                <div class='collapse navbar-collapse' id='navbarNav'>

                    <ul class='navbar-nav ml-auto'>
                        <li class='nav-item'><a class='nav-link a-menu a-menu-navbar' href='../isalem/index.php'>Accueil</a> </li>
                        <li class='nav-item'><a class='nav-link a-menu a-menu-navbar' href='../isalem/questionnaire.php'>Questionnaire</a> </li>
                        <li class='nav-item dropdown'><a class='nav-link dropdown-toggle a-menu a-menu-navbar' data-toggle='dropdown' href='#'>Admin</a> 
                            <div class='dropdown-menu'>
                                <a href='../admin/statistiques.php' class='dropdown-item a-menu-navbar'>Statistiques</a>
                                %s
                                <a href='../admin/questionnaire.php' class='dropdown-item a-menu-navbar'>Questionnaire</a>
                                <a href='../admin/instructions.php' class='dropdown-item a-menu-navbar'>Instructions</a>
                                <a href='../admin/updateDescription.php' class='dropdown-item a-menu-navbar'>Description</a>
                                <a href='../admin/results.php' class='dropdown-item a-menu-navbar'>Résultats</a>
                                <a href='../admin/mails.php' class='dropdown-item a-menu-navbar'>Mails types</a>
                            </div>
                        </li>
                        <li class='nav-item'><a class='nav-link a-menu a-menu-navbar' href='../process/tr_signOut.php'>Se déconnecter</a> </li>
                    </ul>
                </div>
            </div>
        </nav>", $gestion
    );
}
    

//affichage du menu visiteur
function displaynav(){

    return sprintf("
        <nav class='navbar navbar-expand-sm navbar-light menu'>
            <div class='container'>
                <a class='navbar-brand a-menu' href='index.php'>ISALEM</a>

                <button class='navbar-toggler' data-toggle='collapse' data-target='#navbarNav'>
                    <span class='navbar-toggler-icon'></span>
                </button>

                <div class='collapse navbar-collapse' id='navbarNav'>

                    <ul class='navbar-nav ml-auto'>
                        <li class='nav-item'><a class='nav-link a-menu a-menu-navbar' href='index.php'>Accueil</a> </li>
                        <li class='nav-item'><a class='nav-link a-menu a-menu-navbar' href='questionnaire.php'>Questionnaire</a> </li>
                        <li class='nav-item'><a class='nav-link a-menu a-menu-navbar' href='contact.php'>Nous contacter</a> </li>
                    </ul>

                </div>
            </div>
        </nav>   
    ");
}

//affichage d'un administrateur enregistré dans la bdd sous forme de cellules prêtes à être intégrées dans un tableau                   
function displayAdmin($admin, $key = null){

    if ($key){
        return sprintf("
            <tr class='tr-data text-center'>
                <td data-label='#' class='text-center position td-data'>%d</td>
                <td data-label='Admin' class='text-center text-wrap td-data'><span class='firstname'>%s</span><span class='lastname'> %s</span></td>
                <td data-label='Email' class='d-block text-truncate td-data'>%s</td>
                <td data-label='Modifier' class='text-center td-data'><a class='text-decoration-none text-reset' href='updateAdmin.php?u=%d'><i class='fas fa-edit'></i></a></td>
                <td data-label='Effacer' class='text-center td-data'><i class='fas fa-trash-alt deleteAdmin delete'></i></td>
            </tr>",
            $key,
            html_entity_decode($admin->firstname, ENT_HTML5), 
            html_entity_decode($admin->lastname, ENT_HTML5), 
            html_entity_decode($admin->emailAddress, ENT_HTML5), 
            $admin->id);

    } else {
        return sprintf("
            %s",
            html_entity_decode($admin->emailAddress, ENT_HTML5)
        );
    }
}

//affichage de la données voulue d'un objet test.. en appelant la fonction $value peut devenir "title" ou "description".
function displayTest($test, $value){

    return sprintf("
        %s",
        html_entity_decode($test->$value, ENT_HTML5)
    );
}


//affichage d'une instruction. Celle-ci diffère en fonction de l'existence de $admin dans ses paramètres
function displayInstruction($instruction, $admin = null){

    if($admin){ // si $admin existe alors la donnée est renvoyée sous forme de cellules pour tableau d'affichage
        return sprintf("
            <tr class='tr-data'>
                <td data-label='#' class='text-left td-data'>%d</td>
                <td data-label='Texte' class='texte td-data'>%s</td>
                <td data-label='Modifier' class='text-center td-data'><a class='text-decoration-none text-reset' href='updateInstruction.php?u=%d'><i class='fas fa-edit'></i></a></td>
                <td data-label='Effacer' class='text-center td-data'><i value='%d' class='fas fa-trash-alt deleteInstruction delete'></i></td>
            </tr>
            ", 
            html_entity_decode($instruction->orderOfAppearance, ENT_HTML5),
            html_entity_decode($instruction->content, ENT_HTML5), 
            $instruction->id,
            $instruction->id
        );
    } else {  // sinon dans une balise <li></li> afin de l'intégrer dans une liste d'instructions 
        return sprintf("
            <li class='d-flex mb-3'><p class='mr-1'><strong>%d.</strong><div> %s</div></p></li>
            ", 
            html_entity_decode($instruction->orderOfAppearance, ENT_HTML5),
            html_entity_decode($instruction->content, ENT_HTML5)
        );
    }
}

//affichage d'un résultat gardant la même logique la fonction précédente et du paramètre $admin
function displayResult($result, $admin = null){

    if($admin){ 
        return sprintf("
            <tr class='tr-data text-center'>
                <td data-label='Titre' class='text-center td-data'>%s</td>
                <td data-label='Texte' class='text-justify td-data'>%s</td>
                <td data-label='Caractéristiques' class='text-center td-data'><a class='text-decoration-none text-reset' href='updatePersonality.php?u=%s'><i class='far fa-eye'></i></a></td>
                <td data-label='Modifier' class='text-center td-data'><a class='text-decoration-none text-reset' href='updateResult.php?u=%s'><i class='fas fa-edit'></i></a></td>
            </tr>", 
            html_entity_decode($result->title, ENT_HTML5), 
            html_entity_decode($result->content, ENT_HTML5),
            $result->category,
            $result->category
        );

    } else {
        return sprintf("
            <h3>%s</h3>
            <p>%s</p>",
            html_entity_decode($result->title, ENT_HTML5), 
            html_entity_decode($result->content, ENT_HTML5)
        );
    }
}

//suivant toujours la logique précédemment expliquée, avec deux affichage possible suivant l'existance du paramètre $admin
function displayQuestion($question, $admin = null){

    if($admin){
        return sprintf("
            <p class='question'>
                %d. %s
            </p>
            ", 
            $question->id, 
            html_entity_decode($question->content, ENT_HTML5)
        );

    } else {
        return sprintf("
            <p class='question d-flex justify-content-between'>
                <span>
                    <strong>%d.</strong><span> %s</span>
                </span>
                <a class='btn-fa btn text-light a-eraser'><i class='fas fa-eraser'></i></a>
            </p>", 
            $question->id, 
            html_entity_decode($question->content, ENT_HTML5)
        ); 
    }
}

// logique identique que pour les précédentes fonctions avec $admin en paramètre possible et donc deux affichages.
function displayAnswer($answer, $admin = null){

    if($admin){
        return sprintf("
            <p class='answer mr-1'>
                %s. %s
            </p>
            ", 
            html_entity_decode($answer->orderOfAppearance, ENT_HTML5),
            html_entity_decode($answer->content, ENT_HTML5)
        ); 

    } else {
        return sprintf("
            <div class='d-flex input-and-answer'>
                <div class='m-1 div-input'>
                    <input type='number' name='%s%d' class='text-center input_answer' required autocomplete='off' min='1' max='4'>
                </div>
                <p class='ml-2 mr-1 text-wrap'>
                    %s) %s
                </p>
            </div>", 
            $answer->category, //$answer->category combiné à $answer->numberInList permet un attribut name propre à chaque input 
            $answer->numberInList, 
            html_entity_decode($answer->orderOfAppearance, ENT_HTML5), 
            html_entity_decode($answer->content, ENT_HTML5)
        );
    }
}

//affichage d'un trait de personalité
function displayPersonality($personality, $admin = null){

    if($admin){
        return sprintf("
            <input type=\"number\" name=\"%d\" value='%s'>", 
            html_entity_decode($personality->id, ENT_HTML5),
            html_entity_decode($personality->content, ENT_HTML5)
        );

     } else {
        return sprintf("
            <li>%s</li>",
            html_entity_decode($personality->content, ENT_HTML5)
        );
    }
}

//fonction d'affichage d'un mail pour l'interface admin
function displayMail($mail){

    return sprintf("
        <tr class='tr-data text-center'>
            <td data-label='Titre' class='text-center w-50 td-title'>%s</td>
            <td data-label='Mail' class='text-center'><a class='text-decoration-none text-reset' href='mail.php?u=%d'><i class='far fa-eye'></i></td>
            <td data-label='Modifier' class='text-center'><a class='text-decoration-none text-reset' href='updateMail.php?u=%d'><i class='fas fa-edit'></i></a></td>
        </tr>", 
        html_entity_decode($mail->body, ENT_HTML5),
        $mail->id,
        $mail->id
    );
}

//permet d'afficher un message d'alerte en prenant trois paramètres : l'objet message renvoyé par la méthode getMessageByObject(),
//la valeur booléenne de la clé $_SESSION souhaitée.
function displayMessage($message, $bool){

    if ($bool){
        return sprintf("
            <div class='alert alert-%s text-center' role='alert'>
                %s
            </div>",
            $message->alert,
            $message->body
        );
    }
}

//fonction d'affichage du header.
function displayHeader($title){

    return sprintf("
        <header class='header d-flex justify-content-center align-items-center'>
            <div class='container'>
                <h1 class='container header-title'>%s</h1>
            </div>
        </header>",
        $title
    );        
}

//fonction d'affichage du footer
function displayFooter($admin = null){

    if ($admin){
        return sprintf("
        <footer class='mt-5 container-fluid d-flex text-white'>
            <div><a href='../isalem/contact.php' class='a-footer text-decoration-none text-white'>Nous contacter</a></div>
            <div></div>
            <div></div>
            <div></div>
        </footer>");
    } else {
        return sprintf("
        <footer class='mt-5 container-fluid d-flex text-white'>
            <div class='w-25 text-align-center p-2'><a href='contact.php' class='a-footer text-decoration-none text-white'>Nous contacter</a></div>
            <div class='w-25 text-align-center p-2'></div>
            <div class='w-25 text-align-center p-2'></div>
            <div class='w-25 text-align-center p-2'></div>
        </footer>");
    }
}

//Permet l'affichage des propriétés d'un objet, à savoir sa position dans le carrousel
//sa clé (jour ou mois), le complément de cette clé (formats MM/YYYY OU YYYY) et le nombre de
//questionnaires réalisés à la date ("clé + complément")
function displayDataDate($data){

    return sprintf("
        <div class='carousel-item h-100' id='slide-%d'>
            <p class='text-center'>
                <span class='font-italic'><span class='labels'>%s</span>/%s : </span>
                <span class='data_numbers'>%s</span>
                test(s) réalisé(s)
            </p>
        </div>
        ", 
        $data->slide,
        $data->label,
        $data->dateComplement,
        $data->data_number
    );
}

            
    
