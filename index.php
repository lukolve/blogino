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
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body { 
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

#header {
  background-color: #f1f1f1;
  padding: 10px 10px;
  color: black;
  text-align: center;
  font-size: 90px; 
  font-weight: bold;
  position: fixed;
  top: 0;
  width: 100%;
  transition: 0.2s;
}

#snackbar {
  visibility: hidden;
  min-width: 250px;
  margin-left: -125px;
  background-color: #333;
  color: #fff;
  text-align: center;
  border-radius: 2px;
  padding: 16px;
  position: fixed;
  z-index: 1;
  left: 50%;
  bottom: 30px;
  font-size: 17px;
}

#snackbar.show {
  visibility: visible;
  -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
  animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

@-webkit-keyframes fadein {
  from {bottom: 0; opacity: 0;} 
  to {bottom: 30px; opacity: 1;}
}

@keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
  from {bottom: 30px; opacity: 1;} 
  to {bottom: 0; opacity: 0;}
}

@keyframes fadeout {
  from {bottom: 30px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}
</style>
    <link rel="stylesheet" href="bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js" ></script>
<script>
function startTime() {
  const today = new Date();
  let h = today.getHours();
  let m = today.getMinutes();
  let s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('clock').innerHTML =  h + ":" + m + ":" + s;
  setTimeout(startTime, 1000);
}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
</script>
</head>
<body  onload="myNotify('Refresh Page'); startTime()">

<div id="header">BLOGINO</div>

<div class="container" style="max-width:500px; margin-top:170px; margin-bottom: 50px;">
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

if (($_SESSION['loginuser']!=null) && ($obj->IfExistUser($_SESSION['loginuser'])==1)) {
	include('online.php');
}
	else {
		$page = isset( $_GET['page'] ) ? "formular.php" : "rss.php";
		include($page);
	}
?>
</div>

<div id="snackbar"></div>

<script>
function myNotify(str) {
  var x = document.getElementById("snackbar");
  x.className = "show";
  x.innerHTML = str;
  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}

// When the user scrolls down 50px from the top of the document, resize the header's font size
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
    document.getElementById("header").style.fontSize = "30px";
  } else {
    document.getElementById("header").style.fontSize = "90px";
  }
}
</script>

</body>
</html>