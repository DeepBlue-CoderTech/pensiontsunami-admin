<?php
//this file has some needed general functions
include("includes/includes.php");
$hd = mysql_connect('internal-db.s123893.gridserver.com', 'db123893_host', 'vE4habacAfrA')
   or die('Could not connect');
mysql_select_db('db123893_pensiontsunami', $hd) or die('Could not select database');
	if($_GET['type'] == 'add')
	{

				// do some error checking
				if(!isset($_POST['title']) || !isset($_POST['link']) || !isset($_POST['date']) || !isset($_POST['category']))
				{
					die('You forgot to fill in a required field.  Hit the back buttom and try again!');
					break;
				}
				// make everything a variable for ease of debugging
				$title = fix_title(trim($_POST['title']));
				$date = $_POST['date'];
                $date = date('y-m-d', strtotime($date));
				$link = trim($_POST['link']);
				$email = intval($_POST['email']);
				if($email == 1) 
						{
						$body2 = stripslashes($body);
						$title2 = stripslashes($title);
						$author2 = stripslashes($_POST['author']);
						$source2 = stripslashes($_POST['source']);
						$comment2 = stripslashes($_POST['comment']);
						mailEntry($title2, $date, $link, $author2,$source2, $_POST['category'], $body2, $comment2);
                        }
				$title = mysql_real_escape_string($title);
				$date = mysql_real_escape_string($date);
				$category = $_POST['category'];
				$status = $_POST['status'];
                $body = mysql_real_escape_string($_POST['body']);
                $source = mysql_real_escape_string($_POST['source']);
                $author = mysql_real_escape_string($_POST['author']);
                $comment = mysql_real_escape_string($_POST['comment']);
    			$state = $_POST['state'];
                        $county = $_POST['county'];
                        $city = $_POST['city'];
                        if($category == '') die('You forgot to define a category.');
                        // Check for email send
                        
						$sql = "INSERT INTO links (title, link, date, source, author, category, state, county, city, status, posttime, body, email, comment)
						VALUES ('$title', '$link',  '$date' , '$source', '$author', $category, $state, $county, $city, $status, NOW(), '$body', $email, '$comment')";


	// Now we have the SQL statement, so we can enter it into the database
	$res = mysql_query($sql, $hd);
	mysql_close($hd);
	if($res) $message = true;
      else $message = false;
      header("Location: http://www.pensiontsunami.com/admin/add.php?message=$message");
      //exit;
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
	if(isset($_GET['message']))
	{
		if($_GET['message'])
			$message = 'The link was successfully added.  <a href="view.php">Click here</a> to see the links in the database.';
		else $message = 'The link could not be added.  Perhaps you had a malformed field.  Send the entry as submitted to Mike for debugging.';
	echo "<p class=\"message\">" . $message ."</p>";
	} 
	?>
	  

  <form action="add.php?type=add" method="post" enctype="multipart/form-data">
    <p><strong>Bold</strong> fields are required.</p>
    <fieldset><legend>Add a Link</legend>
    
     <div class="required">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" class="inputText" size="80" value="" />
      </div>
     <div class="required">
        <label for="link">Link:</label>
        <input type="text" name="link" id="link" class="inputText" size="80" value="" />
      </div>
	  <div class="optional">
        <label for="source">Source:</label>
        <input type="text" name="source" id="source" class="inputText" size="50" value="" />
      </div>
	  <div class="optional">
        <label for="author">Author:</label>
        <input type="text" name="author" id="author" class="inputText" size="40" value="" />
      </div>
     <div class="required">
        <label for="date">Date:</label>
        <input type="text" name="date" id="date" size="30" value="<?php echo date("n/j/Y") ?>">
      </div>	  
	  <div class="required">
        <fieldset><legend>Publish Status</legend>

          <label for="publish" class="labelRadio compact"><input type="radio" name="status" id="status" class="inputRadio" value="1" checked="checked" /> Publish</label>
          <label for="draft" class="labelRadio compact"><input type="radio" name="status" id="status" class="inputRadio" value="0" /> Draft</label>
        </fieldset>
      </div>    
	  <div class="required">
        <fieldset><legend>Email Options</legend>

          <label for="send" class="labelRadio compact"><input type="radio" name="email" id="email" class="inputRadio" value="1" checked="checked" /> Send to List</label>
          <label for="suppress" class="labelRadio compact"><input type="radio" name="email" id="email" class="inputRadio" value="0" /> Suppress</label>
        </fieldset>
      </div>    
	 <div class="required">
        
        <label for="Category">Category:</label>
        
<select name="category" id="category" class="selectOne">
<option value="">Select a Category</option>
            <?php
			$sql = "SELECT * FROM category";
			$res = mysql_query($sql, $hd);
            while ($cats = mysql_fetch_array($res, MYSQL_ASSOC))
			{
				echo "<option value=\"{$cats['cat_id']}\">{$cats['name']}</option>\n";
			}
		    ?>
</select>

      </div>  
	  
	  
	  <div class="optional">
        
        <label for="state">State:</label>
        
<select name="state" id="state" class="selectOne"><option value="0">Select a State/Province</option>
<?php
			$sql = "SELECT * FROM state ORDER BY abbrev";
			$res = mysql_query($sql, $hd);
            while ($states = mysql_fetch_array($res, MYSQL_ASSOC))
			{
				echo "<option value=\"{$states['state_id']}\">{$states['abbrev']}: {$states['state']}</option>\n";
			}
		    ?>
</select>

      </div>
	   <div class="optional">
        
        <label for="county">County:</label>
        
<select name="county" id="county" class="selectOne"><option value="0">Select a County</option>
<?php
			$sql = "SELECT * FROM counties ORDER BY county";
			$res = mysql_query($sql, $hd);
            while ($cnts = mysql_fetch_array($res, MYSQL_ASSOC))
			{
				echo "<option value=\"{$cnts['county_id']}\">{$cnts['county']}</option>\n";
			}
		    ?>
</select>

      </div>
	  
	  <div class="optional">
        
        <label for="city">City:</label>
        
<select name="city" id="city" class="selectOne"><option value="0">Select a City</option>
<?php
			$sql = "SELECT * FROM cities ORDER BY city";
			$res = mysql_query($sql, $hd);
            while ($cities = mysql_fetch_array($res, MYSQL_ASSOC))
			{
				echo "<option value=\"{$cities['city_id']}\">{$cities['city']}</option>\n";
			}
		    ?>
</select>

      </div>
	   <div class="optional">
        
        <label for="body">Body of article</label>

        <textarea name="body" id="body" class="inputTextarea" rows="10" cols="80"></textarea>
        </div>
          <div class="optional">

        <label for="comment">Comment for email</label>

        <textarea name="comment" id="comment" class="inputTextarea" rows="4" cols="21"></textarea>
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
