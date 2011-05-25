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
if (!isset($display_results)) {
	$display_results = true;
}
if (strlen($_SERVER['HTTP_USER_AGENT']) > 0) {
	require_once "includes/checkauth.php";
	if (permission_exists('upgrade_schema') || ifgroup("superadmin")) {
		//echo "access granted";
	}
	else {
		echo "access denied";
		exit;
	}
}
else {
	$display_results = false; //true false
	//$display_type = 'csv'; //html, csv
}
ini_set(max_execution_time,1200);
if ($display_results) {
	require_once "includes/header.php";
}

//load the default database into memory and compare it with the active database
	require_once "includes/lib_schema.php";
	db_upgrade_schema ($db, $db_type, $db_name, $display_results);

if ($display_results) {
	echo "<br />\n";
	echo "<br />\n";
	require_once "includes/footer.php";
}

?>