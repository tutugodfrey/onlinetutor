<?php 
if(isset($_GET["get_help"])) {
echo "<h1>Instructions for using myLecApp</h1>";
echo <<<block
<h2>Lecturers</h2>
<p>This application enable lecturer/student interaction in a very friendly and easy way.
lecturer can save the courses they are taking. when student register that course with the lecturer,
the student have access to lecture notes saved by the lecturer and other resources such as participate 
in a discussions, take tests and see immediate feedback from the test. <br /> The lecturer can save questions
against a particular course. The saved questions will be  basis for opening a test. only students who register for the 
course have access to the test. the same hold for discussions will the lecturer can open. The discussion can either b
be a opened discussion or a closed discussion. student particular in closed discussion are  not revealed to other
students. The lecturer can grade student participation in any kind of discussion</p>
block;

echo <<<block
<h2>Students</h2>
<p>
Every starts when a student register to use the app. From there a student can search for lecturers 
that have already register to use the app, register with the lecturer. after registering with a lecturer,
the student can see all courses the lecturer is taking and can register any course of interest.
The student only have access to resources for the courses the are registered to.<br />
The application make it possible for student to easily swith between lecturers and have access to the 
resources offerred by the lecturer for the courses they registered with the lecturer.<br />
Student can also see and be-friend other coursemates which will open the platform for students to 
chat.
</p>
block;
}
?>