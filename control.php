<?php
// Sing-ed User
$myuser = isset( $_SESSION['loginuser'] ) ? $_SESSION['loginuser'] : null;
// Is 'page' a _GET atribute?
$page = isset( $_GET['page'] ) ? $_GET['page'] : "default";

switch ( $page ) {
	case 'logout':
		//$obj->unlink_files($_SESSION['loginuser']);
					
		$_SESSION['loginuser'] = null;
		unset($_SESSION['loadpage']); 
		echo $msg =<<<DISPLAY_MESSAGES
			<div class="hehe"><div class="smallfont"><center><b><a href="index.php">Refresh Page</a></b></center></div></div><script>reloadPage();</script>
DISPLAY_MESSAGES;
		//echo($obj->display_zero()); 
		unset( $_SESSION['loginuser'] );
		header( "Location: index.php" );
		exit;
	break;
	case 'formular':
			if ($_GET['user']) { if (file_exists($obj->upload_dir."".$_GET['user']."/formular.txt")) $obj->display_formular($_GET['user']); }
			//else settings_formular();
	break;
	case 'images':
			echo $obj->list_image_files_by_user($_SESSION['loginuser']);
			$obj->upload_myfile();
			//$obj->UploadImages();
	break;
	case 'contacts':
			echo $obj->ManageUsers();
	break;
	case 'image':
	if (isset($_GET['image'])) {
			echo($obj->display_image($_GET['image']));
			// echo($obj->display_image_comments($_GET['image']));
	} else echo $msg =<<<DISPLAY_MESSAGES
		<div class="hehe"><div class="smallfont"><center><b><a href="/index.php"><img src="{$obj->synapse_dir}/error.png"><br><br>Images are Protected. Please Login to see more. Refresh Page</a></b></center></div></div><script>reloadPage();</script>
DISPLAY_MESSAGES;
	break;
	case 'submit':
		if (isset($_POST['synapse-title'])) $title = htmlspecialchars($_POST['synapse-title']);
		if (isset($_POST['synapse-bodytext'])) $bodytext = htmlspecialchars($_POST['synapse-bodytext']);
		if (isset($_POST['synapse-sendto'])) $sendto = htmlspecialchars($_POST['synapse-sendto']);
		//
		if ( ( isset($title)) && (isset($bodytext)) && (isset($sendto)) ) {
		
			if (!empty($_REQUEST['captcha'])) {
				if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']) {
					$captcha_message = "Invalid captcha";
					$style = "background-color: #FF606C";
				} else {
					$captcha_message = "Valid captcha";
					$style = "background-color: #CCFF99";
				
					//$obj->write_post_data($_POST);
				
					echo $msg =<<<DISPLAY_MESSAGES
						<div class="hehe"><div class="smallfont"><center><b><a href="index.php">News was Sended. Refresh Page</a></b></center></div></div><script>eraseCookie("pagelines");</script>
DISPLAY_MESSAGES;
					///$obj->accept_question($_POST);
				}
				$request_captcha = htmlspecialchars($_REQUEST['captcha']);
				/*
				echo <<<HTML
			        <div id="result" style="$style">
						<h2>$captcha_message</h2>
					</div>
HTML;
				*/
				unset($_SESSION['captcha']);
			}
			///write_post_data
		}
	break;
	default:
		if (!empty($_POST['karma_yesno'])) {
		    //if ($_POST['karma_yesno']=="Delete Message") { $test = $obj->delete_message($_POST); echo("<div class=\"hehe\"><div class=\"smallfont\"><center><b><a href=\"index.php\">Message Deleted!!! Refresh Page</a></b></center></div></div><script>reloadPage();</script>"); } else
		    //if ($_POST['karma_yesno']=="Pin to Homepage") { $test = $obj->pin_message($_POST); echo("<div class=\"hehe\"><div class=\"smallfont\"><center><b><a href=\"index.php\">Pin Message to Homepage!!! Refresh Page</a></b></center></div></div><script>reloadPage();</script>"); } else
		    if ($_POST['karma_yesno']=="Yes") { $test = $obj->write_karma($_POST); echo("<div class=\"hehe\"><div class=\"smallfont\"><center><b><a href=\"index.php\">Thanks for -Yes- Vote. Refresh Page</a></b></center></div></div><script>reloadPage();</script>"); } else
		    if ($_POST['karma_yesno']=="No") { $test = $obj->write_karma($_POST);  echo("<div class=\"hehe\"><div class=\"smallfont\"><center><b><a href=\"index.php\">Thanks for -No- Vote. Refresh Page</a></b></center></div></div><script>reloadPage();</script>"); }
		    //if ($_POST['karma_yesno']=="Reply") { echo($obj->display_reply_messages($_POST)); } else 
		    //if ($_POST['karma_yesno']=="Share") { $test = $obj->share_data($_POST); } else
		    //if ($_POST['karma_yesno']=="Public Ask") {  $obj->accept_question($_POST); } 
		} else
		if (!empty($_POST['asked_friend'])) $obj->SendQuestion($_POST); else
		if (!empty($_POST['del_friend'])) $obj->delete_friend($_POST); else
		if (!empty($_POST['accept'])) { 
					// potvrdenie accept priatelstva akymkolvek uzivatelom
					$obj->write_friend($_POST);
					$obj->DeleteQuestion($_POST);
		} else
		if (!empty($_POST['send-message'])) {
			$obj->SaveMessage($_POST);
		}
		//
		$chan = isset( $_GET['channel'] ) ? $_GET['channel'] : "life";
		if ($obj->IfExistZone($chan)==0) $chan="life";
		//
		echo $obj->Notifications();
		//
		echo $obj->LatestAccess();
		//
		echo $obj->PostMessage($chan);
		//
		$obj->LoadMessages($chan);
	break;
}
?>