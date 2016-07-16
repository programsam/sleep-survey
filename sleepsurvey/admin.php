<?php

if ($_POST['password'] == "booger")
{
	setcookie("sleepsurvey", "weregood");
	
	header("Location: admin.php");
	exit;
}

if ($_COOKIE['sleepsurvey'] != "weregood")
{
?>
<form method="post">
Password: <input type=password name=password />
<input type=Submit />
</form>
<?php

exit;
}

if (!@mysql_connect("localhost", "root", "h0m3pl4t3")) {
		echo "<h2>Could not connect to mySQL</h2>";
		die;
	}
	if (mysql_select_db("sleepdata") == 0)
	{
		print "<h2>Could not select sleep data database</h2>";
		die;
	}

$result = mysql_query("select count(*) as number from responses");
$row = mysql_fetch_array($result);

echo "Responses Collected, n=" . $row['number'] . "<br />";
$total = $row['number'];

$result = mysql_query("select count(*) as number from responses where session = 'fall';");
$row = mysql_fetch_array($result);

echo "Last semester: " . $row['number'] . "<br />";

$result = mysql_query("select count(*) as number from responses where session = 'spring';");
$row = mysql_fetch_array($result);

echo "This semester: " . $row['number'] . "<br />";


$result = mysql_query("select * from responses order by recorded desc");
$row = mysql_fetch_array($result);

echo "Latest Response: " . $row['recorded'];

echo "<hr />";
echo "<h2>Sample Averages</h2>";
echo "Age: " . avg("age") . "<br />";
echo "BMI: " . avg("bmi") . "<br />";
echo "Epworth Score: " . avg("epworth") . "<br />";
echo "Highschool GPA: " . avg("gpa") . "<br />";
echo "College GPA: " . avg("collegegpa") . "<br />";

echo "<hr />";
echo "<h2>Categorical Responses</h2>";
?>
<h3>Gender</h3>
<?php categorical("gender", $total); ?>

<h3>Do you smoke?</h3>
<?php categorical("smoke", $total); ?>

<h3>Do you drink?</h3>
<?php categorical("drink", $total); ?>

<h3>Have you ever been a car crash in which you were the driver?</h3>
<?php categorical("crash", $total); ?>

<h3>Work hours</h3>
<?php categorical("workhabit", $total); ?>

<h3>How many hours do you sleep per weeknight?</h3>
<?php categorical("weeknighthours", $total); ?>

<h3>How many hours do you sleep per weekend night?</h3>
<?php categorical("weekendhours", $total); ?>

<h3>How many hours do you nap per week?</h3>
<?php categorical("naphours", $total); ?>

<h3>How many hours do you exercise per week?</h3>
<?php categorical("exercisehours", $total); ?>

<h3>Moderate CDC Description</h3>
<?php categorical("moderatecdc", $total); ?>

<h3>Vigorous CDC Description</h3>
<?php categorical("vigorouscdc", $total); ?>

<h3>Combo CDC Description</h3>
<?php categorical("combocdc", $total); ?>

<h3>Hour of Day Submitted</h3>
<?php
	$result = mysql_query("SELECT  hour(timeofday) as category, (count(hour(timeofday))/$total)*100 as pcnt FROM `responses` group by hour(timeofday);");
	echo "<table border =1>";
	echo "<th>Category</th><th>Percentage (%)</th>";
	while ($row = mysql_fetch_array($result))
	{
		echo "<tr><td>" . $row['category'] . "</td><td>" . $row['pcnt'] . "</tr>";
	}
	echo "</table>";
?>

<?php
function categorical($field, $total)
{
	$result = mysql_query("select (count(*)/$total) * 100 as pcnt, $field as category from responses group by $field");
	echo "<table border =1>";
	echo "<th>Category</th><th>Percentage (%)</th>";
	while ($row = mysql_fetch_array($result))
	{
		echo "<tr><td>" . $row['category'] . "</td><td>" . $row['pcnt'] . "</tr>";
	}
	echo "</table>";
}

function avg($field)
{
	$result = mysql_query("select avg($field) as avg from responses");
	$row = mysql_fetch_array($result);
	
	return $row['avg'];
}
?>
