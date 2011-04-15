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
	class menu_restore {
		var $v_id;

		//delete items in the menu that are not protected
			function delete() {
				//define the global variables
					global $db;
				//set the variable
					$v_id = $this->v_id;
				//remove the old menu
					$sql  = "delete from v_menu ";
					$sql .= "where v_id = '$v_id' ";
					$sql .= "and (menu_protected <> 'true' ";
					$sql .= "or menu_protected is null ";
					$sql .= "or menu_protected = '');";
					$db->exec(check_sql($sql));
			}

		//restore the menu
			function restore() {
				global $db;

				//set the variable
					$v_id = $this->v_id;

				//load the default database into a sqlite memory database
					$filename = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/sql/sqlite.sql';
					$file_contents = file_get_contents($filename);
					unset($filename);
					try {
						$db_default = new PDO('sqlite::memory:'); //sqlite 3
						//$db_default->beginTransaction();
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
								$db_default->query($sql);
							}
							catch (PDOException $error) {
								echo "error: " . $error->getMessage() . " sql: $sql<br/>";
								//die();
							}
							$x++;
						}
						unset ($file_contents, $sql);
						//$db_default->commit();

				//load the default menu into an array
					$sql = "";
					$sql .= "select * from v_menu ";
					$prepstatement = $db_default->prepare(check_sql($sql));
					$prepstatement->execute();
					$menu_array = $prepstatement->fetchAll();

				//use the menu array to restore the default menu
					foreach ($menu_array as &$row) {
						//set the variables
							$menulanguage = $row["menulanguage"];
							$menutitle = $row["menutitle"];
							$menustr = $row["menustr"];
							$menucategory = $row["menucategory"];
							$menudesc = $row["menudesc"];
							$menuorder = $row["menuorder"];
							$menugroup = $row["menugroup"];
							$menuadduser = $row["menuadduser"];
							$menuadddate = $row["menuadddate"];
							$menumoduser = $row["menumoduser"];
							$menumoddate = $row["menumoddate"];
							$menu_protected = $row["menu_protected"];
							$menu_guid = $row["menu_guid"];
							$menu_parent_guid = $row["menu_parent_guid"];

						//if the guid is not currently in the db then add it
							$sql = "select * from v_menu ";
							$sql .= "where v_id = '$v_id' ";
							$sql .= "and menu_guid = '$menu_guid' ";
							$prepstatement = $db->prepare(check_sql($sql));
							if ($prepstatement) {
								$prepstatement->execute();
								$result = $prepstatement->fetchAll(PDO::FETCH_ASSOC);
								if (count($result) == 0) {
									//insert the default menu into the database
										$sql = "insert into v_menu ";
										$sql .= "(";
										$sql .= "v_id, ";
										$sql .= "menulanguage, ";
										$sql .= "menutitle, ";
										$sql .= "menustr, ";
										$sql .= "menucategory, ";
										$sql .= "menugroup, ";
										$sql .= "menudesc, ";
										$sql .= "menuorder, ";
										$sql .= "menuadduser, ";
										$sql .= "menuadddate, ";
										$sql .= "menumoduser, ";
										$sql .= "menumoddate, ";
										$sql .= "menu_protected, ";
										$sql .= "menu_guid, ";
										$sql .= "menu_parent_guid ";
										$sql .= ")";
										$sql .= "values ";
										$sql .= "(";
										$sql .= "'$v_id', ";
										$sql .= "'$menulanguage', ";
										$sql .= "'$menutitle', ";
										$sql .= "'$menustr', ";
										$sql .= "'$menucategory', ";
										$sql .= "'$menugroup', ";
										$sql .= "'$menudesc', ";
										$sql .= "'$menuorder', ";
										$sql .= "'$menuadduser', ";
										$sql .= "'$menuadddate', ";
										$sql .= "'$menumoduser', ";
										$sql .= "'$menumoddate', ";
										$sql .= "'$menu_protected', ";
										$sql .= "'$menu_guid', ";
										$sql .= "'$menu_parent_guid' ";
										$sql .= ")";
										$db->exec(check_sql($sql));
										unset($sql);
								}
							}
							unset($prepstatement, $result);
					}
			} //end function
	} //class

?>