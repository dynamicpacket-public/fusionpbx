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

	if (!function_exists('software_version')) {
		function software_version() {
			return '2.0.5';
		}
	}

	if (!function_exists('check_str')) {
		function check_str($strtemp) {
			//when code in db is urlencoded the ' does not need to be modified
			$strtemp = str_replace ("'", "''", $strtemp); //escape the single quote
			$strtemp = trim ($strtemp); //remove white space
			return $strtemp;
		}
	}

	if (!function_exists('check_sql')) {
		function check_sql($strtemp) {
			global $db_type;
			if ($db_type == "sqlite") {
				//place holder
			}
			if ($db_type == "pgsql") {
				$strtemp = str_replace ("\\", "\\\\", $strtemp); //escape the backslash
			}
			if ($db_type == "mysql") {
				$strtemp = str_replace ("\\", "\\\\", $strtemp); //escape the backslash
			}
			$strtemp = trim ($strtemp); //remove white space
			return $strtemp;
		}
	}

	if (!function_exists('recursive_copy')) {
		function recursive_copy($src,$dst) {
			$dir = opendir($src);
			if (!$dir) {
				throw new Exception("recursive_copy() source directory '".$src."' does not exist.");
			}
			if (!is_dir($dst)) {
				if (!mkdir($dst)) {
					throw new Exception("recursive_copy() failed to create destination directory '".$dst."'");
				}
			}
			while(false !== ( $file = readdir($dir)) ) {
				if (( $file != '.' ) && ( $file != '..' )) {
					if ( is_dir($src . '/' . $file) ) {
						recursive_copy($src . '/' . $file,$dst . '/' . $file);
					}
					else {
						copy($src . '/' . $file,$dst . '/' . $file);
					}
				}
			}
			closedir($dir);
		}
	}

	if (!function_exists('ifgroup')) {
		function ifgroup($group) {
			//set default false
				$result = false;
			//search for the permission
				if (count($_SESSION["groups"]) > 0) {
					foreach($_SESSION["groups"] as $row) {
						if ($row['groupid'] == $group) {
							$result = true;
							break;
						}
					}
				}
			//return the result
				return $result;
		}
	}

	if (!function_exists('permission_exists')) {
		function permission_exists($permission) {
			//set default false
				$result = false;
			//search for the permission
				if (count($_SESSION["permissions"]) > 0) {
					foreach($_SESSION["permissions"] as $row) {
						if ($row['permission_id'] == $permission) {
							$result = true;
							break;
						}
					}
				}
			//return the result
				return $result;
		}
	}

	if (!function_exists('groupmemberlist')) {
		function groupmemberlist($db, $username) {
			global $v_id;
			$sql = "select * from v_group_members ";
			$sql .= "where v_id = '$v_id' ";
			$sql .= "and username = '".$username."' ";
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$result = $prepstatement->fetchAll();
			$resultcount = count($result);

			$groupmemberlist = "||";
			foreach($result as $field) {
				//get the list of groups
				$groupmemberlist .= $field[groupid]."||";
			}
			unset($sql, $result, $rowcount);
			return $groupmemberlist;
		}
	}

	if (!function_exists('ifgroupmember')) {
		function ifgroupmember($groupmemberlist, $group) {
			if (stripos($groupmemberlist, "||".$group."||") === false) {
				return false; //group does not exist
			}
			else {
				return true; //group exists
			}
		}
	}

	if (!function_exists('superadminlist')) {
		function superadminlist($db) {
			global $v_id;
			$sql = "select * from v_group_members ";
			$sql .= "where groupid = 'superadmin' ";
			//echo $sql;
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$result = $prepstatement->fetchAll();
			$resultcount = count($result);

			$strsuperadminlist = "||";
			foreach($result as $field) {
				//get the list of superadmins
				$strsuperadminlist .= $field[groupid]."||";
			}
			unset($sql, $result, $rowcount);
			return $strsuperadminlist;
		}
	}
	//superadminlist($db);

	if (!function_exists('ifsuperadmin')) {
		function ifsuperadmin($superadminlist, $username) {
			if (stripos($superadminlist, "||".$username."||") === false) {
				return false; //username does not exist
			}
			else {
				return true; //username exists
			}
		}
	}

	if (!function_exists('htmlselectother')) {
		function htmlselectother($db, $tablename, $fieldname, $sqlwhereoptional, $fieldcurrentvalue) {
			//html select other : build a select box from distinct items in db with option for other
			global $v_id;

			$html  = "<table width='50%' border='0' cellpadding='1' cellspacing='0'>\n";
			$html .= "<tr>\n";
			$html .= "<td id=\"cell".$fieldname."1\" width='100%'>\n";
			$html .= "\n";
			$html .= "<select id=\"".$fieldname."\" name=\"".$fieldname."\" class='formfld' style='width: 100%;' onchange=\"if (document.getElementById('".$fieldname."').value == 'Other') { /*enabled*/ document.getElementById('".$fieldname."_other').style.width='95%'; document.getElementById('cell".$fieldname."2').width='70%'; document.getElementById('cell".$fieldname."1').width='30%'; document.getElementById('".$fieldname."_other').disabled = false; document.getElementById('".$fieldname."_other').className='txt'; document.getElementById('".$fieldname."_other').focus(); } else { /*disabled*/ document.getElementById('".$fieldname."_other').value = ''; document.getElementById('cell".$fieldname."1').width='95%'; document.getElementById('cell".$fieldname."2').width='5%'; document.getElementById('".$fieldname."_other').disabled = true; document.getElementById('".$fieldname."_other').className='frmdisabled' } \">\n";
			$html .= "<option value=''></option>\n";

			$sql = "SELECT distinct($fieldname) as $fieldname FROM $tablename $sqlwhereoptional ";
			//echo $sql;
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$result = $prepstatement->fetchAll();
			$resultcount = count($result);
			//echo $resultcount;
			if ($resultcount > 0) { //if user account exists then show login
				//print_r($result);
				foreach($result as $field) {
					if (strlen($field[$fieldname]) > 0) {
						if ($fieldcurrentvalue == $field[$fieldname]) {
							$html .= "<option value=\"".$field[$fieldname]."\" selected>".$field[$fieldname]."</option>\n";
						}
						else {
							$html .= "<option value=\"".$field[$fieldname]."\">".$field[$fieldname]."</option>\n";
						}
					}
				}
			}
			unset($sql, $result, $resultcount);

			$html .= "<option value='Other'>Other</option>\n";
			$html .= "</select>\n";
			$html .= "</td>\n";
			$html .= "<td id=\"cell".$fieldname."2\" width='5'>\n";
			$html .= "<input id=\"".$fieldname."_other\" name=\"".$fieldname."_other\" value='' style='width: 5%;' disabled onload='document.getElementById('".$fieldname."_other').disabled = true;' type='text' class='frmdisabled'>\n";
			$html .= "</td>\n";
			$html .= "</tr>\n";
			$html .= "</table>";

		return $html;
		}
	}

	if (!function_exists('htmlselect')) {
		function htmlselect($db, $tablename, $fieldname, $sqlwhereoptional, $fieldcurrentvalue, $fieldvalue = '', $style = '') {
			//html select other : build a select box from distinct items in db with option for other
			global $v_id;

			if (strlen($fieldvalue) > 0) {
			$html .= "<select id=\"".$fieldvalue."\" name=\"".$fieldvalue."\" class='formfld' style='".$style."'>\n";
			$html .= "<option value=\"\"></option>\n";
				$sql = "SELECT distinct($fieldname) as $fieldname, $fieldvalue FROM $tablename $sqlwhereoptional order by $fieldname asc ";
			}
			else {
				$html .= "<select id=\"".$fieldname."\" name=\"".$fieldname."\" class='formfld' style='".$style."'>\n";
				$html .= "<option value=\"\"></option>\n";
				$sql = "SELECT distinct($fieldname) as $fieldname FROM $tablename $sqlwhereoptional ";
			}

			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$result = $prepstatement->fetchAll();
			$resultcount = count($result);
			if ($resultcount > 0) { //if user account exists then show login
				foreach($result as $field) {
					if (strlen($field[$fieldname]) > 0) {
						if ($fieldcurrentvalue == $field[$fieldname]) {
							if (strlen($fieldvalue) > 0) {
								$html .= "<option value=\"".$field[$fieldvalue]."\" selected>".$field[$fieldname]."</option>\n";
							}
							else {
								$html .= "<option value=\"".$field[$fieldname]."\" selected>".$field[$fieldname]."</option>\n";
							}
						}
						else {
							if (strlen($fieldvalue) > 0) {
								$html .= "<option value=\"".$field[$fieldvalue]."\">".$field[$fieldname]."</option>\n";
							}
							else {
								$html .= "<option value=\"".$field[$fieldname]."\">".$field[$fieldname]."</option>\n";
							}
						}
					}
				}
			}
			unset($sql, $result, $resultcount);
			$html .= "</select>\n";

		return $html;
		}
	}
	//$tablename = 'v_templates'; $fieldname = 'templatename'; $sqlwhereoptional = "where v_id = '$v_id' "; $fieldcurrentvalue = '';
	//echo htmlselect($db, $tablename, $fieldname, $sqlwhereoptional, $fieldcurrentvalue);

	if (!function_exists('htmlselectonchange')) {
		function htmlselectonchange($db, $tablename, $fieldname, $sqlwhereoptional, $fieldcurrentvalue, $onchange, $fieldvalue = '') {
			//html select other : build a select box from distinct items in db with option for other
			global $v_id;

			$html .= "<select id=\"".$fieldname."\" name=\"".$fieldname."\" class='formfld' onchange=\"".$onchange."\">\n";
			$html .= "<option value=''></option>\n";

			$sql = "SELECT distinct($fieldname) as $fieldname FROM $tablename $sqlwhereoptional order by $fieldname asc ";
			//echo $sql;
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$result = $prepstatement->fetchAll();
			$resultcount = count($result);
			//echo $resultcount;
			if ($resultcount > 0) { //if user account exists then show login
				//print_r($result);
				foreach($result as $field) {
					if (strlen($field[$fieldname]) > 0) {
						if ($fieldcurrentvalue == $field[$fieldname]) {
								if (strlen($fieldvalue) > 0) {
									$html .= "<option value=\"".$field[$fieldvalue]."\" selected>".$field[$fieldname]."</option>\n";
								}
								else {
									$html .= "<option value=\"".$field[$fieldname]."\" selected>".$field[$fieldname]."</option>\n";
								}
						}
						else {
								if (strlen($fieldvalue) > 0) {
									$html .= "<option value=\"".$field[$fieldvalue]."\">".$field[$fieldname]."</option>\n";
								}
								else {
									$html .= "<option value=\"".$field[$fieldname]."\">".$field[$fieldname]."</option>\n";
								}
						}
					}
				}
			}
			unset($sql, $result, $resultcount);
			$html .= "</select>\n";

		return $html;
		}
	}

	if (!function_exists('thorderby')) {
		//html table header order by
		function thorderby($fieldname, $columntitle, $orderby, $order) {

			$html .= "<th nowrap>&nbsp; &nbsp; ";
			if (strlen($orderby)==0) {
				$html .= "<a href='?orderby=$fieldname&order=desc' title='ascending'>$columntitle</a>";
			}
			else {
				if ($order=="asc") {
					$html .= "<a href='?orderby=$fieldname&order=desc' title='ascending'>$columntitle</a>";
				}
				else {
					$html .= "<a href='?orderby=$fieldname&order=asc' title='descending'>$columntitle</a>";
				}
			}
			$html .= "&nbsp; &nbsp; </th>";
			return $html;
		}
	}
	////example usage
		//$tablename = 'tblcontacts'; $fieldname = 'contactcategory'; $sqlwhereoptional = "", $fieldcurrentvalue ='';
		//echo htmlselectother($db, $tablename, $fieldname, $sqlwhereoptional, $fieldcurrentvalue);
	////  On the page that recieves the POST
		//if (check_str($_POST["contactcategory"]) == "Other") { //echo "found: ".$contactcategory;
		//  $contactcategory = check_str($_POST["contactcategoryother"]);
		//}

	if (!function_exists('logadd')) {
		function logadd($db, $logtype, $logstatus, $logdesc, $logadduser, $logadduserip) {
			return; //this disables the function
			global $v_id;

			$sql = "insert into tbllogs ";
			$sql .= "(";
			$sql .= "logtype, ";
			$sql .= "logstatus, ";
			$sql .= "logdesc, ";
			$sql .= "logadduser, ";
			$sql .= "logadduserip, ";
			$sql .= "logadddate ";
			$sql .= ")";
			$sql .= "values ";
			$sql .= "(";
			$sql .= "'$logtype', ";
			$sql .= "'$logstatus', ";
			$sql .= "'$logdesc', ";
			$sql .= "'$logadduser', ";
			$sql .= "'$logadduserip', ";
			$sql .= "now() ";
			$sql .= ")";
			//echo $sql;
			$db->exec(check_sql($sql));
			unset($sql);
		}
	}
	//$logtype = ''; $logstatus=''; $logadduser=''; $logdesc='';
	//logadd($db, $logtype, $logstatus, $logdesc, $logadduser, $_SERVER["REMOTE_ADDR"]);

	if (!function_exists('get_ext')) {
		function get_ext($filename) {
			preg_match('/[^?]*/', $filename, $matches);
			$string = $matches[0];

			$pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);

			// check if there is any extension
			if(count($pattern) == 1){
				//echo 'No File Extension Present';
				return '';
			}
	 
			if(count($pattern) > 1) {
				$filenamepart = $pattern[count($pattern)-1][0];
				preg_match('/[^?]*/', $filenamepart, $matches);
				return $matches[0];
			}
		}
		//echo "ext: ".get_ext('test.txt');
	}

	if (!function_exists('fileupload')) {
			function fileupload($field = '', $file_type = '', $dest_dir = '') {

					$uploadtempdir = $_ENV["TEMP"]."\\";
					ini_set('upload_tmp_dir', $uploadtempdir);

					$tmp_name = $_FILES[$field]["tmp_name"];
					$file_name = $_FILES[$field]["name"];
					$file_type = $_FILES[$field]["type"];
					$file_size = $_FILES[$field]["size"];
					$file_ext = get_ext($file_name);
					$file_name_orig = $file_name;
					$file_name_base = substr($file_name, 0, (strlen($file_name) - (strlen($file_ext)+1)));
					//$dest_dir = '/tmp';

					if ($file_size ==  0){
						return;
					}

					if (!is_dir($dest_dir)) {
						echo "dest_dir not found<br />\n";
						return;
					}

					//check if allowed file type
					if ($file_type == "img") {
							switch (strtolower($file_ext)) {
								case "jpg":
									break;
								case "png":
									break;
								case "gif":
									break;
								case "bmp":
									break;
								case "psd":
									break;
								case "tif":
									break;
								default:
									return false;
							}
					}
					if ($file_type == "file") {
						switch (strtolower($file_ext)) {
							case "doc":
								break;
							case "pdf":
								break;
							case "ppt":
								break;
							case "xls":
								break;
							case "zip":
								break;
							case "exe":
								break;
							default:
								return false;
							}
					}

					//find unique filename: check if file exists if it does then increment the filename
						$i = 1;
						while( file_exists($dest_dir.'/'.$file_name)) {
							if (strlen($file_ext)> 0) {
								$file_name = $file_name_base . $i .'.'. $file_ext;
							}
							else {
								$file_name = $file_name_orig . $i;
							}
							$i++;
						}

					//echo "file_type: ".$file_type."<br />\n";
					//echo "tmp_name: ".$tmp_name."<br />\n";
					//echo "file_name: ".$file_name."<br />\n";
					//echo "file_ext: ".$file_ext."<br />\n";
					//echo "file_name_orig: ".$file_name_orig."<br />\n";
					//echo "file_name_base: ".$file_name_base."<br />\n";
					//echo "dest_dir: ".$dest_dir."<br />\n";

					//move the file to upload directory  
					//bool move_uploaded_file  ( string $filename, string $destination  )

						if (move_uploaded_file($tmp_name, $dest_dir.'/'.$file_name)){
							 return $file_name;
						}
						else {
							echo "File upload failed!  Here's some debugging info:\n";
							return false;
						}
						exit;
						
			} //end function
	}

	if ( !function_exists('sys_get_temp_dir')) {
		function sys_get_temp_dir() {
			if( $temp=getenv('TMP') )        return $temp;
			if( $temp=getenv('TEMP') )        return $temp;
			if( $temp=getenv('TMPDIR') )    return $temp;
			$temp=tempnam(__FILE__,'');
			if (file_exists($temp)) {
				unlink($temp);
				return dirname($temp);
			}
			return null;
		}
	}
	//echo realpath(sys_get_temp_dir());

	if (!function_exists('user_exists')) {
		function user_exists($username) {
			global $db, $v_id;
			$user_exists = false;
			$sql = "select * from v_users ";
			$sql .= "where v_id = '$v_id' ";
			$sql .= "and username = '".$username."' ";
			//echo $sql;
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$result = $prepstatement->fetchAll();
			$resultcount = count($result);
			if ($resultcount > 0) {
				return true;
			}
			else {
				return false;
			}
		}
	}

	if (!function_exists('user_add')) {
		function user_add($username, $password, $userfirstname='', $userlastname='', $useremail='') {
			if (strlen($username) == 0) { return false; }
			if (strlen($password) == 0) { return false; }
			if (!user_exists($username)) {
				global $db, $v_id;
				//add the user account
					$usertype = 'Individual';
					$usercategory = 'user';
					$sql = "insert into v_users ";
					$sql .= "(";
					$sql .= "v_id, ";
					$sql .= "username, ";
					$sql .= "password, ";
					$sql .= "usertype, ";
					$sql .= "usercategory, ";
					if (strlen($userfirstname) > 0) { $sql .= "userfirstname, "; }
					if (strlen($userlastname) > 0) { $sql .= "userlastname, "; }
					if (strlen($useremail) > 0) { $sql .= "useremail, "; }
					$sql .= "useradddate, ";
					$sql .= "useradduser ";
					$sql .= ")";
					$sql .= "values ";
					$sql .= "(";
					$sql .= "'$v_id', ";
					$sql .= "'$username', ";
					$sql .= "'".md5('e3.7d.12'.$password)."', ";
					$sql .= "'$usertype', ";
					$sql .= "'$usercategory', ";
					if (strlen($userfirstname) > 0) { $sql .= "'$userfirstname', "; }
					if (strlen($userlastname) > 0) { $sql .= "'$userlastname', "; }
					if (strlen($useremail) > 0) { $sql .= "'$useremail', "; }
					$sql .= "now(), ";
					$sql .= "'".$_SESSION["username"]."' ";
					$sql .= ")";
					$db->exec(check_sql($sql));
					unset($sql);

				//add the user to the member group
					$groupid = 'user';
					$sql = "insert into v_group_members ";
					$sql .= "(";
					$sql .= "v_id, ";
					$sql .= "groupid, ";
					$sql .= "username ";
					$sql .= ")";
					$sql .= "values ";
					$sql .= "(";
					$sql .= "'$v_id', ";
					$sql .= "'$groupid', ";
					$sql .= "'$username' ";
					$sql .= ")";
					$db->exec(check_sql($sql));
					unset($sql);
			} //end if !user_exists
		} //end function definition
	} //end function_exists

function switch_module_is_running($fp, $mod) {
	if (!$fp) {
		//if the handle does not exist create it
			$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
		//if the handle still does not exist show an error message
			if (!$fp) {
				$msg = "<div align='center'>Connection to Event Socket failed.<br /></div>"; 
			}
	}
	if ($fp) {
		//send the api command to check if the module exists
		$switchcmd = "module_exists $mod";
		$switch_result = event_socket_request($fp, 'api '.$switchcmd);
		unset($switchcmd);
		if (trim($switch_result) == "true") {
			return true;
		}
		else {
			return false;
		}
	}
	else {
		return false;
	}
}
//switch_module_is_running('mod_spidermonkey');

//format a number (n) replace with a number (r) remove the number
function format_string ($format, $data) {
	$x=0;
	$tmp = '';
	for ($i = 0; $i <= strlen($format); $i++) {
		$tmp_format = strtolower(substr($format, $i, 1));
		if ($tmp_format == 'x') {
			$tmp .= substr($data, $x, 1);
			$x++;
		}
		elseif ($tmp_format == 'r') {
			$x++;
		}
		else {
			$tmp .= $tmp_format;
		}
	}
	return $tmp;
}

//get the format and use it to format the phone number
	function format_phone($phone_number) {
		if (strlen($_SESSION["format_phone_array"]) == 0) {
			$_SESSION["format_phone_array"] = ""; //clear the menu
			global $v_id, $db;
			$sql = "select * from v_vars ";
			$sql .= "where v_id  = '$v_id' ";
			$sql .= "and var_name = 'format_phone' ";
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$result = $prepstatement->fetchAll();
			foreach ($result as &$row) {
				$_SESSION["format_phone_array"][] = $row["var_value"];
			}
			unset ($prepstatement);
		}
		foreach ($_SESSION["format_phone_array"] as &$format) {
			$format_count = substr_count($format, 'x');
			$format_count = $format_count + substr_count($format, 'R');
			if ($format_count == strlen($phone_number)) {
				//format the number
				$phone_number = format_string($format, $phone_number);
			}
		}
		return $phone_number;
	}

//browser detection without browscap.ini dependency
	function http_user_agent() { 
		$u_agent = $_SERVER['HTTP_USER_AGENT']; 
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";

		//get the platform?
			if (preg_match('/linux/i', $u_agent)) {
				$platform = 'linux';
			}
			elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
				$platform = 'mac';
			}
			elseif (preg_match('/windows|win32/i', $u_agent)) {
				$platform = 'windows';
			}

		//get the name of the useragent yes seperately and for good reason
			if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
			{ 
				$bname = 'Internet Explorer'; 
				$ub = "MSIE"; 
			} 
			elseif(preg_match('/Firefox/i',$u_agent)) 
			{ 
				$bname = 'Mozilla Firefox'; 
				$ub = "Firefox"; 
			} 
			elseif(preg_match('/Chrome/i',$u_agent)) 
			{ 
				$bname = 'Google Chrome'; 
				$ub = "Chrome"; 
			} 
			elseif(preg_match('/Safari/i',$u_agent)) 
			{ 
				$bname = 'Apple Safari'; 
				$ub = "Safari"; 
			} 
			elseif(preg_match('/Opera/i',$u_agent)) 
			{ 
				$bname = 'Opera'; 
				$ub = "Opera"; 
			} 
			elseif(preg_match('/Netscape/i',$u_agent)) 
			{ 
				$bname = 'Netscape'; 
				$ub = "Netscape"; 
			} 

		//finally get the correct version number
			$known = array('Version', $ub, 'other');
			$pattern = '#(?<browser>' . join('|', $known) .
			')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
			if (!preg_match_all($pattern, $u_agent, $matches)) {
				// we have no matching number just continue
			}

		// see how many we have
			$i = count($matches['browser']);
			if ($i != 1) {
				//we will have two since we are not using 'other' argument yet
				//see if version is before or after the name
				if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
					$version= $matches['version'][0];
				}
				else {
					$version= $matches['version'][1];
				}
			}
			else {
				$version= $matches['version'][0];
			}

		// check if we have a number
			if ($version==null || $version=="") {$version="?";}

		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	} 

//tail php function for non posix systems
	function tail($file, $num_to_get=10) {
			$fp = fopen($file, 'r');
			$position = filesize($file);
			$chunklen = 4096;
			if($position-$chunklen<=0) { 
				fseek($fp,0); 
			}
			else { 
				fseek($fp, $position-$chunklen);
			}
			$data="";$ret="";$lc=0;
			while($chunklen > 0)
			{
					$data = fread($fp, $chunklen);
					$dl=strlen($data);
					for($i=$dl-1;$i>=0;$i--){
							if($data[$i]=="\n"){
									if($lc==0 && $ret!="")$lc++;
									$lc++;
									if($lc>$num_to_get)return $ret;
							}
							$ret=$data[$i].$ret;
					}
					if($position-$chunklen<=0){
							fseek($fp,0);
							$chunklen=$chunklen-abs($position-$chunklen);
					}else   fseek($fp, $position-$chunklen);
					$position = $position - $chunklen;
			}
			fclose($fp);
			return $ret;
	}

//generate a random password with upper, lowercase and symbols
	function generate_password($length = 10, $strength = 4) {
		$password = '';
		$charset = '';
		if ($strength >= 1) { $charset .= "0123456789"; }
		if ($strength >= 2) { $charset .= "abcdefghijkmnopqrstuvwxyz";	}
		if ($strength >= 3) { $charset .= "ABCDEFGHIJKLMNPQRSTUVWXYZ";	}
		if ($strength >= 4) { $charset .= "!!!!!^$%*?....."; }
		srand((double)microtime() * rand(1000000, 9999999));
		while ($length > 0) {
				$password.= $charset[rand(0, strlen($charset)-1)];
				$length--;
		}
		return $password;
	}
	//echo generate_password(4, 4);

//based on Wez Furlong do_post_request
	if (!function_exists('send_http_request')) {
		function send_http_request($url, $data, $method = "POST", $optional_headers = null) {
			$params = array('http' => array(
						'method' => $method,
						'content' => $data
						));
			if ($optional_headers !== null) {
				$params['http']['header'] = $optional_headers;
			}
			$ctx = stream_context_create($params);
			$fp = @fopen($url, 'rb', false, $ctx);
			if (!$fp) {
				throw new Exception("Problem with $url, $php_errormsg");
			}
			$response = @stream_get_contents($fp);
			if ($response === false) {
				throw new Exception("Problem reading data from $url, $php_errormsg");
			}
			return $response;
		}
	}

//convert the string to a named array
	if(!function_exists('csv_to_named_array')) {
		function csv_to_named_array($tmp_str, $tmp_delimiter) {
			$tmp_array = explode ("\n", $tmp_str);
			$result = '';
			if (trim(strtoupper($tmp_array[0])) != "+OK") {
				$tmp_field_name_array = explode ($tmp_delimiter, $tmp_array[0]);
				$x = 0;
				foreach ($tmp_array as $row) {
					if ($x > 0) {
						$tmp_field_value_array = explode ($tmp_delimiter, $tmp_array[$x]);
						$y = 0;
						foreach ($tmp_field_value_array as $tmp_value) {
							$tmp_name = $tmp_field_name_array[$y];
							if (trim(strtoupper($tmp_value)) != "+OK") {
								$result[$x][$tmp_name] = $tmp_value;
							}
							$y++;
						}
					}
					$x++;
				}
				unset($row);
			}
			return $result;
		}
	}

?>
