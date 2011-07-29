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

//if the dialplan default directory doesn't exist then create it
	if (!is_dir($v_dialplan_default_dir)) { mkdir($v_dialplan_default_dir,0777,true); }
//write the dialplan/default.xml if it does not exist
	//get the contents of the dialplan/default.xml
		$file_default_path = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/templates/conf/dialplan/default.xml';
		$file_default_contents = file_get_contents($file_default_path);
	//prepare the file contents and the path
		if (count($_SESSION['domains']) < 2) {
			//replace the variables in the template in the future loop through all the line numbers to do a replace for each possible line number
				$file_default_contents = str_replace("{v_domain}", 'default', $file_default_contents);
			//set the file path
				$file_path = $v_conf_dir.'/dialplan/default.xml';
		}
		else {
			//replace the variables in the template in the future loop through all the line numbers to do a replace for each possible line number
				$file_default_contents = str_replace("{v_domain}", $v_domain, $file_default_contents);
			//set the file path
				$file_path = $v_conf_dir.'/dialplan/'.$v_domain.'.xml';
		}

	//write the default dialplan
		if (!file_exists($file_path)) {
			$fh = fopen($file_path,'w') or die('Unable to write to '.$file_path.'. Make sure the path exists and permissons are set correctly.');
			fwrite($fh, $file_default_contents);
			fclose($fh);
		}

// add a recordings dialplan entry if it doesn't exist
	$v_recording_action = 'add';
	$sql = "";
	$sql .= "select * from v_dialplan_includes ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and opt1name = 'recordings' ";
	$sql .= "and (opt1value = '732' or opt1value = '732673') ";
	$prep_statement = $db->prepare($sql);
	$prep_statement->execute();
	while($sub_row = $prep_statement->fetch(PDO::FETCH_ASSOC)) {
		$v_recording_action = 'update';
		break; //limit to 1 row
	}
	unset ($sql, $prep_statement);
	if ($v_recording_action == 'add') {
		if ($display_type == "text") {
			echo "	Dialplan Recording: 	added\n";
		}
		$extensionname = 'Recordings';
		$dialplanorder ='900';
		$context = 'default';
		$enabled = 'true';
		$descr = '*732 default system recordings tool';
		$opt1name = 'recordings';
		$opt1value = '732';
		$dialplan_include_id = v_dialplan_includes_add($v_id, $extensionname, $dialplanorder, $context, $enabled, $descr, $opt1name, $opt1value);

		$tag = 'condition'; //condition, action, antiaction
		$fieldtype = 'destination_number';
		$fielddata = '^\*(732)$';
		$fieldorder = '000';
		v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

		$tag = 'action'; //condition, action, antiaction
		$fieldtype = 'set';
		$fielddata = 'recordings_dir='.$v_recordings_dir;
		$fieldorder = '001';
		v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

		$tag = 'action'; //condition, action, antiaction
		$fieldtype = 'set';
		$fielddata = 'recording_slots=true';
		$fieldorder = '002';
		v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

		$tag = 'action'; //condition, action, antiaction
		$fieldtype = 'set';
		$fielddata = 'recording_prefix=recording';
		$fieldorder = '003';
		v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

		$tag = 'action'; //condition, action, antiaction
		$fieldtype = 'set';
		$fielddata = 'pin_number='.generate_password(6, 1);
		$fieldorder = '004';
		v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

		$tag = 'action'; //condition, action, antiaction
		$fieldtype = 'lua';
		$fielddata = 'recordings.lua';
		$fieldorder = '005';
		v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);
	}
	else {
		if ($display_type == "text") {
			echo "	Dialplan Recording: 	no change\n";
		}
	}

// add a disa dialplan entry if it doesn't exist
	$v_disa_action = 'add';
	$sql = "";
	$sql .= "select * from v_dialplan_includes ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and opt1name = 'disa' ";
	$sql .= "and opt1value = '3472' ";
	$prep_statement = $db->prepare($sql);
	$prep_statement->execute();
	while($sub_row = $prep_statement->fetch(PDO::FETCH_ASSOC)) {
		$v_disa_action = 'update';
		break; //limit to 1 row
	}
	unset ($sql, $prep_statement);
	if ($v_disa_action == 'add') {
		if ($display_type == "text") {
			echo "	Dialplan DISA: 		added\n";
		}
		$extensionname = 'DISA';
		$dialplanorder ='900';
		$context = $_SESSION['context'];
		$enabled = 'false';
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
		$fieldtype = 'set';
		$fielddata = 'pin_number='.generate_password(6, 1);
		$fieldorder = '001';
		v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

		$tag = 'action'; //condition, action, antiaction
		$fieldtype = 'set';
		$fielddata = 'context='.$_SESSION['context'];
		$fieldorder = '002';
		v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

		$tag = 'action'; //condition, action, antiaction
		$fieldtype = 'lua';
		$fielddata = 'disa.lua';
		$fieldorder = '003';
		v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);
	}
	else {
		if ($display_type == "text") {
			echo "	Dialplan DISA: 		no change\n";
		}
	}

// synchronize the dialplan
	if ($v_recording_action == 'add' || $v_disa_action == 'add') {
		sync_package_v_dialplan_includes();
	}
?>