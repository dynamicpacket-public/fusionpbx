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
		$contacts_note_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

if (strlen($_GET["contact_id"]) > 0) {
	$contact_id = check_str($_GET["contact_id"]);
}

//get http post variables and set them to php variables
	if (count($_POST)>0) {
		$notes = check_str($_POST["notes"]);
		$last_mod_date = check_str($_POST["last_mod_date"]);
		$last_mod_user = check_str($_POST["last_mod_user"]);
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$contacts_note_id = check_str($_POST["contacts_note_id"]);
	}

	//check for all required data
		//if (strlen($notes) == 0) { $msg .= "Please provide: Notes<br>\n"; }
		//if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		//if (strlen($last_mod_date) == 0) { $msg .= "Please provide: Last Modified Date<br>\n"; }
		//if (strlen($last_mod_user) == 0) { $msg .= "Please provide: Last Modified By<br>\n"; }
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
			$sql = "insert into v_contact_notes ";
			$sql .= "(";
			$sql .= "contact_id, ";
			$sql .= "notes, ";
			$sql .= "v_id, ";
			$sql .= "last_mod_date, ";
			$sql .= "last_mod_user ";
			$sql .= ")";
			$sql .= "values ";
			$sql .= "(";
			$sql .= "'$contact_id', ";
			$sql .= "'$notes', ";
			$sql .= "'$v_id', ";
			$sql .= "now(), ";
			$sql .= "'".$_SESSION['username']."' ";
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
			$sql = "update v_contact_notes set ";
			$sql .= "contact_id = '$contact_id', ";
			$sql .= "notes = '$notes', ";
			$sql .= "last_mod_date = now(), ";
			$sql .= "last_mod_user = '".$_SESSION['username']."' ";
			$sql .= "where v_id = '$v_id' ";
			$sql .= "and contacts_note_id = '$contacts_note_id'";
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
		$contacts_note_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_contact_notes ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and contacts_note_id = '$contacts_note_id' ";
		$prep_statement = $db->prepare(check_sql($sql));
		$prep_statement->execute();
		$result = $prep_statement->fetchAll();
		foreach ($result as &$row) {
			$notes = $row["notes"];
			$last_mod_date = $row["last_mod_date"];
			$last_mod_user = $row["last_mod_user"];
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
		echo "<td align='left' width='15%' nowrap='nowrap'><b>Add Notes</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align='left' width='15%' nowrap='nowrap'><b>Edit Notes</b></td>\n";
	}
	echo "<td width='70%' align='right'><input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_contacts_edit.php?id=$contact_id'\" value='Back'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	Notes:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <textarea class='formfld' type='text' rows=\"20\" style=\"width: 100%\" name='notes'>$notes</textarea>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	echo "				<input type='hidden' name='contact_id' value='$contact_id'>\n";
	if ($action == "update") {
		echo "				<input type='hidden' name='contacts_note_id' value='$contacts_note_id'>\n";
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
