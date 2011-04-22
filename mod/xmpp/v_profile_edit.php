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
}

if($_REQUEST['submit'] != 'Save') {
	// If we arent saving a Profile Display the form.
	include "profile_edit.php";	
	goto end;
}

foreach ($_REQUEST as $field => $data){
	$request[$field] = check_str($data);
}

// print_r($request);
// DataChecking Goes Here



// Save New Entry
if ($action == "add"){

	$sql = "";
	$sql .= "insert into v_xmpp (";
 	$sql .= "v_id, ";
 	$sql .= "profile_name, ";
 	$sql .= "username, ";
 	$sql .= "password, ";
 	$sql .= "dialplan, ";
 	$sql .= "context, ";
 	$sql .= "rtp_ip, ";
 	$sql .= "sasl_type, ";
 	$sql .= "xmpp_server, ";
 	$sql .= "tls_enable, ";
 	$sql .= "usr_rtp_timer, ";
 	$sql .= "default_exten, ";
 	$sql .= "vad, ";
 	$sql .= "avatar, ";
 	$sql .= "candidate_acl";
 	$sql .= "local_network_acl";
	$sql .= ") values (";
 	$sql .= "$v_id, ";
 	$sql .= "'" . $request['profile_name'] . "', ";
 	$sql .= "'" . $request['username'] . "', ";
 	$sql .= "'" . $request['password'] . "', ";
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
	$sql .= ");";

	$db->exec(check_sql($sql));

	include "update_complete.php";
	goto end;

} else { echo "wtf"; }


/*
if ($x > 0) {
	$key = guid();
	$client_ip = $_SERVER['REMOTE_ADDR'];
	$sql = sprintf("INSERT INTO v_flashphone_auth (auth_key, hostaddr, createtime, username) values ('%s', '%s', now(), '%s')",
			$key, $client_ip, $_SESSION["username"]);
	$db->exec(check_sql($sql));
}
*/

end:
//show the footer
require_once "includes/footer.php";

?>
