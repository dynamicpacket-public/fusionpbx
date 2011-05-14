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
	Copyright (C) 2010
	All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/
include "root.php";

//define the dnd class
	class do_not_disturb {
		var $v_id;
		var $dnd_id;
		var $v_domain;
		var $extension;
		var $dnd_enabled;

		//update the user_status
		function dnd_status() {
			global $db;
			if ($this->dnd_enabled == "true") {
				//update the call center status
					$user_status = "Logged Out";
					$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
					if ($fp) {
						$switch_cmd .= "callcenter_config agent set status ".$_SESSION['username']."@".$v_domain." '".$user_status."'";
						$switch_result = event_socket_request($fp, 'api '.$switch_cmd);
					}

				//update the database user_status
					$user_status = "Do Not Disturb";
					$sql  = "update v_users set ";
					$sql .= "user_status = '$user_status' ";
					$sql .= "where v_id = '$v_id' ";
					$sql .= "and username = '".$_SESSION['username']."' ";
					$prepstatement = $db->prepare(check_sql($sql));
					$prepstatement->execute();
			}
		} //function

		function dnd_add() {
			global $db;

			$hunt_group_extension = $this->extension;
			$huntgroup_name = 'dnd_'.$this->extension;
			$hunt_group_type = 'dnd';
			$hunt_group_context = 'default';
			$hunt_group_timeout = '1';
			$hunt_group_timeout_destination = $this->extension;
			$hunt_group_timeout_type = 'voicemail';
			$hunt_group_ring_back = 'us-ring';
			//$hunt_group_cid_name_prefix = '';
			//$hunt_group_pin = '';
			//$hunt_group_call_prompt = 'false';
			$huntgroup_caller_announce = 'false';
			//$hunt_group_user_list = '';
			$hunt_group_enabled = $this->dnd_enabled;
			$hunt_group_descr = 'dnd '.$this->extension;

			$sql = "insert into v_hunt_group ";
			$sql .= "(";
			$sql .= "v_id, ";
			$sql .= "huntgroupextension, ";
			$sql .= "huntgroupname, ";
			$sql .= "huntgrouptype, ";
			$sql .= "huntgroupcontext, ";
			$sql .= "huntgrouptimeout, ";
			$sql .= "huntgrouptimeoutdestination, ";
			$sql .= "huntgrouptimeouttype, ";
			$sql .= "huntgroupringback, ";
			//$sql .= "huntgroupcidnameprefix, ";
			//$sql .= "huntgrouppin, ";
			$sql .= "hunt_group_call_prompt, ";
			$sql .= "huntgroupcallerannounce, ";
			//$sql .= "hunt_group_user_list, ";
			$sql .= "hunt_group_enabled, ";
			$sql .= "huntgroupdescr ";
			$sql .= ")";
			$sql .= "values ";
			$sql .= "(";
			$sql .= "'$this->v_id', ";
			$sql .= "'$hunt_group_extension', ";
			$sql .= "'$huntgroup_name', ";
			$sql .= "'$hunt_group_type', ";
			$sql .= "'$hunt_group_context', ";
			$sql .= "'$hunt_group_timeout', ";
			$sql .= "'$hunt_group_timeout_destination', ";
			$sql .= "'$hunt_group_timeout_type', ";
			$sql .= "'$hunt_group_ring_back', ";
			//$sql .= "'$hunt_group_cid_name_prefix', ";
			//$sql .= "'$hunt_group_pin', ";
			$sql .= "'$hunt_group_call_prompt', ";
			$sql .= "'$huntgroup_caller_announce', ";
			//$sql .= "'$hunt_group_user_list', ";
			$sql .= "'$hunt_group_enabled', ";
			$sql .= "'$hunt_group_descr' ";
			$sql .= ")";
			if ($this->debug) {
				echo $sql."<br />";
			}
			$db->exec(check_sql($sql));
			unset($sql);
		} //function

		function dnd_update() {
			global $db;

			$hunt_group_extension = $this->extension;
			$huntgroup_name = 'dnd_'.$this->extension;
			$hunt_group_type = 'dnd';
			$hunt_group_context = 'default';
			$hunt_group_timeout = '1';
			$hunt_group_timeout_destination = $this->extension;
			$hunt_group_timeout_type = 'voicemail';
			$hunt_group_ring_back = 'us-ring';
			//$hunt_group_cid_name_prefix = '';
			//$hunt_group_pin = '';
			//$hunt_group_call_prompt = 'false';
			$huntgroup_caller_announce = 'false';
			//$hunt_group_user_list = '';
			$hunt_group_enabled = $this->dnd_enabled;
			$hunt_group_descr = 'dnd '.$this->extension;

			$sql = "update v_hunt_group set ";
			$sql .= "huntgroupextension = '$hunt_group_extension', ";
			$sql .= "huntgroupname = '$huntgroup_name', ";
			$sql .= "huntgrouptype = '$hunt_group_type', ";
			$sql .= "huntgroupcontext = '$hunt_group_context', ";
			$sql .= "huntgrouptimeout = '$hunt_group_timeout', ";
			$sql .= "huntgrouptimeoutdestination = '$hunt_group_timeout_destination', ";
			$sql .= "huntgrouptimeouttype = '$hunt_group_timeout_type', ";
			$sql .= "huntgroupringback = '$hunt_group_ring_back', ";
			//$sql .= "huntgroupcidnameprefix = '$hunt_group_cid_name_prefix', ";
			//$sql .= "huntgrouppin = '$hunt_group_pin', ";
			$sql .= "hunt_group_call_prompt = '$hunt_group_call_prompt', ";
			$sql .= "huntgroupcallerannounce = 'false', ";
			//$sql .= "hunt_group_user_list = '$hunt_group_user_list', ";
			$sql .= "hunt_group_enabled = '$hunt_group_enabled', ";
			$sql .= "huntgroupdescr = '$hunt_group_descr' ";
			$sql .= "where v_id = '$this->v_id' ";
			$sql .= "and hunt_group_id = '$this->dnd_id' ";
			if ($this->debug) {
				echo $sql."<br />";
			}
			$db->exec(check_sql($sql));
			unset($sql);
		} //function
	} //class

?>