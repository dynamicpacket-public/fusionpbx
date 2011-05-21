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
	$clipname = check_str($_POST["clipname"]);
	$clipfolder = check_str($_POST["clipfolder"]);
	$cliptextstart = check_str($_POST["cliptextstart"]);
	$cliptextend = check_str($_POST["cliptextend"]);
	$clipdesc = check_str($_POST["clipdesc"]);
	$cliporder = check_str($_POST["cliporder"]);

	$sql = "insert into tblcliplibrary ";
	$sql .= "(";
	$sql .= "clipname, ";
	$sql .= "clipfolder, ";
	$sql .= "cliptextstart, ";
	$sql .= "cliptextend, ";
	$sql .= "clipdesc, ";
	$sql .= "cliporder ";
	$sql .= ")";
	$sql .= "values ";
	$sql .= "(";
	$sql .= "'$clipname', ";
	$sql .= "'$clipfolder', ";
	$sql .= "'$cliptextstart', ";
	$sql .= "'$cliptextend', ";
	$sql .= "'$clipdesc', ";
	$sql .= "'$cliporder' ";
	$sql .= ")";
	$db->exec(check_sql($sql));
	unset($sql,$db);

	require_once "header.php";
	echo "<meta http-equiv=\"refresh\" content=\"1;url=clipoptions.php\">\n";
	echo "Add Complete";
	require_once "footer.php";
	return;
}

	require_once "header.php";
	echo "<div align='left'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";

	echo "<form method='post' action=''>";
	echo "<table width='100%' border='0'>";
	  echo "	<tr>";
	  echo "		<td>Name:</td>";
	  echo "		<td><input type='text' class='txt' name='clipname'></td>";
	  echo "	</tr>";
	  
	  echo "	<tr>";
	  echo "		<td>Folder:</td>";
	  echo "		<td><input type='text' class='txt' name='clipfolder'></td>";
	  echo "	</tr>";
	  
	  echo "	<tr>";
	  echo "		<td colspan='2'>Before Selection:<br>";
	  echo "		  <textarea name='cliptextstart' class='txt'></textarea>";
	  echo "		</td>";
	  echo "	</tr>";
	  
	  echo "	<tr>";
	  echo "		<td colspan='2'>After Selection:<br>";
	  echo "		  <textarea name='cliptextend' class='txt'></textarea>";
	  echo "		</td>";
	  echo "	</tr>";
	  
	  echo "	<tr>";
	  echo "		<td colspan='2'>Notes:<br>";
	  echo "		  <textarea name='clipdesc' class='txt'></textarea>";
	  echo "		</td>";
	  echo "	</tr>";

	echo "		<td colspan='2' align='right'><input type='submit' name='submit' value='Add'></td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";

	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

require_once "footer.php";
?>