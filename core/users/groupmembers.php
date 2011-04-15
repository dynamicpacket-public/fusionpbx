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
require_once "includes/header.php";

if (ifgroup("admin") || ifgroup("superadmin")) {
	//access allowed
}
else {
	echo "access denied";
	return;
}

//requires a superadmin to view members of the superadmin group
	if (!ifgroup("superadmin") && $_GET["groupid"] == "superadmin") {
		echo "access denied";
		return;
	}

//HTTP GET set to a variable
	$groupid = $_GET["groupid"];

function ifgroupmembers($db, $groupid, $username) {
	$sql = "select * from v_group_members ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and groupid = '$groupid' ";
	$sql .= "and username = '$username' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	if (count($prepstatement->fetchAll()) == 0) { return true; } else { return false; }
	unset ($sql, $prepstatement);
}
//$exampledatareturned = example("apples", 1);


$c = 0;
$rowstyle["0"] = "rowstyle0";
$rowstyle["1"] = "rowstyle1";

echo "<div align='center'>\n";
echo "<table width='90%' border='0'><tr><td align='left'>\n";
echo "<span  class=\"\" height='50'>Member list for <b>$groupid</b></span><br /><br />\n";

$sql = "SELECT * FROM v_group_members ";
$sql .= "where v_id = '$v_id' ";
$sql .= "and groupid = '$groupid' ";
$prepstatement = $db->prepare(check_sql($sql));
$prepstatement->execute();

$strlist = "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";

$strlist .= "<tr>\n";
$strlist .= "	<th align=\"left\" nowrap> &nbsp; Username &nbsp; </th>\n";
$strlist .= "	<th align=\"left\" nowrap> &nbsp; &nbsp; </th>\n";
$strlist .= "	<td width='22' align=\"right\" nowrap>\n";
$strlist .= "		&nbsp;\n";
$strlist .= "	</td>\n";
$strlist .= "</tr>\n";

$count = 0;
$result = $prepstatement->fetchAll();
foreach ($result as &$row) {
	$id = $row["id"];
	$username = $row["username"];
	$strlist .= "<tr'>";
	$strlist .= "<td align=\"left\"  class='".$rowstyle[$c]."' nowrap> &nbsp; $username &nbsp; </td>\n";
	$strlist .= "<td align=\"left\"  class='".$rowstyle[$c]."' nowrap> &nbsp; </td>\n";
	$strlist .= "<td align=\"right\" nowrap>\n";
	$strlist .= "	<a href='groupmemberdelete.php?username=$username&groupid=$groupid' onclick=\"return confirm('Do you really want to delete this?')\" alt='delete'>$v_link_label_delete</a>\n";
	$strlist .= "</td>\n";
	$strlist .= "</tr>\n";

	if ($c==0) { $c=1; } else { $c=0; }
	$count++;
}


$strlist .= "</table>\n";
echo $strlist;


echo "</td>";
echo "</tr>";
echo "</table>";
echo "<br>";


echo "  <div align='center'>";
echo "  <form method='post' action='groupmemberadd.php'>";
echo "  <table width='250'>";
echo "	<tr>";
echo "		<td width='60%' align='right'>";

//---- Begin Select List --------------------
$sql = "SELECT * FROM v_users ";
$sql .= "where v_id = '$v_id' ";
$sql .= "order by username ";
$prepstatement = $db->prepare(check_sql($sql));
$prepstatement->execute();

echo "<select name=\"username\" style='width: 200px;' class='formfld'>\n";
echo "<option value=\"\"></option>\n";
$result = $prepstatement->fetchAll();
//$catcount = count($result);
foreach($result as $field) {
	$username = $field[username];
	if (ifgroupmembers($db, $groupid, $username)) {
		echo "<option value='".$field[username]."'>".$field[username]."</option>\n";
	}
}
echo "</select>";
unset($sql, $result);
//---- End Select List --------------------

echo "		</td>";
echo "		<td align='right'>";
echo "          <input type='hidden' name='groupid' value='$groupid'>";
echo "          <input type='submit' class='btn' value='Add Member'>";
echo "      </td>";
echo "	</tr>";
echo "  </table>";
echo "  </form>";
echo "  </div>";

echo "<br><br>";



require_once "includes/footer.php";
?>

