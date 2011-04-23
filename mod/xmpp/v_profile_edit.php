<?php
/* $Id$ */
/*
	v_xmpp.php
	Copyright (C) 2008, 2009 Ken Rice
	All rights reserved.

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:

	1. Redistributions of source code must retain the above copyright notice,
	   this list of conditions and the following disclaimer.

	2. Redistributions in binary form must reproduce the above copyright
	   notice, this list of conditions and the following disclaimer in the
	   documentation and/or other materials provided with the distribution.

	THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
	INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
	AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
	AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
	OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
	SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
	INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
	CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
	ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
	POSSIBILITY OF SUCH DAMAGE.
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

// print_r($_REQUEST);

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
} else { 
 	$profile['dialplan'] = "XML";
	$profile['context'] = $v_domain;
	$profile['rtp_ip'] = '$${local_ip_v4}';
 	$profile['auto_login'] = "true";
 	$profile['sasl_type'] = "plain";
 	$profile['tls_enable'] = "true";
 	$profile['usr_rtp_timer'] = "true";
 	$profile['vad'] = "none";
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

// print_r($request);
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
if ($action == "add") {

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
 	$sql .= "local_network_acl";
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
 	$sql .= "'" . $request['local_network_acl'] . "'";
	$sql .= ") RETURNING xmpp_profile_id;";

	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();

        $result = $prepstatement->fetchAll();

	$xmpp_profile_id = $result[0]['xmpp_profile_id'];

	goto writefile;

} elseif ($action == "update") {
	
	echo "UPDATE THE RECORDS";
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
	$sql .= "local_network_acl = '" . $request['local_network_acl'] . "' ";
	$sql .= "where xmpp_profile_id = " . $request['profile_id'];
	$db->exec(check_sql($sql));
		
	$xmpp_profile_id = $request['profile_id'];
	
	goto writeout;

} 

writeout:
include "client_template.php";
$xml = make_xmpp_xml($request);

$filename = "v_" . $v_domain . "_" . preg_replace("/[^A-Za-z0-9]/", "", $string_to_be_stripped ) . "_" . $xmpp_profile_id . ".xml";




/*
if ($x > 0) {
	$key = guid();
	$client_ip = $_SERVER['REMOTE_ADDR'];
	$sql = sprintf("INSERT INTO v_flashphone_auth (auth_key, hostaddr, createtime, profile_username) values ('%s', '%s', now(), '%s')",
			$key, $client_ip, $_SESSION["profile_username"]);
	$db->exec(check_sql($sql));
}
*/

end:
//show the footer
require_once "includes/footer.php";

?>
