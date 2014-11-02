BACKGROUND:
Hey there. It's Mikos J here. I'm just deciding that this will be my first open-source project. I was originally given the task to create a testing platform for a world-class university(dunno if I'm allowed to say which one), and I came up with this. Keep in mind that this was coded without any prior experience in coding similar software. I knew the logic behind a testing platform, so it wasn't too hard to code. Please bear with my spaghetti code here, because as of the first initial commit, it wasn't done using a framework. I may later redo this in a js framework. It does utilize bootstrap 3.

PREREQS:
As of the first commit, the only things you'll need is a database set up with the following shcema:
TABLES:
-questions
 ---COLUMNS:
    -id(INT)
    -type(VARCHAR(15))**this may vary depending on question types
    -question(VARCHAR(200))**could also vary, but it's unlikely a question will be longer than 200 characters...but if you have story problems, then you'll probably want to extend this or use a different column type.
-answers
 ---COLUMNS:
    -id(INT)
    -question_id(INT)
    -answer(VARCHAR(200)) **varies, based on answer length
    -is_correct(TINYINT(1)) --of course this is binary

After you get this set up, you'll then need to manually add the questions & answers. I'll update this to include a way to add questions & answers in the next push. I'm honestly so anxious to commit my first ever open-source project, so that's why I'm waiting til then to add that! haha

HOW IT WORKS:
It divides the quiz up into 10 question chunks at a time. The person taking the quiz must answer the currently-shown 10 questions correctly before they can advance to the next chunk. Upon completion, it sends a completion email to the person taking the quiz, along with additional recipients specified by the developer. When a person gets an answer wrong, the label is briefly shown on the incorrectly answered question, then fades away(this can be altered in the js file).)

USAGE:
After you've added the questions & answers to the database, simply plug in your database credentials into the 'fxn/fxn.php' file. You'd also want to update the 'sendQuizCompletionEmail()' function with additional email recipients when the quiz is completed. Out of the box, it only sends the completion email to the person that took the exam.

CONFIGURABLES:
-Question chunk count: the number of questions shown per chunk
-Email recipients upon quiz completion

Any other questions, shoot me an email at mikos@kostocoastdev.com
Thanks!
