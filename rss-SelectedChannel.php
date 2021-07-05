<?php
//
// IfExistZone than display all messages posted by this zone,
// witthout user posted messages, etc. Music zone display all posts,
// but not display messages by other users posted in this zone,..
//
// Only Public messages by all zones
//
require($_SERVER['DOCUMENT_ROOT'].'init.php');

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
    $res = mysql_query($sql);
    while($rec = mysql_fetch_array($res)) { // $rec["username"]
		$usr=$rec["username"]);
		if ($obj->IfExistZone($usr==1))
		{
			if ($_GET['user']) {
			if ($_GET['user']==$usr) {
				$dat2=gmdate("D, d M Y H:i:s", $rec["created"])." GMT";
					$bd = $obj->get_bodytext($rec["bodytext"], $rec["created"], $usr);
					$bt = $obj->get_titletext($rec["title"], $rec["created"], $usr);
				$entry_display .= <<<MESSAGE_DISPLAY
				<item>
				<title>{$bt}</title>
				<link>message.php?created={$rec["created"]}</link>
				<guid>message.php?created={$rec["created"]}</guid>
				<description>{$bd}</description>
				<pubDate>{$dat2}</pubDate>
				</item>
MESSAGE_DISPLAY;
			}
			}
		}
    }
    $entry_display .= "</channel></rss>";
    echo($entry_display);
?>
