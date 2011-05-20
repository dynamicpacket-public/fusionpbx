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
if (permission_exists('dialplan_view')) {
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
	echo "<td align=\"center\">\n";
	echo "<br />";

	echo "	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "	<tr>\n";
	echo "	<td align='left'><span class=\"vexpl\"><span class=\"red\"><strong>Dialplan\n";
	echo "		</strong></span></span>\n";
	echo "	</td>\n";
	echo "	<td align='right'>\n";
	if (permission_exists('dialplan_advanced_view')) {
		echo "		<input type='button' class='btn' value='advanced' onclick=\"document.location.href='v_dialplan.php';\">\n";
	}
	else {
		echo "&nbsp;\n";
	}
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "	<td align='left' colspan='2'>\n";
	echo "		<span class=\"vexpl\">\n";
	if (ifgroup("superadmin")) {
		echo "			The dialplan is used to setup call destinations based on conditions and context.\n";
		echo "			You can use the dialplan to send calls to gateways, auto attendants, external numbers,\n";
		echo "			to scripts, or any destination.\n";
	}
	else {
		echo "			The dialplan provides a view of some of the feature codes, as well as the IVR Menu, \n";
		echo "			Conferences, Queues and other destinations.\n";
	}
	echo "		</span>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "	</table>";

	echo "	<br />";
	echo "	<br />";

	$sql = "";
	$sql .= " select * from v_dialplan_includes ";
	$sql .= " where v_id = '$v_id' ";
	if (strlen($orderby)> 0) { $sql .= "order by $orderby $order "; } else { $sql .= "order by dialplanorder asc, extensionname asc "; }
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$numrows = count($result);
	unset ($prepstatement, $result, $sql);

	$rowsperpage = 150;
	$param = "";
	$page = $_GET['page'];
	if (strlen($page) == 0) { $page = 0; $_GET['page'] = 0; } 
	list($pagingcontrols, $rowsperpage, $var3) = paging($numrows, $param, $rowsperpage); 
	$offset = $rowsperpage * $page;

	$sql = "";
	$sql .= " select * from v_dialplan_includes ";
	$sql .= " where v_id = '$v_id' ";
	if (strlen($orderby)> 0) { $sql .= "order by $orderby $order "; } else { $sql .= "order by dialplanorder asc, extensionname asc "; }
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
	echo thorderby('extensionname', 'Name', $orderby, $order);
	echo thorderby('extension_number', 'Number', $orderby, $order);
	echo thorderby('dialplanorder', 'Order', $orderby, $order);
	echo thorderby('enabled', 'Enabled', $orderby, $order);
	echo thorderby('descr', 'Description', $orderby, $order);
	echo "<td align='right' width='42'>\n";
	if (permission_exists('dialplan_add')) {
		echo "	<a href='v_dialplan_includes_add.php' alt='add'>$v_link_label_add</a>\n";
	}
	echo "</td>\n";
	echo "<tr>\n";

	if ($resultcount == 0) { 
		//no results
	}
	else { //received results
		foreach($result as $row) {
			if (strlen($row['extension_number']) == 0) {
				$sql = "";
				$sql .= "select * from v_dialplan_includes_details ";
				$sql .= "where v_id = '$v_id' ";
				$sql .= "and dialplan_include_id = '".$row['dialplan_include_id']."' ";
				$sql .= "and fieldtype = 'destination_number' ";
				$prep_statement = $db->prepare(check_sql($sql));
				$prep_statement->execute();
				$tmp_result = $prep_statement->fetchAll();
				foreach ($tmp_result as &$tmp) {
					//prepare the extension number
						preg_match_all('/[\|0-9\*]/',$tmp["fielddata"], $tmp_match);
						$extension_number = implode("",$tmp_match[0]);
						$extension_number = str_replace("|", " ", $extension_number);
						$row['extension_number'] = $extension_number;
					//update the extension number
						$sql = "update v_dialplan_includes set ";
						$sql .= "extension_number = '$extension_number', ";
						$sql .= "where v_id = '$v_id' ";
						$sql .= "and dialplan_include_id = '".$row['dialplan_include_id']."'";
						$db->exec($sql);
						unset($sql);
					break; //limit to 1 row
				}
				unset ($prep_statement);
			}
	
			echo "<tr >\n";
			echo "   <td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row['extensionname']."</td>\n";
			echo "   <td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row['extension_number']."</td>\n";
			echo "   <td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row['dialplanorder']."</td>\n";
			echo "   <td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row['enabled']."</td>\n";
			echo "   <td valign='top' class='rowstylebg' width='30%'>".$row['descr']."&nbsp;</td>\n";
			echo "   <td valign='top' align='right'>\n";
			if (permission_exists('dialplan_add')) {
				echo "		<a href='v_dialplan_includes_edit.php?id=".$row['dialplan_include_id']."' alt='edit'>$v_link_label_edit</a>\n";
			}
			if (permission_exists('dialplan_edit')) {
				echo "		<a href='v_dialplan_includes_delete.php?id=".$row['dialplan_include_id']."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
			}
			echo "   </td>\n";
			echo "</tr>\n";
			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $rowcount);
	} //end if results


	echo "<tr>\n";
	echo "<td colspan='6'>\n";
	echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='center' nowrap>$pagingcontrols</td>\n";
	echo "		<td width='33.3%' align='right'>\n";
	if (permission_exists('dialplan_add')) {
		echo "			<a href='v_dialplan_includes_add.php' alt='add'>$v_link_label_add</a>\n";
	}
	else {
		echo "			&nbsp;";
	}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td colspan='5' align='left'>\n";
	echo "<br />\n";
	if ($v_path_show) {
		echo $v_dialplan_default_dir;
	}
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


require_once "includes/footer.php";
unset ($resultcount);
unset ($result);
unset ($key);
unset ($val);
unset ($c);
?>
