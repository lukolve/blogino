<?php
$captcha_message = "";
$captcha_style = "";
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

// define variables and set to empty values
$nameErr = $surnameErr = $nickErr =$passwordErr = $repasswordErr = $emailErr = $genderErr = $websiteErr = "";
$name = $surname = $nick =$password = $repassword = $email = $gender = $comment = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
	$name = test_input($_POST["name"]);
	if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
		$nameErr = "Only letters and white space allowed";
	}
  }

  if (empty($_POST["surname"])) {
    $surnameErr = "Surname is required";
  } else {
	$surname = test_input($_POST["surname"]);
	if (!preg_match("/^[a-zA-Z ]*$/",$surname)) {
		$surnameErr = "Only letters and white space allowed";
	}
  }
   
  if (empty($_POST["nick"])) {
    $nickErr = "Nick is required";
  } else {
	$nick = test_input($_POST["nick"]);
	if (!preg_match("/^[a-zA-Z ]*$/",$nick)) {
		$nickErr = "Only letters and white space allowed";
	}
  }

  if (empty($_POST["password"])) {
    $passwordErr = "Password is required";
  } else {
    $password = test_input($_POST["password"]);
  }
  
  if (empty($_POST["repassword"])) {
    $repasswordErr = "Re-Password is required";
  } else {
    $repassword = test_input($_POST["repassword"]);
  }

  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$emailErr = "Invalid email format";
	}
  }

  $website = test_input($_POST["website"]);
  if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
	$websiteErr = "Invalid URL";
  }
  
  if (empty($_POST["comment"])) {
    $comment = "";
  } else {
    $comment = test_input($_POST["comment"]);
  }
  
  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

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
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
  
 <p><br></p>
  <p><br></p>
  <h2  class="animate__animated animate__bounce">Its time to try Our Social Network!</h1>
  <h2  class="animate__animated animate__bounce">Sometimes less is more!</h2>
  <h2  class="animate__animated animate__bounce">We know this and make new type of experience of messaging.</h2>
  <p><br></p>
  <h2  class="animate__animated animate__swing"><?php echo $obj->howmanyusers(); ?> uživateľov je k dnešnému dňu zaregistrovaných..</h2>
  <h2  class="animate__animated animate__swing"><?php echo $obj->howmanyzones(); ?> miestností je pripravených k diskusii..</h2>
  <p></br></p>

<div class="w3-top">
  <div class="w3-bar w3-light-grey">
    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="w3-bar-item w3-button w3-padding-16" style="color: bold;">BLOGINO</a>
    <a id="myBtnCookie"  class="w3-bar-item w3-button w3-padding-16">License and Privacy Agreement</a> <!-- href="./License"  !-->
	<div class="w3-hide-custom">
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<input type="submit" name="submit_btn" value="Sign In" class="w3-bar-item w3-button w3-padding-16 w3-right">
	<input type="password" name="password" class="w3-bar-item w3-button w3-padding-16 w3-right" style="width: 100px;" placeholder="Password..">
	<input type="text" name="nick" class="w3-bar-item w3-button w3-padding-16 w3-right" style="width: 100px;" placeholder="Nick..">
	</form>
	</div>
  </div>
  <div class="w3-topbarline"></div>
  <div class="w3-show-custom" style="max-width: 100%; height: 54px; background-color: #efefdf;" >
	<div class="w3-left w3-padding-16" style="padding-left:16px;">Register, or Sign In</div>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<input type="submit" name="submit_btn" value="Sign In" class="w3-bar-item w3-button w3-padding-16 w3-right">
	<input type="password" name="password" class="w3-bar-item w3-button w3-padding-16 w3-right" style="width: 100px;" placeholder="Password..">
	<input type="text" name="nick" class="w3-bar-item w3-button w3-padding-16 w3-right" style="width: 100px;" placeholder="Nick..">
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
<div class="w3-container w3-animate-bottom">
  <h3>Are you have interest?</h3>
  <p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<input type="hidden" name="asked_work" value="##REGISTERCHANNEL##">
Name: <input class="message-field" type="text" name="name" value="<?php echo $name;?>">
<span class="error">* <?php echo $nameErr;?></span>
<br><br>
Surname: <input class="message-field" type="text" name="surname" value="<?php echo $surname;?>">
<span class="error">* <?php echo $surnameErr;?></span>
<br><br>
Nick: <input class="message-field" type="text" name="nick" value="<?php echo $nick;?>">
<span class="error">* <?php echo $nickErr;?></span>
<br><br>
Password: <input class="message-field" type="text" name="password" value="<?php echo $password;?>">
<span class="error">* <?php echo $passwordErr;?></span>
<br><br>
Re-Password: <input class="message-field" type="text" name="repassword" value="<?php echo $repassword;?>">
<span class="error">* <?php echo $repasswordErr;?></span>
<br><br>
E-mail:
<input class="message-field" type="text" name="email" value="<?php echo $email;?>">
<span class="error">* <?php echo $emailErr;?></span>
<br><br>
Country:
	<select class="message-field" id="country" name="country">
        <option value="slovakia">Slovensko</option>
        <option value="czech">Česko</option>
        <option value="other">iné</option>
      </select>
<br><br>
Gender:
<input type="radio" name="gender"
<?php if (isset($gender) && $gender=="female") echo "checked";?>
value="female">Female
<input type="radio" name="gender"
<?php if (isset($gender) && $gender=="male") echo "checked";?>
value="male">Male
<input type="radio" name="gender"
<?php if (isset($gender) && $gender=="other") echo "checked";?>
value="other">Other
<span class="error">* <?php echo $genderErr;?></span>
<br><br>
<img src="cool-php-captcha/captcha.php" id="captcha" />
<br><br>
<a href="#" onclick=" document.getElementById('captcha').src='cool-php-captcha/captcha.php?'+Math.random(); document.getElementById('captcha-form').focus();" id="change-image">Not readable? Change text.</a><br/><br/>
<input type="text" class="captcha-field" name="captcha" id="captcha-form" /><br />
<br><br>
<div id="result" style="$style">
<h2><?php if (isset($captcha_message)) echo $captcha_message; ?></h2>
</div>
<button class="w3-button w3-teal" style="width: 270px; height: 70px;" type="submit" name="submit_btn" value="Register">+ Registrovať</button>
</form>

  </p>
  <h1><a href="t/"><?php echo $obj->title; ?></a></h1>
  <p>
  <?php
echo "<p>{$obj->author}</p>";
?></p>
  <p>Lot of thanks to W3Schools.com, where i get more inspiration and i learn to program to write web pages.<br>Third-party open source libraries we include in our download are released under their own licenses.</p>
  <p><br></p>
  <p><?php
echo "Today is " . date("Y/m/d") . "<br>"; 
?>
</p>
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
