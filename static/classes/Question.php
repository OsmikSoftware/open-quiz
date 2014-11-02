<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Question
 *
 * @author mikos
 */
class Question {
    public $Answers = array();
    public function getID(){
        return $this->ID;
    }
    public function setID($id){
        $this->ID = $id;
    }
    public function getType(){
        return $this->Type;
    }
    public function setType($type){
        $this->Type = $type;
    }
    public function getCorrectAnswer(){
        return $this->CorrectAnswer;
    }
    public function setCorrectAnswer($ans){
        $this->CorrectAnswer = $ans;
    }
    public function getAnswers(){
        return $this->Answers;
    }
    public function addAnswer($ans){
        $this->Answers[] = $ans;
    }
    public function getQuestion(){
        return $this->Question;
    }
    public function setQuestion($ques){
        $this->Question = $ques;
    }
}
