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
require_once "includes/paging.php";
if (ifgroup("superadmin")) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//set the http get/post variable(s) to a php variable
	if (isset($_REQUEST["id"])) {
		$public_include_id = check_str($_REQUEST["id"]);
	}

//get the v_dialplan_includes data 
	$sql = "";
	$sql .= "select * from v_public_includes ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and public_include_id = '$public_include_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		//$v_id = $row["v_id"];
		$extensionname = $row["extensionname"];
		$publicorder = $row["publicorder"];
		$extensioncontinue = $row["extensioncontinue"];
		$context = $row["context"];
		$enabled = $row["enabled"];
		$descr = 'copy: '.$row["descr"];
		break; //limit to 1 row
	}
	unset ($prepstatement);

//copy the public
	$sql = "insert into v_public_includes ";
	$sql .= "(";
	$sql .= "v_id, ";
	$sql .= "extensionname, ";
	$sql .= "publicorder, ";
	$sql .= "extensioncontinue, ";
	$sql .= "context, ";
	$sql .= "enabled, ";
	$sql .= "descr ";
	$sql .= ")";
	$sql .= "values ";
	$sql .= "(";
	$sql .= "'$v_id', ";
	$sql .= "'$extensionname', ";
	$sql .= "'$publicorder', ";
	$sql .= "'$extensioncontinue', ";
	$sql .= "'default', ";
	$sql .= "'$enabled', ";
	$sql .= "'$descr' ";
	$sql .= ")";
	$db->exec(check_sql($sql));
	$db_public_include_id = $db->lastInsertId($id);
	unset($sql);

//get the the public details
	$sql = "";
	$sql .= "select * from v_public_includes_details ";
	$sql .= "where public_include_id = '$public_include_id' ";
	$sql .= "and v_id = '$v_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$v_id = $row["v_id"];
		$public_include_id = $row["public_include_id"];
		$tag = $row["tag"];
		$fieldtype = $row["fieldtype"];
		$fielddata = $row["fielddata"];
		$fieldorder = $row["fieldorder"];

		//copy the public details
			$sql = "insert into v_public_includes_details ";
			$sql .= "(";
			$sql .= "v_id, ";
			$sql .= "public_include_id, ";
			$sql .= "tag, ";
			$sql .= "fieldtype, ";
			$sql .= "fielddata, ";
			$sql .= "fieldorder ";
			$sql .= ")";
			$sql .= "values ";
			$sql .= "(";
			$sql .= "'$v_id', ";
			$sql .= "'$db_public_include_id', ";
			$sql .= "'$tag', ";
			$sql .= "'$fieldtype', ";
			$sql .= "'$fielddata', ";
			$sql .= "'$fieldorder' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
	}
	unset ($prepstatement);

//synchronize the xml config
	sync_package_v_dialplan_includes();

//redirect the user
	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"2;url=v_public_includes.php\">\n";
	echo "<div align='center'>\n";
	echo "Copy Complete\n";
	echo "</div>\n";
	require_once "includes/footer.php";
	return;

?>