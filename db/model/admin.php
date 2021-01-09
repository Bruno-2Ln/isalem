<?php

class Admin{
    public $id;
    public $roles_admin; // $roles_admin prendra une valeur numérique; si SUPER_ADMIN elle sera de 1 sinon ADMIN sera de 2.
    public $mail_contact;
    public $firstname;
    public $lastname;
    public $mailAddress;
    public $password;

    public function __construct(int $id, int $roles_admin, string $mail_contact, string $firstname, string $lastname, string $emailAddress, string $password){
        $this->id = $id;
        $this->roles_admin = $roles_admin;
        $this->mail_contact = $mail_contact;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
    }

    public function __toString(){
        return $this->mailAddress;
    }
}