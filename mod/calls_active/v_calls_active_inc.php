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
if (ifgroup("superadmin")) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//$conference_name = trim($_REQUEST["c"]);
//$tmp_conference_name = str_replace("_", " ", $conference_name);

/*
API CALL [show(channels as xml)] output:
<result row_count="3">
  <row row_id="1">
    <uuid>64a0ea96-fc35-aa46-aaa1-b50319313fa5</uuid>
    <direction>inbound</direction>
    <created>2010-02-11 16:45:21</created>
    <created_epoch>1265931921</created_epoch>
    <name>sofia/internal/1003@markjcrane.dyndns.org</name>
    <state>CS_EXECUTE</state>
    <cid_name>1003</cid_name>
    <cid_num>1003</cid_num>
    <ip_addr>10.7.0.194</ip_addr>
    <dest>1001</dest>
    <application>bridge</application>
    <application_data>user/1001@markjcrane.dyndns.org</application_data>
    <dialplan>XML</dialplan>
    <context>default</context>
    <read_codec>PCMU</read_codec>
    <read_rate>8000</read_rate>
    <write_codec>PCMU</write_codec>
    <write_rate>8000</write_rate>
    <secure></secure>
  </row>
  <row row_id="2">
    <uuid>974ed012-0169-974c-9b58-fb538830a19c</uuid>
    <direction>outbound</direction>
    <created>2010-02-11 16:45:22</created>
    <created_epoch>1265931922</created_epoch>
    <name>sofia/internal/sip:1001@10.7.0.70;fs_nat=yes;fs_path=sip%3A1001%4010.7.0.70%3A5060</name>
    <state>CS_EXCHANGE_MEDIA</state>
    <cid_name>1003</cid_name>
    <cid_num>12084024632</cid_num>
    <ip_addr>10.7.0.194</ip_addr>
    <dest>sip:1001@10.7.0.70;fs_nat=yes;fs_path=sip%3A1001%4010.7.0.70%3A5060</dest>
    <application></application>
    <application_data></application_data>
    <dialplan>XML</dialplan>
    <context>default</context>
    <read_codec>PCMU</read_codec>
    <read_rate>8000</read_rate>
    <write_codec>PCMU</write_codec>
    <write_rate>8000</write_rate>
    <secure></secure>
  </row>
  <row row_id="3">
    <uuid>5ca89c63-3c73-dd40-88ca-d89f7bcd2a0e</uuid>
    <direction>inbound</direction>
    <created>2010-02-11 16:59:16</created>
    <created_epoch>1265932756</created_epoch>
    <name>sofia/internal/1004@markjcrane.dyndns.org</name>
    <state>CS_EXECUTE</state>
    <cid_name>1004</cid_name>
    <cid_num>1004</cid_num>
    <ip_addr>10.7.0.249</ip_addr>
    <dest>5002</dest>
    <application>set</application>
    <application_data>transfer_ringback=%(2000, 4000, 440.0, 480.0)</application_data>
    <dialplan>XML</dialplan>
    <context>default</context>
    <read_codec>PCMU</read_codec>
    <read_rate>8000</read_rate>
    <write_codec>PCMU</write_codec>
    <write_rate>8000</write_rate>
    <secure></secure>
  </row>
</result>
*/

$switch_cmd = 'show channels as xml';
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
	$xml_str = trim(event_socket_request($fp, 'api '.$switch_cmd));
	try {
		$xml = new SimpleXMLElement($xml_str);
	}
	catch(Exception $e) {
		//echo $e->getMessage();
	}

	// begin the session
	session_start();

	//get the extension information
	/*
		//if (count($_SESSION['extension_array']) == 0) {
			$sql = "";
			$sql .= "select * from v_extensions ";
			$sql .= "where v_id = '$v_id' ";
			$sql .= "order by extension asc ";
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$result = $prepstatement->fetchAll();
			foreach ($result as &$row) {
				$extension = $row["extension"];
				//echo $extension;
				$extension_array[$extension]['v_id'] = $row["v_id"];
				$extension_array[$extension]['extension'] = $row["extension"];

				//$extension_array[$extension]['password'] = $row["password"];
				$extension_array[$extension]['user_list'] = $row["user_list"];
				$extension_array[$extension]['mailbox'] = $row["mailbox"];
				//$vm_password = $row["vm_password"];
				//$vm_password = str_replace("#", "", $vm_password); //preserves leading zeros
				//$_SESSION['extension_array'][$extension]['vm_password'] = $vm_password;
				$extension_array[$extension]['accountcode'] = $row["accountcode"];
				$extension_array[$extension]['effective_caller_id_name'] = $row["effective_caller_id_name"];
				$extension_array[$extension]['effective_caller_id_number'] = $row["effective_caller_id_number"];
				$extension_array[$extension]['outbound_caller_id_name'] = $row["outbound_caller_id_name"];
				$extension_array[$extension]['outbound_caller_id_number'] = $row["outbound_caller_id_number"];
				$extension_array[$extension]['vm_enabled'] = $row["vm_enabled"];
				$extension_array[$extension]['vm_mailto'] = $row["vm_mailto"];
				$extension_array[$extension]['vm_attach_file'] = $row["vm_attach_file"];
				$extension_array[$extension]['vm_keep_local_after_email'] = $row["vm_keep_local_after_email"];
				$extension_array[$extension]['user_context'] = $row["user_context"];
				$extension_array[$extension]['callgroup'] = $row["callgroup"];
				$extension_array[$extension]['auth_acl'] = $row["auth_acl"];
				$extension_array[$extension]['cidr'] = $row["cidr"];
				$extension_array[$extension]['sip_force_contact'] = $row["sip_force_contact"];
				$extension_array[$extension]['enabled'] = $row["enabled"];
				$extension_array[$extension]['description'] = $row["description"];
				//break; //limit to 1 row
			}
			$_SESSION['extension_array'] = $extension_array;
		//}
		echo "<pre>\n";
		print_r($_SESSION['extension_array']);
		echo "</pre>\n";
	*/
	$c = 0;
	$rowstyle["0"] = "rowstyle0";
	$rowstyle["1"] = "rowstyle1";

	echo "<div id='cmd_reponse'>\n";
	echo "</div>\n";

	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr>\n";
	echo "<td >\n";
	//echo "	<strong>Count: $row_count</strong>\n";
	echo "</td>\n";
	echo "<td colspan='2'>\n";
	echo "	&nbsp;\n";
	echo "</td>\n";
	echo "<td colspan='1' align='right'>\n";

	/*
	echo "	<strong>Tools:</strong> \n";
	echo "	<a href='javascript:void(0);' onclick=\"record_count++;send_cmd('v_conference_exec.php?cmd=conference%20".$conference_name." record recordings/conference_".$conference_name."-'+document.getElementById('time_stamp').innerHTML+'_'+record_count+'.wav');\">Start Record</a>&nbsp;\n";
	echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?cmd=conference%20".$conference_name." norecord recordings/conference_".$conference_name."-'+document.getElementById('time_stamp').innerHTML+'_'+record_count+'.wav');\">Stop Record</a>&nbsp;\n";
	if ($locked == "true") {
		echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?cmd=conference%20".$conference_name." unlock');\">Unlock</a>&nbsp;\n";
	}
	else {
		echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_conference_exec.php?cmd=conference%20".$conference_name." lock');\">Lock</a>&nbsp;\n";
	}
	*/
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	//echo "<th>ID</th>\n";

	//echo "<th>UUID</th>\n";
	echo "<th>Dir</th>\n";
	echo "<th>Profile</th>\n";
	echo "<th>Created</th>\n";
	//echo "<th>Created Epoch</th>\n";
	//echo "<th>Name</th>\n";
	echo "<th>Number</th>\n";
	//echo "<th>State</th>\n";
	echo "<th>CID Name</th>\n";
	echo "<th>CID Number</th>\n";
	//echo "<th>IP Addr</th>\n";
	echo "<th>Dest</th>\n";
	echo "<th>Application</th>\n";
	//echo "<th>Application Data</th>\n";
	//echo "<th>Dialplan</th>\n";
	//echo "<th>Context</th>\n";
	echo "<th>Read / Write Codec</th>\n";
	//echo "<th>Read Rate</th>\n";
	//echo "<th>Write Codec</th>\n";
	//echo "<th>Write Rate</th>\n";
	echo "<th>Secure</th>\n";
	echo "<th>Options</th>\n";
	echo "</tr>\n";

	foreach ($xml as $row) {
		//print_r($row);

		$uuid = $row->uuid;
		$direction = $row->direction;
		$created = $row->created;
		$created_epoch = $row->created_epoch;
		$name = $row->name;
		$state = $row->state;
		$cid_name = $row->cid_name;
		$cid_num = $row->cid_num;
		$ip_addr = $row->ip_addr;
		$dest = $row->dest;
		$application = $row->application;
		$application_data = $row->application_data;
		$dialplan = $row->dialplan;
		$context = $row->context;
		$read_codec = $row->read_codec;
		$read_rate = $row->read_rate;
		$write_codec = $row->write_codec;
		$write_rate = $row->write_rate;
		$secure = $row->secure;
		
		if ($direction == "inbound") {
			$direction = 'In';
		}
		else {
			$direction = 'Out';
		}

		//remove the '+' because it breaks the call recording
			$cid_num = str_replace("+", "", $cid_num);

		//$caller_id_name = $row->caller_id_name;
		//$caller_id_name = str_replace("%20", " ", $caller_id_name);
		//$caller_id_number = $row->caller_id_number;
		/*
		<uuid>5ca89c63-3c73-dd40-88ca-d89f7bcd2a0e</uuid>
		<direction>inbound</direction>
		<created>2010-02-11 16:59:16</created>
		<created_epoch>1265932756</created_epoch>
		<name>sofia/internal/1004@markjcrane.dyndns.org</name>
		<state>CS_EXECUTE</state>
		<cid_name>1004</cid_name>
		<cid_num>1004</cid_num>
		<ip_addr>10.7.0.249</ip_addr>
		<dest>5002</dest>
		<application>set</application>
		<application_data>transfer_ringback=%(2000, 4000, 440.0, 480.0)</application_data>
		<dialplan>XML</dialplan>
		<context>default</context>
		<read_codec>PCMU</read_codec>
		<read_rate>8000</read_rate>
		<write_codec>PCMU</write_codec>
		<write_rate>8000</write_rate>
		<secure></secure>
		*/
		echo "<tr>\n";
		//echo "<td valign='top' class='".$rowstyle[$c]."'>$id &nbsp;</td>\n";
		//echo "<td valign='top' class='".$rowstyle[$c]."'>$uuid &nbsp;</td>\n";
		echo "<td valign='top' class='".$rowstyle[$c]."'>$direction &nbsp;</td>\n";


		$name_array = explode("/", $name);
		$sip_profile = $name_array[1];
		$sip_uri = $name_array[2];
		//echo $sip_uri;
		$temp_array = explode("@", $sip_uri);
		$tmp_number = $temp_array[0];
		$tmp_number = str_replace("sip:", "", $tmp_number);

		echo "<td valign='top' class='".$rowstyle[$c]."'>$sip_profile &nbsp;</td>\n";
		echo "<td valign='top' class='".$rowstyle[$c]."'>$created &nbsp;</td>\n";
		//echo "<td valign='top' class='".$rowstyle[$c]."'>$created_epoch &nbsp;</td>\n";

		//echo "<td valign='top' class='".$rowstyle[$c]."'>$name &nbsp;</td>\n";
		echo "<td valign='top' class='".$rowstyle[$c]."'>\n";
		//echo "$name\n";

		echo $tmp_number;
		echo "&nbsp;</td>\n";

		//echo "<td valign='top' class='".$rowstyle[$c]."'>$state &nbsp;</td>\n";
		echo "<td valign='top' class='".$rowstyle[$c]."'>$cid_name &nbsp;</td>\n";
		echo "<td valign='top' class='".$rowstyle[$c]."'>$cid_num &nbsp;</td>\n";
		//echo "<td valign='top' class='".$rowstyle[$c]."'>$ip_addr &nbsp;</td>\n";
		echo "<td valign='top' class='".$rowstyle[$c]."'>$dest &nbsp;</td>\n";
		echo "<td valign='top' class='".$rowstyle[$c]."'>$application &nbsp;</td>\n";
		//echo "<td valign='top' class='".$rowstyle[$c]."'>$application_data &nbsp;</td>\n";
		//echo "<td valign='top' class='".$rowstyle[$c]."'>$dialplan &nbsp;</td>\n";
		//echo "<td valign='top' class='".$rowstyle[$c]."'>$context &nbsp;</td>\n";
		echo "<td valign='top' class='".$rowstyle[$c]."'>$read_codec:$read_rate / $write_codec:$write_rate &nbsp;</td>\n";
		//echo "<td valign='top' class='".$rowstyle[$c]."'>$read_rate &nbsp;</td>\n";
		//echo "<td valign='top' class='".$rowstyle[$c]."'>$write_codec &nbsp;</td>\n";
		//echo "<td valign='top' class='".$rowstyle[$c]."'>$write_rate &nbsp;</td>\n";
		echo "<td valign='top' class='".$rowstyle[$c]."'>$secure &nbsp;</td>\n";

		echo "<td valign='top' class='".$rowstyle[$c]."' style='text-align:center;'>\n";

		//transfer
			//uuid_transfer c985c31b-7e5d-3844-8b3b-aa0835ff6db9 -bleg *9999 xml default

			//document.getElementById('url').innerHTML='v_calls_exec.php?action=energy&direction=down&cmd='+prepare_cmd(escape('$uuid'));
			echo "	<a href='javascript:void(0);' onMouseover=\"document.getElementById('form_label').innerHTML='<strong>Transfer To</strong>';\" onclick=\"send_cmd('v_calls_exec.php?cmd='+get_transfer_cmd(escape('$uuid')));\">xfer</a>&nbsp;\n";

		//park
			echo "	<a href='javascript:void(0);' onclick=\"send_cmd('v_calls_exec.php?cmd='+get_park_cmd(escape('$uuid')));\">park</a>&nbsp;\n";

		//hangup
			echo "	<a href='javascript:void(0);' onclick=\"confirm_response = confirm('Do you really want to hangup this call?');if (confirm_response){send_cmd('v_calls_exec.php?cmd=uuid_kill%20'+(escape('$uuid')));}\">hangup</a>&nbsp;\n";

		//record start/stop
			$tmp_dir = $v_recordings_dir."/archive/".date("Y")."/".date("M")."/".date("d");
			mkdir($tmp_dir, 0777, true);
			$tmp_file = $tmp_dir."/".$uuid.".wav";
			if (file_exists($tmp_file)) {
				//stop
				echo "	<a href='javascript:void(0);' style='color: #444444;' onclick=\"send_cmd('v_calls_exec.php?cmd='+get_record_cmd(escape('$uuid'), 'active_calls_', escape('$cid_num'))+'&uuid='+escape('$uuid')+'&action=record&action2=stop&prefix=active_calls_&name='+escape('$cid_num'));\">stop rec</a>&nbsp;\n";
			}
			else {
				//start
				echo "	<a href='javascript:void(0);' style='color: #444444;' onclick=\"send_cmd('v_calls_exec.php?cmd='+get_record_cmd(escape('$uuid'), 'active_calls_', escape('$cid_num'))+'&uuid='+escape('$uuid')+'&action=record&action2=start&prefix=active_calls_');\">rec</a>&nbsp;\n";
			}

		echo "	&nbsp;";
		echo "</td>\n";
		echo "</tr>\n";
		if ($c==0) { $c=1; } else { $c=0; }
	}
	echo "</table>\n";
}
?>
