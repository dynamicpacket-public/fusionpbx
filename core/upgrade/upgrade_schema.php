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

//check the permission
	if(defined('STDIN')) {
		$document_root = str_replace("\\", "/", $_SERVER["PHP_SELF"]);
		preg_match("/^(.*)\/core\/.*$/", $document_root, $matches);
		$document_root = $matches[1];
		set_include_path($document_root);
		require_once "includes/config.php";
		$_SERVER["DOCUMENT_ROOT"] = $document_root;
		$display_type = 'text'; //html, text
	}
	else {
		include "root.php";
		require_once "includes/config.php";
		require_once "includes/checkauth.php";
		if (permission_exists('upgrade_schema') || ifgroup("superadmin")) {
			//echo "access granted";
		}
		else {
			echo "access denied";
			exit;
		}
		require_once "includes/header.php";
		$display_type = 'html'; //html, text
	}

//set the default
	if (!isset($display_results)) {
		$display_results = true;
	}

//load the default database into memory and compare it with the active database
	require_once "includes/lib_schema.php";
	db_upgrade_schema ($db, $db_type, $db_name, $display_results);
	unset($apps);

//get the list of installed apps from the core and mod directories
	$config_list = glob($_SERVER["DOCUMENT_ROOT"] . PROJECT_PATH . "/*/*/v_config.php");
	$x=0;
	foreach ($config_list as &$config_path) {
		include($config_path);
		$x++;
	}

//find the v_conf_dir by the v_id
	$sql = "";
	$sql .= "select * from v_system_settings ";
	$sql .= "where v_id = '".$v_id."' ";
	$row = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
	$v_conf_dir = $row['v_conf_dir'];

//make sure that prefix-a-leg is set to true in the xml_cdr.conf.xml file
	$file_contents = file_get_contents($v_conf_dir."/autoload_configs/xml_cdr.conf.xml");
	$file_contents_new = str_replace("param name=\"prefix-a-leg\" value=\"false\"/", "param name=\"prefix-a-leg\" value=\"true\"/", $file_contents);
	if ($file_contents != $file_contents_new) {
		$fout = fopen($v_conf_dir."/autoload_configs/xml_cdr.conf.xml","w");
		fwrite($fout, $file_contents_new);
		fclose($fout);
		if ($display_type == "text") {
			echo "	xml_cdr.conf.xml: 	updated\n";
		}
	}

//make sure that enum uses sofia internal in the enum.conf.xml file
	$file_contents = file_get_contents($v_conf_dir."/autoload_configs/enum.conf.xml");
	$file_contents_new = str_replace("service=\"E2U+SIP\" regex=\"sip:(.*)\" replace=\"sofia/\${use_profile}/\$1", "service=\"E2U+SIP\" regex=\"sip:(.*)\" replace=\"sofia/internal/\$1", $file_contents);
	if ($file_contents != $file_contents_new) {
		$fout = fopen($v_conf_dir."/autoload_configs/enum.conf.xml","w");
		fwrite($fout, $file_contents_new);
		fclose($fout);
		if ($display_type == "text") {
			echo "	enum.conf.xml: 	updated\n";
		}
	}

//loop through all domains in v_system_settings
	$sql = "";
	$sql .= "select * from v_system_settings ";
	$v_prep_statement = $db->prepare(check_sql($sql));
	$v_prep_statement->execute();
	$main_result = $v_prep_statement->fetchAll(PDO::FETCH_ASSOC);
	foreach ($main_result as &$row) {
		//get the values from database and set them as php variables
			$v_id = $row["v_id"];
			$v_domain = $row["v_domain"];
			$v_account_code = $row["v_account_code"];
			$v_server_protocol = $row["v_server_protocol"];
			$v_server_port = $row["v_server_port"];
			$php_dir = $row["php_dir"];
			$tmp_dir = $row["tmp_dir"];
			$bin_dir = $row["bin_dir"];
			$v_startup_script_dir = $row["v_startup_script_dir"];
			$v_package_version = $row["v_package_version"];
			$v_build_version = $row["v_build_version"];
			$v_build_revision = $row["v_build_revision"];
			$v_label = $row["v_label"];
			$v_name = $row["v_name"];
			$v_dir = $row["v_dir"];
			$v_parent_dir = $row["v_parent_dir"];
			$v_backup_dir = $row["v_backup_dir"];
			$v_web_dir = $row["v_web_dir"];
			$v_web_root = $row["v_web_root"];
			$v_relative_url = $row["v_relative_url"];
			$v_conf_dir = $row["v_conf_dir"];
			$v_db_dir = $row["v_db_dir"];
			$v_htdocs_dir = $row["v_htdocs_dir"];
			$v_log_dir = $row["v_log_dir"];
			$v_extensions_dir = $row["v_extensions_dir"];
			$v_gateways_dir = $row["v_gateways_dir"];
			$v_dialplan_public_dir = $row["v_dialplan_public_dir"];
			$v_dialplan_default_dir = $row["v_dialplan_default_dir"];
			$v_mod_dir = $row["v_mod_dir"];
			$v_scripts_dir = $row["v_scripts_dir"];
			$v_grammar_dir = $row["v_grammar_dir"];
			$v_storage_dir = $row["v_storage_dir"];
			$v_voicemail_dir = $row["v_voicemail_dir"];
			$v_recordings_dir = $row["v_recordings_dir"];
			$v_sounds_dir = $row["v_sounds_dir"];
			$v_download_path = $row["v_download_path"];
			$v_provisioning_tftp_dir = $row["v_provisioning_tftp_dir"];
			$v_provisioning_ftp_dir = $row["v_provisioning_ftp_dir"];
			$v_provisioning_https_dir = $row["v_provisioning_https_dir"];
			$v_provisioning_http_dir = $row["v_provisioning_http_dir"];
			$v_template_name = $row["v_template_name"];
			$v_time_zone = $row["v_time_zone"];
			$v_description = $row["v_description"];

		//show the domain when display_type is set to text
				if ($display_type == "text") {
					echo "\n";
					echo $v_domain;
					echo "\n";
				}

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

		//if the dialplan directory doesn't exist then create it
			if (!is_dir($v_dialplan_default_dir)) { mkdir($v_dialplan_default_dir,0777,true); }

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
				$fieldorder = '001';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);

				$tag = 'action'; //condition, action, antiaction
				$fieldtype = 'lua';
				$fielddata = 'recordings.lua';
				$fieldorder = '002';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);
				break; //limit to 1 row
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
				$context = 'default';
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
				$fieldtype = 'lua';
				$fielddata = 'disa.lua';
				$fieldorder = '002';
				v_dialplan_includes_details_add($v_id, $dialplan_include_id, $tag, $fieldorder, $fieldtype, $fielddata);
				break; //limit to 1 row
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

		//if the extensions directory doesn't exist then create it
			if (!is_dir($v_extensions_dir)) { mkdir($v_extensions_dir,0777,true); }

		//if there are multiple domains then update the public dir path to include the domain
			if (count($_SESSION["domains"]) > 1) {
				if (substr($v_dialplan_public_dir, -7) == "/public") {
					//clear out the old xml files
						$v_needle = '_v_';
						if($dh = opendir($v_dialplan_public_dir."/")) {
							$files = Array();
							while($file = readdir($dh)) {
								if($file != "." && $file != ".." && $file[0] != '.') {
									if(is_dir($dir . "/" . $file)) {
										//this is a directory
									} else {
										if (strpos($file, $v_needle) !== false && substr($file,-4) == '.xml') {
											unlink($v_dialplan_public_dir."/".$file);
										}
									}
								}
							}
							closedir($dh);
						}
					//add the domain to the public dir path
						$v_dialplan_public_dir = $v_dialplan_public_dir.'/'.$_SESSION['domains'][$v_id]['domain'];
						$sql .= "update v_system_settings set ";
						$sql .= "v_dialplan_public_dir = '".$v_dialplan_public_dir."' ";
						$sql .= "where v_id = '$v_id' ";
						$db->exec($sql);
						unset($sql);
						if ($display_type == "text") {
							echo "	Public Directory:	added domain\n";
						}
					//synch the xml files
						sync_package_v_public_includes();
				}
			}

		//if the public directory doesn't exist then create it
			if (!is_dir($v_dialplan_public_dir)) { mkdir($v_dialplan_public_dir,0777,true); }

		//if multiple domains then make sure that the dialplan/public/domain_name.xml file exists
			if (count($_SESSION["domains"]) > 1) {
				//make sure the public xml file includes the domain directory
				$file = $v_conf_dir."/dialplan/public/".$_SESSION['domains'][$v_id]['domain'].".xml";
				if (!file_exists($file)) {
					$fout = fopen($file,"w");
					$tmpxml = "<include>\n";
					$tmpxml .= "  <X-PRE-PROCESS cmd=\"include\" data=\"".$_SESSION['domains'][$v_id]['domain']."/*.xml\"/>\n";
					$tmpxml .= "</include>\n";
					fwrite($fout, $tmpxml);
					fclose($fout);
					unset($tmpxml,$file);
				}
			}

		//if the recordings directory doesn't exist then create it
			if (!is_dir($v_recordings_dir)) { mkdir($v_recordings_dir,0777,true); }

		//get the list of installed apps from the core and mod directories
			$default_list = glob($_SERVER["DOCUMENT_ROOT"] . PROJECT_PATH . "/*/*/v_defaults.php");
			foreach ($default_list as &$default_path) {
				include($default_path);
			}

	} //end the loop
	unset ($v_prep_statement);

if ($display_results && $display_type == "html") {
	echo "<br />\n";
	echo "<br />\n";
	require_once "includes/footer.php";
}

?>