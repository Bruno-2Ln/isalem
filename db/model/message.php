<?php

class Message{
    public $id;
    public $object;
    public $body;
    public $alert;
    
    public function __construct(int $id, string $object, string $body, string $alert){
        $this->id = $id;
        $this->object = $object;
        $this->body = $body;
        $this->alert = $alert;

    }

    public function __toString(){
        return $this->body;
    }
}