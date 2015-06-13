UPDATE V.1.0
	-rebuilt with AngularJS
	-updated with constants file
	-more configurable code via angular variables

DISCLAIMER:
This is NOT a software testing package(unit,functional,etc.). This testing software is meant for question-answer testing. Yes, that QA, not the other one.

BACKGROUND:
-If you have a team of employees you need to quiz, this platform is perfect. Uses a custom implementation of angularui bootstrap, configured to:
	-include an optional forward-only pager(removes the 'previous' button on the pager)
	-include an optional callback at the end of the quiz(on the last page)
	-include optional button text on the final submit button for the quiz
-Includes both a minified and full version of the custom angularui script.

CONFIGURABLES(Angular file(quizApp.js)):
-Progress message($scope.progress_msg)[DEFAULT=''] - optional progress message to display on the progress bar(can inform the tester of their progress)
-Questions/page($scope.questions_per_page)[DEFAULT = 5]
(index page[tpl_page_index.php])
-forward-only=[DEFAULT="true"] - Declared on the 'pager' element to create a forward-only quiz, which prevents the user from navigating backwards. As of now, this is the only way the quizzes will work. In the future there may be an update to accomodate backwards navigation, but feel free to manipulate it yourself and commit. If this is set to false or removed, navigating backwards currently throws everything off, including the progress bar.
-complete-text[DEFAULT="Complete"] - Declared on the 'pager' element to specify text displayed on the final 'submit' button of the quiz.
-complete-callback[DEFAULT="nextChunk"] - Declared on the 'pager' element to specify an optional callback upon quiz completion. This is only reached on the last page of the quiz.

PREREQS:
You'll need a database set up with the following schema:
TABLES:
-questions
 ---COLUMNS:
    -id(INT)
    -type(VARCHAR(15))**this may vary depending on question types
    (3 compatible question types: 
    	-'single-answer'
    	-'true-false'
    	-'multi-answer'
    For 'single-answer', answers in db can only have 1 'is_correct' value set per 'question_id'. It'll return the first instance of 'is_correct' if multiple correct answers. If you need multiple correct answers for a question, please use 'multi-answer' type. Then you can have multiple 'is_correct' values per 'question_id' in the answers db.)
    -question(VARCHAR(200))**could also vary, but it's unlikely a question will be longer than 200 characters...but if you have story problems, then you'll probably want to extend this or use a different column type.
-answers
 ---COLUMNS:
    -id(INT)
    -question_id(INT)
    -answer(VARCHAR(250)) **varies, based on answer length
    -is_correct(TINYINT(1)) --of course this is binary

After you get this set up, you'll then need to manually add the questions & answers. I'll update this to include a way to add questions & answers in a future revision.

HOW IT WORKS:
It divides the quiz up into based on configured questions per page. The person taking the quiz must answer the currently-shown questions correctly before they can advance to the next chunk. Upon completion, you can configure it to do what you want in the 'ajx/ps.php' file(send a completion email, etc.).

USAGE:
After you've added the questions & answers to the database, simply plug in your database credentials into the 'fxn/fxn.php' file. You'd also want to update the 'sendQuizCompletionEmail()' function with additional email recipients when the quiz is completed. Out of the box, it only sends the completion email to the person that took the exam.

Any other questions, shoot me an email at mikos@kostocoastdev.com
Thanks!
