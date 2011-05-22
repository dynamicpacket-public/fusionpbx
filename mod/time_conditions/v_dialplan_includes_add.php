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
if (permission_exists('time_conditions_add')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}
require_once "includes/header.php";
require_once "includes/paging.php";

$orderby = $_GET["orderby"];
$order = $_GET["order"];


//get the post form variables and se them to php variables
	if (count($_POST)>0) {
		$extension_name = check_str($_POST["extension_name"]);
		$extension_number = check_str($_POST["extension_number"]);
		$dialplanorder = check_str($_POST["dialplanorder"]);
		$condition_hour = check_str($_POST["condition_hour"]);
		$condition_minute = check_str($_POST["condition_minute"]);
		$condition_minute_of_day = check_str($_POST["condition_minute_of_day"]);
		$condition_mday = check_str($_POST["condition_mday"]);
		$condition_mweek = check_str($_POST["condition_mweek"]);
		$condition_mon = check_str($_POST["condition_mon"]);
		$condition_yday = check_str($_POST["condition_yday"]);
		$condition_year = check_str($_POST["condition_year"]);
		$condition_wday = check_str($_POST["condition_wday"]);
		$condition_week = check_str($_POST["condition_week"]);

		$action_1 = check_str($_POST["action_1"]);
		//$action_1 = "transfer:1001 XML default";
		$action_1_array = explode(":", $action_1);
		$action_application_1 = array_shift($action_1_array);
		$action_data_1 = join(':', $action_1_array);

		$anti_action_1 = check_str($_POST["anti_action_1"]);
		//$anti_action_1 = "transfer:1001 XML default";
		$anti_action_1_array = explode(":", $anti_action_1);
		$anti_action_application_1 = array_shift($anti_action_1_array);
		$anti_action_data_1 = join(':', $anti_action_1_array);

		//$action_application_1 = check_str($_POST["action_application_1"]);
		//$action_data_1 = check_str($_POST["action_data_1"]);
		//$anti_action_application_1 = check_str($_POST["anti_action_application_1"]);
		//$anti_action_data_1 = check_str($_POST["anti_action_data_1"]);
		$enabled = check_str($_POST["enabled"]);
		$description = check_str($_POST["description"]);
		if (strlen($enabled) == 0) { $enabled = "true"; } //set default to enabled
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {
	//check for all required data
		if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		if (strlen($extension_name) == 0) { $msg .= "Please provide: Extension Name<br>\n"; }
		//if (strlen($condition_field_1) == 0) { $msg .= "Please provide: Condition Field<br>\n"; }
		//if (strlen($condition_expression_1) == 0) { $msg .= "Please provide: Condition Expression<br>\n"; }
		//if (strlen($action_application_1) == 0) { $msg .= "Please provide: Action Application<br>\n"; }
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
		$sql .= "'$dialplanorder', ";
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

	//add a destination number
		if (strlen($extension_number) > 0) {
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
			$sql .= "'^$extension_number$', ";
			$sql .= "'1' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
		}

	//add time based conditions
		if (strlen($condition_wday) > 0) {
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
			$sql .= "'wday', ";
			$sql .= "'$condition_wday', ";
			$sql .= "'1' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
		}
		if (strlen($condition_minute_of_day) > 0) {
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
			$sql .= "'minute-of-day', ";
			$sql .= "'$condition_minute_of_day', ";
			$sql .= "'2' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
		}
		if (strlen($condition_mday) > 0) {
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
			$sql .= "'mday', ";
			$sql .= "'$condition_mday', ";
			$sql .= "'3' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
		}
		if (strlen($condition_mweek) > 0) {
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
			$sql .= "'mweek', ";
			$sql .= "'$condition_mweek', ";
			$sql .= "'4' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
		}
		if (strlen($condition_mon) > 0) {
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
			$sql .= "'mon', ";
			$sql .= "'$condition_mon', ";
			$sql .= "'5' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
		}
		if (strlen($condition_hour) > 0) {
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
			$sql .= "'hour', ";
			$sql .= "'$condition_hour', ";
			$sql .= "'6' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
		}
		if (strlen($condition_minute) > 0) {
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
			$sql .= "'minute', ";
			$sql .= "'$condition_minute', ";
			$sql .= "'7' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
		}
		if (strlen($condition_week) > 0) {
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
			$sql .= "'week', ";
			$sql .= "'$condition_week', ";
			$sql .= "'8' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
		}
		if (strlen($condition_yday) > 0) {
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
			$sql .= "'yday', ";
			$sql .= "'$condition_yday', ";
			$sql .= "'9' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
		}
		if (strlen($condition_year) > 0) {
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
			$sql .= "'year', ";
			$sql .= "'$condition_year', ";
			$sql .= "'10' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
		}


	//add action 1
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
		$sql .= "'$action_application_1', ";
		$sql .= "'$action_data_1', ";
		$sql .= "'3' ";
		$sql .= ")";
		$db->exec(check_sql($sql));
		unset($sql);

	//add anti-action 1
		if (strlen($anti_action_application_1) > 0) {
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
			$sql .= "'anti-action', ";
			$sql .= "'$anti_action_application_1', ";
			$sql .= "'$anti_action_data_1', ";
			$sql .= "'4' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
		}

	//commit the atomic transaction
		$count = $db->exec("COMMIT;"); //returns affected rows

	//synchronize the xml config
		sync_package_v_dialplan_includes();

	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"2;url=v_dialplan_includes.php\">\n";
	echo "<div align='center'>\n";
	echo "Update Complete\n";
	echo "</div>\n";
	require_once "includes/footer.php";
	return;

} //end if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0)

?><script type="text/javascript">
<!--

function show_advanced_config() {
	document.getElementById("showadvancedbox").innerHTML='';
	aodiv = document.getElementById('showadvanced');
	aodiv.style.display = "block";
}

function hide_advanced_config() {
	document.getElementById("showadvancedbox").innerHTML='';
	aodiv = document.getElementById('showadvanced');
	aodiv.style.display = "block";
}

function template_onchange(tmp_object) {
	var template = tmp_object.value;
	if (template == "Office Hours Mon-Fri 8am-5pm") {
		document.getElementById("condition_hour").value = '';
		document.getElementById("condition_minute").value = '';
		document.getElementById("condition_minute_of_day").value = '480-1020';
		document.getElementById("condition_mday").value = '';
		document.getElementById("condition_mweek").value = '';
		document.getElementById("condition_mon").value = '';
		document.getElementById("condition_yday").value = '';
		document.getElementById("condition_year").value = '';
		document.getElementById("condition_wday").value = '2-6';
		document.getElementById("condition_week").value = '';
	}
	else if (template == "Office Hours Mon-Fri 9am-6pm") {
		document.getElementById("condition_hour").value = '';
		document.getElementById("condition_minute").value = '';
		document.getElementById("condition_minute_of_day").value = '540-1080';
		document.getElementById("condition_mday").value = '';
		document.getElementById("condition_mweek").value = '';
		document.getElementById("condition_mon").value = '';
		document.getElementById("condition_yday").value = '';
		document.getElementById("condition_year").value = '';
		document.getElementById("condition_wday").value = '2-6';
		document.getElementById("condition_week").value = '';
	}
	else if (template == "New Year's Day") {
		document.getElementById("condition_hour").value = '';
		document.getElementById("condition_minute").value = '';
		document.getElementById("condition_minute_of_day").value = '';
		document.getElementById("condition_mday").value = '1';
		document.getElementById("condition_mweek").value = '';
		document.getElementById("condition_mon").value = '1';
		document.getElementById("condition_yday").value = '';
		document.getElementById("condition_year").value = '';
		document.getElementById("condition_wday").value = '';
		document.getElementById("condition_week").value = '';
	}
	else if (template == "Martin Luther King Jr Day") {
		document.getElementById("condition_hour").value = '';
		document.getElementById("condition_minute").value = '';
		document.getElementById("condition_minute_of_day").value = '';
		document.getElementById("condition_mday").value = '';
		document.getElementById("condition_mweek").value = '3';
		document.getElementById("condition_mon").value = '1';
		document.getElementById("condition_yday").value = '';
		document.getElementById("condition_year").value = '';
		document.getElementById("condition_wday").value = '2';
		document.getElementById("condition_week").value = '';
	}
	else if (template == "Presidents Day") {
		document.getElementById("condition_hour").value = '';
		document.getElementById("condition_minute").value = '';
		document.getElementById("condition_minute_of_day").value = '';
		document.getElementById("condition_mday").value = '';
		document.getElementById("condition_mweek").value = '3';
		document.getElementById("condition_mon").value = '2';
		document.getElementById("condition_yday").value = '';
		document.getElementById("condition_year").value = '';
		document.getElementById("condition_wday").value = '2';
		document.getElementById("condition_week").value = '';
	}
	else if (template == "Memorial Day") {
		document.getElementById("condition_hour").value = '';
		document.getElementById("condition_minute").value = '';
		document.getElementById("condition_minute_of_day").value = '';
		document.getElementById("condition_mday").value = '25-31';
		document.getElementById("condition_mweek").value = '';
		document.getElementById("condition_mon").value = '5';
		document.getElementById("condition_yday").value = '';
		document.getElementById("condition_year").value = '';
		document.getElementById("condition_wday").value = '2';
		document.getElementById("condition_week").value = '';
	}
	else if (template == "Independence Day") {
		document.getElementById("condition_hour").value = '';
		document.getElementById("condition_minute").value = '';
		document.getElementById("condition_minute_of_day").value = '';
		document.getElementById("condition_mday").value = '4';
		document.getElementById("condition_mweek").value = '';
		document.getElementById("condition_mon").value = '7';
		document.getElementById("condition_yday").value = '';
		document.getElementById("condition_year").value = '';
		document.getElementById("condition_wday").value = '';
		document.getElementById("condition_week").value = '';
	}
	else if (template == "Labor Day") {
		document.getElementById("condition_hour").value = '';
		document.getElementById("condition_minute").value = '';
		document.getElementById("condition_minute_of_day").value = '';
		document.getElementById("condition_mday").value = '';
		document.getElementById("condition_mweek").value = '1';
		document.getElementById("condition_mon").value = '9';
		document.getElementById("condition_yday").value = '';
		document.getElementById("condition_year").value = '';
		document.getElementById("condition_wday").value = '2';
		document.getElementById("condition_week").value = '';
	}
	else if (template == "Columbus Day") {
		document.getElementById("condition_hour").value = '';
		document.getElementById("condition_minute").value = '';
		document.getElementById("condition_minute_of_day").value = '';
		document.getElementById("condition_mday").value = '';
		document.getElementById("condition_mweek").value = '2';
		document.getElementById("condition_mon").value = '10';
		document.getElementById("condition_yday").value = '';
		document.getElementById("condition_year").value = '';
		document.getElementById("condition_wday").value = '2';
		document.getElementById("condition_week").value = '';
	}
	else if (template == "Veteran's Day") {
		document.getElementById("condition_hour").value = '';
		document.getElementById("condition_minute").value = '';
		document.getElementById("condition_minute_of_day").value = '';
		document.getElementById("condition_mday").value = '11';
		document.getElementById("condition_mweek").value = '';
		document.getElementById("condition_mon").value = '11';
		document.getElementById("condition_yday").value = '';
		document.getElementById("condition_year").value = '';
		document.getElementById("condition_wday").value = '';
		document.getElementById("condition_week").value = '';
	}
	else if (template == "Thanksgiving") {
		document.getElementById("condition_hour").value = '';
		document.getElementById("condition_minute").value = '';
		document.getElementById("condition_minute_of_day").value = '';
		document.getElementById("condition_mday").value = '';
		document.getElementById("condition_mweek").value = '4';
		document.getElementById("condition_mon").value = '11';
		document.getElementById("condition_yday").value = '';
		document.getElementById("condition_year").value = '';
		document.getElementById("condition_wday").value = '5-6';
		document.getElementById("condition_week").value = '';
	}
	else if (template == "Christmas") {
		document.getElementById("condition_hour").value = '';
		document.getElementById("condition_minute").value = '';
		document.getElementById("condition_minute_of_day").value = '';
		document.getElementById("condition_mday").value = '25';
		document.getElementById("condition_mweek").value = '';
		document.getElementById("condition_mon").value = '12';
		document.getElementById("condition_yday").value = '';
		document.getElementById("condition_year").value = '';
		document.getElementById("condition_wday").value = '';
		document.getElementById("condition_week").value = '';
	}
}

function type_onchange(field_type) {
	var field_value = document.getElementById(field_type).value;
	//desc_action_data_1
	//desc_anti_action_data

	if (field_type == "action_application_1") {
		if (field_value == "transfer") {
			document.getElementById("desc_action_data_1").innerHTML = "Transfer the call through the dialplan to the destination. data: 1001 XML default";
		}
		else if (field_value == "bridge") {
			var tmp = "Bridge the call to a destination. <br />";
			tmp += "sip uri (voicemail): sofia/internal/*98@${domain}<br />\n";
			tmp += "sip uri (external number): sofia/gateway/gatewayname/12081231234<br />\n";
			tmp += "sip uri (hunt group): sofia/internal/7002@${domain}<br />\n";
			tmp += "sip uri (auto attendant): sofia/internal/5002@${domain}<br />\n";
			//tmp += "sip uri (user): /user/1001@${domain}<br />\n";
			document.getElementById("desc_action_data_1").innerHTML = tmp;
		}
		else if (field_value == "global_set") {
			document.getElementById("desc_action_data_1").innerHTML = "Sets a global variable. data: var1=1234";
		}
		else if (field_value == "javascript") {
			document.getElementById("desc_action_data_1").innerHTML = "Direct the call to a javascript file. data: disa.js";
		}
		else if (field_value == "set") {
			document.getElementById("desc_action_data_1").innerHTML = "Sets a variable. data: var2=1234";
		}
		else if (field_value == "voicemail") {
			document.getElementById("desc_action_data_1").innerHTML = "Send the call to voicemail. data: default ${domain} 1001";
		}
		else {
			document.getElementById("desc_action_data_1").innerHTML = "";
		}
	}
	if (field_type == "anti_action_application_1") {
		if (field_value == "transfer") {
			document.getElementById("desc_anti_action_data_1").innerHTML = "Transfer the call through the dialplan to the destination. data: 1001 XML default";
		}
		else if (field_value == "bridge") {
			var tmp = "Bridge the call to a destination. <br />";
			tmp += "sip uri (voicemail): sofia/internal/*98@${domain}<br />\n";
			tmp += "sip uri (external number): sofia/gateway/gatewayname/12081231234<br />\n";
			tmp += "sip uri (hunt group): sofia/internal/7002@${domain}<br />\n";
			tmp += "sip uri (auto attendant): sofia/internal/5002@${domain}<br />\n";
			//tmp += "sip uri (user): /user/1001@${domain}<br />\n";
			document.getElementById("desc_anti_action_data_1").innerHTML = tmp;
		}
		else if (field_value == "global_set") {
			document.getElementById("desc_anti_action_data_1").innerHTML = "Sets a global variable. data: var1=1234";
		}
		else if (field_value == "javascript") {
			document.getElementById("desc_anti_action_data_1").innerHTML = "Direct the call to a javascript file. data: disa.js";
		}
		else if (field_value == "set") {
			document.getElementById("desc_anti_action_data_1").innerHTML = "Sets a variable. data: var2=1234";
		}
		else if (field_value == "voicemail") {
			document.getElementById("desc_anti_action_data_1").innerHTML = "Send the call to voicemail. data: default ${domain} 1001";
		}
		else {
			document.getElementById("desc_anti_action_data_1").innerHTML = "";
		}
	}

}
-->
</script>

<?php
echo "<div align='center'>";
echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

echo "<tr class='border'>\n";
echo "	<td align=\"left\">\n";

echo "<form method='post' name='frm' action=''>\n";
echo "<div align='center'>\n";

echo " 	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "	<tr>\n";
echo "		<td align='left'><span class=\"vexpl\"><span class=\"red\"><strong>Time Conditions\n";
echo "			</strong></span></span>\n";
echo "		</td>\n";
echo "		<td align='right'>\n";
echo "			<input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_dialplan_includes.php'\" value='Back'>\n";
echo "		</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td align='left' colspan='2'>\n";
echo "			<span class=\"vexpl\">\n";
echo "			Time conditions route calls based on time conditions. You can use time conditions to \n";
echo "			send calls to gateways, auto attendants, external numbers, to scripts, or any destination.\n";
echo "			</span>\n";
echo "		</td>\n";
echo "	</tr>\n";
echo "	</table>";

echo "<br />\n";
echo "<br />\n";

echo "<table width='100%' border='0' cellpadding='6' cellspacing='0'>\n";

echo "<tr>\n";
echo "<td width='20%' class='vncellreq' valign='top' align='left' nowrap>\n";
echo "    Name:\n";
echo "</td>\n";
echo "<td width='80%' class='vtable' align='left'>\n";
echo "    <input class='formfld' style='width: 60%;' type='text' name='extension_name' maxlength='255' value=\"$extension_name\">\n";
echo "	<br />\n";
echo "	Enter the name for the time condition.\n";
echo "<br />\n";
echo "\n";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='vncell' valign='top' align='left' nowrap>\n";
echo "	Extension:\n";
echo "</td>\n";
echo "<td class='vtable' align='left'>\n";
echo "	<input class='formfld' style='width: 60%;' type='text' name='extension_number' id='extension_number' maxlength='255' value=\"$extension_number\">\n";
echo "	<br />\n";
echo "	Enter the extension number.<br />\n";
echo "</td>\n";
echo "</tr>\n";

//echo "<tr>\n";
//echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
//echo "    Continue:\n";
//echo "</td>\n";
//echo "<td class='vtable' align='left'>\n";
//echo "    <select class='formfld' name='extensioncontinue' style='width: 60%;'>\n";
//echo "    <option value=''></option>\n";
//if ($extensioncontinue == "true") { 
//	echo "    <option value='true' SELECTED >true</option>\n";
//}
//else {
//	echo "    <option value='true'>true</option>\n";
//}
//if ($extensioncontinue == "false") { 
//	echo "    <option value='false' SELECTED >false</option>\n";
//}
//else {
//	echo "    <option value='false'>false</option>\n";
//}
//echo "    </select>\n";
//echo "<br />\n";
//echo "Extension Continue in most cases this is false. default: false\n";
//echo "</td>\n";
//echo "</tr>\n";

echo "<tr>\n";
echo "<td class='vncell' valign='top' align='left' nowrap>\n";
echo "    Template:\n";
echo "</td>\n";
echo "<td class='vtable' align='left'>\n";
echo "	<select class='formfld' name='template2' id='template' onchange='template_onchange(this);' style='width: 60%;'>\n";
echo "		<option value=''></option>\n";
echo "	<optgroup label='Office'>\n";
echo "		<option value='Office Hours Mon-Fri 8am-5pm'>Office Hours Mon-Fri 8am-5pm</option>\n";
echo "		<option value='Office Hours Mon-Fri 9am-6pm'>Office Hours Mon-Fri 9am-6pm</option>\n";
echo "	</optgroup>\n";
echo "	<optgroup label='US Holidays'>\n";
echo "		<option value=\"New Year's Day\">New Year's Day</option>\n";
echo "		<option value='Martin Luther King Jr Day'>Martin Luther King Jr Day</option>\n";
echo "		<option value='Presidents Day'>Presidents Day</option>\n";
echo "		<option value='Memorial Day'>Memorial Day</option>\n";
echo "		<option value='Independence Day'>Independence Day</option>\n";
echo "		<option value='Labor Day'>Labor Day</option>\n";
echo "		<option value='Columbus Day'>Columbus Day</option>\n";
echo "		<option value=\"Veteran's Day\">Veteran's Day</option>\n";
echo "		<option value='Thanksgiving'>Thanksgiving</option>\n";
echo "		<option value='Christmas'>Christmas</option>\n";
echo "	</optgroup>\n";
echo "</select>\n";
echo "<br />\n";
echo "The templates provides a list of preset time conditions.\n";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='vncell' valign='top' align='left' nowrap>\n";
echo "	Day of Month:\n";
echo "</td>\n";
echo "<td class='vtable' align='left'>\n";
echo "	<input class='formfld' style='width: 60%;' type='text' name='condition_mday' id='condition_mday' maxlength='255' value=\"$condition_mday\">\n";
echo "	<br />\n";
echo "	Enter the day of the month. 1-31 <i>mday</i><br />\n";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='vncell' valign='top' align='left' nowrap>\n";
echo "	Day of Week:\n";
echo "</td>\n";
echo "<td class='vtable' align='left'>\n";
echo "	<input class='formfld' style='width: 60%;' type='text' name='condition_wday' id='condition_wday' maxlength='255' value=\"$condition_wday\">\n";
echo "	<br />\n";
echo "	Enter the day of the week. 1-7 (Sun=1, Mon=2, Tues=3) <i>wday</i>\n";
echo "	<br />\n";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='vncell' valign='top' align='left' nowrap>\n";
echo "	Minute of Day:\n";
echo "</td>\n";
echo "<td class='vtable' align='left'>\n";
echo "	<input class='formfld' style='width: 60%;' type='text' name='condition_minute_of_day' id='condition_minute_of_day' maxlength='255' value=\"$condition_minute_of_day\">\n";
echo "	<br />\n";
echo "	Enter the minute of the day. 1-1440 (midnight = 1, 8am=480, 9am=540, 6pm=1080) <i>minute-of-day</i><br />\n";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='vncell' valign='top' align='left' nowrap>\n";
echo "	Month:\n";
echo "</td>\n";
echo "<td class='vtable' align='left'>\n";
echo "	<input class='formfld' style='width: 60%;' type='text' name='condition_mon' id='condition_mon' maxlength='255' value=\"$condition_mon\">\n";
echo "	<br />\n";
echo "	Enter the month. 1-12 (Jan=1, Feb=2, Mar=3, April=4, May=5, Jun=6, July=7 etc.) <i>mon</i><br />\n";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='vncell' valign='top' align='left' nowrap>\n";
echo "	Week of Month:\n";
echo "</td>\n";
echo "<td class='vtable' align='left'>\n";
echo "	<input class='formfld' style='width: 60%;' type='text' name='condition_mweek' id='condition_mweek' maxlength='255' value=\"$condition_mweek\">\n";
echo "	<br />\n";
echo "	Enter the week of the month. 1-6 <i>mweek</i><br />\n";
echo "	<br />\n";
echo "</td>\n";
echo "</tr>\n";

//begin: showadvanced
	echo "<tr>\n";
	echo "<td style='padding: 0px;' colspan='2' class='' valign='top' align='left' nowrap>\n";

	echo "	<div id=\"showadvancedbox\">\n";
	echo "		<table width=\"100%\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";
	echo "		<tr>\n";
	echo "		<td width=\"20%\" valign=\"top\" class=\"vncell\">Show Advanced:</td>\n";
	echo "		<td width=\"80%\" class=\"vtable\">\n";
	echo "			<input type=\"button\" class='btn' onClick=\"show_advanced_config()\" value=\"Advanced\"></input></a>\n";
	echo "		</td>\n";
	echo "		</tr>\n";
	echo "		</table>\n";
	echo "	</div>\n";

	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td style='padding: 0px;' colspan='2' class='' valign='top' align='left' nowrap>\n";

	echo "	<div id=\"showadvanced\" style=\"display:none\">\n";
	echo "	<table width=\"100%\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";

	echo "<tr>\n";
	echo "<td width='20%' class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Day of Year:\n";
	echo "</td>\n";
	echo "<td width='80%' class='vtable' align='left'>\n";
	echo "	<input class='formfld' style='width: 60%;' type='text' name='condition_yday' id='condition_yday' maxlength='255' value=\"$condition_yday\">\n";
	echo "	<br />\n";
	echo "	Enter the day of the year. 1-365 <i>yday</i>\n";
	echo "	<br />\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Hour:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' style='width: 60%;' type='text' name='condition_hour' id='condition_hour' maxlength='255' value=\"$condition_hour\">\n";
	echo "	<br />\n";
	echo "	Enter the hour. 0-23 <i>hour</i>\n";
	echo "	<br />\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Minute:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' style='width: 60%;' type='text' name='condition_minute' id='condition_minute' maxlength='255' value=\"$condition_minute\">\n";
	echo "	<br />\n";
	echo "	Enter the minute. 0-59 <i>minute</i>\n";
	echo "	<br />\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Week:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' style='width: 60%;' type='text' name='condition_week' id='condition_week' maxlength='255' value=\"$condition_week\">\n";
	echo "	<br />\n";
	echo "	Enter the week. 1-52 <i>week</i>\n";
	echo "	<br />\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Year:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' style='width: 60%;' type='text' name='condition_year' id='condition_year' maxlength='255' value=\"$condition_year\">\n";
	echo "	<br />\n";
	echo "	Enter the year. 0-9999 <i>year</i>\n";
	echo "	<br />\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "	</table>\n";
	echo "	</div>";

	echo "</td>\n";
	echo "</tr>\n";
//end: showadvanced


echo "<tr>\n";
echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
echo "    Action when True:\n";
echo "</td>\n";
echo "<td class='vtable' align='left'>\n";

//switch_select_destination(select_type, select_label, select_name, select_value, select_style, $action);
switch_select_destination("dialplan", $action_1, "action_1", $action_1, "width: 60%;", "");

/*
echo "	<table style='width: 60%;' border='0' >\n";
echo "	<tr>\n";
echo "	<td style='width: 62px;'>Application: </td>\n";
echo "	<td style='width: 35%;'>\n";
echo "    <select class='formfld' style='width:100%' id='action_application_1' name='action_application_1' onchange='type_onchange(\"action_application_1\");'>\n";
echo "    <option value=''></option>\n";
if (strlen($action_application_1) > 0) {
	echo "    <option value='$action_application_1' selected>$action_application_1</option>\n";
}
echo "    <option value='answer'>answer</option>\n";
echo "    <option value='bridge'>bridge</option>\n";
echo "    <option value='cond'>cond</option>\n";
echo "    <option value='db'>db</option>\n";
echo "    <option value='global_set'>global_set</option>\n";
echo "    <option value='group'>group</option>\n";
echo "    <option value='expr'>expr</option>\n";
echo "    <option value='export'>export</option>\n";
echo "    <option value='hangup'>hangup</option>\n";
echo "    <option value='info'>info</option>\n";
echo "    <option value='javascript'>javascript</option>\n";
echo "    <option value='read'>read</option>\n";
echo "    <option value='reject'>reject</option>\n";
echo "    <option value='playback'>playback</option>\n";
echo "    <option value='respond'>respond</option>\n";
echo "    <option value='ring_ready'>ring_ready</option>\n";
echo "    <option value='set'>set</option>\n";
echo "    <option value='set_user'>set_user</option>\n";
echo "    <option value='sleep'>sleep</option>\n";
echo "    <option value='sofia_contact'>sofia_contact</option>\n";
echo "    <option value='transfer'>transfer</option>\n";
echo "    <option value='voicemail'>voicemail</option>\n";
echo "    <option value='conference'>conference</option>\n";
echo "    <option value='conference_set_auto_outcall'>conference_set_auto_outcall</option>\n";
echo "    </select><br />\n";
echo "	</td>\n";
echo "	<td style='width: 73px;'>\n";
echo "		&nbsp; Data: \n";
echo "	</td>\n";
echo "	<td>\n";
echo "		<input class='formfld' style='width: 100%;' type='text' name='action_data_1' maxlength='255' value=\"$action_data_1\">\n";
echo "	</td>\n";
echo "	</tr>\n";
echo "	</table>\n";
*/
//echo "	<div id='desc_action_data_1'></div>\n";
echo "</td>\n";
echo "</tr>\n";

echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='vncell' valign='top' align='left' nowrap>\n";
echo "    Action when False:\n";
echo "</td>\n";
echo "<td class='vtable' align='left'>\n";

//switch_select_destination(select_type, select_label, select_name, select_value, select_style, $action);
switch_select_destination("dialplan", $anti_action_1, "anti_action_1", $anti_action_1, "width: 60%;", "");

/*
echo "	<table style='width: 60%;' border='0' >\n";
echo "	<tr>\n";
echo "	<td style='width: 62px;'>Application: </td>\n";
echo "	<td style='width: 35%;'>\n";
echo "    <select class='formfld' style='width:100%' id='anti_action_application_1' name='anti_action_application_1' onchange='type_onchange(\"anti_action_application_1\");'>\n";
echo "    <option value=''></option>\n";
if (strlen($anti_action_application_1) > 0) {
	echo "    <option value='$anti_action_application_1' selected>$anti_action_application_1</option>\n";
}
echo "    <option value='answer'>answer</option>\n";
echo "    <option value='bridge'>bridge</option>\n";
echo "    <option value='cond'>cond</option>\n";
echo "    <option value='db'>db</option>\n";
echo "    <option value='global_set'>global_set</option>\n";
echo "    <option value='group'>group</option>\n";
echo "    <option value='expr'>expr</option>\n";
echo "    <option value='export'>export</option>\n";
echo "    <option value='hangup'>hangup</option>\n";
echo "    <option value='info'>info</option>\n";
echo "    <option value='javascript'>javascript</option>\n";
echo "    <option value='read'>read</option>\n";
echo "    <option value='reject'>reject</option>\n";
echo "    <option value='playback'>playback</option>\n";
echo "    <option value='respond'>respond</option>\n";
echo "    <option value='ring_ready'>ring_ready</option>\n";
echo "    <option value='set'>set</option>\n";
echo "    <option value='set_user'>set_user</option>\n";
echo "    <option value='sleep'>sleep</option>\n";
echo "    <option value='sofia_contact'>sofia_contact</option>\n";
echo "    <option value='transfer'>transfer</option>\n";
echo "    <option value='voicemail'>voicemail</option>\n";
echo "    <option value='conference'>conference</option>\n";
echo "    <option value='conference_set_auto_outcall'>conference_set_auto_outcall</option>\n";
echo "    </select><br />\n";
echo "	</td>\n";
echo "	<td style='width: 73px;'>\n";
echo "		&nbsp; Data: \n";
echo "	</td>\n";
echo "	<td>\n";
echo "		<input class='formfld' style='width: 100%;' type='text' name='anti_action_data_1' maxlength='255' value=\"$anti_action_data_1\">\n";
echo "	</td>\n";
echo "	</tr>\n";
echo "	</table>\n";
*/
echo "	<div id='desc_anti_action_data_1'></div>\n";
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

//include the footer
	require_once "includes/footer.php";

?>
