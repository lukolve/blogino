<?php
session_start();
?>
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Captcha test</title>
</head>
<style type="text/css">
body { font-family: sans-serif; font-size: 0.8em; padding: 20px; }
#result { border: 1px solid green; width: 300px; margin: 0 0 35px 0; padding: 10px 20px; font-weight: bold; }
#change-image { font-size: 0.8em; }
</style>

<body onload="document.getElementById('captcha-form').focus()">

<?php
if (!empty($_REQUEST['captcha'])) {
			if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']) {
				$captcha_message = "Invalid captcha";
				$style = "background-color: #FF606C";
			} else {
				$captcha_message = "Valid captcha";
				$style = "background-color: #CCFF99";
				
				$obj->write_post_data($_POST);
				
				echo $msg =<<<DISPLAY_MESSAGES
					<div class="hehe"><div class="smallfont"><center><b><a href="index.php">News was Sended. Refresh Page</a></b></center></div></div><script>eraseCookie("pagelines"); indexPage();</script>
DISPLAY_MESSAGES;
				///$obj->accept_question($_POST);
				
				$request_captcha = htmlspecialchars($_REQUEST['captcha']);
				echo <<<HTML
			        <div id="result" style="$style">
						<h2>$captcha_message</h2>
					</div>
HTML;
				
			}
			unset($_SESSION['captcha']);
}
?>

<p><strong>Write the following word:</strong></p>

<form method="GET">
<img src="captcha.php" id="captcha" /><br/>


<!-- CHANGE TEXT LINK -->
<a href="#" onclick="
    document.getElementById('captcha').src='captcha.php?'+Math.random();
    document.getElementById('captcha-form').focus();"
    id="change-image">Not readable? Change text.</a><br/><br/>


<input type="text" name="captcha" id="captcha-form" autocomplete="off" /><br/>
<input type="submit" />

</form>
</body>
<HTML>
