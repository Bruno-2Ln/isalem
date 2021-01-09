<?php

class Test{
    public $id;
    public $title;
    public $description;
    
    public function __construct(int $id, string $title, string $description){
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
    }

    public function __toString(){
        return $this->title;
    }
}