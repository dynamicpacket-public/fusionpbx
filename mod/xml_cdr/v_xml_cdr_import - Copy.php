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

//set debug
	$debug = false; //true //false

//increase limits
	set_time_limit(3600);
	ini_set('memory_limit', '256M');

function process_xml_cdr($db, $v_log_dir, $leg, $xml_string) {

	//set global variable
		global $debug;

	//parse the xml to get the call detail record info
		try {
			$xml = simplexml_load_string($xml_string);
		}
		catch(Exception $e) {
			echo $e->getMessage();
		}

	//get the variables from the xml
		$uuid = check_str(urldecode($xml->variables->uuid));
		$domain_name = check_str(urldecode($xml->variables->domain_name));
		$accountcode = check_str(urldecode($xml->variables->accountcode));
		$direction = check_str(urldecode($xml->variables->call_direction));
		$default_language = check_str(urldecode($xml->variables->default_language));
		$xml_string = check_str($xml_string);
		$start_epoch = check_str(urldecode($xml->variables->start_epoch));
		$start_stamp = check_str(urldecode($xml->variables->start_stamp));
		$start_uepoch = check_str(urldecode($xml->variables->start_uepoch));
		$answer_stamp = check_str(urldecode($xml->variables->answer_stamp));
		$answer_epoch = check_str(urldecode($xml->variables->answer_epoch));
		$answer_uepoch = check_str(urldecode($xml->variables->answer_uepoch));
		$end_epoch = check_str(urldecode($xml->variables->end_epoch));
		$end_uepoch = check_str(urldecode($xml->variables->end_uepoch));
		$end_stamp = check_str(urldecode($xml->variables->end_stamp));
		$duration = check_str(urldecode($xml->variables->duration));
		$mduration = check_str(urldecode($xml->variables->mduration));
		$billsec = check_str(urldecode($xml->variables->billsec));
		$billmsec = check_str(urldecode($xml->variables->billmsec));
		$bridge_uuid = check_str(urldecode($xml->variables->bridge_uuid));
		$read_codec = check_str(urldecode($xml->variables->read_codec));
		$write_codec = check_str(urldecode($xml->variables->write_codec));
		$remote_media_ip = check_str(urldecode($xml->variables->remote_media_ip));
		$hangup_cause = check_str(urldecode($xml->variables->hangup_cause));
		$hangup_cause_q850 = check_str(urldecode($xml->variables->hangup_cause_q850));
		$x = 0;
		foreach ($xml->callflow as $row) {
			if ($x == 0) {
				$destination_number = check_str(urldecode($row->caller_profile->destination_number));
				$context = check_str(urldecode($row->caller_profile->context));
				$network_addr = check_str(urldecode($row->caller_profile->network_addr));
			}
			$caller_id_name = check_str(urldecode($row->caller_profile->caller_id_name));
			$caller_id_number = check_str(urldecode($row->caller_profile->caller_id_number));
			$x++;
		}
		unset($x);

	//get break down the date to year, month and day
		$tmp_time = strtotime($start_stamp);
		$tmp_year = date("Y", $tmp_time);
		$tmp_month = date("M", $tmp_time);
		$tmp_day = date("d", $tmp_time);

	//find the v_id by using the domain
		if (strlen($domain_name) == 0) { $domain_name = $_SERVER["HTTP_HOST"]; }
		$sql = "";
		$sql .= "select v_id, v_recordings_dir from v_system_settings ";
		$sql .= "where v_domain = '".$domain_name."' ";
		$row = $db->query($sql)->fetch();
		$v_id = $row['v_id'];
		$v_recordings_dir = $row['v_recordings_dir'];
		if (strlen($v_id) == 0) { $v_id = '1'; }

	//check whether a recording exists
		$recording_relative_path = '/archive/'.$tmp_year.'/'.$tmp_month.'/'.$tmp_day;
		if (file_exists($v_recordings_dir.$recording_relative_path.'/'.$uuid.'.wav')) {
			$recording_file = $recording_relative_path.'/'.$uuid.'.wav';
		}
		if (file_exists($v_recordings_dir.$recording_relative_path.'/'.$uuid.'.mp3')) {
			$recording_file = $recording_relative_path.'/'.$uuid.'.mp3';
		}

	//determine where the xml cdr will be archived
		$sql = "select * from v_vars ";
		$sql .= "where v_id  = '$v_id' ";
		$sql .= "and var_name = 'xml_cdr_archive' ";
		$row = $db->query($sql)->fetch();
		$var_value = trim($row["var_value"]);
		switch ($var_value) {
		case "dir":
			$xml_cdr_archive = 'dir';
			break;
		case "db":
			$xml_cdr_archive = 'db';
			break;
		case "none":
			$xml_cdr_archive = 'none';
			break;
		default:
			$xml_cdr_archive = 'dir';
			break;
		}

	//archive the xml cdr string
		if ($xml_cdr_archive == "dir") {
			if (strlen($uuid) > 0) {
				$tmp_time = strtotime($start_stamp);
				$tmp_year = date("Y", $tmp_time);
				$tmp_month = date("M", $tmp_time);
				$tmp_day = date("d", $tmp_time);
				$tmp_dir = $v_log_dir.'/xml_cdr/archive/'.$tmp_year.'/'.$tmp_month.'/'.$tmp_day;
				mkdir($tmp_dir, 0777, true);
				$tmp_file = $uuid.'.xml';
				$fh = fopen($tmp_dir.'/'.$tmp_file, 'w');
				fwrite($fh, $xml_string);
				fclose($fh);
			}
		}

	//insert xml_cdr into the db
		$sql = "insert into v_xml_cdr ";
		$sql .= "(";
		$sql .= "v_id, ";
		$sql .= "uuid, ";
		$sql .= "domain_name, ";
		$sql .= "accountcode, ";
		$sql .= "direction, ";
		$sql .= "default_language, ";
		$sql .= "context, ";
		if ($xml_cdr_archive == "db") {
			$sql .= "xml_cdr, ";
		}
		$sql .= "caller_id_name, ";
		$sql .= "caller_id_number, ";
		$sql .= "destination_number, ";
		$sql .= "start_epoch, ";
		$sql .= "start_stamp, ";
		$sql .= "start_uepoch, ";
		$sql .= "answer_stamp, ";
		$sql .= "answer_epoch, ";
		$sql .= "answer_uepoch, ";
		$sql .= "end_epoch, ";
		$sql .= "end_uepoch, ";
		$sql .= "end_stamp, ";
		$sql .= "duration, ";
		$sql .= "mduration, ";
		$sql .= "billsec, ";
		$sql .= "billmsec, ";
		$sql .= "bridge_uuid, ";
		$sql .= "read_codec, ";
		$sql .= "write_codec, ";
		$sql .= "remote_media_ip, ";
		$sql .= "network_addr, ";
		$sql .= "hangup_cause, ";
		$sql .= "hangup_cause_q850, ";
		$sql .= "recording_file, ";
		$sql .= "leg ";
		$sql .= ")";
		$sql .= "values ";
		$sql .= "(";
		$sql .= "'".$v_id."', ";
		$sql .= "'".$uuid."', ";
		$sql .= "'".$domain_name."', ";
		$sql .= "'".$accountcode."', ";
		$sql .= "'".$direction."', ";
		$sql .= "'".$default_language."', ";
		$sql .= "'".$context."', ";
		if ($xml_cdr_archive == "db") {
			$sql .= "'".$xml_string."', ";
		}
		$sql .= "'".$caller_id_name."', ";
		$sql .= "'".$caller_id_number."', ";
		$sql .= "'".$destination_number."', ";
		$sql .= "'".$start_epoch."', ";
		$sql .= "'".$start_stamp."', ";
		$sql .= "'".$start_uepoch."', ";
		$sql .= "'".$answer_stamp."', ";
		$sql .= "'".$answer_epoch."', ";
		$sql .= "'".$answer_uepoch."', ";
		$sql .= "'".$end_epoch."', ";
		$sql .= "'".$end_uepoch."', ";
		$sql .= "'".$end_stamp."', ";
		$sql .= "'".$duration."', ";
		$sql .= "'".$mduration."', ";
		$sql .= "'".$billsec."', ";
		$sql .= "'".$billmsec."', ";
		$sql .= "'".$bridge_uuid."', ";
		$sql .= "'".$read_codec."', ";
		$sql .= "'".$write_codec."', ";
		$sql .= "'".$remote_media_ip."', ";
		$sql .= "'".$network_addr."', ";
		$sql .= "'".$hangup_cause."', ";
		$sql .= "'".$hangup_cause_q850."', ";
		$sql .= "'".$recording_file."', ";
		$sql .= "'".$leg."' ";
		$sql .= ")";
		try {
			if (strlen($uuid) > 0) {
				if ($debug) { echo $sql."<br />\n"; }
				$db->exec(check_sql($sql));
			}
		}
		catch(Exception $e) {
			echo $e->getMessage();
		}
		unset($sql);
}

//get cdr details from the http post
	if (strlen($_REQUEST["cdr"]) > 0) {

		//authentication for xml cdr http post
			if (strlen($_SESSION["xml_cdr_username"]) == 0) {
				//get the contents of xml_cdr.conf.xml
					$conf_xml_string = file_get_contents($v_conf_dir.'/autoload_configs/xml_cdr.conf.xml');

				//parse the xml to get the call detail record info
					try {
						$conf_xml = simplexml_load_string($conf_xml_string);
					}
					catch(Exception $e) {
						echo $e->getMessage();
					}
					foreach ($conf_xml->settings->param as $row) {
						if ($row->attributes()->name == "cred") {
							$auth_array = explode(":", $row->attributes()->value);
							$_SESSION["xml_cdr_username"] = $auth_array[0];
							$_SESSION["xml_cdr_password"] = $auth_array[1];
							//echo "username: ".$_SESSION["xml_cdr_username"]."<br />\n";
							//echo "password: ".$_SESSION["xml_cdr_password"]."<br />\n";
						}
					}
			}

			//check for the correct username and password
				if ($_SESSION["xml_cdr_username"] == $_SERVER["PHP_AUTH_USER"] && $_SESSION["xml_cdr_password"] == $_SERVER["PHP_AUTH_PW"]) {
					//echo "access granted 2<br />\n";
				}
				else {
					echo "access denied<br />\n";
					return;
				}

			//loop through all attribues
				//foreach($xml->settings->param[1]->attributes() as $a => $b) {
				//		echo $a,'="',$b,"\"<br />\n";
				//}

		//get the http post variable
			$xml_string = trim($_REQUEST["cdr"]);

		//get the leg of the call
			if (substr($_REQUEST['uuid'], 0, 2) == "a_") {
				$leg = "a";
			}
			else {
				$leg = "b";
			}

		//parse the xml and insert the data into the db
			process_xml_cdr($db, $v_log_dir, $leg, $xml_string);
	}


//check the filesystem for xml cdr records that were missed
	$xml_cdr_dir = $v_log_dir.'/xml_cdr';
	$dir_handle = opendir($xml_cdr_dir);
	$x = 0;
	while($file=readdir($dir_handle)) {
		if ($file != '.' && $file != '..') {
			if ( !is_dir($xml_cdr_dir . '/' . $file) ) {
				//get the leg of the call
					if (substr($file, 0, 2) == "a_") {
						$leg = "a";
					}
					else {
						$leg = "b";
					}

				//get the xml cdr string
					$xml_string = file_get_contents($xml_cdr_dir.'/'.$file);

				//parse the xml and insert the data into the db
					process_xml_cdr($db, $v_log_dir, $leg, $xml_string);

				//delete the file after it has been imported
					unlink($xml_cdr_dir.'/'.$file);

				$x++;
			}
		}
	}
	closedir($dir_handle);


//testing
	if ($debug) {
		//ob_end_clean(); //clean the buffer
		//ob_start();
		//phpinfo();
		$content = ob_get_contents(); //get the output from the buffer
		ob_end_clean(); //clean the buffer
		$fp = fopen('/tmp/xml_cdr_post.log', 'w');
		fwrite($fp, $content);
		fclose($fp);
	}

?>
