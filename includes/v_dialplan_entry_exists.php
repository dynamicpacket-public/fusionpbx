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
if (ifgroup("user") || ifgroup("admin") || ifgroup("superadmin")) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

	// Recordings, make sure it exists in the dialplan if not add it
		$v_recording_action = 'add';
		$sql = "";
		$sql .= "select * from v_dialplan_includes ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and opt1name = 'recordings' ";
		$sql .= "and opt1value = '732673' ";
		$prepstatement2 = $db->prepare($sql);
		$prepstatement2->execute();
		while($row2 = $prepstatement2->fetch()) {
			$v_recording_action = 'update';
			break; //limit to 1 row
		}
		unset ($sql, $prepstatement2);
		//echo "action: ".$action."<br />";

		if ($v_recording_action == 'add') {
			//create recordings extension in the dialplan
				$extensionname = 'Recordings';
				$dialplanorder ='9001';
				$context = 'default';
				$enabled = 'true';
				$descr = '*732673 default system recordings tool';
				$opt1name = 'recordings';
				$opt1value = '732673';
				$dialplan_include_id = v_dialplan_includes_add($v_id, $extensionname, $dialplanorder, $context, $enabled, $descr, $opt1name, $opt1value);

				$tag = 'condition'; //condition, action, antiaction
				$fieldtype = 'destination_number';
				$fielddata = '^\*(732673)$';
				$fieldorder = '000';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

				$tag = 'action'; //condition, action, antiaction
				$fieldtype = 'javascript';
				$fielddata = 'recordings.js';
				$fieldorder = '001';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);
		}


	// DISA make sure it exists in the dialplan if not add it
		$v_disa_action = 'add';
		$sql = "";
		$sql .= "select * from v_dialplan_includes ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and opt1name = 'disa' ";
		$sql .= "and opt1value = '3472' ";
		$prepstatement2 = $db->prepare($sql);
		$prepstatement2->execute();
		while($row2 = $prepstatement2->fetch()) {
			$v_disa_action = 'update';
			break; //limit to 1 row
		}
		unset ($sql, $prepstatement2);
		//echo "action: ".$action."<br />";

		if ($v_disa_action == 'add') {
			//create recordings extension in the dialplan
				$extensionname = 'DISA';
				$dialplanorder ='9001';
				$context = 'default';
				$enabled = 'true';
				$descr = '*3472 Direct Inward System Access ';
				$opt1name = 'disa';
				$opt1value = '3472';
				$dialplan_include_id = v_dialplan_includes_add($v_id, $extensionname, $dialplanorder, $context, $enabled, $descr, $opt1name, $opt1value);

				$tag = 'condition'; //condition, action, antiaction
				$fieldtype = 'destination_number';
				$fielddata = '^\*(3472)$';
				$fieldorder = '000';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

				$tag = 'action'; //condition, action, antiaction
				$fieldtype = 'javascript';
				$fielddata = 'disa.js';
				$fieldorder = '001';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);
		}

	// synchronize the dialplan
		if ($v_recording_action == 'add' || $v_disa_action == 'add') {
			sync_package_v_dialplan_includes();
		}

?>