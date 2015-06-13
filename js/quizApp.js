angular.module('quizApp', ['ui.bootstrap'])
.controller('QuizController', function($http,$scope,$modal){
    $scope.alphabet = 'abcdefghijklmnopqrstuvwxyz';
    
    //initialization properties
    $scope.answeredQuestions = [];
    $scope.incorrectQuestions = [];
    $scope.correctQuestions = [];
    $scope.selectedAnswers = [];
    $scope.correctAnswerCount = 0;
    $scope.progress_msg = '';
    $scope.questions_per_page = 5;
    $scope.currentPage = 1;
    $scope.display_start_index = 0;
    $scope.display_end_index = $scope.questions_per_page;
    $scope.maxDisplayedPages = 5;
    $scope.testing_complete= false;
    $scope.resetAnswers = function(question){
        question.selected_answer=[];
    };
    $scope.proceedToTest = function(tester){
        if(tester){
            if(tester.name && tester.email){
            tester.load_quiz = 1;
            $scope.login_loading = true;
            $scope.login_fail = false;
            $http.post("ajx/ps.php",tester)
            .success(function(response) {
                $scope.login_loading = false;
                if(response.code === 1){
                    $http.get("ajx/fs.php?load_quiz=true")
                    .success(function(response2){
                        $scope.questions = response2.data.questions;
                        $scope.answers = response2.data.answers;
                        $scope.testing = true;
                        $scope.totalQuestionsCount = $scope.questions.length;
                    });
                }
                else{
                    $scope.login_fail = true;
                }
            });
            }
        }
    };
    $scope.answerQuestion = function(question){
        if(question.selected_answer){
            //loop through answered questions
            for(var i=0;i<$scope.answeredQuestions.length;i++){
                //if answered question id == questionid in array(question is in array already)
                if($scope.answeredQuestions[i].id===question.id){
                    //remove it
                    $scope.answeredQuestions.splice(i,1);
                }
            }
            //add newly answered question to array
            $scope.answeredQuestions.push(question);
        }
    };
    $scope.nextChunk = function() {
        var proceed = {};
        $scope.chunk_loading = true;
        proceed.next_chunk = 1;
        proceed.answeredQuestions = $scope.answeredQuestions;
        $http.post("ajx/ps.php",proceed)
        .success(function(response){
            //reset all incorrect questions
            for(var i=0;i<$scope.questions.length;i++){
                $scope.questions[i].question_incorrect = 0;
            };
            if(response.code === 1){
                //empty answered questions array
                $scope.answeredQuestions = [];
                //increment scope correct answer count by number of questions just correctly answered
                $scope.correctAnswerCount += response.correct_questions_count;
                if($scope.correctAnswerCount === $scope.totalQuestionsCount){
                    $scope.progress_msg = "100% complete! Good job!";
                    $scope.completeMessage = "Congrats "+$scope.tester.name+", you've successfully completed the quiz. This page will refresh shortly...";
                    $scope.testing = false;
                    $scope.testing_complete = true;
                    var tester = $scope.tester;
                    tester.testing_complete = true;
                    $http.post("ajx/ps.php",tester)
                    .success(function(response){
                        if(response.code === 1){
                            window.location.reload();
                        }
                    });
                    
                }
                else{
                    $scope.progress_msg = parseInt(($scope.currentPage-1)/($scope.totalQuestionsCount/$scope.questions_per_page)*100)+"%";
                    $scope.display_start_index = ($scope.currentPage*$scope.questions_per_page)-$scope.questions_per_page;
                    $scope.display_end_index = $scope.display_start_index+$scope.questions_per_page;
                    //if on last page
                    if($scope.currentPage === ($scope.totalQuestionsCount/$scope.questions_per_page)){
                        $scope.lastPage = true;
                    }
                }
            }
            else{
                //reset current page
                $scope.currentPage = $scope.currentPage -1;
                
                $scope.incorrectQuestions = response.incorrect_question_ids;
                //loop through each incorrect question id
                for(var i=0;i<response.incorrect_question_ids.length;i++){
                    //loop through scope questions
                    for(var n=0;n<$scope.questions.length;n++){
                        if($scope.questions[n].id === response.incorrect_question_ids[i]){
                            //set question as incorrect
                            $scope.questions[n].question_incorrect = 1;
                        }
                    }
                }
            }
            $scope.chunk_loading = false;
        });
    };
    $scope.completeMessage = "Congrats, you've successfully completed the quiz. This page will refresh shortly...";
})
.filter('slice', function() {
  return function(arr, start, end) {
    return (arr || []).slice(start, end);
  };
});