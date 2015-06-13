<?php
//REQUIRE TESTING FUNCTIONS FILE
require dirname(dirname(__FILE__)).'/fxn/fxn.php';

//for request payload post parsing
$request_body = file_get_contents('php://input');
$json_data = json_decode($request_body);
$response = array("code"=>0);
if(!empty($json_data->load_quiz)){
    if(!empty($json_data->name) && !empty($json_data->email)){
        $response['code'] = 1;
    }
}
if(!empty($json_data->next_chunk) && !empty($json_data->answeredQuestions)){
    $answered_questions = $json_data->answeredQuestions;
    $incorrect_question_ids = array();
    foreach($answered_questions as $answered_question){
        $question_id = $answered_question->id;
        if(!empty($answered_question->selected_answer)){
            $answer = $answered_question->selected_answer;
            //if answer is object(multi-answer)
            if(is_object($answer) || is_array($answer)){
                $choice_count = 0;
                foreach($answer as $choice){
                    if($choice != NULL){
                        //count choices
                        $choice_count +=1;
                        //if answer isn't correct
                        if(!isCorrectAnswer($question_id,$choice)){
                            $incorrect_question_ids[] = $question_id;
                            //if one answer is incorrect, the question is wrong, so break
                            break;
                        }
                    }
                }
                //ensure correct submitted count is == to actual correct answer count
                if($choice_count != getCorrectAnswerCountForQuestionId($question_id)){
                    $incorrect_question_ids[] = $question_id;
                }
            }
            //if answer is single-answer
            else{
                if(!isCorrectAnswer($question_id,$answer)){
                    $incorrect_question_ids[] = $question_id;
                }
            }
        }
        //else if no answers were selected for question
        else{
            $incorrect_question_ids[] = $question_id;
        }
    }
    //if there are any incorrect answers, return the,
    if(count($incorrect_question_ids)>0){
        $response['incorrect_question_ids'] = $incorrect_question_ids;
    }
    //else if no incorrect answers, give the ok to display more questions
    else{
        //they got all questions correct, so thats the count
        $response['correct_questions_count'] = count($answered_questions);
        $response['code'] = 1;
    }
}
//if testing complete
if(!empty($json_data->testing_complete)){
    $tetser_name = $json_data->name;
    $tester_email = $json_data->email;
    $mail_subject = "Employee Quiz Completion";
    $mail_message = $tetser_name." (".$tester_email.") has successfully completed the employee quiz.\r\n\r\nRegards, [YOUR COMPANY]";
    $mail_headers = 'From: [YOUR COMPANY] <noreply@YOURCOMPANY.COM>' . "\r\n" .
    'Reply-To: noreply@YOURCOMPANY.COM' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    //send emails
    sendQuizCompletionEmail($tester_email, $mail_subject, $mail_message,$mail_headers);
}

header('Content-type: application/json');
echo json_encode($response);