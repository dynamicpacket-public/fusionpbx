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
require "includes/config.php";
require_once "includes/checkauth.php";
if (ifgroup("user") || ifgroup("admin") || ifgroup("superadmin")) {
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

	echo "<table width=\"100%\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";
	echo "	<tr>\n";
	echo "	<td align='left'><b>Voicemail</b><br>\n";
	//echo "		Use this to configure your SIP extensions.\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<br />";


	$c = 0;
	$rowstyle["0"] = "rowstyle0";
	$rowstyle["1"] = "rowstyle1";

	echo "<div align='center'>\n";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";

	echo "<tr>\n";
	echo thorderby('extension', 'Extension', $orderby, $order);
	echo thorderby('vm_mailto', 'Voicemail Mail To', $orderby, $order);
	echo "<th>Messages</th>\n";
	echo thorderby('enabled', 'Enabled', $orderby, $order);
	echo thorderby('description', 'Description', $orderby, $order);
	//echo "<td align='right' width='42'>\n";
	//echo "	<a href='v_extensions_edit.php' alt='add'>$v_link_label_add</a>\n";
	//echo "</td>\n";
	echo "<tr>\n";


	$sql = "";
	$sql .= "select * from v_extensions ";
	$sql .= "where v_id = '$v_id' ";
	//superadmin can see all messages
	if(!ifgroup("superadmin")) {
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

	$rowsperpage = 20;
	$param = "";
	$page = $_GET['page'];
	if (strlen($page) == 0) { $page = 0; $_GET['page'] = 0; }
	list($pagingcontrols, $rowsperpage, $var3) = paging($numrows, $param, $rowsperpage); 
	$offset = $rowsperpage * $page; 

	$sql = "";
	$sql .= "select * from v_extensions ";
	$sql .= "where v_id = '$v_id' ";
	if(!ifgroup("superadmin")) {
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

	//pdo voicemail database connection
		include "includes/lib_pdo_vm.php";

	if ($resultcount == 0) { //no results
	}
	else { //received results
		foreach($result as $row) {

			$sql = "";
			$sql .= "select count(*) as count from voicemail_msgs ";
			$sql .= "where domain = '$v_domain' ";
			$sql .= "and username = '".$row[extension]."' ";
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$result = $prepstatement->fetchAll();
			foreach ($result as &$row2) {
				$count = $row2["count"];
				break; //limit to 1 row
			}
			unset ($prepstatement);

			echo "<tr >\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row[extension]."</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$row[vm_mailto]."&nbsp;</td>\n";
			echo "	<td valign='top' class='".$rowstyle[$c]."'>".$count."&nbsp;</td>\n";
			echo "  <td valign='top' class='".$rowstyle[$c]."'>".($row[vm_enabled]?"true":"false")."</td>\n";
			echo "	<td valign='top' class='rowstylebg' width='30%'>".$row[description]."&nbsp;</td>\n";
			echo "	<td valign='top' align='right'>\n";
			//echo "		<a href='v_extensions_edit.php?id=".$row[extension_id]."' alt='edit'>$v_link_label_edit</a>\n";
			//echo "		<a href='v_extensions_delete.php?id=".$row[extension_id]."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
			echo "		<a href='v_voicemail_prefs_delete.php?id=".$row[extension_id]."' alt='restore default preferences' title='restore default preferences' onclick=\"return confirm('Are you sure you want remove the voicemail name and greeting?')\">$v_link_label_delete</a>\n";
			echo "	</td>\n";
			echo "</tr>\n";

			unset($count);
			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $rowcount);
	} //end if results


	//echo "<tr>\n";
	//echo "<td colspan='5' align='left'>\n";
	//echo "	<table border='0' width='100%' cellpadding='0' cellspacing='0'>\n";
	//echo "	<tr>\n";
	//echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
	//echo "		<td width='33.3%' align='center' nowrap>$pagingcontrols</td>\n";
	//echo "		<td width='33.3%' align='right'>\n";
	//echo "			<a href='v_extensions_edit.php' alt='add'>$v_link_label_add</a>\n";
	//echo "		</td>\n";
	//echo "	</tr>\n";
	//echo "	</table>\n";
	//echo "</td>\n";
	//echo "</tr>\n";

	//echo "<tr>\n";
	//echo "<td colspan='5' align='left'>\n";
	//echo "<br />\n";
	//echo "<br />\n";
	//if ($v_path_show) {
	//	echo $v_conf_dir."/directory/default/\n";
	//}
	//echo "</td>\n";
	//echo "</tr>\n";


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
	require "includes/config.php";
	require_once "includes/footer.php";
	unset ($resultcount);
	unset ($result);
	unset ($key);
	unset ($val);
unset ($c);
?>
