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
require_once "includes/paging.php";
if (ifgroup("admin") || ifgroup("superadmin")) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//set the http get/post variable(s) to a php variable
	if (isset($_REQUEST["id"])) {
		$ivr_menu_id = $_GET["id"];
	}

//get the v_ivr_menu data 
	$sql = "";
	$sql .= "select * from v_ivr_menu ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and ivr_menu_id = '$ivr_menu_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$ivr_menu_name = $row["ivr_menu_name"];
		$ivr_menu_extension = $row["ivr_menu_extension"];
		$ivr_menu_greet_long = $row["ivr_menu_greet_long"];
		$ivr_menu_greet_short = $row["ivr_menu_greet_short"];
		$ivr_menu_invalid_sound = $row["ivr_menu_invalid_sound"];
		$ivr_menu_exit_sound = $row["ivr_menu_exit_sound"];
		$ivr_menu_confirm_macro = $row["ivr_menu_confirm_macro"];
		$ivr_menu_confirm_key = $row["ivr_menu_confirm_key"];
		$ivr_menu_tts_engine = $row["ivr_menu_tts_engine"];
		$ivr_menu_tts_voice = $row["ivr_menu_tts_voice"];
		$ivr_menu_confirm_attempts = $row["ivr_menu_confirm_attempts"];
		$ivr_menu_timeout = $row["ivr_menu_timeout"];
		$ivr_menu_inter_digit_timeout = $row["ivr_menu_inter_digit_timeout"];
		$ivr_menu_max_failures = $row["ivr_menu_max_failures"];
		$ivr_menu_max_timeouts = $row["ivr_menu_max_timeouts"];
		$ivr_menu_digit_len = $row["ivr_menu_digit_len"];
		$ivr_menu_direct_dial = $row["ivr_menu_direct_dial"];
		$ivr_menu_enabled = $row["ivr_menu_enabled"];
		$ivr_menu_desc = 'copy: '.$row["ivr_menu_desc"];
		break; //limit to 1 row
	}
	unset ($prepstatement);

	//copy the v_ivr_menu
		$sql = "insert into v_ivr_menu ";
		$sql .= "(";
		$sql .= "v_id, ";
		$sql .= "ivr_menu_name, ";
		$sql .= "ivr_menu_extension, ";
		$sql .= "ivr_menu_greet_long, ";
		$sql .= "ivr_menu_greet_short, ";
		$sql .= "ivr_menu_invalid_sound, ";
		$sql .= "ivr_menu_exit_sound, ";
		$sql .= "ivr_menu_confirm_macro, ";
		$sql .= "ivr_menu_confirm_key, ";
		$sql .= "ivr_menu_tts_engine, ";
		$sql .= "ivr_menu_tts_voice, ";
		$sql .= "ivr_menu_confirm_attempts, ";
		$sql .= "ivr_menu_timeout, ";
		$sql .= "ivr_menu_inter_digit_timeout, ";
		$sql .= "ivr_menu_max_failures, ";
		$sql .= "ivr_menu_max_timeouts, ";
		$sql .= "ivr_menu_digit_len, ";
		$sql .= "ivr_menu_direct_dial, ";
		$sql .= "ivr_menu_enabled, ";
		$sql .= "ivr_menu_desc ";
		$sql .= ")";
		$sql .= "values ";
		$sql .= "(";
		$sql .= "'$v_id', ";
		$sql .= "'$ivr_menu_name', ";
		$sql .= "'$ivr_menu_extension', ";
		$sql .= "'$ivr_menu_greet_long', ";
		$sql .= "'$ivr_menu_greet_short', ";
		$sql .= "'$ivr_menu_invalid_sound', ";
		$sql .= "'$ivr_menu_exit_sound', ";
		$sql .= "'$ivr_menu_confirm_macro', ";
		$sql .= "'$ivr_menu_confirm_key', ";
		$sql .= "'$ivr_menu_tts_engine', ";
		$sql .= "'$ivr_menu_tts_voice', ";
		$sql .= "'$ivr_menu_confirm_attempts', ";
		$sql .= "'$ivr_menu_timeout', ";
		$sql .= "'$ivr_menu_inter_digit_timeout', ";
		$sql .= "'$ivr_menu_max_failures', ";
		$sql .= "'$ivr_menu_max_timeouts', ";
		$sql .= "'$ivr_menu_digit_len', ";
		$sql .= "'$ivr_menu_direct_dial', ";
		$sql .= "'$ivr_menu_enabled', ";
		$sql .= "'$ivr_menu_desc' ";
		$sql .= ")";
		$db->exec(check_sql($sql));
		$db_ivr_menu_id = $db->lastInsertId($id);
		unset($sql);

	//get the the ivr menu options
		$sql = "";
		$sql .= "select * from v_ivr_menu_options ";
		$sql .= "where ivr_menu_id = '$ivr_menu_id' ";
		$sql .= "and v_id = '$v_id' ";
		$sql .= "order by ivr_menu_id asc ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$ivr_menu_options_digits = $row["ivr_menu_options_digits"];
			$ivr_menu_options_action = $row["ivr_menu_options_action"];
			$ivr_menu_options_param = $row["ivr_menu_options_param"];
			$ivr_menu_options_order = $row["ivr_menu_options_order"];
			$ivr_menu_options_desc = $row["ivr_menu_options_desc"];

			//copy the ivr menu options
				$sql = "insert into v_ivr_menu_options ";
				$sql .= "(";
				$sql .= "v_id, ";
				$sql .= "ivr_menu_id, ";
				$sql .= "ivr_menu_options_digits, ";
				$sql .= "ivr_menu_options_action, ";
				$sql .= "ivr_menu_options_param, ";
				$sql .= "ivr_menu_options_order, ";
				$sql .= "ivr_menu_options_desc ";
				$sql .= ")";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'$v_id', ";
				$sql .= "'$db_ivr_menu_id', ";
				$sql .= "'$ivr_menu_options_digits', ";
				$sql .= "'$ivr_menu_options_action', ";
				$sql .= "'$ivr_menu_options_param', ";
				$sql .= "'$ivr_menu_options_order', ";
				$sql .= "'$ivr_menu_options_desc' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql);
		}

	//synchronize the xml config
		sync_package_v_ivr_menu();

	//redirect the user
		require_once "includes/header.php";
		echo "<meta http-equiv=\"refresh\" content=\"2;url=v_ivr_menu.php\">\n";
		echo "<div align='center'>\n";
		echo "Copy Complete\n";
		echo "</div>\n";
		require_once "includes/footer.php";
		return;

?>