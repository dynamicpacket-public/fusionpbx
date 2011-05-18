<?php
/*
	FusionPBX
	Version: MPL 1.1

	The contents of this file are subject to the Mozilla Public License Version
	1.1 (the "License"); you may not use this file except in compliance with
	the License. You may obtain a copy of the License at
	http://www.mozilla.org/MPL/

	Software distributed under the License is distributed on an "AS IS" basis,
	WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
	for the specific language governing rights and limitations under the
	License.

	The Original Code is FusionPBX

	The Initial Developer of the Original Code is
	Mark J Crane <markjcrane@fusionpbx.com>
	Portions created by the Initial Developer are Copyright (C) 2008-2010
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/
include "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
require_once "config.php";
if (permission_exists('content_edit')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//move down more than one level at a time
//update v_rss set rssorder = (rssorder+1) where rssorder > 2 or rssorder = 2

if (count($_GET)>0) {
	$rssid = check_str($_GET["rssid"]);
	$rssorder = check_str($_GET["rssorder"]);

	$sql = "SELECT rssorder FROM v_rss ";
	$sql .= "where v_id  = '$v_id' ";
	$sql .= "and rsscategory  = '$rsscategory' ";
	$sql .= "order by rssorder desc ";
	$sql .= "limit 1 ";
	//echo $sql."<br><br>";
	//return;
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		//print_r( $row );
		$highestrssorder = $row[rssorder];
	}
	unset($prepstatement);

	if ($rssorder != $highestrssorder) {
		//move the current item's order number up
		$sql  = "update v_rss set ";
		$sql .= "rssorder = (rssorder-1) "; //move down
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and rssorder = ".($rssorder+1)." ";
		$sql .= "and rsscategory  = '$rsscategory' ";
		//echo $sql."<br><br>";
		$db->exec(check_sql($sql));
		unset($sql);

		//move the selected item's order number down
		$sql  = "update v_rss set ";
		$sql .= "rssorder = (rssorder+1) "; //move up
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and rssid = '$rssid' ";
		$sql .= "and rsscategory  = '$rsscategory' ";
		//echo $sql."<br><br>";
		$db->exec(check_sql($sql));
		unset($sql);
	}
	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"1;url=rsslist.php?rssid=$rssid\">\n";
	echo "<div align='center'>";
	echo "Item Moved Down";
	echo "</div>";
	require_once "includes/footer.php";
	return;
}


?>
