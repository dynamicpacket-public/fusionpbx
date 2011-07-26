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
if (permission_exists('follow_me') || permission_exists('call_forward') || permission_exists('do_not_disturb')) {
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

	if ($is_included != "true") {
		echo "		<table width=\"100%\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";
		echo "		<tr>\n";
		echo "		<td align='left'><b>Calls</b><br>\n";
		echo "			Use the links to configure call forward follow me, or do no disturb.\n";
		echo "			The following list the extensions that have been assigned to this user account. \n";
		echo "		</td>\n";
		echo "		</tr>\n";
		echo "		</table>\n";
		echo "		<br />";
	}

	$sql = "";
	$sql .= " select * from v_extensions ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and enabled = 'true' ";
	if (!(ifgroup("admin") || ifgroup("superadmin"))) {
		$sql .= "and user_list like '%|".$_SESSION["username"]."|%' ";
	}
	if (strlen($orderby)> 0) {
		$sql .= "order by $orderby $order ";
	}
	else {
		$sql .= "order by extension asc ";
	}
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
	$sql .= " select * from v_extensions ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and enabled = 'true' ";
	if (!(ifgroup("admin") || ifgroup("superadmin"))) {
		$sql .= "and user_list like '%|".$_SESSION["username"]."|%' ";
	}
	if (strlen($orderby)> 0) {
		$sql .= "order by $orderby $order ";
	}
	else {
		$sql .= "order by extension asc ";
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

	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr>\n";
	echo "<th>Extension</th>\n";
	echo "<th>Tools</th>\n";
	echo "<th>Description</th>\n";
	echo "</tr>\n";

	if ($resultcount == 0) {
		//no results
	}
	else { //received results
		foreach($result as $row) {
			echo "<tr >\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row[extension]."</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>\n";
			if (permission_exists('call_forward')) {
				echo "		<a href='".PROJECT_PATH."/mod/calls/v_call_edit.php?id=".$row[extension_id]."&a=call_forward' alt='Call Forward'>Call Forward</a> \n";
				echo "		&nbsp;&nbsp;\n";
			}
			if (permission_exists('follow_me')) {
				echo "		<a href='".PROJECT_PATH."/mod/calls/v_call_edit.php?id=".$row[extension_id]."&a=follow_me' alt='Follow Me'>Follow Me</a> \n";
				echo "		&nbsp;&nbsp;\n";
			}
			if (permission_exists('do_not_disturb')) {
				echo "		<a href='".PROJECT_PATH."/mod/calls/v_call_edit.php?id=".$row[extension_id]."&a=do_not_disturb' alt='Do Not Disturb'>Do Not Disturb</a> \n";
			}
			echo "	</td>\n";
			echo "	<td valign='top' class='rowstylebg' width='40%'>".$row[description]."&nbsp;</td>\n";
			echo "</tr>\n";
			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $rowcount);
	} //end if results

	if (strlen($pagingcontrols) > 0) {
		echo "<tr>\n";
		echo "<td colspan='5' align='left'>\n";
		echo "	<table border='0' width='100%' cellpadding='0' cellspacing='0'>\n";
		echo "	<tr>\n";
		echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
		echo "		<td width='33.3%' align='center' nowrap>$pagingcontrols</td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "</table>";

	echo "</table>";
	echo "</div>";
	echo "<br>";
	echo "<br>";
	echo "<br>";

	if ($is_included != "true") {
		require_once "includes/footer.php";
	}


?>
