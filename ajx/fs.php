<?php
//REQUIRE TESTING FUNCTIONS FILE
require dirname(dirname(__FILE__)).'/fxn/fxn.php';

$response = array();
if(isset($_GET['load_quiz'])){
    $response['data'] = getQuizQuestions();
}
header('Content-type: application/json');
echo json_encode($response);