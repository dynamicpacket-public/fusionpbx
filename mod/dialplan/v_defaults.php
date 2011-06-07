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

//if the disa dialplan entry does not exist then add it
	$sql = "select dialplan_include_id from v_dialplan_includes_details ";
	$sql .= "where fielddata like 'disa.lua' ";
	$sql .= "and v_id = '$v_id' ";
	$result = $db->query($sql)->fetch();
	$prepstatement = $db->prepare(check_sql($sql));
	if ($prepstatement) {
		$prepstatement->execute();
		$result = $prepstatement->fetchAll(PDO::FETCH_ASSOC);
		if (count($result) == 0) {
			//add the disa dialplan entry
				$sql = "INSERT INTO v_dialplan_includes (v_id, extensionname, extensioncontinue, dialplanorder, context, enabled, descr, opt1name, opt1value) VALUES(".$v_id.",'DISA','',900,'default','true','*3472 Direct Inward System Access ','disa',3472);";
				if ($db_type == "sqlite" || $db_type == "mysql" ) {
					$db->exec(check_sql($sql));
					$dialplan_include_id = $db->lastInsertId($id);
				}
				if ($db_type == "pgsql") {
					$sql .= " RETURNING dialplan_include_id ";
					$prepstatement = $db->prepare(check_sql($sql));
					$prepstatement->execute();
					$result = $prepstatement->fetchAll();
					foreach ($result as &$row) {
						$dialplan_include_id = $row["dialplan_include_id"];
					}
					unset($prepstatement, $result);
				}
			//add the disa dialplan inclue entry
				$sql = "INSERT INTO v_dialplan_includes_details (v_id, dialplan_include_id, parent_id, tag, fieldorder, fieldtype, fielddata, fieldbreak) VALUES(".$v_id.",".$dialplan_include_id.",NULL,'condition',0,'destination_number','^\\*3472$','');"; $db->exec(check_sql($sql));
				$sql = "INSERT INTO v_dialplan_includes_details (v_id, dialplan_include_id, parent_id, tag, fieldorder, fieldtype, fielddata, fieldbreak) VALUES(".$v_id.",".$dialplan_include_id.",NULL,'action',1,'set','pin_number=".generate_password(6, 1)."','');"; $db->exec(check_sql($sql));
				$sql = "INSERT INTO v_dialplan_includes_details (v_id, dialplan_include_id, parent_id, tag, fieldorder, fieldtype, fielddata, fieldbreak) VALUES(".$v_id.",".$dialplan_include_id.",NULL,'action',2,'lua','disa.lua','');"; $db->exec(check_sql($sql));
		}
	}
	unset($prepstatement, $result);

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
?>