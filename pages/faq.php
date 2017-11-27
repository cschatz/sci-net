<?php session_set_cookie_params (0); session_start(); ?>

<?php
$course = preg_replace("/\\D$/", "", $_SESSION['course']);
?>


<h1>Frequently Asked Questions</h1>

<div class="inset">

<div class="chunk">
<p>Here are some questions commonly asked about this course, along with their answers.</p>
</div>

<div class="chunk">
<div class="question">
<a onclick="Toggle(1)">How do you pronounce your name, and what can/should I call you?</a>
</div>
<div id="a1" class="answer_hidden">
<p>First, here's how to <b>spell</b> my name - notice that it starts with three 
consonants in a row:<br />
&nbsp;&nbsp;S C H A T Z</p>
<p>It is pronounced exactly like the word "shots".</p>
<p>Feel free to call me any of the following that you are comfortable with:
<ul>
<li>"Mr. Schatz"
<li>"Schatz"
<li>"Colin"
<li>"Dr. Schatz"
</ul>
</p>
<p>Things <b>NOT</b> to call me:
<ul>
<li>"Mister, um..." <br/>
(I will have your name learned within the first week or two of the semester,
along with about 100 other people's names. Please learn mine.)
<li>"Professor" or "Professor Schatz"<br/>
(Technically I'm not a Professor, and I prefer a slightly less formal tone anyway.)
<li>"Sir"<br/>
(This honestly just sounds weird to me. I think this may be more common in classrooms in some places, but
in the U.S. it's what you use in customer service environments: restaurants, stores, etc.)
</ul>
</p>
</div>
</div>

<div class="chunk">
<div class="question">
<a onclick="Toggle(2)">What is the late policy in this course?</a>
</div>
<div id="a2" class="answer_hidden">
<p>The late policy is explained in detail
<a href="handouts/<?=strtoupper($course)?>/<?=strtoupper($course)?>-H00-Syllabus.pdf" target="_blank">in the syllabus</a>. 
After reading it, please let me know if you have further questions.</p>
</div>
</div>

<div class="chunk">
<div class="question">
<a onclick="Toggle(9)">I got a 0 on my assignment. What do I do?</a>
</div>
<div id="a9" class="answer_hidden">
The regrade policy is explained in detail
<a href="handouts/<?=strtoupper($course)?>/<?=strtoupper($course)?>-H00-Syllabus.pdf" target="_blank">in the syllabus</a>. 
After reading it, please let me know if you have further questions.</p>
</div>
</div>


<div class="chunk">
<div class="question">
<a onclick="Toggle(3)">When do you answer your email?</a>
</div>
<div id="a3" class="answer_hidden">
<p>
  In general, I check my email frequently and I answer email questions
from current students as quickly as I can.
</p>
<p>I will <b>usually</b> respond to email actively during these times:
<ul>
<li>Monday&ndash;Friday, 10:00am&ndash;4:30pm
<li>Sunday&ndash;Thursday, 8:00pm&ndash;9:30pm
</ul>

<p>I will most likely <b>NOT</b> respond to email <b>between Friday
at 5:00pm and Sunday at 3:00pm</b>. 
</p>

<p><b>Important</b>: When it is very close (2-3 hours) to the deadline
      for a current assignment, I tend to get <b>many</b> requests for help
      at the same time! In general, you should never put off working on an assignment until the night it is due. If you do choose to do that, two things
to consider are:
<ul>
<li>I have two young children and probably go to sleep earlier than you do.
<li>When handling a flood of requests for help in the evening,
I prioritize my responses based upon how much evidence you provide that <b>you</b> have put thought and work into the problem before contacting me.
</ul>


</p>
</div>
</div>



   <?php if ($course == 'cs1') { ?>

<div class="chunk">
<div class="question">
<a onclick="Toggle(101)">How much work will I have to do in CS1?</a>
</div>
<div id="a101" class="answer_hidden">
<p>First, note that this is a college course, and the LPC student handbook
says you should expect to spend 1 to 3 hours outside for each hour spent
in class.</p>

<p>However, the question you probably want an answer to is:<br />
<i>How many hours will I need to spend on CS1 each week to succeed in the course?</i><br />
The answer to that question <b>varies greatly from person to person</b>, and
depends on a lot of things: how easily the material comes to you naturally,
whether you happen to have done programming before, how much you focus on
class when you are there, how often you miss class, and how much <b>practice</b>
you need to or choose to do to make sure new material is incorporated into what
you know and can do.</p>
<p>Realize that most of what you are doing in this course is
<b>developing a skill</b>, starting (for most of you) from not having any
of that skill at all. Think about what happens when someone starts playing
basketball, or learns a new musical instrument. Different things are hard or
easy for different people, but the best predictor of success is the sheer 
amount of time spent applying the skills you are trying to learn.</p>

<p>What's the bottom line? Depending on who you are, to have a satisfying
experience (and end up with a good grade), the amount of time you'll need
to spend outside of class could be anywhere from 1-2 hours a week up
to 14-15 hours a week.</p>

</div>
</div>

   <?php } ?>
