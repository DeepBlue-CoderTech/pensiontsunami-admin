<?php
$link = mysql_connect('208.179.186.20  3306', 'pbgc', 'aristocracy')
   or die('Could not connect');
echo 'Connected successfully';
mysql_select_db('pensiontsunami', $link) or die('Could not select database');
//the sql statement

$sql = "CREATE TABLE ads (ad_id int(8) NOT NULL auto_increment,
							name varchar(100) NOT NULL,
							expiry DATETIME NOT NULL,
							image text NOT NULL,
							link text NOT NULL,
							description varchar(200),
							PRIMARY KEY (ad_id))";
							

/*$sql =  "CREATE TABLE options(
   options_id int(8) NOT NULL auto_increment,
   name varchar(50) NOT NULL,
   description text,
   value varchar(150) NOT NULL,
   PRIMARY KEY (options_id)
)";
*/

$res = mysql_query($sql, $link);
if($res) echo "yay!";
else echo "nay!";
mysql_close($link);

?>
