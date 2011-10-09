<?php
/* $Id$ */
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
	Ken Rice <krice@tollfreegateway.com>
	Mark J Crane <markjcrane@fusionpbx.com>
*/

include "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";

if (permission_exists('xmpp_add') || permission_exists('xmpp_edit')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

require_once "includes/header.php";

$v_domain = $_SESSION['domains'][$v_id]['domain'];

//add or update the database
if (isset($_REQUEST["id"])) {
	$action = "update";
	$profile_id = check_str($_REQUEST["id"]);
} else {  
	$action = "add";
}


if ($action == "update") {

//get a list of assigned extensions for this user
	$sql = "";
	$sql .= "select * from v_xmpp ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and xmpp_profile_id = '$profile_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();

	$x = 0;
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$profiles_array[$x] = $row;
		$x++;
	}

	$profile = $profiles_array[0];
	unset ($prepstatement);
	$profile['profile_username'] = $profile['username'];
	$profile['profile_password'] = $profile['password'];
} else { 
 	$profile['dialplan'] = "XML";
	$profile['context'] = $v_domain;
	$profile['rtp_ip'] = '$${local_ip_v4}';
	$profile['ext_rtp_ip'] = '$${external_rtp_ip}';
 	$profile['auto_login'] = "true";
 	$profile['sasl_type'] = "plain";
 	$profile['tls_enable'] = "true";
 	$profile['usr_rtp_timer'] = "true";
 	$profile['vad'] = "both";
	$profile['candidate_acl'] = "wan.auto";
 	$profile['local_network_acl'] = "localnet.auto";
}

if ((!isset($_REQUEST['submit'])) || ($_REQUEST['submit'] != 'Save')) {
	// If we arent saving a Profile Display the form.
	include "profile_edit.php";	
	goto end;
}

foreach ($_REQUEST as $field => $data){
	$request[$field] = check_str($data);
}

// DataChecking Goes Here
$error = "";
if (strlen($request['profile_name']) < 1) $error .= "Profile name is a Required Field<br />\n";
if (strlen($request['profile_username']) < 1) $error .= "Username is a Required Field<br />\n";
if (strlen($request['profile_password']) < 1) $error .= "Password is a Required Field<br />\n";
if (strlen($request['default_exten']) < 1) $error .= "Default Extension is a Required Field<br />\n";
if (strlen($error) > 0) { 
	include "errors.php";
	$profile = $request;
	include "profile_edit.php";	
	goto end;
}

// Save New Entry
if ($action == "add" && permission_exists('xmpp_add')) {
	$sql = "";
	$sql .= "insert into v_xmpp (";
 	$sql .= "v_id, ";
 	$sql .= "profile_name, ";
 	$sql .= "username, ";
 	$sql .= "password, ";
 	$sql .= "dialplan, ";
 	$sql .= "context, ";
 	$sql .= "rtp_ip, ";
 	$sql .= "ext_rtp_ip, ";
 	$sql .= "auto_login, ";
 	$sql .= "sasl_type, ";
 	$sql .= "xmpp_server, ";
 	$sql .= "tls_enable, ";
 	$sql .= "usr_rtp_timer, ";
 	$sql .= "default_exten, ";
 	$sql .= "vad, ";
 	$sql .= "avatar, ";
 	$sql .= "candidate_acl, ";
 	$sql .= "local_network_acl, ";
	$sql .= "description ";
	$sql .= ") values (";
 	$sql .= "$v_id, ";
 	$sql .= "'" . $request['profile_name'] . "', ";
 	$sql .= "'" . $request['profile_username'] . "', ";
 	$sql .= "'" . $request['profile_password'] . "', ";
 	$sql .= "'" . $request['dialplan'] . "', ";
 	$sql .= "'" . $request['context'] . "', ";
 	$sql .= "'" . $request['rtp_ip'] . "', ";
 	$sql .= "'" . $request['ext_rtp_ip'] . "', ";
 	$sql .= "'" . $request['auto_login'] . "', ";
 	$sql .= "'" . $request['sasl_type'] . "', ";
 	$sql .= "'" . $request['xmpp_server'] . "', ";
 	$sql .= "'" . $request['tls_enable'] . "', ";
 	$sql .= "'" . $request['usr_rtp_timer'] . "', ";
 	$sql .= "'" . $request['default_exten'] . "', ";
 	$sql .= "'" . $request['vad'] . "', ";
 	$sql .= "'" . $request['avatar'] . "', ";
 	$sql .= "'" . $request['candidate_acl'] . "', ";
 	$sql .= "'" . $request['local_network_acl'] . "', ";
	$sql .= "'" . $request['description'] . "' ";
	$sql .= ") ";
	if ($db_type == "pgsql") {
	 	$sql .= "RETURNING xmpp_profile_id;";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
        	$result = $prepstatement->fetchAll();
		$xmpp_profile_id = $result[0]['xmpp_profile_id'];
	} elseif ($db_type == "sqlite" || $db_type == "mysql" ) {
                $db->exec(check_sql($sql));
		$xmpp_profile_id = $db->lastInsertId();
	}

	goto writeout;

} elseif ($action == "update" && permission_exists('xmpp_edit')) {
	// Update the new Records
	$sql = "";
	$sql .= "UPDATE v_xmpp SET ";
	$sql .= "profile_name = '" . $request['profile_name'] . "', ";
	$sql .= "username = '" . $request['profile_username'] . "', ";
	$sql .= "password = '" . $request['profile_password'] . "', ";
	$sql .= "dialplan = '" . $request['dialplan'] . "', ";
	$sql .= "context = '" . $request['context'] . "', ";
	$sql .= "rtp_ip = '" . $request['rtp_ip'] . "', ";
	$sql .= "ext_rtp_ip = '" . $request['ext_rtp_ip'] . "', ";
	$sql .= "auto_login = '" . $request['auto_login'] . "', ";
	$sql .= "sasl_type = '" . $request['sasl_type'] . "', ";
	$sql .= "xmpp_server = '" . $request['xmpp_server'] . "', ";
	$sql .= "tls_enable = '" . $request['tls_enable'] . "', ";
	$sql .= "usr_rtp_timer = '" . $request['usr_rtp_timer'] . "', ";
	$sql .= "default_exten = '" . $request['default_exten'] . "', ";
	$sql .= "vad = '" . $request['vad'] . "', ";
	$sql .= "avatar = '" . $request['avatar'] . "', ";
	$sql .= "candidate_acl = '" . $request['candidate_acl'] . "', ";
	$sql .= "local_network_acl = '" . $request['local_network_acl'] . "', ";
	$sql .= "description = '" . $request['description'] . "' ";
	$sql .= "where xmpp_profile_id = " . $request['id'];
	$db->exec(check_sql($sql));
		
	$xmpp_profile_id = $request['id'];
	
	goto writeout;
} 

writeout:
include "client_template.php";
$xml = make_xmpp_xml($request);

$filename = $v_conf_dir . "/jingle_profiles/" . "v_" . $v_domain . "_" . preg_replace("/[^A-Za-z0-9]/", "", $request['profile_name']) . "_" . $xmpp_profile_id . ".xml";

$fh = fopen($filename,"w") or die("WTF");
fwrite($fh, $xml);
unset($file_name);
fclose($fh);

$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
if ($fp) {
	//reload the XML Configs
	$tmp_cmd = 'api reloadxml';
	$response = event_socket_request($fp, $tmp_cmd);
	unset($tmp_cmd);

	//Tell mod_dingaling to reload is config
	$tmp_cmd = 'api dingaling reload';
	$response = event_socket_request($fp, $tmp_cmd);
	unset($tmp_cmd);

	//close the connection
	fclose($fp);
}

include "update_complete.php";

end:
//show the footer
require_once "includes/footer.php";

?>
