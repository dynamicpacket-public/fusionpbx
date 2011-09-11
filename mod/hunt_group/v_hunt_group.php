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

//check permissions
	if (permission_exists('hunt_group_view')) {
		//access granted
	}
	else {
		echo "access denied";
		exit;
	}

require_once "includes/header.php";
require_once "includes/paging.php";

$orderby = $_GET["orderby"];
$order = $_GET["order"];

echo "<div align='center'>";
echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
echo "<tr class='border'>\n";
echo "	<td align=\"center\">\n";
echo "      <br />";

echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
echo "<tr>\n";
echo "    <td align='left'><p><span class='vexpl'><span class='red'><strong>Hunt Group<br />\n";
echo "        </strong></span>\n";
echo "			A Hunt Group is a list of destinations that can be called in sequence or simultaneously.\n";
echo "        </span></p></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "<br />\n";

//get the number of rows in v_hunt_group
$sql = "";
$sql .= " select count(*) as num_rows from v_hunt_group ";
$sql .= "where v_id = '$v_id' ";
$sql .= "and huntgrouptype <> 'dnd' ";
$sql .= "and huntgrouptype <> 'call_forward' ";
$sql .= "and huntgrouptype <> 'follow_me_simultaneous' ";
$sql .= "and huntgrouptype <> 'follow_me_sequence' ";
$prepstatement = $db->prepare(check_sql($sql));
if ($prepstatement) {
	$prepstatement->execute();
	$row = $prepstatement->fetch(PDO::FETCH_ASSOC);
	if ($row['num_rows'] > 0) {
		$num_rows = $row['num_rows'];
	}
	else { 
		$num_rows = '0';
	}
}
unset($prepstatement, $result);

//prepare to page the results
$rows_per_page = 150;
$param = "";
$page = $_GET['page'];
if (strlen($page) == 0) { $page = 0; $_GET['page'] = 0; } 
list($pagingcontrols, $rows_per_page, $var3) = paging($num_rows, $param, $rows_per_page); 
$offset = $rows_per_page * $page; 

//get the hunt group list
$sql = "";
$sql .= " select * from v_hunt_group ";
$sql .= "where v_id = '$v_id' ";
$sql .= "and huntgrouptype <> 'dnd' ";
$sql .= "and huntgrouptype <> 'call_forward' ";
$sql .= "and huntgrouptype <> 'follow_me_simultaneous' ";
$sql .= "and huntgrouptype <> 'follow_me_sequence' ";
if (strlen($orderby)> 0) {
	$sql .= "order by $orderby $order ";
}
else {
	$sql .= "order by huntgroupextension asc ";
}
$sql .= " limit $rows_per_page offset $offset ";
$prepstatement = $db->prepare(check_sql($sql));
$prepstatement->execute();
$result = $prepstatement->fetchAll();
$resultcount = count($result);
unset ($prepstatement, $sql);

$c = 0;
$rowstyle["0"] = "rowstyle0";
$rowstyle["1"] = "rowstyle1";

echo "<div align='center'>\n";
echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";

echo "<tr>\n";
echo thorderby('huntgroupextension', 'Extension', $orderby, $order);
echo thorderby('huntgroupname', 'Hunt Group Name', $orderby, $order);
echo thorderby('huntgroupname', 'Enabled', $orderby, $order);
echo thorderby('huntgroupdescr', 'Description', $orderby, $order);
echo "<td align='right' width='42'>\n";
if (permission_exists('hunt_group_add')) {
	echo "	<a href='v_hunt_group_edit.php' alt='add'>$v_link_label_add</a>\n";
}
echo "</td>\n";
echo "<tr>\n";

if ($resultcount == 0) {
	//no results
}
else { //received results
	foreach($result as $row) {
		echo "<tr >\n";
		echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row['huntgroupextension']."</td>\n";
		echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row['huntgroupname']."</td>\n";
		echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row['hunt_group_enabled']."</td>\n";
		echo "   <td valign='top' class='rowstylebg' width='40%'>".$row['huntgroupdescr']."&nbsp;</td>\n";
		echo "   <td valign='top' align='right'>\n";
		if (permission_exists('hunt_group_edit')) {
			echo "		<a href='v_hunt_group_edit.php?id=".$row['hunt_group_id']."' alt='edit'>$v_link_label_edit</a>\n";
		}
		if (permission_exists('hunt_group_delete')) {
			echo "		<a href='v_hunt_group_delete.php?id=".$row['hunt_group_id']."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
		}
		echo "   </td>\n";
		echo "</tr>\n";
		if ($c==0) { $c=1; } else { $c=0; }
	} //end foreach
	unset($sql, $result, $rowcount);
} //end if results

echo "<tr>\n";
echo "<td colspan='5'>\n";
echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
echo "	<tr>\n";
echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
echo "		<td width='33.3%' align='center' nowrap>$pagingcontrols</td>\n";
echo "		<td width='33.3%' align='right'>\n";
if (permission_exists('hunt_group_add')) {
	echo "			<a href='v_hunt_group_edit.php' alt='add'>$v_link_label_add</a>\n";
}
echo "		</td>\n";
echo "	</tr>\n";
echo "	</table>\n";
echo "</td>\n";
echo "</tr>\n";

if ($v_path_show) {
	echo "<tr>\n";
	echo "<td align='left' colspan='4'>\n";
	echo "<br />\n";
	echo $v_scripts_dir."\n";
	echo "</td>\n";
	echo "</tr>\n";
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


require_once "includes/footer.php";
unset ($resultcount);
unset ($result);
unset ($key);
unset ($val);
unset ($c);
?>
