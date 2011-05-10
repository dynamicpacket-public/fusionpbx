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

//define the dialplan class
	class dialplan {
		var $v_id;
		var $dialplan_include_id;
		var $tag;
		var $fieldorder;
		var $fieldtype;
		var $fielddata;
		var $fieldbreak;
		var $field_inline;
		var $field_group;
		var $extensionname;
		var $dialplanorder;
		var $context;
		var $enabled;
		var $opt1name;
		var $opt1value;
		var $descr;

		function dialplan_add() {
			global $db;
		}
		
		function dialplan_update() {
			global $db;
		}

		function dialplan_detail_add() {
			global $db;
			$sql = "insert into v_dialplan_includes_details ";
			$sql .= "(";
			$sql .= "v_id, ";
			$sql .= "dialplan_include_id, ";
			$sql .= "tag, ";
			$sql .= "fieldorder, ";
			$sql .= "fieldtype, ";
			$sql .= "fielddata, ";
			$sql .= "fieldbreak, ";
			$sql .= "field_inline, ";
			$sql .= "field_group ";
			$sql .= ")";
			$sql .= "values ";
			$sql .= "(";
			$sql .= "'$this->v_id', ";
			$sql .= "'$this->dialplan_include_id', ";
			$sql .= "'$this->tag', ";
			$sql .= "'$this->fieldorder', ";
			$sql .= "'$this->fieldtype', ";
			$sql .= "'$this->fielddata', ";
			$sql .= "'$this->fieldbreak', ";
			$sql .= "'$this->field_inline', ";
			if (strlen($this->field_group) == 0) {
				$sql .= "null ";
			}
			else {
				$sql .= "'$this->field_group' ";
			}
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);
		} //end function

		function dialplan_detail_update() {
			global $db;
			$sql = "";
			$sql = "update v_dialplan_includes set ";
			$sql .= "extensionname = '$this->extensionname', ";
			$sql .= "dialplanorder = '$this->dialplanorder', ";
			$sql .= "context = '$this->context', ";
			$sql .= "enabled = '$this->enabled', ";
			$sql .= "descr = '$this->descr' ";
			$sql .= "where v_id = '$this->v_id' ";
			$sql .= "and opt1name = '$this->opt1name' ";
			$sql .= "and opt1value = '$this->opt1value' ";
			//echo "sql: ".$sql."<br />";
			$db->query($sql);
			unset($sql);
		} //end function

	} //class

?>