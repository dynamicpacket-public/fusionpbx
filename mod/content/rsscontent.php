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
if (permission_exists('content_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}


//get data from the db
$rssid = $_REQUEST["rssid"];

$sql = "";
$sql .= "select * from v_rss ";
$sql .= "where v_id = '$v_id' ";
$sql .= "and rssid = '$rssid' ";
//echo $sql;
$prepstatement = $db->prepare(check_sql($sql));
$prepstatement->execute();
$result = $prepstatement->fetchAll();
foreach ($result as &$row) {
	$rsscategory = $row["rsscategory"];
	$rsssubcategory = $row["rsssubcategory"];
	$rsstitle = $row["rsstitle"];
	$rsslink = $row["rsslink"];
	$rssdesc = $row["rssdesc"];
	$rssimg = $row["rssimg"];
	$rssoptional1 = $row["rssoptional1"];
	$rssoptional2 = $row["rssoptional2"];
	$rssoptional3 = $row["rssoptional3"];
	$rssoptional4 = $row["rssoptional4"];
	$rssoptional5 = $row["rssoptional5"];
	$rssadddate = $row["rssadddate"];
	$rssadduser = $row["rssadduser"];
	$rssgroup = $row["rssgroup"];
	$rssorder = $row["rssorder"];
	//$rssdesc = str_replace ("\r\n", "<br>", $rssdesc);

	echo $rssdesc;
	//return;

	break; //limit to 1 row
}

?>
