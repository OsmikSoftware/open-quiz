var chunk_question_count = 10;
var chunk_number = 1;
var chunks_count = 1;

function getAlphabet(){
    var astring = "abcdefghijkjlmnopqrstuvwxyz";
    return astring.split("");
}
function showThenFade(id){
    $(id).show().delay('2000').fadeOut();
}
function showNextChunk(){
    if($("#quiz_id").serialize() == ""){
        //$().showThenFade();
    }
    $.post("ajx/ps.php", {'aq':$("#quiz_id").serialize(), 'name':$("#tester_name").html(), 'email':$("#tester_email").html()})
        .done(function(resp){
            if(resp.correct_answers.length == chunk_number*chunk_question_count){
                //create list of 1st 'li' elements in each question chunk
                var list_chunk_starts = $(".last_chunk_question");//.next("li");

                //get 1st 'li' in next question chunk by searching list via index
                var next_chunk_first_question = list_chunk_starts[chunk_number-1];

                //objectify next question chunk
                var next_question_chunk = $(next_chunk_first_question).nextUntil(".last_chunk_question");

                //hacky, but changes 1st li to appropriate value. For some reason the 1st 'li' element always starts at 1..this fixes that
                $(next_question_chunk[0]).val(chunk_number*chunk_question_count+1);

                //find all previous questions before this chunk
                var prev_questions = $(next_chunk_first_question).prevAll("li");

                //hide previous questions
                $(prev_questions).fadeOut("slow");

                //show next question chunk
                $(next_question_chunk).fadeIn("slow");

                //increment chunk number
                chunk_number +=1;

                //change html page number
                $("#current_page_number").html(chunk_number);
                
                //hide loader
                $("#loader").show();
            }
            else{
                $.each(resp.incorrect_answers, function(k,v){
                    showThenFade("#error_marker_"+v);
                });
                $.each(resp.correct_answers, function(k,v){
                    $("#error_marker_"+v).hide();
                });
            }
        });
        
    
    
    
    //if last chunk of quiz
    if(chunk_number==chunks_count){
        $(".next_chunk").hide();
        $("#q_submit").show();
    }
}
function loadQuiz(quiz, div){
    var counter = 0;
    chunks_count = parseInt(quiz.Questions.length/chunk_question_count);
    if(quiz.Questions.length%chunk_question_count > 0){
        chunks_count +=1;
    }
    var html_quiz = '<form id="quiz_id" method="post">'
                    +'<input type="hidden" id="current_chunk" value="'+chunk_number+'">'
                    +'<ol class="quiz_chunk">';
    $("#current_page_number").html(chunk_number);
    $("#total_pages").html(chunks_count);
    $.each(quiz.Questions, function(key,question){
        var q_number = key+1;
        var question_id = question.ID;
        html_quiz += '<li><b>'+question.Question+'</b> <span class="label label-danger" style="display:none;" id="error_marker_'+question_id+'">Incorrect. Please try again.</span>';
        html_quiz += '<ul class="q_answer_list">';
        $.each(question.Answers, function(a_index,answer_parts){
            var aparts = answer_parts.split(":");
            var answer_id = aparts[0];
            var answer = aparts[1];

            var letters = getAlphabet();//alphabet(a_index);
            var letter = letters[a_index];
            html_quiz += '<li><label class="radio">'
                    +'<input type="radio" class="choicefor_'+q_number+'" value="'+question_id+':'+answer_id+'" name="'+question_id;
                    if(question.Type == "multi-answer"){
                        html_quiz += letter;
                    }
                    html_quiz += '" />'
                    +letter.toUpperCase()+'. '+answer
                    +'</label></li>';
        });//end each answer
        if(question.Type == "multi-answer"){
            html_quiz += '<li><a class="btn btn-primary q_choice_reset" id="reset_'+q_number+'">Reset Answers</a></li>';
        }
        html_quiz += '</ul>'
        +'</li>';

        //if this is the last question in the chunk
        if((key+1)%chunk_question_count == 0){
            html_quiz+='<span class="last_chunk_question"></span>';
        }
    });//end each question
    html_quiz += '</ol>'
                +'<a href="#" class="next_chunk btn btn-primary">Next</a>'
                +'<br><br><img src="../ajx/test_loader.gif" style="display:none;"/><br>'
                +'<a id="q_submit" class="btn btn-primary" style="display:none;">Submit</a>'
                +'</form>';
    
    $(div).html(html_quiz);
    
    $("#loader").hide();
    //if last chunk of quiz
    if(chunk_number==chunks_count){
        $(".next_chunk").hide();
        $("#q_submit").show();
    }
    $(".last_chunk_question").nextAll("li").hide();
    
    $(".next_chunk").click(function(){
       showNextChunk(); 
    });
    $("#q_submit").click(function(){
        $("#loader_quiz_finish").show();
        $.post("ajx/ps.php", {'aq':$("#quiz_id").serialize(), 'name':$("#tester_name").html(), 'email':$("#tester_email").html()})
        .done(function(resp){
            if(resp.code == 1){
                $(".quiz_complete_modal").modal("show");
            }
            else{
                $("#loader_quiz_finish").hide();
                $.each(resp.incorrect_answers, function(k,v){
                    showThenFade("#error_marker_"+v);
                });
                $.each(resp.correct_answers, function(k,v){
                    $("#error_marker_"+v).hide();
                });
            }
        });
    });
    $(".q_choice_reset").click(function(){
        var idiv = $(this).attr('id');
        var parts = idiv.split('_');
        var numbr = parts[1];
        $(".choicefor_"+numbr).removeAttr("checked");
    });
}

/* FUNCTIONS FOR MULTIPLE QUIZZES
 * 
 * function loadTestSelection(div){
    var screen = '<h2>Select a test:</h2><form id="test_selection_form"><input type="hidden" name="test_selection" value="1"><ul>';
    var tests = ["Guest Passes","BeWell/PE/Recreation/HIP Classes/Personal Training","Financial Transactions","Computer Tasks"
                ,"Miscellaneous Questions","RDS-Lenel","Cash Register"];
    $.each(tests, function(key,val){
       screen += '<li><h3><label class="radio">'
                    +'<input type="radio" name="test" value="'+val+'">'+val
                    +'</label></h3></li>';
    });
    screen += '</form></ul>'
            +'<span id="choice_error_label" class="hide label label-important">There was an error selecting your test. Please try again.</span>'
            +'<br><a class="btn btn-stanford" id="test_choice_submit">Proceed</a>';
    $(div).html(screen);
    $("#test_choice_submit").click(function(){
        $.post("../etests/t_w_serv.php", $("#test_selection_form").serialize())
        .done(function(resp){
            if(resp.code == 1){
                loadQuiz(resp.quiz, div);
            }
            else{
                showThenFade("#choice_error_label");
            }
        });
    });
}
function loadQuizzes(div){
    $(div).html("");
    $.get('../etests/t_w_serv.php?quizzes=true',function(data){
        $.each(data.quizzes, function(k,v){
            $(div).append('<li id="quiz_'+v.id+'"><a href="#" class="quiz_title">'+v.title+'</a></li>');
        });
    });
}
function loadQuizQuestions(id,div){
    $(div).html("");
    $.post('../etests/t_w_serv.php',{quiz_id:id})
    .done(function(response){
        $.each(response.questions,function(k,v){
            $(div).append("<li>"+v.question+"</li>");
        });
    });
}*/