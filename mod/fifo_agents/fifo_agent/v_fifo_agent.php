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
	Copyright (C) 2010
	All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/
require_once "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
if (ifgroup("agent") || ifgroup("admin") || ifgroup("superadmin")) {
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
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";
	echo "<tr class='border'>\n";
	echo "	<td align=\"center\">\n";
	echo "		<br>";


	echo "<table width='100%' border='0'>\n";
	echo "<tr>\n";
	echo "<td width='50%' nowrap='nowrap' align='left'><b>Active Agents</b></td>\n";
	echo "<td width='50%' align='right'>&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align='left'>\n";
	echo "Shows the agents that are currently logged into the queues.<br /><br />\n";
	echo "</td>\n";
	echo "<td>\n";
	echo "	<a href='/mod/fifo_agents/v_fifo_agent_login.php'>login</a>";
	echo "	<a href='/mod/fifo_agents/v_fifo_agent_logout.php'>logout</a>";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</tr></table>\n";


	$sql = "";
	$sql .= " select * from v_fifo_agents ";
	$sql .= " where agent_username = '".$_SESSION["username"]."' ";
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
	$sql .= " select * from v_fifo_agents ";
	$sql .= " where agent_username = '".$_SESSION["username"]."' ";
	if (strlen($orderby)> 0) { $sql .= "order by $orderby $order "; }
	//$sql .= " limit $rowsperpage offset $offset ";
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
	echo thorderby('fifo_name', 'Queue Name', $orderby, $order);
	echo thorderby('agent_username', 'Username', $orderby, $order);
	echo thorderby('agent_priority', 'Agent Priority', $orderby, $order);
	echo thorderby('agent_status', 'Status', $orderby, $order);
	echo thorderby('agent_last_call', 'Last Call', $orderby, $order);
	echo thorderby('agent_contact_number', 'Contact Number', $orderby, $order);
	echo "<td align='right' width='42'>\n";
	//echo "	<a href='v_fifo_agents_edit.php' alt='add'>$v_link_label_add</a>\n";
	echo "</td>\n";
	echo "<tr>\n";

	if ($resultcount == 0) { //no results
	}
	else { //received results
		foreach($result as $row) {
			//set the php variables
				$agent_last_call = $row[agent_last_call];
				date_default_timezone_set('America/Boise');

			//format the last call time
				$agent_last_call_desc = date("g:i:s a j M Y",$agent_last_call);

			//get the agent status description
				$x=1;
				$agent_status_desc = '';
				foreach($_SESSION["array_agent_status"] as $value) {
					if ($row[agent_status] == $x) {
						$agent_status_desc = $value;
					}
					$x++;
				}
			echo "<tr >\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row[fifo_name]."</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row[agent_username]."</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row[agent_priority]."</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$agent_status_desc."</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$agent_last_call_desc."</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row[agent_contact_number]."</td>\n";
			echo "	<td valign='top' align='right'>\n";
			echo "		<a href='v_fifo_agent_edit.php?id=".$row[fifo_agent_id]."' alt='edit'>$v_link_label_edit</a>\n";
			//echo "		<a href='v_fifo_agents_delete.php?id=".$row[fifo_agent_id]."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $rowcount);
	} //end if results


	echo "<tr>\n";
	echo "<td colspan='7' align='left'>\n";
	echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='center' nowrap>$pagingcontrols</td>\n";
	echo "		<td width='33.3%' align='right'>\n";
	//echo "			<a href='v_fifo_agents_edit.php' alt='add'>$v_link_label_add</a>\n";
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


require_once "includes/footer.php";
unset ($resultcount);
unset ($result);
unset ($key);
unset ($val);
unset ($c);
?>
