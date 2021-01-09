<?php

class Result{
    public $id;
    public $title;
    public $content;
    public $category;
    
    public function __construct(int $id, string $title, string $content, string $category){
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->category = $category;
    }

    public function __toString(){
        return $this->content;
    }
}