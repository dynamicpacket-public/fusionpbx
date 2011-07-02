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
if (permission_exists('system_settings_add') || permission_exists('system_settings_edit')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//set the action as an add or an update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$v_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//set the http values to variables
	if (count($_POST)>0) {
		$v_id = check_str($_POST["v_id"]);
		$v_domain = check_str($_POST["v_domain"]);
		$v_account_code = check_str($_POST["v_account_code"]);
		$v_server_protocol = check_str($_POST["v_server_protocol"]);
		$v_server_port = check_str($_POST["v_server_port"]);
		$php_dir = check_str($_POST["php_dir"]);
		$tmp_dir = check_str($_POST["tmp_dir"]);
		$bin_dir = check_str($_POST["bin_dir"]);
		$v_startup_script_dir = check_str($_POST["v_startup_script_dir"]);
		$v_package_version = check_str($_POST["v_package_version"]);
		$v_build_version = check_str($_POST["v_build_version"]);
		$v_build_revision = check_str($_POST["v_build_revision"]);
		$v_label = check_str($_POST["v_label"]);
		$v_name = check_str($_POST["v_name"]);
		$v_dir = check_str($_POST["v_dir"]);
		$v_parent_dir = check_str($_POST["v_parent_dir"]);
		$v_backup_dir = check_str($_POST["v_backup_dir"]);
		$v_web_dir = check_str($_POST["v_web_dir"]);
		$v_web_root = check_str($_POST["v_web_root"]);
		$v_relative_url = check_str($_POST["v_relative_url"]);
		$v_conf_dir = check_str($_POST["v_conf_dir"]);
		$v_db_dir = check_str($_POST["v_db_dir"]);
		$v_htdocs_dir = check_str($_POST["v_htdocs_dir"]);
		$v_log_dir = check_str($_POST["v_log_dir"]);
		$v_extensions_dir = check_str($_POST["v_extensions_dir"]);
		$v_gateways_dir = check_str($_POST["v_gateways_dir"]);
		$v_dialplan_public_dir = check_str($_POST["v_dialplan_public_dir"]);
		$v_dialplan_default_dir = check_str($_POST["v_dialplan_default_dir"]);
		$v_mod_dir = check_str($_POST["v_mod_dir"]);
		$v_scripts_dir = check_str($_POST["v_scripts_dir"]);
		$v_grammar_dir = check_str($_POST["v_grammar_dir"]);
		$v_storage_dir = check_str($_POST["v_storage_dir"]);
		$v_voicemail_dir = check_str($_POST["v_voicemail_dir"]);
		$v_recordings_dir = check_str($_POST["v_recordings_dir"]);
		$v_sounds_dir = check_str($_POST["v_sounds_dir"]);
		$v_download_path = check_str($_POST["v_download_path"]);
		$v_provisioning_tftp_dir = check_str($_POST["v_provisioning_tftp_dir"]);
		$v_provisioning_ftp_dir = check_str($_POST["v_provisioning_ftp_dir"]);
		$v_provisioning_https_dir = check_str($_POST["v_provisioning_https_dir"]);
		$v_provisioning_http_dir = check_str($_POST["v_provisioning_http_dir"]);
		$v_template_name = check_str($_POST["v_template_name"]);
		$v_time_zone = check_str($_POST["v_time_zone"]);
		$v_description = check_str($_POST["v_description"]);

		if (strlen($v_template_name) > 0) {
			$_SESSION["v_template_name"] = $v_template_name;
			$_SESSION["template_name"] = $v_template_name;
			$_SESSION["template_content"] = '';
		}
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$preference_id = check_str($_POST["v_id"]);
	}

	//check for all required data
		//if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		if (strlen($v_domain) == 0) { $msg .= "Please provide: Domain<br>\n"; }
		//if (strlen($v_account_code) == 0) { $msg .= "Please provide: Agent Code<br>\n"; }
		//if (strlen($v_server_protocol) == 0) { $msg .= "Please provide: Web Server Protocol<br>\n"; }
		//if (strlen($v_server_port) == 0) { $msg .= "Please provide: Web Server Port<br>\n"; }
		if (strlen($php_dir) == 0) { $msg .= "Please provide: PHP Directory<br>\n"; }
		if (strlen($tmp_dir) == 0) { $msg .= "Please provide: Temp Directory<br>\n"; }
		if (strlen($bin_dir) == 0) { $msg .= "Please provide: Bin Directory<br>\n"; }
		if (strlen($v_startup_script_dir) == 0) { $msg .= "Please provide: Startup Script Directory<br>\n"; }
		//if (strlen($v_package_version) == 0) { $msg .= "Please provide: Package Version<br>\n"; }
		//if (strlen($v_build_version) == 0) { $msg .= "Please provide: Build Version<br>\n"; }
		//if (strlen($v_build_revision) == 0) { $msg .= "Please provide: Build Revision<br>\n"; }
		if (strlen($v_label) == 0) { $msg .= "Please provide: Label<br>\n"; }
		if (strlen($v_name) == 0) { $msg .= "Please provide: Name<br>\n"; }
		//if (strlen($v_description) == 0) { $msg .= "Please provide: Description<br>\n"; }
		//if (strlen($v_dir) == 0) { $msg .= "Please provide: FreeSWITCH Directory<br>\n"; }
		//if (strlen($v_parent_dir) == 0) { $msg .= "Please provide: Parent Directory<br>\n"; }
		if (strlen($v_backup_dir) == 0) { $msg .= "Please provide: Backup Directory<br>\n"; }
		if (strlen($v_web_dir) == 0) { $msg .= "Please provide: Web Directory<br>\n"; }
		if (strlen($v_web_root) == 0) { $msg .= "Please provide: Web Root<br>\n"; }
		if (strlen($v_relative_url) == 0) { $msg .= "Please provide: Relative URL<br>\n"; }
		//if (strlen($v_conf_dir) == 0) { $msg .= "Please provide: Conf Directory<br>\n"; }
		//if (strlen($v_db_dir) == 0) { $msg .= "Please provide: Database Directory<br>\n"; }
		if (strlen($v_htdocs_dir) == 0) { $msg .= "Please provide: htdocs Directory<br>\n"; }
		if (strlen($v_log_dir) == 0) { $msg .= "Please provide: Log Directory<br>\n"; }
		//if (strlen($v_extensions_dir) == 0) { $msg .= "Please provide: Extensions Directory<br>\n"; }
		//if (strlen($v_gateways_dir) == 0) { $msg .= "Please provide: Gateways Directory<br>\n"; }
		//if (strlen($v_dialplan_public_dir) == 0) { $msg .= "Please provide: Dialplan Public Directory<br>\n"; }
		//if (strlen($v_dialplan_default_dir) == 0) { $msg .= "Please provide: Dialplan Default Directory<br>\n"; }
		//if (strlen($v_mod_dir) == 0) { $msg .= "Please provide: Mod Directory<br>\n"; }
		//if (strlen($v_scripts_dir) == 0) { $msg .= "Please provide: Scripts Directory<br>\n"; }
		//if (strlen($v_grammar_dir) == 0) { $msg .= "Please provide: Grammar Directory<br>\n"; }
		//if (strlen($v_storage_dir) == 0) { $msg .= "Please provide: Storage Directory<br>\n"; }
		//if (strlen($v_voicemail_dir) == 0) { $msg .= "Please provide: Voicemail Directory<br>\n"; }
		//if (strlen($v_recordings_dir) == 0) { $msg .= "Please provide: Recordings Directory<br>\n"; }
		//if (strlen($v_sounds_dir) == 0) { $msg .= "Please provide: Sounds Directory<br>\n"; }
		//if (strlen($v_download_path) == 0) { $msg .= "Please provide: Download Path<br>\n"; }
		//if (strlen($v_provisioning_tftp_dir) == 0) { $msg .= "Please provide: Provisioning TFTP Directory<br>\n"; }
		//if (strlen($v_provisioning_ftp_dir) == 0) { $msg .= "Please provide: Provisioning FTP Directory<br>\n"; }
		//if (strlen($v_provisioning_https_dir) == 0) { $msg .= "Please provide: Provisioning HTTPS Directory<br>\n"; }
		//if (strlen($v_provisioning_http_dir) == 0) { $msg .= "Please provide: Provisioning HTTP Directory<br>\n"; }
		//if (strlen($v_template_name) == 0) { $msg .= "Please provide: Template Name<br>\n"; }
		//if (strlen($v_time_zone) == 0) { $msg .= "Please provide: Time Zone<br>\n"; }

		if (strlen($msg) > 0 && strlen($_POST["persistformvar"]) == 0) {
			require_once "includes/header.php";
			require_once "includes/persistformvar.php";
			echo "<div align='center'>\n";
			echo "<table><tr><td>\n";
			echo $msg."<br />";
			echo "</td></tr></table>\n";
			persistformvar($_POST);
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
		}

	//add or update the database
		if ($_POST["persistformvar"] != "true") {
			if ($action == "add" && permission_exists('system_settings_add')) {
				//insert a row into system settings
					$sql = "insert into v_system_settings ";
					$sql .= "(";
					$sql .= "v_domain, ";
					$sql .= "v_account_code, ";
					$sql .= "v_server_protocol, ";
					$sql .= "v_server_port, ";
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
					//$sql .= "v_download_path, ";
					$sql .= "v_sounds_dir, ";
					$sql .= "v_provisioning_tftp_dir, ";
					$sql .= "v_provisioning_ftp_dir, ";
					$sql .= "v_provisioning_https_dir, ";
					$sql .= "v_provisioning_http_dir, ";
					$sql .= "v_template_name, ";
					$sql .= "v_time_zone, ";
					$sql .= "v_description ";
					$sql .= ")";
					$sql .= "values ";
					$sql .= "(";
					$sql .= "'$v_domain', ";
					$sql .= "'$v_account_code', ";
					$sql .= "'$v_server_protocol', ";
					$sql .= "'$v_server_port', ";
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
					//$sql .= "'$v_download_path', ";
					$sql .= "'$v_sounds_dir', ";
					$sql .= "'$v_provisioning_tftp_dir', ";
					$sql .= "'$v_provisioning_ftp_dir', ";
					$sql .= "'$v_provisioning_https_dir', ";
					$sql .= "'$v_provisioning_http_dir', ";
					$sql .= "'$v_template_name', ";
					$sql .= "'$v_time_zone', ";
					$sql .= "'$v_description' ";
					$sql .= ")";
					if ($db_type == "sqlite" || $db_type == "mysql" ) {
							$db->exec(check_sql($sql));
							$v_id = $db->lastInsertId($id);
					}
					if ($db_type == "pgsql") {
							$sql .= " RETURNING v_id ";
							$prepstatement = $db->prepare(check_sql($sql));
							$prepstatement->execute();
							$result = $prepstatement->fetchAll();
							foreach ($result as &$row) {
								$v_id = $row["v_id"];
							}
							unset($prepstatement, $result);
					}
					unset($sql);
			} //if ($action == "add")

			if ($action == "update" && permission_exists('system_settings_edit')) {
				//update the system settings
					$sql = "update v_system_settings set ";
					$sql .= "v_domain = '$v_domain', ";
					$sql .= "v_account_code = '$v_account_code', ";
					$sql .= "v_server_protocol = '$v_server_protocol', ";
					$sql .= "v_server_port = '$v_server_port', ";
					$sql .= "php_dir = '$php_dir', ";
					$sql .= "tmp_dir = '$tmp_dir', ";
					$sql .= "bin_dir = '$bin_dir', ";
					$sql .= "v_startup_script_dir = '$v_startup_script_dir', ";
					//$sql .= "v_package_version = '$v_package_version', ";
					$sql .= "v_build_version = '$v_build_version', ";
					$sql .= "v_build_revision = '$v_build_revision', ";
					$sql .= "v_label = '$v_label', ";
					$sql .= "v_name = '$v_name', ";
					$sql .= "v_dir = '$v_dir', ";
					$sql .= "v_parent_dir = '$v_parent_dir', ";
					$sql .= "v_backup_dir = '$v_backup_dir', ";
					$sql .= "v_web_dir = '$v_web_dir', ";
					$sql .= "v_web_root = '$v_web_root', ";
					$sql .= "v_relative_url = '$v_relative_url', ";
					$sql .= "v_conf_dir = '$v_conf_dir', ";
					$sql .= "v_db_dir = '$v_db_dir', ";
					$sql .= "v_htdocs_dir = '$v_htdocs_dir', ";
					$sql .= "v_log_dir = '$v_log_dir', ";
					$sql .= "v_extensions_dir = '$v_extensions_dir', ";
					$sql .= "v_gateways_dir = '$v_gateways_dir', ";
					$sql .= "v_dialplan_public_dir = '$v_dialplan_public_dir', ";
					$sql .= "v_dialplan_default_dir = '$v_dialplan_default_dir', ";
					$sql .= "v_mod_dir = '$v_mod_dir', ";
					$sql .= "v_scripts_dir = '$v_scripts_dir', ";
					$sql .= "v_grammar_dir = '$v_grammar_dir', ";
					$sql .= "v_storage_dir = '$v_storage_dir', ";
					$sql .= "v_voicemail_dir = '$v_voicemail_dir', ";
					$sql .= "v_recordings_dir = '$v_recordings_dir', ";
					$sql .= "v_sounds_dir = '$v_sounds_dir', ";
					//$sql .= "v_download_path = '$v_download_path', ";
					$sql .= "v_provisioning_tftp_dir = '$v_provisioning_tftp_dir', ";
					$sql .= "v_provisioning_ftp_dir = '$v_provisioning_ftp_dir', ";
					$sql .= "v_provisioning_https_dir = '$v_provisioning_https_dir', ";
					$sql .= "v_provisioning_http_dir = '$v_provisioning_http_dir', ";
					$sql .= "v_template_name = '$v_template_name', ";
					$sql .= "v_time_zone = '$v_time_zone', ";
					$sql .= "v_description = '$v_description' ";
					$sql .= "where v_id = '$v_id'";
					$db->exec(check_sql($sql));
					unset($sql);
			} //if ($action == "update")

			//upgrade the database schema and ensure necessary files and directories exist
				$display_results = false;
				require_once "core/upgrade/upgrade_schema.php";

			//clear the domains session array so that it is updated
				unset($_SESSION["domains"]);

			//redirect the user
				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_system_settings.php\">\n";
				echo "<div align='center'>\n";
				if ($action == "add") {
					echo "Add Complete\n";
				}
				if ($action == "update") {
					echo "Update Complete\n";
				}
				echo "</div>\n";
				require_once "includes/footer.php";
				return;

		} //if ($_POST["persistformvar"] != "true")
} //(count($_POST)>0 && strlen($_POST["persistformvar"]) == 0)

//pre-populate the form
	if (count($_GET)>0 && $_POST["persistformvar"] != "true" && $action == "update") {
		$preference_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_system_settings ";
		$sql .= "where v_id = '$v_id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
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
			break; //limit to 1 row
		}
		unset ($prepstatement);
	}

//clear values
	if ($action == "add") {
		$v_domain = '';
		$v_account_code = '';
	}

//set defaults if value is emtpy
	if (strlen($v_server_port) == 0) { $v_server_port = $_SERVER["SERVER_PORT"]; }
	if (strlen($v_server_protocol) == 0) { 
		if ($v_server_port == "80") { $v_server_protocol = "http"; }
		if ($v_server_port == "443") { $v_server_protocol = "https"; }
	}
	if (strlen($v_gateways_dir) == 0) { $v_gateways_dir = $v_conf_dir.'/sip_profiles/external'; }
	if (strlen($v_voicemail_dir) == 0) { $v_voicemail_dir = $v_storage_dir.'/voicemail'; }
	if (strlen($v_dialplan_public_dir) == 0) { $v_dialplan_public_dir = $v_conf_dir.'/dialplan/public'; }
	if (strlen($v_extensions_dir) == 0) { $v_extensions_dir = $v_conf_dir.'/directory/default'; }
	if (strlen($v_dialplan_default_dir) == 0) { $v_dialplan_default_dir = $v_conf_dir.'/dialplan/default'; }

//show the header
	require_once "includes/header.php";

//show the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";
	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "		<br>";

	echo "<form method='post' name='frm' action=''>\n";
	echo "<div align='center'>\n";
	echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";

	echo "<tr>\n";
	if ($action == "add") {
		echo "<td align='left' width='30%' nowrap><b>System Settings Add</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align='left' width='30%' nowrap><b>System Settings Edit</b></td>\n";
	}
	echo "<td width='70%' align='right'>\n";
	echo "<input type='button' class='btn' value='Restore Default' onclick=\"document.location.href='v_system_settings_default.php?id=".$v_id."';\" />";
	echo "	<input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_system_settings.php'\" value='Back'>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Domain:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_domain' maxlength='255' value=\"$v_domain\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Account Code:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_account_code' maxlength='255' value=\"$v_account_code\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Web Server Protocol:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_server_protocol' maxlength='255' value=\"$v_server_protocol\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Web Server Port:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_server_port' maxlength='255' value=\"$v_server_port\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    PHP Bin Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='php_dir' maxlength='255' value=\"$php_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Temp Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='tmp_dir' maxlength='255' value=\"$tmp_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    FreeSWITCH Bin Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='bin_dir' maxlength='255' value=\"$bin_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Startup Script Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_startup_script_dir' maxlength='255' value=\"$v_startup_script_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	//echo "<tr>\n";
	//echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	//echo "    Package Version:\n";
	//echo "</td>\n";
	//echo "<td class='vtable' align='left'>\n";
	//echo "    <input class='formfld' type='text' name='v_package_version' maxlength='255' value=\"$v_package_version\">\n";
	//echo "<br />\n";
	//echo "\n";
	//echo "</td>\n";
	//echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Build Version:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_build_version' maxlength='255' value=\"$v_build_version\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Build Revision:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_build_revision' maxlength='255' value=\"$v_build_revision\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Label:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_label' maxlength='255' value=\"$v_label\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_name' maxlength='255' value=\"$v_name\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    FreeSWITCH Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_dir' maxlength='255' value=\"$v_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Parent Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_parent_dir' maxlength='255' value=\"$v_parent_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Backup Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_backup_dir' maxlength='255' value=\"$v_backup_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Web Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_web_dir' maxlength='255' value=\"$v_web_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Web Root:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_web_root' maxlength='255' value=\"$v_web_root\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Relative URL:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_relative_url' maxlength='255' value=\"$v_relative_url\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Conf Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_conf_dir' maxlength='255' value=\"$v_conf_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Database Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_db_dir' maxlength='255' value=\"$v_db_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    htdocs Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_htdocs_dir' maxlength='255' value=\"$v_htdocs_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Log Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_log_dir' maxlength='255' value=\"$v_log_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Mod Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_mod_dir' maxlength='255' value=\"$v_mod_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Extensions Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_extensions_dir' maxlength='255' value=\"$v_extensions_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Gateways Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_gateways_dir' maxlength='255' value=\"$v_gateways_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Dialplan Public Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_dialplan_public_dir' maxlength='255' value=\"$v_dialplan_public_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Dialplan Default Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_dialplan_default_dir' maxlength='255' value=\"$v_dialplan_default_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Scripts Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_scripts_dir' maxlength='255' value=\"$v_scripts_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Grammar Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_grammar_dir' maxlength='255' value=\"$v_grammar_dir\">\n";
	echo "<br />\n";
	echo "Enter the grammar directory.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Storage Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_storage_dir' maxlength='255' value=\"$v_storage_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Voicemail Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='v_voicemail_dir' maxlength='255' value=\"$v_voicemail_dir\">\n";
	echo "<br />\n";
	echo "Enter the voicemail directory.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Recordings Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_recordings_dir' maxlength='255' value=\"$v_recordings_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Sounds Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_sounds_dir' maxlength='255' value=\"$v_sounds_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	//echo "<tr>\n";
	//echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	//echo "    Download Path:\n";
	//echo "</td>\n";
	//echo "<td class='vtable' align='left'>\n";
	//echo "    <input class='formfld' type='text' name='v_download_path' maxlength='255' value=\"$v_download_path\">\n";
	//echo "<br />\n";
	//echo "\n";
	//echo "</td>\n";
	//echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Provisioning TFTP Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_provisioning_tftp_dir' maxlength='255' value=\"$v_provisioning_tftp_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Provisioning FTP Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_provisioning_ftp_dir' maxlength='255' value=\"$v_provisioning_ftp_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Provisioning HTTPS Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_provisioning_https_dir' maxlength='255' value=\"$v_provisioning_https_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Provisioning HTTP Directory:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='v_provisioning_http_dir' maxlength='255' value=\"$v_provisioning_http_dir\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "	<tr>\n";
	echo "	<td width='20%' class=\"vncell\" style='text-align: left;'>\n";
	echo "		Template: \n";
	echo "	</td>\n";
	echo "	<td class=\"vtable\" align='left'>\n";
	echo "		<select id='v_template_name' name='v_template_name' class='formfld' style=''>\n";
	echo "		<option value=''></option>\n";
	$theme_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/themes';
	if ($handle = opendir($_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/themes')) {
		while (false !== ($dir_name = readdir($handle))) {
			if ($dir_name != "." && $dir_name != ".." && $dir_name != ".svn" && is_dir($theme_dir.'/'.$dir_name)) {
				$dir_label = str_replace('_', ' ', $dir_name);
				$dir_label = str_replace('-', ' ', $dir_label);
				if ($dir_name == $v_template_name) {
					echo "		<option value='$dir_name' selected='selected'>$dir_label</option>\n";
				}
				else {
					echo "		<option value='$dir_name'>$dir_label</option>\n";
				}
			}
		}
		closedir($handle);
	}
	echo "		</select>\n";
	echo "		<br />\n";
	echo "		Select a template to set as the default.<br />\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	
	echo "	<tr>\n";
	echo "	<td width='20%' class=\"vncell\" style='text-align: left;'>\n";
	echo "		Time Zone: \n";
	echo "	</td>\n";
	echo "	<td class=\"vtable\" align='left'>\n";
	echo "		<select id='v_time_zone' name='v_time_zone' class='formfld' style=''>\n";
	echo "		<option value=''></option>\n";
	//$list = DateTimeZone::listAbbreviations();
    $time_zone_identifiers = DateTimeZone::listIdentifiers();
	$previous_category = '';
	$x = 0;
	foreach ($time_zone_identifiers as $key => $row) {
		$tz = explode("/", $row);
		$category = $tz[0];
		if ($category != $previous_category) {
			if ($x > 0) {
				echo "		</optgroup>\n";
			}
			echo "		<optgroup label='".$category."'>\n";
		}
		if ($row == $v_time_zone) {
			echo "			<option value='".$row."' selected='selected'>".$row."</option>\n";
		}
		else {
			echo "			<option value='".$row."'>".$row."</option>\n";
		}
		$previous_category = $category;
		$x++;
	}
	echo "		</select>\n";
	echo "		<br />\n";
	echo "		Select the default time zone.<br />\n";
	echo "	</td>\n";
	echo "	</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "		Description:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "		<input class='formfld' type='text' name='v_description' maxlength='255' value=\"$v_description\">\n";
	echo "		<br />\n";
	echo "		Enter the description.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "	<td colspan='2' align='right'>\n";
	if ($action == "update") {
		echo "			<input type='hidden' name='v_id' value='$v_id'>\n";
	}
	echo "			<input type='submit' name='submit' class='btn' value='Save'>\n";
	echo "	</td>\n";
	echo "</tr>";
	echo "</table>";
	echo "</form>";

	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

//show the footer
	require_once "includes/footer.php";
?>
