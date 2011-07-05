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
if (permission_exists('conferences_active_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//get the http get or post and set it as php variables
	$conference_name = trim($_REQUEST["c"]);

//check if the domain in the conference name matches the domain
	if (ifgroup("superadmin")) {
		//access granted
	}
	else {
		//find the conference extensions from the dialplan include details
			$sql = "";
			$sql .= "select * from v_dialplan_includes_details ";
			$sql .= "where v_id = '$v_id' ";
			if (!(ifgroup("admin") || ifgroup("superadmin"))) {
				//find the assigned users
					$sql .= "and fielddata like 'conference_user_list%' and fielddata like '%|".$_SESSION['username']."|%' ";
			}
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$x = 0;
			$result = $prepstatement->fetchAll();
			$conference_array = array ();
			foreach ($result as &$row) {
				$dialplan_include_id = $row["dialplan_include_id"];
				//$tag = $row["tag"];
				//$fieldorder = $row["fieldorder"];
				$fieldtype = $row["fieldtype"];
				//$fielddata = $row["fielddata"];
				if (ifgroup("admin") || ifgroup("superadmin")) {
					if ($fieldtype == "conference") {
						$conference_array[$x]['dialplan_include_id'] = $dialplan_include_id;
						$x++;
					}
				}
				else {
					$conference_array[$x]['dialplan_include_id'] = $dialplan_include_id;
					$x++;
				}
			}
			unset ($prepstatement);

		//find if the user is in the admin or superadmin group or has been assigned to this conference
			if (ifgroup("admin") || ifgroup("superadmin")) {
				//allow admin and superadmin access to all conference rooms
			}
			else {
				//get the list of conference numbers the user is assigned to
				$sql = "";
				$sql .= " select * from v_dialplan_includes_details ";
				$x = 0;
				foreach ($conference_array as &$row) {
					if ($x == 0) {
						$sql .= "where v_id = '$v_id' \n";
						$sql .= "and dialplan_include_id = '".$row['dialplan_include_id']."' \n";
						$sql .= "and fieldtype = 'conference' \n";
						$sql .= "and fielddata like '".$conference_name."%' \n";
					}
					else {
						$sql .= "or v_id = '$v_id' \n";
						$sql .= "and dialplan_include_id = '".$row['dialplan_include_id']."' \n";
						$sql .= "and fieldtype = 'conference' \n";
						$sql .= "and fielddata like '".$conference_name."%' \n";
					}
					$x++;
				}
				$prepstatement = $db->prepare(check_sql($sql));
				$prepstatement->execute();
				$result = $prepstatement->fetchAll();
				$result_count = count($result);
				unset ($prepstatement, $sql);
				if ($result_count == 0) { //no results
					echo "access denied";
					exit;
				}
			}
	}

//replace the space with underscore
	$conference_name = str_replace("_", " ", $conference_name);
	$conference_name = $conference_name.'-'.$_SESSION['domains'][$v_id]['domain'];

//create the conference list command
	$switch_cmd = "conference '".$conference_name."' xml_list";

//connect to event socket, send the command and process the results
	$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
	if (!$fp) {
		$msg = "<div align='center'>Connection to Event Socket failed.<br /></div>"; 
		echo "<div align='center'>\n";
		echo "<table width='40%'>\n";
		echo "<tr>\n";
		echo "<th align='left'>Message</th>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td class='rowstyle1'><strong>$msg</strong></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
	}
	else {
		//show the content
		$xml_str = trim(event_socket_request($fp, 'api '.$switch_cmd));
		try {
			$xml = new SimpleXMLElement($xml_str);
		}
		catch(Exception $e) {
			//echo $e->getMessage();
		}
		//$name = $xml->conference['name'];
		$member_count = $xml->conference['member-count'];
		$locked = $xml->conference['locked'];

		$c = 0;
		$rowstyle["0"] = "rowstyle0";
		$rowstyle["1"] = "rowstyle1";

		echo "<div id='cmd_reponse'>\n";
		echo "</div>\n";

		echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "<tr>\n";
		echo "<td >\n";
		echo "	<strong>Count: $member_count</strong>\n";
		echo "</td>\n";
		echo "<td colspan='7'>\n";
		echo "	&nbsp;\n";
		echo "</td>\n";
		echo "<td colspan='1' align='right'>\n";
		echo "	<strong>Conference Tools:</strong> \n";
		echo "	<a href='javascript:void(0);' onclick=\"record_count++;send_cmd('v_conference_exec.php?cmd=conference%20".$conference_name." record recordings/conference_".$conference_name."-'+document.getElementById('time_stamp').innerHTML+'_'+record_count+'.wav');\">Start Record</a>&nbsp;\n";
		echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?cmd=conference%20".$conference_name." norecord recordings/conference_".$conference_name."-'+document.getElementById('time_stamp').innerHTML+'_'+record_count+'.wav');\">Stop Record</a>&nbsp;\n";
		if ($locked == "true") {
			echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?cmd=conference%20".$conference_name." unlock');\">Unlock</a>&nbsp;\n";
		}
		else {
			echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?cmd=conference%20".$conference_name." lock');\">Lock</a>&nbsp;\n";
		}
		echo "</td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo "<th>ID</th>\n";
		//echo "<th>UUID</th>\n";
		echo "<th>Caller ID Name</th>\n";
		echo "<th>Caller ID Number</th>\n";
		echo "<th>Hear</th>\n";
		echo "<th>Speak</th>\n";
		echo "<th>Talking</th>\n";
		echo "<th>Video</th>\n";
		echo "<th>Has Floor</th>\n";
		echo "<th>Tools</th>\n";
		echo "</tr>\n";

		foreach ($xml->conference->members->member as $row) {
			$id = $row->id;
			$flag_can_hear = $row->flags->can_hear;
			$flag_can_speak = $row->flags->can_speak;
			$flag_talking = $row->flags->talking;
			$flag_has_video = $row->flags->has_video;
			$flag_has_floor = $row->flags->has_floor;
			$uuid = $row->uuid;
			$caller_id_name = $row->caller_id_name;
			$caller_id_name = str_replace("%20", " ", $caller_id_name);
			$caller_id_number = $row->caller_id_number;

			echo "<tr>\n";
			echo "<td valign='top' class='".$rowstyle[$c]."'>$id</td>\n";
			//echo "<td valign='top' class='".$rowstyle[$c]."'>$uuid</td>\n";
			echo "<td valign='top' class='".$rowstyle[$c]."'>$caller_id_name</td>\n";
			echo "<td valign='top' class='".$rowstyle[$c]."'>$caller_id_number</td>\n";
			if ($flag_can_hear == "true") {
				echo "<td valign='top' class='".$rowstyle[$c]."'>yes</td>\n";
			}
			else {
				echo "<td valign='top' class='".$rowstyle[$c]."'>no</td>\n";
			}
			if ($flag_can_speak == "true") {
				echo "<td valign='top' class='".$rowstyle[$c]."'>yes</td>\n";
			}
			else {
				echo "<td valign='top' class='".$rowstyle[$c]."'>no</td>\n";
			}
			if ($flag_talking == "true") {
				echo "<td valign='top' class='".$rowstyle[$c]."'>yes</td>\n";
			}
			else {
				echo "<td valign='top' class='".$rowstyle[$c]."'>no</td>\n";
			}
			if ($flag_has_video == "true") {
				echo "<td valign='top' class='".$rowstyle[$c]."'>yes</td>\n";
			}
			else {
				echo "<td valign='top' class='".$rowstyle[$c]."'>no</td>\n";
			}
			if ($flag_has_floor == "true") {
				echo "<td valign='top' class='".$rowstyle[$c]."'>yes</td>\n";
			}
			else {
				echo "<td valign='top' class='".$rowstyle[$c]."'>no</td>\n";
			}
			echo "<td valign='top' class='".$rowstyle[$c]."' style='text-align:right;'>\n";
			//energy
				echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?action=energy&direction=up&cmd=conference%20".$conference_name."%20energy%20".$id."');\">+energy</a>&nbsp;\n";
				echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?action=energy&direction=down&cmd=conference%20".$conference_name."%20energy%20".$id."');\">-energy</a>&nbsp;\n";
			//volume
				echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?action=volume_in&direction=up&cmd=conference%20".$conference_name."%20volume_in%20".$id."');\">+vol</a>&nbsp;\n";
				echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?action=volume_in&direction=down&cmd=conference%20".$conference_name."%20volume_in%20".$id."');\">-vol</a>&nbsp;\n";
				echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?action=volume_out&direction=up&cmd=conference%20".$conference_name."%20volume_out%20".$id."');\">+gain</a>&nbsp;\n";
				echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?action=volume_out&direction=down&cmd=conference%20".$conference_name."%20volume_out%20".$id."');\">-gain</a>&nbsp;\n";
			//mute and unmute
				if ($flag_can_speak == "true"){
					echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?cmd=conference%20".$conference_name."%20mute%20".$id."');\">mute</a>&nbsp;\n";
				}
				else {
					echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?cmd=conference%20".$conference_name."%20unmute%20".$id."');\">unmute</a>&nbsp;\n";
				}
			//deaf and undeaf
				if ($flag_can_hear == "true"){
					echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?cmd=conference%20".$conference_name."%20deaf%20".$id."');\">deaf</a>&nbsp;\n";
				}
				else {
					echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?cmd=conference%20".$conference_name."%20undeaf%20".$id."');\">undeaf</a>&nbsp;\n";
				}
			//kick someone from the conference
				echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?cmd=conference%20".$conference_name."%20kick%20".$id."');\">kick</a>&nbsp;\n";
			echo "	&nbsp;";
			echo "</td>\n";
			echo "</tr>\n";
			if ($c==0) { $c=1; } else { $c=0; }
		}
		echo "</table>\n";
	}
?>
