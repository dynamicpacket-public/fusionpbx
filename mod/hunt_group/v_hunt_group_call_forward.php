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
require_once "includes/paging.php";

//check permissions
if (permission_exists('hunt_group_call_forward')) {
	$orderby = $_GET["orderby"];
	$order = $_GET["order"];

	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";
	echo "<tr class='border'>\n";
	echo "	<td align=\"center\">\n";

	if ($is_included != "true") {
		echo "	<br>";
		echo "	<table width=\"100%\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";
		echo "	<tr>\n";
		echo "	<td align='left'><b>Hunt Group Call Forward</b><br>\n";
		echo "		Use the links to configure hunt group call forward.\n";
		echo "		The following hunt groups have been assigned to this user account. \n";
		echo "	</td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		echo "	<br />";
	}

	$sql = "";
	$sql .= "select * from v_hunt_group ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and huntgrouptype <> 'dnd' ";
	$sql .= "and huntgrouptype <> 'call_forward' ";
	$sql .= "and huntgrouptype <> 'follow_me_simultaneous' ";
	$sql .= "and huntgrouptype <> 'follow_me_sequence' ";
	if (!(permission_exists('hunt_group_add') || permission_exists('hunt_group_edit'))) {
		$sql .= "and hunt_group_user_list like '%|".$_SESSION["username"]."|%' ";
	}
	if (strlen($orderby)> 0) {
		$sql .= "order by $orderby $order ";
	}
	else {
		$sql .= "order by huntgroupextension asc ";
	}
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$result_count = count($result);
	unset ($prepstatement, $sql);

	$c = 0;
	$rowstyle["0"] = "rowstyle0";
	$rowstyle["1"] = "rowstyle1";

	if ($is_included == "true" && $result_count == 0) {
		//hide this when there is no result
	}
	else {
		echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "<tr>\n";
		echo "<th>Hunt Group Extension</th>\n";
		echo "<th>Tools</th>\n";
		echo "<th>Description</th>\n";
		echo "</tr>\n";
	}

	if ($result_count == 0) {
		//no results
	}
	else { //received results
		foreach($result as $row) {
			echo "<tr >\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row['huntgroupextension']."</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>\n";
			echo "		<a href='".PROJECT_PATH."/mod/hunt_group/v_hunt_group_call_forward_edit.php?id=".$row['hunt_group_id']."&a=call_forward' alt='Call Forward'>Call Forward</a> \n";
			echo "	</td>\n";
			echo "	<td valign='top' class='rowstylebg' width='40%'>".$row['huntgroupdescr']."&nbsp;</td>\n";
			echo "</tr>\n";
			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $rowcount);

	} //end if results

	if ($is_included == "true" && $result_count == 0) {
		//hide this when there is no result
	}
	else {
		echo "</table>";

		echo "<br>";
		echo "<br>";
		echo "<br>";
	}

	echo "</table>";
	echo "</div>";

	if ($is_included != "true") {
		require_once "includes/footer.php";
	}
}

?>
