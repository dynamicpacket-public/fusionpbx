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
if (permission_exists('menu_add') || permission_exists('menu_edit') || permission_exists('menu_delete')) {
	//access granted
}
else {
	echo "access denied";
	return;
}

//include the header
	require_once "includes/header.php";

//delete the group from the user
	if ($_REQUEST["a"] == "delete" && permission_exists("menu_delete")) {
		//delete the group from the users
			$sql = "delete from v_menu_groups  ";
			$sql .= "where v_id = '$v_id' ";
			$sql .= "and group_id = '".$_REQUEST['group_id']."' ";
			$sql .= "and menu_group_id = '".$_REQUEST['id']."' ";
			$db->exec(check_sql($sql));
	}

//add a group to the menu
	if (strlen($_REQUEST["group_id"]) > 0 && permission_exists('menu_add')) {
		//add the group to the menu
			if (strlen($_REQUEST["menu_guid"]) > 0 && strlen($_REQUEST["group_id"]) > 0) {
				$sqlinsert = "insert into v_menu_groups ";
				$sqlinsert .= "(";
				$sqlinsert .= "v_id, ";
				$sqlinsert .= "menu_guid, ";
				$sqlinsert .= "group_id ";
				$sqlinsert .= ")";
				$sqlinsert .= "values ";
				$sqlinsert .= "(";
				$sqlinsert .= "'$v_id', ";
				$sqlinsert .= "'".$_REQUEST['menu_guid']."', ";
				$sqlinsert .= "'".$_REQUEST['group_id']."' ";
				$sqlinsert .= ")";
				$db->exec($sqlinsert);
			}
	}

//action add or update
	if (isset($_REQUEST["menuid"])) {
		$action = "update";
		$menuid = check_str($_REQUEST["menuid"]);
	}
	else {
		$action = "add";
	}

//clear the menu session so it will rebuild with the update
	$_SESSION["menu"] = "";

//get the HTTP POST variables and set them as PHP variables
	if (count($_POST)>0) {
		$menuid = check_str($_POST["menuid"]);
		$menutitle = check_str($_POST["menutitle"]);
		$menustr = check_str($_POST["menustr"]);
		$menucategory = check_str($_POST["menucategory"]);
		$menudesc = check_str($_POST["menudesc"]);
		$menu_protected = check_str($_POST["menu_protected"]);
		//$menu_guid = check_str($_POST["menu_guid"]);
		$menu_parent_guid = check_str($_POST["menu_parent_guid"]);
		$menuorder = check_str($_POST["menuorder"]);
	}

//when a HTTP POST is available then process it
	if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

		if ($action == "update") {
			$menuid = check_str($_POST["menuid"]);
		}

		//check for all required data
			$msg = '';
			if (strlen($menutitle) == 0) { $msg .= "Please provide: title<br>\n"; }
			if (strlen($menucategory) == 0) { $msg .= "Please provide: category<br>\n"; }
			//if (strlen($menustr) == 0) { $msg .= "Please provide: menustr<br>\n"; }
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
			if ($action == "add" && permission_exists('menu_add')) {
				$sql = "SELECT menuorder FROM v_menu ";
				$sql .= "where v_id = '$v_id' ";
				$sql .= "and menu_parent_guid  = '$menu_parent_guid' ";
				$sql .= "order by menuorder desc ";
				$sql .= "limit 1 ";
				$prepstatement = $db->prepare(check_sql($sql));
				$prepstatement->execute();
				$result = $prepstatement->fetchAll();
				foreach ($result as &$row) {
					$highestmenuorder = $row[menuorder];
				}
				unset($prepstatement);

				$sql = "insert into v_menu ";
				$sql .= "(";
				$sql .= "v_id, ";
				$sql .= "menutitle, ";
				$sql .= "menustr, ";
				$sql .= "menucategory, ";
				$sql .= "menudesc, ";
				$sql .= "menu_protected, ";
				$sql .= "menu_guid, ";
				$sql .= "menu_parent_guid, ";
				$sql .= "menuorder, ";
				$sql .= "menuadduser, ";
				$sql .= "menuadddate ";
				$sql .= ")";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'$v_id', ";
				$sql .= "'$menutitle', ";
				$sql .= "'$menustr', ";
				$sql .= "'$menucategory', ";
				$sql .= "'$menudesc', ";
				$sql .= "'$menu_protected', ";
				$sql .= "'".guid()."', ";
				$sql .= "'$menu_parent_guid', ";
				$sql .= "'".($highestmenuorder+1)."', ";
				$sql .= "'".$_SESSION["username"]."', ";
				$sql .= "now() ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql);

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=menu_list.php\">\n";
				echo "<div align='center'>\n";
				echo "Add Complete\n";
				echo "</div>\n";
				require_once "includes/footer.php";
				return;
			}

			if ($action == "update" && permission_exists('menu_edit')) {
				$sql  = "update v_menu set ";
				$sql .= "menutitle = '$menutitle', ";
				$sql .= "menustr = '$menustr', ";
				$sql .= "menucategory = '$menucategory', ";
				$sql .= "menudesc = '$menudesc', ";
				$sql .= "menu_protected = '$menu_protected', ";
				$sql .= "menu_parent_guid = '$menu_parent_guid', ";
				$sql .= "menuorder = '$menuorder', ";
				$sql .= "menumoduser = '".$_SESSION["username"]."', ";
				$sql .= "menumoddate = now() ";
				$sql .= "where v_id = '$v_id' ";
				$sql .= "and menuid = '$menuid' ";
				$count = $db->exec(check_sql($sql));

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=menu_edit.php?id=$id&menuid=".$_REQUEST['menuid']."&menu_parent_guid=".$_REQUEST['menu_parent_guid']."\">\n";
				echo "<div align='center'>\n";
				echo "Edit Complete\n";
				echo "</div>\n";
				require_once "includes/footer.php";
				return;
			}
		} //if ($_POST["persistformvar"] != "true")
	} //(count($_POST)>0 && strlen($_POST["persistformvar"]) == 0)

//pre-populate the form
	if (count($_GET)>0 && $_POST["persistformvar"] != "true") {
		$menuid = $_GET["menuid"];

		$sql = "";
		$sql .= "select * from v_menu ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and menuid = '$menuid' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$menu_guid = $row["menu_guid"];
			$menutitle = $row["menutitle"];
			$menustr = $row["menustr"];
			$menucategory = $row["menucategory"];
			$menudesc = $row["menudesc"];
			$menu_protected = $row["menu_protected"];
			$menu_parent_guid = $row["menu_parent_guid"];
			$menuorder = $row["menuorder"];
			$menuadduser = $row["menuadduser"];
			$menuadddate = $row["menuadddate"];
			//$menudeluser = $row["menudeluser"];
			//$menudeldate = $row["menudeldate"];
			$menumoduser = $row["menumoduser"];
			$menumoddate = $row["menumoddate"];
			break; //limit to 1 row
		}
	}

//show the content
	require_once "includes/header.php";
	echo "<div align='center'>";
	echo "<table width='90%' border='0' cellpadding='0' cellspacing='2'>\n";
	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "		<br>";

	echo "<form method='post' action=''>";
	echo "<table width='100%' cellpadding='6' cellspacing='0'>";

	echo "<tr>\n";
	echo "<td width='30%' align='left' valign='top' nowrap><b>Menu Edit</b></td>\n";
	echo "<td width='70%' align='right' valign='top'><input type='button' class='btn' name='' alt='back' onclick=\"window.location='menu_list.php'\" value='Back'><br /><br /></td>\n";
	echo "</tr>\n";

	echo "	<tr>";
	echo "		<td class='vncellreq'>Title:</td>";
	echo "		<td class='vtable'><input type='text' class='formfld' name='menutitle' value='$menutitle'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class='vncellreq'>Link:</td>";
	echo "		<td class='vtable'><input type='text' class='formfld' name='menustr' value='$menustr'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class='vncellreq'>Category:</td>";
	echo "		<td class='vtable'>";
	echo "            <select name=\"menucategory\" class='formfld'>\n";
	echo "            <option value=\"\"></option>\n";
	if ($menucategory == "internal") { echo "<option value=\"internal\" selected>internal</option>\n"; } else { echo "<option value=\"internal\">internal</option>\n"; }
	if ($menucategory == "external") { echo "<option value=\"external\" selected>external</option>\n"; } else { echo "<option value=\"external\">external</option>\n"; }
	if ($menucategory == "email") { echo "<option value=\"email\" selected>email</option>\n"; } else { echo "<option value=\"email\">email</option>\n"; }
	echo "            </select>";
	echo "        </td>";
	echo "	</tr>";

	echo "	<tr>";
	echo "		<td class='vncell'>Parent Menu:</td>";
	echo "		<td class='vtable'>";
	$sql = "SELECT * FROM v_menu ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "order by menutitle asc ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	echo "<select name=\"menu_parent_guid\" class='formfld'>\n";
	echo "<option value=\"\"></option>\n";
	$result = $prepstatement->fetchAll();
	foreach($result as $field) {
			if ($menu_parent_guid == $field['menu_guid']) {
				echo "<option value='".$field['menu_guid']."' selected>".$field['menutitle']."</option>\n";
			}
			else {
				echo "<option value='".$field['menu_guid']."'>".$field['menutitle']."</option>\n";
			}
	}
	echo "</select>";
	unset($sql, $result);
	echo "        </td>";
	echo "	</tr>";

	echo "	<tr>";
	echo "		<td class='vncell' valign='top'>Groups:</td>";
	echo "		<td class='vtable'>";

	echo "<table width='52%'>\n";
	$sql = "SELECT * FROM v_menu_groups ";
	$sql .= "where v_id=:v_id ";
	$sql .= "and menu_guid=:menu_guid ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->bindParam(':v_id', $v_id);
	$prepstatement->bindParam(':menu_guid', $menu_guid);
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$resultcount = count($result);
	foreach($result as $field) {
		if (strlen($field['group_id']) > 0) {
			echo "<tr>\n";
			echo "	<td class='vtable'>".$field['group_id']."</td>\n";
			echo "	<td>\n";
			if (permission_exists('group_member_delete') || ifgroup("superadmin")) {
				echo "		<a href='menu_edit.php?id=".$field['menu_group_id']."&group_id=".$field['group_id']."&menuid=".$menuid."&menu_parent_guid=".$menu_parent_guid."&a=delete' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
			}
			echo "	</td>\n";
			echo "</tr>\n";
		}
	}
	echo "</table>\n";

	echo "<br />\n";
	$sql = "SELECT * FROM v_groups ";
	$sql .= "where v_id = '".$v_id."' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	echo "<select name=\"group_id\" class='frm'>\n";
	echo "<option value=\"\"></option>\n";
	$result = $prepstatement->fetchAll();
	foreach($result as $field) {
		if ($field['groupid'] == "superadmin") {
			//only show the superadmin group to other users in the superadmin group
			if (ifgroup("superadmin")) {
				echo "<option value='".$field['groupid']."'>".$field['groupid']."</option>\n";
			}
		}
		else {
			echo "<option value='".$field['groupid']."'>".$field['groupid']."</option>\n";
		}
	}
	echo "</select>";
	echo "<input type=\"submit\" class='btn' value=\"Add\">\n";
	unset($sql, $result);
	echo "		</td>";
	echo "	</tr>";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Protected:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='menu_protected'>\n";
	echo "    <option value=''></option>\n";
	if ($menu_protected == "true") { 
		echo "    <option value='true' selected='selected' >true</option>\n";
	}
	else {
		echo "    <option value='true'>true</option>\n";
	}
	if ($menu_protected == "false") { 
		echo "    <option value='false' selected='selected' >false</option>\n";
	}
	else {
		echo "    <option value='false'>false</option>\n";
	}
	echo "    </select><br />\n";
	echo "Protect this item in the menu so that is is not removed by 'Restore Default.'<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	if ($action == "update") {
		echo "	<tr>";
		echo "		<td class='vncell'>Menu Order:</td>";
		echo "		<td class='vtable'><input type='text' class='formfld' name='menuorder' value='$menuorder'></td>";
		echo "	</tr>";
		//echo "	<tr>";
		//echo "		<td class='vncell'>Added By:</td>";
		//echo "		<td class='vtable'>$menuadduser &nbsp;</td>";
		//echo "	</tr>";
		//echo "	<tr>";
		//echo "		<td class='vncell'>Add Date:</td>";
		//echo "		<td class='vtable'>$menuadddate &nbsp;</td>";
		//echo "	</tr>";
		//echo "	<tr>";
		//echo "		<td class='vncell'>Menudeluser:</td>";
		//echo "		<td><input type='text' name='menudeluser' value='$menudeluser'></td>";
		//echo "	</tr>";
		//echo "	<tr>";
		//echo "		<td class='vncell'>Menudeldate:</td>";
		//echo "		<td><input type='text' name='menudeldate' value='$menudeldate'></td>";
		//echo "	</tr>";
		//echo "	<tr>";
		//echo "		<td class='vncell'>Modified By:</td>";
		//echo "		<td class='vtable'>$menumoduser &nbsp;</td>";
		//echo "	</tr>";
		//echo "	<tr>";
		//echo "		<td class='vncell'>Modified Date:</td>";
		//echo "		<td class='vtable'>$menumoddate &nbsp;</td>";
		//echo "	</tr>";
	}

	echo "	<tr>";
	echo "		<td class='vncell'>Description:</td>";
	echo "		<td class='vtable'><input type='text' class='formfld' name='menudesc' value='$menudesc'></td>";
	echo "	</tr>";

	if (permission_exists('menu_add') || permission_exists('menu_edit')) {
		echo "	<tr>\n";
		echo "		<td colspan='2' align='right'>\n";
		echo "			<table width='100%'>";
		echo "			<tr>";
		echo "			<td align='left'>";
		echo "			</td>\n";
		echo "			<td align='right'>";
		if ($action == "update") {
			echo "				<input type='hidden' name='menuid' value='$menuid'>";
		}
		echo "				<input type='hidden' name='menu_guid' value='$menu_guid'>";
		echo "				<input type='submit' class='btn' name='submit' value='Save'>\n";
		echo "			</td>";
		echo "			</tr>";
		echo "			</table>";
		echo "		</td>";
		echo "	</tr>";
	}
	echo "</table>";
	echo "</form>";

	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

//include the footer
  require_once "includes/footer.php";
?>
