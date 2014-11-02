<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Quiz
 *
 * @author mikos
 */
class Quiz {
    public $Questions = array();
    public function getTitle(){
        return $this->Title;
    }
    public function setTitle($title){
        $this->Title = $title;
    }
    public function addQuestion($ques){
        $this->Questions[] = $ques;
    }
    public function getQuestions(){
        return $this->Questions;
    }
}