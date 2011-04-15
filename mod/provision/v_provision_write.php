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
	Copyright (C) 2008-2010 All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/
include "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
if (ifgroup("admin") || ifgroup("superadmin")) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//set default variables
	$dir_count = 0;
	$file_count = 0;
	$row_count = 0;
	$tmp_array = '';

//get any system -> variables defined in the 'provision;
	$sql = "";
	$sql .= "select * from v_vars ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and var_enabled= 'true' ";
	$sql .= "and var_cat = 'Provision' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$provision_variables_array = $prepstatement->fetchAll();
	foreach ($provision_variables_array as &$row) {
		if ($row[var_name] == "password") {
			$var_name = $row[var_name];
			$var_value = $row[var_value];
			$$var_name = $var_value;
		}
	}

//get the ftp and tftp directories
	$sql = "";
	$sql .= "select * from v_system_settings ";
	$sql .= "where v_id = '$v_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$provision_variables_array = $prepstatement->fetchAll();
	foreach ($provision_variables_array as &$row) {
		$v_provisioning_tftp_dir = $row['v_provisioning_tftp_dir'];
		$v_provisioning_ftp_dir = $row['v_provisioning_ftp_dir'];
		break;
	}

//get the hardware phone list
	$sql = "";
	$sql .= "select * from v_hardware_phones ";
	$sql .= "where v_id = '$v_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$phone_mac_address = $row["phone_mac_address"];
		$phone_mac_address = strtolower($phone_mac_address);
		$phone_label = $row["phone_label"];
		$phone_vendor = $row["phone_vendor"];
		$phone_model = $row["phone_model"];
		$phone_firmware_version = $row["phone_firmware_version"];
		$phone_provision_enable = $row["phone_provision_enable"];
		$phone_template = $row["phone_template"];
		$phone_username = $row["phone_username"];
		$phone_password = $row["phone_password"];
		$phone_time_zone = $row["phone_time_zone"];
		$phone_description = $row["phone_description"];

		//loop through the provision template directory
			$provision_template_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH."/includes/templates/provision/".$phone_template;

			clearstatcache();
			$dir_list = '';
			$file_list = '';
			$dir_list = opendir($provision_template_dir);
			$dir_array = array();
			while (false !== ($file = readdir($dir_list))) { 
				if ($file != "." AND $file != ".."){
					$new_path = $dir.'/'.$file;
					$level = explode('/',$new_path);
					if (substr($new_path, -4) == ".svn") {
						//ignore .svn dir and subdir
					}
					elseif (substr($new_path, -3) == ".db") {
						//ignore .db files
					}
					else {
						$dir_array[] = $new_path;
					}
					if ($x > 1000) { break; };
					$x++;
				}
			}
			//asort($dir_array);
			foreach ($dir_array as $new_path){
					$level = explode('/',$new_path);
					if (is_dir($new_path)) { 
						//$mod_array[] = array(
								//'level'=>count($level)-1,
								//'path'=>$new_path,
								//'name'=>end($level),
								//'type'=>'dir',
								//'mod_time'=>filemtime($new_path),
								//'size'=>'');
								//$mod_array[] = recur_dir($new_path);
						$dir_name = end($level);
						//$file_list .=  "$dir_name\n";
						//$dir_list .= recur_dir($new_path);
					}
					else {
						//$mod_array[] = array(
								//'level'=>count($level)-1,
								//'path'=>$new_path,
								//'name'=>end($level),
								//'type'=>'dir',
								//'mod_time'=>filemtime($new_path),
								//'size'=>'');
								//$mod_array[] = recur_dir($new_path);
						$file_name = end($level);
						$file_size = round(filesize($new_path)/1024, 2);

						//get the contents of the template
							$file_contents = file_get_contents($_SERVER["DOCUMENT_ROOT"].PROJECT_PATH."/includes/templates/provision/".$phone_template ."/".$file_name);

						//prepare the files
							//replace the variables in the template in the future loop through all the line numbers to do a replace for each possible line number
								$file_contents = str_replace("{v_mac}", $phone_mac_address, $file_contents);
								$file_contents = str_replace("{v_label}", $phone_label, $file_contents);
								$file_contents = str_replace("{v_firmware_version}", $phone_firmware_version, $file_contents);
								$file_contents = str_replace("{v_time_zone}", $phone_time_zone, $file_contents);
								$file_contents = str_replace("{v_domain}", $v_domain, $file_contents);
								$file_contents = str_replace("{v_server1_address}", $server1_address, $file_contents);
								$file_contents = str_replace("{v_proxy1_address}", $proxy1_address, $file_contents);

						//replace the dynamic provision variables that are defined in the system -> variables page
							foreach ($provision_variables_array as &$row) {
								if (substr($var_name, 0, 2) == "v_") {
									$file_contents = str_replace('{'.$row[var_name].'}', $row[var_value], $file_contents);
								}
							}

						//lookup the provisioning information for this MAC address.
							$sql2 = "";
							$sql2 .= "select * from v_extensions ";
							$sql2 .= "where provisioning_list like '%$phone_mac_address%' ";
							$sql2 .= "and v_id = '$v_id' ";
							$prepstatement2 = $db->prepare(check_sql($sql2));
							$prepstatement2->execute();
							$result2 = $prepstatement2->fetchAll();
							foreach ($result2 as &$row2) {
								$provisioning_list = $row2["provisioning_list"];
								if (strlen($provisioning_list) > 1) {
									$provisioning_list_array = explode("|", $provisioning_list);
									foreach ($provisioning_list_array as $prov_row) {
										$prov_row_array = explode(":", $prov_row);
										if (strlen($prov_row_array[0]) > 0) {
											//echo "mac address: ".$prov_row_array[0]."<br />";
											//echo "line_number: ".$prov_row_array[1]."<br />";
											if ($prov_row_array[0] == $phone_mac_address) {
												//print_r($prov_row_array);
												$line_number = $prov_row_array[1];
												//echo "prov_row: ".$prov_row."<br />";
												//echo "line_number: ".$line_number."<br />";
												//echo "<hr><br />\n";
											}
											$file_contents = str_replace("{v_line".$line_number."_server_address}", $v_domain, $file_contents);
											$file_contents = str_replace("{v_line".$line_number."_displayname}", $row2["extension"], $file_contents);
											$file_contents = str_replace("{v_line".$line_number."_shortname}", $row2["extension"], $file_contents);
											$file_contents = str_replace("{v_line".$line_number."_user_id}", $row2["extension"], $file_contents);
											$file_contents = str_replace("{v_line".$line_number."_user_password}", $row2["password"], $file_contents);
										}
									}
									//$user_list = $row["user_list"];
									//$vm_password = $row["vm_password"];
									//$vm_password = str_replace("#", "", $vm_password); //preserves leading zeros
									//$accountcode = $row["accountcode"];
									//$effective_caller_id_name = $row["effective_caller_id_name"];
									//$effective_caller_id_number = $row["effective_caller_id_number"];
									//$outbound_caller_id_name = $row["outbound_caller_id_name"];
									//$outbound_caller_id_number = $row["outbound_caller_id_number"];
									//$vm_enabled = $row["vm_enabled"];
									//$vm_mailto = $row["vm_mailto"];
									//$vm_attach_file = $row["vm_attach_file"];
									//$vm_keep_local_after_email = $row["vm_keep_local_after_email"];
									//$user_context = $row["user_context"];
									//$callgroup = $row["callgroup"];
									//$auth_acl = $row["auth_acl"];
									//$cidr = $row["cidr"];
									//$sip_force_contact = $row["sip_force_contact"];
									//$enabled = $row["enabled"];
									//$description = $row["description"]
								}
							}
							unset ($prepstatement2);

						//cleanup any remaining variables
							for ($i = 1; $i <= 100; $i++) {
								$file_contents = str_replace("{v_line".$i."_server_address}", "", $file_contents);
								$file_contents = str_replace("{v_line".$i."_displayname}", "", $file_contents);
								$file_contents = str_replace("{v_line".$i."_shortname}", "", $file_contents);
								$file_contents = str_replace("{v_line".$i."_user_id}", "", $file_contents);
								$file_contents = str_replace("{v_line".$i."_user_password}", "", $file_contents);
							}

						//replace {v_mac} in the file name
							$file_name = str_replace("{v_mac}", $phone_mac_address, $file_name);

						//write the configuration to the directory
							if (strlen($v_provisioning_tftp_dir) > 0) {
								$fh = fopen($v_provisioning_tftp_dir.'/'.$file_name,"w") or die("Unable to write to $v_provisioning_tftp_dir for provisioning. Make sure the path exists and permissons are set correctly.");
								fwrite($fh, $file_contents);
								unset($file_name);
								fclose($fh);
							}
							if (strlen($v_provisioning_ftp_dir) > 0) {
								$fh = fopen($v_provisioning_ftp_dir.'/'.$file_name,"w") or die("Unable to write to $v_provisioning_ftp_dir for provisioning. Make sure the path exists and permissons are set correctly.");
								fwrite($fh, $file_contents);
								unset($file_name);
								fclose($fh);
							}
					}
			} //end for each
			closedir($dir_list);
	}
	unset ($prepstatement);
?>