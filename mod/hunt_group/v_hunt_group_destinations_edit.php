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

//check permissions
	if (permission_exists('hunt_group_add') || permission_exists('hunt_group_edit')) {
		//access granted
	}
	else {
		echo "access denied";
		exit;
	}

//set the action as an add or an update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$hunt_group_destination_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

	if (isset($_REQUEST["id2"])) {
		$hunt_group_id = check_str($_REQUEST["id2"]);
	}

//get the http values and set them as variables
	if (count($_POST)>0) {
		if (isset($_POST["hunt_group_id"])) {
			$hunt_group_id = check_str($_POST["hunt_group_id"]);
		}
		$destinationdata = check_str($_POST["destinationdata"]);
		$destinationtype = check_str($_POST["destinationtype"]);
		$destination_timeout = check_str($_POST["destination_timeout"]);
		$destinationorder = check_str($_POST["destinationorder"]);
		$destination_enabled = check_str($_POST["destination_enabled"]);
		$destinationdescr = check_str($_POST["destinationdescr"]);
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$hunt_group_destination_id = check_str($_POST["hunt_group_destination_id"]);
	}

	//check for all required data
		if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		if (strlen($destinationdata) == 0) { $msg .= "Please provide: Destination<br>\n"; }
		if (strlen($destinationtype) == 0) { $msg .= "Please provide: Type<br>\n"; }
		//if (strlen($destination_timeout) == 0) { $msg .= "Please provide: Timeout<br>\n"; }
		//if (strlen($destinationorder) == 0) { $msg .= "Please provide: Order<br>\n"; }
		//if (strlen($destination_enabled) == 0) { $msg .= "Please provide: Enabled<br>\n"; }
		//if (strlen($destinationdescr) == 0) { $msg .= "Please provide: Description<br>\n"; }
		if (strlen($msg) > 0 && strlen($_POST["persistformvar"]) == 0) {
			require_once "includes/header.php";
			require_once "includes/persistformvar.php";
			echo "<div align='center'>\n";
			echo "<table><tr><td>\n";
			echo $msg."<br />";
			echo "</td></tr></table>\n";
			persistformvar($_POST);
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
		}

	//add or update the database
		if ($_POST["persistformvar"] != "true") {
			if ($action == "add" && permission_exists('hunt_group_add')) {
				$sql = "insert into v_hunt_group_destinations ";
				$sql .= "(";
				$sql .= "v_id, ";
				$sql .= "hunt_group_id, ";
				$sql .= "destinationdata, ";
				$sql .= "destinationtype, ";
				$sql .= "destination_timeout, ";
				$sql .= "destinationorder, ";
				$sql .= "destination_enabled, ";
				$sql .= "destinationdescr ";
				$sql .= ")";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'$v_id', ";
				$sql .= "'$hunt_group_id', ";
				$sql .= "'$destinationdata', ";
				$sql .= "'$destinationtype', ";
				$sql .= "'$destination_timeout', ";
				$sql .= "'$destinationorder', ";
				$sql .= "'$destination_enabled', ";
				$sql .= "'$destinationdescr' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql);

				//synchronize the xml config
				sync_package_v_hunt_group();

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_hunt_group_edit.php?id=".$hunt_group_id."\">\n";
				echo "<div align='center'>\n";
				echo "Add Complete\n";
				echo "</div>\n";
				require_once "includes/footer.php";
				return;
			} //if ($action == "add")

			if ($action == "update" && permission_exists('hunt_group_edit')) {
				$sql = "update v_hunt_group_destinations set ";
				$sql .= "v_id = '$v_id', ";
				$sql .= "hunt_group_id = '$hunt_group_id', ";
				$sql .= "destinationdata = '$destinationdata', ";
				$sql .= "destinationtype = '$destinationtype', ";
				$sql .= "destination_timeout = '$destination_timeout', ";
				$sql .= "destinationorder = '$destinationorder', ";
				$sql .= "destination_enabled = '$destination_enabled', ";
				$sql .= "destinationdescr = '$destinationdescr' ";
				$sql .= "where v_id = '$v_id' ";
				$sql .= "and hunt_group_destination_id = '$hunt_group_destination_id'";
				$db->exec(check_sql($sql));

				//synchronize the xml config
				sync_package_v_hunt_group();

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_hunt_group_edit.php?id=".$hunt_group_id."\">\n";
				echo "<div align='center'>\n";
				echo "Update Complete\n";
				echo "</div>\n";
				require_once "includes/footer.php";
				return;
			} //if ($action == "update")
		} //if ($_POST["persistformvar"] != "true")
} //(count($_POST)>0 && strlen($_POST["persistformvar"]) == 0)

//pre-populate the form
	if (count($_GET)>0 && $_POST["persistformvar"] != "true") {
		$hunt_group_destination_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_hunt_group_destinations ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and hunt_group_destination_id = '$hunt_group_destination_id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$hunt_group_id = $row["hunt_group_id"];
			$destinationdata = $row["destinationdata"];
			$destinationtype = $row["destinationtype"];
			$destination_timeout = $row["destination_timeout"];
			$destinationorder = $row["destinationorder"];
			$destination_enabled = $row["destination_enabled"];
			$destinationdescr = $row["destinationdescr"];
			break; //limit to 1 row
		}
		unset ($prepstatement);
	}

//show the header
	require_once "includes/header.php";

//show the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "      <br>";

	echo "<form method='post' name='frm' action=''>\n";

	echo "<div align='center'>\n";
	echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";

	echo "<tr>\n";
	if ($action == "add") {
		echo "<td align='left' width='30%' nowrap><b>Destination Add</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align='left' width='30%' nowrap><b>Destination Edit</b></td>\n";
	}
	echo "<td width='70%' align='right'><input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_hunt_group_edit.php?id=".$hunt_group_id."'\" value='Back'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Destination:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='destinationdata' maxlength='255' value=\"$destinationdata\">\n";
	echo "<br />\n";
	echo "extension: 1001<br />\n";
	echo "voicemail: 1001<br />\n";
	echo "sip uri (voicemail): sofia/internal/*98@\${domain}<br />\n";
	echo "sip uri (external number): sofia/gateway/gatewayname/12081231234<br />\n";
	echo "sip uri (auto attendant): sofia/internal/5002@\${domain}<br />\n";
	echo "sip uri (user): /user/1001@\${domain}\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Type:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "                <select name='destinationtype' class='formfld'>\n";
	echo "                <option></option>\n";
	if ($destinationtype == "extension") {
		echo "                <option selected='yes'>extension</option>\n";
	}
	else {
		echo "                <option>extension</option>\n";
	}
	if ($destinationtype == "voicemail") {
		echo "                <option selected='yes'>voicemail</option>\n";
	}
	else {
		echo "                <option>voicemail</option>\n";
	}
	if ($destinationtype == "sip uri") {
		echo "                <option selected='yes'>sip uri</option>\n";
	}
	else {
		echo "                <option>sip uri</option>\n";
	}
	echo "                </select>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Timeout:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "              <select name='destination_timeout' class='formfld'>\n";
	echo "              <option></option>\n";
	if (strlen($destination_timeout)> 0) {
		echo "              <option selected='yes' value='".htmlspecialchars($destination_timeout)."'>".htmlspecialchars($destination_timeout)."</option>\n";
	}
	$i=0;
	while($i<=301) {
		echo "              <option value='$i'>$i</option>\n";
		$i++;
	}
	echo "              </select>\n";
	echo "<br />\n";
	echo "Select the destination timeout in seconds. \n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Order:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "              <select name='destinationorder' class='formfld'>\n";
	//echo "              <option></option>\n";
	if (strlen($destinationorder)> 0) {
		echo "              <option selected='yes' value='".htmlspecialchars($destinationorder)."'>".htmlspecialchars($destinationorder)."</option>\n";
	}
	$i=0;
	while($i<=301) {
		if (strlen($i) == 1) {
			echo "              <option value='00$i'>00$i</option>\n";
		}
		if (strlen($i) == 2) {
			echo "              <option value='0$i'>0$i</option>\n";
		}
		if (strlen($i) == 3) {
			echo "              <option value='$i'>$i</option>\n";
		}
		$i++;
	}
	echo "              </select>\n";
	echo "<br />\n";
	echo "Processing of each destination is determined by this order. \n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Enabled:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='destination_enabled'>\n";
	echo "    <option value=''></option>\n";
	if ($destination_enabled == "true" || strlen($destination_enabled) == 0) { 
		echo "    <option value='true' selected >true</option>\n";
	}
	else {
		echo "    <option value='true'>true</option>\n";
	}
	if ($destination_enabled == "false") { 
		echo "    <option value='false' selected >false</option>\n";
	}
	else {
		echo "    <option value='false'>false</option>\n";
	}
	echo "    </select>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Description:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='destinationdescr' maxlength='255' value=\"$destinationdescr\">\n";
	echo "<br />\n";
	echo "You may enter a description here for your reference (not parsed).\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	echo "				<input type='hidden' name='hunt_group_id' value='$hunt_group_id'>\n";
	if ($action == "update") {
		echo "				<input type='hidden' name='hunt_group_destination_id' value='$hunt_group_destination_id'>\n";
	}
	echo "				<input type='submit' name='submit' class='btn' value='Save'>\n";
	echo "		</td>\n";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";

	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

//show the footer
	require_once "includes/footer.php";
?>
