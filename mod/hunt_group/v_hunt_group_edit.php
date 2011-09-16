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
	if (permission_exists('hunt_group_add') || permission_exists('hunt_group_edit')) {
		//access granted
	}
	else {
		echo "access denied";
		exit;
	}

//action add or update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$hunt_group_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//get the http values and set them as variables
	if (count($_POST)>0) {
		$huntgroupextension = check_str($_POST["huntgroupextension"]);
		$huntgroupname = check_str($_POST["huntgroupname"]);
		$huntgrouptype = check_str($_POST["huntgrouptype"]);
		//$huntgroupcontext = check_str($_POST["huntgroupcontext"]);
		$huntgrouptimeout = check_str($_POST["huntgrouptimeout"]);
		$huntgrouptimeoutdestination = check_str($_POST["huntgrouptimeoutdestination"]);
		$huntgrouptimeouttype = check_str($_POST["huntgrouptimeouttype"]);
		$huntgroupringback = check_str($_POST["huntgroupringback"]);
		$huntgroupcidnameprefix = check_str($_POST["huntgroupcidnameprefix"]);
		$huntgrouppin = check_str($_POST["huntgrouppin"]);
		$huntgroupcallerannounce = check_str($_POST["huntgroupcallerannounce"]);

		//prepare the user list for the database
		$hunt_group_user_list = $_POST["hunt_group_user_list"];
		if (strlen($hunt_group_user_list) > 0) {
			$hunt_group_user_list_array = explode("\n", $hunt_group_user_list);
			if (count($hunt_group_user_list_array) == 0) {
				$hunt_group_user_list = '';
			}
			else {
				$hunt_group_user_list = '|';
				foreach($hunt_group_user_list_array as $user){
					if(strlen(trim($user)) > 0) {
						$hunt_group_user_list .= check_str(trim($user))."|";
					}
				}
			}
		}

		$hunt_group_enabled = check_str($_POST["hunt_group_enabled"]);
		$huntgroupdescr = check_str($_POST["huntgroupdescr"]);

		//remove invalid characters
		$huntgroupcidnameprefix = str_replace(":", "-", $huntgroupcidnameprefix);
		$huntgroupcidnameprefix = str_replace("\"", "", $huntgroupcidnameprefix);
		$huntgroupcidnameprefix = str_replace("@", "", $huntgroupcidnameprefix);
		$huntgroupcidnameprefix = str_replace("\\", "", $huntgroupcidnameprefix);
		$huntgroupcidnameprefix = str_replace("/", "", $huntgroupcidnameprefix);

		//set default
		if (strlen($huntgroupcallerannounce) == 0) { $huntgroupcallerannounce = "false"; }
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$hunt_group_id = check_str($_POST["hunt_group_id"]);
	}

	//check for all required data
		if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		if (strlen($huntgroupextension) == 0) { $msg .= "Please provide: Extension<br>\n"; }
		if (strlen($huntgroupname) == 0) { $msg .= "Please provide: Hunt Group Name<br>\n"; }
		if (strlen($huntgrouptype) == 0) { $msg .= "Please provide: Type<br>\n"; }
		//if (strlen($huntgroupcontext) == 0) { $msg .= "Please provide: Context<br>\n"; }
		if (strlen($huntgrouptimeout) == 0) { $msg .= "Please provide: Timeout<br>\n"; }
		if (strlen($huntgrouptimeoutdestination) == 0) { $msg .= "Please provide: Timeout Destination<br>\n"; }
		if (strlen($huntgrouptimeouttype) == 0) { $msg .= "Please provide: Timeout Type<br>\n"; }
		if (strlen($huntgroupringback) == 0) { $msg .= "Please provide: Ring Back<br>\n"; }
		//if (strlen($huntgroupcidnameprefix) == 0) { $msg .= "Please provide: CID Prefix<br>\n"; }
		//if (strlen($huntgrouppin) == 0) { $msg .= "Please provide: PIN<br>\n"; }
		if (strlen($huntgroupcallerannounce) == 0) { $msg .= "Please provide: Caller Announce<br>\n"; }
		//if (strlen($hunt_group_user_list) == 0) { $msg .= "Please provide: User List<br>\n"; }
		//if (strlen($hunt_group_enabled) == 0) { $msg .= "Please provide: Enabled<br>\n"; }
		//if (strlen($huntgroupdescr) == 0) { $msg .= "Please provide: Description<br>\n"; }
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
				$sql .= "hunt_group_user_list, ";
				$sql .= "hunt_group_enabled, ";
				$sql .= "huntgroupdescr ";
				$sql .= ")";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'$v_id', ";
				$sql .= "'$huntgroupextension', ";
				$sql .= "'$huntgroupname', ";
				$sql .= "'$huntgrouptype', ";
				$sql .= "'default', ";
				$sql .= "'$huntgrouptimeout', ";
				$sql .= "'$huntgrouptimeoutdestination', ";
				$sql .= "'$huntgrouptimeouttype', ";
				$sql .= "'$huntgroupringback', ";
				$sql .= "'$huntgroupcidnameprefix', ";
				$sql .= "'$huntgrouppin', ";
				$sql .= "'$huntgroupcallerannounce', ";
				$sql .= "'$hunt_group_user_list', ";
				$sql .= "'$hunt_group_enabled', ";
				$sql .= "'$huntgroupdescr' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql);

				//synchronize the xml config
				sync_package_v_hunt_group();

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_hunt_group.php\">\n";
				echo "<div align='center'>\n";
				echo "Add Complete\n";
				echo "</div>\n";
				require_once "includes/footer.php";
				return;
			} //if ($action == "add")

			if ($action == "update" && permission_exists('hunt_group_edit')) {
				$sql = "update v_hunt_group set ";
				$sql .= "huntgroupextension = '$huntgroupextension', ";
				$sql .= "huntgroupname = '$huntgroupname', ";
				$sql .= "huntgrouptype = '$huntgrouptype', ";
				$sql .= "huntgroupcontext = 'default', ";
				$sql .= "huntgrouptimeout = '$huntgrouptimeout', ";
				$sql .= "huntgrouptimeoutdestination = '$huntgrouptimeoutdestination', ";
				$sql .= "huntgrouptimeouttype = '$huntgrouptimeouttype', ";
				$sql .= "huntgroupringback = '$huntgroupringback', ";
				$sql .= "huntgroupcidnameprefix = '$huntgroupcidnameprefix', ";
				$sql .= "huntgrouppin = '$huntgrouppin', ";
				$sql .= "huntgroupcallerannounce = '$huntgroupcallerannounce', ";
				if (ifgroup("admin") || ifgroup("superadmin")) {
					$sql .= "hunt_group_user_list = '$hunt_group_user_list', ";
				}
				$sql .= "hunt_group_enabled = '$hunt_group_enabled', ";
				$sql .= "huntgroupdescr = '$huntgroupdescr' ";
				$sql .= "where v_id = '$v_id' ";
				$sql .= "and hunt_group_id = '$hunt_group_id'";
				$db->exec(check_sql($sql));
				unset($sql);

				//synchronize the xml config
				sync_package_v_hunt_group();

				//synchronize the xml config
				sync_package_v_dialplan_includes();

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_hunt_group.php\">\n";
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
		$hunt_group_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_hunt_group ";
		$sql .= "where hunt_group_id = '$hunt_group_id' ";
		$sql .= "and v_id = '$v_id' ";
		$sql .- "hunt_group_enabled = 'true' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$huntgroupextension = $row["huntgroupextension"];
			$huntgroupname = $row["huntgroupname"];
			$huntgrouptype = $row["huntgrouptype"];
			//$huntgroupcontext = $row["huntgroupcontext"];
			$huntgrouptimeout = $row["huntgrouptimeout"];
			$huntgrouptimeoutdestination = $row["huntgrouptimeoutdestination"];
			$huntgrouptimeouttype = $row["huntgrouptimeouttype"];
			$huntgroupringback = $row["huntgroupringback"];
			$huntgroupcidnameprefix = $row["huntgroupcidnameprefix"];
			$huntgrouppin = $row["huntgrouppin"];
			$huntgroupcallerannounce = $row["huntgroupcallerannounce"];
			$hunt_group_user_list = $row["hunt_group_user_list"];
			$hunt_group_enabled = $row["hunt_group_enabled"];
			$huntgroupdescr = $row["huntgroupdescr"];
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
	echo "<td align=\"left\">\n";
	echo "<br>";

	echo "<form method='post' name='frm' action=''>\n";
	echo "<div align='center'>\n";
	echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";
	echo "<tr>\n";
	echo "<td align='left' colspan='2'>\n";

	echo "<table width='100%'>\n";
	echo "<tr>\n";
	echo "<td align='left' width='30%' nowrap>\n";
	echo "	<span class='vexpl'>\n";
	echo "		<strong>Hunt Group</strong><br>\n";
	echo "	</span>\n";
	echo "</td>\n";
	echo "<td width='70%' align='right'>\n";
	echo "	<input type='button' class='btn' name='' alt='copy' onclick=\"if (confirm('Do you really want to copy this?')){window.location='v_hunt_group_copy.php?id=".$hunt_group_id."';}\" value='Copy'>\n";
	echo "	<input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_hunt_group.php'\" value='Back'>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td align='left' colspan='2'>\n";
	echo "		  A Hunt Group is a list of destinations that can be called in sequence or simultaneously. \n";
	echo "		  </span><br />\n";
	echo "<br />\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	 Extension:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	 <input class='formfld' type='text' name='huntgroupextension' maxlength='255' value=\"$huntgroupextension\">\n";
	echo "<br />\n";
	echo "example: 7002\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	 Hunt Group Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	 <input class='formfld' type='text' name='huntgroupname' maxlength='255' value=\"$huntgroupname\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	 Type:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	 <select class='formfld' name='huntgrouptype'>\n";
	echo "	 <option value=''></option>\n";
	if ($huntgrouptype == "simultaneous") { 
		echo "	 <option value='simultaneous' selected='selected'>simultaneous</option>\n";
	}
	else {
		echo "	 <option value='simultaneous'>simultaneous</option>\n";
	}
	if ($huntgrouptype == "sequentially") { 
		echo "	 <option value='sequentially' selected='selected'>sequentially</option>\n";
	}
	else {
		echo "	 <option value='sequentially'>sequentially</option>\n";
	}
	//if ($huntgrouptype == "call_forward") { 
	//	echo "	 <option value='call_forward' selected='selected'>call_forward</option>\n";
	//}
	//else {
	//	echo "	 <option value='call_forward'>call_forward</option>\n";
	//}
	//if ($huntgrouptype == "dnd") { 
	//	echo "	 <option value='dnd' selected='selected'>dnd</option>\n";
	//}
	//else {
	//	echo "	 <option value='dnd'>dnd</option>\n";
	//}
	//if ($huntgrouptype == "follow_me_sequence") { 
	//	echo "	 <option value='follow_me_sequence' selected='selected'>follow_me_sequence</option>\n";
	//}
	//else {
	//	echo "	 <option value='follow_me_sequence'>follow_me_sequence</option>\n";
	//}
	//if ($huntgrouptype == "follow_me_simultaneous") { 
	//	echo "	 <option value='follow_me_simultaneous' selected='selected'>follow_me_simultaneous</option>\n";
	//}
	//else {
	//	echo "	 <option value='follow_me_simultaneous'>follow_me_simultaneous</option>\n";
	//}
	echo "	 </select>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	 Timeout:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	 <input class='formfld' type='text' name='huntgrouptimeout' maxlength='255' value=\"$huntgrouptimeout\">\n";
	echo "<br />\n";
	echo "The timeout sets the time in seconds to continue to call before timing out. \n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	 Timeout Destination:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	 <input class='formfld' type='text' name='huntgrouptimeoutdestination' maxlength='255' value=\"$huntgrouptimeoutdestination\">\n";
	echo "<br />\n";
	echo "Destination. example: 1001\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	 Timeout Type:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	 <select class='formfld' name='huntgrouptimeouttype'>\n";
	echo "	 <option value=''></option>\n";
	if ($huntgrouptimeouttype == "extension") { 
		echo "	 <option value='extension' SELECTED >extension</option>\n";
	}
	else {
		echo "	 <option value='extension'>extension</option>\n";
	}
	if ($huntgrouptimeouttype == "voicemail") { 
		echo "	 <option value='voicemail' SELECTED >voicemail</option>\n";
	}
	else {
		echo "	 <option value='voicemail'>voicemail</option>\n";
	}
	if ($huntgrouptimeouttype == "sip uri") { 
		echo "	 <option value='sip uri' SELECTED >sip uri</option>\n";
	}
	else {
		echo "	 <option value='sip uri'>sip uri</option>\n";
	}
	echo "	 </select>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	 Ring Back:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	 <select class='formfld' name='huntgroupringback'>\n";
	echo "	 <option value=''></option>\n";
	if ($huntgroupringback == "ring") { 
		echo "	 <option value='us-ring' selected='selected'>us-ring</option>\n";
	}
	else {
		echo "	 <option value='us-ring'>us-ring</option>\n";
	}
	if ($huntgroupringback == "us-ring") { 
		echo "	 <option value='us-ring' selected='selected'>us-ring</option>\n";
	}
	else {
		echo "	 <option value='us-ring'>us-ring</option>\n";
	}
	if ($huntgroupringback == "fr-ring") { 
		echo "	 <option value='fr-ring' selected='selected'>fr-ring</option>\n";
	}
	else {
		echo "	 <option value='fr-ring'>fr-ring</option>\n";
	}
	if ($huntgroupringback == "uk-ring") { 
		echo "	 <option value='uk-ring' selected='selected'>uk-ring</option>\n";
	}
	else {
		echo "	 <option value='uk-ring'>uk-ring</option>\n";
	}
	if ($huntgroupringback == "rs-ring") { 
		echo "	 <option value='rs-ring' selected='selected'>rs-ring</option>\n";
	}
	else {
		echo "	 <option value='rs-ring'>rs-ring</option>\n";
	}
	if ($huntgroupringback == "music") { 
		echo "	 <option value='music' selected='selected'>music</option>\n";
	}
	else {
		echo "	 <option value='music'>music</option>\n";
	}
	echo "	 </select>\n";
	echo "<br />\n";
	echo "Defines what the caller will hear while destination is being called. The choices are music (music on hold) ring (ring tone.) default: music \n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	 CID Prefix:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	 <input class='formfld' type='text' name='huntgroupcidnameprefix' maxlength='255' value=\"$huntgroupcidnameprefix\">\n";
	echo "<br />\n";
	echo "Set a prefix on the caller ID name.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	 PIN:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	 <input class='formfld' type='text' name='huntgrouppin' maxlength='255' value=\"$huntgrouppin\">\n";
	echo "<br />\n";
	echo "If this is provided then the caller will be required to enter the PIN number.\n";
	echo "</td>\n";
	echo "</tr>\n";

	if (!$fp) {
		$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
	}
	if (switch_module_is_running($fp, 'mod_spidermonkey')) {
		echo "<tr>\n";
		echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
		echo "	 Caller Announce:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "	 <select class='formfld' name='huntgroupcallerannounce'>\n";
		echo "	 <option value=''></option>\n";
		if ($huntgroupcallerannounce == "true") { 
			echo "	 <option value='true' selected='selected'>true</option>\n";
		}
		else {
			echo "	 <option value='true'>true</option>\n";
		}
		if ($huntgroupcallerannounce == "false") { 
			echo "	 <option value='false' selected='selected'>false</option>\n";
		}
		else {
			echo "	 <option value='false'>false</option>\n";
		}
		echo "	 </select>\n";
		echo "<br />\n";
		echo "\n";
		echo "</td>\n";
		echo "</tr>\n";
	}

	if (ifgroup("admin") || ifgroup("superadmin")) {
		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap>\n";
		echo "		User List:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		$onchange = "document.getElementById('hunt_group_user_list').value += document.getElementById('username').value + '\\n';";
		$tablename = 'v_users'; $fieldname = 'username'; $fieldcurrentvalue = ''; $sqlwhereoptional = "where v_id = '$v_id' "; 
		echo htmlselectonchange($db, $tablename, $fieldname, $sqlwhereoptional, $fieldcurrentvalue, $onchange);
		echo "<br />\n";
		echo "Use the select list to add users to the user list. This will assign users to this extension.\n";
		echo "<br />\n";
		echo "<br />\n";
		//replace the vertical bar with a line feed to display in the textarea
		$hunt_group_user_list = trim($hunt_group_user_list, "|");
		$hunt_group_user_list_array = explode("|", $hunt_group_user_list);
		$hunt_group_user_list = '';
		foreach($hunt_group_user_list_array as $user){
			$hunt_group_user_list .= trim($user)."\n";
		}
		echo "		<textarea name=\"hunt_group_user_list\" id=\"hunt_group_user_list\" class=\"formfld\" cols=\"30\" rows=\"3\" wrap=\"off\">$hunt_group_user_list</textarea>\n";
		echo "		<br>\n";
		echo "Assign the users that are can manage this hunt group extension.\n";
		echo "<br />\n";
		echo "</td>\n";
		echo "</tr>\n";
	}

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	 Enabled:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	 <select class='formfld' name='hunt_group_enabled'>\n";
	echo "	 <option value=''></option>\n";
	if ($hunt_group_enabled == "true" || strlen($hunt_group_enabled) == 0) { 
		echo "	 <option value='true' selected >true</option>\n";
	}
	else {
		echo "	 <option value='true'>true</option>\n";
	}
	if ($hunt_group_enabled == "false") { 
		echo "	 <option value='false' selected >false</option>\n";
	}
	else {
		echo "	 <option value='false'>false</option>\n";
	}
	echo "	 </select>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	 Description:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	 <input class='formfld' type='text' name='huntgroupdescr' maxlength='255' value=\"$huntgroupdescr\">\n";
	echo "<br />\n";
	echo "You may enter a description here for your reference (not parsed). \n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	if ($action == "update") {
		echo "				<input type='hidden' name='hunt_group_id' value='$hunt_group_id'>\n";
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

//list hunt group destinations
	if ($action == "update") {

		echo "<div align='center'>";
		echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

		echo "<tr class='border'>\n";
		echo "	<td align=\"center\">\n";
		echo "		<br>";

		echo "<table width='100%' border='0' cellpadding='6' cellspacing='0'>\n";
		echo "	<tr>\n";
		echo "	<td align='left'><p><span class='vexpl'>\n";
		echo "		<span class='red'><strong>\n";
		echo "			Destinations<br />\n";
		echo "		</strong></span>\n";
		echo "			The following destinations will be called.\n";
		echo "		</span></p></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "<br />\n";

		$sql = "";
		$sql .= " select * from v_hunt_group_destinations ";
		$sql .= " where v_id = '$v_id' ";
		$sql .= " and hunt_group_id = '$hunt_group_id' ";
		$sql .= " order by destinationorder, destinationdata asc";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		$resultcount = count($result);
		unset ($prepstatement, $sql);


		$c = 0;
		$rowstyle["0"] = "rowstyle0";
		$rowstyle["1"] = "rowstyle1";

		echo "<div align='center'>\n";
		echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";

		echo "<tr>\n";
		echo "<th align='center'>Destination</th>\n";
		echo "<th align='center'>Type</th>\n";
		echo "<th align='center'>Profile</th>\n";
		echo "<th align='center'>Order</th>\n";
		echo "<th align='center'>Description</th>\n";
		echo "<td align='right' width='42'>\n";
		if (permission_exists('hunt_group_add')) {
			echo "	<a href='v_hunt_group_destinations_edit.php?id2=".$hunt_group_id."' alt='add'>$v_link_label_add</a>\n";
		}
		echo "</td>\n";
		echo "<tr>\n";

		if ($resultcount == 0) {
			//no results
		}
		else { //received results
			foreach($result as $row) {
				echo "<tr >\n";
				echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row['destinationdata']."</td>\n";
				echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row['destinationtype']."</td>\n";
				echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row['destinationprofile']."</td>\n";
				echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row['destinationorder']."</td>\n";
				echo "	<td valign='top' class='rowstylebg' width='30%'>".$row['destinationdescr']."&nbsp;</td>\n";
				echo "	<td valign='top' align='right'>\n";
				if (permission_exists('hunt_group_edit')) {
					echo "		<a href='v_hunt_group_destinations_edit.php?id=".$row['hunt_group_destination_id']."&id2=".$hunt_group_id."' alt='edit'>$v_link_label_edit</a>\n";
				}
				if (permission_exists('hunt_group_delete')) {
					echo "		<a href='v_hunt_group_destinations_delete.php?id=".$row['hunt_group_destination_id']."&id2=".$hunt_group_id."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
				}
				echo "	</td>\n";
				echo "</tr>\n";
				if ($c==0) { $c=1; } else { $c=0; }
			} //end foreach
			unset($sql, $result, $rowcount);
		} //end if results

		echo "<tr>\n";
		echo "<td colspan='6'>\n";
		echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
		echo "	<tr>\n";
		echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
		echo "		<td width='33.3%' align='center' nowrap>$pagingcontrols</td>\n";
		echo "		<td width='33.3%' align='right'>\n";
		if (permission_exists('hunt_group_add')) {
			echo "			<a href='v_hunt_group_destinations_edit.php?id2=".$hunt_group_id."' alt='add'>$v_link_label_add</a>\n";
		}
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		echo "</td>\n";
		echo "</tr>\n";

		echo "</table>";
		echo "</div>";
		echo "<br><br>";
		echo "<br><br>";

		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</div>";
		echo "<br><br>";
	} //end if update

//show the footer
	require_once "includes/footer.php";
?>
