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
      $sql_edit = "SELECT * FROM ads WHERE ad_id = {$_GET['id']}";
      $res_edit = mysql_query($sql_edit, $hd);
      $row = mysql_fetch_array($res_edit, MYSQL_ASSOC);
      //Define the variables
      $adid = $row['ad_id'];
      $name = stripslashes($row['name']);
      $expiry = $row['expiry'];
      $link = $row['link'];
      $image = $row['image'];
      $description = $row['description'];
      break;
case "delete":
     $sql_delete = "DELETE FROM ads WHERE ad_id =  {$_GET['id']}";
     $res = mysql_query($sql_delete, $hd);
     if($res)     header('Location: get_ads.php?success=delete');
     else header('Location: get_ads.php?success=nodelete');
     break;
case "add":
      $add = true;
      break;
case "update":
      $sql = "UPDATE ads SET
            name = '" . mysql_real_escape_string($_POST['name']) . "',
            link = '{$_POST['link']}',
            image = '{$_POST['image']}',
            description = '" . mysql_real_escape_string($_POST['description']) . "',
            expiry = '" . date('y-m-d', strtotime($_POST['expiry'])) . "'
            WHERE ad_id = {$_POST['id']}";
      $res = mysql_query($sql, $hd);
      if($res) header('Location: get_ads.php?success=update');
      else header('Location: get_ads.php?success=noupdate');
      break;
case "insert":
      $sql = "INSERT INTO ads (name, link, image, description, expiry) VALUES
            ( '" . mysql_real_escape_string($_POST['name']). "',
              '{$_POST['link']}',
			  '{$_POST['image']}',
              '" . mysql_real_escape_string($_POST['description']). "',
              '" . date('y-m-d', strtotime($_POST['expiry'])) . "')";
      //echo $sql;
      $res = mysql_query($sql, $hd);
      if($res) header('Location: get_ads.php?success=add');
      else header('Location: get_ads.php?success=addno');
      break;
}
switch($_GET['success'])
{
      case "";
            break;
      case "delete":
            $include = "<p class=\"message\">The ad has been deleted</p>";
            break;
      case "deleteno":
            $include = "<p class=\"message\">There was an error in deleting the ad.  Don't know why.  Contact Mike.</p>";
            break;
      case "edit":
            $include = "<p class=\"message\">The ad was successfully edited.</p>";
            break;
      case "editno":
            $include = "<p class=\"message\">The edit of the ad failed :(.  Don't know why.  Contact Mike.</p>";
            break;
        case "add":
            $include = "<p class=\"message\">The ad was successfully added.</p>";
            break;
      case "addno":
            $include = "<p class=\"message\">Adding of the ad failed :(.  Don't know why.  Contact Mike.</p>";
            break;
      case "update":
             $include = "<p class=\"message\">Updating ad succeeded.</p>";
            break;
      case "noupdate":
             $include = "<p class=\"message\">Updating ad failed.  Contact Mike.</p>";
            break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<title>Ads | Pension Tsuanami: Content Manager</title>
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

 <form name="form1" method="post" action="get_ads.php?action=update">
  <fieldset><legend>Edit Ad</legend>
  
          <div class="required">
        <label for="id">ID:</label>
        <input name="id" type="hidden" id="id" value="<?php echo $adid ?>"><?php echo $adid ?> (You can't edit this!)
                          </div>
        
        <div class="required">
        <label for="name">Name:</label>
               <input type="text" name="name" cols="50" id="name" value="<?php echo $name ?>">
  </div>
  <div class="required">
        <label for="link">Image:</label>
               <input type="text" name="iamge" cols="80" id="image" value="<?php echo $image ?>">
  </div>
  <div class="required">
        <label for="link">Link:</label>
               <input type="text" name="link" cols="80" id="link" value="<?php echo $link ?>">
  </div>
  <div class="required">
        <label for="author">Expiry:</label>
               <input type="text" name="expiry" cols="40" id="expiry" value="<?php echo $expiry ?>">m/d/yyyy
  </div>
  <div class="required">
        <label for="name">Description:</label>
               <input type="text" name="description" cols="80" id="description" value="<?php echo $description ?>">
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
  <form name="form1" method="post" action="get_ads.php?action=insert">
  <fieldset><legend>Add Ad</legend>
  
                 
        <div class="required">
        <label for="name">Name:</label>
               <input type="text" name="name" cols="50" id="name" value="">
  </div>
  <div class="required">
        <label for="link">Image:</label>
               <input type="text" name="image" cols="80" id="image" value="">
  </div>
  <div class="required">
        <label for="link">Link:</label>
               <input type="text" name="link" cols="80" id="link" value="">
  </div>
  <div class="required">
        <label for="author">Expiry:</label>
               <input type="text" name="expiry" cols="40" id="expiry" value=""> m/d/yyyy
  </div>
  <div class="required">
        <label for="name">Description:</label>
               <input type="text" name="description" cols="80" id="description" value="">
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
$sql = "SELECT * FROM ads LIMIT 100";

$res = mysql_query($sql, $hd);

echo "<a href=\"get_ads.php?action=add\">Add Ad to the Database</a> <br /><br />\n";
echo "<h2>Ads</h2>\n<table cellpadding=\"5\" cellspacing=\"5\"><th>ID</th><th>Name/Link</th><th>Image</th><th>Description</th><th>Expiration</th><th>Edit</th><th>Delete</th>";
$count = 0;
while ($links = mysql_fetch_array($res, MYSQL_ASSOC)) {
      if($count%2 === 0) $background = 'f4f4f4';
            else $background = 'ffffff';
      echo "<tr bgcolor=\"#". $background ."\">\n";
      echo "<td>{$links['ad_id']}</td>";
      echo "<td><a href=\"{$links['link']}\">{$links['name']}</a></td>\n";
      echo "<td><img src=\"http://www.pensiontsunami.com/support/{$links['image']}\" /></td>\n";
      echo "<td>{$links['description']}</td>\n";
      echo "<td>". $links['expiry'] ."</td>";
	  echo "<td><a href=\"get_ads.php?action=edit&id={$links['ad_id']}\">Edit</a></td>\n";
      echo "<td><a href=\"get_ads.php?action=delete&id={$links['ad_id']}\">Delete Ad</a></td>\n";
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
