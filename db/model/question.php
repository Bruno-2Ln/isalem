<?php

class Question{
    public $id;
    public $content;
    
    public function __construct(int $id, string $content){
        $this->id = $id;
        $this->content = $content;
    }

    public function __toString(){
        return $this->content;
    }
}