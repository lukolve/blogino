<?php

require($_SERVER['DOCUMENT_ROOT'].'/zones_cl.php');
$obj = new Beauty_Zones();
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/config.php')) require($_SERVER['DOCUMENT_ROOT'].'/config.php'); else include('setup-config.php');

// connect to DB and test for login,password,db variables
$obj->connect_db();
//if (!isset( $obj->userslist )) $obj->userslist = $obj->usersarray();
$obj->Firsttime_init(); 
// Need to initialize before
if(!isset($_SESSION["loginuser"])) $_SESSION["loginuser"]=null;
if(!isset($_SESSION['pageid'])) $_SESSION['pageid']=1;
$_SESSION['zeroview']=0;
if(!isset($_SESSION['viewpage'])) $_SESSION['viewpage']="##ALLFRIENDS##";

?>


