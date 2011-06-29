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
		preg_match("/^(.*)\/mod\/.*$/", $document_root, $matches);
		$document_root = $matches[1];
		set_include_path($document_root);
		$_SERVER["DOCUMENT_ROOT"] = $document_root;
		require_once "includes/config.php";
		$display_type = 'text'; //html, text
	}
	else {
		include "root.php";
		require_once "includes/config.php";
	}

//set debug
	$debug = false; //true //false
	if($debug){
		$time5 = microtime(true);
		$insert_time=$insert_count=0;
	}

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

	//get the variables from the xml & build a list of variable to save:
	//This is an array where the variable in the xml_cdr is THE SAME as the column name.
	$variables_named=array('uuid','domain_name','accountcode','default_language',
			'start_epoch','start_stamp','start_uepoch',
			'answer_stamp','answer_epoch','answer_uepoch','end_epoch',
			'end_uepoch','end_stamp',
			'duration','mduration','billsec','billmsec',
			'bridge_uuid',
			'read_codec','write_codec','remote_media_ip','hangup_cause','hangup_cause_q850',
			'last_app','sip_hangup_disposition'
			);
		//set the $variable so we can use it.
		foreach($variables_named as $var){
			${$var} = check_str(urldecode($xml->variables->{$var}));
		}//end get the variables from the xml loop.

	//Add the other variables that are going to processed, rather than pulling the actual name from the XML.
	//Pull data from the actual callflow.
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
		array_push($variables_named, 'destination_number','context','network_addr','caller_id_name','caller_id_number');

	//Store the call leg.
	$variables_named[]='leg';

	//Store the call direction.
		$variables_named[]='direction';
		$direction = check_str(urldecode($xml->variables->call_direction));

	//Store PDD, Post Dial Delay, in milliseconds.
		$variables_named[]='pdd_ms';
		$pdd_ms = check_str(urldecode($xml->variables->progress_mediamsec) + urldecode($xml->variables->progressmsec));

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
		$variables_named[]='v_id';

	//check whether a recording exists
		$recording_relative_path = '/archive/'.$tmp_year.'/'.$tmp_month.'/'.$tmp_day;
		if (file_exists($v_recordings_dir.$recording_relative_path.'/'.$uuid.'.wav')) {
			$recording_file = $recording_relative_path.'/'.$uuid.'.wav';
		}
		elseif (file_exists($v_recordings_dir.$recording_relative_path.'/'.$uuid.'.mp3')) {
			$recording_file = $recording_relative_path.'/'.$uuid.'.mp3';
		}
		if(isset($recording_file) && !empty($recording_file)) $variables_named[]='recording_file';

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

	//if xml_cdr_archive is set to DB, then insert it.
		if ($xml_cdr_archive == "db") {
			$xml_cdr = check_str($xml_string);
			$variables_named[]='xml_cdr';
		}

	//if xml_cdr_archive is set to dir, then store it.
		elseif ($xml_cdr_archive == "dir") {
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
		//build the insert string
		$count=count($variables_named);
		$name=$value='';
		for($i=0;$i<$count;$i++){
			$name	.=$variables_named[$i].",";
			$values	.="'".${$variables_named[$i]}."',";
			//if($i+1<$count) {$name	.=",";$values	.=",";}
			}
		$sql = 'insert into v_xml_cdr ('.substr($name,0,-1).') values ('.substr($values,0,-1).')';//substr to strip the extra , off the end.

		//insert the values
		try {
			
			if (strlen($uuid) > 0) {
				if ($debug) {
					$time5_insert=microtime(true);
					echo $sql."<br />\n";
					}
				$db->exec(check_sql($sql));
				if ($debug) {
					GLOBAL $insert_time,$insert_count;
					$insert_time+=microtime(true)-$time5_insert;//add this current query.
					$insert_count++;
					}
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

//debug true
	if ($debug) {
		$content = ob_get_contents(); //get the output from the buffer
		ob_end_clean(); //clean the buffer
		$time="\n\n$insert_count inserts in: ".number_format($insert_time,5). " seconds.\n";
		$time.="Other processing time: ".number_format((microtime(true)-$time5-$insert_time),5). " seconds.\n";
		$fp = fopen('/tmp/xml_cdr_post.log', 'w');
		fwrite($fp, $content.$time);
		fclose($fp);
	}

?>
