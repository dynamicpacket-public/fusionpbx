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
if (permission_exists('system_settings_default')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//action add or update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$v_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//set the required directories
	require_once "includes/lib_system_settings_default.php";

//add to v_system settings
	$action = "add";
	if ($action == "add") {
		$sql = "insert into v_system_settings ";
		$sql .= "(";
		$sql .= "v_domain, ";
		$sql .= "php_dir, ";
		$sql .= "tmp_dir, ";
		$sql .= "bin_dir, ";
		$sql .= "v_startup_script_dir, ";
		//$sql .= "v_package_version, ";
		$sql .= "v_build_version, ";
		$sql .= "v_build_revision, ";
		$sql .= "v_label, ";
		$sql .= "v_name, ";
		$sql .= "v_dir, ";
		$sql .= "v_parent_dir, ";
		$sql .= "v_backup_dir, ";
		$sql .= "v_web_dir, ";
		$sql .= "v_web_root, ";
		$sql .= "v_relative_url, ";
		$sql .= "v_conf_dir, ";
		$sql .= "v_db_dir, ";
		$sql .= "v_htdocs_dir, ";
		$sql .= "v_log_dir, ";
		$sql .= "v_extensions_dir, ";
		$sql .= "v_gateways_dir, ";
		$sql .= "v_dialplan_public_dir, ";
		$sql .= "v_dialplan_default_dir, ";
		$sql .= "v_mod_dir, ";
		$sql .= "v_scripts_dir, ";
		$sql .= "v_grammar_dir, ";
		$sql .= "v_storage_dir, ";
		$sql .= "v_voicemail_dir, ";
		$sql .= "v_recordings_dir, ";
		$sql .= "v_sounds_dir, ";
		//$sql .= "v_download_path, ";
		$sql .= "v_provisioning_tftp_dir, ";
		$sql .= "v_provisioning_ftp_dir, ";
		$sql .= "v_provisioning_https_dir, ";
		$sql .= "v_provisioning_http_dir ";
		$sql .= ")";
		$sql .= "values ";
		$sql .= "(";
		$sql .= "'$v_domain', ";
		$sql .= "'$php_dir', ";
		$sql .= "'$tmp_dir', ";
		$sql .= "'$bin_dir', ";
		$sql .= "'$v_startup_script_dir', ";
		//$sql .= "'$v_package_version', ";
		$sql .= "'$v_build_version', ";
		$sql .= "'$v_build_revision', ";
		$sql .= "'$v_label', ";
		$sql .= "'$v_name', ";
		$sql .= "'$v_dir', ";
		$sql .= "'$v_parent_dir', ";
		$sql .= "'$v_backup_dir', ";
		$sql .= "'$v_web_dir', ";
		$sql .= "'$v_web_root', ";
		$sql .= "'$v_relative_url', ";
		$sql .= "'$v_conf_dir', ";
		$sql .= "'$v_db_dir', ";
		$sql .= "'$v_htdocs_dir', ";
		$sql .= "'$v_log_dir', ";
		$sql .= "'$v_extensions_dir', ";
		$sql .= "'$v_gateways_dir', ";
		$sql .= "'$v_dialplan_public_dir', ";
		$sql .= "'$v_dialplan_default_dir', ";
		$sql .= "'$v_mod_dir', ";
		$sql .= "'$v_scripts_dir', ";
		$sql .= "'$v_grammar_dir', ";
		$sql .= "'$v_storage_dir', ";
		$sql .= "'$v_voicemail_dir', ";
		$sql .= "'$v_recordings_dir', ";
		$sql .= "'$v_sounds_dir', ";
		//$sql .= "'$v_download_path', ";
		$sql .= "'$v_provisioning_tftp_dir', ";
		$sql .= "'$v_provisioning_ftp_dir', ";
		$sql .= "'$v_provisioning_https_dir', ";
		$sql .= "'$v_provisioning_http_dir' ";
		$sql .= ")";
		$db->exec(check_sql($sql));
		unset($sql);
	}

//restore the defaults in the database
	if ($action == "update") {
		$sql = "update v_system_settings set ";
		$sql .= "php_dir = '$install_php_dir', ";
		$sql .= "tmp_dir = '$install_tmp_dir', ";
		$sql .= "bin_dir = '$v_bin_dir', ";
		$sql .= "v_startup_script_dir = '$v_startup_script_dir', ";
		$sql .= "v_package_version = '$v_package_version', ";
		$sql .= "v_build_version = '$v_build_version', ";
		$sql .= "v_build_revision = '$v_build_revision', ";
		$sql .= "v_label = '$v_label', ";
		$sql .= "v_name = '$v_name', ";
		$sql .= "v_dir = '$install_v_dir', ";
		$sql .= "v_parent_dir = '$v_parent_dir', ";
		$sql .= "v_backup_dir = '$install_v_backup_dir', ";
		$sql .= "v_web_dir = '$v_web_dir', ";
		$sql .= "v_web_root = '$v_web_root', ";
		$sql .= "v_relative_url = '$v_relative_url', ";
		$sql .= "v_conf_dir = '$v_conf_dir', ";
		$sql .= "v_db_dir = '$v_db_dir', ";
		$sql .= "v_htdocs_dir = '$v_htdocs_dir', ";
		$sql .= "v_log_dir = '$v_log_dir', ";
		$sql .= "v_mod_dir = '$v_mod_dir', ";
		$sql .= "v_extensions_dir = '$v_extensions_dir', ";
		$sql .= "v_gateways_dir = '$v_gateways_dir', ";
		$sql .= "v_dialplan_public_dir = '$v_dialplan_public_dir', ";
		$sql .= "v_dialplan_default_dir = '$v_dialplan_default_dir', ";
		$sql .= "v_scripts_dir = '$v_scripts_dir', ";
		$sql .= "v_grammar_dir = '$v_grammar_dir', ";
		$sql .= "v_storage_dir = '$v_storage_dir', ";
		$sql .= "v_voicemail_dir = '$v_voicemail_dir', ";
		$sql .= "v_recordings_dir = '$v_recordings_dir', ";
		$sql .= "v_sounds_dir = '$v_sounds_dir', ";
		$sql .= "v_download_path = '$v_download_path' ";
		//$sql .= "v_provisioning_tftp_dir = '$v_provisioning_tftp_dir', ";
		//$sql .= "v_provisioning_ftp_dir = '$v_provisioning_ftp_dir', ";
		//$sql .= "v_provisioning_https_dir = '$v_provisioning_https_dir', ";
		//$sql .= "v_provisioning_http_dir = '$v_provisioning_http_dir' ";
		$sql .= "where v_id = '$v_id'";
		$db->exec($sql);
		unset($sql);
	}

//redirect to the system settings page
	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"2;url=v_system_settings.php\">\n";
	echo "<div align='center'>\n";
	echo "Restore Complete\n";
	echo "</div>\n";
	require_once "includes/footer.php";
	return;

?>