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

$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
if (strlen($_GET["a"]) > 0) {
	if ($_GET["a"] == "stop") {
		$module_name = $_GET["m"];
		if ($fp) {
			$cmd = "api unload $module_name";
			$response = trim(event_socket_request($fp, $cmd));
			$msg = '<strong>Unload Module:</strong><pre>'.$response.'</pre>';
		}
	}
	if ($_GET["a"] == "start") {
		$module_name = $_GET["m"];
		if ($fp) {
			$cmd = "api load $module_name";
			$response = trim(event_socket_request($fp, $cmd));
			$msg = '<strong>Load Module:</strong><pre>'.$response.'</pre>';
		}
	}
}

if (!function_exists('switch_module_active')) {
	function switch_module_active($module_name) {
		$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
		if ($fp) {
			$cmd = "api module_exists $module_name";
			$response = trim(event_socket_request($fp, $cmd));
			if ($response == "true") {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}
}

//show the msg
	if ($msg) {
		echo "<div align='center'>\n";
		echo "<table width='40%'>\n";
		echo "<tr>\n";
		echo "<th align='left'>Message</th>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td class='rowstyle1'><strong>$msg</strong></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
	}

//show the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"center\">\n";
	echo "      <br>";


	echo "<table width='100%' border='0'><tr>\n";
	echo "<td align='left' width='50%' nowrap><b>Module List</b></td>\n";
	echo "<td align='left' width='50%' align='right'>&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align='left'>\n";
	echo "Modules extend the features of the system. Use this page to enable or disable modules. ";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";


	$sql = "";
	$sql .= " select * from v_modules ";
	$sql .= "where v_id = '1' ";
	if (strlen($orderby)> 0) { $sql .= "order by $orderby $order "; }
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$numrows = count($result);
	unset ($prepstatement, $result, $sql);

	$rowsperpage = 200;
	$param = "";
	$page = $_GET['page'];
	if (strlen($page) == 0) { $page = 0; $_GET['page'] = 0; } 
	list($pagingcontrols, $rowsperpage, $var3) = paging($numrows, $param, $rowsperpage); 
	$offset = $rowsperpage * $page; 

	$sql = "";
	$sql .= " select * from v_modules ";
	$sql .= "where v_id = '1' ";
    if (strlen($orderby)> 0) { 
		$sql .= "order by $orderby $order "; 
	}
	else {
		$sql .= "order by modulecat,  modulelabel"; 
	}
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

	$tmp_module_header = "\n";
	$tmp_module_header .= "<tr>\n";
	//$tmp_module_header .= thorderby('modulecat', 'Module Category', $orderby, $order);
	$tmp_module_header .= thorderby('modulelabel', 'Label', $orderby, $order);
	//$tmp_module_header .= thorderby('modulename', 'Module Name', $orderby, $order);
	$tmp_module_header .= thorderby('moduledesc', 'Description', $orderby, $order);
	$tmp_module_header .= "<th>Status</th>\n";
	$tmp_module_header .= "<th>Action</th>\n";
	$tmp_module_header .= thorderby('moduleenabled', 'Enabled', $orderby, $order);
	//$tmp_module_header .= thorderby('moduledefaultenabled', 'Default Enabled', $orderby, $order);
	$tmp_module_header .= "<td align='right' width='42'>\n";
	$tmp_module_header .= "	<a href='v_modules_edit.php' alt='add'>$v_link_label_add</a>\n";
	$tmp_module_header .= "</td>\n";
	$tmp_module_header .= "<tr>\n";

	if ($resultcount == 0) { //no results
	}
	else { //received results
		$prevmodulecat = '';
		foreach($result as $row) {
			if ($prevmodulecat != $row["modulecat"]) {
				$c=0;
				if (strlen($prevmodulecat) > 0) {
					echo "<tr>\n";
					echo "<td colspan='6'>\n";
					echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
					echo "	<tr>\n";
					echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
					echo "		<td width='33.3%' align='center' nowrap>&nbsp;</td>\n";
					echo "		<td width='33.3%' align='right'>\n";
					echo "			<a href='v_modules_edit.php' alt='add'>$v_link_label_add</a>\n";
					echo "		</td>\n";
					echo "	</tr>\n";
					echo "	</table>\n";
					echo "</td>\n";
					echo "</tr>\n";
				}

				echo "<tr><td colspan='4' align='left'>\n";
				echo "	<br />\n";
				echo "	<br />\n";
				echo "	<b>".$row["modulecat"]."</b>&nbsp;</td></tr>\n";
				echo $tmp_module_header;
			}

			echo "<tr >\n";
			//echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row["modulecat"]."</td>\n";
			echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row["modulelabel"]."</td>\n";
			//echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row["modulename"]."</td>\n";
			echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row["moduledesc"]."&nbsp;</td>\n";
			if (switch_module_active($row["modulename"])) {
				echo "   <td valign='top' class='".$rowstyle[$c]."'>Running</td>\n";
				echo "   <td valign='top' class='".$rowstyle[$c]."'><a href='v_modules.php?a=stop&m=".$row["modulename"]."' alt='stop'>Stop</a></td>\n";
			}
			else {
				if ($row['moduleenabled']=="true") {
					echo "   <td valign='top' class='".$rowstyle[$c]."'><b>Stopped</b></td>\n";
				}
				else {
					echo "   <td valign='top' class='".$rowstyle[$c]."'>Stopped $notice</td>\n";
				}
				echo "   <td valign='top' class='".$rowstyle[$c]."'><a href='v_modules.php?a=start&m=".$row["modulename"]."' alt='start'>Start</a></td>\n";
			}
			echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row["moduleenabled"]."</td>\n";
			//echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row["moduledefaultenabled"]."</td>\n";
			echo "   <td valign='top' align='right'>\n";
			echo "		<a href='v_modules_edit.php?id=".$row["module_id"]."' alt='edit'>$v_link_label_edit</a>\n";
			echo "		<a href='v_modules_delete.php?id=".$row["module_id"]."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
			echo "   </td>\n";
			echo "</tr>\n";

			$prevmodulecat = $row["modulecat"];
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
	echo "			<a href='v_modules_edit.php' alt='add'>$v_link_label_add</a>\n";
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
