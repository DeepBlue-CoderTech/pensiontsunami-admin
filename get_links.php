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
      $sql_edit = "SELECT * FROM side_links WHERE side_link_id = {$_GET['id']}";
      $res_edit = mysql_query($sql_edit, $hd);
      $row = mysql_fetch_array($res_edit, MYSQL_ASSOC);
      //Define the variables
      $linkid = $row['side_link_id'];
      $name = $row['name'];
      $cat = $row['link_cat'];
      $link = $row['link'];
      break;
case "delete":
     $sql_delete = "DELETE FROM side_links WHERE side_link_id =  {$_GET['id']}";
     $res = mysql_query($sql_delete, $hd);
     if($res)     header('Location: get_links.php?success=delete');
     else header('Location: get_links.php?success=nodelete');
     break;
case "add":
      $add = true;
      break;
case "update":
      $sql = "UPDATE side_links SET
            name = '" . mysql_real_escape_string($_POST['name']) . "',
            link = '{$_POST['link']}',
            link_cat = {$_POST['category']}
            WHERE side_link_id = {$_POST['id']}";
      $res = mysql_query($sql, $hd);
      if($res) header('Location: get_links.php?success=update');
      else header('Location: get_links.php?success=noupdate');
      break;
case "insert":
      $sql = "INSERT INTO side_links (name, link, link_cat) VALUES
            ( '" . mysql_real_escape_string($_POST['name']). "',
              '" . mysql_real_escape_string($_POST['link']). "',
              {$_POST['category']})";
      $res = mysql_query($sql, $hd);
      if($res) header('Location: get_links.php?success=add');
      else header('Location: get_links.php?success=addno');
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
	<title>Add Link | Pension Tsuanami: Content Manager</title>
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

 <form name="form1" method="post" action="get_links.php?action=update">
  <fieldset><legend>Edit Link</legend>
  
          <div class="required">
        <label for="id">ID:</label>
        <input name="id" type="hidden" id="id" value="<?php echo $linkid ?>"><?php echo $linkid ?> (You can't edit this!)
                          </div>
        
        <div class="required">
        <label for="name">Title:</label>
               <input type="text" name="name" cols="50" id="name" value="<?php echo $name ?>">
  </div>
  <div class="required">
        <label for="name">Link:</label>
               <input type="text" name="link" cols="80" id="link" value="<?php echo $link ?>">
  </div>

   <div class="required">

        <label for="category">Category:</label>

        <select name="category" id="category" class="selectOne">
<option value="">Select a Category</option>
            <?php
			$sql = "SELECT * FROM link_categories";
			$res = mysql_query($sql, $hd);
            while ($cats = mysql_fetch_array($res, MYSQL_ASSOC))
			{
				echo "<option value=\"{$cats['link_cat_id']}\"" . (($cat == $cats['link_cat_id']) ? " selected" : "") .">{$cats['name']}</option>\n";
			}
		    ?>
</select>




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
 <form name="form1" method="post" action="get_links.php?action=insert">
  <fieldset><legend>Add Link</legend>

  <div class="required">
        <label for="name">Title:</label>
               <input type="text" name="name" cols="50" id="name">
  </div>
  <div class="required">
        <label for="name">Link:</label>
               <input type="text" name="link" cols="80" id="link">
  </div>
 <div class="required">

        <label for="category">Category:</label>
  <select name="category" id="category" class="selectOne">
<option value="">Select a Category</option>
            <?php
			$sql = "SELECT * FROM link_categories";
			$res = mysql_query($sql, $hd);
            while ($cats = mysql_fetch_array($res, MYSQL_ASSOC))
			{
				echo "<option value=\"{$cats['link_cat_id']}\">{$cats['name']}</option>\n";
			}
		    ?>
</select>
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
$sql = "SELECT * FROM side_links";

$res = mysql_query($sql, $hd);

echo "<a href=\"get_links.php?action=add\">Add Link to the Database</a> <br /><br />\n";
echo "<h2>Links</h2>\n<table cellpadding=\"3\" cellspacing=\"3\"><th>ID</th><th>Title/Link</th><th>Category</th><th>Edit</th><th>Delete Link</th>";
while ($links = mysql_fetch_array($res, MYSQL_ASSOC)) {
      echo  "<tr>\n";
      echo "<td>{$links['side_link_id']}</td>";
      echo "<td><a href=\"{$links['link']}\">{$links['name']}</a></td>\n";
      echo "<td>". getName($links['link_cat'], 'linkcats') ."</td>\n";
      echo "<td><a href=\"get_links.php?action=edit&id={$links['side_link_id']}\">Edit</a></td>\n";
      echo "<td><a href=\"get_links.php?action=delete&id={$links['side_link_id']}\">Delete Link</a></td>\n";
      echo "</tr>\n";
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
