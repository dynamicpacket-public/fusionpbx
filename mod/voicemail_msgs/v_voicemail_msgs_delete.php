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
require "includes/config.php";
require_once "includes/checkauth.php";
if (permission_exists('voicemail_delete')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//get the http get values
	if (count($_GET)>0) {
		$uuid = $_GET["uuid"];
	}

//pdo voicemail database connection
	include "includes/lib_pdo_vm.php";

//delet the voicemail message
	if (strlen($uuid)>0) {
		$uuid = $_GET["uuid"];
		$sql = "";
		$sql .= "select * from voicemail_msgs ";
		$sql .= "where domain = '$v_domain' ";
		$sql .= "and uuid = '$uuid' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$created_epoch = $row["created_epoch"];
			$read_epoch = $row["read_epoch"];
			$username = $row["username"];
			$domain = $row["domain"];
			$uuid = $row["uuid"];
			$cid_name = $row["cid_name"];
			$cid_number = $row["cid_number"];
			$in_folder = $row["in_folder"];
			$file_path = $row["file_path"];
			$message_len = $row["message_len"];
			$flags = $row["flags"];
			$read_flags = $row["read_flags"];
			break; //limit to 1 row
		}
		unset ($prepstatement);

		if  (file_exists($file_path)) {
			unlink($file_path);
		}

		$sql = "";
		$sql .= "delete from voicemail_msgs ";
		$sql .= "where domain = '$v_domain' ";
		$sql .= "and uuid = '$uuid' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		unset($sql);
	}

//redirect the user
	require "includes/config.php";
	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"2;url=v_voicemail_msgs.php\">\n";
	echo "<div align='center'>\n";
	echo "Delete Complete\n";
	echo "</div>\n";
	require_once "includes/footer.php";
	return;

?>
