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
include "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
if (permission_exists('fax_extension_add') || permission_exists('fax_extension_edit')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//set the fax directory
	if (count($_SESSION["domains"]) > 1) {
		$v_fax_dir = $v_storage_dir.'/fax/'.$v_domain;
	}
	else {
		$v_fax_dir = $v_storage_dir.'/fax';
	}

//delete a fax
	if ($_GET['a'] == "del" && permission_exists('fax_inbox_delete')) {
		$faxextension = check_str($_GET["faxextension"]);
		if ($_GET['type'] == "fax_inbox") {
			unlink($v_fax_dir.'/'.$faxextension.'/inbox/'.$_GET['filename']);
		}
		if ($_GET['type'] == "fax_sent") {
			unlink($v_fax_dir.'/'.$faxextension.'/sent/'.$_GET['filename']);
		}
	}

//download a fax
	if ($_GET['a'] == "download") {
		session_cache_limiter('public');
		//test to see if it is in the inbox or sent directory.
		if ($_GET['type'] == "fax_inbox") {
			if (file_exists($v_fax_dir.'/'.$_GET['ext'].'/inbox/'.$_GET['filename'])) {
				$tmp_faxdownload_file = "".$v_fax_dir.'/'.$_GET['ext'].'/inbox/'.$_GET['filename'];
			}
		}
		else if ($_GET['type'] == "fax_sent") {
			if  (file_exists($v_fax_dir.'/'.$_GET['ext'].'/sent/'.$_GET['filename'])) {
				$tmp_faxdownload_file = "".$v_fax_dir.'/'.$_GET['ext'].'/sent/'.$_GET['filename'];
			}
		}
		//let's see if we found it.
		if (strlen($tmp_faxdownload_file) > 0) {
			$fd = fopen($tmp_faxdownload_file, "rb");
			if ($_GET['t'] == "bin") {
				header("Content-Type: application/force-download");
				header("Content-Type: application/octet-stream");
				header("Content-Type: application/download");
				header("Content-Description: File Transfer");
				header('Content-Disposition: attachment; filename="'.$_GET['filename'].'"');
			}
			else {
				$file_ext = substr($_GET['filename'], -3);
				if ($file_ext == "tif") {
				  header("Content-Type: image/tiff");
				}
				else if ($file_ext == "png") {
				  header("Content-Type: image/png");
				}
				else if ($file_ext == "jpg") {
				  header('Content-Type: image/jpeg');
				}
				else if ($file_ext == "pdf") {
				  header("Content-Type: application/pdf");
				}
			}
			header('Accept-Ranges: bytes');
			header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // date in the past
			header("Content-Length: " . filesize($tmp_faxdownload_file));
			fpassthru($fd);
		}
		else {
			echo "File not found.";
		}
		exit;
	}

//set the action as an add or an update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$fax_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//get the http post values and set them as php variables
	if (count($_POST)>0) {
		$faxextension = check_str($_POST["faxextension"]);
		$faxname = check_str($_POST["faxname"]);
		$faxemail = check_str($_POST["faxemail"]);
		$fax_pin_number = check_str($_POST["fax_pin_number"]);
		$fax_caller_id_name = check_str($_POST["fax_caller_id_name"]);
		$fax_caller_id_number = check_str($_POST["fax_caller_id_number"]);
		$fax_user_list = check_str($_POST["fax_user_list"])."|";
		$fax_user_list = str_replace("\n", "|", "|".$fax_user_list);
		$fax_user_list = str_replace("\r", "", $fax_user_list);
		$fax_user_list = str_replace("||", "|", $fax_user_list);
		$fax_user_list = trim($fax_user_list);
		$faxdescription = check_str($_POST["faxdescription"]);
	}

//clear file status cache
	clearstatcache(); 

//set the fax directories. example /usr/local/freeswitch/storage/fax/329/inbox
	$dir_fax_inbox = $v_fax_dir.'/'.$faxextension.'/inbox';
	$dir_fax_sent = $v_fax_dir.'/'.$faxextension.'/sent';
	$dir_fax_temp = $v_fax_dir.'/'.$faxextension.'/temp';

//make sure the directories exist
	if (!is_dir($v_storage_dir)) {
		mkdir($v_storage_dir);
		chmod($dir_fax_sent,0777);
	}
	if (!is_dir($v_fax_dir.'/'.$faxextension)) {
		mkdir($v_fax_dir.'/'.$faxextension,0777,true);
		chmod($v_fax_dir.'/'.$faxextension,0777);
	}
	//if (!is_dir($dir_fax_inbox)) { 
	//	mkdir($dir_fax_inbox,0777,true); 
	//	chmod($dir_fax_inbox,0777);
	//}
	//if (!is_dir($dir_fax_sent)) { 
	//	mkdir($dir_fax_sent,0777,true); 
	//	chmod($dir_fax_sent,0777);
	//}
	//if (!is_dir($dir_fax_temp)) {
	//	mkdir($dir_fax_temp);
	//	chmod($dir_fax_temp,0777);
	//}

//upload and send the fax
	if (($_POST['type'] == "fax_send") && is_uploaded_file($_FILES['fax_file']['tmp_name'])) {

		$fax_number = $_POST['fax_number'];
		$fax_name = $_FILES['fax_file']['name'];
		$fax_name = str_replace(".tif", "", $fax_name);
		$fax_name = str_replace(".tiff", "", $fax_name);
		$fax_name = str_replace(".pdf", "", $fax_name);
		$provider_type = $_POST['provider_type'];
		$gateway = $_POST['gateway'];
		$sip_uri = $_POST['sip_uri'];
		$fax_id = $_POST["id"];

		//upload the file
			move_uploaded_file($_FILES['fax_file']['tmp_name'], $dir_fax_temp.'/'.$_FILES['fax_file']['name']);

			$fax_file_extension = substr($dir_fax_temp.'/'.$_FILES['fax_file']['name'], -4);
			if ($fax_file_extension == ".pdf") {
				chdir($dir_fax_temp);
				exec("gs -q -sDEVICE=tiffg3 -r204x98 -dNOPAUSE -sOutputFile=".$fax_name.".tif -- ".$fax_name.".pdf -c quit");
				//exec("rm ".$dir_fax_temp.'/'.$fax_name.".pdf");
			}
			if ($fax_file_extension == ".tiff") {
				chdir($dir_fax_temp);
				exec("cp ".$dir_fax_temp.'/'.$fax_name.".tiff ".$dir_fax_temp.'/'.$fax_name.".tif");
				exec("rm ".$dir_fax_temp.'/'.$fax_name.".tiff");
			}

		//send the fax
			$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
			if ($fp) {
				if ($provider_type == "gateway") {
					$cmd = "api originate sofia/gateway/".$gateway."/".$fax_number." &txfax(".$dir_fax_temp."/".$fax_name.".tif)";
				}
				if ($provider_type == "sip_uri") {
					$sip_uri = str_replace("\$1", $fax_number, $sip_uri);
					$cmd = "api originate $sip_uri &txfax(".$dir_fax_temp."/".$fax_name.".tif)";
				}
				$response = event_socket_request($fp, $cmd);
				$response = str_replace("\n", "", $response);
				$uuid = str_replace("+OK ", "", $response);
				fclose($fp);
			}

		sleep(5);

		//copy the .tif to the sent directory
			exec("cp ".$dir_fax_temp.'/'.$fax_name.".tif ".$dir_fax_sent.'/'.$fax_name.".tif");

		//convert the tif to pdf
			chdir($dir_fax_sent);
			exec("gs -q -sDEVICE=tiffg3 -r204x98 -dNOPAUSE -sOutputFile=".$fax_name.".pdf -- ".$fax_name.".tif -c quit");

		//delete the .tif from the temp directory
			//exec("rm ".$dir_fax_temp.'/'.$fax_name.".tif");
		
		//convert the tif to pdf and png
			chdir($dir_fax_sent);
			//which tiff2pdf
			if (is_file("/usr/local/bin/tiff2png")) {
				exec("".bin_dir."/tiff2png ".$dir_fax_sent.$fax_name.".tif");
				exec("".bin_dir."/tiff2pdf -f -o ".$fax_name.".pdf ".$dir_fax_sent.$fax_name.".tif");
			}

		header("Location: v_fax_edit.php?id=".$fax_id."&msg=".$response);
		exit;
	} //end upload and send fax

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update" && permission_exists('fax_extension_edit')) {
		$fax_id = check_str($_POST["fax_id"]);
	}

	//check for all required data
		if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		if (strlen($faxextension) == 0) { $msg .= "Please provide: Extension<br>\n"; }
		if (strlen($faxname) == 0) { $msg .= "Please provide: A file to Fax<br>\n"; }
		//if (strlen($faxemail) == 0) { $msg .= "Please provide: Email<br>\n"; }
		//if (strlen($fax_pin_number) == 0) { $msg .= "Please provide: Pin Number<br>\n"; }
		//if (strlen($fax_caller_id_name) == 0) { $msg .= "Please provide: Caller ID Name<br>\n"; }
		//if (strlen($fax_caller_id_number) == 0) { $msg .= "Please provide: Caller ID Number<br>\n"; }
		//if (strlen($fax_user_list) == 0) { $msg .= "Please provide: Assigned Users<br>\n"; }
		//if (strlen($faxdescription) == 0) { $msg .= "Please provide: Description<br>\n"; }
		if (strlen($msg) > 0 && strlen($_POST["persistformvar"]) == 0) {
			require_once "includes/header.php";
			require_once "includes/persistformvar.php";
			echo "<div align='center'>\n";
			echo "<table><tr><td>\n";
			echo $msg."<br />";
			echo "</td></tr></table>\n";
			persistformvar($_POST);
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
		}

	//add or update the database
		if ($_POST["persistformvar"] != "true") {
			if ($action == "add" && permission_exists('fax_extension_add')) {
				$sql = "insert into v_fax ";
				$sql .= "(";
				$sql .= "v_id, ";
				$sql .= "faxextension, ";
				$sql .= "faxname, ";
				$sql .= "faxemail, ";
				$sql .= "fax_pin_number, ";
				$sql .= "fax_caller_id_name, ";
				$sql .= "fax_caller_id_number, ";
				$sql .= "fax_user_list, ";
				$sql .= "faxdescription ";
				$sql .= ")";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'$v_id', ";
				$sql .= "'$faxextension', ";
				$sql .= "'$faxname', ";
				$sql .= "'$faxemail', ";
				$sql .= "'$fax_pin_number', ";
				$sql .= "'$fax_caller_id_name', ";
				$sql .= "'$fax_caller_id_number', ";
				$sql .= "'$fax_user_list', ";
				$sql .= "'$faxdescription' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql);

				sync_package_v_fax();

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_fax.php\">\n";
				echo "<div align='center'>\n";
				echo "Add Complete\n";
				echo "</div>\n";
				require_once "includes/footer.php";
				return;
			} //if ($action == "add")

			if ($action == "update" && permission_exists('fax_extension_edit')) {
				$sql = "update v_fax set ";
				$sql .= "v_id = '$v_id', ";
				$sql .= "faxextension = '$faxextension', ";
				$sql .= "faxname = '$faxname', ";
				$sql .= "faxemail = '$faxemail', ";
				$sql .= "fax_pin_number = '$fax_pin_number', ";
				$sql .= "fax_caller_id_name = '$fax_caller_id_name', ";
				$sql .= "fax_caller_id_number = '$fax_caller_id_number', ";
				//$sql .= "fax_user_list = '$fax_user_list', ";
				$sql .= "faxdescription = '$faxdescription' ";
				$sql .= "where v_id = '$v_id' ";
				$sql .= "and fax_id = '$fax_id' ";
				if (!ifgroup("admin") || !ifgroup("superadmin")) {
					$sql .= "and fax_user_list like '%|".$_SESSION["username"]."|%' ";
				}
				$db->exec(check_sql($sql));
				unset($sql);

				sync_package_v_fax();

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_fax.php\">\n";
				echo "<div align='center'>\n";
				echo "Update Complete\n";
				echo "</div>\n";
				require_once "includes/footer.php";
				return;
			} //if ($action == "update")
		} //if ($_POST["persistformvar"] != "true")
} //(count($_POST)>0 && strlen($_POST["persistformvar"]) == 0)

//pre-populate the form
	if (count($_GET)>0 && $_POST["persistformvar"] != "true") {
		$fax_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_fax ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and fax_id = '$fax_id' ";
		if (ifgroup("superadmin")) {
			//show all fax extensions
		}
		else if (ifgroup("admin")) {
			//show all fax extensions
		}
		else {
			//show only assigned fax extensions
			$sql .= "and fax_user_list like '%|".$_SESSION["username"]."|%' ";
		}
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		if (count($result) == 0) {
			echo "access denied";
			exit;
		}
		foreach ($result as &$row) {
			//set database fields as variables
				$v_id = $row["v_id"];
				$faxextension = $row["faxextension"];
				$faxname = $row["faxname"];
				$faxemail = $row["faxemail"];
				$fax_pin_number = $row["fax_pin_number"];
				$fax_caller_id_name = $row["fax_caller_id_name"];
				$fax_caller_id_number = $row["fax_caller_id_number"];
				$fax_user_list = $row["fax_user_list"];
				$faxdescription = $row["faxdescription"];

			//set the fax directories. example /usr/local/freeswitch/storage/fax/329/inbox
				$dir_fax_inbox = $v_fax_dir.'/'.$faxextension.'/inbox';
				$dir_fax_sent = $v_fax_dir.'/'.$faxextension.'/sent';

			//make sure the directories exist
				if (!is_dir($v_fax_dir.'/'.$faxextension)) {
					mkdir($v_fax_dir.'/'.$faxextension,0777,true);
					chmod($v_fax_dir.'/'.$faxextension,0777);
				}
				if (!is_dir($dir_fax_inbox)) { 
					mkdir($dir_fax_inbox,0777,true); 
					chmod($dir_fax_inbox,0777);
				}
				if (!is_dir($dir_fax_sent)) { 
					mkdir($dir_fax_sent,0777,true); 
					chmod($dir_fax_sent,0777);
				}

			break; //limit to 1 row
		}
		
		unset ($prepstatement);
	}

//delete the fax
	if ($_GET['a'] == "del") {
		$faxextension = check_str($_GET["faxextension"]);
		if ($_GET['type'] == "fax_inbox" && permission_exists('fax_inbox_delete')) {
			unlink($v_fax_dir.'/'.$faxextension.'/inbox/'.$_GET['filename']);
		}
		if ($_GET['type'] == "fax_sent" && permission_exists('fax_sent_delete')) {
			unlink($v_fax_dir.'/'.$faxextension.'/sent/'.$_GET['filename']);
		}
	}

//download the fax
	if ($_GET['a'] == "download") {
		session_cache_limiter('public');
		//test to see if it is in the inbox or sent directory.
			if ($_GET['type'] == "fax_inbox" && permission_exists('fax_inbox_view')) {
				if (file_exists($v_fax_dir.'/'.$_GET['ext'].'/inbox/'.$_GET['filename'])) {
					$tmp_faxdownload_file = "".$v_fax_dir.'/'.$_GET['ext'].'/inbox/'.$_GET['filename'];
				}
			}else if ($_GET['type'] == "fax_sent" && permission_exists('fax_sent_view')) {
				if  (file_exists($v_fax_dir.'/'.$_GET['ext'].'/sent/'.$_GET['filename'])) {
					$tmp_faxdownload_file = "".$v_fax_dir.'/'.$_GET['ext'].'/sent/'.$_GET['filename'];
				}
			}
		//check to see if it was found.
			if (strlen($tmp_faxdownload_file) > 0) {
				$fd = fopen($tmp_faxdownload_file, "rb");
				if ($_GET['t'] == "bin") {
					header("Content-Type: application/force-download");
					header("Content-Type: application/octet-stream");
					header("Content-Type: application/download");
					header("Content-Description: File Transfer");
					header('Content-Disposition: attachment; filename="'.$_GET['filename'].'"');
				}
				else {
					$file_ext = substr($_GET['filename'], -3);
					if ($file_ext == "tif") {
					  header("Content-Type: image/tiff");
					} else if ($file_ext == "png") {
					  header("Content-Type: image/png");
					} else if ($file_ext == "jpg") {
					  header('Content-Type: image/jpeg');
					} else if ($file_ext == "pdf") {
					  header("Content-Type: application/pdf");
					}
				}
				header('Accept-Ranges: bytes');
				header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
				header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
				header("Content-Length: " . filesize($tmp_faxdownload_file));
				fpassthru($fd);
			}
			else {
				echo "File not found.";
			}
		//exit the code execution
			exit;
	}

//show the header
	require_once "includes/header.php";

//fax extension form
	echo "<div align='center'>";
	echo "<table border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "		<br>";

	echo "<form method='post' name='frm' action=''>\n";

	echo "<div align='center'>\n";
	echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";

	echo "<tr>\n";
	if ($action == "add") {
		echo "<td align='left' width='30%' nowrap><b>Fax Add</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align='left' width='30%' nowrap><b>Fax Edit</b></td>\n";
	}
	echo "<td width='70%' align='right'><input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_fax.php'\" value='Back'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Extension:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='faxextension' maxlength='255' value=\"$faxextension\">\n";
	echo "<br />\n";
	echo "Enter the fax extension here.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='faxname' maxlength='255' value=\"$faxname\">\n";
	echo "<br />\n";
	echo "Enter the name here.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Email:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='faxemail' maxlength='255' value=\"$faxemail\">\n";
	echo "<br />\n";
	echo "	Enter the email address to send the FAX to.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	PIN Number:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='fax_pin_number' maxlength='255' value=\"$fax_pin_number\">\n";
	echo "<br />\n";
	echo "Enter the PIN number here.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Caller ID Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='fax_caller_id_name' maxlength='255' value=\"$fax_caller_id_name\">\n";
	echo "<br />\n";
	echo "Enter the Caller ID name here.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Caller ID Number:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='fax_caller_id_number' maxlength='255' value=\"$fax_caller_id_number\">\n";
	echo "<br />\n";
	echo "Enter the Caller ID number here.\n";
	echo "</td>\n";
	echo "</tr>\n";

	if (ifgroup("admin") || ifgroup("superadmin")) {
		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap>\n";
		echo "		User List:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		$onchange = "document.getElementById('fax_user_list').value += document.getElementById('username').value + '\\n';";
		$tablename = 'v_users'; $fieldname = 'username'; $fieldcurrentvalue = ''; $sqlwhereoptional = "where v_id = '$v_id'"; 
		echo htmlselectonchange($db, $tablename, $fieldname, $sqlwhereoptional, $fieldcurrentvalue, $onchange);
		echo "<br />\n";
		echo "Use the select list to add users to the user list. This will assign users to this extension.\n";
		echo "<br />\n";
		echo "<br />\n";
		$fax_user_list = str_replace("|", "\n", $fax_user_list);
		echo "		<textarea name=\"fax_user_list\" id=\"fax_user_list\" class=\"formfld\" cols=\"30\" rows=\"3\" wrap=\"off\">$fax_user_list</textarea>\n";
		echo "		<br>\n";
		echo "Assign the users that are can manage this fax extension.\n";
		echo "<br />\n";
		echo "</td>\n";
		echo "</tr>\n";
	}

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Description:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='faxdescription' maxlength='255' value=\"$faxdescription\">\n";
	echo "<br />\n";
	echo "Enter the description here.\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	if ($action == "update") {
		echo "			<input type='hidden' name='fax_id' value='$fax_id'>\n";
	}
	echo "			<input type='submit' name='submit' class='btn' value='Save'>\n";
	echo "		</td>\n";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";

	echo "<br />\n";
	echo "<br />\n";

	echo "<form action=\"\" method=\"POST\" enctype=\"multipart/form-data\" name=\"frmUpload\" onSubmit=\"\">\n";
	echo "<div align='center'>\n";
	echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
	echo "	<tr>\n";
	echo "		<td align='left' width='30%'>\n";
	echo "			<span class=\"vexpl\"><span class=\"red\"><strong>Send</strong></span>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan='2' align='left'>\n";
	//pkg_add -r ghostscript8-nox11; rehash
	echo "			To send a fax you can upload a .tif file or if ghost script has been installed then you can also send a fax by uploading a PDF. \n";
	echo "			When sending a fax you can view status of the transmission by viewing the logs from the Status tab or by watching the response from the console.\n";
	echo "			<br />\n";
	echo "		</td>\n";
	echo "	</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "		Fax Number:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "		<input type=\"text\" name=\"fax_number\" class='formfld' style='' value=\"\">\n";
	echo "<br />\n";
	echo "Enter the Number here.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "	Upload:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input name=\"id\" type=\"hidden\" value=\"\$id\">\n";
	echo "	<input name=\"type\" type=\"hidden\" value=\"fax_send\">\n";
	echo "	<input name=\"fax_file\" type=\"file\" class=\"btn\" id=\"fax_file\">\n";
	echo "	<br />\n";
	echo "	Select the file to upload and send as a fax.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<script type=\"text/javascript\">\n";
	echo "	function check_provider_type(sRef) {\n";
	echo "		var val = sRef.value;\n";
	echo "		if (val == 'gateway') {\n";
	echo "			document.getElementById('gateway').style.display = 'inline';\n";
	echo "			document.getElementById('sip_uri').style.display = 'none';\n";
	echo "		}\n";
	echo "		if (val == 'sip_uri') {\n";
	echo "			document.getElementById('gateway').style.display = 'none';\n";
	echo "			document.getElementById('sip_uri').style.display = 'inline';\n";
	echo "		}\n";
	echo "	}\n";
	echo "</script>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Provider:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";

	echo "<table border='0' width='100%'>\n";
	echo "<tr>\n";
	echo "<td style='width:105px' nowrap>\n";
	echo "	<select onchange=\"check_provider_type(this)\" name=\"provider_type\" class=\"formfld\" style='width:105px'>\n";
	echo "	<option selected=\"true\" value='gateway'>Gateway</option>\n";
	echo "	<option value='sip_uri'>SIP URI</option>\n";
	echo "	</select>\n";
	echo "</td>\n";
	echo "<td width='left' width='40%' align='left' nowrap>\n";
	echo "	<span id='gateway' style='display: inline;'>\n";
	$tablename = 'v_gateways'; $fieldname = 'gateway'; $sqlwhereoptional = "where v_id = '$v_id'"; $fieldcurrentvalue = '$gateway'; $fieldstyle = '';
	echo 	htmlselect($db, $tablename, $fieldname, $sqlwhereoptional, $fieldcurrentvalue, "", $fieldstyle);
	echo "	</span>\n";
	echo "	<span id='sip_uri' style='display: none;'>\n";
	echo "		<input type=\"text\" name=\"sip_uri\" class='formfld' style='' value=\"$sip_uri\">\n";
	echo "	</span>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "	Select the gateway or use a SIP URI.\n";
	echo "	</td>\n";
	echo "</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	echo "			<input type=\"hidden\" name=\"faxextension\" value=\"".$faxextension."\">\n";
	echo "			<input type=\"hidden\" name=\"id\" value=\"".$fax_id."\">\n";
	echo "			<input name=\"submit\" type=\"submit\" class=\"btn\" id=\"upload\" value=\"Send\">\n";
	echo "		</td>\n";
	echo "	</tr>";
	echo "</table>";
	echo "</div>\n";
	echo "</form>\n";

//show the inbox
	if (permission_exists('fax_inbox_view')) {
		echo "\n";
		echo "\n";
		echo "\n";
		echo "	<br />\n";
		echo "	<br />\n";
		echo "	<br />\n";
		echo "	<br />\n";
		echo "\n";
		echo "	<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";
		echo "	<tr>\n";
		echo "		<td align='left'>\n";
		echo "			<span class=\"vexpl\"><span class=\"red\"><strong>Inbox</strong></span>\n";
		echo "		</td>\n";
		echo "		<td align='right'>";
		if ($v_path_show) {
			echo "<b>location:</b>&nbsp;";
			echo $dir_fax_inbox."&nbsp; &nbsp; &nbsp;";
		}
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "    </table>\n";
		echo "\n";

		$c = 0;
		$rowstyle["0"] = "rowstyle0";
		$rowstyle["1"] = "rowstyle1";

		echo "	<div id=\"\">\n";
		echo "	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "	<tr>\n";
		echo "		<th width=\"60%\" class=\"listhdrr\">File Name (download)</td>\n";
		echo "		<th width=\"10%\" class=\"listhdrr\">View</td>\n";
		echo "		<th width=\"20%\" class=\"listhdr\">Last Modified</td>\n";
		echo "		<th width=\"10%\" class=\"listhdr\" nowrap>Size</td>\n";
		echo "	</tr>";

		if ($handle = opendir($dir_fax_inbox)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && is_file($dir_fax_inbox.'/'.$file)) {
					$tmp_filesize = filesize($dir_fax_inbox.'/'.$file);
					$tmp_filesize = byte_convert($tmp_filesize);
					$tmp_file_array = explode(".",$file);
					$file_name = $tmp_file_array[0];
					$file_ext = $tmp_file_array[count($tmp_file_array)-1];
					if (strtolower($file_ext) == "tif") {
						if (!file_exists($dir_fax_inbox.'/'.$file_name.".pdf")) {
							//convert the tif to pdf
								chdir($dir_fax_inbox);
								if (is_file("/usr/local/bin/tiff2pdf")) {
									exec("/usr/local/bin/tiff2pdf -f -o ".$file_name.".pdf ".$dir_fax_inbox.'/'.$file_name.".tif");
								}
								if (is_file("/usr/bin/tiff2pdf")) {
									exec("/usr/bin/tiff2pdf -f -o ".$file_name.".pdf ".$dir_fax_inbox.'/'.$file_name.".tif");
								}
						}
						//if (!file_exists($dir_fax_inbox.'/'.$file_name.".jpg")) {
						//	//convert the tif to jpg
						//		chdir($dir_fax_inbox);
						//		if (is_file("/usr/local/bin/tiff2rgba")) {
						//			exec("/usr/local/bin/tiff2rgba ".$file_name.".tif ".$dir_fax_inbox.'/'.$file_name.".jpg");
						//		}
						//		if (is_file("/usr/bin/tiff2rgba")) {
						//			exec("/usr/bin/tiff2rgba ".$file_name.".tif ".$dir_fax_inbox.'/'.$file_name.".jpg");
						//		}
						//}
						echo "<tr>\n";
						echo "  <td class='".$rowstyle[$c]."' ondblclick=\"\">\n";
						echo "	  <a href=\"v_fax_edit.php?id=".$fax_id."&a=download&type=fax_inbox&t=bin&ext=".urlencode($faxextension)."&filename=".urlencode($file)."\">\n";
						echo "    	$file";
						echo "	  </a>";
						echo "  </td>\n";

						echo "  <td class='".$rowstyle[$c]."' ondblclick=\"\">\n";
						if (file_exists($dir_fax_inbox.'/'.$file_name.".pdf")) {
							echo "	  <a href=\"v_fax_edit.php?id=".$fax_id."&a=download&type=fax_inbox&t=bin&ext=".urlencode($faxextension)."&filename=".urlencode($file_name).".pdf\">\n";
							echo "    	PDF";
							echo "	  </a>";
						}
						else {
							echo "&nbsp;\n";
						}
						echo "  </td>\n";

						//echo "  <td class='".$rowstyle[$c]."' ondblclick=\"\">\n";
						//if (file_exists($dir_fax_inbox.'/'.$file_name.".jpg")) {
						//	echo "	  <a href=\"v_fax_edit.php?id=".$fax_id."&a=download&type=fax_inbox&t=jpg&ext=".$faxextension."&filename=".$file_name.".jpg\" target=\"_blank\">\n";
						//	echo "    	jpg";
						//	echo "	  </a>";
						//}
						//else {
						//	echo "&nbsp;\n";
						//}
						//echo "  &nbsp;</td>\n";

						echo "  <td class='".$rowstyle[$c]."' ondblclick=\"\">\n";
						echo 		date ("F d Y H:i:s", filemtime($dir_fax_inbox.'/'.$file));
						echo "  </td>\n";

						echo "  <td class='".$rowstyle[$c]."' ondblclick=\"\">\n";
						echo "	".$tmp_filesize;
						echo "  </td>\n";

						echo "  <td valign=\"middle\" nowrap class=\"list\">\n";
						echo "    <table border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n";
						echo "      <tr>\n";
						if (permission_exists('fax_inbox_delete')) {
							echo "        <td><a href=\"v_fax_edit.php?id=".$fax_id."&type=fax_inbox&a=del&faxextension=".urlencode($faxextension)."&filename=".urlencode($file)."\" onclick=\"return confirm('Do you really want to delete this file?')\"><img src=\"$v_icon_delete\" width=\"17\" height=\"17\" border=\"0\"></a></td>\n";
						}
						echo "      </tr>\n";
						echo "   </table>\n";
						echo "  </td>\n";
						echo "</tr>\n";
					}
				}
			}
			closedir($handle);
		}
		echo "	<tr>\n";
		echo "		<td class=\"list\" colspan=\"3\"></td>\n";
		echo "		<td class=\"list\"></td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		echo "\n";
		echo "	<br />\n";
		echo "	<br />\n";
		echo "	<br />\n";
		echo "	<br />\n";
		echo "\n";
	}

//show the sent box
	if (permission_exists('fax_sent_view')) {
		echo "  <table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";
		echo "	<tr>\n";
		echo "		<td align='left'>\n";
		echo "			<span class=\"vexpl\"><span class=\"red\"><strong>Sent</strong></span>\n";
		echo "		</td>\n";
		echo "		<td align='right'>\n";
		if ($v_path_show) {
			echo "<b>location:</b>\n";
			echo $dir_fax_sent."&nbsp; &nbsp; &nbsp;\n";
		}
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "    </table>\n";
		echo "\n";
		echo "    <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "    <tr>\n";
		echo "		<th width=\"60%\">File Name (download)</td>\n";
		echo "		<th width=\"10%\">View</td>\n";
		echo "		<th width=\"20%\">Last Modified</td>\n";
		echo "		<th width=\"10%\" nowrap>Size</td>\n";
		echo "		</tr>";

		if ($handle = opendir($dir_fax_sent)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && is_file($dir_fax_sent.'/'.$file)) {
					$tmp_filesize = filesize($dir_fax_sent.'/'.$file);
					$tmp_filesize = byte_convert($tmp_filesize);
					$tmp_file_array = explode(".",$file);
					$file_name = $tmp_file_array[0];
					$file_ext = $tmp_file_array[count($tmp_file_array)-1];
					if (strtolower($file_ext) == "tif") {
						if (!file_exists($dir_fax_sent.'/'.$file_name.".pdf")) {
							//convert the tif to pdf
								chdir($dir_fax_sent);
								if (is_file("/usr/local/bin/tiff2pdf")) {
									exec("/usr/local/bin/tiff2pdf -f -o ".$file_name.".pdf ".$dir_fax_sent.'/'.$file_name.".tif");
								}
								if (is_file("/usr/bin/tiff2pdf")) {
									exec("/usr/bin/tiff2pdf -f -o ".$file_name.".pdf ".$dir_fax_sent.'/'.$file_name.".tif");
								}
						}
						if (!file_exists($dir_fax_sent.'/'.$file_name.".jpg")) {
							//convert the tif to jpg
								//chdir($dir_fax_sent);
								//if (is_file("/usr/local/bin/tiff2rgba")) {
								//	exec("/usr/local/bin/tiff2rgba -c jpeg -n ".$file_name.".tif ".$dir_fax_sent.'/'.$file_name.".jpg");
								//}
								//if (is_file("/usr/bin/tiff2rgba")) {
								//	exec("/usr/bin/tiff2rgba -c lzw -n ".$file_name.".tif ".$dir_fax_sent.'/'.$file_name.".jpg");
								//}
						}
						echo "<tr>\n";
						echo "  <td class='".$rowstyle[$c]."' ondblclick=\"\">\n";
						echo "	  <a href=\"v_fax_edit.php?id=".$fax_id."&a=download&type=fax_sent&t=bin&ext=".urlencode($faxextension)."&filename=".urlencode($file)."\">\n";
						echo "    	$file";
						echo "	  </a>";
						echo "  </td>\n";
						echo "  <td class='".$rowstyle[$c]."' ondblclick=\"\">\n";
						if (file_exists($dir_fax_sent.'/'.$file_name.".pdf")) {
							echo "	  <a href=\"v_fax_edit.php?id=".$fax_id."&a=download&type=fax_sent&t=bin&ext=".urlencode($faxextension)."&filename=".urlencode($file_name).".pdf\">\n";
							echo "    	PDF";
							echo "	  </a>";
						}
						else {
							echo "&nbsp;\n";
						}
						echo "  </td>\n";
						//echo "  <td class='".$rowstyle[$c]."' ondblclick=\"\">\n";
						//if (file_exists($dir_fax_sent.'/'.$file_name.".jpg")) {
						//	echo "	  <a href=\"v_fax_edit.php?id=".$fax_id."&a=download&type=fax_sent&t=jpg&ext=".$faxextension."&filename=".$file_name.".jpg\" target=\"_blank\">\n";
						//	echo "    	jpg";
						//	echo "	  </a>";
						//}
						//else {
						//	echo "&nbsp;\n";
						//}
						//echo "  </td>\n";
						echo "  <td class='".$rowstyle[$c]."' ondblclick=\"\">\n";
						echo 		date ("F d Y H:i:s", filemtime($dir_fax_sent.'/'.$file));
						echo "  </td>\n";

						echo "  <td class=\"".$rowstyle[$c]."\" ondblclick=\"list\">\n";
						echo "	".$tmp_filesize;
						echo "  </td>\n";

						echo "  <td class='' valign=\"middle\" nowrap>\n";
						echo "    <table border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n";
						echo "      <tr>\n";
						if (permission_exists('fax_sent_delete')) {
							echo "        <td><a href=\"v_fax_edit.php?id=".$fax_id."&type=fax_sent&a=del&faxextension=".urlencode($faxextension)."&filename=".urlencode($file)."\" onclick=\"return confirm('Do you really want to delete this file?')\"><img src=\"$v_icon_delete\" width=\"17\" height=\"17\" border=\"0\"></a></td>\n";
						}
						echo "      </tr>\n";
						echo "   </table>\n";
						echo "  </td>\n";
						echo "</tr>\n";
						if ($c==0) { $c=1; } else { $c=0; }
					} //check if the file is a .tif file
				}
			} //end while
			closedir($handle);
		}
		echo "     <tr>\n";
		echo "       <td class=\"list\" colspan=\"3\"></td>\n";
		echo "       <td class=\"list\"></td>\n";
		echo "     </tr>\n";
		echo "     </table>\n";
		echo "\n";
		echo "\n";
		echo "	<br />\n";
		echo "	<br />\n";
		echo "	<br />\n";
		echo "	<br />\n";
	}
	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

//show the footer
	require_once "includes/footer.php";
?>
