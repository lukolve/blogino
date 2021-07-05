
<?php
	if (isSet($_SESSION["locale"])) $locale=$_SESSION["locale"]; else $locale = "en_US";
	if (isSet($_GET["locale"])) {$locale = $_GET["locale"];$_SESSION["locale"]=$locale;}

	include(dirname(__FILE__).'/'.'php-mo.php');

	phpmo_convert( dirname(__FILE__).'/locale/'.$locale.'/LC_MESSAGES/messages.po', [ dirname(__FILE__).'/locale/'.$locale.'/LC_MESSAGES/messages.mo' ] );
	
	/// if (file_exists(dirname(__FILE__).'/locale/'.$locale.'/LC_MESSAGES/messages.po')) { echo("exists"); }
	
	putenv("LC_ALL=$locale");
	setlocale(LC_ALL, $locale);
	bindtextdomain("messages", "./locale/");
	textdomain("messages");
?>