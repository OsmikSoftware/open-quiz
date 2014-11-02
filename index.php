<?php
//require 'require_session_req.php';
?>
<!DOCTYPE html>
<!--[if IE 7]> <html lang="en" class="ie7"> <![endif]-->
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
    <title>Staff Testing</title>
    <script src="js/fxn.js"></script>
</head>

<body class="home">
    <div id="content" class="container" role="main" tabindex="0">
        <div class="modal fade quiz_complete_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Quiz Complete</h4>
                    </div>
                    <div class="modal-body">
                        <p>Quiz successfully completed!</p>
                        <button id="btn-return-home" class="btn btn-primary">Finish</button>
                        <script>
                            $("#btn-return-home").click(function(){
                                window.location.reload();
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>

        <h1 class="">Staff Testing</h1>
        <div class="row"> 
            <!--  #main-content (Center) -->
            <div id="main-content" class="col-md-12" role="main"> 
                <form role="form" method="post" id="signin_form">
                    <input type="hidden" name="login" value="1">
                    <label>Please enter your name:</label>
                    <div class="row">
                        <div class="col-xs-6">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                        </div>
                    </div>
                    <label>Email Address:</label>
                    <div class="row">
                        <div class="col-xs-6">
                            <p id="invalid_email_label" style="display:none;"><span class="label label-danger">Invalid email address. Please enter a valid email address</span></p>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-6">
                            <p id="missing_info_label" style="display:none;"><span class="label label-danger">You're missing some information. Please completely fill out the login form.</span></p>
                            <a class="btn btn-primary" id="signin_submit">Sign in</a>
                        </div>
                    </div>
                </form>
                <img src="ajx/stanford_loader.gif" id="loader" class="hidden"/>
                <div id="test_area" style="display:none;">
                <h3>Testing as: <span id="tester_name"></span> (<span id="tester_email"></span>)</h3>
                <h2>Don't close your browser window, otherwise you'll have to start over!</h2>
                <br><h3>Page <span id="current_page_number"></span> of <span id="total_pages"></span></h3>
                <br><div id="test_screen"></div>
                </div>
                <script>
                function initQuiz(){
                    $.post("ajx/login.php", $("#signin_form").serialize())
                    .done(function(resp){
                        if(resp.code == 2){
                            showThenFade("#missing_info_label");
                        }
                        else if(resp.code == 3){
                            showThenFade("#invalid_email_label");
                        }
                        else if(resp.code == 1){
                            $("#signin_form").hide();
                            $("#tester_name").html(resp.name);
                            $("#tester_email").html(resp.email);
                            show("#loader");
                            $.post("ajx/ps.php", {take_quiz:1})
                            .done(function(resp){
                                hide("#loader");
                                if(resp.code == 1){
                                    loadQuiz(resp.quiz, "#test_screen");
                                }
                                else{
                                    showThenFade("#choice_error_label");
                                }
                            });
                            $("#test_area").show();
                        }
                    });
                }
                $("#name").keypress(function( event ) {
                  if ( event.which == 13 ) {
                      initQuiz();
                  }
                });
                $("#email").keypress(function( event ) {
                  if ( event.which == 13 ) {
                      initQuiz();
                  }
                });
                $("#signin_submit").click(function(){
                    initQuiz();
                });
                </script>
            </div>
        </div>
    </div>
</body>
</html>