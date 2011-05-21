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
if (permission_exists('script_editor_save')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}
require_once "config.php";

if (count($_POST)>0) {
    $id = check_str($_POST["id"]);
    $clipname = check_str($_POST["clipname"]);
    $clipfolder = check_str($_POST["clipfolder"]);
    $cliptextstart = check_str($_POST["cliptextstart"]);
    $cliptextend = check_str($_POST["cliptextend"]);
    $clipdesc = check_str($_POST["clipdesc"]);
    $cliporder = check_str($_POST["cliporder"]);

    //sql update
    $sql  = "update tblcliplibrary set ";
    $sql .= "clipname = '$clipname', ";
    $sql .= "clipfolder = '$clipfolder', ";
    $sql .= "cliptextstart = '$cliptextstart', ";
    $sql .= "cliptextend = '$cliptextend', ";
    $sql .= "clipdesc = '$clipdesc', ";
    $sql .= "cliporder = '$cliporder' ";
    $sql .= "where id = '$id' ";
    $count = $db->exec(check_sql($sql));
    //echo "Affected Rows: ".$count;


    //edit: make sure the meta redirect url is correct 
    require_once "header.php";
    echo "<meta http-equiv=\"refresh\" content=\"1;url=clipoptions.php\">\n";
    echo "Update Complete";
    require_once "footer.php";
    return;
}
else {
	//get data from the db
		$id = $_GET["id"];

		$sql = "";
		$sql .= "select * from tblcliplibrary ";
		$sql .= "where id = '$id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();

		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$clipname = $row["clipname"];
			$clipfolder = $row["clipfolder"];
			$cliptextstart = $row["cliptextstart"];
			$cliptextend = $row["cliptextend"];
			$clipdesc = $row["clipdesc"];
			$cliporder = $row["cliporder"];
			break; //limit to 1 row
		}
		echo "</table>";
		echo "<div>";}

	require_once "header.php";
	echo "<div align='left'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";

	echo "<form method='post' action=''>";
	echo "<table border='0' width='100%'>";
	echo "	<tr>";
	echo "		<td>Name:</td>";
	echo "		<td><input type='text' class='txt' name='clipname' value='$clipname'></td>";
	echo "	</tr>";

	echo "	<tr>";
	echo "		<td>Folder:</td>";
	echo "		<td><input type='text' class='txt'  name='clipfolder' value='$clipfolder'></td>";
	echo "	</tr>";

	echo "	<tr>";
	echo "		<td colspan='2'>Before Selection:<br>";
	echo "		  <textarea  class='txt' name='cliptextstart'>$cliptextstart</textarea>";
	echo "		</td>";
	echo "	</tr>";

	echo "	<tr>";
	echo "		<td colspan='2'>After Selection:<br>";
	echo "		  <textarea  class='txt' name='cliptextend'>$cliptextend</textarea>";
	echo "		</td>";
	echo "	</tr>";

	echo "	<tr>";
	echo "		<td colspan='2'>Notes:<br>";
	echo "		  <textarea  class='txt' name='clipdesc'>$clipdesc</textarea>";
	echo "		</td>";
	echo "	</tr>";

	echo "	<tr>";
	echo "		<td colspan='2' align='right'>";
	echo "     <input type='hidden' name='id' value='$id'>";
	echo "     <input type='submit' name='submit' value='Update'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";

	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

	require_once "footer.php";
?>