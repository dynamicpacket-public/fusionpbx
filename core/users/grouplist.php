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

//check the permissions
	require_once "includes/checkauth.php";
	if (ifgroup("admin") || ifgroup("superadmin")) {
		//access allowed
	}
	else {
		echo "access denied";
		return;
	}

//show the header
	require_once "includes/header.php";

//show the content
	echo "<div class='' style='padding:0px;'>\n";
	echo "<table width='100%'>";
	echo "<td>";

	echo "<table width='100%' border='0'><tr>";
	echo "<td width='50%'><b>Group List</b></td>";
	echo "<td width='50%' align='right'>";
	if (permission_exists('user_view')) {
		echo "  <input type='button' class='btn' onclick=\"window.location='index.php'\" value='User Manager'>";
	}
	echo "</td>\n";
	echo "</tr></table>";

	$sql = "SELECT * FROM v_groups ";
	$sql .= "where v_id = '$v_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();

	$c = 0;
	$rowstyle["0"] = "rowstyle0";
	$rowstyle["1"] = "rowstyle1";

	$strlist = "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
	$strlist .= "<tr class='border'>\n";
	$strlist .= "	<th align=\"left\" nowrap> &nbsp; Group ID &nbsp; </th>\n";
	$strlist .= "	<th align=\"left\" nowrap> &nbsp; Group Description &nbsp; </th>\n";
	$strlist .= "	<th align=\"center\" nowrap>&nbsp;</th>\n";

	$strlist .= "	<td width='22px' align=\"right\" nowrap>\n";
	if (permission_exists('group_add')) {
		$strlist .= "	<a href='groupadd.php' alt='add'>$v_link_label_add</a>\n";
	}
	$strlist .= "	</td>\n";
	$strlist .= "</tr>\n";

	$count = 0;
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$id = $row["id"];
		$groupid = $row["groupid"];
		$groupdesc = $row["groupdesc"];
		if (strlen($groupid) == 0) { $groupid = "&nbsp;"; }
		if (strlen($groupdesc) == 0) { $groupdesc = "&nbsp;"; }
		$groupdesc = wordwrap($groupdesc, 50, "<br />\n");

		if (!ifgroup("superadmin") && $groupid == "superadmin") {
			//hide the superadmin group from non superadmin's
		}
		else {
			$strlist .= "<tr>";
			$strlist .= "<td class='".$rowstyle[$c]."' align=\"left\" class='' nowrap> &nbsp; $groupid &nbsp; </td>\n";
			$strlist .= "<td class='".$rowstyle[$c]."' align=\"left\" class='' nowrap> &nbsp;  $groupdesc &nbsp; </td>\n";

			$strlist .= "<td class='".$rowstyle[$c]."' align=\"center\" nowrap>\n";
			if (permission_exists('group_add') || ifgroup("superadmin")) {
				$strlist .= "&nbsp;<a class='' href='v_group_permissions.php?group_id=$groupid' title='Group Permissions'>Permissions</a>&nbsp;&nbsp;";
			}
			if (permission_exists('group_member_view') || ifgroup("superadmin")) {
				$strlist .= "&nbsp;<a class='' href='groupmembers.php?groupid=$groupid' title='Group Members'>Members</a>&nbsp;";
			}
			$strlist .= "</td>\n";

			$strlist .= "<td align=\"right\" nowrap>\n";
			$strlist .= "<a href='groupdelete.php?id=$id' onclick=\"return confirm('Do you really want to delete this?')\" alt='delete'>$v_link_label_delete</a>\n";

			$strlist .= "</td>\n";
			$strlist .= "</tr>\n";
		}
		if ($c==0) { $c=1; } else { $c=0; }
		$count++;
	}

	$strlist .= "<tr>\n";
	$strlist .= "<td colspan='4' align='right' height='20'>\n";
	if (permission_exists('group_add')) {
		$strlist .= "	<a href='groupadd.php' alt='add'>$v_link_label_add</a>\n";
	}
	$strlist .= "</td>\n";
	$strlist .= "</tr>\n";

	$strlist .= "</table>\n";
	if ($count > 0) {
		echo $strlist;
	}

	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "<br>";
	echo "</div>";

//show the footer
	require_once "includes/footer.php";

?>