<style>
.message-field {
  width: 300px;
  padding: 12px 20px;
  margin: 8px 0;
  box-sizing: border-box;
  border: none;
  border-bottom: 2px solid grey;
  background-color: transparent;
  color: grey;
  font-size: 18px;
}

.message-field:hover {
  border-bottom: 2px solid red;
  color: red;
}

.message-submit {
  border: 2px solid grey;
  background-color: transparent;
  color: grey;
  height: 40px;
}

.message-submit:hover {
  border: 2px solid red;
  background-color: transparent;
  color: red;
  height: 40px;
}


.captcha-field[type=text] {
  width: 300px;
  padding: 12px 20px;
  margin: 8px 0;
  box-sizing: border-box;
  border: 1px solid #555;
  outline: none;
}

.captcha-field:focus {
  background-color: lightblue;
}
</style>
<link rel="stylesheet" type="text/css" href="twitter.css">

<div class="w3-top">
  <div class="w3-bar w3-light-grey">
    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="w3-bar-item w3-button w3-padding-16" style="color: bold;">BLOGINO</a>
	<a id="myBtnCookie"  class="w3-bar-item w3-button w3-padding-16">License and Privacy Agreement</a> <!-- href="./License"  !-->
	<div class="w3-hide-custom">
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<input type="text" class="w3-bar-item w3-input w3-padding-16 w3-right" style="max-width: 300px; background-color: #dfefff;" placeholder="Search..">
	</form>
	<a href="index.php?page=new" class="w3-bar-item w3-button w3-padding-16" style="color: bold;">CHCEM SA PRIHLÁSIŤ</a>
	</div>
	<div class="w3-show-custom">
	<a href="index.php?page=new" class="w3-bar-item w3-button w3-padding-16 w3-right" style="color: bold;">@</a>
	</div>
  </div>
  <div class="w3-topbarline"></div>
  <div class="w3-show-custom">
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<input type="text" class="w3-bar-item w3-input w3-padding-16 w3-right" style="max-width: 100%; background-color: #dfefff;" placeholder="Search..">
  </form>
  </div>
</div>

<style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>
<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span> 
	<p><?php echo $obj->author; ?></p>
	<p>All rights reserved.</p>
	<p><br></p>
	<p>The Beauty-Zones software is licensed under the open source (revised) BSD license, one of the most flexible and liberal licenses available. 
	Third-party open source libraries we include in our download are released under their own licenses.</p>
	<p><br></p>
	<p>Týmto Vás žiadame o súhlas s používaním Vašich údajov na nasledujúce účely:</p>
	<p>1. Uchovávanie a/alebo prístup k informáciám na zariadení</p>
	<p>2. Personalizované reklamy a obsah, štatistiky a reklamy produktov</p>
	<p>3. Vaše osobné údaje budú spracované a informácie z vášho zariadenia (súbory cookie, jedinečné identifikátory a ďalšie údaje zariadenia) môžu byť uchovávané, používané, prípadne používané konkrétne týmto webom alebo aplikáciou.</p>
	<p>4. Súhlas môžete zrušiť vyčistením cache vo vašom prehliadači prípadne časti tejto stránky alebo v našich pravidlách ochrany súkromia.</p>
  </div>

</div>

<div class="w3-main" style="margin-top: 130px">
	<div class="w3-center">
		<h3>
		<?php 
			$icount=0;

			foreach($obj->zones as $x=>$x_value)
			{
			if ($icount<=6) { 
		?>
				<a href="./index.php?channel=<?php echo $x; ?>" class="w3-padding-16" title="<?php echo $obj->zones_info[$x];?>"><?php echo $x_value; ?></a>
		<?php 
			} 
			$icount++;
			} 
		?>
		</h3>
		<!-- item container -->
		<ul class="tweet" id="itemContainer">
<?php
	$obj->switch_data_table();
    $sql = "SELECT * FROM data ORDER BY created DESC LIMIT 2048";
    $res = $obj->warp_query($sql);
	$chan = isset( $_GET['channel'] ) ? $_GET['channel'] : "life";
    while($rec = $obj->warp_fetch_array($res)) { // $rec["username"]
		$usr=$rec["username"];
		if ($obj->IfExistZoneInArray($usr)==1) if ((strstr($usr, $chan)!==false)||(strstr($chan, "life")!==false))
		{
				$dat2=gmdate("D, d M Y H:i:s", $rec["created"])." GMT";
					$bd = $obj->get_bodytext($rec["bodytext"], $rec["created"], $usr);
					$bd = strip_tags($bd); 
					$bd = preg_replace("#\[iframe\](.*?)\[/iframe\]#si", "", $bd);
					$bd = $obj->spracuj_form($bd);
				$entry_display .= <<<MESSAGE_DISPLAY
				<li class="text">
				<title>{$usr} zone</title>
				<description>{$bd}</description>
				<pubDate>{$dat2}</pubDate>
				</li>
MESSAGE_DISPLAY;
		}
    }
    $entry_display .= "</ul>";
    echo($entry_display);
?>
	<!-- navigation holder -->
    <div class="holder">
    </div>
	</div>
</div>


<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtnCookie");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>