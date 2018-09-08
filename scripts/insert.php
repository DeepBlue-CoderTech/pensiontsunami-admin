<?php
$link = mysql_connect('208.179.186.20  3306', 'pbgc', 'aristocracy')
   or die('Could not connect');
echo 'Connected successfully';
mysql_select_db('pensiontsunami', $link) or die('Could not select database');
//the sql statement
echo "<br />";
//$sql = "DROP TABLE otpions";
//$sql =  "INSERT INTO options (name, value, description) VALUES ('numads', 5, 'How many ads do you want displayed on every page?')";
$sql = "INSERT INTO state (state, abbrev) VALUES ('Oregon', 'OR')";
//$sql = "SELECT * FROM links LIMIT 50, OFFSET 50";
echo $sql;
$res = mysql_query($sql, $link);
echo mysql_error();
echo "<br />";
if($res) echo "yay!";
else echo "nay!";
mysql_close($link);

?>
