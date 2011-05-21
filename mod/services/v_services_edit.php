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
require_once "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
if (permission_exists('services_add') || permission_exists('services_edit')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//action add or update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$service_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//get http post and set it to php variables
	if (count($_POST)>0) {
		$v_service_name = check_str($_POST["v_service_name"]);
		$v_service_type = check_str($_POST["v_service_type"]);
		$v_service_data = check_str($_POST["v_service_data"]);
		$v_service_cmd_start = check_str($_POST["v_service_cmd_start"]);
		$v_service_cmd_stop = check_str($_POST["v_service_cmd_stop"]);
		$v_service_desc = check_str($_POST["v_service_desc"]);
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$service_id = check_str($_POST["service_id"]);
	}

	//check for all required data
		//if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		if (strlen($v_service_name) == 0) { $msg .= "Please provide: Name<br>\n"; }
		//if (strlen($v_service_type) == 0) { $msg .= "Please provide: Type<br>\n"; }
		//if (strlen($v_service_data) == 0) { $msg .= "Please provide: Data<br>\n"; }
		//if (strlen($v_service_cmd_start) == 0) { $msg .= "Please provide: Start Command<br>\n"; }
		//if (strlen($v_service_cmd_stop) == 0) { $msg .= "Please provide: Stop Command<br>\n"; }
		//if (strlen($v_service_desc) == 0) { $msg .= "Please provide: Description<br>\n"; }
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
			if ($action == "add" && permission_exists('services_add')) {
				$sql = "insert into v_services ";
				$sql .= "(";
				$sql .= "v_id, ";
				$sql .= "v_service_name, ";
				$sql .= "v_service_type, ";
				$sql .= "v_service_data, ";
				$sql .= "v_service_cmd_start, ";
				$sql .= "v_service_cmd_stop, ";
				$sql .= "v_service_desc ";
				$sql .= ")";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'$v_id', ";
				$sql .= "'$v_service_name', ";
				$sql .= "'$v_service_type', ";
				$sql .= "'$v_service_data', ";
				$sql .= "'$v_service_cmd_start', ";
				$sql .= "'$v_service_cmd_stop', ";
				$sql .= "'$v_service_desc' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql);

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_services.php\">\n";
				echo "<div align='center'>\n";
				echo "Add Complete\n";
				echo "</div>\n";
				require_once "includes/footer.php";
				return;
			} //if ($action == "add")

			if ($action == "update" && permission_exists('services_edit')) {
				$sql = "update v_services set ";
				$sql .= "v_service_name = '$v_service_name', ";
				$sql .= "v_service_type = '$v_service_type', ";
				$sql .= "v_service_data = '$v_service_data', ";
				$sql .= "v_service_cmd_start = '$v_service_cmd_start', ";
				$sql .= "v_service_cmd_stop = '$v_service_cmd_stop', ";
				$sql .= "v_service_desc = '$v_service_desc' ";
				$sql .= "where v_id = '$v_id'";
				$sql .= "and service_id = '$service_id'";
				$db->exec(check_sql($sql));
				unset($sql);

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_services.php\">\n";
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
		$service_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_services ";
		$sql .= "where service_id = '$service_id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$v_id = $row["v_id"];
			$v_service_name = $row["v_service_name"];
			$v_service_type = $row["v_service_type"];
			$v_service_data = $row["v_service_data"];
			$v_service_cmd_start = $row["v_service_cmd_start"];
			$v_service_cmd_stop = $row["v_service_cmd_stop"];
			$v_service_desc = $row["v_service_desc"];
			break; //limit to 1 row
		}
		unset ($prepstatement);
	}

//show the header
	require_once "includes/header.php";

//begin the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing=''>\n";
	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "	  <br>";

	echo "<form method='post' name='frm' action=''>\n";
	echo "<div align='center'>\n";
	echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";
	echo "<tr>\n";
	if ($action == "add") {
		echo "<td align='left' width='30%' nowrap><b>Service Add</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align='left' width='30%' nowrap><b>Service Edit</b></td>\n";
	}
	echo "<td width='70%' align='right'><input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_services.php'\" value='Back'></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align='left' colspan='2'>\n";
	echo "Shows a list of processes and provides ability to start and stop them.<br /><br />\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_service_name' maxlength='255' value=\"$v_service_name\">\n";
	echo "<br />\n";
	echo "Enter the service name.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Type:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<select class='formfld' name='v_service_type'>\n";
	echo "	<option value=''></option>\n";
	if ($v_service_type == "pid_file") { 
		echo "	<option value='pid_file' SELECTED >pid file</option>\n";
	}
	else {
		echo "	<option value='pid_file'>pid file</option>\n";
	}
	if ($v_service_type == "php") { 
		echo "	<option value='php' SELECTED >php</option>\n";
	}
	else {
		echo "	<option value='php'>php</option>\n";
	}
	echo "	</select>\n";
	echo "<br />\n";
	echo "Select the service type.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Data:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_service_data' maxlength='255' value=\"$v_service_data\">\n";
	//echo "	<textarea class='formfld' name='v_service_data' rows='4'>$v_service_data</textarea>\n";
	echo "<br />\n";
	echo "Enter the service data.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Start Command:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_service_cmd_start' maxlength='255' value=\"$v_service_cmd_start\">\n";
	echo "<br />\n";
	echo "Enter the command to  start the service.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Stop Command:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_service_cmd_stop' maxlength='255' value=\"$v_service_cmd_stop\">\n";
	echo "<br />\n";
	echo "Enter the command to  stop the service.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Description:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<textarea class='formfld' name='v_service_desc' rows='4'>$v_service_desc</textarea>\n";
	echo "<br />\n";
	echo "Enter the service description.\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	if ($action == "update") {
		echo "				<input type='hidden' name='service_id' value='$service_id'>\n";
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
