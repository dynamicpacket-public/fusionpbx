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
if (permission_exists('call_center_active_view')) {
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
	echo "		<br>";

	echo "<table width='100%' border='0'>\n";
	echo "<tr>\n";
	echo "<td width='50%' align=\"left\" nowrap=\"nowrap\"><b>Call Center Queue List</b></td>\n";
	echo "<td width='50%' align=\"right\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"left\" colspan='2'>\n";
	echo "List of queues for the call center.<br /><br />\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</tr></table>\n";

	$sql = "";
	$sql .= " select * from v_call_center_queue ";
	$sql .= " where v_id = '$v_id' ";
	if (strlen($orderby)> 0) { $sql .= "order by $orderby $order "; }
	$prep_statement = $db->prepare(check_sql($sql));
	$prep_statement->execute();
	$result = $prep_statement->fetchAll();
	$numrows = count($result);
	unset ($prep_statement, $result, $sql);
	$rows_per_page = 100;
	$param = "";
	$page = $_GET['page'];
	if (strlen($page) == 0) { $page = 0; $_GET['page'] = 0; } 
	list($paging_controls, $rows_per_page, $var3) = paging($numrows, $param, $rows_per_page); 
	$offset = $rows_per_page * $page; 

	$sql = "";
	$sql .= " select * from v_call_center_queue ";
	$sql .= " where v_id = '$v_id' ";
	if (strlen($orderby)> 0) { $sql .= "order by $orderby $order "; }
	$sql .= " limit $rows_per_page offset $offset ";
	$prep_statement = $db->prepare(check_sql($sql));
	$prep_statement->execute();
	$result = $prep_statement->fetchAll();
	$result_count = count($result);
	unset ($prep_statement, $sql);

	$c = 0;
	$row_style["0"] = "rowstyle0";
	$row_style["1"] = "rowstyle1";

	echo "<div align='center'>\n";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";

	echo "<tr>\n";
	echo thorderby('queue_name', 'Queue Name', $orderby, $order);
	echo thorderby('queue_extension', 'Extension', $orderby, $order);
	echo thorderby('queue_strategy', 'Strategy', $orderby, $order);
	//echo thorderby('queue_moh_sound', 'Music On Hold', $orderby, $order);
	//echo thorderby('queue_record_template', 'Record Template', $orderby, $order);
	//echo thorderby('queue_time_base_score', 'Time Base Score', $orderby, $order);
	//echo thorderby('queue_max_wait_time', 'Max Wait Time', $orderby, $order);
	//echo thorderby('queue_max_wait_time_with_no_agent', 'Max Wait Time With No Agent', $orderby, $order);
	//echo thorderby('queue_tier_rules_apply', 'Tier Rules Apply', $orderby, $order);
	//echo thorderby('queue_tier_rule_wait_second', 'Tier Rule Wait Second', $orderby, $order);
	//echo thorderby('queue_tier_rule_no_agent_no_wait', 'Tier Rule No Agent No Wait', $orderby, $order);
	//echo thorderby('queue_discard_abandoned_after', 'Discard Abandoned After', $orderby, $order);
	//echo thorderby('queue_abandoned_resume_allowed', 'Abandoned Resume Allowed', $orderby, $order);
	//echo thorderby('queue_tier_rule_wait_multiply_level', 'Tier Rule Wait Multiply Level', $orderby, $order);
	echo thorderby('queue_description', 'Description', $orderby, $order);
	echo "<td align='right' width='42'>\n";
	//echo "	<a href='v_call_center_queue_edit.php' alt='add'>$v_link_label_add</a>\n";
	echo "</td>\n";
	echo "<tr>\n";

	if ($result_count == 0) { //no results
	}
	else { //received results
		foreach($result as $row) {
			echo "<tr >\n";
			echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_name]."</td>\n";
			echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_extension]."</td>\n";
			echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_strategy]."</td>\n";
			//echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_moh_sound]."</td>\n";
			//echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_record_template]."</td>\n";
			//echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_time_base_score]."</td>\n";
			//echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_max_wait_time]."</td>\n";
			//echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_max_wait_time_with_no_agent]."</td>\n";
			//echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_tier_rules_apply]."</td>\n";
			//echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_tier_rule_wait_second]."</td>\n";
			//echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_tier_rule_no_agent_no_wait]."</td>\n";
			//echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_discard_abandoned_after]."</td>\n";
			//echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_abandoned_resume_allowed]."</td>\n";
			//echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_tier_rule_wait_multiply_level]."</td>\n";
			echo "	<td valign='top' class='".$row_style[$c]."'>".$row[queue_description]."&nbsp;</td>\n";
			echo "	<td valign='top' align='right'>\n";
			echo "		<a href='".PROJECT_PATH."/mod/call_center_active/v_call_center_active.php?queue_name=".$row[queue_name]."' alt='edit'>$v_link_label_edit</a>\n";
			//echo "		<a href='v_call_center_queue_delete.php?id=".$row[call_center_queue_id]."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
			//echo "		<input type='button' class='btn' name='' alt='edit' onclick=\"window.location='v_call_center_queue_edit.php?id=".$row[call_center_queue_id]."'\" value='e'>\n";
			//echo "		<input type='button' class='btn' name='' alt='delete' onclick=\"if (confirm('Are you sure you want to delete this?')) { window.location='v_call_center_queue_delete.php?id=".$row[call_center_queue_id]."' }\" value='x'>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $row_count);
	} //end if results

	echo "<tr>\n";
	echo "<td colspan='17' align='left'>\n";
	echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='center' nowrap>$paging_controls</td>\n";
	echo "		<td width='33.3%' align='right'>\n";
	//echo "			<a href='v_call_center_queue_edit.php' alt='add'>$v_link_label_add</a>\n";
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
