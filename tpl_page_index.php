<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.0/angular.min.js"></script>
<script src="js/ui-bootstrap-tpls-0.13.0.min.js" type="text/javascript"></script>
<script src="js/quizApp.js"></script>
<link rel="stylesheet" href="css/quiz_style.css" type="text/css" />
<?php include 'php/constants.php';?>
<div class="center-block">
<div class="row"> 
    <!--  #main-content (Center) -->
    <div id="main-content" class="col-lg-12 col-md-12" role="main" ng-app="quizApp">
        <div ng-controller="QuizController">
            <div ng-hide="testing || testing_complete" class="col-lg-6 col-lg-offset-3">
                <h1 class="text-center">Welcome <span ng-show="tester">, {{tester.name}}</span></h1>
                <form name="quizForm" novalidate>
                    <label>Please enter your name:</label>
                    <input type="text" class="form-control" name="name" ng-minlength="1" ng-model="tester.name" placeholder="Name" required>
                    <div ng-show="quizForm.$submitted || quizForm.name.$touched">
                        <span ng-show="quizForm.name.$error.required" class="label label-danger">Please enter your name.</span>
                    </div>
                    <label>Email Address:</label>
                    <input type="email" class="form-control" name="email" ng-minlength="1" ng-model="tester.email" placeholder="Email Address" required>
                    <div ng-show="quizForm.$submitted || quizForm.email.$touched">
                        <span ng-show="quizForm.email.$error.required" class="label label-danger">Please enter your email address.</span>
                        <span ng-show="quizForm.email.$error.email" class="label label-danger">Invalid email address. Please enter a valid email address.</span>
                    </div>
                    <br>
                    <span class="label label-danger" ng-show="login_fail">Oops! There seems to be an issue with your information. Please be sure it's correctly filled and try again.</span>
                    <img src="ajx/loader64.gif" class="center-block" ng-show="login_loading" />
                    <br>
                    <input type="submit" class="btn btn-primary btn-block" ng-click="proceedToTest(tester)" value="Sign in" />
                </form>
            </div>
            <div ng-show="testing && !testing_complete" class="col-lg-9 col-lg-offset-1">
                <h1 class="text-center">Testing as <span ng-show="tester"> {{tester.name}}({{tester.email}})</span></h1>
                <h2 class="text-center">Don't close or refresh your browser window, otherwise you'll have to start over!</h2>
                <progressbar class="progress-striped active" max="totalQuestionsCount" value="correctAnswerCount" type="success">{{progress_msg}}</progressbar>
                <form ng-hide="chunk_loading">
                <ul>
                    <li class="tr-selectable" ng-repeat="question in questions |  slice: display_start_index:display_end_index" ng-init="answeredQuestions.push(question)">
                        <h2>{{($index+display_start_index)+1}}. {{question.question}}</h2>
                        <span ng-show="question.question_incorrect" class="label label-danger">Incorrect. please try again.</span>
                        <ul>
                            <li ng-repeat="answer in answers | filter: {'question_id':question.id}:true">
                                <label class="radio" ng-if="question.type!='multi-answer'"><input type="radio" ng-model="question.selected_answer" ng-change="answerQuestion(question)" name="{{question.id+(question.type=='multi-answer' && $index || '')}}" value="{{answer.answer}}"><h3>{{alphabet.charAt($index).toUpperCase()}}. {{answer.answer}}</h3></label>
                                <label class="radio" ng-if="question.type=='multi-answer'"><input type="radio" ng-model="question.selected_answer[$index]" ng-change="answerQuestion(question)" name="{{question.id+(question.type=='multi-answer' && $index || '')}}" value="{{answer.answer}}"><h3>{{alphabet.charAt($index).toUpperCase()}}. {{answer.answer}}</h3></label>
                            </li> 
                            <button class="btn btn-primary" ng-show="question.type=='multi-answer'" ng-click="resetAnswers(question)">Reset Answers</button>
                        </ul>
                    </li>
                </ul>
                <label class="label label-danger" ng-show="incorrect_answers">Oops! You have some incorrect answers. Fix them before proceeding.</label>
                </form>
                <img src="ajx/loader128.gif" class="center-block" ng-show="chunk_loading" />
                <pager forward-only="true" items-per-page="questions_per_page" complete-text="Complete" complete-callback="nextChunk" ng-change="nextChunk()" total-items="totalQuestionsCount" ng-model="currentPage"></pager>
                <div class="">
                    <span class="pull-right">Powered By <a target="blank" href="<?php echo PRODUCT_HREF; ?>"><?php echo PRODUCT_NAME; ?></a> v.<?php echo PRODUCT_VERSION; ?></span>
                </div>
            </div>
            <div ng-show="testing_complete" class="col-lg-8 col-lg-offset-2">
                <progressbar class="progress-striped active" max="totalQuestionsCount" value="correctAnswerCount" type="success">{{progress_msg}}</progressbar>
                <h2 class="text-center">{{completeMessage}}</h2>
            </div>
        </div>
    </div>
</div>
</div>