<?php
$link = mysql_connect('208.179.186.20  3306', 'pbgc', 'aristocracy')
   or die('Could not connect');
echo 'Connected successfully';
mysql_select_db('pensiontsunami', $link) or die('Could not select database');

$sql_1 = "ALTER TABLE options ADD COLUMN value_new text";
$res = mysql_query($sql_1, $link);
if($res) echo "Added new column<br />";
else
{
      echo "No column added";
      exit;
}
$sql_2 = "UPDATE options SET value_new = value";
$res = mysql_query($sql_2, $link);
if($res) echo "Setting new column<br />";
else
{
      echo "No column set";
      exit;
}
$sql_3 = "ALTER TABLE options DROP COLUMN value";
$res = mysql_query($sql_3, $link);
if($res) echo "Dropping old column<br />";
else
{
      echo "No column dropped";
      exit;
}

$sql_4 = "ALTER TABLE options CHANGE value_new value text";
$res = mysql_query($sql_4, $link);
if($res) echo "Renamed new column<br />";
else
{
      echo "No column renamed";
      exit;
}
?>
