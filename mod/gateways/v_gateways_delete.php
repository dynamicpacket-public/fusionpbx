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
if (permission_exists('gateways_delete')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

if (count($_GET)>0) {
	if (is_numeric($_GET["id"])) {
		$id = $_GET["id"];
	}
	else {
		echo "access denied";
		exit;
	}
}

if (strlen($id)>0) {

	//get the gateway name
		$sql = "";
		$sql .= "select * from v_gateways ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and gateway_id = '$id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$gateway = $row["gateway"];
			break; //limit to 1 row
		}
		unset ($prepstatement);

	//create the event socket connection and stop the gateway
		if (!$fp) {
			$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
		}
		if ($fp) {
			//send the api gateway stop command over event socket
				if (count($_SESSION["domains"]) > 1) {
					$tmp_cmd = 'api sofia profile external killgw '.$v_domain.'-'.$gateway;
				}
				else {
					$tmp_cmd = 'api sofia profile external killgw '.$gateway;
				}
				$response = event_socket_request($fp, $tmp_cmd);
				unset($tmp_cmd);
		}

	//delete gateway
		$sql = "";
		$sql .= "delete from v_gateways ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and gateway_id = '$id' ";
		$db->query($sql);
		unset($sql);

	//delete the dialplan entries
		$sql = "";
		$sql .= "select * from v_dialplan_includes ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and opt1name = 'gateway_id' ";
		$sql .= "and opt1value = '".$id."' ";
		//echo "sql: ".$sql."<br />\n";
		$prepstatement2 = $db->prepare($sql);
		$prepstatement2->execute();
		while($row2 = $prepstatement2->fetch()) {
			$dialplan_include_id = $row2['dialplan_include_id'];

			$sql = "";
			$sql = "delete from v_dialplan_includes_details ";
			$sql .= "where v_id = '$v_id' ";
			$sql .= "and dialplan_include_id = '$dialplan_include_id' ";
			$db->query($sql);
			unset($sql);

			//break; //limit to 1 row
		}
		unset ($sql, $prepstatement2);

		$sql = "";
		$sql = "delete from v_dialplan_includes ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and opt1name = 'gateway_id' ";
		$sql .= "and opt1value = '$id' ";
		//echo "sql: ".$sql."<br />\n";
		$db->query($sql);
		unset($sql);

	//syncrhonize configuration
		sync_package_v_gateways();

	//synchronize the xml config
		sync_package_v_dialplan_includes();

	//rescan the external profile to look for new or stopped gateways
		//create the event socket connection and send a command
			if (!$fp) {
				$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
			}
			if ($fp) {
				//send the api commandover event socket
					$tmp_cmd = 'api sofia profile external rescan';
					$response = event_socket_request($fp, $tmp_cmd);
					unset($tmp_cmd);
				//close the connection
					fclose($fp);
			}
			usleep(1000);

		//clear the apply settings reminder
			$_SESSION["reload_xml"] = false;
}

//redirect the users
	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"2;url=v_gateways.php\">\n";
	echo "<div align='center'>\n";
	echo "Delete Complete\n";
	echo "</div>\n";

	require_once "includes/footer.php";
	return;

?>
