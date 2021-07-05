<?php
error_reporting(0);
include($_SERVER['DOCUMENT_ROOT'].'/init.php');
?>

<link rel="stylesheet" type="text/css" href="./admin.css">
<link rel="stylesheet" type="text/css" href="./twitter.css">

</head>
 <body style="background-image: url(); background-color: rgb(200, 200, 200); color: rgb(0, 0, 0);" alink="#ee0000" link="#0000ee" vlink="#551a8b">
	<center>
	<?php
		$crea = htmlspecialchars($_GET['created']);
		
		if(isset($crea)) { $obj->preview_type = 1; echo($obj->get_message($crea)); } else echo("Failed to load message.....");
	?>
	</center>
  </body>

</html>
