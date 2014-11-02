<?php
if($_POST['login']){
    $errors = 0;
    $resp = array();
    if(!$_POST['name'] || !$_POST['email']){
        $errors += 1;
        $resp['code'] = 2;
    }
    if(!filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)){
        $errors += 1;
        $resp['code'] = 3;
    }
    if($errors == 0){
        $resp['name'] = $_POST['name'];
        $resp['email'] = $_POST['email'];
        $resp['stamp'] = time();
        $resp['code'] = 1;
    }
    header('Content-type: application/json');
    echo json_encode($resp);
}