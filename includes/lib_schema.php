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

	$db->beginTransaction();

	/*
	//PHP PDO check if table or column exists
		//check if table exists
			SELECT * FROM sqlite_master WHERE type='table' and name='v_cdr'
		//check if column exists
			SELECT * FROM sqlite_master WHERE type='table' and name='v_cdr' and sql like '%caller_id_name TEXT,%'
		//aditional information
			http://www.sqlite.org/faq.html#q9

		//postgresql
			//list all tables in the database
				select tablename from pg_tables where schemaname='public';
			//check if table exists
				select * from pg_tables where schemaname='public' and tablename = 'v_groups'
			//check if column exists
				SELECT attname FROM pg_attribute WHERE attrelid = (SELECT oid FROM pg_class WHERE relname = 'v_cdr') AND attname = 'caller_id_name'; 

		//mysql
			//list all tables in the database
				SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = 'fusionpbx'
			//check if table exists
				SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = 'fusionpbx' and TABLE_NAME = 'v_groups'
			//check if column exists
				SELECT * FROM information_schema.COLUMNS where TABLE_SCHEMA = 'fusionpbx' and TABLE_NAME = 'v_cdr' and COLUMN_NAME = 'context'

		//oracle
			//check if table exists
				SELECT TABLE_NAME FROM ALL_TABLES
	*/

	//schema sqlite db
		//--- begin: create the sqlite db file -----------------------------------------
			$filename = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/sql/sqlite.sql';
			$file_contents = file_get_contents($filename);
			unset($filename);
			try {
				//$dbschema = new PDO('sqlite:'.$dbfilepath.'/'.$dbfilename); //sqlite 3
				$dbschema = new PDO('sqlite::memory:'); //sqlite 3
				$dbschema->beginTransaction();
			}
			catch (PDOException $error) {
				print "error: " . $error->getMessage() . "<br/>";
				die();
			}

			//replace \r\n with \n then explode on \n
				$file_contents = str_replace("\r\n", "\n", $file_contents);

			//loop line by line through all the lines of sql code
				$stringarray = explode("\n", $file_contents);
				$x = 0;
				foreach($stringarray as $sql) {
						try {
							$dbschema->query($sql);
						}
						catch (PDOException $error) {
							echo "error: " . $error->getMessage() . " sql: $sql<br/>";
							//die();
						}
					$x++;
				}
				unset ($file_contents, $sql);
				$dbschema->commit();
		//--- end: create the sqlite db -----------------------------------------

	if ($display_results) {
		echo "<strong>Database Type: ".$db_type. "</strong><br /><br />";
	}

	//declare the variable(s)
		$sqlupdate = '';

	//list all tables
		if ($display_results) {
			echo "<table width='100%' border='0' cellpadding='20' cellspacing='0'>\n";
			echo "<tr>\n";
			echo "<th>Table</th>\n";
			echo "<th>Exists</th>\n";
			echo "<th>Details</th>\n";
			echo "<tr>\n";
		}

		$var_id = $_GET["id"];
		$sql = "";
		$sql .= "SELECT * FROM sqlite_master WHERE type='table' ";
		$prepstatement = $dbschema->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$table_name = $row["name"];
			$sql = trim($row["sql"]);
			$sql = str_replace("\r\n", " ", $sql);
			$sql = str_replace("\n", " ", $sql);
			$sql = str_replace("  ", " ", $sql);
			//echo "table name: $table_name<br />\n";
			if ($display_results) {
				echo "<tr>\n";
			}

			//check if the table exists
				if (db_table_exists($db, $db_type, $db_name, $table_name)) {
					if ($display_results) {
						echo "<td valign='top' class='rowstyle1'><strong>table</strong><br />$table_name</td>\n";
						echo "<td valign='top' class='vncell' style=''>true</td>\n";
					}
					if (preg_match("/\(.*\)/", $sql, $matches)) {
						//print_r($matches);
						if ($display_results) {
							echo "<td class='rowstyle1'>\n";
						}

						//remove the ( ) brackets
							$column_list = trim($matches[0], " ()");
						//explode the data into a column array with column name and datatype
							$column_list_array = explode(",", $column_list);
						//explode using the space to seperate the name and data type
							$x=0;
							foreach ($column_list_array as &$row) {
								$row_array = explode(" ", trim($row));
								//print_r($row_array);
								$column_array[$x]['column_name'] = $row_array[0];
								if (count($row_array) == 2){ $column_array[$x]['column_data_type'] = $row_array[1]; }
								if (count($row_array) == 3){ $column_array[$x]['column_data_type'] = $row_array[1].' '.$row_array[2]; }
								if (count($row_array) == 4){ $column_array[$x]['column_data_type'] = $row_array[1].' '.$row_array[2].' '.$row_array[3]; }
								$x++;
							}
							unset($column_list_array);
						//show the list of columns
							if ($display_results) {
								echo "<table border='0' cellpadding='10' cellspacing='0'>\n";
								echo "<tr>\n";
								echo "<th>name</th>\n";
								echo "<th>type</th>\n";
								echo "<th>exists</th>\n";
								echo "</tr>\n";
							}
							foreach ($column_array as &$column_row) {
								//print_r($column_row);
								if ($display_results) {
									echo "<tr>\n";
									echo "<td class='rowstyle1' width='200'>".$column_row['column_name']."</td>\n";
									echo "<td class='rowstyle1'>".$column_row['column_data_type']."</td>\n";
								}
								if (db_column_exists ($db, $db_type, $db_name, $table_name, $column_row['column_name'])) {
									if ($display_results) {
										echo "<td class='vncell' style=''>true</td>\n";
										echo "<td>&nbsp;</td>\n";
									}
								}
								else {
									$tmpsql = "alter table ".$table_name." add ".$column_row['column_name']." ".$column_row['column_data_type']."\n";
									$sqlupdate .= $tmpsql;
									if ($display_results) {
										echo "<td class='rowstyle1' style='background-color:#8D0D0D;'>false</td>\n";
										echo "<td class='rowstyle1' style='background-color:#8D0D0D;'>$tmpsql</td>\n";
									}
								}
								if ($display_results) {
									echo "</tr>\n";
								}
							}
							unset($column_array);
							if ($display_results) {
								echo "</table>\n";
							}
						if ($display_results) {
							echo "</td>\n";
						}
					}
					else {
						if ($display_results) {
							echo "<td class='rowstyle1'>\n";
							echo "	$sql";
							echo "</td>\n";
						}
					}
				}
				else {
					if ($db_type == "sqlite") {
						$sqlupdate .= $sql."\n";
					}
					if ($db_type == "pgsql") {
						$sql = str_replace("INTEGER PRIMARY KEY", "SERIAL", $sql);
						$sql = str_replace("NUMBER", "NUMERIC", $sql);
						$sqlupdate .= $sql."\n";
					}
					if ($db_type == "mysql") {
						$sql = str_replace("INTEGER PRIMARY KEY", "INT NOT NULL AUTO_INCREMENT PRIMARY KEY", $sql);
						$sql = str_replace("NUMBER", "NUMERIC", $sql);
						$sqlupdate .= $sql."\n";
					}
					if ($display_results) {
						echo "<td valign='top' class='rowstyle1'><strong>table</strong><br />$table_name</td>\n";
						echo "<td valign='top' class='rowstyle1' style='background-color:#8D0D0D;'><strong>exists</strong><br />false</td>\n";
					}
				}

			if ($display_results) {
				//echo "<br />\n";
				echo "</tr>\n";
			}
		}
		unset ($prepstatement);
		if ($display_results) {
			if (strlen($sqlupdate) > 0) {
				echo "<tr>\n";
				echo "<td class='rowstyle1' colspan='3'>\n";
				echo "<pre>\n";
				echo $sqlupdate;
				echo "</pre>\n";
				echo "</td>\n";
				echo "</tr>\n";
			}
			echo "</table>\n";
		}

		//loop line by line through all the lines of sql code
			$udpate_array = explode("\n", $sqlupdate);
			$x = 0;
			foreach($udpate_array as $sql) {
				try {
					$db->query($sql);
				}
				catch (PDOException $error) {
					if ($display_results) {
						echo "error: " . $error->getMessage() . " sql: $sql<br/>";
					}
					//die();
				}
				$x++;
			}
			unset ($file_contents, $sqlupdate, $sql);

	$db->commit();
} //end function

?>