<?php
include '../etests/zz021/fxn.php';
$response = array();
if($_POST['take_quiz']){
    $resp = array();
    $resp['code'] = 1;
    //$quiz = buildQuiz($_POST['test']);
    //$quiz = loadQuiz();
    //$q_questions = $quiz->getQuestions();
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
    header('Content-type: application/json');
    echo json_encode($resp);
}
//if tq(theoretical quiz)/quiz attempt is posted
if($_POST['tq']){
    $resp = array();
    //real quiz questions
    $questions = $_POST['tq']['Questions'];
    
    //answer arrays
    $correct_answers = array();
    $incorrect_answers = array();
    
    $answered_questions = array();
    
    //shell for multi-answer questions
    $ans_arr = array();
    
    //separate url parameters
    $aq_params = parse_url($_POST['aq']);
    $aq_parts = $aq_params['path'];
    parse_str($aq_parts, $aq);
    
    //foreach answered question
    foreach($aq as $aqnum=>$letter_ans){
        $q_num_parts = preg_split('/(?<=[0-9])(?=[a-z]+)/i',$aqnum);
        $q_num = $q_num_parts[0];
        $str_num = (string)$q_num;
        if($q_num_parts[1]){
            if(!$ans_arr[$str_num]){
                $ans_arr[$str_num] = array($letter_ans);
            }
            else{
                $ans_arr[$str_num][] = $letter_ans;
            }
        }
        else{
            $ans_arr[$str_num] = $letter_ans;
        }
    }
    //loops through all quiz questions & check against answered questions
    foreach($questions as $qin=>$question){
        //add 1 to the index to get actual question number
        $q_number = $qin+1;
        $str_num = (string)$q_number;
        if($question['CorrectAnswer'] == $ans_arr[$str_num]){
            $correct_answers[] = $q_number;
        }
        else{
            $incorrect_answers[] = $q_number;
        }
    }
    if(count($incorrect_answers) > 0){
        $resp['correct_answers'] = $correct_answers;
        $resp['incorrect_answers'] = $incorrect_answers;
    }//if any wrong answers
    else{
        $resp['code'] = 1;
        $subject = "Employee e-Test Completion";
        $msg = $_POST['name']." (".$_POST['email'].") has successfully completed the staff e-Test.\r\n\r\nRegards, Stanford ACSR e-Testing";
        
        //send emails
        include '../etests/zz021/fxn.php';
        //sendTestCompletionEmail($_POST['email'], $subject, $msg);
        
        //send to management
        //$m_email = "rec_management@lists.stanford.edu";
        //sendTestCompletionEmail($m_email, $subject, $msg);
    }//end else if all answers correct
    header('Content-type: application/json');
    echo json_encode($resp);
}
if($_POST['quiz_id']){
    $pos = strpos($_POST['quiz_id'],'_');
    $id = substr($_POST['quiz_id'], $pos);
    $response['questions'] = getQuestions();
    header('Content-type: application/json');
    echo json_encode($response);
}

//GETS
if($_GET['quizzes']){
    $response['quizzes'] = getQuizzes();
    header('Content-type: application/json');
    echo json_encode($response);
}