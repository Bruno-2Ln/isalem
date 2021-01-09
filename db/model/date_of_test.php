<?php

class DateOfTest{
    public $id;
    public $date;
    
    public function __construct(int $id, string $date){
        $this->id = $id;
        $this->date = $date;

    }

    public function __toString(){
        return $this->date;
    }
}