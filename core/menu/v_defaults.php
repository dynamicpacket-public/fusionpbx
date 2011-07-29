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

//if there are no items in the menu then add the default menu
	$sql = "SELECT * FROM v_menu where v_id = '$v_id' ";
	$prep_statement = $db->prepare(check_sql($sql));
	if ($prep_statement) {
		$prep_statement->execute();
		$sub_result = $prep_statement->fetchAll(PDO::FETCH_ASSOC);
		if (count($sub_result) == 0) {
			require_once "includes/classes/menu_restore.php";
			$menu_restore = new menu_restore;
			$menu_restore->db = $db;
			$menu_restore->v_id = $v_id;
			$menu_restore->restore();
			unset($menu_restore);
			if ($display_type == "text") {
				echo "	Menu:			added\n";
			}
		}
		else {
			if ($display_type == "text") {
				echo "	Menu:			no change\n";
			}
		}
	}
	unset($prep_statement, $sub_result);

//if there are no groups listed in v_menu_groups then add the default groups
	$sql = "";
	$sql .= "select count(*) as count from v_menu_groups ";
	$sql .= "where v_id = $v_id ";
	$prep_statement = $db->prepare($sql);
	$prep_statement->execute();
	$sub_result = $prep_statement->fetch(PDO::FETCH_ASSOC);
	unset ($prep_statement);
	if ($sub_result['count'] > 0) {
		if ($display_type == "text") {
			echo "	Menu Groups:		no change\n";
		}
	}
	else {
		if ($display_type == "text") {
			echo "	Menu Groups:		added\n";
		}
		//no menu groups found add the defaults
			$db->beginTransaction();
			foreach($apps as $app) {
				foreach ($app['menu'] as $sub_row) {
					foreach ($sub_row['groups'] as $group) {
						//add the record
						$sql = "insert into v_menu_groups ";
						$sql .= "(";
						$sql .= "v_id, ";
						$sql .= "menu_guid, ";
						$sql .= "group_id ";
						$sql .= ")";
						$sql .= "values ";
						$sql .= "(";
						$sql .= "'$v_id', ";
						$sql .= "'".$sub_row['guid']."', ";
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