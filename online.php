<?php

if ($_POST['submit_btn']=="Logout") {
		//$obj->unlink_files($_SESSION['loginuser']);
					
		$_SESSION['loginuser'] = null;
		unset($_SESSION['loadpage']); 
		unset( $_SESSION['loginuser'] );
		header( "Location: index.php" );
		exit;
}
?>

<style>
body, h1,h2,h3,h4,h5,h6 {font-family: "Montserrat", sans-serif}
.w3-row-padding img {margin-bottom: 12px}
.bgimg {
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  background-image: url('/w3images/profile_girl.jpg');
  min-height: 100%;
}

.tweet {
	CLEAR: left; FONT-SIZE: 12px;  PADDING: 0.0em; MARGIN: 0.0em;
	WIDTH: inherit;
	font-family: verdana, tahoma, arial, helvetica, sans-serif;
	font:200 16px/16px sans-serif;
	
	border-left: 1px dotted #dddddd;
	border-right: 1px dotted #dddddd;
}
.tweet LI {
	PADDING-BOTTOM: 20px; PADDING-TOP: 20px;  
	PADDING-LEFT: 5px; PADDING-RIGHT: 5px;
	
	DISPLAY: block;
	
	TEXT-ALIGN: left; TEXT-DECORATION: none;
	/*LIST-STYLE-TYPE: none;*/
	
	border-bottom: 1px dotted #dddddd;
	
	COLOR: black; 

}
/*
.tweet LI A {
	
	DISPLAY: block; */
	/* FONT-SIZE: 11px;*/  
/*	COLOR: black; 
	TEXT-ALIGN: left; TEXT-DECORATION: none;
}
*/
.tweet LI:hover {
	TEXT-ALIGN: left; TEXT-DECORATION: none;
	
	DISPLAY: block;
	
	color: black;
	box-shadow: 0px 0px 0px 3px rgb(204, 204, 204);
		  background-color: rgb(0,0,0); /* Fallback color */
  
    background-color: rgba(255,100,1,.1); /* Black w/ opacity */
	background-size: 100% 100%;
	
	/* background-color: #eef; */
	/* border-bottom: 1px dotted black; */
	/* FONT-SIZE: 11px;*/
	/*PADDING-LEFT: 7px; */
}


.tweet-avatar {
    position: relative;
    top: 20px;
    left: 20px;
    border-radius: 50% 50% 50% 50%;
        border-top-left-radius: 50%;
        border-top-right-radius: 50%;
        border-bottom-right-radius: 50%;
        border-bottom-left-radius: 50%;
    box-shadow: 0px 0px 0px 3px rgb(204, 204, 204);
}
.tweet-reply-link {
    position: relative;
    top: 60px;
    left: 10px;
    font-size: 13px;
    width: 50px;
    text-align: center;
    color: rgb(204, 204, 204);
}
.tweet-author {
	position: relative;
    font-weight: bold;
    font-size: 16px;
    right: 100px;
}
.tweet-date-link {
    position: relative;
    top: 5px;
    right: 10px;
    font-size: 11px;
    line-height: 13px;
    text-align: right;
}
.tweet-text {
	position: relative;
    font-weight: normal;
    font-size: 16px;
    top: 20px;
    left: 100px;
}

.message-field {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  box-sizing: border-box;
  border: none;
  border-bottom: 2px solid grey;
  background-color: transparent;
  color: grey;
  font-size: 28px;
}

.message-field:hover {
  border-bottom: 2px solid red;
  color: red;
}

.fbutton, .message-submit {
  border: 2px solid red;
  background-color: transparent;
  color: red;
  height: 40px;
}
</style>

<?php 
	// $obj->display_AdminPanel(); 
?>

<div class="w3-top w3-animate-top">
  <div class="w3-center">
		<a href="./index.php?page=logout" class="w3-bar-item w3-button w3-padding-16 w3-underline-color" title="Logout">Logout</a>
		<div class="w3-dropdown-hover">
		<button class="w3-button  w3-padding-16">?</button>
		<div class="w3-dropdown-content w3-bar-block w3-card-4">
		<input type="text" class="w3-bar-item w3-input w3-padding-16 w3-right" style="background-color: #00ffff;" placeholder="Search..">
		<a href="?page=contacts" class="w3-bar-item w3-button w3-padding-16 w3-underline-color">Kontakty</a>
		<a href="?page=images" class="w3-bar-item w3-button w3-padding-16  w3-underline-color">Fotky</a>
		</div>
		</div>
	</div>
</div>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-bar-block w3-white w3-collapse w3-top" style="z-index:3;width:250px" id="mySidebar">
  <div class="w3-container w3-display-container w3-padding-16">
    <i onclick="w3_close()" class="fa fa-remove w3-hide-large w3-button w3-display-topright"></i>
    <h3 class="w3-wide"><b>BLOGINO</b></h3>
	Hi, <?php echo $_SESSION['loginuser']; ?>
  </div>
  <div class="w3-padding-64 w3-large w3-text-grey" style="font-weight:bold">
    <a onclick="myAccFunc()" href="javascript:void(0)" class="w3-button w3-block w3-white w3-left-align" id="myBtn">
      Friends <i class="fa fa-caret-down"></i>
    </a>
	<hr>
    <div id="demoAcc" class="w3-bar-block w3-hide w3-padding-large w3-medium">
	<a href="?page=contacts" class="w3-bar-item w3-button w3-padding w3-blue">Manage users</a><br> 
    <?php echo $obj->FindFriends($_SESSION['loginuser']); ?>
	</div>
		<!--a href="?channel=life" class="w3-bar-item w3-button w3-padding-16" style="text-color: bold;">BLOGINO</a!-->
	
		<?php 
			$icount=0;

			foreach($obj->zones as $x=>$x_value)
			{
			if ($icount<=6) { 
		?>
				<a href="./index.php?channel=<?php echo $x; ?>" class="w3-bar-item w3-button w3-padding-16 w3-underline-color" title="<?php echo $obj->zones_info[$x];?>"><?php echo $x_value; ?></a>
		<?php 
			} 
			$icount++;
			} 
		?>
  </div>
  <hr> 
  <a href="?page=images"  class="w3-bar-item w3-button w3-padding">Photos</a>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<input type="submit" name="submit_btn" value="Logout" class="w3-bar-item w3-button w3-padding-16">
  </form>
</nav>


<div class="w3-main w3-animate-bottom"  style="margin-left:250px">
<div class="w3-show-custom">
	<div class="w3-center">
		<?php 
			$icount=0;

			foreach($obj->zones as $x=>$x_value)
			{
			if ($icount<=6) { 
		?>
				<a href="./index.php?channel=<?php echo $x; ?>" class="w3-bar-item w3-button w3-padding-16 w3-underline-color" title="<?php echo $obj->zones_info[$x];?>"><?php echo $x_value; ?></a>
		<?php 
			} 
			$icount++;
			} 
		?>
	</div>
</div>
<div  style="margin-top:140px;">
<?php
	require($_SERVER['DOCUMENT_ROOT'].'/control.php');
?>
</div>
  <p>
  <?php
echo "<p>Copyright &copy; 2013-" . date("Y") . " Lukas Veselovsky</p>";
?></p>
  <p></p>
  <p><?php
$startdate=strtotime("Saturday");
$enddate=strtotime("+6 weeks", $startdate);

while ($startdate < $enddate) {
  $dt=date("M d", $startdate);
  echo "<a href=\"index.php?date=".$dt."\">".$dt."</a> . ";
  $startdate = strtotime("+1 week", $startdate);
}
?></p>
</div>

<script>
// bbCode control by
// subBlue design
// www.subBlue.com

// Startup variables
var imageTag = false;
var theSelection = false;

// Check for Browser & Platform for PC & IE specific bits
// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
                && (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));
var is_moz = 0;

var is_win = ((clientPC.indexOf("win")!=-1) || (clientPC.indexOf("16bit") != -1));
var is_mac = (clientPC.indexOf("mac")!=-1);

// Define the bbCode tags
bbcode = new Array();
bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[br]','[/br]','[s]','[/s]','[list]','[/list]','[list=]','[/list]','[img]','[/img]','[url]','[/url]');
//bbtags = new Array('<b>','</b>','<i>','</i>','<u>','</u>','<br>','</br>','<s>','</s>','[list]','[/list]','[list=]','[/list]','[img]','[/img]','[url]','[/url]');
imageTag = false;

// Replacement for arrayname.length property
function getarraysize(thearray) {
	for (i = 0; i < thearray.length; i++) {
		if ((thearray[i] == "undefined") || (thearray[i] == "") || (thearray[i] == null))
			return i;
		}
	return thearray.length;
}

// Replacement for arrayname.push(value) not implemented in IE until version 5.5
// Appends element to the array
function arraypush(thearray,value) {
	thearray[ getarraysize(thearray) ] = value;
}

// Replacement for arrayname.pop() not implemented in IE until version 5.5
// Removes and returns the last element of an array
function arraypop(thearray) {
	thearraysize = getarraysize(thearray);
	retval = thearray[thearraysize - 1];
	delete thearray[thearraysize - 1];
	return retval;
}

function inputimgenc_url(id) {
     var txtarea = document.getElementById('text'+id);
    if (!txtarea) return false;
    
    var v = prompt('Enter IMAGE URL');
    if (v) {
        txtarea.value += '<img src="'+v+'">';
    }
}

function inputimg_url(id) {
     var txtarea = document.getElementById('text'+id);
    if (!txtarea) return false;
    
    var v = prompt('Enter IMAGE URL');
    if (v) {
        txtarea.value += '[img]'+v+'[/img]';
    }
}

function input_url(id) {
     var txtarea = document.getElementById('text'+id);
    if (!txtarea) return false;
    
    var v = prompt('Enter URL');
    if (v) {
        txtarea.value += '[url]'+v+'[/url]';
    }
}

function input_youtube(id) {
     var txtarea = document.getElementById('text'+id);
    if (!txtarea) return false;
    
    var v = prompt('Enter URL');
    if (v) {
       // txtarea.value += '<iframe width=\"560\" height=\"315\" src=\"'+v+'\" frameborder=\"0\" allowfullscreen></iframe>';
	    txtarea.value += '[iframe]'+v+'[/iframe]';
    }
}

function bbstyle(bbnumber, id) {

 var txtarea = document.getElementById('text'+id);
 if (!txtarea) return false;


	txtarea.focus();
	donotinsert = false;
	theSelection = false;
	bblast = 0;

	if (bbnumber == -1) { // Close all open tags & default button names
		while (bbcode[0]) {
			butnumber = arraypop(bbcode) - 1;
			txtarea.value += bbtags[butnumber + 1];

            buttext = eval('document.getElementById("addbbcode'+bbnumber+'_'+id+'").value');
            eval('document.getElementById("addbbcode'+bbnumber+'_'+id+'").value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
		}
		imageTag = false; // All tags are closed including image tags :D
		txtarea.focus();
		return false;
	}

	if ((clientVer >= 4) && is_ie && is_win)
	{
		theSelection = document.selection.createRange().text; // Get text selection
		if (theSelection) {
			// Add tags around selection
			document.selection.createRange().text = bbtags[bbnumber] + theSelection + bbtags[bbnumber+1];
			txtarea.focus();
			theSelection = '';
			return false;
		}
	}
	else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))
	{
		mozWrap(txtarea, bbtags[bbnumber], bbtags[bbnumber+1]);
		return false;
	}
	
	// Find last occurance of an open tag the same as the one just clicked
	for (i = 0; i < bbcode.length; i++) {
		if (bbcode[i] == bbnumber+1) {
			bblast = i;
			donotinsert = true;
		}
	}

	if (donotinsert) {		// Close all open tags up to the one just clicked & default button names
		while (bbcode[bblast]) {
				butnumber = arraypop(bbcode) - 1;
				txtarea.value += bbtags[butnumber + 1];
                //buttext = eval('document.getElementById("addbbcode'+bbnumber+'_'+id+'").value');
                //eval('document.getElementById("addbbcode'+bbnumber+'_'+id+'").value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
				imageTag = false;
			}
			txtarea.focus();
			return false;
	} else { // Open tags
	
		if (imageTag && (bbnumber != 14)) {		// Close image tag before adding another
			txtarea.value += bbtags[15];
			lastValue = arraypop(bbcode) - 1;	// Remove the close image tag from the list
			//document.post.addbbcode14.value = "Img";	// Return button back to normal state
			imageTag = false;
		}
		
		// Open tag
		txtarea.value += bbtags[bbnumber];
		if ((bbnumber == 14) && (imageTag == false)) imageTag = 1; // Check to stop additional tags after an unclosed image tag
		arraypush(bbcode,bbnumber+1);
		//eval('document.post.addbbcode'+id+'_'+bbnumber+'.value += "*"');
		txtarea.focus();
        return false;
	}
	storeCaret(txtarea);
	return false;
}

// Accordion 
function myAccFunc() {
  var x = document.getElementById("demoAcc");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else {
    x.className = x.className.replace(" w3-show", "");
  }
}

// Click on the "Jeans" link on page load to open the accordion for demo purposes
document.getElementById("myBtn").click();


// Open and close sidebar
function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("myOverlay").style.display = "block";
}
 
function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
}
</script>