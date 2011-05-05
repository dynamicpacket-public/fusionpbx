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

//http get and set variables
	if (strlen($_GET['url']) > 0) {
		$url = $_GET['url'];
	}

// active extensions

	//get a list of assigned extensions for this user
		if (count($_SESSION['user_extension_array']) == 0) {
			$sql = "";
			$sql .= "select * from v_extensions ";
			$sql .= "where v_id = '$v_id' ";
			$sql .= "and user_list like '%|".$_SESSION["username"]."|%' ";
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$x = 0;
			$result = $prepstatement->fetchAll();
			foreach ($result as &$row) {
				$user_extension_array[$x]['extension_id'] = $row["extension_id"];
				$user_extension_array[$x]['extension'] = $row["extension"];
				$x++;
			}
			unset ($prepstatement, $x);
			$_SESSION['user_extension_array'] = $user_extension_array;
		}

		echo "<table width='100%' border='0' cellpadding='5' cellspacing='0'>\n";
		echo "<tr>\n";
		echo "<td valign='top'>\n";

		echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "<tr>\n";
		echo "<th width='50px;'>Ext</th>\n";
		if ($_SESSION['user_status_display'] == "false") {
			//hide the user_status when it is set to false
		}
		else {
			echo "<th>Status</th>\n";
		}
		echo "<th>Time</th>\n";
		//echo "<th>Direction</th>\n";
		//echo "<th>Profile</th>\n";
		echo "<th>CID Name</th>\n";
		echo "<th>CID Number</th>\n";
		echo "<th>Dest</th>\n";
		echo "<th>Application</th>\n";
		echo "<th>Secure</th>\n";
		echo "<th>Name</th>\n";
		echo "<th>Options</th>\n";
		echo "</tr>\n";
		foreach ($_SESSION['extension_array'] as $row) {
			$v_id = $row['v_id'];
			$extension = $row['extension'];
			$enabled = $row['enabled'];
			$effective_caller_id_name = $row['effective_caller_id_name'];

			foreach ($_SESSION['user_extension_array'] as &$user_row) {
				if ($extension == $user_row['extension']) {
					$found_extension = false;
					$x = 1;
					foreach ($channels_array as $row) {
						//set the php variables
							foreach ($row as $key => $value) {
								$$key = $value;
							}
						//find the matching extensions
							if ($number == $extension) {
								//set the found extension to true
									$found_extension = true;
								//remove the '+' because it breaks the call recording
									$cid_num = str_replace("+", "", $cid_num);
								//prepare the call length values
									$call_length_seconds = time() - $created_epoch;
									$call_length_hour = floor($call_length_seconds/3600);
									$call_length_min = floor($call_length_seconds/60 - ($call_length_hour * 60));
									$call_length_sec = $call_length_seconds - (($call_length_hour * 3600) + ($call_length_min * 60));
									$call_length_min = sprintf("%02d", $call_length_min);
									$call_length_sec = sprintf("%02d", $call_length_sec);
									$call_length = $call_length_hour.':'.$call_length_min.':'.$call_length_sec;
							}
					} //end foreach

					if ($found_extension) {

						if ($application == "conference") { 
							$alt_color = "background-image: url('".PROJECT_PATH."/images/background_cell_active.gif";
						}
						switch ($application) {
						case "conference":
							$style_alternate = "style=\"color: #444444; background-image: url('".PROJECT_PATH."/images/background_cell_conference.gif');\"";
							break;
						case "fifo":
							$style_alternate = "style=\"color: #444444; background-image: url('".PROJECT_PATH."/images/background_cell_fifo.gif');\"";
							break;
						default:
							$style_alternate = "style=\"color: #444444; background-image: url('".PROJECT_PATH."/images/background_cell_active.gif');\"";
						}
						echo "<tr>\n";
						echo "<td class='".$rowstyle[$c]."' $style_alternate>$extension</td>\n";
						if ($_SESSION['user_status_display'] == "false") {
							//hide the user_status when it is set to false
						}
						else {
							echo "<td class='".$rowstyle[$c]."' $style_alternate>".$user_array[$extension]['user_status']."&nbsp;</td>\n";
						}
						echo "<td class='".$rowstyle[$c]."' $style_alternate width='20px;'>".$call_length."</td>\n";
						//echo "<td class='".$rowstyle[$c]."' $style_alternate>$direction</td>\n";
						//echo "<td class='".$rowstyle[$c]."' $style_alternate>$sip_profile</td>\n";
						if (strlen($url) == 0) {
							echo "<td class='".$rowstyle[$c]."' $style_alternate>".$cid_name."</td>\n";
							echo "<td class='".$rowstyle[$c]."' $style_alternate>".$cid_num."</td>\n";
						}
						else {
							echo "<td class='".$rowstyle[$c]."' $style_alternate><a href='".$url."cid_name=".$cid_name."&cid_num=".$cid_num."' style='color: #444444;' target='_blank'>".$cid_name."</a></td>\n";
							echo "<td class='".$rowstyle[$c]."' $style_alternate><a href='".$url."cid_name=".$cid_name."&cid_num=".$cid_num."' style='color: #444444;' target='_blank'>".$cid_num."</a></td>\n";
						}
						
						//get the active uuid list
							if (strlen($uuid) > 0 ) {
								if ($x == 1) {
									$uuid_1 = $uuid;
									$direction_1 = $direction;
									$cid_name_1 = $cid_name;
									$cid_num_1 = $cid_num;
									$x++;
								}
								if ($x == 2) {
									$uuid_2 = $uuid;
									$direction_2 = $direction;
									$cid_name_2 = $cid_name;
									$cid_num_2 = $cid_num;
									$x++;
								}
							}
					}
					else {
						$style_alternate = "style=\"color: #444444; background-image: url('".PROJECT_PATH."/images/background_cell_light.gif');\"";
						echo "<tr>\n";
						echo "<td class='".$rowstyle[$c]."' $style_alternate>$extension</td>\n";
						if ($_SESSION['user_status_display'] == "false") {
							//hide the user_status when it is set to false
						}
						else {
							echo "<td class='".$rowstyle[$c]."' $style_alternate>".$user_array[$extension]['user_status']."&nbsp;</td>\n";
						}
						echo "<td class='".$rowstyle[$c]."' $style_alternate>&nbsp;</td>\n";
						//echo "<td class='".$rowstyle[$c]."' $style_alternate>&nbsp;</td>\n";
						//echo "<td class='".$rowstyle[$c]."' $style_alternate>&nbsp;</td>\n";
						echo "<td class='".$rowstyle[$c]."' $style_alternate>&nbsp;</td>\n";
						echo "<td class='".$rowstyle[$c]."' $style_alternate>&nbsp;</td>\n";
					}

					if ($found_extension) {
						echo "<td class='".$rowstyle[$c]."' $style_alternate>\n";
					}
					else {
						echo "<td valign='top' class='".$rowstyle[$c]."' $style_alternate>\n";
					}
					echo "".$dest."<br />\n";
					echo "</td>\n";

					if ($found_extension) {
						echo "<td class='".$rowstyle[$c]."' $style_alternate>\n";
					}
					else {
						echo "<td valign='top' class='".$rowstyle[$c]."' $style_alternate>\n";
					}
					if ($application == "fifo") {
						echo "queue &nbsp;\n";
					}
					else {
						echo $application." &nbsp;\n";
					}
					echo "</td>\n";

					if ($found_extension) {
						echo "<td class='".$rowstyle[$c]."' $style_alternate>\n";
					}
					else {
						echo "<td valign='top' class='".$rowstyle[$c]."' $style_alternate>\n";
					}
					echo "".$secure."<br />\n";
					echo "</td>\n";

					if ($found_extension) {
						echo "<td class='".$rowstyle[$c]."' $style_alternate>\n";
					}
					else {
						echo "<td valign='top' class='".$rowstyle[$c]."' $style_alternate>\n";
					}
					echo "".$effective_caller_id_name."<br />\n";
					echo "</td>\n";

					if ($found_extension) {
						echo "<td valign='top' class='".$rowstyle[$c]."' $style_alternate>\n";
							//transfer
								//uuid_transfer c985c31b-7e5d-3844-8b3b-aa0835ff6db9 -bleg *9999 xml default
								//document.getElementById('url').innerHTML='v_calls_exec.php?action=energy&direction=down&cmd='+prepare_cmd(escape('$uuid'));
								echo "	<a href='javascript:void(0);' style='color: #444444;' onMouseover=\"document.getElementById('form_label').innerHTML='<strong>Transfer To</strong>';\" onclick=\"send_cmd('v_calls_exec.php?cmd='+get_transfer_cmd(escape('$uuid')));\">transfer</a>&nbsp;\n";

							//park
								echo "	<a href='javascript:void(0);' style='color: #444444;' onclick=\"send_cmd('v_calls_exec.php?cmd='+get_park_cmd(escape('$uuid')));\">park</a>&nbsp;\n";

							//hangup
								echo "	<a href='javascript:void(0);' style='color: #444444;' onclick=\"confirm_response = confirm('Do you really want to hangup this call?');if (confirm_response){send_cmd('v_calls_exec.php?cmd=uuid_kill%20'+(escape('$uuid')));}\">hangup</a>&nbsp;\n";

							//record start/stop
								$tmp_file = $v_recordings_dir."/archive/".date("Y")."/".date("M")."/".date("d")."/".$uuid.".wav";
								if (file_exists($tmp_file)) {
									//stop
									echo "	<a href='javascript:void(0);' style='color: #444444;' onclick=\"send_cmd('v_calls_exec.php?cmd='+get_record_cmd(escape('$uuid'), 'active_extensions_', escape('$cid_num'))+'&uuid='+escape('$uuid')+'&action=record&action2=stop&prefix=active_extensions_&name='+escape('$cid_num'));\">stop record</a>&nbsp;\n";
								}
								else {
									//start
									echo "	<a href='javascript:void(0);' style='color: #444444;' onclick=\"send_cmd('v_calls_exec.php?cmd='+get_record_cmd(escape('$uuid'), 'active_extensions_', escape('$cid_num'))+'&uuid='+escape('$uuid')+'&action=record&action2=start&prefix=active_extensions_');\">start record</a>&nbsp;\n";
								}

							echo "	&nbsp;";
						echo "</td>\n";
					}
					else {
						echo "<td valign='top' class='".$rowstyle[$c]."' $style_alternate>\n";
						echo "	&nbsp;";
						echo "</td>\n";
					}

					echo "</tr>\n";

					unset($found_extension);
					unset($uuid);
					//unset($direction);
					//unset($sip_profile);
					unset($created);
					unset($created_epoch);
					unset($name);
					unset($state);
					unset($cid_name);
					unset($cid_num);
					unset($ip_addr);
					unset($dest);
					unset($application);
					unset($application_data);
					unset($dialplan);
					unset($context);
					unset($read_codec);
					unset($read_rate);
					unset($write_codec);
					unset($write_rate);
					unset($secure);

					if ($c==0) { $c=1; } else { $c=0; }
				} //end if
			} //end foreach
		}

		echo "</table>\n";

		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";

		echo "<span id='uuid_1' style='visibility:hidden;'>$uuid_1</span>\n";
		echo "<span id='direction_1' style='visibility:hidden;'>$direction_1</span>\n";
		echo "<span id='cid_name_1' style='visibility:hidden;'>$cid_name_1</span>\n";
		echo "<span id='cid_num_1' style='visibility:hidden;'>$cid_num_1</span>\n";

		echo "<span id='uuid_2' style='visibility:hidden;'>$uuid_2</span>\n";
		echo "<span id='direction_2' style='visibility:hidden;'>$direction_2</span>\n";
		echo "<span id='cid_name_2' style='visibility:hidden;'>$cid_name_2</span>\n";
		echo "<span id='cid_num_2' style='visibility:hidden;'>$cid_num_2</span>\n";

		echo "<br />\n";

?>