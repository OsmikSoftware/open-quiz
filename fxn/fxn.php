<?php
/*
 * CORE FUNCTIONS FILE FOR STAFF TESTING PLATFORM
 * AUTHOR: MIKOS J
 * DATE: ~9/2014
 */

//DB INFO
define("DB_SERVER", "your server name");
define("DB_USER", "your server user name");
define("DB_PASS", "your server password");
define("DB_NAME", "your db name");

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

//IDEAL FOR SINGLE-ACTION QUERIES TO BE EXECUTED, BECAUSE IT RETURNS THE AFFECTED ROWS(RETURNS 1 ON SUCCESS)
function actionQuery($query) {
    $conn = getDBConnection();
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $conn->affected_rows;
}

function getQuestions(){
    return getRowsQuery("SELECT * FROM questions");
}
function getQuestionIDs(){
    $question_ids = array();
    $qdata = getRowsQuery("SELECT id FROM questions");
    foreach($qdata as $question_id){
        $question_ids[] = $question_id['id'];
    }
    return $question_ids;
}
function getQuestionByID($question_id){
    return getRowQuery("SELECT * FROM questions WHERE id={$question_id}");
}
function checkAnswer($answer_id){
    $adata = getRowQuery("SELECT is_correct FROM answers WHERE id={$answer_id}");
    if($adata['is_correct']):
        return 1;
    else:
        return 0;
    endif;
}
function getQuestionAnswers($question_id){
    return getRowsQuery("SELECT * FROM answers WHERE question_id={$question_id}");
}
function getQuestionCorrectAnswers($question_id){
    $answer_ids = array();
    $adata = getRowsQuery("SELECT id FROM answers WHERE question_id={$question_id} AND is_correct=1");
    foreach($adata as $answer_id){
        $answer_ids[] = $answer_id['id'];
    }
    return $answer_ids;
}

//BUILDS & RETURNS THE FULL QUIZ
function returnQuiz(){
    //LOAD CLASSES
    chdir(__DIR__);
    require_once '../static/classes/Quiz.php';
    require_once '../static/classes/Question.php';
    
    $quiz = new Quiz();
    $questions = getQuestions();
    foreach($questions as $db_question){
        $question = new Question();
        $question->setID($db_question['id']);
        $question->setQuestion($db_question['question']);
        $question->setType($db_question['type']);
        $answers = getRowsQuery("SELECT * FROM answers WHERE question_id={$db_question['id']}");
        foreach($answers as $db_answer){
            $question->addAnswer($db_answer['id'].':'.$db_answer['answer']);
        }
        $quiz->addQuestion($question);
    }
    return $quiz;
}

//FUNCTION TO EXECUTE UPON SUCCESSFUL QUIZ COMPLETION
function sendQuizCompletionEmail($email, $mailsubject, $message){
    $email .= "add additional recipients here(testing administrator, other staff members,etc.)";
    $headers = "Place your custom headers here";
    mail($email, $mailsubject, $message, $headers);
}