<?php
$link = mysql_connect('208.179.186.20  3306', 'pbgc', 'aristocracy')
   or die('Could not connect');
echo 'Connected successfully';
mysql_select_db('pensiontsunami', $link) or die('Could not select database');
//the sql statement

$sql =  "UPDATE state SET state = 'Hawaii' WHERE state_id = 17";

echo $sql;
$res = mysql_query($sql, $link);
mysql_error();
if($res) echo "yay!";
else echo "nay!";
mysql_close($link);

?>
