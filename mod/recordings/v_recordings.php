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
if (permission_exists('recordings_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}
require_once "includes/paging.php";

//set the max php execution time
	ini_set(max_execution_time,7200);

//get the http get values and set them as php variables
	$orderby = $_GET["orderby"];
	$order = $_GET["order"];

//download the recordings
	if ($_GET['a'] == "download" && permission_exists('recordings_download')) {
		session_cache_limiter('public');
		if ($_GET['type'] = "rec") {
			if (file_exists($v_recordings_dir.'/'.base64_decode($_GET['filename']))) {
				$fd = fopen($v_recordings_dir.'/'.base64_decode($_GET['filename']), "rb");
				if ($_GET['t'] == "bin") {
					header("Content-Type: application/force-download");
					header("Content-Type: application/octet-stream");
					header("Content-Type: application/download");
					header("Content-Description: File Transfer");
					header('Content-Disposition: attachment; filename="'.base64_decode($_GET['filename']).'"');
				}
				else {
					$file_ext = substr(base64_decode($_GET['filename']), -3);
					if ($file_ext == "wav") {
						header("Content-Type: audio/x-wav");
					}
					if ($file_ext == "mp3") {
						header("Content-Type: audio/mp3");
					}
				}
				header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
				header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
				header("Content-Length: " . filesize($v_recordings_dir.'/'.base64_decode($_GET['filename'])));
				fpassthru($fd);
			}
		}
		exit;
	}

//upload the recording
	if (($_POST['submit'] == "Upload") && is_uploaded_file($_FILES['ulfile']['tmp_name']) && permission_exists('recordings_upload')) {
		if ($_POST['type'] == 'rec') {
			move_uploaded_file($_FILES['ulfile']['tmp_name'], $v_recordings_dir.'/'.$_FILES['ulfile']['name']);
			$savemsg = "Uploaded file to ".$v_recordings_dir."/". htmlentities($_FILES['ulfile']['name']);
			//system('chmod -R 744 $v_recordings_dir*');
			unset($_POST['txtCommand']);
		}
	}

//build a list of recordings
	$config_recording_list = '|';
	$i = 0;
	$sql = "";
	$sql .= "select * from v_recordings ";
	$sql .= "where v_id = '$v_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$config_recording_list .= $row['filename']."|";
	}
	unset ($prepstatement);

//add recordings to the database
	if (is_dir($v_recordings_dir.'/')) {
		if ($dh = opendir($v_recordings_dir.'/')) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($v_recordings_dir."/".$file) == "file") {
					if (strpos($config_recording_list, "|".$file) === false) {
						//echo "The $file was not found<br/>";
						//file not found add it to the database
						$a_file = explode("\.", $file);

						$sql = "insert into v_recordings ";
						$sql .= "(";
						$sql .= "v_id, ";
						$sql .= "filename, ";
						$sql .= "recordingname, ";
						//$sql .= "recordingid, ";
						$sql .= "descr ";
						$sql .= ")";
						$sql .= "values ";
						$sql .= "(";
						$sql .= "'$v_id', ";
						$sql .= "'$file', ";
						$sql .= "'".$a_file[0]."', ";
						//$sql .= "'".guid()."', ";
						$sql .= "'auto' ";
						$sql .= ")";
						$db->exec(check_sql($sql));
						unset($sql);
					}
					else {
						//echo "The $file was found.<br/>";
					}
				}
			}
			closedir($dh);
		}
	}

//include the header
	require_once "includes/header.php";

//begin the content
	echo "<script>\n";
	echo "function EvalSound(soundobj) {\n";
	echo "  var thissound= eval(\"document.\"+soundobj);\n";
	echo "  thissound.Play();\n";
	echo "}\n";
	echo "</script>";

	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";
	echo "<tr class='border'>\n";
	echo "	<td align=\"center\">\n";
	echo "      <br>";

	echo "<table width=\"100%\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "    <td align='left'><p><span class=\"vexpl\"><span class=\"red\"><strong>Recordings:<br>\n";
	echo "        </strong></span>\n";
	echo "        To make a recording dial *732 or you can make a\n";
	echo "        16bit 8khz/16khz Mono WAV file then copy it to the\n";
	echo "        following directory then refresh the page to play it back.\n";
	echo "        Click on the 'Filename' to download it or the 'Recording Name' to\n";
	echo "        play the audio.\n";
	echo "        </span></p></td>\n";
	echo "  </tr>\n";
	echo "</table>";

	echo "<br />\n";

	echo "<form action=\"\" method=\"POST\" enctype=\"multipart/form-data\" name=\"frmUpload\" onSubmit=\"\">\n";
	echo "	<table border='0' width='100%'>\n";
	echo "	<tr>\n";
	echo "		<td align='left' width='50%'>\n";
	if ($v_path_show) {
		echo "<b>location:</b> \n";
		echo $v_recordings_dir;
	}
	echo "		</td>\n";
	echo "		<td valign=\"top\" class=\"label\">\n";
	echo "			<input name=\"type\" type=\"hidden\" value=\"rec\">\n";
	echo "		</td>\n";
	echo "		<td valign=\"top\" align='right' class=\"label\" nowrap>\n";
	echo "			File to upload:\n";
	echo "			<input name=\"ulfile\" type=\"file\" class=\"btn\" id=\"ulfile\">\n";
	echo "			<input name=\"submit\" type=\"submit\"  class=\"btn\" id=\"upload\" value=\"Upload\">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "</form>";

	$sql = "";
	$sql .= "select * from v_recordings ";
	$sql .= "where v_id = '$v_id' ";
	if (strlen($orderby)> 0) { $sql .= "order by $orderby $order "; }
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$numrows = count($result);
	unset ($prepstatement, $result, $sql);

	$rowsperpage = 100;
	$param = "";
	$page = $_GET['page'];
	if (strlen($page) == 0) { $page = 0; $_GET['page'] = 0; } 
	list($pagingcontrols, $rowsperpage, $var3) = paging($numrows, $param, $rowsperpage); 
	$offset = $rowsperpage * $page; 

	$sql = "";
	$sql .= "select * from v_recordings ";
	$sql .= "where v_id = '$v_id' ";
	if (strlen($orderby)> 0) { $sql .= "order by $orderby $order "; }
	$sql .= " limit $rowsperpage offset $offset ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$resultcount = count($result);
	unset ($prepstatement, $sql);

	$c = 0;
	$rowstyle["0"] = "rowstyle0";
	$rowstyle["1"] = "rowstyle1";

	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr>\n";
	echo thorderby('filename', 'Filename (download)', $orderby, $order);
	echo thorderby('recordingname', 'Recording Name (play)', $orderby, $order);
	echo "<th width=\"10%\" class=\"listhdr\" nowrap>Size</th>\n";
	echo thorderby('descr', 'Description', $orderby, $order);
	echo "<td align='right' width='42'>\n";
	if (permission_exists('recordings_add')) {
		echo "	<a href='v_recordings_edit.php' alt='add'>$v_link_label_add</a>\n";
	}
	echo "</td>\n";
	echo "</tr>\n";

	if ($resultcount == 0) {
		//no results
	}
	else { //received results
		foreach($result as $row) {
			$tmp_filesize = filesize($v_recordings_dir.'/'.$row['filename']);
			$tmp_filesize = byte_convert($tmp_filesize);

			echo "<tr >\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>";
			echo "		<a href=\"v_recordings.php?a=download&type=rec&t=bin&filename=".base64_encode($row['filename'])."\">\n";
			echo $row['filename'];
			echo "	  </a>";
			echo "	</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>";
			echo "	  <a href=\"javascript:void(0);\" onclick=\"window.open('v_recordings_play.php?a=download&type=moh&filename=".base64_encode($row['filename'])."', 'play',' width=420,height=40,menubar=no,status=no,toolbar=no')\">\n";
			echo $row['recordingname'];
			echo "	  </a>";
			echo 	"</td>\n";
			echo "	<td class='".$rowstyle[$c]."' ondblclick=\"\">\n";
			echo "	".$tmp_filesize;
			echo "	</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."' width='30%'>".$row['descr']."</td>\n";
			echo "	<td valign='top' align='right'>\n";
			if (permission_exists('recordings_edit')) {
				echo "		<a href='v_recordings_edit.php?id=".$row['recording_id']."' alt='edit'>$v_link_label_edit</a>\n";
			}
			if (permission_exists('recordings_delete')) {
				echo "		<a href='v_recordings_delete.php?id=".$row['recording_id']."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
			}
			echo "	</td>\n";
			echo "</tr>\n";

			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $rowcount);
	} //end if results
	echo "</table>\n";

	echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='center' nowrap>$pagingcontrols</td>\n";
	echo "		<td width='33.3%' align='right'>\n";
	if (permission_exists('recordings_add')) {
		echo "			<a href='v_recordings_edit.php' alt='add'>$v_link_label_add</a>\n";
	}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";

	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>";
	echo "</div>";

	echo "<br>\n";
	echo "<br>\n";
	echo "<br>\n";
	echo "<br>\n";

//include the footer
	require_once "includes/footer.php";

?>