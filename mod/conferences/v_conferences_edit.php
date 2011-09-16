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
if (permission_exists('conferences_add') || permission_exists('conferences_edit')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}
require_once "includes/paging.php";

$orderby = $_GET["orderby"];
$order = $_GET["order"];

//action add or update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$dialplan_include_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//check if the user has been assigned this conference room
	if (permission_exists('conferences_add') && permission_exists('conferences_edit')) {
		//allow users that have been assigned conferences_add or conferences_edit to all conference rooms
	}
	else {
		//get the list of conference numbers the user is assigned to
			$sql = "select * from v_dialplan_includes_details ";
			$sql .= "where v_id = '$v_id' ";
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$x = 0;
			$result = $prepstatement->fetchAll();
			foreach ($result as &$row) {
				$tmp_dialplan_include_id = $row["dialplan_include_id"];
				$fieldtype = $row["fieldtype"];
				if ($fieldtype == "conference") {
					$conference_array[$x]['dialplan_include_id'] = $tmp_dialplan_include_id;
					$x++;
				}
			}
			unset ($prepstatement);

		//get the list of assigned conference numbers for this user
			foreach ($conference_array as &$row) {
				$sql = "select * from v_dialplan_includes_details ";
				$sql .= "where v_id = '$v_id' ";
				$sql .= "and dialplan_include_id = '".$row['dialplan_include_id']."' ";
				$sql .= "and fielddata like 'conference_user_list%' and fielddata like '%|".$_SESSION['username']."|%' ";
				$tmp_row = $db->query($sql)->fetch();
				if (strlen($tmp_row['dialplan_include_id']) > 0) {
					$conference_auth_array[$tmp_row['dialplan_include_id']] = $tmp_row['dialplan_include_id'];
				}
			}

		//check if the user has been assigned to this conference room
			if (strlen($conference_auth_array[$dialplan_include_id]) == 0) {
				echo "access denied";
				exit;
			}
	}

//show the header
	require_once "includes/header.php";

//http post to php variables
	if (count($_POST)>0) {
		$extension_name = check_str($_POST["extension_name"]);
		$extension_number = check_str($_POST["extension_number"]);
		$dialplan_order = check_str($_POST["dialplan_order"]);
		$pin_number = check_str($_POST["pin_number"]);

		//prepare the user list for the database
		$user_list = $_POST["user_list"];
		if (strlen($user_list) > 0) {
			$user_list_array = explode("\n", $user_list);
			if (count($user_list_array) == 0) {
				$user_list = '';
			}
			else {
				$user_list = '|';
				foreach($user_list_array as $user){
					if(strlen(trim($user)) > 0) {
						$user_list .= check_str(trim($user))."|";
					}
				}
			}
		}

		$profile = check_str($_POST["profile"]);
		$flags = check_str($_POST["flags"]);
		$enabled = check_str($_POST["enabled"]);
		$description = check_str($_POST["description"]);
		if (strlen($enabled) == 0) { $enabled = "true"; } //set default to enabled
	}

//process the http post
	if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {
		//check for all required data
			if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
			if (strlen($extension_name) == 0) { $msg .= "Please provide: Conference Name<br>\n"; }
			if (strlen($extension_number) == 0) { $msg .= "Please provide: Extension Number<br>\n"; }
			//if (strlen($pin_number) == 0) { $msg .= "Please provide: PIN Number<br>\n"; }
			if (strlen($profile) == 0) { $msg .= "Please provide: profile<br>\n"; }
			//if (strlen($flags) == 0) { $msg .= "Please provide: Flags<br>\n"; }
			if (strlen($enabled) == 0) { $msg .= "Please provide: Enabled True or False<br>\n"; }
			//if (strlen($description) == 0) { $msg .= "Please provide: Description<br>\n"; }
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

		//start the atomic transaction
			$count = $db->exec("BEGIN;"); //returns affected rows

		//prepare the fieldata so that it combines the conference name, profile, pin number and flags
			if (strlen($action) > 0) {
				$tmp_pin_number = ''; if (strlen($pin_number) > 0) { $tmp_pin_number = "+".$pin_number; }
				$tmp_flags = ''; if (strlen($flags) > 0) { $tmp_flags = "+flags{".$flags."}"; }
				$tmp_fielddata = $extension_name.'-'.$v_domain."@".$profile.$tmp_pin_number.$tmp_flags;
			}

		if ($action == "add" && permission_exists('conferences_add')) {

			//add the main dialplan include entry
				$sql = "insert into v_dialplan_includes ";
				$sql .= "(";
				$sql .= "v_id, ";
				$sql .= "extensionname, ";
				$sql .= "dialplanorder, ";
				$sql .= "extensioncontinue, ";
				$sql .= "context, ";
				$sql .= "enabled, ";
				$sql .= "descr ";
				$sql .= ") ";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'$v_id', ";
				$sql .= "'$extension_name', ";
				$sql .= "'$dialplan_order', ";
				$sql .= "'false', ";
				$sql .= "'default', ";
				$sql .= "'$enabled', ";
				$sql .= "'$description' ";
				$sql .= ")";
				if ($db_type == "sqlite" || $db_type == "mysql" ) {
					$db->exec(check_sql($sql));
					$dialplan_include_id = $db->lastInsertId($id);
				}
				if ($db_type == "pgsql") {
					$sql .= " RETURNING dialplan_include_id ";
					$prepstatement = $db->prepare(check_sql($sql));
					$prepstatement->execute();
					$result = $prepstatement->fetchAll();
					foreach ($result as &$row) {
						$dialplan_include_id = $row["dialplan_include_id"];
					}
					unset($prepstatement, $result);
				}
				unset($sql);

			if (strlen($dialplan_include_id) > 0) {
				//add condition for the extension number
					$sql = "insert into v_dialplan_includes_details ";
					$sql .= "(";
					$sql .= "v_id, ";
					$sql .= "dialplan_include_id, ";
					$sql .= "tag, ";
					$sql .= "fieldtype, ";
					$sql .= "fielddata, ";
					$sql .= "fieldorder ";
					$sql .= ") ";
					$sql .= "values ";
					$sql .= "(";
					$sql .= "'$v_id', ";
					$sql .= "'$dialplan_include_id', ";
					$sql .= "'condition', ";
					$sql .= "'destination_number', ";
					$sql .= "'^".$extension_number."$', ";
					$sql .= "'1' ";
					$sql .= ")";
					$db->exec(check_sql($sql));
					unset($sql);

				//add action answer
					$sql = "insert into v_dialplan_includes_details ";
					$sql .= "(";
					$sql .= "v_id, ";
					$sql .= "dialplan_include_id, ";
					$sql .= "tag, ";
					$sql .= "fieldtype, ";
					$sql .= "fielddata, ";
					$sql .= "fieldorder ";
					$sql .= ") ";
					$sql .= "values ";
					$sql .= "(";
					$sql .= "'$v_id', ";
					$sql .= "'$dialplan_include_id', ";
					$sql .= "'action', ";
					$sql .= "'answer', ";
					$sql .= "'', ";
					$sql .= "'2' ";
					$sql .= ")";
					$db->exec(check_sql($sql));
					unset($sql);

				//add action set
					$sql = "insert into v_dialplan_includes_details ";
					$sql .= "(";
					$sql .= "v_id, ";
					$sql .= "dialplan_include_id, ";
					$sql .= "tag, ";
					$sql .= "fieldtype, ";
					$sql .= "fielddata, ";
					$sql .= "fieldorder ";
					$sql .= ") ";
					$sql .= "values ";
					$sql .= "(";
					$sql .= "'$v_id', ";
					$sql .= "'$dialplan_include_id', ";
					$sql .= "'action', ";
					$sql .= "'set', ";
					$sql .= "'conference_user_list=$user_list', ";
					$sql .= "'3' ";
					$sql .= ")";
					$db->exec(check_sql($sql));
					unset($sql);

				//add action conference
					$sql = "insert into v_dialplan_includes_details ";
					$sql .= "(";
					$sql .= "v_id, ";
					$sql .= "dialplan_include_id, ";
					$sql .= "tag, ";
					$sql .= "fieldtype, ";
					$sql .= "fielddata, ";
					$sql .= "fieldorder ";
					$sql .= ") ";
					$sql .= "values ";
					$sql .= "(";
					$sql .= "'$v_id', ";
					$sql .= "'$dialplan_include_id', ";
					$sql .= "'action', ";
					$sql .= "'conference', ";
					$sql .= "'".$tmp_fielddata."', ";
					$sql .= "'4' ";
					$sql .= ")";
					$db->exec(check_sql($sql));
					unset($sql);
					unset($fielddata);
			} //end if (strlen($dialplan_include_id) > 0)
		} //if ($action == "add")

		//update the data
			if ($action == "update" && permission_exists('conferences_edit')) {
				$sql = "update v_dialplan_includes set ";
				$sql .= "extensionname = '$extension_name', ";
				$sql .= "dialplanorder = '$dialplan_order', ";
				//$sql .= "extensioncontinue = '$extensioncontinue', ";
				$sql .= "context = '$context', ";
				$sql .= "enabled = '$enabled', ";
				$sql .= "descr = '$description' ";
				$sql .= "where v_id = '$v_id' ";
				$sql .= "and dialplan_include_id = '$dialplan_include_id'";
				$db->exec(check_sql($sql));
				unset($sql);

				$sql = "";
				$sql .= "select * from v_dialplan_includes_details ";
				$sql .= "where v_id = '$v_id' ";
				$sql .= "and dialplan_include_id = '$dialplan_include_id' ";
				$prepstatement = $db->prepare(check_sql($sql));
				$prepstatement->execute();
				$result = $prepstatement->fetchAll();
				unset($prepstatement);
				foreach ($result as $row) {
					if ($row['fieldtype'] == "destination_number") {
						$sql = "update v_dialplan_includes_details set ";
						//$sql .= "tag = '$tag', ";
						//$sql .= "fieldtype = '$fieldtype', ";
						$sql .= "fielddata = '^".$extension_number."$', ";
						$sql .= "fieldorder = '".$row['fieldorder']."' ";
						$sql .= "where v_id = '$v_id' ";
						$sql .= "and dialplan_include_id = '$dialplan_include_id' ";
						$sql .= "and dialplan_includes_detail_id = '".$row['dialplan_includes_detail_id']."' ";
						//echo $sql."<br />\n";
						$db->exec(check_sql($sql));
						unset($sql);
					}
					if (permission_exists('conferences_add') && permission_exists('conferences_edit')) {
						$fielddata_array = explode("=", $row['fielddata']);
						if ($fielddata_array[0] == "conference_user_list") {
							$sql = "update v_dialplan_includes_details set ";
							//$sql .= "tag = '$tag', ";
							//$sql .= "fieldtype = '$fieldtype', ";
							$sql .= "fielddata = 'conference_user_list=".$user_list."', ";
							$sql .= "fieldorder = '".$row['fieldorder']."' ";
							$sql .= "where v_id = '$v_id' ";
							$sql .= "and dialplan_include_id = '$dialplan_include_id' ";
							$sql .= "and dialplan_includes_detail_id = '".$row['dialplan_includes_detail_id']."' ";
							//echo $sql."<br />\n";
							$db->exec(check_sql($sql));
							unset($sql);
						}
					}
					if ($row['fieldtype'] == "conference") {
						$sql = "update v_dialplan_includes_details set ";
						//$sql .= "tag = '$tag', ";
						//$sql .= "fieldtype = '$fieldtype', ";
						$sql .= "fielddata = '".$tmp_fielddata."', ";
						$sql .= "fieldorder = '".$row['fieldorder']."' ";
						$sql .= "where v_id = '$v_id' ";
						$sql .= "and dialplan_include_id = '$dialplan_include_id' ";
						$sql .= "and dialplan_includes_detail_id = '".$row['dialplan_includes_detail_id']."' ";
						$db->exec(check_sql($sql));
						//echo $sql."<br />\n";
						unset($sql);
						unset($fielddata);
					}
				}

			} //if ($action == "update")

		//commit the atomic transaction
			$count = $db->exec("COMMIT;"); //returns affected rows

		//synchronize the xml config
			sync_package_v_dialplan_includes();

		require_once "includes/header.php";
		echo "<meta http-equiv=\"refresh\" content=\"2;url=v_conferences.php\">\n";
		echo "<div align='center'>\n";
		if ($action == "add") {
			echo "Add Complete\n";
		}
		if ($action == "update") {
			echo "Update Complete\n";
		}
		echo "</div>\n";
		require_once "includes/footer.php";
		return;

	} //end if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0)


//pre-populate the form
	if (count($_GET)>0 && $_POST["persistformvar"] != "true") {

		$sql = "";
		$sql .= "select * from v_dialplan_includes ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and dialplan_include_id = '$dialplan_include_id' ";
		$row = $db->query($sql)->fetch();
		$extension_name = $row['extensionname'];
		$context = $row['context'];
		$dialplan_order = $row['dialplanorder'];
		$enabled = $row['enabled'];
		$description = $row['descr'];

		$sql = "";
		$sql .= "select * from v_dialplan_includes_details ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and dialplan_include_id = '$dialplan_include_id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			if ($row['fieldtype'] == "destination_number") {
				$extension_number = $row['fielddata'];
				$extension_number = trim($extension_number, '^$');
			}
			$fielddata_array = explode("=", $row['fielddata']);
			if ($fielddata_array[0] == "conference_user_list") {
				$user_list = $fielddata_array[1];
			}
			if ($row['fieldtype'] == "conference") {
				$fielddata = $row['fielddata'];
				$tmp_pos = stripos($fielddata, "@");
				if ($tmp_pos !== false) {
					$tmp_fielddata = substr($fielddata, $tmp_pos+1, strlen($fielddata));
					$tmp_fielddata_array = explode("+",$tmp_fielddata);
					foreach ($tmp_fielddata_array as &$tmp_row) {
						if (is_numeric($tmp_row)) {
							$pin_number = $tmp_row;
						}
						if (substr($tmp_row, 0, 5) == "flags") {
							$flags = substr($tmp_row, 6, $tmp_row-1);
						}
					}
					$profile = $tmp_fielddata_array[0];
				}
			}
		}
	}

//show the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";
	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "		<br>";

	echo "<form method='post' name='frm' action=''>\n";
	echo "<div align='center'>\n";

	echo " 	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "	<tr>\n";
	echo "		<td align='left'><span class=\"vexpl\"><span class=\"red\">\n";
	echo "			<strong>Conferences</strong>\n";
	echo "			</span></span>\n";
	echo "		</td>\n";
	echo "		<td align='right'>\n";
	if (permission_exists('conferences_advanced_view') && $action == "update") {
		echo "			<input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_conferences_edit_advanced.php?id=$dialplan_include_id'\" value='Advanced'>\n";
	}
	echo "			<input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_conferences.php'\" value='Back'>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align='left' colspan='2'>\n";
	echo "			<span class=\"vexpl\">\n";
	echo "			Conferences are used to setup conference rooms with a name, description, and an optional pin number.\n";
	echo "			</span>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	</table>";

	echo "<br />\n";
	echo "<br />\n";

	echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";
	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Conference Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' style='width: 60%;' type='text' name='extension_name' maxlength='255' value=\"$extension_name\">\n";
	echo "<br />\n";
	echo "The name the conference will be assigned.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Extension Number:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' style='width: 60%;' type='text' name='extension_number' maxlength='255' value=\"$extension_number\">\n";
	echo "<br />\n";
	echo "The number that will be assinged to the conference.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    PIN Number:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' style='width: 60%;' type='text' name='pin_number' maxlength='255' value=\"$pin_number\">\n";
	echo "<br />\n";
	echo "Optional PIN number to secure access to the conference.\n";
	echo "</td>\n";
	echo "</tr>\n";

	if (permission_exists('conferences_add') || permission_exists('conferences_edit')) {
		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap>\n";
		echo "		User List:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		$onchange = "document.getElementById('user_list').value += document.getElementById('username').value + '\\n';";
		$tablename = 'v_users'; $fieldname = 'username'; $fieldcurrentvalue = ''; $sqlwhereoptional = "where v_id = '$v_id'"; 
		echo htmlselectonchange($db, $tablename, $fieldname, $sqlwhereoptional, $fieldcurrentvalue, $onchange);
		echo "<br />\n";
		echo "Use the select list to add users to the userlist. This will assign users to this extension.\n";
		echo "<br />\n";
		echo "<br />\n";
		//replace the vertical bar with a line feed to display in the textarea
		$user_list = trim($user_list, "|");
		$user_list_array = explode("|", $user_list);
		$user_list = '';
		foreach($user_list_array as $user){
			$user_list .= trim($user)."\n";
		}
		echo "		<textarea name=\"user_list\" id=\"user_list\" class=\"formfld\" cols=\"30\" rows=\"3\" style='width: 60%;' wrap=\"off\">$user_list</textarea>\n";
		echo "		<br>\n";
		echo "If a user is not in the select list it can be added manually to the user list and it will be created automatically.\n";
		echo "<br />\n";
		echo "</td>\n";
		echo "</tr>\n";
	}

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Profile:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='profile' style='width: 60%;'>\n";
	//if the profile has no value set it to default
	if ($profile == "") { $profile = "default"; }

	if ($profile == "default") { echo "<option value='default' selected='selected'>default</option>\n"; } else {	echo "<option value='default'>default</option>\n"; }
	if ($profile == "wideband") { echo "<option value='wideband' selected='selected'>wideband</option>\n"; } else {	echo "<option value='wideband'>wideband</option>\n"; }
	if ($profile == "ultrawideband") { echo "<option value='ultrawideband' selected='selected'>ultrawideband</option>\n"; } else {	echo "<option value='ultrawideband'>ultrawideband</option>\n"; }
	if ($profile == "cdquality") { echo "<option value='cdquality' selected='selected'>cdquality</option>\n"; } else {	echo "<option value='cdquality'>cdquality</option>\n"; }
	echo "    </select>\n";
	echo "<br />\n";
	echo "Conference Profile is a collection of settings for the conference.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Flags:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' style='width: 60%;' type='text' name='flags' maxlength='255' value=\"$flags\">\n";
	echo "<br />\n";
	echo "Optional conference flags. examples: mute|deaf|waste|moderator\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Order:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "              <select name='dialplan_order' class='formfld' style='width: 60%;'>\n";
	if (strlen(htmlspecialchars($dialplan_order))> 0) {
		echo "              <option selected='yes' value='".htmlspecialchars($dialplan_order)."'>".htmlspecialchars($dialplan_order)."</option>\n";
	}
	$i=0;
	while($i<=999) {
		if (strlen($i) == 1) { echo "              <option value='00$i'>00$i</option>\n"; }
		if (strlen($i) == 2) { echo "              <option value='0$i'>0$i</option>\n"; }
		if (strlen($i) == 3) { echo "              <option value='$i'>$i</option>\n"; }
		$i++;
	}
	echo "              </select>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Enabled:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='enabled' style='width: 60%;'>\n";
	if ($enabled == "true") { 
		echo "    <option value='true' SELECTED >true</option>\n";
	}
	else {
		echo "    <option value='true'>true</option>\n";
	}
	if ($enabled == "false") { 
		echo "    <option value='false' SELECTED >false</option>\n";
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
	echo "<td colspan='4' class='vtable' align='left'>\n";
	echo "    <input class='formfld' style='width: 60%;' type='text' name='description' maxlength='255' value=\"$description\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "	<td colspan='5' align='right'>\n";
	if ($action == "update") {
		echo "			<input type='hidden' name='dialplan_include_id' value='$dialplan_include_id'>\n";
	}
	echo "			<input type='submit' name='submit' class='btn' value='Save'>\n";
	echo "	</td>\n";
	echo "</tr>";

	echo "</table>";
	echo "</div>";
	echo "</form>";

	echo "</td>\n";
	echo "</tr>";
	echo "</table>";
	echo "</div>";

	echo "<br><br>";

//show the footer
	require_once "includes/footer.php";
?>
