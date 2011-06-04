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
require "includes/config.php";
require_once "includes/checkauth.php";
if (permission_exists('voicemail_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//download the voicemail
	if ($_GET['a'] == "download") {

		//pdo voicemail database connection
			include "includes/lib_pdo_vm.php";

		session_cache_limiter('public');

		$uuid = $_GET["uuid"];
		$sql = "";
		$sql .= "select * from voicemail_msgs ";
		$sql .= "where domain = '$v_domain' ";
		$sql .= "and uuid = '$uuid' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$created_epoch = $row["created_epoch"];
			$read_epoch = $row["read_epoch"];
			$username = $row["username"];
			$uuid = $row["uuid"];
			$cid_name = $row["cid_name"];
			$cid_number = $row["cid_number"];
			$in_folder = $row["in_folder"];
			$file_path = $row["file_path"];
			$message_len = $row["message_len"];
			$flags = $row["flags"];
			$read_flags = $row["read_flags"];
			break; //limit to 1 row
		}
		unset ($prepstatement);

		if ($_GET['type'] = "vm") {
			if  (file_exists($file_path)) {
				$fd = fopen($file_path, "rb");
				if ($_GET['t'] == "bin") {
					header("Content-Type: application/force-download");
					header("Content-Type: application/octet-stream");
					header("Content-Type: application/download");
					header("Content-Description: File Transfer");
					$file_ext = substr($file_path, -3);
					if ($file_ext == "wav") {
						header('Content-Disposition: attachment; filename="voicemail.wav"');
					}
					if ($file_ext == "mp3") {
						header('Content-Disposition: attachment; filename="voicemail.mp3"');
					}
				}
				else {
					$file_ext = substr($file_path, -3);
					if ($file_ext == "wav") {
						header("Content-Type: audio/x-wav");
					}
					if ($file_ext == "mp3") {
						header("Content-Type: audio/mp3");
					}
				}
				header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
				header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // date in the past
				header("Content-Length: " . filesize($file_path));
				fpassthru($fd);
			}
			return;
		}
	}

//get the includes
	require "includes/config.php";
	require_once "includes/header.php";
	require_once "includes/paging.php";

//get the http get values
	$orderby = $_GET["orderby"];
	$order = $_GET["order"];

//get a list of assigned extensions for this user
	$sql = "";
	$sql .= "select * from v_extensions ";
	$sql .= "where v_id = '$v_id' ";
	//superadmin can see all messages
	if(!ifgroup("superadmin")) {
	$sql .= "and user_list like '%|".$_SESSION["username"]."|%' ";
	}
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	//$v_mailboxes = '';
	$x = 0;
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		//$v_mailboxes = $v_mailboxes.$row["extension"].'|';
		//$extension_id = $row["extension_id"]
		//$extension = $row["extension"]
		$mailbox_array[$x]['extension_id'] = $row["extension_id"];
		$mailbox_array[$x]['extension'] = $row["extension"];
		$x++;
	}
	unset ($prepstatement, $x);
	//$user_list = str_replace("\n", "|", "|".$user_list);
	//echo "v_mailboxes $v_mailboxes<br />";
	//$mailbox_array = explode ("|", $v_mailboxes);
	//echo "<pre>\n";
	//print_r($mailbox_array);
	//echo "</pre>\n";

//pdo voicemail database connection
	include "includes/lib_pdo_vm.php";

//show the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";
	echo "<tr class='border'>\n";
	echo "	<td align=\"center\">\n";
	echo "		<br>";

	echo "<table width='100%' border='0'>\n";
	echo "<tr>\n";
	echo "<td align='left' width='50%' nowrap><b>Voicemail Messages</b></td>\n";
	echo "<td align='left' width='50%' align='right'>&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan='2' align='left'>\n";
	echo "Voicemails are listed, played, downloaded and deleted from this page. \n";
	if (ifgroup("admin") || ifgroup("superadmin")) {
		echo "Voicemails for an extension are shown to the user(s) that have been assigned to an extension.\n";
		echo "User accounts are created in the 'User Manager' and then are assigned on the 'Extensions' page. \n";
	}
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	$tmp_msg_header = '';
	$tmp_msg_header .= "<tr>\n";
	$tmp_msg_header .= thorderby('created_epoch', 'Created', $orderby, $order);
	//$tmp_msg_header .= thorderby('read_epoch', 'Read', $orderby, $order);
	//$tmp_msg_header .= thorderby('username', 'Ext', $orderby, $order);
	//$tmp_msg_header .= thorderby('domain', 'Domain', $orderby, $order);
	//$tmp_msg_header .= thorderby('uuid', 'UUID', $orderby, $order);
	$tmp_msg_header .= thorderby('cid_name', 'Caller ID Name', $orderby, $order);
	$tmp_msg_header .= thorderby('cid_number', 'Caller ID Number', $orderby, $order);
	$tmp_msg_header .= thorderby('in_folder', 'Folder', $orderby, $order);
	//$tmp_msg_header .= "<th>Options</th>\n";
	//$tmp_msg_header .= thorderby('file_path', 'File Path', $orderby, $order);
	$tmp_msg_header .= thorderby('message_len', 'Length (play)', $orderby, $order);
	$tmp_msg_header .= "<th nowrap>Size (download)</th>\n";
	//$tmp_msg_header .= thorderby('flags', 'Flags', $orderby, $order);
	//$tmp_msg_header .= thorderby('read_flags', 'Read Flags', $orderby, $order);
	$tmp_msg_header .= "<td align='right' width='22'>\n";
	//$tmp_msg_header .= "  <input type='button' class='btn' name='' alt='add' onclick=\"window.location='voicemail_msgs_edit.php'\" value='+'>\n";
	$tmp_msg_header .= "</td>\n";
	$tmp_msg_header .= "<tr>\n";

	echo "<div align='center'>\n";
	echo "<table width='100%' border='0' cellpadding='2' cellspacing='0'>\n";

	if (count($mailbox_array) == 0) {
		echo $tmp_msg_header;
	}
	else {
		foreach($mailbox_array as $value) {
			if (strlen($value['extension']) > 0) {
				echo "<tr><td colspan='5' align='left'>\n";
				echo "	<br />\n";
				echo "	<b>Mailbox: ".$value['extension']."</b>&nbsp;\n";
				echo "	\n";
				echo "</td>\n";
				echo "<td valign='bottom' align='right'>\n";
				echo "	<input type='button' class='btn' name='' alt='add' onclick=\"window.location='v_voicemail_msgs_password.php?id=".$value['extension_id']."'\" value='settings'>\n";
				echo "</td>\n";
				echo "</tr>\n";

				echo $tmp_msg_header;

				$sql = "";
				$sql .= "select * from voicemail_msgs ";
				$sql .= "where domain = '$v_domain' ";
				$sql .= "and username = '".$value['extension']."' ";
				if (strlen($orderby)> 0) { $sql .= "order by $orderby $order "; }
				$prepstatement = $db->prepare(check_sql($sql));
				$prepstatement->execute();
				$result = $prepstatement->fetchAll();
				$resultcount = count($result);
				unset ($prepstatement, $sql);

				$c = 0;
				$rowstyle["0"] = "rowstyle0";
				$rowstyle["1"] = "rowstyle1";

				if ($resultcount == 0) { //no results
				}
				else { //received results
					$prevextension = '';
					foreach($result as $row) {

						$extension_id = '';
						foreach($mailbox_array as $value) {
							if ($value['extension'] == $row[username]) {
								$extension_id = $value['extension_id'];
								break;
							}
							$x++;
						}

						$tmp_filesize = filesize($row[file_path]);
						$tmp_filesize = byte_convert($tmp_filesize);
						$file_ext = substr($row[file_path], -3);

						$tmp_message_len = $row[message_len];
						if ($tmp_message_len < 60 ) {
							$tmp_message_len = $tmp_message_len. " sec";
						}
						else {
							$tmp_message_len = round(($tmp_message_len/60), 2). " min";
						}

						echo "<tr >\n";
						echo "   <td valign='top' class='".$rowstyle[$c]."' nowrap>";
						echo date("j M Y g:i a",$row[created_epoch]);
						echo "</td>\n";
						//echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row[read_epoch]."</td>\n";
						//echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row[username]."</td>\n";
						//echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row[domain]."</td>\n";
						//echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row[uuid]."</td>\n";
						echo "   <td valign='top' class='".$rowstyle[$c]."' nowrap>".$row[cid_name]."</td>\n";
						echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row[cid_number]."</td>\n";
						echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row[in_folder]."</td>\n";
						echo "	<td valign='top' class='".$rowstyle[$c]."'>\n";
						echo "		<a href=\"javascript:void(0);\" onclick=\"window.open('v_voicemail_msgs_play.php?a=download&type=vm&uuid=".$row[uuid]."&ext=".$file_ext."&desc=".urlencode($row[cid_name]." ".$row[cid_number])."', 'play',' width=420,height=40,menubar=no,status=no,toolbar=no')\">\n";
						echo "		$tmp_message_len";
						echo "		</a>";
						echo "	</td>\n";
						//echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row[flags]."&nbsp;</td>\n";
						//echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row[read_flags]."</td>\n";
						echo "	<td valign='top' class='".$rowstyle[$c]."' nowrap>";
						echo "		<a href=\"v_voicemail_msgs.php?a=download&type=vm&t=bin&uuid=".$row[uuid]."\">\n";
						echo $tmp_filesize;
						echo "		</a>";
						echo 	"</td>\n";
						echo "   <td valign='top' align='center' nowrap>\n";
						//echo "		<a href='v_voicemail_msgs_edit.php?id=".$row[voicemail_msg_id]."' alt='edit'>$v_link_label_edit</a>\n";
						echo "		&nbsp;&nbsp;<a href='v_voicemail_msgs_delete.php?uuid=".$row[uuid]."' alt='delete message' title='delete message' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
						echo "   </td>\n";
						echo "</tr>\n";

						$prevextension = $row[username];
						unset($tmp_message_len, $tmp_filesize);
						if ($c==0) { $c=1; } else { $c=0; }
					} //end foreach
					unset($sql, $result, $rowcount);
				} //end if results
			}
		}
	}

	echo "</table>";
	echo "</div>";
	echo "<br><br>";
	echo "<br><br>";

	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</div>";
	echo "<br><br>";

//show the footer
	require "includes/config.php";
	require_once "includes/footer.php";
	unset ($resultcount);
	unset ($result);
	unset ($key);
	unset ($val);
	unset ($c);
	?>
