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
		$dialplan_include_id = check_str($_REQUEST["id"]);
	}

//get the v_dialplan_includes data 
	$dialplan_include_id = $_GET["id"];
	$sql = "";
	$sql .= "select * from v_dialplan_includes ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and dialplan_include_id = '$dialplan_include_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$v_id = $row["v_id"];
		$extensionname = $row["extensionname"];
		$dialplanorder = $row["dialplanorder"];
		$extensioncontinue = $row["extensioncontinue"];
		$context = $row["context"];
		$enabled = $row["enabled"];
		$descr = "copy: ".$row["descr"];
		break; //limit to 1 row
	}
	unset ($prepstatement);

	//copy the dialplan
		$sql = "insert into v_dialplan_includes ";
		$sql .= "(";
		$sql .= "v_id, ";
		$sql .= "extensionname, ";
		$sql .= "dialplanorder, ";
		$sql .= "extensioncontinue, ";
		$sql .= "context, ";
		$sql .= "enabled, ";
		$sql .= "descr ";
		$sql .= ")";
		$sql .= "values ";
		$sql .= "(";
		$sql .= "'$v_id', ";
		$sql .= "'$extensionname', ";
		$sql .= "'$dialplanorder', ";
		$sql .= "'$extensioncontinue', ";
		$sql .= "'$context', ";
		$sql .= "'$enabled', ";
		$sql .= "'$descr' ";
		$sql .= ")";
		$db->exec(check_sql($sql));
		$db_dialplan_include_id = $db->lastInsertId($id);
		unset($sql);

	//get the the dialplan details
		$sql = "";
		$sql .= "select * from v_dialplan_includes_details ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and dialplan_include_id = '$dialplan_include_id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$v_id = $row["v_id"];
			//$dialplan_include_id = $row["dialplan_include_id"];
			$tag = $row["tag"];
			$fieldorder = $row["fieldorder"];
			$fieldtype = $row["fieldtype"];
			$fielddata = $row["fielddata"];

			//copy the dialplan details
				$sql = "insert into v_dialplan_includes_details ";
				$sql .= "(";
				$sql .= "v_id, ";
				$sql .= "dialplan_include_id, ";
				$sql .= "tag, ";
				$sql .= "fieldorder, ";
				$sql .= "fieldtype, ";
				$sql .= "fielddata ";
				$sql .= ")";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'$v_id', ";
				$sql .= "'$db_dialplan_include_id', ";
				$sql .= "'$tag', ";
				$sql .= "'$fieldorder', ";
				$sql .= "'$fieldtype', ";
				$sql .= "'$fielddata' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql);
		}
		unset ($prepstatement);

	//synchronize the xml config
		sync_package_v_dialplan_includes();

	//redirect the user
		require_once "includes/header.php";
		echo "<meta http-equiv=\"refresh\" content=\"2;url=v_dialplan_includes.php\">\n";
		echo "<div align='center'>\n";
		echo "Copy Complete\n";
		echo "</div>\n";
		require_once "includes/footer.php";
		return;

?>