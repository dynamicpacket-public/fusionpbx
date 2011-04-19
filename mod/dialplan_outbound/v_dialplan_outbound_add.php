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
if (ifgroup("admin") || ifgroup("superadmin")) {
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


//get the http post values and set theme as php variables
	if (count($_POST)>0) {
		$extension_name = check_str($_POST["extension_name"]);
		$gateway = check_str($_POST["gateway"]);
		$gateway_array = explode(":",$gateway);
		$gateway_id = $gateway_array[0];
		$gateway_name = $gateway_array[1];
		$dialplanorder = check_str($_POST["dialplanorder"]);
		$dialplan_expression = check_str($_POST["dialplan_expression"]);
		$condition_field_1 = check_str($_POST["condition_field_1"]);
		$condition_expression_1 = check_str($_POST["condition_expression_1"]);
		$condition_field_2 = check_str($_POST["condition_field_2"]);
		$condition_expression_2 = check_str($_POST["condition_expression_2"]);
		$enabled = check_str($_POST["enabled"]);
		$description = check_str($_POST["description"]);
		if (strlen($enabled) == 0) { $enabled = "true"; } //set default to enabled
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {
	//check for all required data
		if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		if (strlen($gateway_name) == 0) { $msg .= "Please provide: Gateway Name<br>\n"; }
		if (strlen($dialplan_expression) == 0) { $msg .= "Please provide: Dialplan Expression<br>\n"; }
		//if (strlen($extension_name) == 0) { $msg .= "Please provide: Extension Name<br>\n"; }
		//if (strlen($condition_field_1) == 0) { $msg .= "Please provide: Condition Field<br>\n"; }
		//if (strlen($condition_expression_1) == 0) { $msg .= "Please provide: Condition Expression<br>\n"; }
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

	if (strlen(trim($_POST['dialplan_expression']))> 0) {

		$sql = "";
		$sql .= "select * from v_settings ";
		$sql .= "where v_id = '$v_id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$default_area_code = $row["default_area_code"];
			break;
		}

		$tmp_array = explode("\n", $_POST['dialplan_expression']);

		foreach($tmp_array as $dialplan_expression) {
			$dialplan_expression = trim($dialplan_expression);
			if (strlen($dialplan_expression)>0) {

				if (count($_SESSION["domains"]) > 1) {
					$tmp_gateway_name = $_SESSION['domains'][$v_id]['domain'] .'-'.$gateway_name;
				}
				else {
					$tmp_gateway_name = $gateway_name;
				}

				switch ($dialplan_expression) {
				case "^(\d{7})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/1".$default_area_code."\$1";
					$label = "7 digits";
					$abbrv = "7d";
					break;
				case "^(\d{8})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "8 digits";
					$abbrv = "8d";
					break;
				case "^(\d{9})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "9 digits";
					$abbrv = "9d";
					break;
				case "^(\d{10})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/1\$1";
					$label = "10 digits";
					$abbrv = "10d";
					break;
				case "^\+?(\d{11})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "11 digits";
					$abbrv = "11d";
					break;
				case "^(\d{12})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "12 digits";
					$abbrv = "12d";
					break;
				case "^(\d{13})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "13 digits";
					$abbrv = "13d";
					break;
				case "^(\d{14})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "14 digits";
					$abbrv = "14d";
					break;
				case "^(0\d{12,14})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "International";
					$abbrv = "Intl";
					break;
				case "^311$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/311";
					$label = "311";
					$abbrv = "311";
					break;
				case "^411$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/411";
					$label = "411";
					$abbrv = "411";
					break;
				case "^911$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/911";
					$label = "911";
					$abbrv = "911";
					break;
				case "^9(\d{3})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "dial 9, 3 digits";
					$abbrv = "9.3d";
					break;
				case "^9(\d{4})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "dial 9, 4 digits";
					$abbrv = "9.4d";
					break;	
				case "^9(\d{7})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/1".$default_area_code."\$1";
					$label = "dial 9, 7 digits";
					$abbrv = "9.7d";
					break;
				case "^9(\d{10})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/1\$1";
					$label = "dial 9, 10 digits";
					$abbrv = "9.10d";
					break;
				case "^9(\d{11})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "dial 9, 11 digits";
					$abbrv = "9.11d";
					break;
				case "^9(\d{12})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "dial 9, International";
					$abbrv = "9.12d";
					break;
				case "^9(\d{13})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "dial 9, International";
					$abbrv = "9.13d";
					break;
				case "^9(\d{14})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "dial 9, International";
					break;
				case "^9(\d{15})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "dial 9, International";
					$abbrv = "9.15d";
					break;
				case "^1?(8(00|55|66|77|88)[2-9]\d{6})$":
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = "toll free";
					$abbrv = "tollfree";
					break;
				default:
					$action_data = "sofia/gateway/".$tmp_gateway_name."/\$1";
					$label = $dialplan_expression;
					$abbrv = filename_safe($dialplan_expression);
					//echo "abbrv: $abbrv<br />\n";
					//exit;
				}

				$extension_name = $gateway_name.".".$abbrv;
				if (strlen($dialplanorder) == 0) {
					$dialplanorder ='999';
				}
				$context = 'default';
				$enabled = 'true';
				$opt1name = 'gateway_id';
				$opt1value = $gateway_id;
				//$dialplan_include_id = v_dialplan_includes_add($v_id, $extensionname, $dialplanorder, $context, $enabled, $description, $opt1name, $opt1value);

				//add the main dialplan include entry
					$sql = "insert into v_dialplan_includes ";
					$sql .= "(";
					$sql .= "v_id, ";
					$sql .= "extensionname, ";
					$sql .= "dialplanorder, ";
					$sql .= "extensioncontinue, ";
					$sql .= "context, ";
					$sql .= "opt1name, ";
					$sql .= "opt1value, ";
					$sql .= "enabled, ";
					$sql .= "descr ";
					$sql .= ") ";
					$sql .= "values ";
					$sql .= "(";
					$sql .= "'$v_id', ";
					$sql .= "'$extension_name', ";
					$sql .= "'$dialplanorder', ";
					$sql .= "'$enabled', ";
					$sql .= "'$context', ";
					$sql .= "'$opt1name', ";
					$sql .= "'$opt1value', ";
					$sql .= "'$enabled', ";
					$sql .= "'$description' ";
					$sql .= ")";
					if ($v_debug) {
						echo $sql."<br />";
					}
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

				$tag = 'condition'; //condition, action, antiaction
				$fieldtype = 'destination_number';
				$fielddata = $dialplan_expression;
				$fieldorder = '005';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

				$tag = 'action'; //condition, action, antiaction
				$fieldtype = 'set';
				$fielddata = 'sip_h_X-accountcode=${accountcode}';
				$fieldorder = '010';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

				$tag = 'action'; //condition, action, antiaction
				$fieldtype = 'set';
				$fielddata = 'call_direction=outbound';
				$fieldorder = '010';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

				$tag = 'action'; //condition, action, antiaction
				$fieldtype = 'set';
				$fielddata = 'hangup_after_bridge=true';
				$fieldorder = '015';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

				$tag = 'action'; //condition, action, antiaction
				$fieldtype = 'set';
				$fielddata = 'effective_caller_id_name=${outbound_caller_id_name}';
				$fieldorder = '020';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

				$tag = 'action'; //condition, action, antiaction
				$fieldtype = 'set';
				$fielddata = 'effective_caller_id_number=${outbound_caller_id_number}';
				$fieldorder = '025';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

				$tag = 'action'; //condition, action, antiaction
				$fieldtype = 'bridge';
				$fielddata = $action_data;
				$fieldorder = '030';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

				unset($label);
				unset($abbrv);
				unset($dialplan_expression);
				unset($action_data);
			} //if strlen
		} //end for each

		//synchronize the xml config
			sync_package_v_dialplan_includes();
		
		//changes in the dialplan may affect routes in the hunt groups
			sync_package_v_hunt_group();
	}

	//commit the atomic transaction
		$count = $db->exec("COMMIT;"); //returns affected rows

	//synchronize the xml config
		sync_package_v_dialplan_includes();

	//redirect the user
		require_once "includes/header.php";
		echo "<meta http-equiv=\"refresh\" content=\"2;url=v_dialplan_outbound.php\">\n";
		echo "<div align='center'>\n";
		echo "Update Complete\n";
		echo "</div>\n";
		require_once "includes/footer.php";
		return;
} //end if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0)

?><script type="text/javascript">
<!--
function type_onchange(field_type) {
	var field_value = document.getElementById(field_type).value;

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

-->
</script>

<?php
echo "<div align='center'>";
echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

echo "<tr class='border'>\n";
echo "	<td align=\"left\">\n";
echo "		<br>";

echo "<form method='post' name='frm' action=''>\n";
echo "<div align='center'>\n";

echo " 	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "	<tr>\n";
echo "		<td align='left'><span class=\"vexpl\"><span class=\"red\"><strong>Outbound Routes\n";
echo "			</strong></span></span>\n";
echo "		</td>\n";
echo "		<td align='right'>\n";
echo "			<input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_dialplan_outbound.php'\" value='Back'>\n";
echo "		</td>\n";
echo "	</tr>\n";
echo "	<tr>\n";
echo "		<td align='left' colspan='2'>\n";
echo "			<span class=\"vexpl\">\n";
echo "				Outbound dialplans have one or more conditions that are matched to attributes of a call. \n";
echo "				When a call matches the conditions the call is then routed to the gateway.\n";
echo "			</span>\n";
echo "		</td>\n";
echo "	</tr>\n";
echo "	</table>";

echo "<br />\n";
echo "<br />\n";

echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";

echo "<tr>\n";
echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
echo "    Gateway:\n";
echo "</td>\n";
echo "<td class='vtable' align='left'>\n";
$sql = "";
$sql .= " select * from v_gateways ";
$sql .= " where v_id = '$v_id' ";
$prepstatement = $db->prepare(check_sql($sql));
$prepstatement->execute();
$result = $prepstatement->fetchAll();
unset ($prepstatement, $sql);
echo "<select name=\"gateway\" id=\"gateway\" class=\"formfld\" style='width: 60%;'>\n";
echo "<option value=''></option>\n";
foreach($result as $row) {
	if ($row['gateway'] == $gateway_name) {
		echo "<option value=\"".$row['gateway_id'].":".$row['gateway']."\" selected>".$row['gateway']."</option>\n";
	}
	else {
		echo "<option value=\"".$row['gateway_id'].":".$row['gateway']."\">".$row['gateway']."</option>\n";
	}
}
unset($sql, $result, $rowcount);
echo "</select>\n";
echo "<br />\n";
echo "Select the gateway to use with this outbound route.\n";
echo "</td>\n";
echo "</tr>\n";


echo "<tr>\n";
echo "  <td valign=\"top\" class=\"vncellreq\">Dialplan Expression</td>\n";
echo "  <td align='left' class=\"vtable\">";
echo "    <textarea name=\"dialplan_expression\" id=\"dialplan_expression\" class=\"formfld\" style='width: 60%;' cols=\"30\" rows=\"4\" wrap=\"off\"></textarea>\n";
echo "    <br>\n";
echo "    <select name='dialplan_expression_select' id='dialplan_expression_select' onchange=\"document.getElementById('dialplan_expression').value += document.getElementById('dialplan_expression_select').value + '\\n';\" class='formfld' style='width: 60%;'>\n";
echo "    <option></option>\n";
echo "    <option value='^(\\d{2})\$'>2 digits</option>\n";
echo "    <option value='^(\\d{3})\$'>3 digits</option>\n";
echo "    <option value='^(\\d{4})\$'>4 digits</option>\n";
echo "    <option value='^(\\d{5})\$'>5 digits</option>\n";
echo "    <option value='^(\\d{6})\$'>6 digits</option>\n";
echo "    <option value='^(\\d{7})\$'>7 digits local</option>\n";
echo "    <option value='^(\\d{8})\$'>8 digits</option>\n";
echo "    <option value='^(\\d{9})\$'>9 digits</option>\n";
echo "    <option value='^(\\d{10})\$'>10 digits long distance</option>\n";
echo "    <option value='^\+?(\\d{11})\$'>11 digits long distance</option>\n";
echo "    <option value='^(\\d{12})\$'>12 digits</option>\n";
echo "    <option value='^(\\d{13})\$'>13 digits</option>\n";
echo "    <option value='^(\\d{14})\$'>14 digits</option>\n";
echo "    <option value='^(\\d{15})\$'>15 digits International</option>\n";
echo "    <option value='^311\$'>311 information</option>\n";
echo "    <option value='^411\$'>411 information</option>\n";
echo "    <option value='^911\$'>911 emergency</option>\n";
echo "    <option value='^1?(8(00|55|66|77|88)[2-9]\\d{6})\$'>toll free</option>\n";
echo "    <option value='^9(\\d{2})\$'>Dial 9 then 2 digits</option>\n";
echo "    <option value='^9(\\d{3})\$'>Dial 9 then 3 digits</option>\n";
echo "    <option value='^9(\\d{4})\$'>Dial 9 then 4 digits</option>\n";
echo "    <option value='^9(\\d{5})\$'>Dial 9 then 5 digits</option>\n";
echo "    <option value='^9(\\d{6})\$'>Dial 9 then 6 digits</option>\n";
echo "    <option value='^9(\\d{7})\$'>Dial 9 then 7 digits</option>\n";
echo "    <option value='^9(\\d{8})\$'>Dial 9 then 8 digits</option>\n";
echo "    <option value='^9(\\d{9})\$'>Dial 9 then 9 digits</option>\n";
echo "    <option value='^9(\\d{10})\$'>Dial 9 then 10 digits</option>\n";
echo "    <option value='^9(\\d{11})\$'>Dial 9 then 11 digits</option>\n";
echo "    <option value='^9(\\d{12})\$'>Dial 9 then 12 digits</option>\n";
echo "    <option value='^9(\\d{13})\$'>Dial 9 then 13 digits</option>\n";
echo "    <option value='^9(\\d{14})\$'>Dial 9 then 14 digits</option>\n";
echo "    <option value='^9(\\d{15})\$'>Dial 9 then 15 digits</option>\n";
echo "    </select>\n";
echo "    <span class=\"vexpl\">\n";
echo "    <br />\n";
echo "    Shortcut to create the outbound dialplan entries for this Gateway. \n";
echo "    </span></td>\n";
echo "</tr>";

/*
echo "<tr>\n";
echo "<td class='vncell' valign='top' align='left' nowrap>\n";
echo "	Condition 1:\n";
echo "</td>\n";
echo "<td class='vtable' align='left'>\n";
echo "	<table style='width: 60%;' border='0'>\n";
echo "	<tr>\n";
echo "	<td style='width: 62px;'>Field</td>\n";
echo "	<td style='width: 35%;'>\n";
echo "    <select class='formfld' name='condition_field_1' id='condition_field_1' onchange='type_onchange(\"condition_field_1\");' style='width:100%'>\n";
echo "    <option value=''></option>\n";
if (strlen($condition_field_1) > 0) {
	echo "    <option value='$condition_field_1' selected>$condition_field_1</option>\n";
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
echo "	<td style='width: 73px;'>&nbsp; Expression</td>\n";
echo "	<td>\n";
echo "		<input class='formfld' type='text' name='condition_expression_1' maxlength='255' style='width:100%' value=\"$condition_expression_1\">\n";
echo "	</td>\n";
echo "	</tr>\n";
echo "	</table>\n";
echo "	<div id='desc_condition_expression_1'></div>\n";
echo "	The condition is optional. It is used to set additional conditons on the outbound route restricting the route to a more specific use. ";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='vncell' valign='top' align='left' nowrap>\n";
echo "	Condition 2\n";
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
echo "	</select><br />\n";
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
*/

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
echo "Select the order number. The order number determines the order of the outbound routes when there is more than one.\n";
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
echo "Choose to enable or disable the outbound route.\n";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class='vncell' valign='top' align='left' nowrap>\n";
echo "    Description:\n";
echo "</td>\n";
echo "<td colspan='4' class='vtable' align='left'>\n";
echo "    <input class='formfld' style='width: 60%;' type='text' name='description' maxlength='255' value=\"$description\">\n";
echo "<br />\n";
echo "Enter a description for the outbound route.\n";
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "	<td colspan='5' align='right'>\n";
if ($action == "update") {
	echo "		<input type='hidden' name='dialplan_include_id' value='$dialplan_include_id'>\n";
}
echo "		<input type='submit' name='submit' class='btn' value='Save'>\n";
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


require_once "includes/footer.php";
unset ($resultcount);
unset ($result);
unset ($key);
unset ($val);
unset ($c);
?>
