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
require_once "includes/config.php";
session_start();

// set the salt for password hash generation
// changing this string can cause existing users to no longer be able to log in,
// unless you regenerate their passwords in the v_users table
	$v_salt = 'e3.7d.12';


//if the username session is not set the check username and password
	if (strlen($_SESSION["username"]) == 0) {
		//clear the menu
			$_SESSION["menu"] = "";

		//clear the template only if the template has not been assigned by the superadmin
			if (strlen($_SESSION["v_template_name"]) == 0) {
				$_SESSION["template_content"] = '';
			}

		//if username from form is not provided then send to login.php
			if (strlen(check_str($_POST["username"])) == 0) {
				$strphpself = $_SERVER["PHP_SELF"];
				//$strphpself = str_replace ("/", "", $strphpself);
				$msg = "Please provide a username.";
				header("Location: ".PROJECT_PATH."/login.php?path=".urlencode($strphpself)."&msg=".urlencode($msg));
				exit;
			}

		//check the username and password if they don't match then redirect back to login
			$sql = "select * from v_users ";
			$sql .= "where v_id=:v_id ";
			$sql .= "and username=:username ";
			$sql .= "and password=:password ";
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->bindParam(':v_id', $v_id);
			$prepstatement->bindParam(':username', check_str($_POST["username"]));
			$prepstatement->bindParam(':password', md5($v_salt.check_str($_POST["password"])));
			$prepstatement->execute();
			$result = $prepstatement->fetchAll(PDO::FETCH_NAMED);
			$resultcount = count($result);
			if (count($result) == 0) {
				$strphpself = $_SERVER["PHP_SELF"];
				//$strphpself = str_replace ("/", "", $strphpself);

				//Log the failed auth attempt to the system, to be available for fail2ban.
				openlog('FusionPBX', LOG_NDELAY, LOG_AUTH);
				syslog(LOG_WARNING, '['.$_SERVER['REMOTE_ADDR']."] authentication failed for ".$_POST["username"]);
				closelog();

				$msg = "Username or Password were incorrect. Please try again.";
				header("Location: ".PROJECT_PATH."/login.php?path=".urlencode($strphpself)."&msg=".urlencode($msg));
				exit;
			}
			else {
				$_SESSION["username"] = check_str($_POST["username"]);
				foreach ($result as &$row) {
					//allow the user to choose a template only if the template has not been assigned by the superadmin
					if (strlen($_SESSION["v_template_name"]) == 0) {
						$_SESSION["template_name"] = $row["user_template_name"];
					}
					$_SESSION["time_zone"]["user"] = '';
					if (strlen($row["user_time_zone"]) > 0) {
						//user defined time zone
						$_SESSION["time_zone"]["user"] = $row["user_time_zone"];
					}
					break;
				}
				//echo "username: ".$_SESSION["username"]." and password are correct";
			}

		//if there are no permissions listed in v_group_permissions then set the default permissions
			$sql = "";
			$sql .= "select count(*) as count from v_group_permissions ";
			$prep_statement = $db->prepare(check_sql($sql));
			$prep_statement->execute();
			$result = $prep_statement->fetchAll();
			foreach ($result as &$row) {
				$group_permission_count = $row["count"];
				break; //limit to 1 row
			}
			unset ($prep_statement);
			if ($group_permission_count == 0) {
				//no permissions found add the defaults
				foreach($apps as $app) {
					foreach ($app['permissions'] as $row) {
						foreach ($row['groups'] as $group) {
							//add the record
							$sql = "insert into v_group_permissions ";
							$sql .= "(";
							$sql .= "v_id, ";
							$sql .= "permission_id, ";
							$sql .= "group_id ";
							$sql .= ")";
							$sql .= "values ";
							$sql .= "(";
							$sql .= "'$v_id', ";
							$sql .= "'".$row['name']."', ";
							$sql .= "'".$group."' ";
							$sql .= ")";
							$db->exec(check_sql($sql));
							unset($sql);
						}
					}
				}
			}

		//get the groups the user is a member of
			$sql = "SELECT * FROM v_group_members ";
			$sql .= "where v_id=:v_id ";
			$sql .= "and username=:username ";
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->bindParam(':v_id', $v_id);
			$prepstatement->bindParam(':username', $_SESSION["username"]);
			$prepstatement->execute();
			$result = $prepstatement->fetchAll(PDO::FETCH_NAMED);
			$_SESSION["groups"] = $result;
			unset($sql, $rowcount, $prepstatement);

		//get and set the permissions to a session
			$x = 0;
			$sql = "select distinct(permission_id) from v_group_permissions ";
			foreach($result as $field) {
				if (strlen($field[groupid]) > 0) {
					if ($x == 0) {
						$sql .= "where (v_id = '".$v_id."' and group_id = '".$field['groupid']."') ";
					}
					else {
						$sql .= "or (v_id = '".$v_id."' and group_id = '".$field['groupid']."') ";
					}
					$x++;
				}
			}
			$prepstatementsub = $db->prepare($sql);
			$prepstatementsub->execute();
			$_SESSION['permissions'] = $prepstatementsub->fetchAll(PDO::FETCH_NAMED);
			unset($sql, $prepstatementsub);

		//redirect the user
			$path = check_str($_POST["path"]);
			if(isset($path) && !empty($path) && $path!="index2.php") {
				header("Location: ".$path);
				die();
			}
	}

//set the time zone
	if (strlen($_SESSION["time_zone"]["user"]) == 0) {
		//set the domain time zone as the default time zone
		date_default_timezone_set($_SESSION["v_time_zone"]);
	}
	else {
		//set the user defined time zone
		date_default_timezone_set($_SESSION["time_zone"]["user"]);
	}

//hide the path unless logged in as a superadmin.
	if (!ifgroup("superadmin")) {
		$v_path_show = false;
	}

?>
