<?php

/**

	Copyright (c) 2013 - 2015  Lukas Veselovsky
	All rights reserved.

The Beauty-Synapse software is licensed under the open source (revised) BSD license, one of the most flexible and liberal licenses available. 
Third-party open source libraries we include in our download are released under their own licenses.

**/

if (!empty($obj->track)) {
	$message =<<<DISPLAY_MESSAGE
			<a href="http://www.toplist.sk/" target="_top"><img
			src="http://toplist.sk/count.asp?id={$obj->track}" alt="TOPlist" border="0"></a>
DISPLAY_MESSAGE;
	echo ($message);
}
?>
