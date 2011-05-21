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
if (permission_exists('services_view')) {
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

if (strlen($_GET["a"]) > 0) {
	$service_id = $_GET["id"];
	$sql = "";
	$sql .= "select * from v_services ";
	$sql .= "where service_id = '$service_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$v_id = $row["v_id"];
		$v_service_name = $row["v_service_name"];
		$v_service_type = $row["v_service_type"];
		$v_service_data = $row["v_service_data"];
		$v_service_cmd_start = $row["v_service_cmd_start"];
		$v_service_cmd_stop = $row["v_service_cmd_stop"];
		$v_service_desc = $row["v_service_desc"];
		break; //limit to 1 row
	}
	unset ($prepstatement);

	if ($_GET["a"] == "stop") {
		$msg = 'Service: '.$v_service_name. ' stopping. ';
		shell_exec($v_service_cmd_stop);
	}
	if ($_GET["a"] == "start") {
		$msg = 'Service: '.$v_service_name. ' starting. ';
		shell_exec($v_service_cmd_start);
	}

	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"5;url=v_services.php\">\n";
	echo "<div align='center'>\n";
	echo $msg."\n";
	echo "</div>\n";
	require_once "includes/footer.php";
	return;
}

//check if a process is running
	function is_process_running($pid) {
		$status = shell_exec( 'ps -p ' . $pid );
		$status_array = explode ("\n", $status);
		if (strlen(trim($status_array[1])) > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";
	echo "<tr class='border'>\n";
	echo "	<td align=\"center\">\n";
	echo "		<br>";

	echo "<table width='100%' border='0'>\n";
	echo "<tr>\n";
	echo "<td width='50%' align='left' nowrap='nowrap'><b>Services</b></td>\n";
	echo "<td width='50%' align='right'>&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align='left' colspan='2'>\n";
	echo "Shows a list of processes, the status of the process and provides control to start and stop the process.<br /><br />\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</tr></table>\n";

	$sql = "";
	$sql .= " select * from v_services ";
	if (strlen($orderby)> 0) { $sql .= "order by $orderby $order "; }
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$numrows = count($result);
	unset ($prepstatement, $result, $sql);
	$rowsperpage = 10;
	$param = "";
	$page = $_GET['page'];
	if (strlen($page) == 0) { $page = 0; $_GET['page'] = 0; } 
	list($pagingcontrols, $rowsperpage, $var3) = paging($numrows, $param, $rowsperpage); 
	$offset = $rowsperpage * $page; 

	$sql = "";
	$sql .= " select * from v_services ";
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
	echo thorderby('v_service_name', 'Name', $orderby, $order);
	echo thorderby('v_service_desc', 'Description', $orderby, $order);
	echo "<th>Status</th>\n";
	echo "<th>Action</th>\n";
	echo "<td align='right' width='42'>\n";
	if (permission_exists('services_add')) {
		echo "	<a href='v_services_edit.php' alt='add'>$v_link_label_add</a>\n";
	}
	echo "</td>\n";
	echo "<tr>\n";

	if ($resultcount == 0) {
		//no results
	}
	else { //received results
		foreach($result as $row) {
			echo "<tr >\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row[v_service_name]."</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row[v_service_desc]."</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>\n";
			$pid = file_get_contents($row[v_service_data]);
			if (is_process_running($pid)) {
				echo "<strong>Running</strong>";
			}
			else {
				echo "<strong>Stopped</strong>";
			}
			echo "</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>\n";
			if (is_process_running($pid)) {
				echo "		<a href='v_services.php?id=".$row[service_id]."&a=stop' alt='stop'>Stop</a>";
			}
			else {
				echo "		<a href='v_services.php?id=".$row[service_id]."&a=start' alt='start'>Start</a>";
			}
			echo "</td>\n";
			echo "	<td valign='top' align='right'>\n";
			if (permission_exists('services_edit')) {
				echo "		<a href='v_services_edit.php?id=".$row[service_id]."' alt='edit'>$v_link_label_edit</a>\n";
			}
			if (permission_exists('services_delete')) {
				echo "		<a href='v_services_delete.php?id=".$row[service_id]."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
			}
			echo "	</td>\n";
			echo "</tr>\n";
			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $rowcount);
	} //end if results

	echo "<tr>\n";
	echo "<td colspan='5' align='left'>\n";
	echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='center' nowrap>$pagingcontrols</td>\n";
	echo "		<td width='33.3%' align='right'>\n";
	if (permission_exists('services_add')) {
		echo "			<a href='v_services_edit.php' alt='add'>$v_link_label_add</a>\n";
	}
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

//include the footer
	require_once "includes/footer.php";

?>
