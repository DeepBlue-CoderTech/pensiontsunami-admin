<html>
  <head>
    <title>What's what</title>
  </head>
  <body>
<?php
// Connecting, selecting database
$hd = mysql_connect('208.179.186.20  3306', 'pbgc', 'aristocracy')
   or die('Could not connect');
echo 'Connected successfully';
mysql_select_db('pensiontsunami', $hd) or die('Could not select database');

// Performing SQL query
$query = "INSERT INTO links (title, link, date, source, author, category, state, county, city, status, posttime, body, email, comment) VALUES ('ol0ll', 'uluil', '05-07-12' , 'uilui', 'luiluil', 1, , , , 1, 1121221187, 'uiluil', 1, 'uil')";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

// Printing results in HTML
echo "<table>\n";
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
   echo "\t<tr>\n";
   foreach ($line as $col_value) {
       echo "\t\t<td>$col_value</td>\n";
   }
   echo "\t</tr>\n";
}
echo "</table>\n";

// Closing connection
mysql_close($hd);
?>
  </body>
</html>
