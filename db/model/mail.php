<?php

class Mail{
    public $id;
    public $object;
    public $body;
    public $result_id;
    
    public function __construct(int $id, string $object, string $body, int $result_id){
        $this->id = $id;
        $this->object = $object;
        $this->body = $body;
        $this->result_id = $result_id;
    }

    public function __toString(){
        return $this->body;
    }
}