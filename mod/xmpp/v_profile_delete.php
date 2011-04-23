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

require_once "includes/header.php";

$v_domain = $_SESSION['domains'][$v_id]['domain'];

$profile_id = $_REQUEST['id'];

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

$sql = "";
$sql .= "delete from v_xmpp ";
$sql .= "where v_id = '$v_id' ";
$sql .= "and xmpp_profile_id = '$profile_id' ";

$db->exec(check_sql($sql));

$filename = $v_conf_dir . "/jingle_profiles/" . "v_" . $v_domain . "_" . 
	preg_replace("/[^A-Za-z0-9]/", "", $profile['profile_name']) . "_" . $profile_id . ".xml";

unlink($filename);

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
