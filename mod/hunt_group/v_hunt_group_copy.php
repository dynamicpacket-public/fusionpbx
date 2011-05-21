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

//check permissions
	if (permission_exists('hunt_group_add')) {
		//access granted
	}
	else {
		echo "access denied";
		exit;
	}

//set the http get/post variable(s) to a php variable
	if (isset($_REQUEST["id"])) {
		$hunt_group_id = check_str($_REQUEST["id"]);
	}

//get the v_dialplan_includes data 
	$sql = "";
	$sql .= "select * from v_hunt_group ";
	$sql .= "where hunt_group_id = '$hunt_group_id' ";
	$sql .= "and v_id = '$v_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$huntgroupextension = $row["huntgroupextension"];
		$huntgroupname = $row["huntgroupname"];
		$huntgrouptype = $row["huntgrouptype"];
		$huntgroupcontext = $row["huntgroupcontext"];
		$huntgrouptimeout = $row["huntgrouptimeout"];
		$huntgrouptimeoutdestination = $row["huntgrouptimeoutdestination"];
		$huntgrouptimeouttype = $row["huntgrouptimeouttype"];
		$huntgroupringback = $row["huntgroupringback"];
		$huntgroupcidnameprefix = $row["huntgroupcidnameprefix"];
		$huntgrouppin = $row["huntgrouppin"];
		$huntgroupcallerannounce = $row["huntgroupcallerannounce"];
		$huntgroupdescr = "copy: ".$row["huntgroupdescr"];
		break; //limit to 1 row
	}
	unset ($prepstatement);

	//copy the hunt group
		$sql = "insert into v_hunt_group ";
		$sql .= "(";
		$sql .= "v_id, ";
		$sql .= "huntgroupextension, ";
		$sql .= "huntgroupname, ";
		$sql .= "huntgrouptype, ";
		$sql .= "huntgroupcontext, ";
		$sql .= "huntgrouptimeout, ";
		$sql .= "huntgrouptimeoutdestination, ";
		$sql .= "huntgrouptimeouttype, ";
		$sql .= "huntgroupringback, ";
		$sql .= "huntgroupcidnameprefix, ";
		$sql .= "huntgrouppin, ";
		$sql .= "huntgroupcallerannounce, ";
		$sql .= "huntgroupdescr ";
		$sql .= ")";
		$sql .= "values ";
		$sql .= "(";
		$sql .= "'$v_id', ";
		$sql .= "'$huntgroupextension', ";
		$sql .= "'$huntgroupname', ";
		$sql .= "'$huntgrouptype', ";
		$sql .= "'$huntgroupcontext', ";
		$sql .= "'$huntgrouptimeout', ";
		$sql .= "'$huntgrouptimeoutdestination', ";
		$sql .= "'$huntgrouptimeouttype', ";
		$sql .= "'$huntgroupringback', ";
		$sql .= "'$huntgroupcidnameprefix', ";
		$sql .= "'$huntgrouppin', ";
		$sql .= "'$huntgroupcallerannounce', ";
		$sql .= "'$huntgroupdescr' ";
		$sql .= ")";
		$db->exec(check_sql($sql));
		$db_hunt_group_id = $db->lastInsertId($id);
		unset($sql);

	//get the the dialplan details
		$sql = "";
		$sql .= "select * from v_hunt_group_destinations ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and hunt_group_id = '$hunt_group_id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			//$v_id = $row["v_id"];
			$hunt_group_id = $row["hunt_group_id"];
			$destinationdata = $row["destinationdata"];
			$destinationtype = $row["destinationtype"];
			$destinationprofile = $row["destinationprofile"];
			$destinationorder = $row["destinationorder"];
			$destinationdescr = $row["destinationdescr"];

			//copy the hunt group destinations
				$sql = "insert into v_hunt_group_destinations ";
				$sql .= "(";
				$sql .= "v_id, ";
				$sql .= "hunt_group_id, ";
				$sql .= "destinationdata, ";
				$sql .= "destinationtype, ";
				$sql .= "destinationprofile, ";
				$sql .= "destinationorder, ";
				$sql .= "destinationdescr ";
				$sql .= ")";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'$v_id', ";
				$sql .= "'$db_hunt_group_id', ";
				$sql .= "'$destinationdata', ";
				$sql .= "'$destinationtype', ";
				$sql .= "'$destinationprofile', ";
				$sql .= "'$destinationorder', ";
				$sql .= "'$destinationdescr' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql);
		}
		unset ($prepstatement);

	//synchronize the xml config
		sync_package_v_hunt_group();

	//redirect the user
		require_once "includes/header.php";
		echo "<meta http-equiv=\"refresh\" content=\"2;url=v_hunt_group.php\">\n";
		echo "<div align='center'>\n";
		echo "Copy Complete\n";
		echo "</div>\n";
		require_once "includes/footer.php";
		return;

?>