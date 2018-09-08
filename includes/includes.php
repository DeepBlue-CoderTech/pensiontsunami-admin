<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
// The required database stuff
$hd = mysql_connect('internal-db.s123893.gridserver.com', 'db123893_host', 'vE4habacAfrA')
   or die('Could not connect');
mysql_select_db('db123893_pensiontsunami', $hd) or die('Could not select database');

function mailEntry($title, $date, $link, $author, $source, $category, $body, $comment)
{
    // Reformat the date
    $date = date("F j, Y", strtotime($date));
    $to      = 'PensionWatch@PensionTsunami.com';
    $subject = getName($category, 'category') . ":" . $title;
    // get the mail footer
	$sql = "SELECT value FROM options WHERE name = 'mailfooter'";
	$res_3 = mysql_query($sql);
	$footer = mysql_result($res_3, 0);
	if($author <> '') $author = "by " . $author;
	$message = $comment ."\n\n-------------\n\n". $link ."\n\n" . $source . "\n". $date ."\n\n" .$author . "\n\n" .$body . "\n\n". $footer;
    $headers = 'From: JackDean@webcommanders.com' . "\r\n" .
   'Reply-To: JackDean@webcommanders.com' . "\r\n";
   mail($to, $subject, $message, $headers);
}
function getName($id, $table)
{
$hd = mysql_connect('internal-db.s123893.gridserver.com', 'db123893_host', 'vE4habacAfrA')
   or die('Could not connect');
      //echo "Working on". $table;
      switch($table)
      {
          case "state":
            $sql = "SELECT state FROM state WHERE state_id = {$id}";
            break;
          case "counties":
            $sql = "SELECT county FROM counties WHERE county_id = {$id}";
            break;
          case "cities":
            $sql = "SELECT city FROM cities WHERE city_id = {$id}";
            break;
          case "category":
            $sql = "SELECT name FROM category WHERE cat_id = {$id}";
            break;
          case "linkcats":
            $sql ="SELECT name FROM link_categories WHERE link_cat_id = {$id}";
            break;
      }
      //echo $sql;
      $res_name = mysql_query($sql, $hd);
      $name = mysql_result($res_name, 0, 0);
      return $name;
}

function gen_header()
{
      $menu = <<<MENU
<ul id="navlist">
    <li><a href="index.php" title="Main admin page">Home</a></li>
    <li><a href="add.php" title="Add a link.">Add Link</a></li>
    <li><a href="view.php" title="View Links">View Links</a></li>
    <li><a href="get_links.php" title="Edit, add or delete side links.">Manage Side Links</a></li>
    <li><a href="#" title="Edit, add or delete hot spots.">Manage HotSpots</a></li>
    <li><a href="get_ads.php" title="Control the ads on the pages.">Edit Ads</a></li>
    <li><a href="get_spotlites.php" title="Control Spotlite">Edit Spotlights</a></li>
    <li><a href="get_options.php" title="Options control">Options</a></li>
  </ul>
MENU;

$lines = split("\n", $menu);
foreach ($lines as $line) {
    $current = false;
    preg_match('/href="([^"]+)"/', $line, $url);
    if (substr($_SERVER["PHP_SELF"], 7, 5) == substr($url[1], 0, 5)) {
        $line = str_replace('<a h', '<a id="current" h', $line);
        }
    echo $line."\n";
}
}

function fix_title($title)
{
        $title = trim(ucfirst(str_replace(Array("As ","Of ","A ","The ","And ","An ", "Or ", "Nor ","But ","If ", "At ","By ","On ","For ","In ","Out ","To ", "U.S. ", "U.N. ", "Us ", "Un ", "Un: ", "\"", "Us, ", "Eu "),Array("as ", "of ","a ","the ","and ","an ","or ","nor ","but ","if ","at ","by ","on ","for ","in ","out ","to ", "US ", "UN ", "US ", "UN ", "UN: ", "'", "US, ", "EU "),ucwords(strtolower($title)))));
        $things = array("Awacs ", "U.s.: ", "U.S. ", "U.s. ", "U.n. ", "Uk ", "Cia", "Fbi", "Iaea", "Hq", "Al-sadr", "Idf", "'a","'b","'c","'d","'e","'f","'g","'h","'i ","'j","'k","'l","'m","'n","'o","'p","'q","'r","'s", "'t", "'u","'v","'w","'x", "'y", "'z", "Gop", "Rnc", "Dnc", "N.c.", "Al-qaeda", "Cbs", "Abc", "Nbc", "Iaf", "U.k.","Mp","Pm","Uk ","Ex-soldiers","Small-arms", "Gi ", "Gis ","C.i.a","Wmd","W.m.d", "Bbc", "Oks", "Au ", "Tv ", "Mp ", "Mps ", "Pa ", "Fm ", "Un ", "Awol ", "Ied ", "Plo ", " out", "Uk ",  "'S ", "'S'",  "'T ", "Us: ", "Uk: ", "U.s.", "<i>times</i>", "Nato ", "“", "”", "into ", "Pr ", "Pr: ", "Fm ", "Fm: ", "But ", "Aclu ", "Aclu", "Imf ", "Bin Laden", "-a", "-b", "-c", "-d", "-e", "-f", "-g", "-h", "-i", "-j", "-k", "-l", "-m", "-n", "-o", "-p", "-q", "-r" , "-s" , "-t" , "-u", "-v", "-w", "-x", "-y", "-z", "Shi\'Ite", "Tv ", "Dod ", "Un ", "Raf ", "Tv ",  " Tv", "Elbaradei", "Nz", "Calpers", "S&p", "Bart");
        $replace = array("AWACS ","US: ", "US ", "US ", "UN ", "UK ", "CIA", "FBI", "IAEA", "HQ", "Al-Sadr", "IDF", "'A","'B","'C","'D","'E","'F","'G","'H","'I ","'J","'K","'L","'M","'N","'O","'P","'Q","'R", "'S", "'T", "'U","'V","'W","'X", "'Y", "'Z", "GOP", "RNC", "DNC","NC", "Al-Qaeda", "CBS", "ABC", "NBC", "IAF", "UK","MP","PM","UK ","Ex-Soldiers ","Small-Arms","GI ", "GIs ","CIA","WMD","WMD", "BBC", "OKs", "AU ", "TV ", "MP ", "MPs ", "PA ", "FM ", "UN ", "AWOL ", "IED ", "PLO ", " Out", "UK ",  "'s ", "'s'", "'t ", "US: ", "UK: ", "US", "<i>Times</i>", "NATO ", "\"", "\"", "Into ", "PR ", "PR: ", "FM ", "FM: ", "but ", "ACLU", "ACLU", "IMF ", "bin Laden", "-A", "-B", "-C", "-D", "-E", "-F", "-G", "-H", "-I", "-J", "-K", "-L", "-M", "-N", "-O", "-P", "-Q", "-R" , "-S" , "-T" , "-U", "-V", "-W", "-X", "-Y", "-Z", "Shi\'ite", "TV ", "DoD ", "UN ", "RAF ", "TV ", " TV", "ElBaradei", "NZ", "CalPERS", "S&P", "BART");
        $title  = str_replace($things, $replace, $title);
        return $title;
}


function autop($pee, $br=0) {
$pee = preg_replace("/(\r\n|\n|\r)/", "\n", $pee); // cross-platform newlines
$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
$pee = preg_replace('/\n?(.+?)(\n\n|\z)/s', "<p>$1</p>\n", $pee); // make paragraphs, including one at the end
if ($br) $pee = preg_replace('|(?<!</p>)\s*\n|', "<br />\n", $pee); // optionally make line breaks
return $pee;
}


?>
