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

//if the recordings directory doesn't exist then create it
	if (!is_dir($v_recordings_dir)) { mkdir($v_recordings_dir,0777,true); }

//if the recordings dialplan entry does not exist then add it
	$sql = "select dialplan_include_id from v_dialplan_includes_details ";
	$sql .= "where fielddata like 'recordings.lua' ";
	$sql .= "and v_id = '$v_id' ";
	$result = $db->query($sql)->fetch();
	$prepstatement = $db->prepare(check_sql($sql));
	if ($prepstatement) {
		$prepstatement->execute();
		$result = $prepstatement->fetchAll(PDO::FETCH_ASSOC);
		if (count($result) == 0) {
			//add the recordings dialplan entry
				$sql = "INSERT INTO v_dialplan_includes (v_id, extensionname, extensioncontinue, dialplanorder, context, enabled, descr, opt1name, opt1value) VALUES(".$v_id.",'Recordings','',900,'default','true','*732 default system recordings tool','recordings',732);";
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
			//add the recordings dialplan inclue entry
				$sql = "INSERT INTO v_dialplan_includes_details (v_id, dialplan_include_id, parent_id, tag, fieldorder, fieldtype, fielddata, fieldbreak) VALUES (".$v_id.",".$dialplan_include_id.",NULL,'condition',0,'destination_number','^\\*732$','');"; $db->exec(check_sql($sql));
				$sql = "INSERT INTO v_dialplan_includes_details (v_id, dialplan_include_id, parent_id, tag, fieldorder, fieldtype, fielddata, fieldbreak) VALUES (".$v_id.",".$dialplan_include_id.",NULL,'action',1,'set','recordings_dir=$v_recordings_dir','');"; $db->exec(check_sql($sql));
				$sql = "INSERT INTO v_dialplan_includes_details (v_id, dialplan_include_id, parent_id, tag, fieldorder, fieldtype, fielddata, fieldbreak) VALUES (".$v_id.",".$dialplan_include_id.",NULL,'action',2,'set','pin_number=".generate_password(4, 1)."','');"; $db->exec(check_sql($sql));
				$sql = "INSERT INTO v_dialplan_includes_details (v_id, dialplan_include_id, parent_id, tag, fieldorder, fieldtype, fielddata, fieldbreak) VALUES (".$v_id.",".$dialplan_include_id.",NULL,'action',3,'lua','recordings.lua','');"; $db->exec(check_sql($sql));
		}
		else {
			//update the recordings dialplan entry
				foreach ($result as &$row) {
					$dialplan_include_id = $row['dialplan_include_id'];
					$sql = "update v_dialplan_includes_details set";
					$sql .= "fielddata = 'recordings_dir=".$v_recordings_dir."' ";
					$sql .= "and v_id = '$v_id' ";
					$db->exec(check_sql($sql));
				}
		}
	}
	unset($prepstatement, $result);

?>