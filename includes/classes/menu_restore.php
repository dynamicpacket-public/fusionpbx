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

//define the follow me class
	class menu_restore {
		var $v_id;

		//delete items in the menu that are not protected
			function delete() {
				//set the variable
					$db = $this->db;
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
				//set the variable
					$db = $this->db;
					$v_id = $this->v_id;

				//get the $apps array from the installed apps from the core and mod directories
					$config_list = glob($_SERVER["DOCUMENT_ROOT"] . PROJECT_PATH . "/*/*/v_config.php");
					$x=0;
					foreach ($config_list as &$config_path) {
						include($config_path);
						$x++;
					}

				//use the app array to restore the default menu
					$db->beginTransaction();
					foreach ($apps as $row) {
						foreach ($row['menu'] as $menu) {
							//set the variables
								$menu_title = $menu['title']['en'];
								$menu_language = 'en';
								$menu_guid = $menu['guid'];
								$menu_parent_guid = $menu['parent_guid'];
								$menu_category = $menu['category'];
								$menu_path = $menu['path'];
								$menu_order = $menu['order'];
								$menu_desc = $menu['desc'];
								if (strlen($menu_order) == 0) {
									$menu_order = 1;
								}

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
											$sql .= "menudesc, ";
											$sql .= "menuorder, ";
											$sql .= "menu_guid, ";
											$sql .= "menu_parent_guid ";
											$sql .= ") ";
											$sql .= "values ";
											$sql .= "(";
											$sql .= "'$v_id', ";
											$sql .= "'$menu_language', ";
											$sql .= "'$menu_title', ";
											$sql .= "'$menu_path', ";
											$sql .= "'$menu_category', ";
											$sql .= "'$menu_desc', ";
											$sql .= "'$menu_order', ";
											$sql .= "'$menu_guid', ";
											$sql .= "'$menu_parent_guid' ";
											$sql .= ")";

											if ($menu_guid == $menu_parent_guid) {
												//echo $sql."<br />\n";
											}
											else {
												$db->exec(check_sql($sql));
											}
											unset($sql);
									}
								}
						}
					}
					$db->commit();
			} //end function
	} //class

?>