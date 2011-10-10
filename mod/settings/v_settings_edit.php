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
if (permission_exists('settings_view') || ifgroup("superadmin")) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//action add or update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$setting_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//get the http values and set them as php variables
	if (count($_POST)>0) {
		$numbering_plan = check_str($_POST["numbering_plan"]);
		$default_gateway = check_str($_POST["default_gateway"]);
		$event_socket_ip_address = check_str($_POST["event_socket_ip_address"]);
		if (strlen($event_socket_ip_address) == 0) { $event_socket_ip_address = '127.0.0.1'; }
		$event_socket_port = check_str($_POST["event_socket_port"]);
		$event_socket_password = check_str($_POST["event_socket_password"]);
		$xml_rpc_http_port = check_str($_POST["xml_rpc_http_port"]);
		$xml_rpc_auth_realm = check_str($_POST["xml_rpc_auth_realm"]);
		$xml_rpc_auth_user = check_str($_POST["xml_rpc_auth_user"]);
		$xml_rpc_auth_pass = check_str($_POST["xml_rpc_auth_pass"]);
		$admin_pin = check_str($_POST["admin_pin"]);
		$smtphost = check_str($_POST["smtphost"]);
		$smtpsecure = check_str($_POST["smtpsecure"]);
		$smtpauth = check_str($_POST["smtpauth"]);
		$smtpusername = check_str($_POST["smtpusername"]);
		$smtppassword = check_str($_POST["smtppassword"]);
		$smtpfrom = check_str($_POST["smtpfrom"]);
		$smtpfromname = check_str($_POST["smtpfromname"]);
		$mod_shout_decoder = check_str($_POST["mod_shout_decoder"]);
		$mod_shout_volume = check_str($_POST["mod_shout_volume"]);
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$setting_id = check_str($_POST["setting_id"]);
	}

	//check for all required data
		//if (strlen($numbering_plan) == 0) { $msg .= "Please provide: Numbering Plan<br>\n"; }
		//if (strlen($default_gateway) == 0) { $msg .= "Please provide: Default Gateway<br>\n"; }
		if (strlen($event_socket_port) == 0) { $msg .= "Please provide: Event Socket Port<br>\n"; }
		if (strlen($event_socket_password) == 0) { $msg .= "Please provide: Event Socket Password<br>\n"; }
		//if (strlen($xml_rpc_http_port) == 0) { $msg .= "Please provide: XML RPC HTTP Port<br>\n"; }
		//if (strlen($xml_rpc_auth_realm) == 0) { $msg .= "Please provide: XML RPC Auth Realm<br>\n"; }
		//if (strlen($xml_rpc_auth_user) == 0) { $msg .= "Please provide: XML RPC Auth User<br>\n"; }
		//if (strlen($xml_rpc_auth_pass) == 0) { $msg .= "Please provide: XML RPC Auth Password<br>\n"; }
		//if (strlen($admin_pin) == 0) { $msg .= "Please provide: Admin PIN Number<br>\n"; }
		//if (strlen($smtphost) == 0) { $msg .= "Please provide: SMTP Host<br>\n"; }
		//if (strlen($smtpsecure) == 0) { $msg .= "Please provide: SMTP Secure<br>\n"; }
		//if (strlen($smtpauth) == 0) { $msg .= "Please provide: SMTP Auth<br>\n"; }
		//if (strlen($smtpusername) == 0) { $msg .= "Please provide: SMTP Username<br>\n"; }
		//if (strlen($smtppassword) == 0) { $msg .= "Please provide: SMTP Password<br>\n"; }
		//if (strlen($smtpfrom) == 0) { $msg .= "Please provide: SMTP From<br>\n"; }
		//if (strlen($smtpfromname) == 0) { $msg .= "Please provide: SMTP From Name<br>\n"; }
		//if (strlen($mod_shout_decoder) == 0) { $msg .= "Please provide: Mod Shout Decoder<br>\n"; }
		//if (strlen($mod_shout_volume) == 0) { $msg .= "Please provide: Mod Shout Volume<br>\n"; }
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
			if ($action == "add" && permission_exists('settings_edit')) {
				$sql = "insert into v_settings ";
				$sql .= "(";
				$sql .= "v_id, ";
				$sql .= "numbering_plan, ";
				$sql .= "default_gateway, ";
				$sql .= "event_socket_ip_address, ";
				$sql .= "event_socket_port, ";
				$sql .= "event_socket_password, ";
				$sql .= "xml_rpc_http_port, ";
				$sql .= "xml_rpc_auth_realm, ";
				$sql .= "xml_rpc_auth_user, ";
				$sql .= "xml_rpc_auth_pass, ";
				$sql .= "admin_pin, ";
				$sql .= "smtphost, ";
				$sql .= "smtpsecure, ";
				$sql .= "smtpauth, ";
				$sql .= "smtpusername, ";
				$sql .= "smtppassword, ";
				$sql .= "smtpfrom, ";
				$sql .= "smtpfromname, ";
				$sql .= "mod_shout_decoder, ";
				$sql .= "mod_shout_volume ";
				$sql .= ")";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'1', ";
				$sql .= "'$numbering_plan', ";
				$sql .= "'$default_gateway', ";
				$sql .= "'$event_socket_ip_address', ";
				$sql .= "'$event_socket_port', ";
				$sql .= "'$event_socket_password', ";
				$sql .= "'$xml_rpc_http_port', ";
				$sql .= "'$xml_rpc_auth_realm', ";
				$sql .= "'$xml_rpc_auth_user', ";
				$sql .= "'$xml_rpc_auth_pass', ";
				$sql .= "'$admin_pin', ";
				$sql .= "'$smtphost', ";
				$sql .= "'$smtpsecure', ";
				$sql .= "'$smtpauth', ";
				$sql .= "'$smtpusername', ";
				$sql .= "'$smtppassword', ";
				$sql .= "'$smtpfrom', ";
				$sql .= "'$smtpfromname', ";
				$sql .= "'$mod_shout_decoder', ";
				$sql .= "'$mod_shout_volume' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql);

				//synchronize settings
					sync_package_v_settings();

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_settings_edit.php?id=1\">\n";
				echo "<div align='center'>\n";
				echo "Add Complete\n";
				echo "</div>\n";
				require_once "includes/footer.php";
				return;
			} //if ($action == "add")

			if ($action == "update" && permission_exists('settings_edit')) {
				$sql = "update v_settings set ";
				$sql .= "v_id = '1', ";
				$sql .= "numbering_plan = '$numbering_plan', ";
				$sql .= "default_gateway = '$default_gateway', ";
				$sql .= "event_socket_ip_address = '$event_socket_ip_address', ";
				$sql .= "event_socket_port = '$event_socket_port', ";
				$sql .= "event_socket_password = '$event_socket_password', ";
				$sql .= "xml_rpc_http_port = '$xml_rpc_http_port', ";
				$sql .= "xml_rpc_auth_realm = '$xml_rpc_auth_realm', ";
				$sql .= "xml_rpc_auth_user = '$xml_rpc_auth_user', ";
				$sql .= "xml_rpc_auth_pass = '$xml_rpc_auth_pass', ";
				$sql .= "admin_pin = '$admin_pin', ";
				$sql .= "smtphost = '$smtphost', ";
				$sql .= "smtpsecure = '$smtpsecure', ";
				$sql .= "smtpauth = '$smtpauth', ";
				$sql .= "smtpusername = '$smtpusername', ";
				$sql .= "smtppassword = '$smtppassword', ";
				$sql .= "smtpfrom = '$smtpfrom', ";
				$sql .= "smtpfromname = '$smtpfromname', ";
				$sql .= "mod_shout_decoder = '$mod_shout_decoder', ";
				$sql .= "mod_shout_volume = '$mod_shout_volume' ";
				$sql .= "where setting_id = '$setting_id' ";
				$db->exec(check_sql($sql));
				unset($sql);

				//synchronize settings
					sync_package_v_settings();

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_settings_edit.php?id=1\">\n";
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
		$setting_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_settings ";
		$sql .= "where setting_id = '$setting_id' ";
		$sql .= "and v_id = '1' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$numbering_plan = $row["numbering_plan"];
			$default_gateway = $row["default_gateway"];
			$event_socket_ip_address = $row["event_socket_ip_address"];
			$event_socket_port = $row["event_socket_port"];
			$event_socket_password = $row["event_socket_password"];
			$xml_rpc_http_port = $row["xml_rpc_http_port"];
			$xml_rpc_auth_realm = $row["xml_rpc_auth_realm"];
			$xml_rpc_auth_user = $row["xml_rpc_auth_user"];
			$xml_rpc_auth_pass = $row["xml_rpc_auth_pass"];
			$admin_pin = $row["admin_pin"];
			$smtphost = $row["smtphost"];
			$smtpsecure = $row["smtpsecure"];
			$smtpauth = $row["smtpauth"];
			$smtpusername = $row["smtpusername"];
			$smtppassword = $row["smtppassword"];
			$smtpfrom = $row["smtpfrom"];
			$smtpfromname = $row["smtpfromname"];
			$mod_shout_decoder = $row["mod_shout_decoder"];
			$mod_shout_volume = $row["mod_shout_volume"];
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
	echo "<td align='left' width='30%' nowrap><b>Setting Add</b></td>\n";
	}
	if ($action == "update") {
	echo "<td align='left' width='30%' nowrap><b>Setting Update</b></td>\n";
	}
	echo "<td width='70%' align='right'><input type='button' class='btn' name='' alt='back' onclick=\"window.location='javascript:history.go(-1)'\" value='Back'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Numbering Plan:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='numbering_plan' maxlength='255' value=\"$numbering_plan\">\n";
	echo "<br />\n";
	echo "Enter the numbering plan. example: US\n";
	echo "</td>\n";
	echo "</tr>\n";

	//echo "<tr>\n";
	//echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	//echo "    Default Gateway:\n";
	//echo "</td>\n";
	//echo "<td class='vtable' align='left'>\n";
	//echo "    <input class='formfld' type='text' name='default_gateway' maxlength='255' value=\"$default_gateway\">\n";
	//echo "<br />\n";
	//echo " Enter the default gateway name.\n";
	//echo "</td>\n";
	//echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Event Socket IP Address:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='event_socket_ip_address' maxlength='255' value=\"$event_socket_ip_address\">\n";
	echo "<br />\n";
	echo "Enter the event socket IP address. default: 127.0.0.1\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Event Socket Port:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='event_socket_port' maxlength='255' value=\"$event_socket_port\">\n";
	echo "<br />\n";
	echo "Enter the event socket port. default: 8021\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Event Socket Password:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='password' name='event_socket_password' id='event_socket_password' onfocus=\"document.getElementById('show_event_socket_password').innerHTML = 'Password: '+document.getElementById('event_socket_password').value;\" onblur=\"//document.getElementById('show_event_socket_password').innerHTML = ''\" maxlength='50' value=\"$event_socket_password\">\n";
	echo "<br />\n";
	echo "Enter the event socket password. <span id='show_event_socket_password'></span>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    XML RPC HTTP Port:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='xml_rpc_http_port' maxlength='255' value=\"$xml_rpc_http_port\">\n";
	echo "<br />\n";
	echo "Enter the XML RPC HTTP Port. default: 8787\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    XML RPC Auth Realm:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='xml_rpc_auth_realm' maxlength='255' value=\"$xml_rpc_auth_realm\">\n";
	echo "<br />\n";
	echo "Enter the XML RPC Auth Realm. default: freeswitch\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    XML RPC Auth User:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='xml_rpc_auth_user' maxlength='255' value=\"$xml_rpc_auth_user\">\n";
	echo "<br />\n";
	echo "Enter the XML RPC Auth User. default: xmlrpc\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    XML RPC Auth Password:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='password' name='xml_rpc_auth_pass' id='xml_rpc_auth_pass' onfocus=\"document.getElementById('show_xml_rpc_auth_pass').innerHTML = 'Password: '+document.getElementById('xml_rpc_auth_pass').value;\" onblur=\"//document.getElementById('show_xml_rpc_auth_pass').innerHTML = ''\" maxlength='50' value=\"$xml_rpc_auth_pass\">\n";
	echo "<br />\n";
	echo "Enter the XML RPC Auth Password. <span id='show_xml_rpc_auth_pass'></span>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Admin PIN Number:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='password' name='admin_pin' id='admin_pin' onfocus=\"document.getElementById('show_admin_pin').innerHTML = 'Password: '+document.getElementById('admin_pin').value;\" onblur=\"document.getElementById('show_admin_pin').innerHTML = ''\" maxlength='50' value=\"$admin_pin\">\n";
	echo "<br />\n";
	echo "<span id='show_admin_pin'></span>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    SMTP Host:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='smtphost' maxlength='255' value=\"$smtphost\">\n";
	echo "<br />\n";
	echo "Enter the SMTP host address. TLS example: smtp.gmail.com:587\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    SMTP Secure:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='smtpsecure'>\n";
	echo "    <option value=''></option>\n";
	if ($smtpsecure == "none") { 
	echo "    <option value='none' SELECTED >none</option>\n";
	}
	else {
	echo "    <option value='none'>none</option>\n";
	}
	if ($smtpsecure == "tls") { 
	echo "    <option value='tls' SELECTED >tls</option>\n";
	}
	else {
	echo "    <option value='tls'>tls</option>\n";
	}
	if ($smtpsecure == "ssl") { 
	echo "    <option value='ssl' SELECTED >ssl</option>\n";
	}
	else {
	echo "    <option value='ssl'>ssl</option>\n";
	}
	echo "    </select>\n";
	echo "<br />\n";
	echo "Select the SMTP security. None, TLS, SSL\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    SMTP Auth:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='smtpauth'>\n";
	echo "    <option value=''></option>\n";
	if ($smtpauth == "true") { 
	echo "    <option value='true' SELECTED >true</option>\n";
	}
	else {
	echo "    <option value='true'>true</option>\n";
	}
	if ($smtpauth == "false") { 
	echo "    <option value='false' SELECTED >false</option>\n";
	}
	else {
	echo "    <option value='false'>false</option>\n";
	}
	echo "    </select>\n";
	echo "<br />\n";
	echo "Use SMTP Authentication true or false.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    SMTP Username:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='smtpusername' maxlength='255' value=\"$smtpusername\">\n";
	echo "<br />\n";
	echo "Enter the SMTP authentication username.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    SMTP Password:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='password' name='smtppassword' id='smtppassword' onfocus=\"document.getElementById('show_smtppassword').innerHTML = 'Password: '+document.getElementById('smtppassword').value;\" onblur=\"document.getElementById('show_smtppassword').innerHTML = ''\" maxlength='50' value=\"$smtppassword\">\n";
	echo "<br />\n";
	echo "Enter the SMTP authentication password. <span id='show_smtppassword'></span>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    SMTP From:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='smtpfrom' maxlength='255' value=\"$smtpfrom\">\n";
	echo "<br />\n";
	echo "Enter the SMTP From email address.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    SMTP From Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='smtpfromname' maxlength='255' value=\"$smtpfromname\">\n";
	echo "<br />\n";
	echo "Enter the SMTP From Name.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Mod Shout Decoder:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='mod_shout_decoder' maxlength='255' value=\"$mod_shout_decoder\">\n";
	echo "<br />\n";
	echo "Enter the Decoder. default: i386\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Mod Shout Volume:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='mod_shout_volume' maxlength='255' value=\"$mod_shout_volume\">\n";
	echo "<br />\n";
	echo "Enter Mod Shout Volume.\n";
	echo "</td>\n";
	echo "</tr>\n";
	if (permission_exists('settings_edit')) {
		echo "	<tr>\n";
		echo "		<td colspan='2' align='right'>\n";
		if ($action == "update") {
			echo "				<input type='hidden' name='setting_id' value='$setting_id'>\n";
		}
		echo "				<input type='submit' name='submit' class='btn' value='Save'>\n";
		echo "		</td>\n";
		echo "	</tr>";
	}
	echo "</table>";
	echo "</form>";

	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

//show the footer
	require_once "includes/footer.php";
?>
