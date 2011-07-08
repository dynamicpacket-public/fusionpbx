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
if (permission_exists('menu_add') || permission_exists('menu_edit')) {
	//access granted
}
else {
	echo "access denied";
	return;
}

$tmp_menuorder = 0;

function builddbchildmenulist ($db, $menulevel, $menu_guid, $c) {
	global $v_id, $tmp_menuorder, $v_link_label_edit, $v_link_label_delete;

	//begin check for children
		$menulevel = $menulevel+1;

		$sql = "select * from v_menu ";
		$sql .= "where v_id = '".$v_id."' ";
		$sql .= "and menu_parent_guid = '".$menu_guid."' ";
		$sql .= "order by menuorder, menutitle asc ";

		$prepstatement2 = $db->prepare($sql);
		$prepstatement2->execute();
		$result2 = $prepstatement2->fetchAll();

		$rowstyle["0"] = "rowstyle1";
		$rowstyle["1"] = "rowstyle1";

		if (count($result2) > 0) {
			if ($c == 0) { $c2 = 1; } else { $c2 = 0; }
			foreach($result2 as $row2) {
				$menuid = $row2['menuid'];
				$menucategory = $row2['menucategory'];
				$menu_protected = $row2['menu_protected'];
				$menu_protected = $row2['menu_protected'];
				$menu_protected = $row2['menu_protected'];
				$menu_guid = $field['menu_guid'];
				$menu_parent_guid = $field['menu_parent_guid'];
				$menuorder = $field['menuorder'];
				$menulanguage = $field['menulanguage'];
				$menutitle = $row2[menutitle];
				$menustr = $row2[menustr];
				switch ($menucategory) {
					case "internal":
						$menutitle = "<a href='".PROJECT_PATH."$menustr'>$menutitle</a>";
						break;
					case "external":
						if (substr($menustr, 0,1) == "/") {
							$menustr = PROJECT_PATH . $menustr;
						}
						$menutitle = "<a href='$menustr' target='_blank'>$menutitle</a>";
						break;
					case "email":
						$menutitle = "<a href='mailto:$menustr'>$menutitle</a>";
						break;
				}

				echo "<tr'>\n";
				echo "<td valign='top' class='".$rowstyle[$c]."'>";
				echo "  <table cellpadding='0' cellspacing='0' border='0'>";
				echo "  <tr>";
				echo "      <td nowrap>";
				$i=0;
				while($i < $menulevel){
					echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
					$i++;
				}
				echo "       ".$menutitle."&nbsp;";

				echo "      </td>";
				echo "  </tr>";
				echo "  </table>";
				echo "</td>";
				//echo "<td valign='top'>&nbsp;".$menustr."&nbsp;</td>";
				echo "<td valign='top' class='".$rowstyle[$c]."'>&nbsp;".$menucategory."&nbsp;</td>";
				//echo "<td valign='top'>".$row[menudesc]."</td>";
				//echo "<td valign='top'>&nbsp;".$row[menuorder]."&nbsp;</td>";
				if ($menu_protected == "true") {
					echo "<td valign='top' class='".$rowstyle[$c]."'>&nbsp; <strong>yes</strong> &nbsp;</td>";
				}
				else {
					echo "<td valign='top' class='".$rowstyle[$c]."'>&nbsp; no &nbsp;</td>";
				}
				echo "<td valign='top' align='center' nowrap class='".$rowstyle[$c]."'>";
				echo "  ".$row2[menuorder]."&nbsp;";
				echo "</td>";

				echo "<td valign='top' align='center' class='".$rowstyle[$c]."'>";
				if (permission_exists('menu_edit')) {
					echo "  <input type='button' class='btn' name='' onclick=\"window.location='menu_move_up.php?menu_parent_guid=".$row2['menu_parent_guid']."&menuid=".$row2[menuid]."&menuorder=".$row2[menuorder]."'\" value='<' title='".$row2[menuorder].". Move Up'>";
					echo "  <input type='button' class='btn' name='' onclick=\"window.location='menu_move_down.php?menu_parent_guid=".$row2['menu_parent_guid']."&menuid=".$row2[menuid]."&menuorder=".$row2[menuorder]."'\" value='>' title='".$row2[menuorder].". Move Down'>";
				}
				echo "</td>";

				echo "   <td valign='top' align='right' nowrap>\n";
				if (permission_exists('menu_edit')) {
					echo "		<a href='menu_edit.php?menuid=".$row2[menuid]."&menu_parent_guid=".$row2['menu_parent_guid']."' alt='edit'>$v_link_label_edit</a>\n";
				}
				if (permission_exists('menu_delete')) {
					echo "		<a href='menu_delete.php?menuid=".$row2[menuid]."' onclick=\"return confirm('Do you really want to delete this?')\" alt='delete'>$v_link_label_delete</a>\n";
				}
				echo "   </td>\n";
				echo "</tr>";

				if ($row2[menuorder] != $tmp_menuorder) {
					$sql  = "update v_menu set ";
					$sql .= "menutitle = '".$row2[menutitle]."', ";
					$sql .= "menuorder = '".$tmp_menuorder."' ";
					$sql .= "where v_id = '".$v_id."' ";
					$sql .= "and menuid = '".$row2[menuid]."' ";
					$count = $db->exec(check_sql($sql));
				}
				$tmp_menuorder++;

				if (strlen($menu_guid)> 0) {                  
				  $c = builddbchildmenulist($db, $menulevel, $menu_guid, $c);
				}

				if ($c==0) { $c=1; } else { $c=0; }
			} //end foreach
			unset($sql, $result2, $row2);
		}
		return $c;
	//end check for children
}

require_once "includes/header.php";
$orderby = $_GET["orderby"];
$order = $_GET["order"];

	echo "<div align='center'>";
	echo "<table width='90%' border='0' cellpadding='0' cellspacing='0'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";

	echo "<table width='100%' border='0'><tr>";
	echo "<td width='50%'><b>Menu Manager</b></td>";
	echo "<td width='50%' align='right'>\n";
	if (permission_exists('menu_restore')) {
		echo "	<input type='button' class='btn' value='Restore Default' onclick=\"document.location.href='menu_restore_default.php';\" />";
	}
	echo "</td>\n";
	echo "<td width='35' nowrap></td>\n";
	echo "</tr></table>";

	$sql = "";
	$sql .= "select * from v_menu ";
	$sql .= "where v_id = '".$v_id."' ";
	$sql .= "and menu_parent_guid = '' ";
	$sql .= "or v_id = '".$v_id."' ";
	$sql .= "and menu_parent_guid is null ";
	if (strlen($orderby)> 0) {
		$sql .= "order by $orderby $order ";
	}
	else {
		$sql .= "order by menuorder asc ";
	}
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$resultcount = count($result);

	$c = 0;
	$rowstyle["0"] = "rowstyle0";
	$rowstyle["1"] = "rowstyle0";

	echo "<div align='left'>\n";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";

	if ($resultcount == 0) {
		//no results
		echo "<tr><td>&nbsp;</td></tr>";
	}
	else {
		echo "<tr>";
		echo "<th align='left' nowrap>&nbsp; Title &nbsp; </th>";
		echo "<th align='left'nowrap>&nbsp; Category &nbsp; </th>";
		echo "<th nowrap>&nbsp; Protected &nbsp; </th>";
		echo "<th align='left'  width='55' nowrap>&nbsp; Order &nbsp;</th>";
		echo "<th nowrap width='70'>&nbsp; </th>";
		echo "<td align='right' width='42'>\n";
		if (permission_exists('menu_add')) {
			echo "	<a href='menu_edit.php' alt='add'>$v_link_label_add</a>\n";
		}
		echo "</td>\n";
		echo "</tr>";

		foreach($result as $row) {
			$menucategory = $row['menucategory'];
			$menutitle = $row['menutitle'];
			$menustr = $row['menustr'];
			$menu_protected = $row['menu_protected'];

			switch ($menucategory) {
				case "internal":
					$menutitle = "<a href='".PROJECT_PATH."$menustr'>$menutitle</a>";
					break;
				case "external":
					if (substr($menustr, 0,1) == "/") {
						$menustr = PROJECT_PATH . $menustr;
					}
					$menutitle = "<a href='$menustr' target='_blank'>$menutitle</a>";
					break;
				case "email":
					$menutitle = "<a href='mailto:$menustr'>$menutitle</a>";
					break;
			}

			echo "<tr style='".$rowstyle[$c]."'>\n";
			echo "<td valign='top' class='".$rowstyle[$c]."'>&nbsp; ".$menutitle."&nbsp;</td>";
			//echo "<td valign='top' class='".$rowstyle[$c]."'>&nbsp;".$menustr."&nbsp;</td>";
			echo "<td valign='top' class='".$rowstyle[$c]."'>&nbsp;".$menucategory."&nbsp;</td>";
			//echo "<td valign='top' class='".$rowstyle[$c]."'>".$row[menudesc]."</td>";
			//echo "<td valign='top' class='".$rowstyle[$c]."'>&nbsp;".$row[menu_parent_guid]."&nbsp;</td>";
			//echo "<td valign='top' class='".$rowstyle[$c]."'>&nbsp;".$row[menuorder]."&nbsp;</td>";

			if ($menu_protected == "true") {
				echo "<td valign='top' class='".$rowstyle[$c]."'>&nbsp; <strong>yes</strong> &nbsp;</td>";
			}
			else {
				echo "<td valign='top' class='".$rowstyle[$c]."'>&nbsp; no &nbsp;</td>";
			}

			echo "<td valign='top' align='center' nowrap class='".$rowstyle[$c]."'>";
			echo "  ".$row[menuorder]."&nbsp;";
			echo "</td>";

			echo "<td valign='top' align='center' nowrap class='".$rowstyle[$c]."'>";
			if (permission_exists('menu_edit')) {
				echo "  <input type='button' class='btn' name='' onclick=\"window.location='menu_move_up.php?menu_parent_guid=".$row['menu_parent_guid']."&menuid=".$row['menuid']."&menuorder=".$row['menuorder']."'\" value='<' title='".$row['menuorder'].". Move Up'>";
				echo "  <input type='button' class='btn' name='' onclick=\"window.location='menu_move_down.php?menu_parent_guid=".$row['menu_parent_guid']."&menuid=".$row['menuid']."&menuorder=".$row['menuorder']."'\" value='>' title='".$row['menuorder'].". Move Down'>";
			}
			echo "</td>";

			echo "   <td valign='top' align='right' nowrap>\n";
			if (permission_exists('menu_edit')) {
				echo "		<a href='menu_edit.php?menuid=".$row[menuid]."' alt='edit'>$v_link_label_edit</a>\n";
			}
			if (permission_exists('menu_delete')) {
				echo "		<a href='menu_delete.php?menuid=".$row[menuid]."' onclick=\"return confirm('Do you really want to delete this?')\" alt='delete'>$v_link_label_delete</a>\n";
			}
			echo "   </td>\n";
			echo "</tr>";

			if ($row[menuorder] != $tmp_menuorder) {
				$sql  = "update v_menu set ";
				$sql .= "menutitle = '".$row[menutitle]."', ";
				$sql .= "menuorder = '".$tmp_menuorder."' ";
				$sql .= "where v_id = '".$v_id."' ";
				$sql .= "and menuid = '".$row[menuid]."' ";
				//$db->exec(check_sql($sql));
			}
			$tmp_menuorder++;
			$menulevel = 0;
			if (strlen($row['menu_guid']) > 0) {
				$c = builddbchildmenulist($db, $menulevel, $row['menu_guid'], $c);
			}

			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $rowcount);

	} //end if results

	echo "<tr>\n";
	echo "<td colspan='7' align='left'>\n";
	echo "	<table border='0' width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='center' nowrap>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='right'>\n";
	if (permission_exists('menu_add')) {
		echo "			<a href='menu_edit.php' alt='add'>$v_link_label_add</a>\n";
	}
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "</table>\n";
	echo "</div>\n";
	echo "<br><br>";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</div>";

	echo "<br><br>";
	require_once "includes/footer.php";

?>
