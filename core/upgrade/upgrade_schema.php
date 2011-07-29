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

		//get the list of installed apps from the core and mod directories and execute the php code in v_defaults.php
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