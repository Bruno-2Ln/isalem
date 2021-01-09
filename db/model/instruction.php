<?php

class Instruction{
    public $id;
    public $orderOfAppearance;
    public $content;
    
    public function __construct(int $id, int $orderOfAppearance, string $content){
        $this->id = $id;
        $this->orderOfAppearance = $orderOfAppearance;
        $this->content = $content;
    }

    public function __toString(){
        return $this->content;
    }
}