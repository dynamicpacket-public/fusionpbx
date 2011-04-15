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
require_once "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
if (ifgroup("admin") || ifgroup("superadmin")) {
	//access granted
}
else {
	echo "access denied";
	exit;
}


/*
//action add or update
if (isset($_REQUEST["id"])) {
	$action = "update";
	$xml_cdr_id = check_str($_REQUEST["id"]);
}
else {
	$action = "add";
}

//get the http values and set them to a variable
	if (count($_POST)>0) {
		$v_id = check_str($_POST["v_id"]);
		$uuid = check_str($_POST["uuid"]);
		$direction = check_str($_POST["direction"]);
		$language = check_str($_POST["language"]);
		$context = check_str($_POST["context"]);
		$xml_cdr = check_str($_POST["xml_cdr"]);
		$caller_id_name = check_str($_POST["caller_id_name"]);
		$caller_id_number = check_str($_POST["caller_id_number"]);
		$destination_number = check_str($_POST["destination_number"]);
		$start_epoch = check_str($_POST["start_epoch"]);
		$start_stamp = check_str($_POST["start_stamp"]);
		$start_uepoch = check_str($_POST["start_uepoch"]);
		$answer_stamp = check_str($_POST["answer_stamp"]);
		$answer_epoch = check_str($_POST["answer_epoch"]);
		$answer_uepoch = check_str($_POST["answer_uepoch"]);
		$end_epoch = check_str($_POST["end_epoch"]);
		$end_uepoch = check_str($_POST["end_uepoch"]);
		$end_stamp = check_str($_POST["end_stamp"]);
		$duration = check_str($_POST["duration"]);
		$mduration = check_str($_POST["mduration"]);
		$billsec = check_str($_POST["billsec"]);
		$billmsec = check_str($_POST["billmsec"]);
		$bridge_uuid = check_str($_POST["bridge_uuid"]);
		$read_codec = check_str($_POST["read_codec"]);
		$write_codec = check_str($_POST["write_codec"]);
		$remote_media_ip = check_str($_POST["remote_media_ip"]);
		$network_addr = check_str($_POST["network_addr"]);
		$hangup_cause = check_str($_POST["hangup_cause"]);
		$hangup_cause_q850 = check_str($_POST["hangup_cause_q850"]);
	}


if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$xml_cdr_id = check_str($_POST["xml_cdr_id"]);
	}

	//check for all required data
		//if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		//if (strlen($uuid) == 0) { $msg .= "Please provide: UUID<br>\n"; }
		//if (strlen($direction) == 0) { $msg .= "Please provide: Direction<br>\n"; }
		//if (strlen($language) == 0) { $msg .= "Please provide: Language<br>\n"; }
		//if (strlen($context) == 0) { $msg .= "Please provide: Context<br>\n"; }
		//if (strlen($xml_cdr) == 0) { $msg .= "Please provide: XML<br>\n"; }
		//if (strlen($caller_id_name) == 0) { $msg .= "Please provide: CID Name<br>\n"; }
		//if (strlen($caller_id_number) == 0) { $msg .= "Please provide: CID Number<br>\n"; }
		//if (strlen($destination_number) == 0) { $msg .= "Please provide: Destination<br>\n"; }
		//if (strlen($start_epoch) == 0) { $msg .= "Please provide: Start Epoch<br>\n"; }
		//if (strlen($start_stamp) == 0) { $msg .= "Please provide: Start<br>\n"; }
		//if (strlen($start_uepoch) == 0) { $msg .= "Please provide: Start Micro Epoch<br>\n"; }
		//if (strlen($answer_stamp) == 0) { $msg .= "Please provide: Answer<br>\n"; }
		//if (strlen($answer_epoch) == 0) { $msg .= "Please provide: Answer Epoch<br>\n"; }
		//if (strlen($answer_uepoch) == 0) { $msg .= "Please provide: Answer UEpoch<br>\n"; }
		//if (strlen($end_epoch) == 0) { $msg .= "Please provide: End Epoch<br>\n"; }
		//if (strlen($end_uepoch) == 0) { $msg .= "Please provide: End UEpoch<br>\n"; }
		//if (strlen($end_stamp) == 0) { $msg .= "Please provide: End<br>\n"; }
		//if (strlen($duration) == 0) { $msg .= "Please provide: Duration<br>\n"; }
		//if (strlen($mduration) == 0) { $msg .= "Please provide: M Duration<br>\n"; }
		//if (strlen($billsec) == 0) { $msg .= "Please provide: Bill Seconds<br>\n"; }
		//if (strlen($billmsec) == 0) { $msg .= "Please provide: Bill M Sec<br>\n"; }
		//if (strlen($bridge_uuid) == 0) { $msg .= "Please provide: Bridge UUID<br>\n"; }
		//if (strlen($read_codec) == 0) { $msg .= "Please provide: Read Codec<br>\n"; }
		//if (strlen($write_codec) == 0) { $msg .= "Please provide: Write Codec<br>\n"; }
		//if (strlen($remote_media_ip) == 0) { $msg .= "Please provide: Remote Media IP<br>\n"; }
		//if (strlen($network_addr) == 0) { $msg .= "Please provide: Network Address<br>\n"; }
		//if (strlen($hangup_cause) == 0) { $msg .= "Please provide: Hangup Cause<br>\n"; }
		//if (strlen($hangup_cause_q850) == 0) { $msg .= "Please provide: Hangup Cause q850<br>\n"; }
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
		if ($action == "add") {
			$sql = "insert into v_xml_cdr ";
			$sql .= "(";
			$sql .= "v_id, ";
			$sql .= "uuid, ";
			$sql .= "direction, ";
			$sql .= "language, ";
			$sql .= "context, ";
			$sql .= "xml_cdr, ";
			$sql .= "caller_id_name, ";
			$sql .= "caller_id_number, ";
			$sql .= "destination_number, ";
			$sql .= "start_epoch, ";
			$sql .= "start_stamp, ";
			$sql .= "start_uepoch, ";
			$sql .= "answer_stamp, ";
			$sql .= "answer_epoch, ";
			$sql .= "answer_uepoch, ";
			$sql .= "end_epoch, ";
			$sql .= "end_uepoch, ";
			$sql .= "end_stamp, ";
			$sql .= "duration, ";
			$sql .= "mduration, ";
			$sql .= "billsec, ";
			$sql .= "billmsec, ";
			$sql .= "bridge_uuid, ";
			$sql .= "read_codec, ";
			$sql .= "write_codec, ";
			$sql .= "remote_media_ip, ";
			$sql .= "network_addr, ";
			$sql .= "hangup_cause, ";
			$sql .= "hangup_cause_q850 ";
			$sql .= ")";
			$sql .= "values ";
			$sql .= "(";
			$sql .= "'$v_id', ";
			$sql .= "'$uuid', ";
			$sql .= "'$direction', ";
			$sql .= "'$language', ";
			$sql .= "'$context', ";
			$sql .= "'$xml_cdr', ";
			$sql .= "'$caller_id_name', ";
			$sql .= "'$caller_id_number', ";
			$sql .= "'$destination_number', ";
			$sql .= "'$start_epoch', ";
			$sql .= "'$start_stamp', ";
			$sql .= "'$start_uepoch', ";
			$sql .= "'$answer_stamp', ";
			$sql .= "'$answer_epoch', ";
			$sql .= "'$answer_uepoch', ";
			$sql .= "'$end_epoch', ";
			$sql .= "'$end_uepoch', ";
			$sql .= "'$end_stamp', ";
			$sql .= "'$duration', ";
			$sql .= "'$mduration', ";
			$sql .= "'$billsec', ";
			$sql .= "'$billmsec', ";
			$sql .= "'$bridge_uuid', ";
			$sql .= "'$read_codec', ";
			$sql .= "'$write_codec', ";
			$sql .= "'$remote_media_ip', ";
			$sql .= "'$network_addr', ";
			$sql .= "'$hangup_cause', ";
			$sql .= "'$hangup_cause_q850' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);

			require_once "includes/header.php";
			echo "<meta http-equiv=\"refresh\" content=\"2;url=v_xml_cdr.php\">\n";
			echo "<div align='center'>\n";
			echo "Add Complete\n";
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
		} //if ($action == "add")

		if ($action == "update") {
			$sql = "update v_xml_cdr set ";
			$sql .= "uuid = '$uuid', ";
			$sql .= "direction = '$direction', ";
			$sql .= "language = '$language', ";
			$sql .= "context = '$context', ";
			$sql .= "xml_cdr = '$xml_cdr', ";
			$sql .= "caller_id_name = '$caller_id_name', ";
			$sql .= "caller_id_number = '$caller_id_number', ";
			$sql .= "destination_number = '$destination_number', ";
			$sql .= "start_epoch = '$start_epoch', ";
			$sql .= "start_stamp = '$start_stamp', ";
			$sql .= "start_uepoch = '$start_uepoch', ";
			$sql .= "answer_stamp = '$answer_stamp', ";
			$sql .= "answer_epoch = '$answer_epoch', ";
			$sql .= "answer_uepoch = '$answer_uepoch', ";
			$sql .= "end_epoch = '$end_epoch', ";
			$sql .= "end_uepoch = '$end_uepoch', ";
			$sql .= "end_stamp = '$end_stamp', ";
			$sql .= "duration = '$duration', ";
			$sql .= "mduration = '$mduration', ";
			$sql .= "billsec = '$billsec', ";
			$sql .= "billmsec = '$billmsec', ";
			$sql .= "bridge_uuid = '$bridge_uuid', ";
			$sql .= "read_codec = '$read_codec', ";
			$sql .= "write_codec = '$write_codec', ";
			$sql .= "remote_media_ip = '$remote_media_ip', ";
			$sql .= "network_addr = '$network_addr', ";
			$sql .= "hangup_cause = '$hangup_cause', ";
			$sql .= "hangup_cause_q850 = '$hangup_cause_q850' ";
			$sql .= "where v_id = '$v_id'";
			$sql .= "and xml_cdr_id = '$xml_cdr_id'";
			$db->exec(check_sql($sql));
			unset($sql);

			require_once "includes/header.php";
			echo "<meta http-equiv=\"refresh\" content=\"2;url=v_xml_cdr.php\">\n";
			echo "<div align='center'>\n";
			echo "Update Complete\n";
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
		} //if ($action == "update")
	} //if ($_POST["persistformvar"] != "true")

} //(count($_POST)>0 && strlen($_POST["persistformvar"]) == 0)
*/

//pre-populate the form
	if (count($_GET)>0 && $_POST["persistformvar"] != "true") {
		$xml_cdr_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_xml_cdr ";
		$sql .= "where xml_cdr_id = '$xml_cdr_id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$v_id = $row["v_id"];
			$uuid = $row["uuid"];
			$direction = $row["direction"];
			$language = $row["language"];
			$context = $row["context"];
			$xml_cdr = $row["xml_cdr"];
			$caller_id_name = $row["caller_id_name"];
			$caller_id_number = $row["caller_id_number"];
			$destination_number = $row["destination_number"];
			$start_epoch = $row["start_epoch"];
			$start_stamp = $row["start_stamp"];
			$start_uepoch = $row["start_uepoch"];
			$answer_stamp = $row["answer_stamp"];
			$answer_epoch = $row["answer_epoch"];
			$answer_uepoch = $row["answer_uepoch"];
			$end_epoch = $row["end_epoch"];
			$end_uepoch = $row["end_uepoch"];
			$end_stamp = $row["end_stamp"];
			$duration = $row["duration"];
			$mduration = $row["mduration"];
			$billsec = $row["billsec"];
			$billmsec = $row["billmsec"];
			$bridge_uuid = $row["bridge_uuid"];
			$read_codec = $row["read_codec"];
			$write_codec = $row["write_codec"];
			$remote_media_ip = $row["remote_media_ip"];
			$network_addr = $row["network_addr"];
			$hangup_cause = $row["hangup_cause"];
			$hangup_cause_q850 = $row["hangup_cause_q850"];
			break; //limit to 1 row
		}
		unset ($prepstatement);
	}

//show the header
	require_once "includes/header.php";

//show the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing=''>\n";
	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "	  <br>";

	echo "<form method='post' name='frm' action=''>\n";
	echo "<div align='center'>\n";
	echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";

	echo "<tr>\n";
	if ($action == "add") {
		echo "<td align='left' width='30%' nowrap><b>Xml Cdr Add</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align='left' width='30%' nowrap><b>Xml Cdr Edit</b></td>\n";
	}
	if ($action == "") {
		echo "<td align='left' width='30%' nowrap><b>Call Details</b></td>\n";
	}
	echo "<td width='70%' align='right'><input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_xml_cdr.php'\" value='Back'></td>\n";
	echo "</tr>\n";

	//echo "<tr>\n";
	//echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	//echo "	v_id:\n";
	//echo "</td>\n";
	//echo "<td class='vtable' align='left'>\n";
	//echo "  <input class='formfld' type='text' name='v_id' maxlength='255' value='$v_id'>\n";
	//echo "<br />\n";
	//echo "\n";
	//echo "</td>\n";
	//echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	UUID:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='uuid' maxlength='255' value=\"$uuid\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Direction:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='direction' maxlength='255' value=\"$direction\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Language:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='language' maxlength='255' value=\"$language\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Context:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='context' maxlength='255' value=\"$context\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	//echo "<tr>\n";
	//echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	//echo "	XML:\n";
	//echo "</td>\n";
	//echo "<td class='vtable' align='left'>\n";
	//echo "	<input class='formfld' type='text' name='xml_cdr' maxlength='255' value=\"$xml_cdr\">\n";
	//echo "<br />\n";
	//echo "\n";
	//echo "</td>\n";
	//echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	CID Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='caller_id_name' maxlength='255' value=\"$caller_id_name\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	CID Number:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <input class='formfld' type='text' name='caller_id_number' maxlength='255' value='$caller_id_number'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Destination:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <input class='formfld' type='text' name='destination_number' maxlength='255' value='$destination_number'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Start Epoch:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <input class='formfld' type='text' name='start_epoch' maxlength='255' value='$start_epoch'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Start:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='start_stamp' maxlength='255' value=\"$start_stamp\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Start Micro Epoch:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <input class='formfld' type='text' name='start_uepoch' maxlength='255' value='$start_uepoch'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Answer:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='answer_stamp' maxlength='255' value=\"$answer_stamp\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Answer Epoch:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <input class='formfld' type='text' name='answer_epoch' maxlength='255' value='$answer_epoch'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Answer UEpoch:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <input class='formfld' type='text' name='answer_uepoch' maxlength='255' value='$answer_uepoch'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	End Epoch:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <input class='formfld' type='text' name='end_epoch' maxlength='255' value='$end_epoch'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	End UEpoch:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <input class='formfld' type='text' name='end_uepoch' maxlength='255' value='$end_uepoch'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	End:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='end_stamp' maxlength='255' value=\"$end_stamp\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Duration:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <input class='formfld' type='text' name='duration' maxlength='255' value='$duration'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	M Duration:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <input class='formfld' type='text' name='mduration' maxlength='255' value='$mduration'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Bill Seconds:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <input class='formfld' type='text' name='billsec' maxlength='255' value='$billsec'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Bill M Sec:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <input class='formfld' type='text' name='billmsec' maxlength='255' value='$billmsec'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Bridge UUID:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='bridge_uuid' maxlength='255' value=\"$bridge_uuid\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Read Codec:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='read_codec' maxlength='255' value=\"$read_codec\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Write Codec:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='write_codec' maxlength='255' value=\"$write_codec\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Remote Media IP:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='remote_media_ip' maxlength='255' value=\"$remote_media_ip\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Network Address:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='network_addr' maxlength='255' value=\"$network_addr\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Hangup Cause:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='hangup_cause' maxlength='255' value=\"$hangup_cause\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Hangup Cause q850:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <input class='formfld' type='text' name='hangup_cause_q850' maxlength='255' value='$hangup_cause_q850'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";
	//echo "	<tr>\n";
	//echo "		<td colspan='2' align='right'>\n";
	//if ($action == "update") {
	//	echo "				<input type='hidden' name='xml_cdr_id' value='$xml_cdr_id'>\n";
	//}
	//echo "				<input type='submit' name='submit' class='btn' value='Save'>\n";
	//echo "		</td>\n";
	//echo "	</tr>";
	echo "</table>";
	echo "</form>";

	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

//show the footer
	require_once "includes/footer.php";
?>
