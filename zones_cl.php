<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/* VER 2021-MAR/004 */

define('SALT_LENGTH', 15);

require_once dirname(__FILE__).'/'.'Crypt.php';

class Beauty_Zones extends Crypt {
	
	var $title;
	var $slogan;
	
	var $author;

	var $refresh;

	// if edit mode = 0 then store data in mysql db
	// if edit mode = 1 then store data in files
	var $editmode = 0;
	
	var $maintenance;
	var $maintenance_ip;

	var $master = "192.168.1.109";
	
	var $index = "/index.php"; // "{$_SERVER['PHP_SELF']}"

	var $glob_password = "pLeAsElIkEmE";	// = ""; //  <-- set the synapse_password
	var $password_after = 1391296382;	// <-- after this crea will be messages crypted
	
	// more Hash are supported via php hash() function
	// default is "md5", you can use another one like "sha"
	var $password_type = "md5";

	var $adminemail;
	
	var $zones;
	var $zones_info;
	
	var $upload_dir = "./bindata/files/";
	var $blogl = "/index.php?page=blog&user=";

	var $host;
	var $username;
	var $password;
	var $table;
	
	var $captcha_type;
	var $captcha_siteKey;
	var $captcha_secret;
	var $captcha_lang;
	
	var $current_dir;
	
/**
 ** Utilities Support
 **
 **/
/* @info  this function, parse page before put to the screen for better view */
public function spracuj_form($stranka) {
  $stranka = stripslashes($stranka);
  //$nahrad_co = array("[hr]");
  //$nahrad_cim = array("<hr>");
  $stranka = str_replace("[hr]", "", $stranka);
  $stranka = str_replace("<hr>", "", $stranka);
  $stranka = str_replace("</hr>", "", $stranka);
  $stranka = str_replace("</>", "", $stranka);
  $stranka = str_replace("<//>", "", $stranka);
  $stranka = preg_replace("#\<a href\=\"(.*?)\"\>(.*?)\<\/a\>#", "\\1", $stranka);
  // others
  $stranka = str_replace("[br]", "<br>", $stranka);
  $stranka = str_replace("[/br]", "<br>", $stranka);
  $stranka = preg_replace("#\[i\](.*?)\[/i\]#si", "<i>\\1</i>", $stranka);
  $stranka = preg_replace("#\[b\](.*?)\[/b\]#si", "<b>\\1</b>", $stranka);
  $stranka = preg_replace("#\[img\](.*?)\[/img\]#si", "<img src=\"\\1\" width=\"320\" height=\"240\" border=\"0\">", $stranka);
  $stranka = preg_replace("#\[url=(.*?)\](.*?)\[/url\]#si", "<a href=\"\\1\">\\2</a>", $stranka);
  $stranka = preg_replace("#\[red\](.*?)\[/red\]#si", "<font color=\"red\">\\1</font>", $stranka);
  $stranka = preg_replace("#\[green\](.*?)\[/green\]#si", "<font color=\"green\">\\1</font>", $stranka);
  $stranka = preg_replace("#\[blue\](.*?)\[/blue\]#si", "<font color=\"blue\">\\1</font>", $stranka);
  $stranka = preg_replace("#\[farba=\#(.*?)\](.*?)\[/farba\]#si", "<font color=\"#\\1\">\\2</font>", $stranka);
  $stranka = preg_replace("#\[vlavo\](.*?)\[/vlavo\](.*?)<br />#si", "<div align=\"left\">\\1</div>", $stranka);
  $stranka = preg_replace("#\[stred\](.*?)\[/stred\](.*?)<br />#si", "<div align=\"center\">\\1</div>", $stranka);
  $stranka = preg_replace("#\[vpravo\](.*?)\[/vpravo\](.*?)<br />#si", "<div align=\"right\">\\1</div>", $stranka);
  $stranka = preg_replace("#([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "<a href=\"mailto:\\1@\\2\">\\1@\\2</a>", $stranka);
  $stranka = preg_replace("#\*\*([[:digit:]]{1,2})\*#si", "<img src=\"smajliky/\\1.gif\" alt=\"Smajlik\" border=\"0\">", $stranka);
  
  $stranka = preg_replace("#\[style\](.*?)\[/style\]#si", "<style>\\1</style>", $stranka);
  
  // youtube iframe support
  $stranka = preg_replace("#\[iframe\](.*?)\[/iframe\]#si", "<iframe width=\"560\" height=\"315\" src=\"\\1\" frameborder=\"0\" allowfullscreen></iframe>", $stranka);
  // $stranka = preg_replace("#\[iframe\](.*?)\[/iframe\]#si", "", $stranka);
  
  return $stranka;
}

function ismedia($file) {
	$search_array = array(	'.WMA' => 'music', '.wma' => 'music', '.MP3' => 'music', '.mp3' => 'music', '.OGG' => 'music', '.ogg' => 'music',
							'.FLAC' => 'music', '.flac' => 'music', '.PNG' => 'picture', '.png' => 'picture', '.JPG' => 'picture', '.jpg' => 'picture',
							'.BMP' => 'picture', '.bmp' => 'picture', '.GIF' => 'picture', '.gif' => 'picture', '.SVG' => 'picture', '.svg' => 'picture',
							'.AVI' => 'video', '.avi' => 'video', '.FLV' => 'video', '.flv' => 'video', '.MPG' => 'video', '.mpg' => 'video' );
	
	if(array_key_exists($file, $search_array))
	{
				if($search_array[$file]=="music")
				{
						return 1;
				} else
				if($search_array[$file]=="picture") {
						return 1;
				} else
				if($search_array[$file]=="video") {
						return 1;
				} else {
						//handle any other format whitelisted for the application
						return 0;
				}
	}
	else {
               //error_log("format parameter missing . using default html format");
               //header('Content-Type: text/html');
               return 0;
	}
}


function list_image_files_box($user)
{
  $message_display = <<<MESSAGE_DISPLAY
		<br>
		<div class="outer">
		<ul id="menu-ho">
MESSAGE_DISPLAY;

  $dir = $this->upload_dir . "/" . $user . "/";

  if(is_dir($dir))
  {
    if($handle = opendir($dir))
    {
		$id=0;
      while(($file = readdir($handle)) !== false)
      {
        if($file != "." && $file != ".." && $file != "Thumbs.db"/*pesky windows, images..*/)
        {
			$rul = $dir . "/" . $file;
			// decrypt if needed
			/*
			if ($this->synapse_crypt_files==1) 
			if (strpos($file, ".sif")!==false) { 
				$this->decrypt_file($rul , $user );
				$rul = preg_replace('/\.sif$/','',$rul);
			}
			*/
			// preview image
			if ( $this->ismedia($file)==1 ) {
				$message_display .= <<<MESSAGE_DISPLAY
					<li>
					<a href="$dir/$file">
					<img  HSPACE=5 VSPACE=5 ALIGN=LEFT border=5; width=102px; height=76px; src="{$rul}" alt="painting" title="painting" >
					<span>
						<b class="h2">{$file}</b><br />
						
						Picture file.
					</span>
					</a>
				</li>
MESSAGE_DISPLAY;
		  } // <b class="h3">$dir</b><br />
          $id++;
		}
      }
	   //if ($id > 100) echo '<br><b>Next Page</b> .... ';
      closedir($handle);
    }
  }
  $message_display .= <<<MESSAGE_DISPLAY
		</ul>
		</div>
		<br>
		<br>
MESSAGE_DISPLAY;
  echo($message_display);
}

public function list_image_files_by_user($user)
{
$message_display = <<<MESSAGE_DISPLAY
		<div class="post-content">
MESSAGE_DISPLAY;

  $dir = $this->upload_dir . "/" . $user . "/";

  if(is_dir($dir))
  {
    if($handle = opendir($dir))
    {
		$id=0;
      while(($file = readdir($handle)) !== false)
      {
        if($file != "." && $file != ".." && $file != "Thumbs.db"/*pesky windows, images..*/)
        {
			$rul = $dir . $file;
			// decrypt if needed
			/*
			if ($this->synapse_crypt_files==1) 
			if (strpos($file, ".sif")!==false) { 
				$this->decrypt_file($rul , $user );
				$rul = preg_replace('/\.sif$/','',$rul);
			}
			*/
			// preview image
			//if ( $this->ismedia($file)==1 ) {
				$message_display .= <<<MESSAGE_DISPLAY
						<a id="browser-link" href="/index.php?page=image&image={$dir}{$file}" TITLE=$file>
						<img style="border: 3px solid #CCCCCC; text-decoration: none; background: #FFFFFF; 
							font-weight: normal;color: #53524E;" HSPACE=5; VSPACE=5; alt="" src="{$dir}{$file}" class="alignnone" width="250" height="167"></a>
MESSAGE_DISPLAY;
		  //}
          $id++;
		}
      }
      closedir($handle);
    }
  }
  $message_display .= <<<MESSAGE_DISPLAY
		</div>
MESSAGE_DISPLAY;
  return $message_display;
}

function display_image($path) {
	return $message_display = <<<MESSAGE_DISPLAY
					<!--ul!-->
		<div class="post-content">
						<a id="browser-link" href="/index.php?page=images"  TITLE=$path>
						<img style="border: 5px solid #CCCCCC; text-decoration: none; background: #FFFFFF; 
							font-weight: normal;color: #53524E;" HSPACE=5; VSPACE=5; alt="" src="{$path}" class="alignnone" width="800" height="100%"></a>
		</div>
MESSAGE_DISPLAY;
}


function upload_myfile() {
	if ($_SESSION['loginuser'])
		$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
	// formular
	$message_display = <<<MESSAGE_DISPLAY
		<div style="text-align: center;" class="thePanel">
		<center>
		<br>
	    <form action="{$_SERVER['PHP_SELF']}?page=images" method="post" name="fileForm" id="fileForm" enctype="multipart/form-data">
		<h3>
		<a href="index.php?page=images">Upload file for the $myuser</a>
		</h3>
		<br/> 
		<br/>
        <table>
          <tr>
          <td>
          <input style="border: 2px solid #DFDFDF;" name="upfile" type="file" size="26">
		  <input style="background: #FFFFFF" class="text" type="submit" name="task" value="Upload">
		  </td>
		  </tr>
        </table> 
		</form>
		<br>
		</center>
		</div>
MESSAGE_DISPLAY;
	echo($message_display);
	
	if ( (isset($_POST['task'])) && ($_POST['task']=="Upload") ) {
		if ( $_POST['upfile'] ) $meno = $_POST['upfile'];
		//Windows way
		
		//if ($prefix==0) $pref=$myuser; else $pref="";
		
		$uploadLocation = $this->upload_dir .$meno; // fix and add myuser  $myuser . 
		//echo $uploadLocation;
		//Unix, Linux way
		//$uploadLocation = "\tmp";
		$meno = $_SESSION['loginuser'] . '/';
		$target_dir = $uploadLocation . $meno;
		if (!file_exists($target_dir )) {
			mkdir($target_dir);
		}
		$target_path = $target_dir  . basename( $_FILES['upfile']['name']);
		$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".js", ".shtml", ".pl" ,".py");
		foreach ($blacklist as $file) 
		{
			if(preg_match("/$file\$/i", $_FILES['upfile']['name'])) 
			{
				echo "Error, failed filename.\n";
				exit;
			}
		}
		
		// upload process
		if(move_uploaded_file($_FILES['upfile']['tmp_name'], $target_path)) {
				// echo "$Web" . "$WebFolder". $meno .  basename( $_FILES['upfile']['name']);
		} else{
				echo ("<div class=\"hehe\"><div class=\"smallfont\"><center><b><a href=\"/index.php?page=images\"><img src=\"./error.png\"><br><br>Error, while Uploading file! Refresh Page.</a></b></center></div></div>");
		}
	}
}

// this function need testing 
public function UploadImages() {
  // If upload button is clicked ... 
  if (isset($_POST['upload'])) {  
    $name = $_FILES['uploadfile']['name'];
	$target_dir = "upload/";
	$target_file = $target_dir . basename($_FILES["uploadfile"]["name"]);

	// Select file type
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	// Valid file extensions
	$extensions_arr = array("jpg","jpeg","png","gif");

	// Check extension
	if( in_array($imageFileType,$extensions_arr) ){
 
		// Convert to base64 
		$image_base64 = base64_encode(file_get_contents($_FILES['uploadfile']['tmp_name']) );
		$image = 'data:image/'.$imageFileType.';base64,'.$image_base64;
		// Insert record
		$this->switch_images_table();
		$crea = time();
		$q = "INSERT INTO images VALUES('$user',".$image.",'$crea')";
		$this->warp_query($q);
  
		// Upload file
		move_uploaded_file($_FILES['uploadfile']['tmp_name'],$target_dir.$name);
	}
  }
echo $message_display = <<<MESSAGE_DISPLAY
<style>
#content{ 
    width: 50%; 
    margin: 20px auto; 
    border: 1px solid #cbcbcb; 
} 
form{ 
    width: 50%; 
    margin: 20px auto; 
} 
form div{ 
    margin-top: 5px; 
} 
#img_div{ 
    width: 80%; 
    padding: 5px; 
    margin: 15px auto; 
    border: 1px solid #cbcbcb; 
} 
#img_div:after{ 
    content: ""; 
    display: block; 
    clear: both; 
} 
img{ 
    float: left; 
    margin: 5px; 
    width: 300px; 
    height: 140px; 
} 
</style> 
<div id="content"> 
  
	<form method="POST" action="" enctype="multipart/form-data"> 
		<input type="file" name="uploadfile" value=""/> 
        
		<div> 
          <button type="submit" name="upload">UPLOAD</button> 
        </div> 
	</form> 
</div>
MESSAGE_DISPLAY;
}


public function switch_images_table() {
    $this->warp_select_db($this->table) or die("Could not select database. " . $this->warp_error());
	
    return $this->build_images_db();
}
  
private function build_images_db() {
    $sql = <<<warp_QUERY
CREATE TABLE IF NOT EXISTS buffer (
user	VARCHAR(150),
image	VARCHAR(150),
Created	VARCHAR(100)
)
warp_QUERY;
    return $this->warp_query($sql);
}

public function display_formular($usr)
{
	$data="";
	if (file_exists($this->upload_dir."/".$usr."/formular.txt")) {
	    $handle = fopen($this->upload_dir."/".$usr."/formular.txt","r");
	    while (!feof($handle))
	    {
		$data .= fgets($handle, 512);
	    }
	    fclose($handle);
	}
	return $data;
}

function unlink_files($usr)
{
  if ($this->synapse_crypt_files==1) {
	  
	echo (" unlink files.. <br />");
	  
	$dir = $this->upload_dir . $usr . "/";
	  
	if(is_dir($dir))
	{
    if($handle = opendir($dir))
    {
		$id=0;
      while(($file = readdir($handle)) !== false)
      {
        if($file != "." && $file != ".." && $file != "Thumbs.db"/*pesky windows, images..*/)
        {	
			if (strpos($file, ".sif")!==false) { 
			} else {
				//echo (" Deleting ". $dir . $file . "<br />");
				$result=@unlink($dir . $file );
				//if($result==false) echo 'deleting file failed.<br />'; else echo 'deleting file $file OK<br />';
				
			}
		}
	  }
		closedir($handle);
	 }
	 }
   }
}

public function get_bodytext($bla, $crea, $usr) {
			$kluc = "";
			
			//inicializácia vnorenej-triedy
			//$crypt = new Crypt();
			$this->Mode = Crypt::MODE_HEX; // druh šifrovania
			$this->Key  = $this->glob_password; //kľúč
			$kluc = $this->decrypt($this->getpassword($usr, $crea)); // desifruje lubovolny zasifrovany retazec
			
			//$encrypted = $crypt->encrypt('lubovolny retazec'); // zasifruje lubovolny retazec
			$bla = $this->decrypt($bla); // desifruje lubovolny zasifrovany retazec
			
			return $bla;
}

public function display_bodytext($crea) {
		if ($this->editmode==0) {
			$this->switch_data_table();
		
			$q = $this->warp_query("SELECT Bodytext FROM data WHERE Created='$crea'");	
			$a = $this->warp_fetch_assoc($q);
			return $pgtxt = $a['Bodytext'];
		} else {
			$data = "";
			$handle = fopen("pages/bodytext-".$crea.".php","r"); 
			while (!feof($handle)) 
			{ 
				$data .= fgets($handle, 512);  
			} 
			fclose($handle);
			return $data; 
		}
}

public function display_user($crea) {
		if ($this->editmode==0) {
			$this->switch_data_table();
		
			$q = $this->warp_query("SELECT Username FROM data WHERE Created='$crea'");	
			$a = $this->warp_fetch_assoc($q);
			return $pgtxt = $a['Username'];
		} else {
			$data = "";
			$handle = fopen("pages/username-".$crea.".php","r"); 
			while (!feof($handle)) 
			{ 
				$data .= fgets($handle, 512);  
			} 
			fclose($handle);
			return $data; 
		}
	}

public function error_config($err) {
	include("./error.php");
}


public function time_elapsed_A($secs) {
    $bit = array(
        'y' => $secs / 31556926 % 12,
        'w' => $secs / 604800 % 52,
        'd' => $secs / 86400 % 7,
        'h' => $secs / 3600 % 24,
        'm' => $secs / 60 % 60,
        's' => $secs % 60
        );
       
    foreach($bit as $k => $v)
        if($v > 0)$ret[] = $v . $k;
       
    return join(' ', $ret);
}   

public function time_elapsed_B($secs) {
    $bit = array(
        ' year'        => $secs / 31556926 % 12,
        ' week'        => $secs / 604800 % 52,
        ' day'        => $secs / 86400 % 7,
        ' hour'        => $secs / 3600 % 24,
        ' minute'    => $secs / 60 % 60,
        ' second'    => $secs % 60
        );
       
    foreach($bit as $k => $v){
        if($v > 1)$ret[] = $v . $k . 's';
        if($v == 1)$ret[] = $v . $k;
        }
    array_splice($ret, count($ret)-1, 0, 'and');
    $ret[] = 'ago.';
   
    return join(' ', $ret);
}

/**
// $nowtime = time();
// $oldtime = 1335939007;

// echo "time_elapsed_A: ".time_elapsed_A($nowtime-$oldtime)."\n";
// echo "time_elapsed_B: ".time_elapsed_B($nowtime-$oldtime)."\n";

// time_elapsed_A: 6d 15h 48m 19s
// time_elapsed_B: 6 days 15 hours 48 minutes and 19 seconds ago.
**/

public function nicetime($date) {
    if(empty($date)) {
        return "No date provided";
    }
   
    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");
   
    $now             = time();
    $unix_date         = strtotime($date);
   
       // check validity of date
    if(empty($unix_date)) {   
        return "Bad date";
    }

    // is it future date or past date
    if($now > $unix_date) {   
        $difference     = $now - $unix_date;
        $tense         = "ago";
       
    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }
   
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
   
    $difference = round($difference);
   
    if($difference != 1) {
        $periods[$j].= "s";
    }
   
    return "$difference $periods[$j] {$tense}";
}

/**
// $date = "2009-03-04 17:45";
// $result = nicetime($date); // 2 days ago
**/

public function get_real_ip()
{
 if (isset($_SERVER)){
if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
if(strpos($ip,",")){
$exp_ip = explode(",",$ip);
$ip = $exp_ip[0];
}
}else if(isset($_SERVER["HTTP_CLIENT_IP"])){
$ip = $_SERVER["HTTP_CLIENT_IP"];
}else{
$ip = $_SERVER["REMOTE_ADDR"];
}
}else{
if(getenv('HTTP_X_FORWARDED_FOR')){
$ip = getenv('HTTP_X_FORWARDED_FOR');
if(strpos($ip,",")){
$exp_ip=explode(",",$ip);
$ip = $exp_ip[0];
}
}else if(getenv('HTTP_CLIENT_IP')){
$ip = getenv('HTTP_CLIENT_IP');
}else {
$ip = getenv('REMOTE_ADDR');
}
}
return $ip; 
}

function maintenance_break() {
	$ip = $this->get_real_ip();
	if (strstr($this->maintenance_ip, $ip)!==false) return 1; else return 0;
}


/*
 * Update synapse on the fly - get files from lukves.6f.sk site
 *
 */
private function update() {
$urls=array(
$this->master.'/config.php',
$this->master.'/me-sketch.png',
$this->master.'/init.php',
$this->master.'/index.php',
$this->master.'/index-offline.php',
$this->master.'/index-online.php',
$this->master.'/track.php',
$this->master.'/zones_cl.php',
$this->master.'/w3.css',
$this->master.'/other.css',
$this->master.'/error.php',
$this->master.'/error.png',
$this->master.'/License');
 
$save_to=$this->current_dir;
 
$mh = curl_multi_init();
foreach ($urls as $i => $url) {
    $g=$save_to.basename($url);
    if(!is_file($g)){
        $conn[$i]=curl_init($url);
        $fp[$i]=fopen ($g, "w");
        curl_setopt ($conn[$i], CURLOPT_FILE, $fp[$i]);
        curl_setopt ($conn[$i], CURLOPT_HEADER ,0);
        curl_setopt($conn[$i],CURLOPT_CONNECTTIMEOUT,60);
        curl_multi_add_handle ($mh,$conn[$i]);
    }
}
do {
    $n=curl_multi_exec($mh,$active);
}
while ($active);
foreach ($urls as $i => $url) {
    curl_multi_remove_handle($mh,$conn[$i]);
    curl_close($conn[$i]);
    fclose ($fp[$i]);
}
curl_multi_close($mh);
}

private function readInputFromFile($file)
{
   $fh = fopen($file, 'r');
   while (!feof($fh))
   {
      $ln = fgets($fh);
      $parts[] = $ln;
   }

   fclose($fh);

   return $parts;
}

function HashMe($phrase, &$salt = null)
{
	$key = '!@#$%^&*()_+=-{}][;";/?<>.,';
	
    if ($salt == '')
    {
        $salt = substr(hash('sha256',uniqid(rand(), true).$key.microtime()), 0, SALT_LENGTH); // 15
    }
    else
    {
        $salt = substr($salt, 0, SALT_LENGTH); // 15
    }

    return hash('sha256', $salt . $key .  $phrase);
}

/**
 ** WARP DB WRAPPER
 **
 ** 0 - Mysql
 ** 1 - Mysqli
 ** 2 - Postgresql
 **
 **/

var $warp;
var $con;


/*db_connect*/
public function warp_connect($host,$user,$pass) {
	//return mysql_connect($host,$user,$pass);
	switch ($this->warp) {
		case 0:
			$co = mysql_connect($host,$user,$pass);
			if (!$co) {
			    DEFINE('BZONES', FALSE);
			    echo "Failed to connect to MySQL: " . mysql_error();
			    //include('error.php');
			} else DEFINE('BZONES', TRUE);
			return $co;
		break;
		case 1:
			$co = mysqli_connect($host,$user,$pass,$this->table);
			// Check connection
			if (mysqli_connect_errno())
		        {
			  DEFINE('BZONES', FALSE);
			  echo "Failed to connect to MySQL: " . mysqli_connect_error();
			  //include('error.php');
			} else DEFINE('BZONES', TRUE);
			return $co;
		break;
		default:
			// postgree not implemented yet
		break;
	}
}

/*error*/
public function warp_error() {
	//return mysql_error();
	switch ($this->warp) {
		case 0:
			return mysql_error();
		break;
		case 1:
			return mysqli_error($this->con);
			// echo("Error description: " . mysqli_error($this->con));
		break;
		default:
			// postgree not implemented yet
		break;
	}
}

/*query*/
public function warp_query($que) {
	//return mysql_query($que);
	switch ($this->warp) {
		case 0:
			return mysql_query($que);
		break;
		case 1:
			$result = mysqli_query($this->con, $que);
			if ($result === TRUE) return $result;
			else {
					// printf("Error: %s\n", mysqli_error($this->con));
					return $result;
			}
		break;
		default:
			// postgree not implemented yet
		break;
	}
}

/*num_fields*/
public function warp_num_fields($res) {
	//return mysql_num_fields($res);
	switch ($this->warp) {
		case 0:
			return mysql_num_fields($res);
		break;
		case 1:
			return mysqli_num_fields($res);
		break;
		default:
			// postgree not implemented yet
		break;
	}
}

/*num_fields*/
public function warp_num_rows($res) {
	//return mysql_num_rows($res);
	switch ($this->warp) {
		case 0:
			return mysql_num_rows($res);
		break;
		case 1:
			return mysqli_num_rows($res);
		break;
		default:
			// postgree not implemented yet
		break;
	}
}

/*fetch_assoc*/
public function warp_fetch_assoc($res) {
	//return mysql_fetch_assoc($res);
	switch ($this->warp) {
		case 0:
			return mysql_fetch_assoc($res);
		break;
		case 1:
			return mysqli_fetch_assoc($res);
		break;
		default:
			// postgree not implemented yet
		break;
	}
}

/*fetch_row*/
public function warp_fetch_row($res) {
	//return mysql_fetch_row($res);
	switch ($this->warp) {
		case 0:
			return mysql_fetch_row($res);
		break;
		case 1:
			return mysqli_fetch_row($res);
		break;
		default:
			// postgree not implemented yet
		break;
	}
}

/*select_db*/
public function warp_select_db($name) {
	//return mysql_select_db($name);
	switch ($this->warp) {
		case 0:
			return mysql_select_db($name);
		break;
		case 1:
			return mysqli_select_db($this->con, $name);
		break;
		default:
			// postgree not implemented yet
		break;
	}
}

/*real_escape_string*/
public function warp_real_escape_string($res) {
	//return mysql_real_escape_string($res);
	switch ($this->warp) {
		case 0:
			return mysql_real_escape_string($res);
		break;
		case 1:
			return mysqli_real_escape_string($this->con, $res);
		break;
		default:
			// postgree not implemented yet
		break;
	}
}

/*fetch_array*/
public function warp_fetch_array($res) {
	//return mysql_fetch_assoc($res);
	switch ($this->warp) {
		case 0:
			return mysql_fetch_array($res);
		break;
		case 1:
			return mysqli_fetch_array($res);
		break;
		default:
			// postgree not implemented yet
		break;
	}
}

/* backup the db OR just a table
 * 
 * backup_tables('localhost','username','password','blog');
 * 
 **/
public function backup_tables($host,$user,$pass,$name,$tables = '*')
{
  
  $link = $this->warp_connect($host,$user,$pass);
  $this->warp_select_db($name,$link);
  
  //get all of the tables
  if($tables == '*')
  {
    $tables = array();
    $result = $this->warp_query('SHOW TABLES');
    while($row = $this->warp_fetch_row($result))
    {
      $tables[] = $row[0];
    }
  }
  else
  {
    $tables = is_array($tables) ? $tables : explode(',',$tables);
  }
  
  //cycle through
  foreach($tables as $table)
  {
    $result = $this->warp_query('SELECT * FROM '.$table);
    $num_fields = $this->warp_num_fields($result);
    
    $return.= 'DROP TABLE '.$table.';';
    $row2 = $this->warp_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
    $return.= "\n\n".$row2[1].";\n\n";
    
    for ($i = 0; $i < $num_fields; $i++) 
    {
      while($row = $this->warp_fetch_row($result))
      {
        $return.= 'INSERT INTO '.$table.' VALUES(';
        for($j=0; $j<$num_fields; $j++) 
        {
          $row[$j] = addslashes($row[$j]);
          $row[$j] = ereg_replace("\n","\\n",$row[$j]);
          if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
          if ($j<($num_fields-1)) { $return.= ','; }
        }
        $return.= ");\n";
      }
    }
    $return.="\n\n\n";
  }
  
  //save file
  $handle = fopen($this->upload_dir.'/'.$this->getadmin().'/db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
  fwrite($handle,$return);
  fclose($handle);
}

/**
 ** USER MANAGEMENT
 **
 **/


/* @info  delete friend from blacklist by message id == crea posted from formular */
public function deletefrom_blacklist($blocked) {
	if ($_SESSION['loginuser']) {
		$this->switch_blacklist_table();
		$q = "DELETE FROM blacklist WHERE Blocked='$blocked'";
		$r = $this->warp_query($q);
		echo("<div class=\"hehe\"><div class=\"smallfont\"><center><b><a href=\"index.php\">Refresh Page</a></b></center></div></div><script>reloadPage();</script>");
	}
}

/* @info  delete friend from blacklist by crea */
public function deletefrom_blacklist_bycrea($crea) {
	if ($_SESSION['loginuser']) {
		$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
	
		//if ( $_POST['karma_index'] )
		//$crea = $this->warp_real_escape_string($_POST['karma_index']);
		$this->switch_blacklist_table();
		
		$q = "DELETE FROM blacklist WHERE Created='$crea'";
		$r = $this->warp_query($q);
		echo("<div class=\"hehe\"><div class=\"smallfont\"><center><b><a href=\"index.php\">Refresh Page</a></b></center></div></div><script>reloadPage();</script>");
	}
}

// @info add friend to blacklist
public function addto_blacklist($p) {
	if ($_SESSION['loginuser']) {
		$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
		// add to blacklist
		if ( (isset($_POST['synapse-blocked'])) && (isset($_POST['synapse-blockedinfo'])) ) { 
			$blocked = htmlspecialchars($_POST['synapse-blocked']);
			$inform = htmlspecialchars($_POST['synapse-blockedinfo']);
				$crea = time();
		
			if ($inform==1) {
				$this->switch_blacklist_table();
			
				$q = "INSERT INTO blacklist VALUES('$myuser','$blocked','$inform','$crea','$crea')";
				$r = $this->warp_query($q);
				
				echo("<div class=\"hehe\"><div class=\"smallfont\"><center><b><a href=\"index.php\">Refresh Page</a></b></center></div></div><script>reloadPage();</script>");
			} else if ($inform==2) $this->deletefrom_blacklist($blocked);
		} // echo("<div class=\"hehe\"><div class=\"smallfont\"><center><b><a href=\"index.php\">[error] Refresh Page</a></b></center></div></div><script>reloadPage();</script>");
	}
}

public function blacklisted($crea) {
	if ($_SESSION['loginuser']) {
		$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
		
		$this->switch_blacklist_table();

		$checkem = $this->warp_query("SELECT Username FROM blacklist WHERE Blocked='$crea'");	
		$a = $this->warp_fetch_assoc($checkem);
		$usr = $a['Username'];
		//$inf = $a['inform'];
	
		// && ($inf == 1)
		if ($usr == $myuser ) return 1;
			else 
				return 0;
	} else return 0;
}

public function switch_blacklist_table() {
    $this->warp_select_db($this->table) or $this->error_config("Could not select database. " . $this->warp_error()); // die("Could not select database. " . mysql_error());
	
    return $this->build_blacklist_db();
}
  
private function build_blacklist_db() {
    $sql = <<<warp_QUERY
CREATE TABLE IF NOT EXISTS blacklist (
Username	    VARCHAR(150),
Blocked     	VARCHAR(150),
Inform		    INT(16),
Lasttime    VARCHAR(150),
Created		INT(24)
)
warp_QUERY;
    return $this->warp_query($sql);
}
// ** user related database functions like switch, build
// simple and enhaced database model **
public function switch_users_table() {
	$this->warp_select_db($this->table) or $this->error_config("Could not select database. " . $this->warp_error()); // die("Could not select database. " . mysql_error());

    return $this->build_users_db();
}

public function switch_passwords_table() {
	$this->warp_select_db($this->table) or $this->error_config("Could not select database. " . $this->warp_error()); // die("Could not select database. " . mysql_error());

    return $this->build_passwords_db();
}

private function build_passwords_db() {
		$sql = <<<warp_QUERY
CREATE TABLE IF NOT EXISTS passwords (
Username	VARCHAR(150),
Password	VARCHAR(150),
Salt		VARCHAR(150),
Firsttime	VARCHAR(150),
Lasttime	VARCHAR(150),
Created		INT(24)
)
warp_QUERY;

		return $this->warp_query($sql);
}
  
private function build_users_db() {
		$sql = <<<warp_QUERY
CREATE TABLE IF NOT EXISTS users (
Username	VARCHAR(150),
Password	VARCHAR(150),
Salt		VARCHAR(150),
Email		VARCHAR(150),
Name			VARCHAR(150),
Surname			VARCHAR(150),
Visible		VARCHAR(150),
Avatar		VARCHAR(150),
Inform		    	TEXT,
Sex			TEXT,
Gender		TEXT,
Favourite	TEXT,
Interest	TEXT,
Privacy		INT(11),
Location	VARCHAR(150),
birthday_day		INT(11),
birthday_month		INT(11),
birthday_year		INT(11),
Lasttime	VARCHAR(150),
Created		INT(24)
)
warp_QUERY;

		return $this->warp_query($sql);
}

public function connect_db() {
	$this->con = $this->warp_connect($this->host,$this->username,$this->password) or include("./error.php");
}


var $userslist;
public function usersarray() {
	$this->switch_users_table();

	$q = "SELECT * FROM users ORDER BY created DESC LIMIT 2048";
    $r = $this->warp_query($q);
	
	$userArray = null;
	
    if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
		while ( $a = $this->warp_fetch_assoc($r) ) {
			$user = stripslashes($a['Username']);
			$em = stripslashes($a['Email']);
			$crea = stripslashes($a['Created']);
			$userArray[] = $user;
		}
	}
	return $userArray;
}

public function Firsttime_init() {
	$adm_user = $this->getadmin();
	$adm_email = $this->getemail($adm_user);
	$adm_name = $this->getname($adm_user);
	$adm_surname = $this->getsurname($adm_user);
	$adm_gender = $this->getgender($adm_user);
	
	$crea = time();

	$datum = StrFTime("%d/%m/%Y %H:%M:%S", $crea);
	$date = StrFTime("%Y-%m-%d %H:%M", $crea);   // "2009-03-04 17:45";
	//$result = $obj->nicetime($date); // 2 days ago 			
	// over heslo, a ak je spravne
	$adm_password = $this->adminemail[$adm_email];
	
	$rv = "1";
	foreach($this->zones as $x=>$x_value)
		{
			if ($this->IfExistUser($x)==0) $this->userRegister($adm_name,$adm_surname,$x,$adm_password,$adm_password,$adm_email,$adm_gender,$rv);
		}
	
	// $this->ResetZones("lukas11");
}

public function write_registration($p) {
	if (($this->maintenance == 99) && ($this->maintenance_break() == 0)) {
		echo $entry_display = <<<ADMIN_FORM
			<div class="hehe"><div class="smallfont"><center><b><a href="/index.php"><img src="{$this->current_dir}/error.png"><br><br>. This site is down for maintenance.<br> Please check back again soon.</p>. Refresh Page</a></b></center></div></div><script></script>
ADMIN_FORM;
	} else {
		$rv = "0";
		return $this->userRegister($_POST['name'], $_POST['surname'], $_POST['nick'],$_POST['password'],$_POST['repassword'],$_POST['email'],$_POST['gender'],$rv);
	}
}

private function userRegister($rname, $rsurname, $rusername,$rpassword,$rcpassword,$remail,$rgender,$rvisib) {
	$this->switch_users_table();
	/*
	$entry_display = <<<ADMIN_FORM
				<div class="hehe"><div class="smallfont"><center><b><a href="index.php">Registration of user</a></b></center></div></div>
ADMIN_FORM;
			echo ($entry_display);
	*/
    //$con = mysql_connect($this->host,$this->username,$this->password) or die("Could not connect. " . mysql_error());
	//if (!$con)
    //{
    //die('Could not connect: ' . mysql_error());
    //}
	//$this->warp_select_db($this->table) or die('Could not connect: ' . mysql_error());
	
	//$this->build_logins_db();
	
	//echo ("{$rusername} : {$rpassword} : {$rcpassword} : {$remail}<br>");
	
	// test for incorect chars in regname like :,;,/,?
	if ( (strpos($rusername,":")!==false)||(strpos($rusername,";")!==false)||(strpos($rusername,"?")!==false)||(strpos($rusername,"<")!==false)||(strpos($rusername,">")!==false)||
		 (strpos($rusername,",")!==false)||(strpos($rusername,"/")!==false)||(strpos($rusername,".")!==false)||(strpos($rusername,"-")!==false)||(strpos($rusername,"+")!==false)||
		 (strpos($rusername,"=")!==false)||(strpos($rusername,"!")!==false)||(strpos($rusername,"@")!==false)||(strpos($rusername,"#")!==false)||(strpos($rusername,"$")!==false)||
		 (strpos($rusername,"%")!==false)||(strpos($rusername,"^")!==false)||(strpos($rusername,"&")!==false)||(strpos($rusername,"*")!==false)||(strpos($rusername,"(")!==false)||
		 (strpos($rusername,")")!==false)||(strpos($rusername,"|")!==false)||(strpos($rusername,"{")!==false)||(strpos($rusername,"}")!==false)||(strpos($rusername,"'")!==false)||
		 (strpos($rusername,"~")!==false)||(strpos($rusername,"_")!==false) ) {
			$entry_display = <<<ADMIN_FORM
				<p><a href="index.php">Failed. Refresh Page</a></p><script>reloadPage();</script>
ADMIN_FORM;
			echo ($entry_display);
			return null;
	}
	
    if ($rname==NULL||$rsurname==NULL||$rusername==NULL||$rpassword==NULL||$rcpassword==NULL||$rgender==NULL||$remail==NULL)
    {
	//checks to make sure no fields were left blank
	echo "A field was left blank.";
	return null;
    } else if($rpassword != $rcpassword) {
        // the passwords are not the same!  
        echo "Passwords do not match";
	return null; 
    } else {
		$salt = '';
        //passwords match! We continue...
		$rpassword = $this->HashMe($rpassword, $salt);
				
						
		// crypt or decrypt
		//inicializácia vnorenej-triedy
		$this->Mode = Crypt::MODE_HEX; // druh šifrovania
		if (isset($this->glob_password)) $this->Key  = $this->glob_password; //kľúč
		$rpassword = $this->encrypt($rpassword); // zasifruje lubovolny retazec
		        
        $checkname = $this->warp_query("SELECT username FROM users WHERE Username='$rusername'");
        $checkname= $this->warp_num_rows($checkname);
        
	// ak sa jedna o registraciu obycajneho uzivatela tak chekni ci nieje uz zaregistrovany 
	// pod tymto emailom aby neboli dvaja uzivatelia s rovnakym emailom
	// 
	// uzivatel moze zaregistrovat aj viacej kanalov takze email nezalezi
	//
	$checkemail = 0;
	if ($rvisib==0) {
		$checkemail = $this->warp_query("SELECT email FROM users WHERE Email='$remail'");
        $checkemail = $this->warp_num_rows($checkemail);
    }
	
	if (($checkemail>0) || ($checkname>0)) {
	// oops...someone has already registered with that username or email!

		$entry_display = <<<ADMIN_FORM
		<p><a href="index.php">Failed. Refresh Page</a></p><script>reloadPage();</script>
ADMIN_FORM;
		echo ($entry_display);
	} else {
		// noone is using that email or username!  We continue...
		$rusername = strip_tags($rusername);
		$rpassword = strip_tags($rpassword);
		$remail = strip_tags($remail);
		$rcreated = time();
		
		//echo ("{$rusername} : {$rpassword} : {$rcpassword} : {$remail}<br>");
		
		//$rvisib = "0";
		
        $this->warp_query("INSERT INTO users VALUES ('$rusername', '$rpassword', '$salt', '$remail', '$rname', '$rsurname', '$rvisib', '', '', '$rgender', '$rgender', '', '', NULL, '', NULL, NULL, NULL, '', '$rcreated')");

		// Send Info Mail to New Registered User
//		if ($rvisib==0) mail ($remail, "Registration to Synapse.", "You are regitered with username: $rusername to Synapse social network portal. Good Luck.");
		
		
		// create backup of table with new user
		// $this->backup_tables($this->host,$this->username,$this->password,'users'); 
				
		
		$entry_display = <<<ADMIN_FORM
			<p><a href="index.php">OK. Refresh Page</a></p><script>loginPage();createCookie("actualPage", "login", 10);alert(readCookie("actualPage"));</script>
ADMIN_FORM;
		echo ($entry_display);
	}
  }
}

public function write_login($p) {
	//echo ("{$_POST['login_username']} : {$_POST['login_password']} : {$_POST['login_repassword']} : {$_POST['login_email']}<br>");
	return $this->userLogin($_POST['nick'],$_POST['password']);
}

private function userLogin($rusername,$rpassword) {
    //$con = mysql_connect($this->host,$this->username,$this->password) or die("Could not connect. " . mysql_error());
	//if (!$con)
    //{
    //die('Could not connect: ' . mysql_error());
    //}
	//$this->warp_select_db($this->table) or die('Could not connect: ' . mysql_error());
	
	//$this->build_logins_db();
	
	//echo ("{$rusername} : {$rpassword} : {$rcpassword} : {$remail}<br>");
	
	$this->switch_users_table();
	
    if ($rusername==NULL||$rpassword==NULL)
    {
		//checks to make sure no fields were left blank
		echo "A field was left blank.";    
	}

	// Test for email or user string
	if (strstr($rusername, "@")!==false) {
		//find user by email
		$checkname = $this->warp_query("SELECT username FROM users WHERE Email='$rusername'");
		$r = $this->warp_fetch_assoc($checkname);
		$rusername = $r['username'];
	} else
		//find user by name
		$checkname = $this->warp_query("SELECT username FROM users WHERE Username='$rusername'");

        $checkname= $this->warp_num_rows($checkname);
		
	if ($checkname>0) {
				//make hash of submited pass
				// $rpassword = md5($rpassword);// crypt or decrypt
				$checksalt = $this->warp_query("SELECT salt FROM users WHERE Username='$rusername'");	
				$a = $this->warp_fetch_assoc($checksalt);
				$salt = $a['salt'];
				$rpassword = $this->HashMe($rpassword, $salt);
				
				//
				//inicializácia vnorenej-triedy
				$this->Mode = Crypt::MODE_HEX; // druh šifrovania
				if (isset($this->glob_password)) $this->Key  = $this->glob_password; //kľúč
				$rpassword = $this->encrypt($rpassword); // zasifruje lubovolny retazec
			
			// password compare
			$checkpass = $this->warp_query("SELECT password FROM users WHERE Username='$rusername'");	
			$a = $this->warp_fetch_assoc($checkpass);
			$rcpassword = $a['password'];
			//echo ("<center><big>====== {$rusername} :: {$rcpassword} =====</big></center>");
			if($rpassword == $rcpassword) {
				// all good.. lets go.
				//$_SESSION['refreshid']=5;
				//return $rusername;
				//setcookie("refreshid",0);
				//$_COOKIE['refreshid'] = 5;
				//$_COOKIE['loginuser'] = $rusername;
				//setcookie("loginuser",$rusername);
		$entry_display = <<<ADMIN_FORM
				<p><a href="index.php">Sign in {$rusername}, Refresh Page</a></p><script>pagePage();reloadPage();</script>
ADMIN_FORM;
		echo ("$entry_display");
				return $rusername;
			} else { 
				$entry_display = <<<ADMIN_FORM
				<p><a href="index.php">Sorry. Refresh Page</a></p><script>reloadPage();</script>				
ADMIN_FORM;
				echo ("$entry_display");
				return null; 
			}
	} else { 
				$entry_display = <<<ADMIN_FORM
				<p><a href="index.php">Sorry. Refresh Page</a></p><script>newsPage();reloadPage();</script>
ADMIN_FORM;
				echo ("$entry_display");
				return null; 
	}
}

public function userlasttime() {
	$this->switch_users_table();

	$lastinf = "";

	if ($_SESSION['loginuser']) {
      $myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
	
		$q = $this->warp_query("SELECT Lasttime FROM users WHERE Username='$myuser'");	
		$a = $this->warp_fetch_assoc($q);
		$lastinf = $a['Lasttime'];
	}
	
	return $lastinf;
}

// @info add password to password list with Firsttime and Listtime when will be used
private function addto_passwordlist($p) {
	if ($_SESSION['loginuser']) {
		$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
		// get requested old parameters
		$this->switch_users_table();

		$checkem = $this->warp_query("SELECT password FROM users WHERE Username='$myuser'");	
		$a = $this->warp_fetch_assoc($checkem);
		$rcpassword = $a['password'];
		
		$checkem = $this->warp_query("SELECT salt FROM users WHERE Username='$myuser'");	
		$a = $this->warp_fetch_assoc($checkem);
		$rcsalt = $a['salt'];
		
		$checkem = $this->warp_query("SELECT created FROM users WHERE Username='$myuser'");	
		$a = $this->warp_fetch_assoc($checkem);
		$first = $a['created'];
		
		// add to passwordlist
		$crea = time();
		
		$last = $crea;
		
		$this->switch_passwords_table();
			
		$q = "INSERT INTO passwords VALUES('$myuser','$rcpassword','$rcsalt','$first','$last','$crea')";
		$r = $this->warp_query($q);
	}
}

// NOTE: update user password informations in database  
public function update_passwordsettings($p) {
	if ($_SESSION['loginuser'])
		$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
	
	if ( (!empty($_POST['reg_password'])) && (!empty($_POST['reg_repassword'])) ) { 
		$pwdopt = $_POST['reg_password'];
		$repwdopt = $_POST['reg_repassword'];
		
		if ($pwdopt==$repwdopt) {
			// addto_password list
			$this->addto_passwordlist();
			// change password
				
			$salt = '';
			$pwdopt = $this->HashMe($pwdopt, $salt);
						
				
			// crypt or decrypt
			//inicializácia vnorenej-triedy
			$this->Mode = Crypt::MODE_HEX; // druh šifrovania
			if (isset($this->glob_password)) $this->Key  = $this->glob_password; //kľúč
			$rpassword = $this->encrypt($pwdopt); // zasifruje lubovolny retazec
			
			$this->switch_users_table();
			$q = "UPDATE users SET Password='$rpassword' WHERE Username = '$myuser'";
			$r = $this->warp_query($q);
			$rtime = time();
			$q = "UPDATE users SET Created='$rtime' WHERE Username = '$myuser'";
			$r = $this->warp_query($q);
			$q = "UPDATE users SET Salt='$salt' WHERE Username = '$myuser'";
			$r = $this->warp_query($q);
				
			echo("<div class=\"hehe\"><div class=\"smallfont\"><center><b><a href=\"index.php\">Password Changed! Refresh Page</a></b></center></div></div>");
		}
	}
}

private function ResetZones($globpass) {
	if ($_SESSION['loginuser'])
		$myuser = mysql_real_escape_string($_SESSION['loginuser']);
	
	$rv = "1";
	foreach($this->zones as $x=>$x_value)
		{
			if ($this->IfExistUser($x)==1) {
				$pwdopt = $globpass;
				
				$salt = '';
				$pwdopt = $this->HashMe($pwdopt, $salt);
				
				// crypt or decrypt
				//inicializácia vnorenej-triedy
				$this->Mode = Crypt::MODE_HEX; // druh šifrovania
				if (isset($this->glob_password)) $this->Key  = $this->glob_password; //kľúč
				$rpassword = $this->encrypt($pwdopt); // zasifruje lubovolny retazec
			
				$this->switch_users_table();
				$q = "UPDATE users SET Password='$rpassword' WHERE Username = '$x'";
				$r = $this->warp_query($q);
				$rtime = time();
				$q = "UPDATE users SET Created='$rtime' WHERE Username = '$x'";
				$r = $this->warp_query($q);
				$q = "UPDATE users SET Salt='$salt' WHERE Username = '$x'";
				$r = $this->warp_query($q);
			}
		}
}

// NOTE: update user informations in database  
public function update_usersettings($p) {
	if ($_SESSION['loginuser'])
		$myuser = mysql_real_escape_string($_SESSION['loginuser']);
		
	if (!empty($_POST['usersettings_opt'])) { 
		$usropt = $_POST['usersettings_opt'];
		
		$this->switch_users_table();
		$q = "UPDATE users SET Avatar='$usropt' WHERE Username = '$myuser'";
		$r = $this->warp_query($q);
	}
	
	if (!empty($_POST['usersettings_name'])) { 
		$usrname = $_POST['usersettings_name'];
		
		$this->switch_users_table();
		$q = "UPDATE users SET Name='$usrname' WHERE Username = '$myuser'";
		$r = $this->warp_query($q);
	}
	
	if (!empty($_POST['usersettings_surname'])) { 
		$usrsname = $_POST['usersettings_surname'];
		
		$this->switch_users_table();
		$q = "UPDATE users SET Surname='$usrsname' WHERE Username = '$myuser'";
		$r = $this->warp_query($q);
	}
	
	//if (!empty($_POST['usersettingsbg_opt'])) { 
		//$usrbgopt = $_POST['usersettingsbg_opt'];
		
		//$_SESSION['bodycss']=$usrbgopt;
				
		//$this->switch_users_table();
		//$q = "UPDATE users SET Avatar='$usropt' WHERE Username = '$myuser'";
		//$r = $this->warp_query($q);
	//}

	if (!empty($_POST['usersettings_sex'])) { 
		$sexopt = $_POST['usersettings_sex'];
		
		$this->switch_users_table();
		$q = "UPDATE users SET Sex='$sexopt' WHERE Username = '$myuser'";
		$r = $this->warp_query($q);
	}
	
	if (!empty($_POST['usersettings_gender'])) { 
		$gendopt = $_POST['usersettings_gender'];
		
		$this->switch_users_table();
		$q = "UPDATE users SET Gender='$gendopt' WHERE Username = '$myuser'";
		$r = $this->warp_query($q);
	}
	
	if (!empty($_POST['usersettings_location'])) { 
		$locaopt = $_POST['usersettings_location'];
		
		$this->switch_users_table();
		$q = "UPDATE users SET Location='$locaopt' WHERE Username = '$myuser'";
		$r = $this->warp_query($q);
	}
	
	if (!empty($_POST['usersettings_favourite'])) { 
		$favouropt = $_POST['usersettings_favourite'];
		
		$this->switch_users_table();
		$q = "UPDATE users SET Favourite='$favouropt' WHERE Username = '$myuser'";
		$r = $this->warp_query($q);
	}
	
	if (!empty($_POST['usersettings_interest'])) { 
		$intopt = $_POST['usersettings_interest'];
		
		$this->switch_users_table();
		$q = "UPDATE users SET Interest='$intopt' WHERE Username = '$myuser'";
		$r = $this->warp_query($q);
	}
	
	if (!empty($_POST['usersettings_info'])) { 
		$infopt = $_POST['usersettings_info'];
		
		$this->switch_users_table();
		$q = "UPDATE users SET Inform='$infopt' WHERE Username = '$myuser'";
		$r = $this->warp_query($q);
	}
	
	echo("<div class=\"hehe\"><div class=\"smallfont\"><center><b><a href=\"index.php\">Refresh Page</a></b></center></div></div>");
}

// NOTE: update user password informations in database  
public function update_privacysettings($p) {
	if ($_SESSION['loginuser'])
		$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
	
	if (!empty($_POST['usersettings_privacy'])) { 
		$privopt = $_POST['usersettings_privacy'];
		
		$this->switch_users_table();
		$q = "UPDATE users SET Privacy='$privopt' WHERE Username = '$myuser'";
		$r = $this->warp_query($q);
	}
	
	echo("<div class=\"hehe\"><div class=\"smallfont\"><center><b><a href=\"index.php\">Refresh Page</a></b></center></div></div>");
}

public function isadmin($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT email FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcemail = $a['email'];
		
	// ak existuje pouzivatel v poli pouzivatelov
	if (isset($this->adminemail[$rcemail])) {
		return 1;
	}
	
	return 0;
}

public function getadmin() {
	$this->switch_users_table();
    
	$q = "SELECT * FROM users ORDER BY created DESC LIMIT 2048";
    $r = $this->warp_query($q);
	
    if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
		while ( $a = $this->warp_fetch_assoc($r) ) {
			$user = stripslashes($a['Username']);
			$em = stripslashes($a['Email']);
			$crea = stripslashes($a['Created']);
			
			if ($this->isadmin($user)==1) {
				return $user;
			}
		}
	}
	return null;
}

public function getfavourite($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT favourite FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcinf = $a['favourite'];
	
	return $rcinf;
}

public function getinterest($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT interest FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcinf = $a['interest'];
	
	return $rcinf;
}


public function getgender($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT gender FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcinf = $a['gender'];
	
	return $rcinf;
}


public function getsex($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT sex FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcinf = $a['sex'];
	
	return $rcinf;
}

private function delete_user($usr) {
	if ($_SESSION['loginuser']) {
		// $usr = $_SESSION['loginuser'];
	
		$this->switch_users_table();
		$q = "DELETE FROM users WHERE username = '$usr'";
		$r = $this->warp_query($q);
		
		$_SESSION['loginuser'] = null; 
		unset($_SESSION['loadpage']);
		
		echo("<div class=\"hehe\"><div class=\"smallfont\"><center><b><a href=\"index.php\">User Deleted !!! Thanks for using {$this->title}</a></b></center></div></div>");
		return true;
	}
}

// about avatar, info
public function display_users() {
	$this->switch_users_table();
	
	$entry_display = <<<MESSAGE_FORM
MESSAGE_FORM;
	
	//if ($_SESSION['loginuser'])
	//		$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
	
	$q = "SELECT * FROM users ORDER BY created DESC LIMIT 2048";
	$r = $this->warp_query($q);
	
	if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
		while ( $a = $this->warp_fetch_assoc($r) ) {
			$user = stripslashes($a['Username']);
			$em = stripslashes($a['Email']);
			$crea = stripslashes($a['Created']);
			
					$txt = $this->getinform($user);
					$av = $this->getimgavatar($user);
					
					if ($this->isvisible($user)==1) {
						$hyper = "<a href=\"index.php?channel=$user\">{$user}</a>";
					} else $hyper = "<a href=\"".$this->current_dir."blog.php?user={$user}\">{$user}</a>";
			
					$entry_display .= <<<MESSAGE_FORM
						<br>
						<div class="tweet"> 
						<img class="avatar" src="{$av}" alt="twitter"> 
						<img class="bubble" src="./images/speech_bubble.png" alt="bubble">
						
						<div class="text" style="width: 85%;"> 
						<h2>{$user}</h2>
						<br>
						{$txt} 
						<br>
						<br>
						<span>Link to {$hyper} pressentation page.</span> 
						<br>
						</div>
					
						</div>
						<br>
MESSAGE_FORM;
			
		}
	}
	return $entry_display;
}

public function IfExistUser($myuser) {
	$this->switch_users_table();
    
	$q = "SELECT * FROM users ORDER BY created DESC LIMIT 2048";
    $r = $this->warp_query($q);
	
    if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
		while ( $a = $this->warp_fetch_assoc($r) ) {
			$user = stripslashes($a['Username']);
			$em = stripslashes($a['Email']);
			$crea = stripslashes($a['Created']);
			
			if ($user==$myuser) {
				return 1;
			}
		}
	}
	return 0;
}

public function IfExistZone($zn) {
	$test = 0;
	foreach($this->zones as $x=>$x_value)
		{
			if ($zn==$x) $test = 1;
		}
	if ( ($this->IfExistUser($zn)) && ($test==1) ) return 1; else return 0;
}

public function IfExistZoneInArray($zn) {
	$test = 0;
	foreach($this->zones as $x=>$x_value)
		{
			if ($zn==$x) $test = 1;
		}
	if ($test==1) return 1; else return 0;
}

// 0 - Anyone
// 1 - Members
// 2 - Just My Friends
public function getprivacy($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT privacy FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcinf = $a['privacy'];
	
	return $rcinf;
}

// NOTE: isuser online
public function isvisible($myus) {
	$this->switch_users_table($_POST);
 
	$checkem = $this->warp_query("SELECT visible FROM users WHERE Username='$myus'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcvisib = $a['visible'];
	
	if ($rcvisib==0) return 0; else return 1;
}

// last refresh, compare to times
public function lasttime_test($tim) {
	if ($_SESSION['loginuser'])
			$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
	
	$this->switch_users_table($_POST);
 
	$checkem = $this->warp_query("SELECT lasttime FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rctime = $a['lasttime'];
	
	if ($tim > $rctime) return 1; else return 0;
}

public function getcreated($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT created FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcinf = $a['created'];
	
	return $rcinf;
}

public function getname($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT name FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcinf = $a['name'];
	
	return $rcinf;
}

public function getinform($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT inform FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcinf = $a['inform'];
	
	if (strlen($rcinf)==0) return "<a href=\"index.php?page=infos\"></a>";
		else
	
	return $rcinf;
}

public function getlocation($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT location FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcinf = $a['location'];
	
	return $rcinf;
}

public function getsurname($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT surname FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcinf = $a['surname'];
	
	return $rcinf;
}

public function getimgavatar($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT avatar FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	
	if ($a['avatar']) {
		$rcavat =  $this->upload_dir . $myuser . "/" .  $a['avatar'];
		if (file_exists( $rcavat )) {
			return $rcavat;
		} else return "./images/silhouette.png";
	} else return "./images/silhouette.png";
}

public function getavatar($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT avatar FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcavat =  $a['avatar'];

	return $rcavat;
}

public function getemail($myuser) {
	$this->switch_users_table();

	$checkem = $this->warp_query("SELECT email FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcinf = $a['email'];
	
	return $rcinf;
}

// how many users
public function howmanyusers() {
	$this->switch_users_table();
    
	$q = "SELECT * FROM users ORDER BY created DESC LIMIT 2048";
    $r = $this->warp_query($q);
	$id=0;
    if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
		while ( $a = $this->warp_fetch_assoc($r) ) {
			$user = stripslashes($a['Username']);
			if ($this->IfExistZone($user)==0) $id++;
		}
	}
	return $id;
}

// how many channels
public function howmanyzones() {
	$this->switch_users_table();
    
	$q = "SELECT * FROM users ORDER BY created DESC LIMIT 2048";
    $r = $this->warp_query($q);
	$id=0;
    if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
		while ( $a = $this->warp_fetch_assoc($r) ) {
			$user = stripslashes($a['Username']);
			if ($this->IfExistZone($user)==1) $id++;
		}
	}
	return $id;
}

public function getpassword($myuser, $crea) {
	if ($crea != 0) {
		$this->switch_passwords_table();

		$q = "SELECT * FROM passwords ORDER BY created";
		$r = $this->warp_query($q);
	
		if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
			while ( $a = $this->warp_fetch_assoc($r) ) {
				$u = stripslashes($a['Username']);
				$p = stripslashes($a['Password']);
				//$s = stripslashes($a['Salt']);
				$f = stripslashes($a['Firsttime']);
				$l = stripslashes($a['Lasttime']);
				$c = stripslashes($a['Created']);
				if (($crea >= $f)&&($crea <= $l)) {
					if ($myuser==$u) return $p;
				}
			}
		}
	}
		$this->switch_users_table();

		$checkem = $this->warp_query("SELECT password FROM users WHERE Username='$myuser'");	
		$a = $this->warp_fetch_assoc($checkem);
		$rcinf = $a['password'];
	
		return $rcinf;
}

public function isdisabled($myus) {
	$this->switch_users_table($_POST);
 
	$checkem = $this->warp_query("SELECT visible FROM users WHERE Username='$myus'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcvisib = $a['visible'];
	
	if ($rcvisib==99) return 1; else return 0;
}

private function disable_user($usr) {
		$this->switch_users_table();
		$q = "UPDATE users SET Visible='99' WHERE Username = '$usr'";
		$r = $this->warp_query($q);
}

private function user_unvisible($usr) {
		$this->switch_users_table();
		$q = "UPDATE users SET Visible='0' WHERE Username = '$usr'";
		$r = $this->warp_query($q);
}

private function user_visible($usr) {
		$this->switch_users_table();
		$q = "UPDATE users SET Visible='1' WHERE Username = '$usr'";
		$r = $this->warp_query($q);
}

public function display_AdminPanel_helper() {
	$entry_display = <<<ADMIN_FORM
				<div id="menu-bar" align="center">
				<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
				<tr>
				<td onclick="loadURL('admin_global.php');"><a href="/index.php?page=adminsettings&work=global">Global Settings</a></td>
				<td onclick="loadURL('admin_editmenu.php');"><a href="admin_editmenu.php">Edit Menu</a></td>
				<td onclick="loadURL('index.php');"><a href="/index.php?page=adminsettings&work=plugins">Plugins</a></td>
				<td onclick="loadURL('');"><a href="index.php?plugin=switch-theme">Change Template</a></td>
				<td onclick="loadURL('admin_regusers.php');"><a href="admin_regusers.php">Registered Users</a></td>
				<td onclick="loadURL('admin_msgs.php');"><a href="admin_msgs.php">Manage Messages</a></td>
				</tr>
				</tbody>
				</table>
				</div>
				<br>
ADMIN_FORM;
}

public function display_AdminPanel() {
	if ($_SESSION['loginuser']) {
	$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
	
	$this->switch_users_table();
		
	$checkem = $this->warp_query("SELECT email FROM users WHERE Username='$myuser'");	
	$a = $this->warp_fetch_assoc($checkem);
	$rcemail = $a['email'];
		
	// ak existuje pouzivatel v poli pouzivatelov
	if (isset($this->adminemail[$rcemail])) {
		// over heslo, a ak je spravne
		if ( $this->adminemail[$rcemail] == "admin" ) {
				echo $this->display_AdminPanel_helper();
		}
	}
	}
}

// health of Karma
// 0 - nothing
// 1 - Good
// 2 - Bad
function karma_health($kea) {
	$this->switch_karma_table();
	
	$q = "SELECT * FROM karma ORDER BY created DESC LIMIT 2048";
    $r = $this->warp_query($q);
	
	$good=0;
	$bad=0;
	
    if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
		while ( $a = $this->warp_fetch_assoc($r) ) {
			$user = stripslashes($a['Username']);
			$kyesno = stripslashes($a['yesno']);
			$id = stripslashes($a['id']);
			
			if ($id==$kea) {
				if (strpos($kyesno,"Yes")!==false) $good++;
					else if (strpos($kyesno,"No")!==false) $bad++;
			}
		}
	}
	if ($good>$bad) {
		return $entry_display = <<<ADMIN_FORM
				<img src="./images/plus.gif">
ADMIN_FORM;
	} else
		if ($good<$bad) {
		return $entry_display = <<<ADMIN_FORM
				<img src="./images/minus.gif">
ADMIN_FORM;
	}
	
	return $entry_display = <<<ADMIN_FORM
				<form NAME="formular" action="index.php" method="post" onsubmit="">
					<button type="submit" style="border: 0; background: transparent" name="karma_yesno" value="Yes">
						<img src="./images/vote_up.png" alt="submit" />
					</button> Yes 
											
					<button type="submit" style="border: 0; background: transparent" name="karma_yesno" value="No">
						<img src="./images/vote_down.png" alt="submit" />
					</button> No 
											
					<input type="hidden" name="karma_user" value="{$user}">
					<input type="hidden" name="karma_index" value="{$crea}">
				</form>
ADMIN_FORM;
}

function karma_health_likes($kea) {
	$this->switch_karma_table();
	
	$q = "SELECT * FROM karma ORDER BY created DESC LIMIT 2048";
    $r = $this->warp_query($q);
	
	$good=0;
	$bad=0;
	
    if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
		while ( $a = $this->warp_fetch_assoc($r) ) {
			//$user = stripslashes($a['Username']);
			$kyesno = stripslashes($a['yesno']);
			$id = stripslashes($a['id']);
			
			if ($id==$kea) {
				if (strpos($kyesno,"Yes")!==false) $good++;
					//else if (strpos($kyesno,"No")!==false) $bad++;
			}
		}
	}
	//if ($good>$bad) return 1; else
		//if ($good<$bad) return 2; else
	return $good;
}


/* @info write karma.. */
public function write_karma($p) {
	$this->switch_karma_table($p);
	
	//var_dump($_POST);
	
    if ( $_POST['karma_index'] )
      $kindex = $this->warp_real_escape_string($_POST['karma_index']);
	if ( $_POST['karma_yesno'])
      $kyesno = $this->warp_real_escape_string($_POST['karma_yesno']);
	if ($_SESSION['loginuser'])
      $myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
	 
	if ( $myuser && $kyesno && $kindex ) {
		//echo ("KARMA {$kindex} : {$kyesno} : {$user}<br>");
		$created = time();
		$sql = "INSERT INTO karma VALUES('$myuser','$kyesno','$kindex','$created')";
		$this->warp_query($sql);
		return true;
    } else {
      return false;
    }
  } 

  public function switch_karma_table() {
	$this->warp_select_db($this->table) or die("Could not select database. " . $this->warp_error());

    return $this->build_karma_db();
  }

 private function build_karma_db() {
    $sql = <<<warp_QUERY
CREATE TABLE IF NOT EXISTS karma (
username    VARCHAR(150),
yesno       VARCHAR(150),
id			VARCHAR(100),
created		VARCHAR(100)
)
warp_QUERY;
    return $this->warp_query($sql);
  }
  
/* @info  display titles */
public function LoadTitles($num) {
	$this->switch_data_table();
	
	$entry_display = <<<ADMIN_FORM
			<span style="font-weight: bold;">Public Messages</span><br>
ADMIN_FORM;

    $q = "SELECT * FROM data ORDER BY created DESC LIMIT 2048";
    $r = $this->warp_query($q);
    $id=0;
    if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
		while ( $a = $this->warp_fetch_assoc($r) ) {
			$title = stripslashes($a['title']);
			$bodytext = $a['bodytext'];
			$user = stripslashes($a['username']);
			$sto = stripslashes($a['sendto']);
			$crea = stripslashes($a['created']);
			if ($sto == "##SYSMESSAGE##") {
			} else if ( ($sto == "##ALLUSERS##") && ($id<=$num) ) {
				$entry_display .= <<<MESSAGE_DISPLAY
					<a href="{$this->synapse_dir}message.php?created=$crea">$title</a><br>
MESSAGE_DISPLAY;
				$id++;
			}
		}
   }
	$entry_display .= <<<ADMIN_FORM
		<a href="">...</a><br>
		<br>
ADMIN_FORM;
	return $entry_display;
}

/* @info  write data..message.. */
public function SaveMessage($p) {
	$this->switch_data_table();
	
	//var_dump($_POST);
	
    if ( $_POST['channel'] )
      $channel = $this->warp_real_escape_string($_POST['channel']);
    if ( $_POST['bodytext'])
      $bodytext = $this->warp_real_escape_string($_POST['bodytext']);
	if ($_POST['user'])
      $user = $this->warp_real_escape_string($_POST['user']);
		
	//echo ("{$title} : {$bodytext} : {$user}<br>");
	 
	if ( $channel && $bodytext && $user ) {
		$t1 = "<?";
		$t2 = "<script";
		//// test for BAD words
		if (strpos($bodytext,$t1)!==false) {
			return false;
		} else if (strpos($bodytext,$t2)!==false) {
			return false;
		} else {
			if ($this->IfExistZone($channel)==1) {
			// crypt or decrypt
			$kluc = "";
			//$crea = $this->getcreated($user);
			$this->Mode = Crypt::MODE_HEX; // druh šifrovania
			$this->Key  = $this->glob_password; //kľúč
			$kluc = $this->decrypt($this->getpassword($user, 0)); // desifruje lubovolny zasifrovany retazec
			//inicializácia vnorenej-triedy
			//$crypt = new Crypt();
			$this->Mode = Crypt::MODE_HEX; // druh šifrovania
			
			$bodytext = $this->encrypt($bodytext); // zasifruje lubovolny retazec
			
			// write func
			$created = time();
			
			$result = $this->warp_query("SELECT * FROM data");
			$num_rows = $this->warp_num_rows($result);
			$num_rows = $num_rows+1;
			
			$sql = "INSERT INTO data VALUES('$channel','$bodytext','$user','0','$num_rows','$created')";
			$this->warp_query($sql);
			return true;
			} else return false;
		}
    } else {
      return false;
    }
}

/* @info  update message with link parameter */
public function UpdateMessage($p) {	
	if ( $_POST['channel'] )
      $channel = $this->warp_real_escape_string($_POST['channel']);
	if ( $_POST['user'] )
      $user = $this->warp_real_escape_string($_POST['user']);
	if ( $_POST['bodytext'])
      $bodytext = $this->warp_real_escape_string($_POST['bodytext']);
	if ($_POST['crea'])
      $user = $this->warp_real_escape_string($_POST['crea']);

		$this->switch_data_table();
		$q = "UPDATE data SET bodytext='$bodytext' WHERE created = '$crea'";
		$r = $this->warp_query($q);
}


/* @info  delete message */
public function DeleteMessage($p) {
	if ($_POST['crea'])
      $crea = $this->warp_real_escape_string($_POST['crea']);
	
	$this->switch_data_table();
	$q = "DELETE FROM data WHERE created = '$crea'";
	$r = $this->warp_query($q);
}


/* @info  delete message */
public function DeleteMessage_Created() {
	if ($_SESSION['loginuser'])
		$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
	
	if ( $_POST['karma_index'] )
		$crea = $this->warp_real_escape_string($_POST['karma_index']);
	
	// if exist user? only admin can delete messages or information channel
	// if ($this->isadmin($myuser)==1) || ($this->isvisible($myuser)==1) {
	
	// when there are not replies then delete it, or when is login user a admin of web portal
		$this->switch_data_table();
		$q = "DELETE FROM data WHERE created = '$crea'";
		$r = $this->warp_query($q);
}

/* @info  how many messages by user */
function PostedMessages($myuser) {
	$this->switch_data_table();
	$q = "SELECT * FROM data ORDER BY created DESC LIMIT 2048";
	$r = $this->warp_query($q);
	
	$id=0;
	
	if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
		while ( $a = $this->warp_fetch_assoc($r) ) {
			//$title = stripslashes($a['title']);
			//$bodytext = stripslashes($a['bodytext']);
			$user = stripslashes($a['username']);
			//$sto = stripslashes($a['sendto']);
			//$crea = stripslashes($a['created']);
			if ($user==$myuser) {
				$id++;
			}
		}
	}
	return $id;
}

/* @info  how many channel friendships */
function Friendships($myuser) {
	$this->switch_friends_table();
			
	//if ($_SESSION['loginuser'])
	//	$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);

	//BEGIN prejdi celu databazu FRIENDS po riadkoch a napln datamy objekt		
    $usrq = "SELECT * FROM friends ORDER BY created DESC LIMIT 2048";
    $usrr = $this->warp_query($usrq);
	
	$id=0;
    if ( $usrr !== false && $this->warp_num_rows($usrr) > 0 ) {
		while ( $usra = $this->warp_fetch_assoc($usrr) ) {
			$usrx = stripslashes($usra['UsernameOne']);
			$usry = stripslashes($usra['UsernameTwo']);
			if ($myuser==$usrx) {
				if ($this->isvisible($usry)==0) $id++;
			} else
			if ($myuser==$usry) {
				if ($this->isvisible($usrx)==0) $id++;
			}
		}
	}
	
	return $id;
}

/* @info  how many channel friendships */
public function FindFriends($myuser) {
	$this->switch_friends_table();
			
	//if ($_SESSION['loginuser'])
	//	$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);

	//BEGIN prejdi celu databazu FRIENDS po riadkoch a napln datamy objekt		
    $usrq = "SELECT * FROM friends ORDER BY created DESC LIMIT 2048";
    $usrr = $this->warp_query($usrq);
	$message_display = <<<MESSAGE_DISPLAY
MESSAGE_DISPLAY;
	$id=0;
    if ( $usrr !== false && $this->warp_num_rows($usrr) > 0 ) {
		while ( $usra = $this->warp_fetch_assoc($usrr) ) {
			$usrx = stripslashes($usra['UsernameOne']);
			$usry = stripslashes($usra['UsernameTwo']);
			if ($myuser==$usrx) {
				if ($this->isvisible($usry)==0) {
					$usr=$this->getname($usry);
					$sur=$this->getsurname($usry);
					$inform=$this->getinform($usry);
					$avat = $this->getimgavatar($usry);
					$email = $this->getemail($usry);
$message_display .= <<<MESSAGE_DISPLAY
					<div class="w3-container">
						<img class="w3-round w3-margin-right" src="$avat" style="width:15%;"><span class="w3-opacity w3-large">$usry</span>
						<p>$usr $sur</p>
						<p>$email</p>
						<p>$inform</p>
					</div>
MESSAGE_DISPLAY;
				$id++;}
			} else
			if ($myuser==$usry) {
				if ($this->isvisible($usrx)==0) {
					$usr=$this->getname($usrx);
					$sur=$this->getsurname($usrx);
					$inform=$this->getinform($usrx);
					$avat = $this->getimgavatar($usrx);
					$email = $this->getemail($usrx);
$message_display .= <<<MESSAGE_DISPLAY
					<div class="w3-container">
						<img class="w3-round w3-margin-right" src="$avat" style="width:15%;"><span class="w3-opacity w3-large">$usrx</span>
						<p>$usr $sur</p>
						<p>$email</p>
						<p>$inform</p>
					</div>
MESSAGE_DISPLAY;
					$id++;}
			}
		}
	}
	
	if ($id==0) return "<a href=\"index.php?page=contacts\">empty</a>";
		else
	return $message_display;
}

public function PostFormular()  {
	return $message_display = <<<MESSAGE_DISPLAY
    <p>Lets get in touch. Send me a message:</p>
    <form action="index.php?page=updateformular" target="_blank">
      <p><input class="w3-input w3-padding-16 w3-border" type="text" placeholder="Name" required name="Name"></p>
      <p><input class="w3-input w3-padding-16 w3-border" type="text" placeholder="Email" required name="Email"></p>
      <p><input class="w3-input w3-padding-16 w3-border" type="text" placeholder="Subject" required name="Subject"></p>
      <p><input class="w3-input w3-padding-16 w3-border" type="text" placeholder="Message" required name="Message"></p>
      <p>
        <button class="w3-button w3-light-grey w3-padding-large" type="submit">
          <i class="fa fa-paper-plane"></i> SEND MESSAGE
        </button>
      </p>
    </form>
MESSAGE_DISPLAY;
}

public function PostMessage($chan) {
	$crea = time();

	$datum = StrFTime("%d/%m/%Y %H:%M:%S", $crea);
	$date = StrFTime("%Y-%m-%d %H:%M", $crea);   // "2009-03-04 17:45";
	//$result = $obj->nicetime($date); // 2 days ago 	

	$user = $_SESSION['loginuser'];
	return $message_display = <<<MESSAGE_DISPLAY
		<div class="w3-container w3-card w3-white w3-round w3-margin"><br>
		<p>
		<div class="toolbar">
					<button type="button" class="fbutton" accesskey="b" id="addbbcode0_0" style="width: 30px" onclick="bbstyle(0, 0); return false"><span style="font-weight: bold"> B </span></button>
					<button type="button" class="fbutton" accesskey="i" id="addbbcode2_0" style="width: 30px" onclick="bbstyle(2, 0); return false"><span style="font-style:italic"> i </span></button>
					<button type="button" class="fbutton" accesskey="u" id="addbbcode4_0" style="width: 30px" onclick="bbstyle(4, 0); return false"><span style="text-decoration: underline"> U </span></button>
					<button type="button" class="fbutton" accesskey="s" id="addbbcode8_0" style="width: 30px" onclick="bbstyle(8, 0); return false"><span style="text-decoration: line-through"> S </span></button>
					<button type="button" class="fbutton" style="width: 50px" onclick="inputimg_url(0); return false"><span> IMAGE </span></button>
					<button type="button" class="fbutton" style="width: 50px" onclick="input_url(0); return false"><span> URL </span></button>
					<button type="button" class="fbutton" id="addbbcode6_0" style="width: 60px" onclick="bbstyle(6, 0); return false"><span> BREAK </span></button>
					<button type="button" class="fbutton" id="" style="width: 80px" onclick="input_youtube(0); return false"><span> YOUTUBE </span></button>
		</div>
		<br />

		<form name="formular" role="search" method="post" class="search-form" action="index.php?channel=<?php echo $chan; ?>">
			<input type="hidden" name="user" value="$user">
			<input type="hidden" name="channel" value="$chan">
		<label>
			<span class="screen-reader-text"></span>
				<input class="message-field" style="width:700px;height:47px;" placeholder="left message … in $chan" value="" name="bodytext" id="text0" title="Search for:" type="search">
		</label>
		 <input class="message-submit" type="submit" name="send-message" value="Fast Message" style="height:38px;" />
	    </form>
		</p>
		<br>
		</div>
MESSAGE_DISPLAY;
}

public function LatestAccess() {
	$date = StrFTime("%Y-%m-%d %H:%M", $this->userlasttime());   // "2009-03-04 17:45";
	$result = $this->nicetime($date); // 2 days ago
	
	return $message_display = <<<MESSAGE_DISPLAY
	<div class="w3-container w3-card w3-white w3-round w3-margin"><br>
	<p>
	<a href="" title="Your last refresh was {$result} at {$date}">Your last refresh was {$result} at {$date}</a><br><br>
	</p>
	</div>
MESSAGE_DISPLAY;
}

public function LoadMessages($chan) {
	$this->switch_users_table();
	
	//$test=0;$ts1=0;$ts2=0;
	if ( (isset($_GET['timeline1']))&&(isset($_GET['timeline2'])) ) {
		//$test=1;
		
		$tm1=$this->warp_real_escape_string($_GET['timeline1']);	// 1-11-2012
		$tm2=$this->warp_real_escape_string($_GET['timeline2']);	// 30-11-2012
		
		
		$ts1 = strtotime($tm1);
		$ts2 = strtotime($tm2);
		
		//var_dump($tm1);
		//var_dump($tm2);
		
		//$format="%d/%m/%Y %H:%M:%S";
		//$strf=strftime($format);
		//echo("$strf");
		//$ts = strptime($tm1, $format);
		//$ts1 = mktime($ts['tm_hour'], $ts['tm_min'], $ts['tm_sec'], $ts['tm_mon'], $ts['tm_mday'], ($ts['tm_year'] + 1900));
		//var_dump($ts1);
		//$ts = strptime($tm2, $format);
		//$ts2 = mktime($ts['tm_hour'], $ts['tm_min'], $ts['tm_sec'], $ts['tm_mon'], $ts['tm_mday'], ($ts['tm_year'] + 1900));
		//var_dump($ts2);
		
		$q = "SELECT * FROM data WHERE created<$ts2 ORDER BY created DESC LIMIT 2048";
		
	} 
	else $q = "SELECT * FROM data ORDER BY created DESC LIMIT 2048";

    $r = $this->warp_query($q);
	//$ix=1;
    if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
		while ( $a = $this->warp_fetch_assoc($r) ) {
			//
			$channel = stripslashes($a['channel']);
			$bodytext = $a['bodytext'];
			$yesno = stripslashes($a['yesno']);
			$id = $a['id'];
			$user = stripslashes($a['username']);
			$crea = stripslashes($a['created']);
			// $crea = stripslashes($a['created']);

			// more then X (433) chars?
			$bodytext = $this->get_bodytext($bodytext, $crea, $user);
		
			$bodytext = strip_tags($bodytext); 
			$bodytext = $this->spracuj_form($bodytext);
		
			//$replies = $this->howmany_reply_messages($crea);
		
			//if ($replies>0) $reptxt = "<br><a href=\"index.php?comments=$crea\">comments</a><br>"; else $reptxt = "";
		
			if ($channel == $chan) {
					$datum = StrFTime("%d/%m/%Y %H:%M:%S", $crea);
					$date = StrFTime("%Y-%m-%d %H:%M", $crea);   // "2009-03-04 17:45";
					$result = $this->nicetime($date); // 2 days ago 
						$avat = $this->getimgavatar($user);
						//if ($_SESSION['loginuser']) $replybtn = "<input type=\"submit\" name=\"karma_yesno\" value=\"Reply\">";
						$karm=$this->karma_health_likes($crea);
						$message_display .= <<<MESSAGE_DISPLAY
		<div class="w3-container w3-card w3-white w3-round w3-margin" id="comment-{$id}"><br>
        <img src="{$avat}" alt="Avatar" class="w3-left w3-circle w3-margin-right" style="width:60px">
        <span class="w3-right w3-opacity">{$result}</span>
        <h4>{$user}</h4><br>
        <hr class="w3-clear">
        <p>{$bodytext}</p>
        <div class="w3-row-padding" style="margin:0 -16px">
        </div>
		<hr class="w3-clear">
        <button type="button" class="w3-button w3-theme-d1 w3-margin-bottom"><i class="fa fa-thumbs-up"></i>  {$karm} Like</button> 
        <button type="button" class="w3-button w3-theme-d2 w3-margin-bottom"><i class="fa fa-comment"></i>  Comment</button> 
        </div>
MESSAGE_DISPLAY;
				//$ix++;
				}
			}
		}
	
	echo $message_display .= <<<MESSAGE_DISPLAY
MESSAGE_DISPLAY;
}


/* @info  submit news */
public function SubmitMessage() {
?>
<form  NAME="formular" action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="bbstyle(-1,0)"
<?php
//$myuser = $this->getadmin();
$crea = time();

$datum = StrFTime("%d/%m/%Y %H:%M:%S", $crea);
$date = StrFTime("%Y-%m-%d %H:%M", $crea);   // "2009-03-04 17:45";
//$result = $obj->nicetime($date); // 2 days ago 			

$chan = isset( $_GET['channel'] ) ? $_GET['channel'] : "life";

$user = $_SESSION['loginuser'];

$message_display = <<<MESSAGE_DISPLAY
	<input type="hidden" name="user" value="{$user}">
	<input type="hidden" name="channel" value="{$chan}">
	<p>
	<br>
		<img src="./images/bg-slider-arrow-down.png">
		<br>
		<img src="./images/bg-slider-arrow-down.png">
		<br>
		<br>
	<big><b><spam style="color: red;">Submit message to {$chan} zone</spam></b></big>
	</p>
	<p>
		<br>
	</p>
	<p>
	</p>
	<!--textarea type="hidden" class="myTextEditor" name="synapse-title" id="msg_title" maxlength="40" style="width: 500px; height:20px;" ></textarea!-->
	</p>
	<p>
	  <div class="toolbar" style="width: 400px;">
           <button type="button" class="fbutton" accesskey="b" id="addbbcode0_0" style="width: 30px" onclick="bbstyle(0, 0); return false"><span style="font-weight: bold"> B </span></button>
           <button type="button" class="fbutton" accesskey="i" id="addbbcode2_0" style="width: 30px" onclick="bbstyle(2, 0); return false"><span style="font-style:italic"> i </span></button>
           <button type="button" class="fbutton" accesskey="u" id="addbbcode4_0" style="width: 30px" onclick="bbstyle(4, 0); return false"><span style="text-decoration: underline"> U </span></button>
           <button type="button" class="fbutton" accesskey="s" id="addbbcode8_0" style="width: 30px" onclick="bbstyle(8, 0); return false"><span style="text-decoration: line-through"> S </span></button>
           <button type="button" class="fbutton" style="width: 50px" onclick="inputimg_url(0); return false"><span> IMAGE </span></button>
           <button type="button" class="fbutton" style="width: 50px" onclick="input_url(0); return false"><span> URL </span></button>
           <button type="button" class="fbutton" id="addbbcode6_0" style="width: 60px" onclick="bbstyle(6, 0); return false"><span> BREAK </span></button>
		   <button type="button" class="fbutton" id="" style="width: 80px" onclick="input_youtube(0); return false"><span> YOUTUBE </span></button>
       </div>
    </p>
    <p>
    <textarea class="myTextEditor" name="bodytext" id="text0" maxlength="1024" style="width: 900px; height:40px;" title="[url=HTTP://]URL_NAME[/url] , [img]URL_TO_IMAGE[/img] , [iframe]URL_TO_YOUTUBE_VIDEO[/iframe] , [b][/b] , [i][/i] , [u][/u] , [br][/br]"></textarea>
    </p>
    <p>
		<br>
		</br>
MESSAGE_DISPLAY;
if ($this->captcha_type != 99) {
	$message_display .= <<<MESSAGE_DISPLAY
		<img src="cool-php-captcha/captcha.php" id="captcha" /><br/>
		<br/>
		<a href="#" onclick=" document.getElementById('captcha').src='cool-php-captcha/captcha.php?'+Math.random(); document.getElementById('captcha-form').focus();" id="change-image">Not readable? Change text.</a><br/><br/>
		<input type="text" class="bginput" style="background-color: #E8EEC2;"  name="captcha" id="captcha-form" /><br />
MESSAGE_DISPLAY;
} else {
	$message_display .= <<<MESSAGE_DISPLAY
		Google ReCaptcha
MESSAGE_DISPLAY;
}
$message_display .= <<<MESSAGE_DISPLAY
	<p><br/></p>
    <input id="create-message" style="width: 150px;" type="submit" name="send-message" value="Send Message" />
    </p>
MESSAGE_DISPLAY;
?>
</form>
<?php
echo $message_display;
}

 public function switch_data_table() {
    $this->warp_select_db($this->table) or die("Could not select database. " . $this->warp_error());
	
    return $this->build_data_db();
  }
  
 private function build_data_db() {
    $sql = <<<warp_QUERY
CREATE TABLE IF NOT EXISTS data (
channel	    VARCHAR(150),
bodytext	    	TEXT,
username    VARCHAR(150),
yesno		    INT(24),
id		    INT(24),
created		INT(24)
)
warp_QUERY;
    return $this->warp_query($sql);
  }


/* @info  are you my friend */
public function ismyfriend($test) {
	if ($_SESSION['loginuser'])
		$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);

    //BEGIN prejdi celu databazu FRIENDS po riadkoch a napln datamy objekt
    $this->switch_friends_table();		
    $usrq = "SELECT * FROM friends ORDER BY created DESC LIMIT 2048";
    $usrr = $this->warp_query($usrq);
	
    if ( $usrr !== false && $this->warp_num_rows($usrr) > 0 ) {
		while ( $usra = $this->warp_fetch_assoc($usrr) ) {
			$usrx = stripslashes($usra['UsernameOne']);
			$usry = stripslashes($usra['UsernameTwo']);
			if ($myuser==$usrx) {
				if($usry==$test) return 1;
			}
			if ($myuser==$usry) {
				if ($usrx==$test) return 1;
			}
		}
	}

	return 0;
}

// Display all users with their short informations
// about avatar, info
public function ManageUsers() {
	$this->switch_users_table();
	
	if ($_SESSION['loginuser'])
			$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
	$entry_display = <<<MESSAGE_FORM
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
              <tbody>
              <tr>
                <td class="mainNavGrey" style="PADDING-LEFT: 160px" colspan="2" height="27"><br>
<a href="/index.php?page=contacts">[Home]</a>
<a href="/index.php?page=contacts&search=a">[A]</a>
<a href="/index.php?page=contacts&search=b">[B]</a>
<a href="/index.php?page=contacts&search=c">[C]</a>
<a href="/index.php?page=contacts&search=d">[D]</a>
<a href="/index.php?page=contacts&search=e">[E]</a>
<a href="/index.php?page=contacts&search=f">[F]</a>
<a href="/index.php?page=contacts&search=g">[G]</a>
<a href="/index.php?page=contacts&search=h">[H]</a>
<a href="/index.php?page=contacts&search=i">[I]</a>
<a href="/index.php?page=contacts&search=j">[J]</a>
<a href="/index.php?page=contacts&search=k">[K]</a>
<a href="/index.php?page=contacts&search=l">[L]</a>
<a href="/index.php?page=contacts&search=m">[M]</a>
<a href="/index.php?page=contacts&search=n">[N]</a>
<a href="/index.php?page=contacts&search=o">[O]</a>
<a href="/index.php?page=contacts&search=p">[P]</a>
<a href="/index.php?page=contacts&search=q">[Q]</a>
<a href="/index.php?page=contacts&search=r">[R]</a>
<a href="/index.php?page=contacts&search=s">[S]</a>
<a href="/index.php?page=contacts&search=t">[T]</a>
<a href="/index.php?page=contacts&search=u">[U]</a>
<a href="/index.php?page=contacts&search=v">[V]</a>
<a href="/index.php?page=contacts&search=w">[W]</a>
<a href="/index.php?page=contacts&search=x">[X]</a>
<a href="/index.php?page=contacts&search=y">[Y]</a>
<a href="/index.php?page=contacts&search=z">[Z]</a>
</td></tr>
</tbody>
</table>
<br>
<br>
<br>
<form  NAME="formular" action="{$_SERVER['PHP_SELF']}" method="post">
MESSAGE_FORM;
	
	//if ($_SESSION['loginuser'])
	//		$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
	
	$q = "SELECT * FROM users ORDER BY created DESC LIMIT 2048";
	$r = $this->warp_query($q);
	
	if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
		while ( $a = $this->warp_fetch_assoc($r) ) {
			$user = stripslashes($a['Username']);
			$em = stripslashes($a['Email']);
			$crea = stripslashes($a['Created']);
		
		
		$sear="";
		if (isset($_GET['search'])) $sear=$_GET['search'];
			if ( (substr($user, 0, 1) === $sear) || (empty($_GET['search'])) ) {
				
					$hyper = "<a href=\"index.php?channel={$user}\">{$user}</a>";
					
													
					$txt = $this->getinform($user);
					$av = $this->getimgavatar($user);
					// is channel?
					
				if ($myuser==$user) {
				} else
				if ($this->IfExistZone($user)==1) {
					$entry_display .= <<<MESSAGE_FORM
MESSAGE_FORM;
				} else {
			//<i>{$this->getsex($user)} - {$this->getgender($user)}</i><br>
					$entry_display .= <<<MESSAGE_FORM
						<div class="w3-container w3-card w3-white w3-round w3-margin">
						<p>
						<br>
						<div class="tweet"> 
						<img class="avatar" src="{$av}"> 
						
						<div class="text" style="width: 85%;"> 
						<h2>{$user}</h2>
						<br>
						<div style="text-align: left;">
						{$txt} 
						</div>
						<br>
						<br>
MESSAGE_FORM;
					
					
					// or is ordinary user
					if ($this->ismyfriend($user)==1) { 
							$friendsopt = <<<MESSAGE_DISPLAY
											<button type="submit" name="del_friend" value="$user" title="$user">Break Friendship</button>
MESSAGE_DISPLAY;
					} else {
							$friendsopt = <<<MESSAGE_DISPLAY
											<button type="submit" name="asked_friend" value="$user" title="$user">Ask for Friendship</button>
MESSAGE_DISPLAY;
					}
						
					$entry_display .= <<<MESSAGE_FORM
						<br>
						<div style="float: left;"><span><b><big>Napíš niečo na nástenku {$hyper}.</big><b></span></div>
						<div style="float: right;">{$friendsopt}</div> 
						<br>
						</div>
					
						</div>
						<br>
						</p>
						</div>
MESSAGE_FORM;
				}
			}
		}
	}
				$entry_display .= <<<MESSAGE_FORM
						</form>
MESSAGE_FORM;
	return $entry_display;
}

public function delete_friend($p) {
		$this->switch_friends_table();
	
		if ( $_POST['del_friend'] )
			$myfriend = $_POST['del_friend']; 	
			
		if ($_SESSION['loginuser'])
			$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
			
		if (( $myuser ) && ($myfriend)) {
				$q = "DELETE FROM friends WHERE UsernameOne = '$myuser' AND UsernameTwo = '$myfriend'";
				$r = $this->warp_query($q);
				$q = "DELETE FROM friends WHERE UsernameOne = '$myfriend' AND UsernameTwo = '$myuser'";
				$r = $this->warp_query($q);
				$message_display = <<<MESSAGE_DISPLAY
					<div class="hehe"><div class="smallfont"><center><b><a href="index.php">Break friendship with $myfriend !</a></b><br><br></center></div></div>
MESSAGE_DISPLAY;
				echo($message_display);
		}
}

public function write_friend($p) {
		$this->switch_friends_table();
	
		if ( $_POST['find_friend'] )
			$myfriend = $_POST['find_friend']; 
		else 
		if ( $_POST['insert_friend'] )
			$myfriend = $_POST['insert_friend']; 	
			
		if ($_SESSION['loginuser'])
			$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
			
		if (( $myuser ) && ($myfriend)) {
				$q = "SELECT * FROM friends ORDER BY created DESC LIMIT 2048";
				$r = $this->warp_query($q);
				//$id=1;
				if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
					while ( $a = $this->warp_fetch_assoc($r) ) {
						$one = stripslashes($a['UsernameOne']);
						$two = stripslashes($a['UsernameTwo']);
						if ( (($one==$myuser)&&($two==$myfriend)) || (($two==$myuser)&&($one==$myfriend)) ) return null;
					}
					$message_display = <<<MESSAGE_DISPLAY
					<div class="hehe"><div class="smallfont"><center><b><a href="index.php"><br>Your New Friend is $myfriend !</a></b><br><br></center></div></div>
MESSAGE_DISPLAY;
				} else {
$message_display = <<<MESSAGE_DISPLAY
					<div class="hehe"><div class="smallfont"><center><b><a href="index.php"><br>Refresh Page</a></b><br><br></center></div></div>
MESSAGE_DISPLAY;
				}
				echo($message_display);
				// register user to contact list
				$this->switch_friends_table();
				$created = time();
				$sql = "INSERT INTO friends VALUES('$myuser','$myfriend','$created')";
				return $this->warp_query($sql);
		} else return null;
} 	
 
 public function switch_friends_table() {
	$this->warp_select_db($this->table) or die("Could not select database. " . $this->warp_error());

    return $this->build_friends_db();
  }
  
  private function build_friends_db() {
    $sql = <<<warp_QUERY
CREATE TABLE IF NOT EXISTS friends (
UsernameOne	VARCHAR(150),
UsernameTwo	VARCHAR(150),
Created		VARCHAR(100)
)
warp_QUERY;

    return $this->warp_query($sql);
  }
  
  
/* @info delete message/item from buffer */
public function DeleteQuestion($p) {
		$this->switch_buffer_table();
	
		if ( $_POST['insert_friend'] )
			$myfriend = $_POST['insert_friend']; 	
			
		if ($_SESSION['loginuser'])
			$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);

			
		if (( $myuser ) && ($myfriend)) {
				$q = "DELETE FROM buffer WHERE UsernameOne = '$myuser' AND UsernameTwo = '$myfriend'";
				$r = $this->warp_query($q);
				$q = "DELETE FROM buffer WHERE UsernameOne = '$myfriend' AND UsernameTwo = '$myuser'";
				$r = $this->warp_query($q);
		}
}

/* @info  display notifications, etc. ask for friendship */
public function Notifications() {
		$this->switch_buffer_table();
			
		if ($_SESSION['loginuser'])
			$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
		
		// VIEW FORMULAR WHEN IS ASKED
		$q = "SELECT * FROM buffer ORDER BY created DESC LIMIT 2048";
		$r = $this->warp_query($q);
	
		if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
			// You have new notifications
			$entry_display = <<<ADMIN_FORM
					<ul class="menu">
ADMIN_FORM;
			
			$id =1;
			
			while ( $a = $this->warp_fetch_assoc($r) ) {
				$one = stripslashes($a['UsernameOne']);
				$two = stripslashes($a['UsernameTwo']);
				$wo = stripslashes($a['Work']);
				$pass = stripslashes($a['Password']);
				$ema = stripslashes($a['Email']);
				$crea = stripslashes($a['Created']);
				if ($wo=="##FRIENDSHIP##") {
					$askmsg="User $one ask you for friendship";
					$askname="accept";
				}
				// ak je poziadavka
				if ($two == $myuser) {
						$entry_display .= <<<MESSAGE_DISPLAY
							<li class="menu" onmouseover="" onmouseout="" onclick=""><a href="">
							<p>$askmsg</p>
							<form NAME="formular" action="index.php" method="post" onsubmit="">
							
							<input type="hidden" name="insert_friend" value="$one">
							<input type="hidden" name="reg_password" value="$pass">
							<input type="hidden" name="reg_email" value="$ema">
							
							<input type="submit" name="$askname" value="Accept" tabindex="104" title="Accept" accesskey="s" />
							<input type="submit" name="notaccept" value="X" tabindex="104" title="X" accesskey="s" />
							</form>
							</a>
							</li>
MESSAGE_DISPLAY;
					$id++; 
				}
			}
			$entry_display .= <<<ADMIN_FORM
					</ul>
ADMIN_FORM;
			return $entry_display;
		}
}

/* @info  insert connection in buffer */
public function SendQuestion($p) {
		
		if ( $_POST['asked_friend'] )
			$myfriend = $_POST['asked_friend']; 	
			
		if ($_SESSION['loginuser'])
			$myuser = $this->warp_real_escape_string($_SESSION['loginuser']);
		
		$passw = "#";
		$ema = "#";
		
		
		$askmsg="Please, be friends! $myfriend";
			
		if ( ( $myuser ) && ($myfriend) && ($ema) && ($passw) ) {
				// create buffer intem
				$this->switch_buffer_table();
				$q = "SELECT * FROM buffer ORDER BY created DESC LIMIT 2048";
				$r = $this->warp_query($q);
				//$id=1;
				if ( $r !== false && $this->warp_num_rows($r) > 0 ) {
					while ( $a = $this->warp_fetch_assoc($r) ) {
						$one = stripslashes($a['UsernameOne']);
						$two = stripslashes($a['UsernameTwo']);
						if ( (($one==$myuser)&&($two==$myfriend)) || (($two==$myuser)&&($one==$myfriend)) ) return null;
					}
				}
				$message_display = <<<MESSAGE_DISPLAY
					<div class="hehe"><div class="smallfont"><center><b><a href="index.php"><br>$askmsg</a></b><br><br></center></div></div>
MESSAGE_DISPLAY;
				echo($message_display);
				// ask for friendship
				$this->switch_buffer_table();
				$created = time();
				$sql = "INSERT INTO buffer VALUES('$myuser','$myfriend','##FRIENDSHIP##','$passw','$ema','$created')";
				return $this->warp_query($sql);
		} else {
			$message_display = <<<MESSAGE_DISPLAY
					<div class="hehe"><div class="smallfont"><center><b><a href="index.php"><br>Error. Refresh Page.</a></b><br><br></center></div></div>
MESSAGE_DISPLAY;
			echo($message_display);
			return null;
		}
}

public function switch_buffer_table() {
    $this->warp_select_db($this->table) or die("Could not select database. " . $this->warp_error());
	
    return $this->build_buffer_db();
}
  
private function build_buffer_db() {
    $sql = <<<warp_QUERY
CREATE TABLE IF NOT EXISTS buffer (
UsernameOne	VARCHAR(150),
UsernameTwo	VARCHAR(150),
Work		VARCHAR(150),
Password	VARCHAR(150),
Email		VARCHAR(150),
Created		VARCHAR(100)
)
warp_QUERY;
    return $this->warp_query($sql);
}

function __construct() { 
    $this->current_dir = "/";
}

/*
 * ENF OF */
}

/*
	include(dirname(__FILE__).'/'.'php-mo.php');

	phpmo_convert( dirname(__FILE__).'/locale/'.$locale.'/LC_MESSAGES/messages.po', [ dirname(__FILE__).'/locale/'.$locale.'/LC_MESSAGES/messages.mo' ] );
	
	/// if (file_exists(dirname(__FILE__).'/locale/'.$locale.'/LC_MESSAGES/messages.po')) { echo("exists"); }
	
	putenv("LC_ALL=$locale");
	setlocale(LC_ALL, $locale);
	bindtextdomain("messages", "./locale/");
	textdomain("messages");
*/	

//for PHP < 5.3.0
/*
 if ( !defined('__DIR__') ) define('__DIR__', dirname(__FILE__));

endif;
*/

?>
