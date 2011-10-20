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
		$contact_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//get http post variables and set them to php variables
	if (count($_POST)>0) {
		$type = check_str($_POST["type"]);
		$org = check_str($_POST["org"]);
		$n_given = check_str($_POST["n_given"]);
		$n_family = check_str($_POST["n_family"]);
		$nickname = check_str($_POST["nickname"]);
		$title = check_str($_POST["title"]);
		$role = check_str($_POST["role"]);
		$email = check_str($_POST["email"]);
		$url = check_str($_POST["url"]);
		$tz = check_str($_POST["tz"]);
		$note = check_str($_POST["note"]);
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$contact_id = check_str($_POST["contact_id"]);
	}

	//check for all required data
		//if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		//if (strlen($type) == 0) { $msg .= "Please provide: Type<br>\n"; }
		//if (strlen($org) == 0) { $msg .= "Please provide: Organization<br>\n"; }
		//if (strlen($n_given) == 0) { $msg .= "Please provide: First Name<br>\n"; }
		//if (strlen($n_family) == 0) { $msg .= "Please provide: Last Name<br>\n"; }
		//if (strlen($nickname) == 0) { $msg .= "Please provide: Nickname<br>\n"; }
		//if (strlen($title) == 0) { $msg .= "Please provide: Title<br>\n"; }
		//if (strlen($role) == 0) { $msg .= "Please provide: Role<br>\n"; }
		//if (strlen($) == 0) { $msg .= "Please provide: Contact Information<br>\n"; }
		//if (strlen($email) == 0) { $msg .= "Please provide: Email<br>\n"; }
		//if (strlen($url) == 0) { $msg .= "Please provide: URL<br>\n"; }
		//if (strlen($) == 0) { $msg .= "Please provide: Additional Information<br>\n"; }
		//if (strlen($tz) == 0) { $msg .= "Please provide: Time Zone<br>\n"; }
		//if (strlen($note) == 0) { $msg .= "Please provide: Notes<br>\n"; }
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
			$sql = "insert into v_contacts ";
			$sql .= "(";
			$sql .= "v_id, ";
			$sql .= "type, ";
			$sql .= "org, ";
			$sql .= "n_given, ";
			$sql .= "n_family, ";
			$sql .= "nickname, ";
			$sql .= "title, ";
			$sql .= "role, ";
			$sql .= "email, ";
			$sql .= "url, ";
			$sql .= "tz, ";
			$sql .= "note ";
			$sql .= ")";
			$sql .= "values ";
			$sql .= "(";
			$sql .= "'$v_id', ";
			$sql .= "'$type', ";
			$sql .= "'$org', ";
			$sql .= "'$n_given', ";
			$sql .= "'$n_family', ";
			$sql .= "'$nickname', ";
			$sql .= "'$title', ";
			$sql .= "'$role', ";
			$sql .= "'$email', ";
			$sql .= "'$url', ";
			$sql .= "'$tz', ";
			$sql .= "'$note' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);

			require_once "includes/header.php";
			echo "<meta http-equiv=\"refresh\" content=\"2;url=v_contacts.php\">\n";
			echo "<div align='center'>\n";
			echo "Add Complete\n";
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
		} //if ($action == "add")

		if ($action == "update") {
			$sql = "update v_contacts set ";
			$sql .= "v_id = '$v_id', ";
			$sql .= "type = '$type', ";
			$sql .= "org = '$org', ";
			$sql .= "n_given = '$n_given', ";
			$sql .= "n_family = '$n_family', ";
			$sql .= "nickname = '$nickname', ";
			$sql .= "title = '$title', ";
			$sql .= "role = '$role', ";
			$sql .= "email = '$email', ";
			$sql .= "url = '$url', ";
			$sql .= "tz = '$tz', ";
			$sql .= "note = '$note' ";
			$sql .= "where v_id = '$v_id' ";
			$sql .= "and contact_id = '$contact_id' ";
			$db->exec(check_sql($sql));
			unset($sql);

			require_once "includes/header.php";
			echo "<meta http-equiv=\"refresh\" content=\"2;url=v_contacts.php\">\n";
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
		$contact_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_contacts ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and contact_id = '$contact_id' ";
		$prep_statement = $db->prepare(check_sql($sql));
		$prep_statement->execute();
		$result = $prep_statement->fetchAll();
		foreach ($result as &$row) {
			$type = $row["type"];
			$org = $row["org"];
			$n_given = $row["n_given"];
			$n_family = $row["n_family"];
			$nickname = $row["nickname"];
			$title = $row["title"];
			$role = $row["role"];
			$email = $row["email"];
			$url = $row["url"];
			$tz = $row["tz"];
			$note = $row["note"];
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
	echo "		<br>";

	echo "<form method='post' name='frm' action=''>\n";
	echo "<div align='center'>\n";
	echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";
	echo "<tr>\n";
	if ($action == "add") {
		echo "<td align='left' width='30%' nowrap='nowrap'><b>Contact Add</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align='left' width='30%' nowrap='nowrap'><b>Contact Edit</b></td>\n";
	}
	echo "<td width='70%' align='right'>\n";
	echo "	<input type='button' class='btn' name='' alt='qr code' onclick=\"window.location='v_contacts_vcard.php?id=$contact_id&type=image'\" value='QR Code'>\n";
	echo "	<input type='button' class='btn' name='' alt='vcard' onclick=\"window.location='v_contacts_vcard.php?id=$contact_id&type=download'\" value='vCard'>\n";
	if ($action == "update" && is_dir($_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/mod/invoices')) {
		echo "	<input type='button' class='btn' name='' alt='invoice' onclick=\"window.location='/mod/invoices/v_invoices.php?id=$contact_id'\" value='Invoices'>\n";
	}
	echo "	<input type='button' class='btn' name='' alt='back' onclick=\"window.history.back();\" value='Back'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan='2'>\n";
	echo "The contact is a list of individuals and organizations.<br /><br />\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<table border='0' width='100%'>\n";
	echo "<tr>\n";
	echo "<td width='55%' class='vncell' valign='top' align='left' nowrap='nowrap'>\n";

		echo "<table border='0' width='100%'>\n";
		echo "<tr>\n";
		echo "	<td><strong>User Information</strong></td>\n";
		echo "	<td>&nbsp;</td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
		echo "	Type:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "	<select class='formfld' style='width:85%;' name='type'>\n";
		echo "	<option value=''></option>\n";
		if ($type == "customer") { 
			echo "	<option value='customer' selected='selected' >Customer</option>\n";
		}
		else {
			echo "	<option value='customer'>Customer</option>\n";
		}
		if ($type == "contractor") { 
			echo "	<option value='contractor' selected='selected' >Contractor</option>\n";
		}
		else {
			echo "	<option value='contractor'>Contractor</option>\n";
		}
		if ($type == "friend") { 
			echo "	<option value='friend' selected='selected' >Friend</option>\n";
		}
		else {
			echo "	<option value='friend'>Friend</option>\n";
		}
		if ($type == "lead") { 
			echo "	<option value='lead' selected='selected' >Lead</option>\n";
		}
		else {
			echo "	<option value='lead'>Lead</option>\n";
		}
		if ($type == "member") { 
			echo "	<option value='member' selected='selected' >Member</option>\n";
		}
		else {
			echo "	<option value='member'>Member</option>\n";
		}
		if ($type == "family") { 
			echo "	<option value='family' selected='selected' >Family</option>\n";
		}
		else {
			echo "	<option value='family'>Family</option>\n";
		}
		if ($type == "subscriber") { 
			echo "	<option value='subscriber' selected='selected' >Subscriber</option>\n";
		}
		else {
			echo "	<option value='subscriber'>Subscriber</option>\n";
		}
		if ($type == "supplier") { 
			echo "	<option value='supplier' selected='selected' >Supplier</option>\n";
		}
		else {
			echo "	<option value='supplier'>Supplier</option>\n";
		}
		if ($type == "provider") { 
			echo "	<option value='provider' selected='selected' >Provider</option>\n";
		}
		else {
			echo "	<option value='provider'>Provider</option>\n";
		}
		if ($type == "volunteer") { 
			echo "	<option value='volunteer' selected='selected' >Volunteer</option>\n";
		}
		else {
			echo "	<option value='volunteer'>Volunteer</option>\n";
		}
		echo "	</select>\n";
		echo "<br />\n";
		echo "Select the contact type.\n";
		echo "</td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
		echo "	Organization:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "	<input class='formfld' style='width:85%;' type='text' name='org' maxlength='255' value=\"$org\">\n";
		echo "<br />\n";
		echo "Enter the organization.\n";
		echo "</td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
		echo "	First Name:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "	<input class='formfld' style='width:85%;' type='text' name='n_given' maxlength='255' value=\"$n_given\">\n";
		echo "<br />\n";
		echo "Enter the given name.\n";
		echo "</td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
		echo "	Last Name:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "	<input class='formfld' style='width:85%;' type='text' name='n_family' maxlength='255' value=\"$n_family\">\n";
		echo "<br />\n";
		echo "Enter the family name.\n";
		echo "</td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
		echo "	Nickname:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "	<input class='formfld' style='width:85%;' type='text' name='nickname' maxlength='255' value=\"$nickname\">\n";
		echo "<br />\n";
		echo "Enter the nickname.\n";
		echo "</td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
		echo "	Title:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "	<input class='formfld' style='width:85%;' type='text' name='title' maxlength='255' value=\"$title\">\n";
		echo "<br />\n";
		echo "Enter the title.\n";
		echo "</td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
		echo "	Role:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "	<input class='formfld' style='width:85%;' type='text' name='role' maxlength='255' value=\"$role\">\n";
		echo "<br />\n";
		echo "Enter the role.\n";
		echo "</td>\n";
		echo "</tr>\n";

		//echo "<tr>\n";
		//echo "<td><strong>Contact Information</strong></td>\n";
		//echo "<td>&nbsp;</td>\n";
		//echo "<tr>\n";

		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
		echo "	Email:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "	<input class='formfld' style='width:85%;' type='text' name='email' maxlength='255' value=\"$email\">\n";
		echo "<br />\n";
		echo "Enter the email address.\n";
		echo "</td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
		echo "	URL:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "  <input class='formfld' style='width:85%;' type='text' name='url' maxlength='255' value='$url'>\n";
		echo "<br />\n";
		echo "Enter the website address.\n";
		echo "</td>\n";
		echo "</tr>\n";

		//echo "<tr>\n";
		//echo "<td><strong>Additional Information</strong></td>\n";
		//echo "<td>&nbsp;</td>\n";
		//echo "<tr>\n";

		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
		echo "	Time Zone:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "	<input class='formfld' style='width:85%;' type='text' name='tz' maxlength='255' value=\"$tz\">\n";
		echo "<br />\n";
		echo "Enter the time zone.\n";
		echo "</td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
		echo "	Notes:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "  <input class='formfld' style='width:85%;' type='text' name='note' maxlength='255' value='$note'>\n";
		echo "<br />\n";
		echo "Enter the notes.\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "	<tr>\n";
		echo "		<td colspan='2' align='right'>\n";
		if ($action == "update") {
			echo "				<input type='hidden' name='contact_id' value='$contact_id'>\n";
		}
		echo "				<input type='submit' name='submit' class='btn' value='Save'>\n";
		echo "		</td>\n";
		echo "	</tr>";
		echo "</table>";
		echo "</form>";

	echo "</td>\n";
	echo "<td width='45%' class='' valign='top' align='center' nowrap='nowrap'>\n";
		//echo "	<img src='v_contacts_vcard.php?id=$contact_id&type=image' width='90%'><br /><br />\n";

		if ($action == "update") {
			require "v_contacts_tel.php";
			require "v_contacts_adr.php";
			require "v_contact_notes.php";
			//echo "<br/><br/>\n";
		}

	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	if ($action == "update") {
		echo "<br/>\n";
		
	}

	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

//include the footer
	require_once "includes/footer.php";
?>
