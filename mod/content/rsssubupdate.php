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

$rssid = $_GET["rssid"];

if (count($_POST)>0 && $_POST["persistform"] == "0") {
	$rsssubid = check_str($_POST["rsssubid"]);
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

	$msg = '';
	if (strlen($rssid) == 0) { $msg .= "Error missing rssid.<br>\n"; }
	if (strlen($rsssubid) == 0) { $msg .= "Error missing rsssubid.<br>\n"; }
	//if (strlen($rsssubtitle) == 0) { $msg .= "Please provide a title.<br>\n"; }
	if (strlen($rsssubdesc) == 0) { $msg .= "Please provide a description.<br>\n"; }

	if (strlen($msg) > 0) {
		require_once "includes/persistform.php";
		require_once "includes/header.php";
		echo "<div align='center' style='' >";
		echo "<table>";
		echo "<tr>";
		echo "<td>";
		echo "  <div class='borderlight' align='left' style='padding:10px;'>";
		echo "      $msg";
		echo "      <br>";
		echo "      <div align='center'>".persistform($_POST)."</div>";
		echo "  </div>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</div>";

		require_once "includes/footer.php";
		return;
	}


	//sql update
	$sql  = "update v_rss_sub set ";
	//$sql .= "rssid = '$rssid', ";
	$sql .= "rsssubtitle = '$rsssubtitle', ";
	$sql .= "rsssublink = '$rsssublink', ";
	$sql .= "rsssubdesc = '$rsssubdesc', ";
	$sql .= "rsssuboptional1 = '$rsssuboptional1', ";
	$sql .= "rsssuboptional2 = '$rsssuboptional2', ";
	$sql .= "rsssuboptional3 = '$rsssuboptional3', ";
	$sql .= "rsssuboptional4 = '$rsssuboptional4', ";
	$sql .= "rsssuboptional5 = '$rsssuboptional5' ";
	//$sql .= "rsssubadddate = now(), ";
	//$sql .= "rsssubadduser = '".$_SESSION["username"]."' ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and rsssubid = '$rsssubid' ";
	//$sql .= "and rssid = '$rssid' ";
	$count = $db->exec(check_sql($sql));
	//echo "Affected Rows: ".$count;

	//edit: make sure the meta redirect url is correct 
	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"2;url=rsssublist.php?rssid=$rssid&rsssubid=$rsssubid\">\n";
	echo "<div align='center'>";
	echo "Update Complete";
	echo "</div>";
	require_once "includes/footer.php";
	return;
}
else {
	//get data from the db
	$rsssubid = $_GET["rsssubid"];

	$sql = "";
	$sql .= "select * from v_rss_sub ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and rsssubid = '$rsssubid' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		//$rssid = $row["rssid"];
		$rsssubtitle = $row["rsssubtitle"];
		$rsssublink = $row["rsssublink"];
		$rsssubdesc = $row["rsssubdesc"];
		$rsssuboptional1 = $row["rsssuboptional1"];
		$rsssuboptional2 = $row["rsssuboptional2"];
		$rsssuboptional3 = $row["rsssuboptional3"];
		$rsssuboptional4 = $row["rsssuboptional4"];
		$rsssuboptional5 = $row["rsssuboptional5"];
		$rsssubadddate = $row["rsssubadddate"];
		$rsssubadduser = $row["rsssubadduser"];
		break; //limit to 1 row
	}
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
	//echo "	<tr>";
	//echo "		<td>Rssid:</td>";
	//echo "		<td><input type='text' name='rssid' class='txt' value='$rssid'></td>";
	//echo "	</tr>";
	echo "	<tr>";
	echo "		<td nowrap>Sub Title:</td>";
	echo "		<td width='100%'><input type='text' name='rsssubtitle' class='txt' value='$rsssubtitle'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td>Sub Link:</td>";
	echo "		<td><input type='text' name='rsssublink' class='txt' value='$rsssublink'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td valign='top'>Description:</td>";
	echo "		<td>";
	echo "            <textarea name='rsssubdesc' rows='12' class='txt'>$rsssubdesc</textarea>";
	echo "        </td>";
	echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td>Rsssuboptional1:</td>";
	//echo "		<td><input type='text' name='rsssuboptional1' value='$rsssuboptional1'></td>";
	//echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td>Rsssuboptional2:</td>";
	//echo "		<td><input type='text' name='rsssuboptional2' value='$rsssuboptional2'></td>";
	//echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td>Rsssuboptional3:</td>";
	//echo "		<td><input type='text' name='rsssuboptional3' value='$rsssuboptional3'></td>";
	//echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td>Rsssuboptional4:</td>";
	//echo "		<td><input type='text' name='rsssuboptional4' value='$rsssuboptional4'></td>";
	//echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td>Rsssuboptional5:</td>";
	//echo "		<td><input type='text' name='rsssuboptional5' value='$rsssuboptional5'></td>";
	//echo "	</tr>";

	echo "	<tr>";
	echo "		<td colspan='2' align='right'>";
	echo "		    <input type='hidden' name='rssid' value='$rssid'>";
	echo "		    <input type='hidden' name='persistform' value='0'>";
	echo "          <input type='hidden' name='rsssubid' value='$rsssubid'>";
	echo "          <input type='submit' name='submit' class='btn' value='Update'>";
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
