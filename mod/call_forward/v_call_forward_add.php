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
if (permission_exists('call_forward_add')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}
require_once "includes/paging.php";
$orderby = $_GET["orderby"];
$order = $_GET["order"];


//POST to PHP variables
	if (count($_POST)>0) {
		$extension_name = check_str($_POST["extension_name"]);
		$dialplanorder = check_str($_POST["dialplanorder"]);
		$condition_field_1 = check_str($_POST["condition_field_1"]);
		$condition_expression_1 = check_str($_POST["condition_expression_1"]);
		$condition_field_2 = check_str($_POST["condition_field_2"]);
		$condition_expression_2 = check_str($_POST["condition_expression_2"]);

 		$action_1 = check_str($_POST["action_1"]);
		//$action_1 = "transfer:1001 XML default";
		$action_1_array = explode(":", $action_1);
		$action_application_1 = array_shift($action_1_array);
		$action_data_1 = join(':', $action_1_array);

 		$action_2 = check_str($_POST["action_2"]);
		//$action_2 = "transfer:1001 XML default";
		$action_2_array = explode(":", $action_2);
		$action_application_2 = array_shift($action_2_array);
		$action_data_2 = join(':', $action_2_array);

		//$action_application_1 = check_str($_POST["action_application_1"]);
		//$action_data_1 = check_str($_POST["action_data_1"]);
		//$action_application_2 = check_str($_POST["action_application_2"]);
		//$action_data_2 = check_str($_POST["action_data_2"]);

		$enabled = check_str($_POST["enabled"]);
		$description = check_str($_POST["description"]);
		if (strlen($enabled) == 0) { $enabled = "true"; } //set default to enabled
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {
	//check for all required data
		if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		if (strlen($extension_name) == 0) { $msg .= "Please provide: Extension Name<br>\n"; }
		//if (strlen($condition_field_1) == 0) { $msg .= "Please provide: Condition Field<br>\n"; }
		if (strlen($condition_expression_1) == 0) { $msg .= "Please provide: Condition Expression<br>\n"; }
		if (strlen($action_application_1) == 0) { $msg .= "Please provide: Action Application<br>\n"; }
		//if (strlen($enabled) == 0) { $msg .= "Please provide: Enabled True or False<br>\n"; }
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

	//add the entries to the dialplan
			$extensionname = $extension_name."";
			$context = 'default';
			$opt1name = 'call_forward_id';
			$dialplan_include_id = v_dialplan_includes_add($v_id, $extensionname, $dialplanorder, $context, $enabled, $description, $opt1name, $opt1value);
			if (strlen($dialplan_include_id) > 0 && strlen($condition_expression_1) > 0) {
				//add condition 1
					$tag = 'condition'; //condition, action, antiaction
					$fieldtype = 'destination_number';
					$fielddata = '^'.$condition_expression_1.'$';
					$fieldorder = '000';
					v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);
				//add condition 2
					if (strlen($condition_expression_2) > 0) {
						$tag = 'condition'; //condition, action, antiaction
						$fieldtype = $condition_field_2;
						$fielddata = '^'.$condition_expression_2.'$';
						$fieldorder = '001';
						v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);
					}
				//set action 1
					if (strlen($action_application_1) > 0) {
						$tag = 'action'; //condition, action, antiaction
						$fieldtype = 'set';
						$fieldtype = $action_application_1;
						$fielddata = $action_data_1;
						$fieldorder = '002';
						v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);
					}
				//set action 2
					if (strlen($action_application_2) > 0) {
						$tag = 'action'; //condition, action, antiaction
						$fieldtype = $action_application_2;
						$fielddata = $action_data_2;
						$fieldorder = '003';
						v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);
					}
			}

	//commit the atomic transaction
		$count = $db->exec("COMMIT;"); //returns affected rows

	//synchronize the xml config
		sync_package_v_dialplan_includes();

	//redirect the user
		require_once "includes/header.php";
		echo "<meta http-equiv=\"refresh\" content=\"2;url=v_call_forward.php\">\n";
		echo "<div align='center'>\n";
		echo "Update Complete\n";
		echo "</div>\n";
		require_once "includes/footer.php";
		return;
} //end if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0)

//show the header
	require_once "includes/header.php";

?>

<script type="text/javascript">
<!--
function type_onchange(field_type) {
var field_value = document.getElementById(field_type).value;

//desc_action_data_1
//desc_action_data_2

if (field_type == "condition_field_1") {
	if (field_value == "destination_number") {
		document.getElementById("desc_condition_expression_1").innerHTML = "expression: ^12081231234$";
	}
	else if (field_value == "zzz") {
		document.getElementById("desc_condition_expression_1").innerHTML = "";
	}
	else {
		document.getElementById("desc_condition_expression_1").innerHTML = "";
	}
}
if (field_type == "condition_field_2") {
	if (field_value == "destination_number") {
		document.getElementById("desc_condition_expression_2").innerHTML = "expression: ^12081231234$";
	}
	else if (field_value == "zzz") {
		document.getElementById("desc_condition_expression_2").innerHTML = "";
	}
	else {
		document.getElementById("desc_condition_expression_2").innerHTML = "";
	}
}
</script>

<?php

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
	echo "		<td align='left'><span class=\"vexpl\">\n";
	echo "			<span class=\"red\"><strong>\n";
	echo "				Call Forward\n";
	echo "			</strong></span></span>\n";
	echo "		</td>\n";
	echo "		<td align='right'>\n";
	echo "			<input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_call_forward.php'\" value='Back'>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align='left' colspan='2'>\n";
	echo "			<span class=\"vexpl\">\n";
	echo "				Use Call Forward to redirect calls from a particular extension to an alternate destination. \n";
	echo "				The alternate destination can be another extension, external phone number such as a cell phone, \n";
	echo "				or various other possibilities.\n";
	echo "			</span>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	</table>";

	echo "<br />\n";
	echo "<br />\n";

	echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";
	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' style='width: 60%;' type='text' name='extension_name' maxlength='255' value=\"$extension_name\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Extension:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<table style='width: 60%;' border='0'>\n";
	echo "	<tr>\n";
	echo "	<td style='width: 62px;'>Number:</td>\n";
	echo "	<td colspan='3' style='width: 100%; align: left;'>\n";
	//condition_field_1

	echo "    <select class='formfld' name='condition_expression_1' id='condition_expression_1' onchange='type_onchange(\"condition_expression_1\");' style='width:100%'>\n";
	echo "    <option value=''></option>\n";
	if (strlen($condition_expression_1) > 0) {
		echo "    <option value='$condition_expression_1' selected>$condition_expression_1</option>\n";
	}

	$sql = "";
	$sql .= " select * from v_extensions ";
	$sql .= "where v_id = '$v_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$resultcount = count($result);
	unset ($prepstatement, $sql);
	if ($resultcount == 0) { //no results
	}
	else { //received results
		foreach($result as $row) {
			//print_r( $row );
			if ($condition_expression_1 == "^".$row[extension]."$") {
				echo "    <option value='".$row[extension]."' selected>".$row[extension]." ".$row[description]."</option>\n";
			}
			else {
				echo "    <option value='".$row[extension]."'>".$row[extension]." ".$row[description]."</option>\n";
			}
		} //end foreach
		unset($sql, $result, $rowcount);
	} //end if results
	echo "    </select><br />\n";
	echo "	</td>\n";

	echo "	</tr>\n";
	echo "	</table>\n";
	echo "	<div id='desc_condition_expression_1'></div>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Condition\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";

	echo "	<table style='width: 60%;' border='0'>\n";
	echo "	<tr>\n";
	echo "	<td align='left' style='width: 62px;'>\n";
	echo "		Field:\n";
	echo "	</td>\n";
	echo "	<td style='width: 35%;' align='left'>\n";
	echo "    <select class='formfld' name='condition_field_2' id='condition_field_2' onchange='type_onchange(\"condition_field_2\");' style='width:100%'>\n";
	echo "    <option value=''></option>\n";
	if (strlen($condition_field_2) > 0) {
		echo "    <option value='$condition_field_2' selected>$condition_field_2</option>\n";
	}
	echo "	<optgroup label='Field'>\n";
	echo "		<option value='context'>context</option>\n";
	echo "		<option value='username'>username</option>\n";
	echo "		<option value='rdnis'>rdnis</option>\n";
	echo "		<option value='destination_number'>destination_number</option>\n";
	echo "		<option value='public'>public</option>\n";
	echo "		<option value='caller_id_name'>caller_id_name</option>\n";
	echo "		<option value='caller_id_number'>caller_id_number</option>\n";
	echo "		<option value='ani'>ani</option>\n";
	echo "		<option value='ani2'>ani2</option>\n";
	echo "		<option value='uuid'>uuid</option>\n";
	echo "		<option value='source'>source</option>\n";
	echo "		<option value='chan_name'>chan_name</option>\n";
	echo "		<option value='network_addr'>network_addr</option>\n";
	echo "	</optgroup>\n";
	echo "	<optgroup label='Time'>\n";
	echo "		<option value='hour'>hour</option>\n";
	echo "		<option value='minute'>minute</option>\n";
	echo "		<option value='minute-of-day'>minute of day</option>\n";
	echo "		<option value='mday'>day of month</option>\n";
	echo "		<option value='mweek'>week of month</option>\n";
	echo "		<option value='mon'>month</option>\n";
	echo "		<option value='yday'>day of year</option>\n";
	echo "		<option value='year'>year</option>\n";
	echo "		<option value='wday'>day of week</option>\n";
	echo "		<option value='week'>week</option>\n";
	echo "	</optgroup>\n";
	echo "    </select><br />\n";
	echo "	</td>\n";
	echo "	<td style='width: 73px;' align='left'>\n";
	echo "		&nbsp; Expression:\n";
	echo "	</td>\n";
	echo "	<td>\n";
	echo "		<input class='formfld' type='text' name='condition_expression_2' maxlength='255' style='width:100%' value=\"$condition_expression_2\">\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "	<div id='desc_condition_expression_2'></div>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Action 1:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";

	//switch_select_destination(select_type, select_label, select_name, select_value, select_style, $action);
	switch_select_destination("dialplan", $action_1, "action_1", $action_1, "width: 60%;", "");

	echo "</td>\n";
	echo "</tr>\n";

	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Action 2:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";

	//switch_select_destination(select_type, select_label, select_name, select_value, select_style, $action);
	switch_select_destination("dialplan", $action_2, "action_2", $action_2, "width: 60%;", "");

	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Order:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "              <select name='dialplanorder' class='formfld' style='width: 60%;'>\n";
	//echo "              <option></option>\n";
	if (strlen(htmlspecialchars($dialplanorder))> 0) {
		echo "              <option selected='yes' value='".htmlspecialchars($dialplanorder)."'>".htmlspecialchars($dialplanorder)."</option>\n";
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
	//echo "    <option value=''></option>\n";
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
