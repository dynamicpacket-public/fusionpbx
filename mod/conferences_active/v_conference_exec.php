<?php
/* $Id$ */
/*
	v_exec.php
	Copyright (C) 2008 Mark J Crane
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
if (permission_exists('conferences_active_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//get the http values and set them as php variables
	if (count($_GET)>0) {
		$switch_cmd = trim($_GET["cmd"]);
		$action = trim($_GET["action"]);
		$direction = trim($_GET["direction"]);
	}

if (count($_GET)>0) {
	if (strlen($switch_cmd) > 0) {

		//check if the domain is in the switch_cmd
			if(stristr($switch_cmd, $v_domain) === FALSE) {
				echo "access denied";
				exit;
			}

		//connect to event socket
			$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
			if ($fp) {
				if ($action == "energy") {
					//conference 3001-example-domain.org energy 103
					$switch_result = event_socket_request($fp, 'api '.$switch_cmd);
					$result_array = explode("=",$switch_result);
					$tmp_value = $result_array[1];
					if ($direction == "up") { $tmp_value = $tmp_value + 100; }
					if ($direction == "down") { $tmp_value = $tmp_value - 100; }
					//echo "energy $tmp_value<br />\n";
					$switch_result = event_socket_request($fp, 'api '.$switch_cmd.' '.$tmp_value);
				}
				if ($action == "volume_in") {
					$switch_result = event_socket_request($fp, 'api '.$switch_cmd);
					$result_array = explode("=",$switch_result);
					$tmp_value = $result_array[1];
					if ($direction == "up") { $tmp_value = $tmp_value + 1; }
					if ($direction == "down") { $tmp_value = $tmp_value - 1; }
					//echo "volume $tmp_value<br />\n";
					$switch_result = event_socket_request($fp, 'api '.$switch_cmd.' '.$tmp_value);
				}
				if ($action == "volume_out") {
					$switch_result = event_socket_request($fp, 'api '.$switch_cmd);
					$result_array = explode("=",$switch_result);
					$tmp_value = $result_array[1];
					if ($direction == "up") { $tmp_value = $tmp_value + 1; }
					if ($direction == "down") { $tmp_value = $tmp_value - 1; }
					//echo "volume $tmp_value<br />\n";
					$switch_result = event_socket_request($fp, 'api '.$switch_cmd.' '.$tmp_value);
				}
			}

		//send a command over event socket
			if ($fp) {
				$switch_result = event_socket_request($fp, 'api '.$switch_cmd);
			}
	}

}

?>
