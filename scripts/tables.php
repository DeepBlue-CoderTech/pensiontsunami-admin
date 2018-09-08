<?php
$link = mysql_connect('208.179.186.20  3306', 'pbgc', 'aristocracy')
   or die('Could not connect');
echo 'Connected successfully';
mysql_select_db('pensiontsunami', $link) or die('Could not select database');

$sql = "SHOW TABLES FROM pensiontsunami";
$result = mysql_query($sql);

if (!$result) {
   echo "DB Error, could not list tables\n";
   echo 'MySQL Error: ' . mysql_error();
   exit;
}

while ($row = mysql_fetch_row($result)) {
   echo "Table: {$row[0]}\n";
}

mysql_free_result($result);
?>
