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

$rssid = $_GET["rssid"];

if (count($_POST)>0) {
	$rssid = check_str($_POST["rssid"]);
	$rsssubtitle = check_str($_POST["rsssubtitle"]);
	$rsssublink = check_str($_POST["rsssublink"]);
	$rsssubdesc = check_str($_POST["rsssubdesc"]);
	$rsssuboptional1 = check_str($_POST["rsssuboptional1"]);
	$rsssuboptional2 = check_str($_POST["rsssuboptional2"]);
	$rsssuboptional3 = check_str($_POST["rsssuboptional3"]);
	$rsssuboptional4 = check_str($_POST["rsssuboptional4"]);
	$rsssuboptional5 = check_str($_POST["rsssuboptional5"]);
	$rsssubadddate = check_str($_POST["rsssubadddate"]);
	$rsssubadduser = check_str($_POST["rsssubadduser"]);

	$sql = "insert into v_rss_sub ";
	$sql .= "(";
	$sql .= "v_id, ";
	$sql .= "rssid, ";
	$sql .= "rsssubtitle, ";
	$sql .= "rsssublink, ";
	$sql .= "rsssubdesc, ";
	$sql .= "rsssuboptional1, ";
	$sql .= "rsssuboptional2, ";
	$sql .= "rsssuboptional3, ";
	$sql .= "rsssuboptional4, ";
	$sql .= "rsssuboptional5, ";
	$sql .= "rsssubadddate, ";
	$sql .= "rsssubadduser ";
	$sql .= ")";
	$sql .= "values ";
	$sql .= "(";
	$sql .= "'$v_id', ";
	$sql .= "'$rssid', ";
	$sql .= "'$rsssubtitle', ";
	$sql .= "'$rsssublink', ";
	$sql .= "'$rsssubdesc', ";
	$sql .= "'$rsssuboptional1', ";
	$sql .= "'$rsssuboptional2', ";
	$sql .= "'$rsssuboptional3', ";
	$sql .= "'$rsssuboptional4', ";
	$sql .= "'$rsssuboptional5', ";
	$sql .= "now(), ";
	$sql .= "'".$_SESSION["username"]."' ";
	$sql .= ")";
	$db->exec(check_sql($sql));
	unset($sql);

	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"2;url=rsssublist.php?rssid=$rssid\">\n";
	echo "<div align='center'>";
	echo "Add Complete";
	echo "</div>";
	require_once "includes/footer.php";
	return;
}

	require_once "includes/header.php";
	require_once "includes/wysiwyg.php";
	echo "<div align='center'>";
	echo "<table border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "      <br>";


	echo "<form method='post' action=''>";
	echo "<table width='100%'>";

	echo "	<tr>";
	echo "		<td nowrap>Title:</td>";
	echo "		<td width='100%'><input type='text' class='txt' name='rsssubtitle'></td>";
	echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td>Link:</td>";
	//echo "		<td><input type='text' class='txt' name='rsssublink'></td>";
	//echo "	</tr>";
	echo "	<tr>";
	echo "		<td valign='top'>Description:</td>";
	echo "        <td>";
	echo "		    <textarea class='txt' rows='12' name='rsssubdesc'></textarea>";
	echo "        </td>";
	echo "	</tr>";
	/*
	echo "	<tr>";
	echo "		<td>Rsssuboptional1:</td>";
	echo "		<td><input type='text' name='rsssuboptional1'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td>Rsssuboptional2:</td>";
	echo "		<td><input type='text' name='rsssuboptional2'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td>Rsssuboptional3:</td>";
	echo "		<td><input type='text' name='rsssuboptional3'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td>Rsssuboptional4:</td>";
	echo "		<td><input type='text' name='rsssuboptional4'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td>Rsssuboptional5:</td>";
	echo "		<td><input type='text' name='rsssuboptional5'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td>Rsssubadddate:</td>";
	echo "		<td><input type='text' name='rsssubadddate'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td>Rsssubadduser:</td>";
	echo "		<td><input type='text' name='rsssubadduser'></td>";
	echo "	</tr>";
	*/
	//echo "	<tr>";
	//echo "	<td>example:</td>";
	//echo "	<td><textarea name='example'></textarea></td>";
	//echo "	</tr>";    echo "	<tr>";
	echo "		<td colspan='2' align='right'>";
	echo "		    <input type='hidden' name='rssid' value='$rssid'>";
	echo "          <input type='submit' name='submit' class='btn' value='Add'>";
	echo "      </td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";

	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";


require_once "includes/footer.php";
?>
