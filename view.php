<?php
//this file has some needed general functions
include("includes/includes.php");
$hd = mysql_connect('internal-db.s123893.gridserver.com', 'db123893_host', 'vE4habacAfrA')
   or die('Could not connect');
mysql_select_db('db123893_pensiontsunami', $hd) or die('Could not select database');

// check for approval or deletion
if(isset($_GET['action']))
{
      switch($_GET['action'])
      {
            case "delete":
                  $sql = "DELETE FROM links WHERE link_id = {$_GET['id']}";
                  break;
            case "approve":
                  $sql = "UPDATE links SET status = 1 WHERE link_id = {$_GET['id']}";
                  break;
            case "update":
                  // First we need to check if this was already emailed and if
                  // not, we need to send one
                  $date = date('y-m-d', strtotime($_POST['date']));
                  $sql = "UPDATE links SET
                        title = '" . mysql_real_escape_string($_POST['title']) . "',
                        link =  '" . mysql_real_escape_string($_POST['link']) . "',
                        date = '$date',
                        source = '" . mysql_real_escape_string($_POST['source']) ."',
                        author = '"  . mysql_real_escape_string($_POST['author']) ."',
                        state = {$_POST['state']},
                        county = {$_POST['county']},
                        city = {$_POST['city']},
                        status = {$_POST['status']},
                        posttime = NOW(),
                        body = '" . mysql_real_escape_string($_POST['body']) . "',
                        comment = '"  . mysql_real_escape_string($_POST['comment']) ."',
                        email = {$_POST['email']}
                        WHERE link_id = '" . $_GET['id'] ."'";
               //echo $sql;
               break;
      }
      $res = mysql_query($sql, $hd);
      if($_GET['action'] == 'delete' && $res) $message = 1;
      if($_GET['action'] == 'delete' && !$res) $message = 2;
      if($_GET['action'] == 'approve' && $res) $message = 3;
      if($_GET['action'] == 'approve' && !$res) $message = 4;
      if($_GET['action'] == 'update' && $res) $message = 5;
      if($_GET['action'] == 'update' && !$res) $message = 6;
      if($_GET['action'] == 'approve' && $res) $message = 7;
      if($_GET['action'] == 'apprive' && !$res) $message = 8;
}

?>
 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<title>View Links | Pension Tsuanami: Content Manager</title>
	<style type="text/css" media="screen">@import url(stylesheets/admin.css);</style>
	<style type="text/css" media="screen">@import url(stylesheets/form.css);</style>
</head>

<body>
<div id="container">
	<div id="header">
	<h1>Pension Tsunami Custom Content Manager</h1>
   <?php gen_header() ?>
	</div>
	<div id="content">
	<?php 
      if(isset($message))
	{
            switch($message)
            {
                  case "1":
                        $message = 'The entry has been deleted.';
                        break;
                   case "2":
                        $message = 'The entry could not be deleted.  Contact Mike.';
                        break;
                   case "3":
                        $message = 'The entry was approved for publishing.';
                        break;
                  case "4":
                        $message = 'The entry could not be approved for publishing.  Contact Mike.';
                        break;
                  case "5":
                        $message = 'The entry was successfully updated.';
                        break;
                  case "6":
                        $message = 'Updating the entry failed.  Contact Mike.';
                        break;
                  case "7":
                        $message = 'Approved the entry.';
                        break;
                  case "8":
                        $message = 'Approving the entry failed.  Contact Mike.';
                        break;
            }
            echo "<p class=\"message\">" . $message ."</p>";
	} 
	?>
<?php
    // First we need to do "pages"
    $res_count = mysql_query("SELECT count(*) FROM links", $hd);
    $numarts = mysql_result($res_count,0,0);
    $recsperpage = 50;
    $pages = intval(ceil($numarts / $recsperpage));
    if(isset($_GET['page']))
    {
        $curpage = intval($_GET['page']);
    }
    else
    {
        $curpage = 1;
    }
	$offset = ($curpage - 1) * 50;
	$begin = $offset;
	$end = $offset + 50;
    // Now onto the grabbing of the data
    if($offset != 0)    $sql = "SELECT * FROM links ORDER BY posttime DESC LIMIT $begin, $end";
    else  $sql = "SELECT * FROM links ORDER BY posttime DESC LIMIT 50";
    //echo $sql; 
	$res = mysql_query($sql);
    // Start table prinout
    echo "<table width=\"100%\" cellpadding=\"3\" cellspacing=\"3\">\n";
    echo "<tr>";
    // Label the columns
    echo "<th scope=\"col\">ID</th>\n";
    echo "<th scope=\"col\">Title/Link</th>\n";
    echo "<th scope=\"col\">Date</th>\n";
    echo "<th scope=\"col\">Source</th>";
    echo "<th scope=\"col\">Author</th>";
    echo "<th scope=\"col\">Category</th>";
    echo "<th scope=\"col\">State</th>";
    echo "<th scope=\"col\">County</th>";
    echo "<th scope=\"col\">City</th>";
    echo "<th scope=\"col\">Published?</th>";
    echo "<th scope=\"col\">Email?</th>";
    echo "<th scope=\"col\">Edit</th>";
    echo "<th scope=\"col\">Approve</th>";
    echo "</tr>";
      // Now we print out the data
      $count = 0; // need to alternate the colors of the columns

      // Need this for the delete link
      if($_GET['page'] != '') $page = $_GET['page'];
      else $page = 1;
      
      while ($link = mysql_fetch_array($res, MYSQL_ASSOC))
      {
            if($count%2 === 0) $background = 'f4f4f4';
            else $background = 'ffffff';
            // Have to convert dates to something pretty
            //$posted = date('n/j', strtotime($link['posttime']));
            $date = date('n/j', strtotime($link['date']));
            // We only store states, etc as numbers, so we need to retrieve their respective names
            // We only need to do this if it is public pensions
            $cat = intval($link['category']);
            if($cat === 1)
            {
                  if($link['state'] <> 0) $state = getName($link['state'], 'state');
                  if($link['county'] <> 0) $county = getName($link['county'], 'counties');
                  if($link['city'] <> 0) $city = getName($link['city'], 'cities');
                  $category = 'Public';
            }
            else
            {
                  $category = getName($link['category'], 'category');
                  $state = '';
                  $county = '';
                  $city = '';
            }
            // Translate the booleans
            if($link['status'] == 0) $publish = 'No';
            else $publish = '<img src="images/check.gif" border=\"0\" />';
            if($link['email'] == 0) $email = 'No';
            else $email = '<img src="images/check.gif" border=\"0\" />';
            
              // Begin row printout
            echo "<tr bgcolor=\"#". $background ."\">\n";
            echo "<td>". $link['link_id'] ."</td>\n";
            echo "<td><a href=\"" . $link['link'] . "\">". stripslashes($link['title']) ."</a></td>\n";
            echo "<td>". $date ."</td>\n";
            echo "<td>". stripslashes($link['source']) ."</td>\n";
            echo "<td>". stripslashes($link['author']) ."</td>\n";
            echo "<td>". $category ."</td>\n";
            echo "<td>". $state ."</td>\n";
            echo "<td>". $county ."</td>\n";
            echo "<td>". $city ."</td>\n";
            echo "<td align=\"center\">". $publish ."</td>\n";
            echo "<td align=\"center\">". $email ."</td>\n";
            echo "<td><a href=\"edit.php?type=link&id=". $link['link_id'] ."\" title=\"Edit this entry\"><img src=\"images/edit.gif\" border=\"0\" hspace=\"3\" /></a><a title=\"Delete this entry\" href=\"view.php?page=" . $page ."&action=delete&id=".  $link['link_id'] . "\" onClick=\"return confirm('Are you sure you want to delete this?')\"><img src=\"images/delete.gif\" border=\"0\" /></a></td>\n";
            if($link['status'] == 0) echo "<td><a href=\"view.php?action=approve&id=" .$link['link_id'] ."\" title=\"Click to approve\"><img src=\"images/check.gif\" border=\"0\" /></a></td>\n";
            else echo "<td></td>";
            echo "</tr>\n";
            $count++;
      }
      echo "</table>";

// Begin page navigation
echo "Page nav: ";
for($i = 1; $i <= $pages; $i++)
        {
                if($i == $curpage)
                        print("$i\n");
                else
                {
                        if(strpos($_SERVER['QUERY_STRING'], "page") === false)
                        {
                                $qs = "page=$i";
                        }
                        else
                        {
                                $qs = preg_replace("/page=\d+/", "page=$i", $_SERVER['QUERY_STRING']);
                        }
                        print("<a href=\"view.php?$qs\">$i</a>\n");
                }
        }
        mysql_close($hd);
?>


	</div>
	
	<div id="footer">
	Copyright 2005 Michael Ewens, snewe.com
	</div>
	

</div>

</body>
</html>
