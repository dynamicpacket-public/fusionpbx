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
if (permission_exists('system_settings_delete')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//get the id
	if (count($_GET)>0) {
		$id = check_str($_GET["id"]);
	}

//allow delete for ids other than v_id = '1'
	if ($id == "1") {
		// id 1 is used for the system defaults and for some system wide tools do not allow it to be deleted.
	}
	else {
		//get the $apps array from the installed apps from the core and mod directories
			$config_list = glob($_SERVER["DOCUMENT_ROOT"] . PROJECT_PATH . "/*/*/v_config.php");
			$x=0;
			foreach ($config_list as &$config_path) {
				include($config_path);
				$x++;
			}

		//set the needle
			if (count($_SESSION["domains"]) > 1) {
				$v_needle = 'v_'.$v_domain.'_';
			}
			else {
				$v_needle = 'v_';
			}

		//get the domain using the id
			if (strlen($id)>0) {
				$sql = "";
				$sql .= "select * from v_system_settings ";
				$sql .= "where v_id = '$id' ";
				$prepstatement = $db->prepare(check_sql($sql));
				$prepstatement->execute();
				$result = $prepstatement->fetchAll();
				foreach ($result as &$row) {
					$v_domain = $row["v_domain"];
					$v_recordings_dir = $row["v_recordings_dir"];
					$v_dialplan_default_dir = $row["v_dialplan_default_dir"];
					$v_extensions_dir = $row["v_extensions_dir"];
					$v_dialplan_public_dir = $row["v_dialplan_public_dir"];
					$v_scripts_dir = $row["v_scripts_dir"];
					break; //limit to 1 row
				}
				unset ($prepstatement);
			}

		//delete the system_settings entry by the id and all child data
			if (strlen($id)>0) {
				$db->beginTransaction();
				foreach ($apps as &$app) {
					foreach ($app['db'] as $row) {
						$table_name = $row['table'];
						$sql = "delete from $table_name where v_id = '$id' ";
						$db->query($sql);
					}
				}
				$db->commit();
			}

		//delete the extension
			unlink($v_conf_dir.'/directory/'.$v_domain.'.xml');
			if (strlen($v_extensions_dir) > 0) {
				system("rm -rf ".$v_extensions_dir);
			}

		//delete the gateways
			if($dh = opendir($v_gateways_dir."")) {
				$files = Array();
				while($file = readdir($dh)) {
					if($file != "." && $file != ".." && $file[0] != '.') {
						if(is_dir($dir . "/" . $file)) {
							//this is a directory do nothing
						} else {
							//check if file extension is xml
							if (strpos($file, $v_needle) !== false && substr($file,-4) == '.xml') {
								unlink($v_gateways_dir."/".$file);
							}
						}
					}
				}
				closedir($dh);
			}

		//delete the dialplan
			unlink($v_conf_dir.'/dialplan/'.$v_domain.'.xml');
			if (strlen($v_dialplan_default_dir) > 0) {
				system("rm -rf ".$v_dialplan_default_dir);
			}

		//delete the recordings
			if (strlen($v_recordings_dir) > 0) {
				system("rm -rf ".$v_recordings_dir);
			}

		//delete the ivr menu
			if($dh = opendir($v_conf_dir."/ivr_menus/")) {
				$files = Array();
				while($file = readdir($dh)) {
					if($file != "." && $file != ".." && $file[0] != '.') {
						if(is_dir($dir . "/" . $file)) {
							//this is a directory
						} else {
							if (strpos($file, $v_needle) !== false && substr($file,-4) == '.xml') {
								//echo "file: $file<br />\n";
								unlink($v_conf_dir."/ivr_menus/".$file);
							}
						}
					}
				}
				closedir($dh);
			}

		//delete the public dialplan
			if (substr($v_dialplan_public_dir, - strlen($v_domain)) == $v_domain) {
				system("rm -rf ".$v_dialplan_public_dir);
				unlink(substr($v_dialplan_public_dir, 0, ($v_dialplan_public_dir - (strlen($v_domain) +1)))."/".$v_domain.".xml");
			}

		//delete the hunt group lua scripts
			$v_prefix = 'v_huntgroup_'.$v_domain.'_';
			if($dh = opendir($v_scripts_dir)) {
				$files = Array();
				while($file = readdir($dh)) {
					if($file != "." && $file != ".." && $file[0] != '.') {
						if(is_dir($dir . "/" . $file)) {
							//this is a directory
						} else {
							if (substr($file,0, strlen($v_prefix)) == $v_prefix && substr($file,-4) == '.lua') {
								unlink($v_scripts_dir.'/'.$file);
							}
						}
					}
				}
				closedir($dh);
			}

		//apply settings reminder
			$_SESSION["reload_xml"] = true;

		//clear the domains session array so that it is updated
			unset($_SESSION["domains"]);
	}

//redirect the user
	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"2;url=v_system_settings.php\">\n";
	echo "<div align='center'>\n";
	echo "Delete Complete\n";
	echo "</div>\n";
	require_once "includes/footer.php";
	return;

?>
