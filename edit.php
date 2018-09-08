<?php
$hd = mysql_connect('internal-db.s123893.gridserver.com', 'db123893_host', 'vE4habacAfrA')
   or die('Could not connect');
mysql_select_db('db123893_pensiontsunami', $hd) or die('Could not select database');
include('includes/includes.php');
if($_GET['type'] == 'link')
{
    // We need to retrieve the data
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM links WHERE link_id = $id";
    $res = mysql_query($sql, $hd);
    $link = mysql_fetch_array($res, MYSQL_ASSOC);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<title>Edit Link | Pension Tsuanami: Content Manager</title>
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
	if($message <> "")
	{
		if($_GET['success'])
			$message = 'The link was successfully added.';
		else $message = 'The link could not be added.  Perhaps you had a malformed field.  Send the entry as submitted to Mike for debugging.';
	echo "<p class=\"message\">" . $message ."</p>";
	} 
	?>
	  

  <form action="view.php?action=update&id=<?php echo $_GET['id'] ?>" method="post" enctype="multipart/form-data">
    <p><strong>Bold</strong> fields are required.</p>
    <fieldset><legend>Edit Link</legend>
    
     <div class="required">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" class="inputText" size="80" value="<?php echo stripslashes($link['title']) ?>" />
      </div>
     <div class="required">
        <label for="link">Link:</label>
        <input type="text" name="link" id="link" class="inputText" size="80" value="<?php echo $link['link'] ?>" />
      </div>
	  <div class="optional">
        <label for="source">Source:</label>
        <input type="text" name="source" id="source" class="inputText" size="50" value="<?php echo stripslashes($link['source']) ?>" />
      </div>
	  <div class="optional">
        <label for="author">Author:</label>
        <input type="text" name="author" id="author" class="inputText" size="40" value="<?php echo stripslashes($link['author']) ?>" />
      </div>
     <div class="required">
        <label for="date">Date:</label>
        <input type="text" name="date" id="date" size="30" value="<?php echo date('n/j/Y', strtotime($link['date'])) ?>">
      </div>	  
	  <div class="required">
        <fieldset><legend>Publish Status</legend>
          <label for="publish" class="labelRadio compact"><input type="radio" name="status" id="status" class="inputRadio" value="1" <?php if($link['status'] == 1) echo "checked=\"checked\"" ?>  /> Publish</label>
          <label for="draft" class="labelRadio compact"><input type="radio" name="status" id="status" class="inputRadio" value="0" <?php if($link['status'] == 0) echo "checked=\"checked\""; ?>  /> Draft</label>
        </fieldset>
      </div>    
	  <div class="required">
        <fieldset><legend>Email Options</legend>

          <label for="send" class="labelRadio compact"><input type="radio" name="email" id="email" class="inputRadio" value="1" <?php if($link['email'] == 1) echo "checked=\"checked\""; ?>  /> Send to List</label>
          <label for="suppress" class="labelRadio compact"><input type="radio" name="email" id="email" class="inputRadio" value="0" <?php if($link['email'] == 0) echo "checked=\"checked\""; ?>  /> Suppress</label>
        </fieldset>
      </div>    
	 <div class="required">
        
        <label for="Category">Category:</label>
        
<select name="category" id="category" class="selectOne">
<option value="">Select a Category</option>
<option value="">---------------</option>
            <?php
			$sql = "SELECT * FROM category";
			$res = mysql_query($sql, $hd);
            while ($cats = mysql_fetch_array($res, MYSQL_ASSOC))
			{
				echo "<option value=\"{$cats['cat_id']}\"" . (($link['category'] == $cats['cat_id']) ? " selected" : "") .">{$cats['name']}</option>\n";
			}
		    ?>
</select>

      </div>  
	  
	  
	  <div class="optional">
        
        <label for="state">State:</label>
        
<select name="state" id="state" class="selectOne"><option value="0">Select a State/Province</option><option value="0">---------------</option>
<?php
			$sql = "SELECT * FROM state ORDER BY abbrev";
			$res = mysql_query($sql, $hd);
            while ($states = mysql_fetch_array($res, MYSQL_ASSOC))
			{
    echo "<option value=\"{$states['state_id']}\"" . (($link['state'] == $states['state_id']) ? " selected" : "") .">{$states['state']}</option>\n";
			}
		    ?>
</select>

      </div>
	   <div class="optional">
        
        <label for="county">County:</label>
        
<select name="county" id="county" class="selectOne"><option value="0">Select a County</option><option value="0">---------------</option>
<?php
			$sql = "SELECT * FROM counties ORDER BY county";
			$res = mysql_query($sql, $hd);
            while ($cnts = mysql_fetch_array($res, MYSQL_ASSOC))
			{
			echo "<option value=\"{$cnts['county_id']}\"" . (($link['county'] == $cnts['county_id']) ? " selected" : "") .">{$cnts['county']}</option>\n";
			}
		    ?>
</select>

      </div>
	  
	  <div class="optional">
        
        <label for="city">City:</label>
        
<select name="city" id="city" class="selectOne"><option value="0">Select a City</option><option value="0">---------------</option>
<?php
			$sql = "SELECT * FROM cities ORDER BY city";
			$res = mysql_query($sql, $hd);
            while ($cities = mysql_fetch_array($res, MYSQL_ASSOC))
			{
			echo "<option value=\"{$cities['city_id']}\"" . (($link['city'] == $cities['city_id']) ? " selected" : "") .">{$cities['city']}</option>\n";
			}
		    ?>
</select>

      </div>
	   <div class="optional">
        
        <label for="body">Body of article</label>

        <textarea name="body" id="body" class="inputTextarea" rows="10" cols="81"><?php echo stripslashes($link['body']) ?></textarea>
        </div>
          <div class="optional">

        <label for="comment">Comment for email</label>

        <textarea name="comment" id="comment" class="inputTextarea" rows="4" cols="21"><?php echo $link['comment'] ?></textarea>
        </div>
        <div class="submit">
        <div>

          <input type="submit" class="inputSubmit" value="Submit &raquo;" />
          <input type="Reset" class="inputSubmit" value="Clear" />
        </div>
      </div>
   </form>



	</div>
	
	<div id="footer">
	Copyright 2005 Michael Ewens, snewe.com
	</div>
	

</div>

</body>
</html>
