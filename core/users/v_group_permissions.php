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
require_once "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
if (permission_exists('group_permissions') || ifgroup("superadmin")) {
	//access granted
}
else {
	echo "access denied";
	exit;
}
require_once "includes/header.php";
require_once "includes/paging.php";

//get the list of installed apps from the core and mod directories
	$config_list = glob($_SERVER["DOCUMENT_ROOT"] . PROJECT_PATH . "/*/*/v_config.php");
	$x=0;
	foreach ($config_list as &$config_path) {
		include($config_path);
		$x++;
	}

//if there are no permissions listed in v_group_permissions then set the default permissions
	$sql = "";
	$sql .= "select count(*) as count from v_group_permissions ";
	$prep_statement = $db->prepare(check_sql($sql));
	$prep_statement->execute();
	$result = $prep_statement->fetchAll();
	foreach ($result as &$row) {
		$group_permission_count = $row["count"];
		break; //limit to 1 row
	}
	unset ($prep_statement);
	if ($group_permission_count == 0) {
		//no permissions found add the defaults
		foreach($apps as $app) {
			foreach ($app['permissions'] as $row) {
				foreach ($row['groups'] as $group) {
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
					$sql .= "'".$row['name']."', ";
					$sql .= "'".$group."' ";
					$sql .= ")";
					$db->exec(check_sql($sql));
					unset($sql);
				}
			}
		}
	}

//get the http values and set them as php variables
	$group_id = $_REQUEST['group_id'];
	
//get the permissions assigned to this group
	$sql = "";
	$sql .= " select * from v_group_permissions ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and group_id = '$group_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$permission_id = $row["permission_id"];
		$permissions_db[$permission_id] = "true";
	}
//show the db checklist
	//echo "<pre>";
	//print_r($permissions_db);
	//echo "</pre>";

//list all the permissions in the database
	foreach($apps as $app) {
		foreach ($app['permissions'] as $row) {
			if ($permissions_db[$row['name']] == "true") {
				$permissions_db_checklist[$row['name']] = "true";
			}
			else {
				$permissions_db_checklist[$row['name']] = "false";
			}
		}
	}
//show the db checklist
	//echo "<pre>";
	//print_r($permissions_db_checklist);
	//echo "</pre>";

//process the http post
	if (count($_POST)>0) {
		foreach($_POST['permissions_form'] as $permission) {
			$permissions_form[$permission] = "true";
		}

		//list all the permissions
			foreach($apps as $app) {
				foreach ($app['permissions'] as $row) {
					if ($permissions_form[$row['name']] == "true") {
						$permissions_form_checklist[$row['name']] = "true";
					}
					else {
						$permissions_form_checklist[$row['name']] = "false";
					}
				}
			}
		//show the form db checklist
			//echo "<pre>";
			//print_r($permissions_form_checklist);
			//echo "</pre>";
		
		//list all the permissions
			foreach($apps as $app) {
				foreach ($app['permissions'] as $row) {
					$permission = $row['name'];
					if ($permissions_db_checklist[$permission] == "true" && $permissions_form_checklist[$permission] == "true") {
						//matched do nothing
					}
					if ($permissions_db_checklist[$permission] == "false" && $permissions_form_checklist[$permission] == "false") {
						//matched do nothing
					}
					if ($permissions_db_checklist[$permission] == "true" && $permissions_form_checklist[$permission] == "false") {
						//delete the record
							$sql = "delete from v_group_permissions ";
							$sql .= "where v_id = '$v_id' ";
							$sql .= "and group_id = '$group_id' ";
							$sql .= "and permission_id = '$permission' ";
							$db->exec(check_sql($sql));
							unset($sql);
						//set the permission to false in the permissions_db_checklist
							$permissions_db_checklist[$permission] = "false";
					}
					if ($permissions_db_checklist[$permission] == "false" && $permissions_form_checklist[$permission] == "true") {
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
							$sql .= "'$permission', ";
							$sql .= "'$group_id' ";
							$sql .= ")";
							$db->exec(check_sql($sql));
							unset($sql);
						//set the permission to true in the permissions_db_checklist
							$permissions_db_checklist[$permission] = "true";
					}
				}
			}
	}

//show the content
	echo "<form method='post' name='frm' action=''>\n";
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";
	echo "<tr class='border'>\n";
	echo "	<td align=\"center\">\n";
	echo "		<br>";

	echo "<table width='100%' border='0'>\n";
	echo "<tr>\n";
	echo "<td width='50%' align=\"left\" nowrap=\"nowrap\"><b>Group Permission List for $group_id</b></td>\n";
	echo "<td width='50%' align=\"right\">\n";
	echo "	<input type='button' class='btn' name='' alt='back' onclick=\"window.location='grouplist.php'\" value='Back'> ";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"left\" colspan='2'>\n";
	echo "	Assign permissions to groups.<br /><br />\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</tr></table>\n";

	echo "<br />\n";

	$c = 0;
	$row_style["0"] = "rowstyle0";
	$row_style["1"] = "rowstyle1";

	echo "<div align='left'>\n";

	//list all the permissions
		foreach($apps as $app) {
			$app_name = $app['name'];
			$description = $app['description']['en'];

			echo "<strong>".$app_name."</strong><br />\n";
			echo "".$description."<br /><br />";
			echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
			echo "<tr>\n";
			echo "<th>Permissions</th>\n";
			echo "<th>Description</th>\n";
			echo "<tr>\n";

			foreach ($app['permissions'] as $row) {
				echo "<tr >\n";
				echo "	<td valign='top' style='width:250px' nowrap='nowrap' class='".$row_style[$c]."'>\n";
				if ($permissions_db_checklist[$row['name']] == "true") {
					echo "		<input type='checkbox' name='permissions_form[]' checked='checked' value='".$row['name']."'>\n";
				}
				else {
					echo "		<input type='checkbox' name='permissions_form[]' value='".$row['name']."'>\n";
				}
				echo "		&nbsp; ".$row['name']."\n";
				echo "	</td>\n";
				echo "	<td valign='top' class='".$row_style[$c]."'>\n";
				echo "		&nbsp; ".$row['description']."\n";
				echo "	</td>\n";
				echo "</tr>\n";
				if ($c==0) { $c=1; } else { $c=0; }
			}
			
			echo "<tr>\n";
			echo "	<td colspan='3' align='right'>\n";
			echo "		<input type='submit' name='submit' class='btn' value='Save'>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			echo "</table>";
			echo "<br />\n";
		} //end foreach
		unset($sql, $result, $row_count);

	echo "</div>";
	echo "<br><br>";
	echo "<br><br>";

	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</div>";
	echo "<form>\n";

	echo "<br><br>";

//show the footer
	require_once "includes/footer.php";

?>
