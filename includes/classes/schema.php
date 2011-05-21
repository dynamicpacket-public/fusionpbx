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

//define the follow me class
	class schema {
		public $v_id;
		public $db;
		public $apps;
		public $db_type;

		//get the list of installed apps from the core and mod directories
			public function __construct() {
				$config_list = glob($_SERVER["DOCUMENT_ROOT"] . PROJECT_PATH . "/*/*/v_config.php");
				$x=0;
				foreach ($config_list as &$config_path) {
					include($config_path);
					$x++;
				}
				$this->apps = $apps;
			}

		//create the database schema
			public function add() {
				$sql = '';
				$sql_schema = '';
				$this->db->beginTransaction();
				foreach ($this->apps as $app) {
					if (count($app['db'])) {
						foreach ($app['db'] as $row) {
							//create the sql string
								$table_name = $row['table'];
								$sql = "CREATE TABLE " . $row['table'] . " (\n";
								$field_count = 0;
								foreach ($row['fields'] as $field) {
									if ($field_count > 0 ) { $sql .= ",\n"; }
									$sql .= $field['name'] . " ";
									if (is_array($field['type'])) {
										$sql .= $field['type'][$this->db_type];
									}
									else {
										$sql .= $field['type'];
									}
									$field_count++;
								}
								$sql .= ");";
							//execute the sql query
								try {
									$this->db->query($sql);
								}
								catch (PDOException $error) {
									echo "error: " . $error->getMessage() . " sql: $sql<br/>";
								}
								unset($sql);
						}
					}
				}
				$this->db->commit();
			}
	}