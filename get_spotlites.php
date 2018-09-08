<?php
$hd = mysql_connect('internal-db.s123893.gridserver.com', 'db123893_host', 'vE4habacAfrA')
   or die('Could not connect');
mysql_select_db('db123893_pensiontsunami', $hd) or die('Could not select database');
include("includes/includes.php");
switch ($_GET['action'])
{
case "":
      break;
case "edit":
      $edit = true;
      $sql_edit = "SELECT * FROM spotlites WHERE spotlites_id = {$_GET['id']}";
      $res_edit = mysql_query($sql_edit, $hd);
      $row = mysql_fetch_array($res_edit, MYSQL_ASSOC);
      //Define the variables
      $linkid = $row['spotlites_id'];
      $title = stripslashes($row['title']);
      $author = stripslashes($row['author']);
      $link = $row['link'];
      $source = stripslashes($row['source']);
      $date = $row['date'];
      $byline = $row['byline'];
      break;
case "delete":
     $sql_delete = "DELETE FROM spotlites WHERE spotlites_id =  {$_GET['id']}";
     $res = mysql_query($sql_delete, $hd);
     if($res)     header('Location: get_spotlites.php?success=delete');
     else header('Location: get_spotlites.php?success=nodelete');
     break;
case "add":
      $add = true;
      break;
case "update":
      $sql = "UPDATE spotlites SET
            title = '" . mysql_real_escape_string($_POST['title']) . "',
            link = '{$_POST['link']}',
            author = '" . mysql_real_escape_string($_POST['author']) . "',
            source = '" . mysql_real_escape_string($_POST['source']) . "',
            date = '" . date('y-m-d', strtotime($_POST['date'])) . "',
            byline = '{$_POST['byline']}',
            posttime = NOW()
            WHERE spotlites_id = {$_POST['id']}";
      $res = mysql_query($sql, $hd);
      if($res) header('Location: get_spotlites.php?success=update');
      else header('Location: get_spotlites.php?success=noupdate');
      break;
case "insert":
      $sql = "INSERT INTO spotlites (title, link, author, byline, source, date, posttime) VALUES
            ( '" . mysql_real_escape_string($_POST['title']). "',
              '{$_POST['link']}',
              '" . mysql_real_escape_string($_POST['author']). "',
              '{$_POST['byline']}',
              '" . mysql_real_escape_string($_POST['source']). "',
              '" . date('y-m-d', strtotime($_POST['date'])) . "',
              NOW())";
      //echo $sql;
      $res = mysql_query($sql, $hd);
      if($res) header('Location: get_spotlites.php?success=add');
      else header('Location: get_spotlites.php?success=addno');
      break;
case "make":
      $sql = "UPDATE spotlites SET which = 0";
      $res_1 = mysql_query($sql, $hd);
      $sql_2 = "UPDATE spotlites SET which = 1 WHERE spotlites_id = {$_GET['id']}";
      $res_2 = mysql_query($sql_2, $hd);
      if($res_2) header('Location: get_spotlites.php?success=make');
      else header('Location: get_spotlites.php?success=nomake');
      break;
}
switch($_GET['success'])
{
      case "";
            break;
      case "delete":
            $include = "<p class=\"message\">The entry has been deleted</p>";
            break;
      case "deleteno":
            $include = "<p class=\"message\">There was an error in deleting the entry.  Don't know why.  Contact Mike.</p>";
            break;
      case "edit":
            $include = "<p class=\"message\">The entry was successfully edited.</p>";
            break;
      case "editno":
            $include = "<p class=\"message\">The edit of the entry failed :(.  Don't know why.  Contact Mike.</p>";
            break;
        case "add":
            $include = "<p class=\"message\">The entry was successfully added.</p>";
            break;
      case "addno":
            $include = "<p class=\"message\">Adding of the entry failed :(.  Don't know why.  Contact Mike.</p>";
            break;
      case "update":
             $include = "<p class=\"message\">Updating link succeeded.</p>";
            break;
      case "noupdate":
             $include = "<p class=\"message\">Updating link failed.  Contact Mike.</p>";
            break;
      case "make":
             $include = "<p class=\"message\">Spotlite set successfully.</p>";
            break;
      case "nomake":
             $include = "<p class=\"message\">Updating link failed.  Contact Mike.</p>";
            break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<title>Spotlights | Pension Tsuanami: Content Manager</title>
	<style type="text/css" media="screen">@import url(stylesheets/admin.css);</style>
	<style type="text/css" media="screen">@import url(stylesheets/form.css);</style>
</head>

<body>
<div id="container">
 <div id="header">
	<h1>Pension Tsunami Custom Content Manager</h1>
    <?php gen_header(); ?>
	</div>
	<div id="content">
	<?php 
	if(isset($include))
	{
		echo $include;
	} 
if($edit)
{
?>

 <form name="form1" method="post" action="get_spotlites.php?action=update">
  <fieldset><legend>Edit Spotlight</legend>
  
          <div class="required">
        <label for="id">ID:</label>
        <input name="id" type="hidden" id="id" value="<?php echo $linkid ?>"><?php echo $linkid ?> (You can't edit this!)
                          </div>
        
        <div class="required">
        <label for="name">Title:</label>
               <input type="text" name="title" cols="50" id="title" value="<?php echo $title ?>">
  </div>
  <div class="required">
        <label for="link">Link:</label>
               <input type="text" name="link" cols="80" id="link" value="<?php echo $link ?>">
  </div>
  <div class="required">
        <label for="author">Author:</label>
               <input type="text" name="author" cols="80" id="author" value="<?php echo $author ?>">
  </div>
  <div class="required">
        <label for="name">Source:</label>
               <input type="text" name="source" cols="80" id="source" value="<?php echo $source ?>">
  </div>
  <div class="required">
        <label for="name">Date:</label>
               <input type="text" name="date" cols="80" id="date" value="<?php echo $date ?>">
  </div>
     <div class="required">
        <label for="name">Byline:</label>
  <input name="byline" type="radio" value="none" <?php if($byline == 'none') echo "checked"?>>

          None
          <input type="radio" name="byline" value="by" <?php if($byline == 'by') echo "checked"?>>
          By
          <input type="radio" name="byline" value="italicized" <?php if($byline == 'italicized') echo "checked"?>>
          italicized
      </div>

       <div class="submit">
        <div>

          <input type="submit" class="inputSubmit" value="Submit &raquo;" />
          <input type="Reset" class="inputSubmit" value="Clear" />
        </div>
      </div>
    </fieldset>
</form>
<?php
//ending the edit if
}
if($add)
{
?>
  <form name="form1" method="post" action="get_spotlites.php?action=insert">
  <fieldset><legend>Add Spotlight</legend>

                 <div class="required">
        <label for="name">Title:</label>
               <input type="text" name="title" cols="50" id="title">
  </div>
  <div class="required">
        <label for="link">Link:</label>
               <input type="text" name="link" cols="80" id="link">
  </div>
  <div class="required">
        <label for="author">Author:</label>
               <input type="text" name="author" cols="80" id="author">
  </div>
  <div class="required">
        <label for="name">Source:</label>
               <input type="text" name="source" cols="80" id="source">
  </div>
  <div class="required">
        <label for="name">Date:</label>
               <input type="text" name="date" cols="80" id="date" value="<?php echo date('n/j/Y', time()) ?>">
  </div>
     <div class="required">
        <label for="name">Byline:</label>
  <input name="byline" type="radio" value="none">

          None
          <input type="radio" name="byline" value="by" checked>
          By
          <input type="radio" name="byline" value="italicized">
          italicized
      </div>

       <div class="submit">
        <div>

          <input type="submit" class="inputSubmit" value="Submit &raquo;" />
          <input type="Reset" class="inputSubmit" value="Clear" />
        </div>
      </div>
    </fieldset>
</form>
<?php
}
	  
//the sql statement
$sql = "SELECT * FROM spotlites LIMIT 100";

$res = mysql_query($sql, $hd);

echo "<a href=\"get_spotlites.php?action=add\">Add Spotlight to the Database</a> <br /><br />\n";
echo "<h2>Spotlights</h2>\n<table cellpadding=\"5\" cellspacing=\"5\"><th>ID</th><th>Title/Link</th><th>Source</th><th>Author</th><th>Spotlite?</th><th>Edit</th><th>Delete</th>";
$count = 0;
while ($links = mysql_fetch_array($res, MYSQL_ASSOC)) {
      if($count%2 === 0) $background = 'f4f4f4';
            else $background = 'ffffff';
      echo "<tr bgcolor=\"#". $background ."\">\n";
      echo "<td>{$links['spotlites_id']}</td>";
      echo "<td><a href=\"{$links['link']}\">{$links['title']}</a></td>\n";
      if($links['byline'] == 'by') {$inc_b = 'by';}
      if($links['byline'] == 'italicized') {$inc_b = '<i>'; $inc_a = '</i>';}
      echo "<td>{$links['source']}</td>\n";
      echo "<td>{$inc_b}{$links['author']}{$inc_a}</td>\n";
      if($links['which'] == 0) $state = "no. <a href=\"get_spotlites.php?action=make&id={$links['spotlites_id']}\">Set as spotlight</a>";
      else    $state = 'yes';
      echo "<td>{$state}</td>";
      echo "<td><a href=\"get_spotlites.php?action=edit&id={$links['spotlites_id']}\">Edit</a></td>\n";
      echo "<td><a href=\"get_spotlites.php?action=delete&id={$links['spotlites_id']}\">Delete Link</a></td>\n";
      echo "</tr>\n";
      $count++;
}
echo "</table>";
?>


	</div>
	
	<div id="footer">
	Copyright 2005 Michael Ewens, snewe.com
	</div>
	

</div>

</body>
</html>
