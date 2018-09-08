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
      $sql_edit = "SELECT * FROM options WHERE options_id = {$_GET['id']}";
      $res_edit = mysql_query($sql_edit, $hd);
      $row = mysql_fetch_array($res_edit, MYSQL_ASSOC);
      //Define the variables
      $optionsid = $row['options_id'];
      $name = $row['name'];
      $value = $row['value'];
      break;
case "update":
      $value = mysql_real_escape_string($_POST['value']);
	  $sql = "UPDATE options SET
            value = '" . $value . "'
             WHERE options_id = {$_POST['id']}";
      $res = mysql_query($sql, $hd);
      if($res) header('Location: get_options.php?success=update');
      else header('Location: get_options.php?success=noupdate');
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
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<title>Edit Options | Pension Tsuanami: Content Manager</title>
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

 <form name="form1" method="post" action="get_options.php?action=update">
  <fieldset><legend>Edit Option</legend>
  
          <div class="required">
        <label for="id">ID:</label>
        <input name="id" type="hidden" id="id" value="<?php echo $optionsid ?>"><?php echo $optionsid ?> (You can't edit this!)
                          </div>
        
        <div class="required">
        <label for="name">Name:</label>
              <input name="name" type="hidden" id="name" value="<?php echo $name ?>"><?php echo $name ?> (You can't edit this!)
  </div>
  <div class="required">
        <label for="name">Value:</label>
               <textarea name="value" cols="80" rows="10" id="value"><?php echo stripslashes($value) ?></textarea>
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
$sql = "SELECT * FROM options";

$res = mysql_query($sql, $hd);
$count = 0;
echo "<h2>Options</h2>\n<table cellpadding=\"3\" cellspacing=\"3\"><th>ID</th><th>Name/Link</th><th>Description</th><th>Value</th><th>Edit</th>";
while ($links = mysql_fetch_array($res, MYSQL_ASSOC)) {
       if($count%2 === 0) $background = 'f4f4f4';
            else $background = 'ffffff';
      echo "<tr bgcolor=\"#". $background ."\">\n";
      echo "<td>{$links['options_id']}</td>";
      echo "<td>{$links['name']}</td>\n";
      echo "<td width=\"30%\">{$links['description']}</td>\n";
      echo "<td>". autop(stripslashes($links['value'])). "</td>\n";
      echo "<td width=\"10%\"><a href=\"get_options.php?action=edit&id={$links['options_id']}\">Edit Option</a></td>\n";
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
