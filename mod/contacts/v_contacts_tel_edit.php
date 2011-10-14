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
if (permission_exists('contacts_edit')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//action add or update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$contacts_tel_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

if (strlen($_GET["contact_id"]) > 0) {
	$contact_id = check_str($_GET["contact_id"]);
}

//get http post variables and set them to php variables
	if (count($_POST)>0) {
		$tel_type = check_str($_POST["tel_type"]);
		$tel_number = check_str($_POST["tel_number"]);

		//remove any phone number formatting
		$tel_number = preg_replace('{\D}', '', $tel_number);
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$contacts_tel_id = check_str($_POST["contacts_tel_id"]);
	}

	//check for all required data
		//if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		//if (strlen($tel_type) == 0) { $msg .= "Please provide: Telephone Type.<br>\n"; }
		//if (strlen($tel_number) == 0) { $msg .= "Please provide: Telephone Number<br>\n"; }
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
			$sql = "insert into v_contacts_tel ";
			$sql .= "(";
			$sql .= "contact_id, ";
			$sql .= "v_id, ";
			$sql .= "tel_type, ";
			$sql .= "tel_number ";
			$sql .= ")";
			$sql .= "values ";
			$sql .= "(";
			$sql .= "'$contact_id', ";
			$sql .= "'$v_id', ";
			$sql .= "'$tel_type', ";
			$sql .= "'$tel_number' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);

			require_once "includes/header.php";
			echo "<meta http-equiv=\"refresh\" content=\"2;url=v_contacts_edit.php?id=$contact_id\">\n";
			echo "<div align='center'>\n";
			echo "Add Complete\n";
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
		} //if ($action == "add")

		if ($action == "update") {
			$sql = "update v_contacts_tel set ";
			$sql .= "contact_id = '$contact_id', ";
			$sql .= "tel_type = '$tel_type', ";
			$sql .= "tel_number = '$tel_number' ";
			$sql .= "where v_id = '$v_id'";
			$sql .= "and contacts_tel_id = '$contacts_tel_id'";
			$db->exec(check_sql($sql));
			unset($sql);

			require_once "includes/header.php";
			echo "<meta http-equiv=\"refresh\" content=\"2;url=v_contacts_edit.php?id=$contact_id\">\n";
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
		$contacts_tel_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_contacts_tel ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and contacts_tel_id = '$contacts_tel_id' ";
		$prep_statement = $db->prepare(check_sql($sql));
		$prep_statement->execute();
		$result = $prep_statement->fetchAll();
		foreach ($result as &$row) {
			$tel_type = $row["tel_type"];
			$tel_number = $row["tel_number"];
			break; //limit to 1 row
		}
		unset ($prep_statement);
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
		echo "<td align='left' width='30%' nowrap='nowrap'><b>Contacts Tel Add</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align='left' width='30%' nowrap='nowrap'><b>Contacts Tel Edit</b></td>\n";
	}
	echo "<td width='70%' align='right'><input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_contacts_edit.php?id=$contact_id'\" value='Back'></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan='2'>\n";
	echo "Telephone Numbers<br /><br />\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	Telephone Type.:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<select class='formfld' name='tel_type'>\n";
	echo "	<option value=''></option>\n";
	if ($tel_type == "home") { 
		echo "	<option value='home' SELECTED >Home</option>\n";
	}
	else {
		echo "	<option value='home'>Home</option>\n";
	}
	if ($tel_type == "work") { 
		echo "	<option value='work' SELECTED >Work</option>\n";
	}
	else {
		echo "	<option value='work'>Work</option>\n";
	}
	if ($tel_type == "pref") { 
		echo "	<option value='pref' SELECTED >Pref</option>\n";
	}
	else {
		echo "	<option value='pref'>Pref</option>\n";
	}
	if ($tel_type == "voice") { 
		echo "	<option value='voice' SELECTED >Voice</option>\n";
	}
	else {
		echo "	<option value='voice'>Voice</option>\n";
	}
	if ($tel_type == "fax") { 
		echo "	<option value='fax' SELECTED >Fax</option>\n";
	}
	else {
		echo "	<option value='fax'>Fax</option>\n";
	}
	if ($tel_type == "msg") { 
		echo "	<option value='msg' SELECTED >MSG</option>\n";
	}
	else {
		echo "	<option value='msg'>MSG</option>\n";
	}
	if ($tel_type == "cell") { 
		echo "	<option value='cell' SELECTED >Cell</option>\n";
	}
	else {
		echo "	<option value='cell'>Cell</option>\n";
	}
	if ($tel_type == "pager") { 
		echo "	<option value='pager' SELECTED >Pager</option>\n";
	}
	else {
		echo "	<option value='pager'>Pager</option>\n";
	}
	if ($tel_type == "bbs") { 
		echo "	<option value='bbs' SELECTED >BBS</option>\n";
	}
	else {
		echo "	<option value='bbs'>BBS</option>\n";
	}
	if ($tel_type == "modem") { 
		echo "	<option value='modem' SELECTED >Modem</option>\n";
	}
	else {
		echo "	<option value='modem'>Modem</option>\n";
	}
	if ($tel_type == "car") { 
		echo "	<option value='car' SELECTED >Car</option>\n";
	}
	else {
		echo "	<option value='car'>Car</option>\n";
	}
	if ($tel_type == "isdn") { 
		echo "	<option value='isdn' SELECTED >ISDN</option>\n";
	}
	else {
		echo "	<option value='isdn'>ISDN</option>\n";
	}
	if ($tel_type == "video") { 
		echo "	<option value='video' SELECTED >Video</option>\n";
	}
	else {
		echo "	<option value='video'>Video</option>\n";
	}
	if ($tel_type == "pcs") { 
		echo "	<option value='pcs' SELECTED >PCS</option>\n";
	}
	else {
		echo "	<option value='pcs'>PCS</option>\n";
	}
	if ($tel_type == "iana-token") { 
		echo "	<option value='iana-token' SELECTED >iana-token</option>\n";
	}
	else {
		echo "	<option value='iana-token'>iana-token</option>\n";
	}
	if ($tel_type == "x-name") { 
		echo "	<option value='x-name' SELECTED >x-name</option>\n";
	}
	else {
		echo "	<option value='x-name'>x-name</option>\n";
	}
	echo "	</select>\n";
	echo "<br />\n";
	echo "Enter the  telephone type.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	Telephone Number:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='tel_number' maxlength='255' value=\"$tel_number\">\n";
	echo "<br />\n";
	echo "Enter the telephone number.\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	echo "				<input type='hidden' name='contact_id' value='$contact_id'>\n";
	if ($action == "update") {
		echo "				<input type='hidden' name='contacts_tel_id' value='$contacts_tel_id'>\n";
	}
	echo "				<input type='submit' name='submit' class='btn' value='Save'>\n";
	echo "		</td>\n";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";

	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

//include the footer
	require_once "includes/footer.php";
?>
