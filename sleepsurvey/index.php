<?php

if (!@mysql_connect("localhost", "root", "h0m3pl4t3")) {
		echo "<h2>Could not connect to mySQL</h2>";
		die;
	}
	if (mysql_select_db("sleepdata") == 0)
	{
		print "<h2>Could not select sleep data database</h2>";
		die;
	}
if (isset($_POST['submitted']) && $_POST['submitted'] != 2)
{

	if ($_COOKIE['sleepsurvey'] == "taken")
		exit;

	$GLOBALS['errorlist'] = "Please do the following to complete the form:<br />" . $GLOBALS['errorlist'];
	
	checkSet("age", "Please enter a valid age.", "age");
	checkSet("gender", "Please select a gender.", "gender");
	checkSet("weeknighthours", "Please enter an average number of weeknight hours.", "wnighthours");
	checkSet("weekendhours", "Please enter an average number of weekend hours.", "weekendhours");
	checkSet("naphours", "Please enter a number of nap hours per week.", "naphours");
	checkSet("gpa", "Please enter a valid GPA.", "gpa");
	checkSet("collegegpa", "Please enter a valid GPA.", "collegegpa");
	checkSet("smoke", "Please select an answer regarding smoking.", "smoke");
	checkSet("drink", "Please select an answer regarding drinking.", "drink");
	checkSet("feet", "Please enter a number of feet for your height", "height");
	checkSet("inches", "Please enter a number of inches for your height.", "height");
	checkSet("weight", "Please enter a valid weight.", "weight");
	checkSet("exercisehours", "Please enter a valid number of exercise hours.", "exercisehours");
	checkSet("moderatecdc", "Please enter yes or no regarding the questions on exercise behavior.", "exercisehours");
	checkSet("vigorouscdc", "Please enter yes or no regarding the questions on exercise behavior.", "cdcexercise");
	checkSet("combocdc", "Please enter yes or no regarding the questions on exercise behavior.", "cdcexercise");
	checkSet("workhabit", "Please select a valid descriptor for your work habits.", "workhabit");
	checkSet("crash", "Please enter an answer about car crashes.", "crash");

	if (cshrt("sittingandreading") || cshrt("watchingtv") || cshrt("publicplace") || cshrt("carpassenger") || cshrt("lyingdown") || cshrt("sittingandtalking") || cshrt("quietlyafterlunch") || cshrt("stoppedatlight"))
	{
		$GLOBALS['errorlist'] .= "Please fill out <em>all</em> questions involving the likelihood of falling asleep in various situations.<br />";
		$GLOBALS['error'] = 1;
		$GLOBALS['divstyles']['epworth'] = "style='border-color: red;'";
	}
	
//	function checkValidNumber($field, $lower, $upper=null, $divname, $message)
	checkValidNumber("feet", 0, 12, "height", "Please enter a valid height between 0 and 12 feet.");
	checkValidNumber("inches", 0, 11, "height", "Please enter a valid height between 0 and 11 inches");
	checkValidNumber("gpa", 0, 5, "gpa", "Please enter a GPA between 0 and 4.0");
	checkValidNumber("collegegpa", 0, 5, "collegegpa", "Please enter a GPA between 0 and 4.0");
	checkValidNumber("weight", 0, 600, "weight", "Please enter a valid weight.");
	checkValidNumber("exercisehours", 0, 168, "exercise", "Please enter a valid number of exercise hours");
	checkValidNumber("age", 18, 130, "weeknighthours", "Please enter a valid age.");

	
	
	if ($GLOBALS['error'] != 1)
	{
		$age = $_POST['age'];
		$gender = $_POST['gender'];
		$weeknighthours = $_POST['weeknighthours'];
		$weekendhours = $_POST['weekendhours'];
		$naphours = $_POST['naphours'];
		$gpa = $_POST['gpa'];
		$collegegpa = $_POST['collegegpa'];
		$smoke = $_POST['smoke'];
		$drink = $_POST['drink'];
		$weight = $_POST['weight'];
		$exercisehours = $_POST['exercisehours'];
		$workhabit = $_POST['workhabit'];
		$crash = $_POST['crash'];
		$sittingandreading = $_POST['sittingandreading'];
		$watchingtv = $_POST['watchingtv'];
		$publicplace = $_POST['publicplace'];
		$carpassenger = $_POST['carpassenger'];
		$lyingdown = $_POST['lyingdown'];
		$sittingandtalking = $_POST['sittingandtalking'];
		$quietlyafterlunch = $_POST['quietlyafterlunch'];
		$stoppedatlight = $_POST['stoppedatlight'];
		$moderatecdc = $_POST['moderatecdc'];
		$vigorouscdc = $_POST['vigorouscdc'];
		$combocdc = $_POST['combocdc'];
	
		$epworth = $sittingandreading + $watchingtv + $publicplace + $carpassenger + $lyingdown + $sittingandtalking + $quietlyafterlunch + $stoppedatlight;
	
		$feet = $_POST['feet'];
		$inches = $_POST['inches'];
		$height = ($feet * 12) + $inches;
	
		$numerator = $weight * 703;
		$denominator = $height * $height;
		$bmi_raw = $numerator / $denominator;
		$bmi = number_format($bmi_raw, 2);
		
		//mysql_query("insert into responses (session, timeofday, epworth, bmi, age, gender, weeknighthours, weekendhours, naphours, gpa, collegegpa, smoke, drink, height, weight, exercisehours, moderatecdc, vigorouscdc, combocdc, workhabit, crash, sittingandreading, watchingtv, publicplace, carpassenger, lyingdown, sittingandtalking, quietlyafterlunch, stoppedatlight) values ('spring', CURRENT_TIME(), '$epworth', '$bmi', '$age', '$gender', '$weeknighthours', '$weekendhours', '$naphours', '$gpa', '$collegegpa', '$smoke', '$drink', '$height', '$weight', '$exercisehours', '$moderatecdc', '$vigorouscdc', '$combocdc', '$workhabit', '$crash', '$sittingandreading', '$watchingtv', '$publicplace', '$carpassenger', '$lyingdown', '$sittingandtalking', '$quietlyafterlunch', '$stoppedatlight')");
	
		//setcookie("sleepsurvey", "taken"); //turn this on before release
		$GLOBALS['error'] = 2;
	} //not in error state
	
	
}
	

function cshrt($field)
{
	return (! isset($_POST[$field]) || $_POST[$field] == "");
}

function checkValidNumber($field, $lower, $upper=null, $divname, $message)
{
	if ($upper == null)
	{
		if ($_POST[$field] < $lower)
		{
			$GLOBALS['errorlist'] .= $message . "<br />";
			$GLOBALS['error'] = 1;
			$GLOBALS['divstyles'][$divname] = "style='border-color: red;'";
		}
	}
	else
	{
		if ($_POST[$field] < $lower || $_POST[$field] > $upper)
		{
			$GLOBALS['errorlist'] .= $message . "<br />";
			$GLOBALS['error'] = 1;
			$GLOBALS['divstyles'][$divname] = "style='border-color: red;'";
		}
	}
}
	
	
function checkSet($field, $message, $divname)
{
	if (! isset($_POST[$field]) || $_POST[$field] == "")
	{
		$GLOBALS['errorlist'] .= $message . "<br />";
		$GLOBALS['error'] = 1;
		$GLOBALS['divstyles'][$divname] = "style='border-color: red;'";
	}
}

function isSelected($fieldname, $groupname=null)
{

if ($groupname == null)
{
	if ($_POST[$fieldname] == "on")
	{
		return "checked";
	}
}
else
{
	if ($_POST[$groupname] == $fieldname)
	{
		return "checked";
	}
} //else
} //function
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
<meta name="description" content="description"/>
<meta name="keywords" content="keywords"/> 
<meta name="author" content="author"/> 
<link rel="stylesheet" type="text/css" href="default.css"/>
<title>Sleep Survey</title>
</head>
<body>
<div class="main">
	<div class="gfx">
	<table border=0 width="100%">
	<tr>
	<td width="670px">
	
	<h1>Sleep Behaviors and Correlates in Young Adult College Students</h1>
</td>
	</tr>
	</table>
	</div>
	<div class="content">		
<form action="index.php" method="post">
<div class="item">
<?php
if ($GLOBALS['error'] == 1)
{
	echo "<font color='red'>" . $GLOBALS['errorlist'] . "</font>";
}
else if ($GLOBALS['error'] == 2)
{
?>
Thank you for completing the survey!

<?php
if ($epworth > 10)
{
?>
<p /><br />Your Epworth sleepiness scale score is greater than or equal to 10, which is suggestive of sleepiness. The most common cause of sleepiness among college-aged students is not sleeping enough.  <a href="http://www.sleepfoundation.org/article/sleep-related-problems/excessive-sleepiness-and-sleep">See here</a> for more information.
<?php
} else {
?>
Your Epworth sleepiness scale score is 9 or less, which is considered to be normal. Congratulations!
<?php } ?>
<p /><br />
<?php

if ($bmi < 18.5)
	$range = "underweight";
else if ($bmi >= 18.5 && $bmi < 25)
	$range = "normal";
else if ($bmi >= 25 && $bmi < 30)
	$range = "overweight";
else if ($bmi >= 30)
	$range = "obese";

echo "Your BMI is $bmi.  This is considered $range.  See <a href='http://www.nhlbi.nih.gov/health/public/heart/obesity/lose_wt/risk.htm'>NIH website</a> for more information.<P />";

if ($moderatecdc == "Y" || $vigorouscdc == "Y" || $combocdc == "Y")
	echo "Congratulations, you meet the CDC physical activity recommendations for adults.";
else
	echo "You do not meet the CDC daily exercise recommendations.";
	
echo " For more information, see <a href='http://www.cdc.gov/physicalactivity/everyone/guidelines/adults.html'>the CDC website</a>.";

?>
</div>
</div>
	<div class="footer">Created and Designed by <a href="http://bensmith.zapto.org/">Ben Smith</a>, Ellen Smith, and Barbara Phillips</div>
</div>
</body>
</html>
<?php
exit;
}
else
{


if ($_COOKIE['sleepsurvey'] == "taken")
{
?>

You have already taken this survey.
</div>
</div>
	<div class="footer">Created and Designed by <a href="http://bensmith.zapto.org/">Ben Smith</a>, Ellen Smith, and Barbara Phillips</div>
</div>
</body>
</html>

<?php
exit;
}
?>

<?php
if (! isset($_POST['submitted']))
{
?>

<h1>Indiana University Bloomington - Study Information Sheet</h1>
<h1>Study #1007001539</h1>
<p>You are invited to participate in a research study of sleep behaviors, lifestyle, and health outcomes in college students. You were selected as a possible subject because you are learning about sleep in your HPER T 142 class. We ask that you read this form and ask any questions you may have before agreeing to be in the study. </p>
<h3>STUDY PURPOSE</h3>
<p>The purpose of this study is to learn more about the patterns of sleep in college students, and to try to relate sleep schedules and sleep problems with other lifestyle and health issues.  </p>
<h3>NUMBER OF PEOPLE TAKING PART IN THE STUDY:</h3>
<p>If you agree to participate, you will be one of approximately 300 subjects who will be invited to participate in this research.</p>
<h3>PROCEDURES FOR THE STUDY:</h3>
<p>If you agree to be in the study, you will do the following things: 
Complete an anonymous survey about your sleep habits and health behaviors. The answers are completely anonymous, cannot be traced back to you, and cannot be used in any way to affect your grade for this course. We estimate that it will take about 3 minutes for you to complete the online survey. 
</p>
<h3>BENEFITS OF TAKING PART IN THE STUDY:</h3>
<p>The benefits to participation that are reasonable to expect are a better understanding of your own sleep and health. The survey instrument will assess and report to you your level of sleepiness, your body mass index, and your level of physical fitness. </p>
<h3>ALTERNATIVES TO TAKING PART IN THE STUDY:</h3>
<p>An alternative to participating in the study is to choose not to participate.</p>
<h3>CONFIDENTIALITY</h3>
<p>Efforts will be made to keep your personal information confidential.  We cannot guarantee absolute confidentiality.  Your personal information may be disclosed if required by law.  Your identity will be held in confidence in reports in which the study may be published. The online data will be used for analysis only, and will be destroyed within a year of your taking this survey. </p>
<p>Organizations that may inspect and/or copy your research records for quality assurance and data analysis include groups such as the study investigator and his/her research associates, the IUB Institutional Review Board or its designees,and (as allowed by law) state or federal agencies, specifically the Office for Human Research Protections (OHRP), who may need to access your research records.</p>
<h3>CONTACTS FOR QUESTIONS OR PROBLEMS</h3>
<p>For questions about the study or a research-related injury, contact the researcher Dr Barbara Phillips, 859-361-0927, <a href="mailto:rfphil1@uky.edu">rphil1@uky.edu</a>.</p>
<p>For questions about your rights as a research participant or to discuss problems, complaints or concerns about a research study, or to obtain information, or offer input, contact the IUB Human Subjects office, 530 E Kirkwood Ave, Carmichael Center, 203, Bloomington IN 47408, 812-855-3067 or by email at iub_hsc@indiana.edu</p>
<h3>VOLUNTARY NATURE OF STUDY</h3>
<P>Taking part in this study is voluntary.  You may choose not to take part or may leave the study at any time.  Leaving the study will not result in any penalty or loss of benefits to which you are entitled.  Your decision whether or not to participate in this study will not affect your current or future relations with the investigator(s).</p>
<h3>CONSENTING AGE</h3>
In order to take the survey, you must be 18 years of age.  Please click "Yes" below to indicate that you are a legal adult.<p />
<input type=hidden name=submitted value=2 />
<input type=Submit value="Yes, I am at least 18 years of age" />
<input type=Button value="No, I am a minor" onclick="location='http://www.google.com'" />
</form>
</div>
</div>
	<div class="footer">Created and Designed by <a href="http://bensmith.zapto.org/">Ben Smith</a>, Ellen Smith, and Barbara Phillips.</div>
</div>
</body>
</html>

<?php }

}

if ($_POST['submitted'] == 2 || $_POST['submitted'] == 1)
{
?>
You have indicated that you are of legal consenting age to participate in this study.
</div>
<input type=hidden name=submitted value=1 />

<div class="item" <?=$GLOBALS['divstyles']['age'] ?>>
	What is your age?<p /><br />
	<input type=text name=age size=2 autocomplete=off value="<?=$_POST['age']?>" />
</div>
<div class="item" <?=$GLOBALS['divstyles']['gender'] ?>>
	What is your gender?<p /><br />
	<input type=radio name=gender value="M" <?=isSelected("M", "gender"); ?>>Male<br />
	<input type=radio name=gender value="F" <?=isSelected("F", "gender"); ?>>Female
</div>
<div class="item" <?=$GLOBALS['divstyles']['wnighthours'] ?>>
	How many hours of sleep do you get on average per week night?<p /><br />
	<input type=radio name=weeknighthours value="less4" <?=isSelected("less4", "weeknighthours"); ?>> less than 4<br />
	<input type=radio name=weeknighthours value="4" <?=isSelected("4", "weeknighthours"); ?>> 4 <br />
	<input type=radio name=weeknighthours value="5" <?=isSelected("5", "weeknighthours"); ?>> 5 <br />
	<input type=radio name=weeknighthours value="6" <?=isSelected("6", "weeknighthours"); ?>> 6 <br />
	<input type=radio name=weeknighthours value="7" <?=isSelected("7", "weeknighthours"); ?>> 7 <br />
	<input type=radio name=weeknighthours value="8" <?=isSelected("8", "weeknighthours"); ?>> 8 <br />
	<input type=radio name=weeknighthours value="9" <?=isSelected("9", "weeknighthours"); ?>> 9 <br />
	<input type=radio name=weeknighthours value="great9" <?=isSelected("great9", "weeknighthours"); ?>> more than 9
</div>
<div class="item" <?=$GLOBALS['divstyles']['weekendhours'] ?>>
	How many hours of sleep do you get on average per weekend night?<p /><br />
	<input type=radio name=weekendhours value="less4" <?=isSelected("less4", "weekendhours"); ?>> less than 4<br />
	<input type=radio name=weekendhours value="4" <?=isSelected("4", "weekendhours"); ?>> 4 <br />
	<input type=radio name=weekendhours value="5" <?=isSelected("5", "weekendhours"); ?>> 5 <br />
	<input type=radio name=weekendhours value="6" <?=isSelected("6", "weekendhours"); ?>> 6 <br />
	<input type=radio name=weekendhours value="7" <?=isSelected("7", "weekendhours"); ?>> 7 <br />
	<input type=radio name=weekendhours value="8" <?=isSelected("8", "weekendhours"); ?>> 8 <br />
	<input type=radio name=weekendhours value="9" <?=isSelected("9", "weekendhours"); ?>> 9 <br />
	<input type=radio name=weekendhours value="great9" <?=isSelected("great9", "weekendhours"); ?>> more than 9
</div>
<div class="item" <?=$GLOBALS['divstyles']['naphours'] ?>>
	How many hours do you nap per week?<p /><br />
	<input type=radio name=naphours value="dontnap" <?=isSelected("dontnap", "naphours"); ?>> I don't nap<br />
	<input type=radio name=naphours value="less2" <?=isSelected("less2", "naphours"); ?>> less than two <br />
	<input type=radio name=naphours value="2" <?=isSelected("2", "naphours"); ?>> 2 <br />
	<input type=radio name=naphours value="3" <?=isSelected("3", "naphours"); ?>> 3 <br />
	<input type=radio name=naphours value="4" <?=isSelected("4", "naphours"); ?>> 4 	<br />
	<input type=radio name=naphours value="5" <?=isSelected("5", "naphours"); ?>> 5 <br />
	<input type=radio name=naphours value="6" <?=isSelected("6", "naphours"); ?>> 6 	<br />
	<input type=radio name=naphours value="great6" <?=isSelected("great6", "naphours"); ?>> more than 6
</div>
<div class="item" <?=$GLOBALS['divstyles']['gpa'] ?>>
	Please estimate your high school GPA:<p /><br />
	<input type=text name=gpa size=2 autocomplete=off value="<?=$_POST['gpa']?>" />
</div>
<div class="item" <?=$GLOBALS['divstyles']['collegegpa'] ?>>
	Please estimate your current cumulative college GPA:<p /><br />
	<input type=text name=collegegpa size=2 autocomplete=off value="<?=$_POST['collegegpa']?>" />
</div>
<div class="item" <?=$GLOBALS['divstyles']['smoke'] ?>>
Do you smoke cigarettes?<p /><br />
<input type=radio name=smoke value="Y" <?=isSelected("Y", "smoke"); ?>>Yes<br />
<input type=radio name=smoke value="N" <?=isSelected("N", "smoke"); ?>>No

</div>
<div class="item" <?=$GLOBALS['divstyles']['drink'] ?>>
Which of the following best describes your alcohol consumption:<p /><br />
<input type=radio name=drink value="lifeabstain" <?=isSelected("lifeabstain", "drink"); ?>> fewer than 12 drinks in lifetime<br />
<input type=radio name=drink value="formdrink" <?=isSelected("formdrink", "drink"); ?>> no drinks in the past year<br />
<input type=radio name=drink value="current" <?=isSelected("current", "drink"); ?>> one or more drinks in the past year<br />
<input type=radio name=drink value="abstain" <?=isSelected("abstain", "drink"); ?>> fewer than 12 drinks in lifetime or no drinks in the past year<br />
<input type=radio name=drink value="light" <?=isSelected("light", "drink"); ?>> on average, 3 or fewer drinks per week in the past year<br />
<input type=radio name=drink value="moderate" <?=isSelected("moderate", "drink"); ?>> on average, more than 3 drinks but no more than 7 drinks per week for women or no more than 14 drinks per week for men in the past year.<br />
<input type=radio name=drink value="heavy" <?=isSelected("heavy", "drink"); ?>> on average, more than one drink per day for women or more than two drinks per day for men in the past year<br />

</div>
<div class="item" <?=$GLOBALS['divstyles']['height'] ?>>
What is your height?<p /><br />
<input type=text name=feet size=2 autocomplete=off value="<?=$_POST['feet']?>" /> ft. <input type=text name=inches autocomplete=off size=2 value="<?=$_POST['inches']?>" /> in.
</div>
<div class="item" <?=$GLOBALS['divstyles']['weight'] ?>>
What is your weight?<p /><br />
<input type=text name=weight size=2 autocomplete=off value="<?=$_POST['weight']?>"/> lbs
</div>
<div class="item" <?=$GLOBALS['divstyles']['exercisehours'] ?>>
How many hours a week do you spend exercising?<p /><br />
	<input type=radio name=exercisehours value="less1" <?=isSelected("less1", "exercisehours"); ?>> less than 1<br />
	<input type=radio name=exercisehours value="1to3" <?=isSelected("1to3", "exercisehours"); ?>> 1-2.99 <br />
	<input type=radio name=exercisehours value="3to6" <?=isSelected("3to6", "exercisehours"); ?>> 3-5.99 <br />
	<input type=radio name=exercisehours value="great6" <?=isSelected("6more", "exercisehours"); ?>> 6 or more <br />
</div>
<div class="item" <?=$GLOBALS['divstyles']['cdcexercise'] ?>>
Does your exercise behavior meet or exceed any of the following three categories:<p /><br />

2 hours and 30 minutes (150 minutes) of moderate-intensity aerobic activity (i.e., brisk walking) every week <strong>and</strong> muscle-strengthening activities on 2 or more days a week that work all major muscle groups (legs, hips, back, abdomen, chest, shoulders, and arms).<p />
	<input type=radio name=moderatecdc value="Y" <?=isSelected("Y", "moderatecdc"); ?>> Yes<br />
	<input type=radio name=moderatecdc value="N" <?=isSelected("N", "moderatecdc"); ?>> No <br /><p />
	
1 hour and 15 minutes (75 minutes) of vigorous-intensity aerobic activity (i.e., jogging or running) every week <strong>and</strong> muscle-strengthening activities on 2 or more days a week that work all major muscle groups (legs, hips, back, abdomen, chest, shoulders, and arms).<p />
	<input type=radio name=vigorouscdc value="Y" <?=isSelected("Y", "vigorouscdc"); ?>> Yes<br />
	<input type=radio name=vigorouscdc value="N" <?=isSelected("N", "vigorouscdc"); ?>> No<br /><p />
	
An equivalent mix of moderate- and vigorous- intesnity aerobic activity <strong>and</strong> muscle-strengthening activities on 2 or more days a week that work all major muscle groups (legs, hips, back, abdomen, chest, shoulders, and arms).<p />
	<input type=radio name=combocdc value="Y" <?=isSelected("Y", "combocdc"); ?>> Yes <br />
	<input type=radio name=combocdc value="N" <?=isSelected("N", 
	"combocdc"); ?>> No <br />
	
	
</div>
<div class="item" <?=$GLOBALS['divstyles']['workhabit'] ?>>
Please select an option from below that best describes you:<p /><br />
<input type=radio name=workhabit value="none" <?=isSelected("none", "workhabit"); ?>> I do not work or volunteer.<br />
<input type=radio name=workhabit value="lessthan20" <?=isSelected("lessthan20", "workhabit"); ?>> I work or volunteer in addition to my studies, but for less than 20 hours per week.<br />
<input type=radio name=workhabit value="morethan20" <?=isSelected("morethan20", "workhabit"); ?>> I work or volunteer more than 20 hours per week in addition to my studies.<p />
</div>
<div class="item" <?=$GLOBALS['divstyles']['crash'] ?>>
Have you had a car crash in which you were the driver?<p /><br />
<input type=radio name=crash value="Y" <?=isSelected("Y", "crash"); ?>> Yes<br />
<input type=radio name=crash value="N" <?=isSelected("N", "crash"); ?>> No

</div>
<div class="item" <?=$GLOBALS['divstyles']['epworth'] ?>>
How likely are you to doze off or fall asleep in the following situations, in contrast to just feeling tired? This refers to your usual way of life in recent times. Even if you have not done some of these things recently, try to work out how they would have affected you.<p />

Use the following scale to chose the most appropriate number for each situation:<p /><br />
0=would <em>never</em> doze<br />
1=<em>slight</em> chance of dozing<br />
2=<em>moderate</em> chance of dozing<br />
3=<em>high</em> chance of dozing<p />

<table border=0 width="100%">
<tr style="background: #DDDDDD">
<td>Sitting and reading</td>
<td><input type=radio name=sittingandreading value="0" <?=isSelected("0", "sittingandreading"); ?>> 0</td>
<td><input type=radio name=sittingandreading value="1" <?=isSelected("1", "sittingandreading"); ?>> 1</td>
<td><input type=radio name=sittingandreading value="2" <?=isSelected("2", "sittingandreading"); ?>> 2</td>
<td><input type=radio name=sittingandreading value="3" <?=isSelected("3", "sittingandreading"); ?>> 3</td>
</tr>
<tr>
<td>Watching TV</td>
<td><input type=radio name=watchingtv value="0" <?=isSelected("0", "watchingtv"); ?>> 0</td>
<td><input type=radio name=watchingtv value="1" <?=isSelected("1", "watchingtv"); ?>> 1</td>
<td><input type=radio name=watchingtv value="2" <?=isSelected("2", "watchingtv"); ?>> 2</td>
<td><input type=radio name=watchingtv value="3" <?=isSelected("3", "watchingtv"); ?>> 3</td>
</tr>
<tr style="background: #DDDDDD">
<td>Sitting, inactive, in a public place</td>
<td><input type=radio name=publicplace value="0" <?=isSelected("0", "publicplace"); ?>> 0</td>
<td><input type=radio name=publicplace value="1" <?=isSelected("1", "publicplace"); ?>> 1</td>
<td><input type=radio name=publicplace value="2" <?=isSelected("2", "publicplace"); ?>> 2</td>
<td><input type=radio name=publicplace value="3" <?=isSelected("3", "publicplace"); ?>> 3</td>
</tr>
<tr>
<td>As a passenger in a car for an hour</td>
<td><input type=radio name=carpassenger value="0" <?=isSelected("0", "carpassenger"); ?>> 0</td>
<td><input type=radio name=carpassenger value="1" <?=isSelected("1", "carpassenger"); ?>> 1</td>
<td><input type=radio name=carpassenger value="2" <?=isSelected("2", "carpassenger"); ?>> 2</td>
<td><input type=radio name=carpassenger value="3" <?=isSelected("3", "carpassenger"); ?>> 3</td>
</tr>
<tr style="background: #DDDDDD">
<td >Lying down in the afternoon</td>
<td><input type=radio name=lyingdown value="0" <?=isSelected("0", "lyingdown"); ?>> 0</td>
<td><input type=radio name=lyingdown value="1" <?=isSelected("1", "lyingdown"); ?>> 1</td>
<td><input type=radio name=lyingdown value="2" <?=isSelected("2", "lyingdown"); ?>> 2</td>
<td><input type=radio name=lyingdown value="3" <?=isSelected("3", "lyingdown"); ?>> 3</td>
</tr>
<tr>
<td>Sitting and talking to someone</td>
<td><input type=radio name=sittingandtalking value="0" <?=isSelected("0", "sittingandtalking"); ?>> 0</td>
<td><input type=radio name=sittingandtalking value="1" <?=isSelected("1", "sittingandtalking"); ?>> 1</td>
<td><input type=radio name=sittingandtalking value="2" <?=isSelected("2", "sittingandtalking"); ?>> 2</td>
<td><input type=radio name=sittingandtalking value="3" <?=isSelected("3", "sittingandtalking"); ?>> 3</td>
</tr>
<tr  style="background: #DDDDDD">
<td>Sitting quietly after a lunch without alcohol</td>
<td><input type=radio name=quietlyafterlunch value="0" <?=isSelected("0", "quietlyafterlunch"); ?>> 0</td>
<td><input type=radio name=quietlyafterlunch value="1" <?=isSelected("1", "quietlyafterlunch"); ?>> 1</td>
<td><input type=radio name=quietlyafterlunch value="2" <?=isSelected("2", "quietlyafterlunch"); ?>> 2</td>
<td><input type=radio name=quietlyafterlunch value="3" <?=isSelected("3", "quietlyafterlunch"); ?>> 3</td>
</tr>
<tr>
<td>In a car, while stopped for a few minutes in traffic</td>
<td><input type=radio name=stoppedatlight value="0" <?=isSelected("0", "stoppedatlight"); ?>> 0</td>
<td><input type=radio name=stoppedatlight value="1" <?=isSelected("1", "stoppedatlight"); ?>> 1</td>
<td><input type=radio name=stoppedatlight value="2" <?=isSelected("2", "stoppedatlight"); ?>> 2</td>
<td><input type=radio name=stoppedatlight value="3" <?=isSelected("3", "stoppedatlight"); ?>> 3</td>
</tr>

</table>


</div>
<div class="item">
<input type=Submit value="Submit" />
</form>
</div>
</div>
	<div class="footer">Created and Designed by <a href="http://bensmith.zapto.org/">Ben Smith</a>, Ellen Smith, and Barbara Phillips</div>
</div>
</body>
</html>
<?php
}
?>
