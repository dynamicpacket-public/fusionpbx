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

//if the are no groups add the default groups
	$sql = "SELECT * FROM v_groups ";
	$sql .= "where v_id = '$v_id' ";
	$sub_result = $db->query($sql)->fetch();
	$prep_statement = $db->prepare(check_sql($sql));
	if ($prep_statement) {
		$prep_statement->execute();
		$sub_result = $prep_statement->fetchAll(PDO::FETCH_ASSOC);
		if (count($sub_result) == 0) {
			$sql = "INSERT INTO v_groups (v_id, groupid, groupdesc) VALUES ($v_id,'hidden','Hidden Group hides items in the menu');"; $db->exec(check_sql($sql));
			$sql = "INSERT INTO v_groups (v_id, groupid, groupdesc) VALUES ($v_id,'user','User Group');"; $db->exec(check_sql($sql));
			$sql = "INSERT INTO v_groups (v_id, groupid, groupdesc) VALUES ($v_id,'agent','Call Center Agent Group');"; $db->exec(check_sql($sql));
			$sql = "INSERT INTO v_groups (v_id, groupid, groupdesc) VALUES ($v_id,'admin','Administrator Group');"; $db->exec(check_sql($sql));
			$sql = "INSERT INTO v_groups (v_id, groupid, groupdesc) VALUES ($v_id,'superadmin','Super Administrator Group');"; $db->exec(check_sql($sql));
		}
	}
	unset($prep_statement, $sub_result);

//if there are no permissions listed in v_group_permissions then set the default permissions
	$sql = "";
	$sql .= "select count(*) as count from v_group_permissions ";
	$sql .= "where v_id = $v_id ";
	$prep_statement = $db->prepare($sql);
	$prep_statement->execute();
	$sub_result = $prep_statement->fetch(PDO::FETCH_ASSOC);
	unset ($prep_statement);
	if ($sub_result['count'] > 0) {
		if ($display_type == "text") {
			echo "	Group Permissions:	no change\n";
		}
	}
	else {
		if ($display_type == "text") {
			echo "	Group Permissions:	added\n";
		}
		//no permissions found add the defaults
		$db->beginTransaction();
		foreach($apps as $app) {
			foreach ($app['permissions'] as $sub_row) {
				foreach ($sub_row['groups'] as $group) {
					//add the record
					$sql = "insert into v_group_permissions ";
					$sql .= "(";
					$sql .= "v_id, ";
					$sql .= "permission_id, ";
					$sql .= "group_id ";
					$sql .= ")";
					$sql .= "values ";
					$sql .= "(";
					$sql .= "'$v_id', ";
					$sql .= "'".$sub_row['name']."', ";
					$sql .= "'".$group."' ";
					$sql .= ")";
					$db->exec($sql);
					unset($sql);
				}
			}
		}
		$db->commit();
	}

?>