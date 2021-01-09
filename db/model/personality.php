<?php

class Personality{
    public $id;
    public $content;
    public $point;
    
    public function __construct(int $id, string $content, string $point){
        $this->id = $id;
        $this->content = $content;
        $this->point = $point;
    }

    public function __toString(){
        return $this->content;
    }
}