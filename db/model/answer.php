<?php

class Answer{
    public $id;
    public $numberInList;
    public $orderOfAppearance;
    public $content;
    public $category;
    
    public function __construct(int $id, int $numberInList, string $orderOfAppearance, string $content, string $category){
        $this->id = $id;
        $this->numberInList = $numberInList;
        $this->orderOfAppearance = $orderOfAppearance;
        $this->content = $content;
        $this->category = $category;
    }

    public function __toString(){
        return $this->content;
    }
}