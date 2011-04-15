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
if (ifgroup("superadmin")) {
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

//show the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"center\">\n";
	echo "      <br>";


	echo "<table width='100%' border='0'>\n";
	echo "<tr>\n";
	echo "<td align='left' width='50%' nowrap><b>System Settings</b></td>\n";
	echo "<td align='left' width='50%' align='right'>&nbsp;</td>\n";
	echo "</tr>\n";
	echo "</tr></table>\n";


	$sql = "";
	$sql .= " select * from v_system_settings ";
	if (strlen($orderby)> 0) { $sql .= "order by $orderby $order "; }
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$numrows = count($result);
	unset ($prepstatement, $result, $sql);

	$rowsperpage = 150;
	$param = "";
	$page = $_GET['page'];
	if (strlen($page) == 0) { $page = 0; $_GET['page'] = 0; } 
	list($paging_controls, $rows_per_page, $tmp) = paging($numrows, $param, $rowsperpage); 
	$offset = $rows_per_page * $page; 

	$sql = "";
	$sql .= " select * from v_system_settings ";
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

	echo "<div align='center'>\n";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr>\n";
	echo thorderby('v_domain', 'Domain', $orderby, $order);
	//echo thorderby('v_package_version', 'Package Version', $orderby, $order);
	echo thorderby('v_label', 'Label', $orderby, $order);
	//echo thorderby('v_name', 'Name', $orderby, $order);
	//echo thorderby('v_dir', 'Directory', $orderby, $order);
	echo "<th width='40%'>Description</th>\n";
	echo "<td align='right' width='42'>\n";
	echo "	<a href='v_system_settings_edit.php' alt='add'>$v_link_label_add</a>\n";
	echo "</td>\n";
	echo "<tr>\n";

	if ($resultcount == 0) {
		//no results found
	}
	else {
		//received results
		foreach($result as $row) {
			if (strlen($row['v_server_port']) == 0) { $row['v_server_port'] = '80'; }
			switch ($row['v_server_port']) {
				case "80":
					$url = strtolower($row['v_server_protocol']).'://'.$row['v_domain'];
					break;
				case "443":
					$url = strtolower($row['v_server_protocol']).'://'.$row['v_domain'];
					break;
				default:
					$url = strtolower($row['v_server_protocol']).'://'.$row['v_domain'].':'.$row['v_server_port'];
					break;
			}
			echo "<tr >\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'><a href='".$url."'>".$row['v_domain']."</a></td>\n";
			//echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row['v_package_version']."</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row['v_label']."</td>\n";
			//echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row['v_name']."</td>\n";
			//echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row['v_dir']."</td>\n";
			echo "	<td valign='top' class='rowstylebg'>".$row['v_description']."</td>\n";
			echo "	<td valign='top' align='right'>\n";
			echo "		<a href='v_system_settings_edit.php?id=".$row['v_id']."' alt='edit'>$v_link_label_edit</a>\n";
			echo "		<a href='v_system_settings_delete.php?id=".$row['v_id']."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $rowcount);
	} //end if results

	echo "<tr>\n";
	echo "<td colspan='7'>\n";
	echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='center' nowrap>$pagingcontrols</td>\n";
	echo "		<td width='33.3%' align='right'>\n";
	echo "			<a href='v_system_settings_edit.php' alt='add'>$v_link_label_add</a>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "</table>";
	echo "</div>";
	echo "<br><br>";
	echo "<br><br>";

	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</div>";
	echo "<br><br>";

//show the footer
	require_once "includes/footer.php";
?>
