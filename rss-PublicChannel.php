<?php

require($_SERVER['DOCUMENT_ROOT'].'/zones_cl.php');
$obj = new Beauty_Zones();
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/config.php')) require($_SERVER['DOCUMENT_ROOT'].'/config.php'); else include('error.php');

$obj->connect_db();

header('Expires: ' . gmdate('D, d M Y H:i:s') . '  GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . '  GMT');
header('Content-Type: text/xml; charset=utf-8');

$dat1 = gmdate("D, d M Y H:i:s")." GMT";
$entry_display = <<<MESSAGE_DISPLAY
<?xml version="1.0" encoding="utf-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>{$obj->synapse_title}</title>
    <link>{$_SERVER['SERVER_ADDR']}</link>
    <category>social network, blog</category>
    <docs>{$_SERVER['SERVER_ADDR']}/rss</docs>
    <lastBuildDate>{$dat1}</lastBuildDate>
    <atom:link href="{$_SERVER['SERVER_ADDR']}/rss/" rel="self" type="application/rss+xml" />
    <image>
      <url>{$_SERVER['SERVER_ADDR']}/me-sketch.png</url>
      <title>{$obj->synapse_title}</title>
      <link>{$_SERVER['SERVER_ADDR']}</link>
    </image>
MESSAGE_DISPLAY;

    $obj->switch_data_table();
    $sql = "SELECT * FROM data ORDER BY created DESC LIMIT 2048";
    $res = $obj->warp_query($sql);
    while($rec = $obj->warp_fetch_array($res)) { // $rec["username"]
		$usr=$rec["username"];
		if ($obj->IfExistZoneInArray($usr)==1)
		{
				$dat2=gmdate("D, d M Y H:i:s", $rec["created"])." GMT";
					$bd = $obj->get_bodytext($rec["bodytext"], $rec["created"], $usr);
					$bd = strip_tags($bd); 
					$bd = preg_replace("#\[iframe\](.*?)\[/iframe\]#si", "", $bd);
					$bd = $obj->spracuj_form($bd);
				$entry_display .= <<<MESSAGE_DISPLAY
				<item>
				<title>{$usr} zone</title>
				<link>message.php?created={$rec["created"]}</link>
				<guid>message.php?created={$rec["created"]}</guid>
				<description>{$bd}</description>
				<pubDate>{$dat2}</pubDate>
				</item>
MESSAGE_DISPLAY;
		}
    }
    $entry_display .= "</channel></rss>";
    echo($entry_display);
//
// IfExistZone than display all messages posted by this zone,
// witthout user posted messages, etc. Music zone display all posts,
// but not display messages by other users posted in this zone,..
//
// Only Public messages by all zones
//
?>
