<?php
//REQUIRE TESTING FUNCTIONS FILE
chdir(__DIR__);
require '../fxn/fxn.php';

$response = array();
if($_POST['take_quiz']){
    $resp = array();
    $resp['code'] = 1;
    $quiz = returnQuiz();
    $q_content = '<h1>Employee Training Quiz</h1>
                    <div class="well">
                        <b>Note:</b> If you close your browser window you\'ll have to start from the beginning of the quiz.
                    </div>
                    <form id="quiz-id" action="" method="post">
                    <ul class="quiz-area">';
                    $q_content .= '</ul>
                    <input type="hidden" name="next_chunk" value="<?php echo $next_chunk;?>" />
                    <button type="submit" class="btn pull-right btn-primary" id="nxt_chunk<?php echo $next_chunk; ?>">Next</button>
                    </form>';
    $resp['quiz'] = $quiz;
}

//if posting quiz
if($_POST['aq']){
    //question ids in question chunk
    $chunked_ids = $_POST['chunked_ids'];
    
    //decode the posted quiz data
    $decoded = urldecode($_POST['aq']);
    
    //separate each answer & place into one array
    $answers = explode("&",$decoded);
    
    //create array for multi-answer tracking
    $running_actual_answers = array();
    
    /*//create counter for multi-answer counting
    $acounter = 0;*/
    $running_question_id = 0;
    $question_correct_answers = array();
    
    //create empty array for correct quiz answers
    $quiz_correct_answers = array();
    
    //create empty array for questions answered by user
    $answered_questions = array();
    
    //loop through each answer given
    foreach($answers as $answer){
        //answer is formatted as 'question_number=question_id:answer_id', so parse it
        $answer = substr($answer, strpos($answer, '=')+1, strlen($answer));
        
        //separate question id from answer id
        $answer_parts = explode(':', $answer);
        
        //create vars for each
        $question_id = $answer_parts[0];
        $answer_id = $answer_parts[1];
        
        $answered_questions[$question_id][] = $answer_id;
    }
    foreach($answered_questions as $question_id=>$answer){
        if(getQuestionCorrectAnswers($question_id) == $answer){
            $quiz_correct_answers[] = $question_id;
        }
    }
    //if all answers correct
    if($quiz_correct_answers == getQuestionIDs()){
        $resp['code'] = 1;
        $subject = "Employee Quiz Completion";
        $msg = $_POST['name']." (".$_POST['email'].") has successfully completed the employee quiz.\r\n\r\nRegards, Stanford Recreation";
        //send emails
        sendQuizCompletionEmail($_POST['email'], $subject, $msg);
    }
    //else if got some questions wrong
    else{
        $resp['correct_answers'] = $quiz_correct_answers;
        $resp['incorrect_answers'] = array_diff(getQuestionIDs(), $quiz_correct_answers);
    }
}

header('Content-type: application/json');
echo json_encode($resp);