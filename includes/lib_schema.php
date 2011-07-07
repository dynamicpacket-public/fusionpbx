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
//include "root.php";
//require_once "includes/config.php";

function db_table_exists_alternate ($db, $db_type, $table_name) {
	$sql = "select count(*) from $table_name ";
	$result = $db->query($sql);
	if ($result > 0) {
		return true; //table exists
	}
	else {
		return false; //table doesn't exist
	}
}

function db_table_exists ($db, $db_type, $db_name, $table_name) {
	$sql = "";
	if ($db_type == "sqlite") {
		$sql .= "SELECT * FROM sqlite_master WHERE type='table' and name='$table_name' ";
	}
	if ($db_type == "pgsql") {
		$sql .= "select * from pg_tables where schemaname='public' and tablename = '$table_name' ";
	}
	if ($db_type == "mysql") {
		$sql .= "SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = '$db_name' and TABLE_NAME = '$table_name' ";
	}
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	if (count($result) > 0) {
		return true; //table exists
	}
	else {
		return false; //table doesn't exist
	}
}

function db_column_exists ($db, $db_type, $db_name, $tmp_table_name, $tmp_column_name) {
	global $display_type;

	//check if the column exists
		$sql = "";
		if ($db_type == "sqlite") {
			$sql .= "SELECT * FROM sqlite_master WHERE type='table' and name='$tmp_table_name' and sql like '%$tmp_column_name%' ";
		}
		if ($db_type == "pgsql") {
			$sql .= "SELECT attname FROM pg_attribute WHERE attrelid = (SELECT oid FROM pg_class WHERE relname = '$tmp_table_name') AND attname = '$tmp_column_name'; ";
		}
		if ($db_type == "mysql") {
			//$sql .= "SELECT * FROM information_schema.COLUMNS where TABLE_SCHEMA = '$db_name' and TABLE_NAME = '$tmp_table_name' and COLUMN_NAME = '$tmp_column_name' ";
			$sql .= "show columns from $tmp_table_name where field = '$tmp_column_name' ";
		}
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		if (!$result) {
			//return false;
		}
		if (count($result) > 0) {
			//echo "table $tmp_table_name $tmp_column_name exists $result<br />\n";
			return true;
		}
		else {
			//echo "table $tmp_table_name $tmp_column_name does not exist<br />\n";
			return false;
		}
		unset ($prepstatement);
}


function db_upgrade_schema ($db, $db_type, $db_name, $display_results) {
	global $display_type;

	//PHP PDO check if table or column exists
		//check if table exists
			// SELECT * FROM sqlite_master WHERE type='table' and name='v_cdr'
		//check if column exists
			// SELECT * FROM sqlite_master WHERE type='table' and name='v_cdr' and sql like '%caller_id_name TEXT,%'
		//aditional information
			// http://www.sqlite.org/faq.html#q9

		//postgresql
			//list all tables in the database
				// select tablename from pg_tables where schemaname='public';
			//check if table exists
				// select * from pg_tables where schemaname='public' and tablename = 'v_groups'
			//check if column exists
				// SELECT attname FROM pg_attribute WHERE attrelid = (SELECT oid FROM pg_class WHERE relname = 'v_cdr') AND attname = 'caller_id_name'; 
		//mysql
			//list all tables in the database
				// SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = 'fusionpbx'
			//check if table exists
				// SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = 'fusionpbx' and TABLE_NAME = 'v_groups'
			//check if column exists
				// SELECT * FROM information_schema.COLUMNS where TABLE_SCHEMA = 'fusionpbx' and TABLE_NAME = 'v_cdr' and COLUMN_NAME = 'context'
		//oracle
			//check if table exists
				// SELECT TABLE_NAME FROM ALL_TABLES

	//get the $apps array from the installed apps from the core and mod directories
		$config_list = glob($_SERVER["DOCUMENT_ROOT"] . PROJECT_PATH . "/*/*/v_config.php");
		$x=0;
		foreach ($config_list as &$config_path) {
			include($config_path);
			$x++;
		}

	//show the database type
		if ($display_results && $display_type == "html") {
			echo "<strong>Database Type: ".$db_type. "</strong><br /><br />";
		}

	//declare the variable(s)
		$sql_update = '';

	//list all tables
		if ($display_results && $display_type == "html") {
			echo "<table width='100%' border='0' cellpadding='20' cellspacing='0'>\n";
			echo "<tr>\n";
			echo "<th>Table</th>\n";
			echo "<th>Exists</th>\n";
			echo "<th>Details</th>\n";
			echo "<tr>\n";
		}

		$var_id = $_GET["id"];

		$sql = '';
		foreach ($apps as &$app) {
			foreach ($app['db'] as $row) {
				$table_name = $row['table'];

				if ($display_results && $display_type == "html") {
					echo "<tr>\n";
				}

				//check if the table exists
					if (db_table_exists($db, $db_type, $db_name, $table_name)) {
						if ($display_results && $display_type == "html") {
							echo "<td valign='top' class='rowstyle1'><strong>table</strong><br />$table_name</td>\n";
							echo "<td valign='top' class='vncell' style=''>true</td>\n";
						}
						$field_count = 0;
						foreach ($row['fields'] as $field) {
							if ($field_count > 0 ) { $sql .= ",\n"; }
							$sql .= $field['name'] . " ";
							if (is_array($field['type'])) {
								$sql .= $field['type'][$db_type];
							}
							else {
								$sql .= $field['type'];
							}
							$field_count++;
						}

						if (count($row['fields']) > 0) {
							if ($display_results && $display_type == "html") {
								echo "<td class='rowstyle1'>\n";
							}
							//show the list of columns
								if ($display_results && $display_type == "html") {
									echo "<table border='0' cellpadding='10' cellspacing='0'>\n";
									echo "<tr>\n";
									echo "<th>name</th>\n";
									echo "<th>type</th>\n";
									echo "<th>exists</th>\n";
									echo "</tr>\n";
								}
								foreach ($row['fields'] as $field) {
									if (is_array($field['type'])) {
										$field_type = $field['type'][$db_type];
									}
									else {
										$field_type = $field['type'];
									}
					
									if ($display_results && $display_type == "html") {
										echo "<tr>\n";
										echo "<td class='rowstyle1' width='200'>".$field['name']."</td>\n";
										echo "<td class='rowstyle1'>".$field_type."</td>\n";
									}
									if (db_column_exists ($db, $db_type, $db_name, $table_name, $field['name'])) {
										if ($display_results && $display_type == "html") {
											echo "<td class='rowstyle0' style=''>true</td>\n";
											echo "<td>&nbsp;</td>\n";
										}
									}
									else {
										$sql_update .= "alter table ".$table_name." add ".$field['name']." ".$field_type."; \n";
										if ($display_results && $display_type == "html") {
											echo "<td class='rowstyle1' style='background-color:#444444;color:#CCCCCC;'>false</td>\n";
											echo "<td>&nbsp;</td>\n";
										}
									}
									if ($display_results && $display_type == "html") {
										echo "</tr>\n";
									}
								}
								unset($column_array);
							if ($display_results && $display_type == "html") {
								echo "	</table>\n";
								echo "</td>\n";
							}
						}
					}
					else {
						$sql = "CREATE TABLE " . $row['table'] . " (\n";
						$field_count = 0;
						foreach ($row['fields'] as $field) {
							if ($field_count > 0 ) { $sql .= ",\n"; }
							$sql .= $field['name'] . " ";
							if (is_array($field['type'])) {
								$sql .= $field['type'][$db_type];
							}
							else {
								$sql .= $field['type'];
							}
							$field_count++;
						}
						$sql .= ");\n\n";
						$sql_update .= $sql;
						if ($display_results && $display_type == "html") {
							echo "<td valign='top' class='rowstyle1'><strong>table</strong><br />$table_name</td>\n";
							echo "<td valign='top' class='rowstyle1' style='background-color:#444444;color:#CCCCCC;'><strong>exists</strong><br />false</td>\n";
							echo "<td valign='top' class='rowstyle1'>&nbsp;</td>\n";
						}
					}

				if ($display_results && $display_type == "html") {
					echo "</tr>\n";
				}
			}
		}
		unset ($prepstatement);
		if ($display_results) {
			if (strlen($sql_update) > 0) {
				if ($display_type == "html") {
					echo "<tr>\n";
					echo "<td class='rowstyle1' colspan='3'>\n";
					echo "<br />\n";
					echo "<strong>SQL Changes:</strong><br />\n";
					echo "<pre>\n";
					echo $sql_update;
					echo "</pre>\n";
					echo "<br />\n";
					echo "</td>\n";
					echo "</tr>\n";
				}
			}
			if ($display_type == "html") {
				echo "</table>\n";
			}
		}

		//loop line by line through all the lines of sql code
			$x = 0;
			if (strlen($sql_update) == 0 && $display_type == "text") {
				echo "	Schema:			no change\n";
			}
			else {
				if ($display_type == "text") {
					echo "	Schema:\n";
				}
				$db->beginTransaction();
				$update_array = explode(";", $sql_update);
				foreach($update_array as $sql) {
					if (strlen(trim($sql))) {
						try {
							$db->query(trim($sql));
							if ($display_type == "text") {
								echo " 	$sql\n";
							}
						}
						catch (PDOException $error) {
							if ($display_results) {
								echo "	error: " . $error->getMessage() . "	sql: $sql<br/>";
							}
						}
					}
				}
				$db->commit();
				echo "\n";
				unset ($file_contents, $sql_update, $sql);
			}

} //end function

?>