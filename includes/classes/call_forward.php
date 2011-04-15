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

//define the call_forward class
	class call_forward {
		var $v_id;
		var $db_type;
		var $call_forward_id;
		var $extension;
		var $call_forward_enabled;
		var $call_forward_number;

		function call_forward_add() {
			global $db;
			$hunt_group_extension = $this->extension;
			$huntgroup_name = 'call_forward_'.$this->extension;
			$hunt_group_type = 'call_forward';
			$hunt_group_context = 'default';
			$hunt_group_timeout_destination = $this->extension;
			$hunt_group_timeout_type = 'voicemail';
			$hunt_group_ring_back = 'us-ring';
			$hunt_group_cid_name_prefix = '';
			$hunt_group_pin = '';
			$huntgroup_caller_announce = 'false';
			$hunt_group_user_list = '';
			$hunt_group_enabled = $this->call_forward_enabled;
			$hunt_group_descr = 'call forward '.$this->extension;

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
			$sql .= "huntgroupcidnameprefix, ";
			$sql .= "huntgrouppin, ";
			$sql .= "hunt_group_call_prompt, ";
			$sql .= "huntgroupcallerannounce, ";
			$sql .= "hunt_group_user_list, ";
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
			$sql .= "'$hunt_group_cid_name_prefix', ";
			$sql .= "'$hunt_group_pin', ";
			$sql .= "'$hunt_group_call_prompt', ";
			$sql .= "'$huntgroup_caller_announce', ";
			$sql .= "'$hunt_group_user_list', ";
			$sql .= "'$hunt_group_enabled', ";
			$sql .= "'$hunt_group_descr' ";
			$sql .= ")";
			if ($v_debug) {
				echo "add: ".$sql."<br />";
			}
			if ($this->db_type == "sqlite" || $this->db_type == "mysql" ) {
				$db->exec(check_sql($sql));
				$this->call_forward_id = $db->lastInsertId($id);
			}
			if ($this->db_type == "pgsql") {
				$sql .= " RETURNING hunt_group_id ";
				$prepstatement = $db->prepare(check_sql($sql));
				$prepstatement->execute();
				$result = $prepstatement->fetchAll();
				foreach ($result as &$row) {
					$this->call_forward_id = $row["hunt_group_id"];
				}
				unset($prepstatement, $result);
			}
			unset($sql);
			$this->call_forward_destination();
		}

		function call_forward_update() {
			global $db;
			$hunt_group_extension = $this->extension;
			$huntgroup_name = 'call_forward_'.$this->extension;
			$hunt_group_type = 'call_forward';
			$hunt_group_context = 'default';
			$hunt_group_timeout_destination = $this->extension;
			$hunt_group_timeout_type = 'voicemail';
			$hunt_group_ring_back = 'us-ring';
			$hunt_group_cid_name_prefix = '';
			$hunt_group_pin = '';
			$huntgroup_caller_announce = 'false';
			$hunt_group_user_list = '';
			$hunt_group_enabled = $this->call_forward_enabled;
			$hunt_group_descr = 'call forward '.$this->extension;

			$sql = "update v_hunt_group set ";
			$sql .= "huntgroupextension = '$hunt_group_extension', ";
			$sql .= "huntgroupname = '$huntgroup_name', ";
			$sql .= "huntgrouptype = '$hunt_group_type', ";
			$sql .= "huntgroupcontext = '$hunt_group_context', ";
			$sql .= "huntgrouptimeout = '$hunt_group_timeout', ";
			$sql .= "huntgrouptimeoutdestination = '$hunt_group_timeout_destination', ";
			$sql .= "huntgrouptimeouttype = '$hunt_group_timeout_type', ";
			$sql .= "huntgroupringback = '$hunt_group_ring_back', ";
			$sql .= "huntgroupcidnameprefix = '$hunt_group_cid_name_prefix', ";
			$sql .= "huntgrouppin = '$hunt_group_pin', ";
			$sql .= "hunt_group_call_prompt = '$hunt_group_call_prompt', ";
			$sql .= "huntgroupcallerannounce = '$huntgroup_caller_announce', ";
			$sql .= "hunt_group_user_list = '$hunt_group_user_list', ";
			$sql .= "hunt_group_enabled = '$hunt_group_enabled', ";
			$sql .= "huntgroupdescr = '$hunt_group_descr' ";
			$sql .= "where v_id = '$this->v_id' ";
			$sql .= "and hunt_group_id = '$this->call_forward_id' ";
			$db->exec(check_sql($sql));
			unset($sql);
			$this->call_forward_destination();
		} //end function

		function call_forward_destination() {
			global $db;
			//delete related v_hunt_group_destinations
				$sql = "delete from v_hunt_group_destinations where hunt_group_id = '$this->call_forward_id' ";
				$db->exec(check_sql($sql));
			//check whether the number is an extension or external number
				if (strlen($this->call_forward_number) > 7) {
					$destination_type = 'sip uri';
					$destination_profile = '';
				}
				else {
					$destination_type = 'extension';
					$destination_profile = 'internal';
				}
			//prepare the variables
				$destination_data = $this->call_forward_number;
				$destination_timeout = '';
				$destination_order = '1';
				$destination_enabled = 'true';
				$destination_descr = 'call forward';
			//add the hunt group destination
				if ($this->call_forward_id > 0) {
					$sql = "insert into v_hunt_group_destinations ";
					$sql .= "(";
					$sql .= "v_id, ";
					$sql .= "hunt_group_id, ";
					$sql .= "destinationdata, ";
					$sql .= "destinationtype, ";
					$sql .= "destinationprofile, ";
					$sql .= "destination_timeout, ";
					$sql .= "destinationorder, ";
					$sql .= "destination_enabled, ";
					$sql .= "destinationdescr ";
					$sql .= ")";
					$sql .= "values ";
					$sql .= "(";
					$sql .= "'$this->v_id', ";
					$sql .= "'$this->call_forward_id', ";
					$sql .= "'$destination_data', ";
					$sql .= "'$destination_type', ";
					$sql .= "'$destination_profile', ";
					$sql .= "'$destination_timeout', ";
					$sql .= "'$destination_order', ";
					$sql .= "'$destination_enabled', ";
					$sql .= "'$destination_descr' ";
					$sql .= ")";
					$db->exec(check_sql($sql));
					unset($sql);
				}
		} //end function
	}

?>