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
if (permission_exists('content_add')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}


if (count($_POST)>0) {
	$rsscategory = check_str($_POST["rsscategory"]);
	$rsssubcategory = check_str($_POST["rsssubcategory"]);
	$rsssubcategorydesc = check_str($_POST["rsssubcategorydesc"]);
	$rssadduser = check_str($_POST["rssadduser"]);
	$rssadddate = check_str($_POST["rssadddate"]);

	$sql = "insert into v_rss_sub_category ";
	$sql .= "(";
	$sql .= "v_id, ";
	$sql .= "rsscategory, ";
	$sql .= "rsssubcategory, ";
	$sql .= "rsssubcategorydesc, ";
	$sql .= "rssadduser, ";
	$sql .= "rssadddate ";
	$sql .= ")";
	$sql .= "values ";
	$sql .= "(";
	$sql .= "'$v_id', ";
	$sql .= "'$rsscategory', ";
	$sql .= "'$rsssubcategory', ";
	$sql .= "'$rsssubcategorydesc', ";
	$sql .= "'$rssadduser', ";
	$sql .= "'$rssadddate' ";
	$sql .= ")";
	$db->exec(check_sql($sql));
	unset($sql);

	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"5;url=rsssubcategorylist.php\">\n";
	echo "Add Complete";
	require_once "includes/footer.php";
	return;
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
echo "		<td><input type='text' name='rsscategory'></td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td>Rsssubcategory:</td>";
echo "		<td><input type='text' name='rsssubcategory'></td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td>Rsssubcategorydesc:</td>";
echo "		<td><input type='text' name='rsssubcategorydesc'></td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td>Rssadduser:</td>";
echo "		<td><input type='text' name='rssadduser'></td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td>Rssadddate:</td>";
echo "		<td><input type='text' name='rssadddate'></td>";
echo "	</tr>";
//echo "	<tr>";
//echo "	<td>example:</td>";
//echo "	<td><textarea name='example'></textarea></td>";
//echo "	</tr>";    echo "	<tr>";
echo "		<td colspan='2' align='right'><input type='submit' name='submit' value='Add'></td>";
echo "	</tr>";
echo "</table>";
echo "</form>";


echo "	</td>";
echo "	</tr>";
echo "</table>";
echo "</div>";


require_once "includes/footer.php";
?>
