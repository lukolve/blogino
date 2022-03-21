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

pre,
code {
    font-family: 'Roboto Mono', monospace;
}
pre {
    padding: 1em;
    background-color: var(--code-bg);
    border: 1pt solid #93a1a1;
    box-shadow: 2pt 2pt 4pt #93a1a1;
    white-space: pre;
    word-break: break-all;
    word-wrap: break-word;
    overflow-x: auto;
}
code {
    background-color: var(--code-bg);
    font-size: 90%;
    padding: 2px 4px;
    border-radius: 0.3em;
}
pre code {
    padding: 0px;
}
a {
    text-decoration: none;
    color: var(--anchor-color);
}
a:hover {
    text-decoration: underline;
    color: var(--anchor-hover-color);
}
a:focus {
    outline: thin dotted;
}
b,
strong {
    font-weight: bold;
}
blockquote {
    font-style: italic;
    color: var(--blockquote-color);
}
blockquote p {
    display: inline;
}
q, blockquote {
    quotes: "\201C" "\201D" "\2018" "\2019";
}
q:before, blockquote:before {
    content: open-quote;
}
q:after, blockquote:after {
    content: close-quote;
}
small {
    font-size: 80%;
}
ul ul,
ol ol {
    padding-left: 1em;
}
svg:not(:root) {
    overflow: hidden;
}
svg {
    stroke: #67767e;
    height: 1em;
    width: 1em;
    fill: none;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}
th {
    color: var(--header-color);
}
nav {
    display: inline-flex;
    font-size: 1.2rem;
}
nav a:first-child {
    margin-left: 0;
}
nav a:last-child  {
    margin-right: 0;
}
nav a {
    margin-left: 0.3em;
    margin-right: 0.3em;
    text-transform: uppercase;
}
footer {
    margin-top: 7%;
    text-align: center;
}
footer a {
    font-size: .9em;
}
footer nav {
    display: flex;
    justify-content: center;
    margin-bottom: .8em;
}
kbd {
    display: inline-block;
    font-family: Arial, Helvetica, sans-serif;
    font-size: .75em;
    color: #333;
    background-color: #f7f7f7;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-shadow: 0 1px 0 rgba(0,0,0,0.2), 0 0 0 2px #fff inset;
    padding: .3em .6em .1em .6em;
}
iframe {
    max-width: 100%;
}
.banner {
    display: flex;
    align-items: center;
    justify-content: center;
    padding-bottom: 1.5em;
}
.banner nav {
    font-weight: bold;
}
.banner h1 {
    text-align: center;
    margin-top: .1em;
}
.banner img {
    max-width: 180px;
    padding-right: 1.5em;
}
.align {
    display: inline-block;
    vertical-align: middle;
}
.posts,
.projects,
.contributions {
    list-style: none;
    padding-left: .5em;
}
.posts li,
.projects li,
.contributions li {
    display: flex;
    align-items: baseline;
	margin-top: 1em;
}
.posts .section-meta {
    text-align: right;
    min-width: 7em;
}
.groupby {
    margin-top: 1em;
    list-style: none;
}
.post-meta,
.section-meta {
    font-size: 75%;
    color: var(--blockquote-color);
}
.section-meta {
    margin-right: 1em;
}
.list {
    display: inline;
    list-style: none;
    padding-left: 0;
}
.list li {
    display: inline;
}
.list li:after {
    content: ", ";
}
.list li:last-child:after {
    content: "";
}
@media screen and (max-width: 690px) {
    body {
     //   margin: 2em 1em;
    }
    p {
        text-align: justify;
    }
    .banner {
      //  display: block;
        padding-bottom: 0;
    }
    .banner img {
        padding-right: 10px;
    }
    .banner nav {
     //   display: block;
        margin-top: 1em;
    }
    .banner nav a {
      //  display: block;
        margin: 0;
		margin-left: 0.3em;
    margin-right: 0.3em;
        text-align: center;
    }
    #name {
        display: none;
    }
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
	<p><br></p>
	<h2  class="animate__animated animate__swing"><?php echo $obj->howmanyusers(); ?> uživateľov je k dnešnému dňu zaregistrovaných..</h2>
	<h2  class="animate__animated animate__swing"><?php echo $obj->howmanyzones(); ?> miestností je pripravených k diskusii..</h2>
  <p></br></p>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<input type="submit" name="submit_btn" value="Sign In" class="w3-bar-item w3-button w3-padding-16 w3-right">
	<input type="password" name="password" class="w3-bar-item w3-button w3-padding-16 w3-right" style="width: 100px;" placeholder="Password..">
	<input type="text" name="nick" class="w3-bar-item w3-button w3-padding-16 w3-right" style="width: 100px;" placeholder="Nick..">
	</form>
  </div>

</div>

<div class="banner">
    <div>
      <nav>
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
      </nav>
    </div>
</div>

<h2>Welcome</h2>
<p>This website is room style messaging portal where you can share within the your friends a news and other content. You can get like to message, and if this news is very liked then goes automaticaly to global dashboard.</p>

<!-- item container class=tweet -->
<div>
<ul class="projects" id="itemContainer">
<?php
	$obj->switch_data_table();
    $sql = "SELECT * FROM data ORDER BY created DESC LIMIT 2048";
    $res = $obj->warp_query($sql);
	$chan = isset( $_GET['channel'] ) ? $_GET['channel'] : "life";
	$num = 0;
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
				<li class="groupby">
				<time class="section-meta" datetime="{$dat2}">
					{$dat2}
				</time>
					{$bd}<a href=""></a>
				</li>
MESSAGE_DISPLAY;
			$num++;
		}
    }
	if ($num==0) $entry_display .= "<li><b>Here is silent and so empty!</b></li>";
    $entry_display .= "</ul>";
    echo($entry_display);
?>
</div>

<div class="banner">
<a id="myBtnCookie"  class="w3-bar-item w3-button w3-padding-16">License and Privacy Agreement</a><br>
<a href="index.php?page=new" class="w3-bar-item w3-button w3-padding-16" style="color: bold;">-IDEM MEDZI VÁS</a>
<a href=""></a>
</div>

<link rel="stylesheet" type="text/css" href="syntax.css">
<link rel="stylesheet" type="text/css" href="zoom.css">
<script defer="defer" type="text/javascript" src="zoom-vanilla.js"></script>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function() {
    images = document.querySelectorAll("img[data-action=\"zoom\"]");
    images.forEach(function(image) {
      image.style.display = "block";
    })
  }, false);
</script>

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
