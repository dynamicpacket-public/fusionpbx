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
if (permission_exists('variables_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//include the header
	require_once "includes/header.php";

//set http values as php variables
	$orderby = $_GET["orderby"];
	$order = $_GET["order"];

//show the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"center\">\n";
	echo "		<br>";

	echo "<table width=\"100%\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "	<td align='left'><b>Variables</b><br>\n";
	echo "		Define preprocessor variables here. \n";
	echo "	</td>\n";
	echo "  </tr>\n";
	echo "</table>\n";

	$sql = "";
	$sql .= "select * from v_vars ";
	$sql .= "where v_id = '1' ";
	if (strlen($orderby)> 0) {
		$sql .= "order by $orderby $order ";
	}
	else {
		$sql .= "order by var_cat, var_order asc ";
	}
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

	$tmp_var_header = '';
	$tmp_var_header .= "<tr>\n";
	$tmp_var_header .= thorderby('var_name', 'Name', $orderby, $order);
	$tmp_var_header .= thorderby('var_value', 'Value', $orderby, $order);
	//$tmp_var_header .= thorderby('var_cat', 'Category', $orderby, $order);
	//$tmp_var_header .= thorderby('var_order', 'Order', $orderby, $order);
	$tmp_var_header .= thorderby('var_enabled', 'Enabled', $orderby, $order);
	$tmp_var_header .= "<th>Description</th>\n";
	$tmp_var_header .= "<td align='right' width='42'>\n";
	if (permission_exists('variables_add')) {
		$tmp_var_header .= "	<a href='v_vars_edit.php' alt='add'>$v_link_label_add</a>\n";
	}
	$tmp_var_header .= "</td>\n";
	$tmp_var_header .= "<tr>\n";

	if ($resultcount == 0) {
		//no results
	}
	else { 
		$prev_var_cat = '';
		foreach($result as $row) {
			$var_value = $row[var_value];
			$var_value = substr($var_value, 0, 50);
			if ($prev_var_cat != $row[var_cat]) {
				$c=0;
				if (strlen($prev_var_cat) > 0) {
					echo "<tr>\n";
					echo "<td colspan='5'>\n";
					echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
					echo "	<tr>\n";
					echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
					echo "		<td width='33.3%' align='center' nowrap>&nbsp;</td>\n";
					echo "		<td width='33.3%' align='right'>\n";
					if (permission_exists('variables_add')) {
						echo "			<a href='v_vars_edit.php' alt='add'>$v_link_label_add</a>\n";
					}
					echo "		</td>\n";
					echo "	</tr>\n";
					echo "	</table>\n";
					echo "</td>\n";
					echo "</tr>\n";
				}
				echo "<tr><td colspan='4' align='left'>\n";
				echo "	<br />\n";
				echo "	<br />\n";
				echo "	<b>".$row['var_cat']."</b>&nbsp;</td></tr>\n";
				echo $tmp_var_header;
			}

			echo "<tr >\n";
			echo "	<td valign='top' align='left' class='".$rowstyle[$c]."'>".substr($row['var_name'],0,32)."</td>\n";
			echo "	<td valign='top' align='left' class='".$rowstyle[$c]."'>".substr($var_value,0,30)."</td>\n";
			//echo "	<td valign='top' align='left' class='".$rowstyle[$c]."'>".$row['var_cat']."</td>\n";
			//echo "	<td valign='top' align='left' class='".$rowstyle[$c]."'>".$row['var_order']."</td>\n";
			echo "	<td valign='top' align='left' class='".$rowstyle[$c]."'>".$row['var_enabled']."</td>\n";
			$var_desc = str_replace("\n", "<br />", trim(substr(base64_decode($row['var_desc']),0,40)));
			$var_desc = str_replace("   ", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $var_desc);
			echo "	<td valign='top' align='left' class='".$rowstyle[$c]."'>".$var_desc."&nbsp;</td>\n";
			echo "	<td valign='top' align='right'>\n";
			if (permission_exists('variables_edit')) {
				echo "		<a href='v_vars_edit.php?id=".$row['var_id']."' alt='edit'>$v_link_label_edit</a>\n";
			}
			if (permission_exists('variables_delete')) {
				echo "		<a href='v_vars_delete.php?id=".$row['var_id']."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
			}
			echo "	</td>\n";
			echo "</tr>\n";

			$prev_var_cat = $row[var_cat];
			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $rowcount);
	} //end if results

	echo "<tr>\n";
	echo "<td colspan='6' align='left'>\n";
	echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='center' nowrap>$pagingcontrols</td>\n";
	echo "		<td width='33.3%' align='right'>\n";
	if (permission_exists('variables_add')) {
		echo "			<a href='v_vars_edit.php' alt='add'>$v_link_label_add</a>\n";
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
