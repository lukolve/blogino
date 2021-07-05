<!DOCTYPE html>
<!-- vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:

	Copyright (c) 2013 - 2020  Lukas Veselovsky
	All rights reserved.

The Beauty-Zones software is licensed under the open source (revised) BSD license, one of the most flexible and liberal licenses available. 
Third-party open source libraries we include in our download are released under their own licenses.
!-->
<?php
session_start();

if (strnatcmp(phpversion(),'5.4.11') >= 0) 
{ 
	// equal or newer
} 
else 
{ 
	// not sufficiant 
}

//for PHP < 5.3.0
if ( !defined('__DIR__') ) define('__DIR__', dirname(__FILE__));

// Turn off all error reporting
error_reporting(0);

include 'init.php';
?>
<html>
<head>
<title><?php echo $obj->title . " " . $obj->slogan; ?></title>
<meta charset="UTF-8">
<link rel="stylesheet" href="reset.css" />
<!--link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"!-->
<link rel="stylesheet" href="w3.css">
<link rel="stylesheet" href="other.css" />
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="js/jPages/css/jPages.css">
<link rel="stylesheet" href="js/jPages/css/animate.css">
  <script type="text/javascript" src="js/jPages/js/jquery-1.8.2.min.js"></script>
  <script type="text/javascript" src="js/jPages/js/jPages.js"></script>
  <script type="text/javascript" src="js/jPages/js/highlight.pack.js"></script>
  <script type="text/javascript" src="js/jPages/js/tabifier.js"></script>
  <script type="text/javascript" src="js/jPages/js/js.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
 <script>
  /* when document is ready */
  $(function(){
  /*
   * initiate the plugin without buttons and numeration
   * setting midRange to 15 to prevent the breaks "..."
   */
   $("div.holder").jPages({
    containerID : "itemContainer",
    first       : false,
    previous    : false,
    next        : false,
    last        : false,
    perPage    : 7,
    links       : "blank"
  });
 });
  </script>
  <style type="text/css">
  .holder {
    margin: 15px 0;
  }
  .holder a {
    display: inline-block;
    cursor: pointer;
    margin: 0 5px;
    padding: 4px;
    border-radius: 50%;
    background-color: #D4EE5E;
  }
  .holder a:hover {
    background-color: #222;
    color: #fff;
  }
  .holder a.jp-previous { margin-right: 15px; }
  .holder a.jp-next { margin-left: 15px; }
  .holder a.jp-current, a.jp-current:hover {
    color: #FF4242;
    font-weight: bold;
  }
  .holder a.jp-disabled, a.jp-disabled:hover {
    color: #bbb;
  }
  .holder a.jp-current, a.jp-current:hover,
  .holder a.jp-disabled, a.jp-disabled:hover {
    cursor: default;
    background-color: #FF4242;
  }
  .holder span { margin: 0 5px; }
  
.w3-underline-color:hover {

}
.w3-hide-custom{display:block!important}
.w3-show-custom{display:none!important}
@media (max-width:800px){	
.w3-hide-custom{display:none!important}
.w3-show-custom{display:block!important}
}
  </style>
</head>

<body>

<?php

if ($_POST['submit_btn']=="Sign In") {
	$_SESSION['viewpage']="";
	$_SESSION['loginuser'] = $obj->write_login($_POST); 
	if ($_SESSION['loginuser']) { 
		//$obj->unpack_files($_SESSION['loginuser']);
	}
	header( "Location: index.php" );
	exit;
}

if ($_POST['submit_btn']=="Register") {
	if (!empty($_REQUEST['captcha'])) {
		if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']) {
			$captcha_message = "Invalid captcha";
			$captcha_style = "background-color: #FF606C";
		} else {
			$captcha_message = "Valid captcha";
			$captcha_style = "background-color: #CCFF99";
			$obj->write_registration($_POST);
		}
		$request_captcha = htmlspecialchars($_REQUEST['captcha']);
		unset($_SESSION['captcha']);
	}
}

if (($_SESSION['loginuser']!=null) && ($obj->IfExistUser($_SESSION['loginuser'])==1)) {
	include('index-online.php');
}
	else {
		$page = isset( $_GET['page'] ) ? "index-new.php" : "index-rss.php";
		include($page);
	}
?>

</body>
</html>
