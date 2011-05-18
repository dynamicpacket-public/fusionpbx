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

if (count($_POST)>0) {
	$rsssubcategoryid = check_str($_POST["rsssubcategoryid"]);
	$rsscategory = check_str($_POST["rsscategory"]);
	$rsssubcategory = check_str($_POST["rsssubcategory"]);
	$rsssubcategorydesc = check_str($_POST["rsssubcategorydesc"]);
	$rssadduser = check_str($_POST["rssadduser"]);
	$rssadddate = check_str($_POST["rssadddate"]);

	//sql update
	$sql  = "update v_rss_sub_category set ";
	$sql .= "rsscategory = '$rsscategory', ";
	$sql .= "rsssubcategory = '$rsssubcategory', ";
	$sql .= "rsssubcategorydesc = '$rsssubcategorydesc', ";
	$sql .= "rssadduser = '$rssadduser', ";
	$sql .= "rssadddate = '$rssadddate' ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and rsssubcategoryid = '$rsssubcategoryid' ";
	$count = $db->exec(check_sql($sql));
	//echo "Affected Rows: ".$count;

	//edit: make sure the meta redirect url is correct 
	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"5;url=rsssubcategorylist.php\">\n";
	echo "Update Complete";
	require_once "includes/footer.php";
	return;
}
else {
	//get data from the db
	$rsssubcategoryid = $_GET["rsssubcategoryid"];

	$sql = "";
	$sql .= "select * from v_rss_sub_category ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and rsssubcategoryid = '$rsssubcategoryid' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$rsscategory = $row["rsscategory"];
		$rsssubcategory = $row["rsssubcategory"];
		$rsssubcategorydesc = $row["rsssubcategorydesc"];
		$rssadduser = $row["rssadduser"];
		$rssadddate = $row["rssadddate"];
		break; //limit to 1 row
	}
}

require_once "includes/header.php";
echo "<div align='center'>";
echo "<table border='0' cellpadding='0' cellspacing='2'>\n";

echo "<tr class='border'>\n";
echo "	<td align=\"left\">\n";
echo "      <br>";


echo "<form method='post' action=''>";
echo "<table>";
echo "	<tr>";
echo "		<td>Rsscategory:</td>";
echo "		<td><input type='text' name='rsscategory' value='$rsscategory'></td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td>Rsssubcategory:</td>";
echo "		<td><input type='text' name='rsssubcategory' value='$rsssubcategory'></td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td>Rsssubcategorydesc:</td>";
echo "		<td><input type='text' name='rsssubcategorydesc' value='$rsssubcategorydesc'></td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td>Rssadduser:</td>";
echo "		<td><input type='text' name='rssadduser' value='$rssadduser'></td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td>Rssadddate:</td>";
echo "		<td><input type='text' name='rssadddate' value='$rssadddate'></td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td colspan='2' align='right'>";
echo "     <input type='hidden' name='rsssubcategoryid' value='$rsssubcategoryid'>";
echo "     <input type='submit' name='submit' value='Update'>";
echo "		</td>";
echo "	</tr>";
echo "</table>";
echo "</form>";


echo "	</td>";
echo "	</tr>";
echo "</table>";
echo "</div>";


  require_once "includes/footer.php";
?>
