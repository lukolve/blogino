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
  if (empty($_POST["nick"])) {
    $nickErr = "Nick is required";
  } else {
	$nick = test_input($_POST["nick"]);
	if (!preg_match("/^[a-zA-Z ]*$/",$nick)) {
		$nickErr = "Only letters and white space allowed";
	}
  }

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
  
  if (empty($_POST["age"])) {
    $ageErr = "Age is required";
  } else {
    $age = test_input($_POST["age"]);
  }
  
  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }
}

?>

<h4 align="">User Details</h4><br />
	<?php echo "<h5>".$error."</h5>"; ?>
	<form method="post" NAME="formular" action="/index.php?page=register.php">
		Nick: <input class="form-control" type="text" name="nick" value="<?php echo $nick;?>">
		<span class="error">* <?php echo $nickErr;?></span>
		<br><br>
		Name: <input class="form-control" type="text" name="name" value="<?php echo $name;?>">
		<span class="error">* <?php echo $nameErr;?></span>
		<br><br>
		Surname: <input class="form-control" type="text" name="surname" value="<?php echo $surname;?>">
		<span class="error">* <?php echo $surnameErr;?></span>
		<br><br>
		Password: <input class="form-control" type="text" name="password" value="<?php echo $password;?>">
		<span class="error">* <?php echo $passwordErr;?></span>
		<br><br>
		Re-Password: <input class="form-control" type="text" name="repassword" value="<?php echo $repassword;?>">
		<span class="error">* <?php echo $repasswordErr;?></span>
		<br><br>
		E-mail:
		<input class="form-control" type="text" name="email" value="<?php echo $email;?>">
		<span class="error">* <?php echo $emailErr;?></span>
		<br><br>
		Age: <input class="form-control" type="text" name="age" value="<?php echo $age;?>">
		<span class="error">* <?php echo $ageErr;?></span>
		<br><br>
		<label>Gender</label>
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

		<input type="hidden" name="created" value="<?php echo time(); ?>">
	    <input type="submit" name="submit_btn" value="Register" class="btn btn-info" onclick="myNotify('Send Formular');" /><br />
    </form>

