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
if (permission_exists('modules_add') || permission_exists('modules_edit')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//determin the action add or update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$module_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//set the http post variables to php variables
	if (count($_POST)>0) {
		$modulelabel = check_str($_POST["modulelabel"]);
		$modulename = check_str($_POST["modulename"]);
		$moduledesc = check_str($_POST["moduledesc"]);
		$modulecat = check_str($_POST["modulecat"]);
		$moduleenabled = check_str($_POST["moduleenabled"]);
		$moduledefaultenabled = check_str($_POST["moduledefaultenabled"]);
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$module_id = check_str($_POST["module_id"]);
	}

	//check for all required data
		if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		if (strlen($modulelabel) == 0) { $msg .= "Please provide: Label<br>\n"; }
		if (strlen($modulename) == 0) { $msg .= "Please provide: Module Name<br>\n"; }
		//if (strlen($moduledesc) == 0) { $msg .= "Please provide: Description<br>\n"; }
		if (strlen($modulecat) == 0) { $msg .= "Please provide: Module Category<br>\n"; }
		if (strlen($moduleenabled) == 0) { $msg .= "Please provide: Enabled<br>\n"; }
		if (strlen($moduledefaultenabled) == 0) { $msg .= "Please provide: Default Enabled<br>\n"; }
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
			if ($action == "add" && permission_exists('modules_add')) {
				$sql = "insert into v_modules ";
				$sql .= "(";
				$sql .= "v_id, ";
				$sql .= "modulelabel, ";
				$sql .= "modulename, ";
				$sql .= "moduledesc, ";
				$sql .= "modulecat, ";
				$sql .= "moduleenabled, ";
				$sql .= "moduledefaultenabled ";
				$sql .= ")";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'1', ";
				$sql .= "'$modulelabel', ";
				$sql .= "'$modulename', ";
				$sql .= "'$moduledesc', ";
				$sql .= "'$modulecat', ";
				$sql .= "'$moduleenabled', ";
				$sql .= "'$moduledefaultenabled' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql);

				sync_package_v_modules();

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_modules.php\">\n";
				echo "<div align='center'>\n";
				echo "Add Complete\n";
				echo "</div>\n";
				require_once "includes/footer.php";
				return;
			} //if ($action == "add")

			if ($action == "update" && permission_exists('modules_edit')) {
				$sql = "update v_modules set ";
				$sql .= "modulelabel = '$modulelabel', ";
				$sql .= "modulename = '$modulename', ";
				$sql .= "moduledesc = '$moduledesc', ";
				$sql .= "modulecat = '$modulecat', ";
				$sql .= "moduleenabled = '$moduleenabled', ";
				$sql .= "moduledefaultenabled = '$moduledefaultenabled' ";
				$sql .= "where module_id = '$module_id' ";
				$db->exec(check_sql($sql));
				unset($sql);

				sync_package_v_modules();

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_modules.php\">\n";
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
		$module_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_modules ";
		$sql .= "where module_id = '$module_id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$v_id = $row["v_id"];
			$modulelabel = $row["modulelabel"];
			$modulename = $row["modulename"];
			$moduledesc = $row["moduledesc"];
			$modulecat = $row["modulecat"];
			$moduleenabled = $row["moduleenabled"];
			$moduledefaultenabled = $row["moduledefaultenabled"];
			break; //limit to 1 row
		}
		unset ($prepstatement);
	}

//show the header
	require_once "includes/header.php";

//show the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "      <br>";


	echo "<form method='post' name='frm' action=''>\n";

	echo "<div align='center'>\n";
	echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";

	echo "<tr>\n";
	if ($action == "add") {
		echo "<td align='left' width='30%' nowrap><b>Module Add</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align='left' width='30%' nowrap><b>Module Update</b></td>\n";
	}
	echo "<td width='70%' align='right'><input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_modules.php'\" value='Back'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Label:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='modulelabel' maxlength='255' value=\"$modulelabel\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Module Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='modulename' maxlength='255' value=\"$modulename\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Description:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='moduledesc' maxlength='255' value=\"$moduledesc\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Module Category:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	$tablename = 'v_modules';$fieldname = 'modulecat';$sqlwhereoptional = "where v_id = '$v_id'";$fieldcurrentvalue = $modulecat;
	echo htmlselectother($db, $tablename, $fieldname, $sqlwhereoptional, $fieldcurrentvalue);
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Enabled:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='moduleenabled'>\n";
	echo "    <option value=''></option>\n";
	if ($moduleenabled == "true") { 
		echo "    <option value='true' SELECTED >true</option>\n";
	}
	else {
		echo "    <option value='true'>true</option>\n";
	}
	if ($moduleenabled == "false") { 
		echo "    <option value='false' SELECTED >false</option>\n";
	}
	else {
		echo "    <option value='false'>false</option>\n";
	}
	echo "    </select>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Default Enabled:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='moduledefaultenabled'>\n";
	echo "    <option value=''></option>\n";
	if ($moduledefaultenabled == "true") { 
		echo "    <option value='true' SELECTED >true</option>\n";
	}
	else {
		echo "    <option value='true'>true</option>\n";
	}
	if ($moduledefaultenabled == "false") { 
		echo "    <option value='false' SELECTED >false</option>\n";
	}
	else {
		echo "    <option value='false'>false</option>\n";
	}
	echo "    </select>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	if ($action == "update") {
		echo "				<input type='hidden' name='module_id' value='$module_id'>\n";
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


require_once "includes/footer.php";
?>
