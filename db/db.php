<?php

require_once('model/admin.php');
require_once('model/test.php');
require_once('model/question.php');
require_once('model/instruction.php');
require_once('model/personality.php');
require_once('model/result.php');
require_once('model/answer.php');
require_once('model/result.php');
require_once('model/mail.php');
require_once('model/message.php');

class DatabaseHandler{
    private string $_dbname;
    private string $_adminname;
    private string $_password;
    private PDO $_handler; //va renfermer l'objet PDO qui sera instancié par la méthode connect().

    public function __construct(string $dbname, string $adminname, string $password){
        $this->_dbname = $dbname;
        $this->_adminname = $adminname;
        $this->_password = $password;
       
        $this->connect(); //pour permettre une connexion automatique à chaque instanciation de DatabaseHandler.
    }

    public function connect(){
        try {
            $dbh = new PDO("mysql:host=localhost;charset=utf8;dbname={$this->_dbname}", $this->_adminname, $this->_password, [PDO::ATTR_EMULATE_PREPARES => false]);
        } catch (PDOException $e){
           
            http_response_code(500);
            die("500 - Internal Server Error");
        }

        $this->_handler = $dbh;
    }


    //Récupération de l'admin par l'adresse email
    public function getAdminByEmailAddress(string $emailAddress){
        $stmt = $this->_handler->prepare("SELECT * FROM admin WHERE admin.emailAddress = :email");
        $stmt->execute(
            [
            ":email" => $emailAddress
            ]
        );
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    //Récupération de l'admin dont le mail est celui du contact
    public function getAdminContact(){
        $stmt = $this->_handler->prepare("SELECT * FROM admin WHERE mail_contact = 1");
        $stmt->execute();
        
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        $admin = new Admin(
            $res->id, 
            $res->roles_admin,
            $res->mail_contact, 
            $res->firstname, 
            $res->lastname, 
            $res->emailAddress, 
            $res->password);
    
    return $admin;
    }

    //Récupération de l'admin par son id
    public function getAdminByid(int $id){
        $stmt = $this->_handler->prepare("SELECT * FROM admin WHERE admin.id = :id");
        $stmt->execute(
            [
                ":id" => $id
            ]
        );
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    //Création d'un admin
    public function createAdmin(int $contactMail, string $firstname, string $lastname, int $roles_admin, string $emailAddress, string $password){

        $stmt = $this->_handler->prepare(
                                "INSERT INTO admin 
                                (mail_contact, firstname, lastname, roles_admin, emailAddress, password) 
                                VALUES 
                                (:mail_contact, :firstname, :lastname, :roles_admin, :emailAddress, :password)");

        $stmt->execute(
            [
            "mail_contact" => $contactMail,
            ":firstname" => $firstname,
            ":lastname" => $lastname,
            ":roles_admin" => $roles_admin,
            ":emailAddress" => $emailAddress,
            ":password" => $password,
            ]
        );
    }

    //Pour renvoyer un tableau de tous les admins
    public function getAllAdmins(){

        $stmt = $this->_handler->prepare(
            "SELECT * FROM admin");

        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        $admins = [];
        foreach ($res as $admin){
            array_push(
                $admins, 
                new Admin(
                    $admin->id, 
                    $admin->roles_admin,
                    $admin->mail_contact, 
                    $admin->firstname, 
                    $admin->lastname, 
                    $admin->emailAddress, 
                    $admin->password,));
        }
        return $admins;
    }

    //Suppression d'un admin
    public function deleteAdmin(int $id){

        $sqlQuery = (
            "DELETE FROM admin WHERE :id = id");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':id' => $id
            ]
        );
    }

    //Modification d'un admin
    public function updateAdmin(int $id, int $roles_admin, int $mail_contact, string $firstname, string $lastname, string $emailAddress, string $password){
        $sqlQuery = (
                    "UPDATE admin SET 
                    roles_admin = :roles_admin,
                    mail_contact = :mail_contact,
                    firstname = :firstname, 
                    lastname = :lastname, 
                    emailAddress = :emailAddress, 
                    password = :password 
                    WHERE :id = id");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ":id" => $id,
            ":roles_admin" => $roles_admin,
            ":mail_contact" => $mail_contact,
            ":firstname" => $firstname,
            ":lastname" => $lastname,
            ":emailAddress" => $emailAddress,
            ":password" => $password,
            ]
        );
    }

    //lorsque le mail de contact doit être changé, la méthode est appelée 
    //pour remettre à 0 la valeur de la colonne d'admin contactMail étant à 1
    //c'est à dire que l'admin qui était le mail de contact ne l'est plus.
    public function updateAdminContact(){
        $sqlQuery = (
                "UPDATE admin SET 
                mail_contact = 0
                WHERE mail_contact = 1");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute();
    }


    //Retourne un objet Test par l'id entré en paramètre    
    public function getTestById(int $id){
        $sqlQuery = (
                    "SELECT * 
                    FROM test
                    WHERE id = :id");
        
        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':id' => $id
            ]
        );
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        $test = new Test($res->id, $res->title, $res->description);
        
        return $test;
    }
    
    //Retourne un objet Test par le titre entré en paramètre  
    public function getTestByTitle(string $title){
        $sqlQuery = (
                    "SELECT * 
                    FROM test
                    WHERE title = :title");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':title' => $title
            ]
        );
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        $test = new Test($res->id, $res->title, $res->description);
        
        return $test;
    }

    //Modification d'un test
    public function updateTest(int $id, string $description){
        $sqlQuery = (
                    "UPDATE test
                    SET description = :description
                    WHERE :id = id");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ":id" => $id,
            ":description" => $description,
            ]
        );  
    }

    //Pour renvoyer une variable contenant un objet de question
    public function getQuestion(int $id){
        $sqlQuery = (
                    "SELECT * 
                    FROM question 
                    WHERE question.id = :id");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':id' => $id
            ]
        );
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        $question = new Question($res->id, $res->content);

        return $question;
    }
    
    //Modification d'une question
    public function updateQuestion(int $id, string $content){
        $sqlQuery = (
                    "UPDATE question
                    SET content = :content
                    WHERE :id = id");
    
        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ":id" => $id,
            ":content" => $content,
            ]
        );    
    }


    //Retourne un objet question en fonction de l'id renseigné
    public function getAnswer(int $id){

        $sqlQuery = (
                    "SELECT * 
                    FROM answer 
                    WHERE id = :id");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':id' => $id
            ]
        );
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        $answer = new Answer(
                        $res->id, 
                        $res->numberInList, 
                        $res->orderOfAppearance, 
                        $res->content, 
                        $res->category);

        return $answer;
    }
    
    
    //Pour renvoyer un tableau d'objets de toutes les réponses d'une question
    public function getAnswersForOneQuestion($id){
        
        $sqlQuery = (
                    "SELECT * FROM question 
                    INNER JOIN answer ON question_id = question.id
                    WHERE question.id = :id");


        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':id' => $id
            ]
        );
        $answers = [];
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($res as $answer){
            array_push(
                    $answers, 
                    new Answer(
                        $answer->id, 
                        $answer->numberInList, 
                        $answer->orderOfAppearance, 
                        $answer->content, 
                        $answer->category));
        }
        return $answers;
    } 
    
    //Modification d'une réponse
    public function updateAnswer(int $id, string $content){
        $sqlQuery = (
                    "UPDATE answer
                    SET content = :content
                    WHERE :id = id");
        
        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ":id" => $id,
            ":content" => $content,
            ]
        );  
    }

    //Va renvoyer le nombre de questions du questionnaire
    public function numberOfQuestion(){
        
        $sqlQuery = (
                    "SELECT COUNT(id) FROM question");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_NUM);
        foreach ($res as $ligne){
          
            return $ligne; 
        }
    }
    
    // Récupère sous forme de tableau les points forts ou faibles d'un résultat
    // le paramètre $point prend l'une des deux valeurs possibles appartenant à la table personality,
    // à savoir strong ou weak.
    public function getPointOfCategory($category, $point){

        $sqlQuery = (
                    "SELECT personality.content, personality.id, personality.point
                    FROM result INNER JOIN personality ON result_id = result.id 
                    WHERE :category = category AND :point = point");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':category' => $category,
            ':point' => $point
            ]
        );
        $personalities = [];
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($res as $personality){
            array_push(
                $personalities,
                new Personality(
                    $personality->id,
                    $personality->content,
                    $personality->point));
        }
        return $personalities;
    }

    //Modification d'une ligne de la table personality
    public function updatePersonality(int $id, string $content){
        $sqlQuery = (
            "UPDATE personality
            SET content = :content
            WHERE :id = id");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ":id" => $id,
            ":content" => $content,
            ]
        );
    }

    
    //Pour renvoyer sous forme de tableau tous les objets Instruction
    public function getAllInstructions(){
        $sqlQuery = (
            "SELECT * FROM instruction 
            ORDER BY orderOfAppearance");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        $instructions = [];

        foreach ($res as $instruction){
            array_push(
                $instructions, 
                new Instruction(
                        $instruction->id, 
                        $instruction->orderOfAppearance,
                        $instruction->content));
        }
        return $instructions;
    }
    
    //Va renvoyer un objet Instruction en fonction de l'id
    public function getInstruction(int $id){
        $sqlQuery = (
                    "SELECT * 
                    FROM instruction 
                    WHERE :id = id");
            
        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':id' => $id,
            ]
        );
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    //Création d'une instruction
    public function createInstruction(int $orderOfAppearance, string $content){

        $stmt = $this->_handler->prepare(
                                "INSERT INTO instruction 
                                (orderOfAppearance, content) 
                                VALUES 
                                (:orderOfAppearance, :content)");

        $stmt->execute(
            [
            ":orderOfAppearance" => $orderOfAppearance,
            ":content" => $content,
            ]
        );
    }
    

    //Modification d'une instruction
    public function updateInstruction(int $id, string $content, int $orderOfAppearance){
        $sqlQuery = (
                "UPDATE instruction 
                SET orderOfAppearance = :orderOfAppearance, content = :content
                WHERE :id = id");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ":id" => $id,
            ":orderOfAppearance" => $orderOfAppearance,
            ":content" => $content
            ]
        );
    }

    //Suppression d'une instruction
    public function deleteInstruction(int $id){

        $sqlQuery = (
            "DELETE FROM instruction 
            WHERE :id = id");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':id' => $id
            ]
        );
    }
    
    //Pour renvoyer un tableau de tous les objets Result
    public function getAllResults(){

        $sqlQuery = ("SELECT * FROM result");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        $results = [];
        foreach ($res as $result){
            array_push(
                $results, 
                new Result(
                    $result->id, 
                    $result->title, 
                    $result->content, 
                    $result->category));
        }
        return $results;
    }
    
    //Va renvoyer un objet Result en fonction de la categorie donnée
    public function getResultOfTest(string $category){
        $sqlQuery = (
                "SELECT * 
                FROM result 
                WHERE :category = category");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':category' => $category,
            ]
        );
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        return new Result(
                        $res->id, 
                        $res->title, 
                        $res->content, 
                        $res->category);
    }

    //Modification du titre et du contenu d'un résultat
    public function updateResult(string $category, string $title, string $content){
        $sqlQuery = (
                "UPDATE result 
                SET title = :title,
                    content = :content 
                WHERE :category = category");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ":category" => $category,
            ":title" => $title,
            ":content" => $content,
            ]
        );
    }
    
    //Retourne un objet mail en fonction de la catégorie de résultat
    public function getMail(string $category){
        $sqlQuery = (
            "SELECT mail.id, mail.object, mail.body, mail.result_id
            FROM result INNER JOIN mail ON result_id = result.id 
            WHERE :category = category");
        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':category' => $category,
            ]
        );
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        return new Mail(
                        $res->id, 
                        $res->object, 
                        $res->body,
                        $res->result_id);
    }

    //retourne un objet mail en fonction de son id
    public function getMailById(int $id){
        $sqlQuery = (
            "SELECT * FROM mail
            WHERE :id = id");
        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':id' => $id
            ]
        );
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        return new Mail(
                        $res->id, 
                        $res->object, 
                        $res->body,
                        $res->result_id);
    }


    //Retourne un tableau d'objets mail
    public function getAllMails(){
        $sqlQuery = (
            "SELECT * FROM mail");
            
        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        $mails = [];
        foreach ($res as $mail){
            array_push(
                $mails, 
                new Mail(
                    $mail->id, 
                    $mail->object, 
                    $mail->body,
                    $mail->result_id));
            }
            return $mails;
    }
    
    //Modification de l'objet et du corps d'un mail
    public function updateMail(int $id, string $object, string $body){
        $sqlQuery = (
                "UPDATE mail 
                SET object = :object, body = :body 
                WHERE :id = id");

        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ":id" => $id,
            ":object" => $object,
            ":body" => $body
            ]
        );
    }
    
    //Pour renvoyer un objet Message récupéré par sa propriété object
    public function getMessageByObject(string $object){
        $sqlQuery = (
            "SELECT * FROM message
            WHERE :object = object");
        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':object' => $object
            ]
        );
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        return new Message(
                        $res->id, 
                        $res->object, 
                        $res->body,
                        $res->alert);
    }

    //Pour renvoyer un tableau d'objets Messages
    public function getAllMessages(){
        $sqlQuery = (
            "SELECT * FROM message"); 
        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        $messages = [];
        foreach ($res as $message){
            array_push(
                $messages, 
                new Message(
                    $message->id, 
                    $message->object, 
                    $message->body,
                    $message->alert));
            }
            return $messages;
    }
    
    //Création d'une ligne dans la table date_of_test
    public function createDate(string $date){

        $stmt = $this->_handler->prepare(
                                "INSERT INTO date_of_test 
                                (date) 
                                VALUES 
                                (:date)");
        $stmt->execute(
            [
            ":date" => $date,
            ]
        );
    }

    //Retourne le nombre d'id ayant comme date celle entrée en paramètre
    public function getDateData(string $date){

        $sqlQuery = (
                "SELECT COUNT(id) FROM date_of_test
                WHERE :date = date"); 
        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute(
            [
            ':date' => $date,
            ]
        );
        $res = $stmt->fetch(PDO::FETCH_NUM);

        foreach ($res as $ligne){
            return $ligne;
        }
    }

    //Retourne le nombre d'id dont la date est comprise entre les deux paramètres
    public function getNumberOfDates(string $date1, string $date2){

        $sqlQuery = (
                "SELECT COUNT(id) FROM date_of_test
                WHERE date BETWEEN '$date1' AND '$date2'"); 
        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_NUM);
    
        foreach ($res as $ligne){
    
            return $ligne;
        }
    }
    
    //Efface toutes les données d'une table
    public function deleteDatas($table){
        $sqlQuery = (
            "TRUNCATE TABLE $table");
    
        $stmt = $this->_handler->prepare($sqlQuery);
        $stmt->execute();
    }

}