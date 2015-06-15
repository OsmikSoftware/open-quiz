<?php
/*
 * CORE FUNCTIONS FILE FOR STAFF TESTING PLATFORM
 * AUTHOR: MIKOS J
 * DATE: ~9/2014
 * LAST UPDATED: 6/13/15
 */

//DB INFO
define("DB_SERVER", "dbserver");
define("DB_USER", "dbuser");
define("DB_PASS", "dbpass");
define("DB_NAME", "dbname");
//FUNCTION TO INITIALIZE A DB CONNECTION. ACTUAL DB CREDENTIALS MUST BE DEFINED SEPARATELY
function getDBConnection() {
    return new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
}

//USEFUL FUNCTION TO GRAB A ROW FROM DB
function getRowQuery($query) {
    $conn = getDBConnection();
    $result = $conn->query($query);
    return $result->fetch_assoc();
}

//USEFUL FUNCTION TO GRAB MULTIPLE ROWS FROM DB
function getRowsQuery($query) {
    $data = array();
    $conn = getDBConnection();
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

//IDEAL FOR SINGLE-ACTION QUERIES TO BE EXECUTED, BECAUSE IT RETURNS THE AFFECTED ROWS(RETURN VALUE '1' IS IDEAL)
function actionQuery($query) {
    $conn = getDBConnection();
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $conn->affected_rows;
}

//questions and answers to display to quiz
function getQuizQuestions(){
    $questions = getRowsQuery("SELECT * FROM questions");
    $answers = getRowsQuery("SELECT question_id,answer FROM answers");
    $quiz_questions = array('questions'=>$questions,'answers'=>$answers);
    return $quiz_questions;
}
function getCorrectAnswerCountForQuestionId($question_id){
    return count(getRowsQuery("SELECT id FROM answers WHERE question_id={$question_id} AND is_correct=1"));
}
function isCorrectAnswer($question_id,$string_answer){
    return getRowQuery("SELECT answer FROM answers WHERE question_id={$question_id} AND answer='{$string_answer}' AND is_correct=1");
}

function sendQuizCompletionEmail($email, $mailsubject, $message,$headers=''){
    //uncomment the first comma if you plan to add additional emails
    //$email  .=  ', '; // note the comma
    //comma-separated email addresses(e.g. for management, etc.)
    //$email .= 'admin1@testadmin.com,admin2@testadmin.com, etc.';
    mail($email, $mailsubject, $message, $headers);
}
