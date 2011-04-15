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
require_once "config.php";

if (!function_exists('thorderby')) {
	//html table header order by
	function thorderby($fieldname, $columntitle, $orderby, $order) {

		$html .= "<th nowrap>&nbsp; &nbsp; ";
		if (strlen($orderby)==0) {
			$html .= "<a href='?orderby=$fieldname&order=desc' title='ascending'>$columntitle</a>";
		}
		else {
			if ($order=="asc") {
				$html .= "<a href='?orderby=$fieldname&order=desc' title='ascending'>$columntitle</a>";
			}
			else {
				$html .= "<a href='?orderby=$fieldname&order=asc' title='descending'>$columntitle</a>";
			}
		}
		$html .= "&nbsp; &nbsp; </th>";

		return $html;
	}
}

require_once "includes/header.php";
echo "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"\" href=\"rss.php\" />\n";

$orderby = $_GET["orderby"];
$order = $_GET["order"];


	echo "<div align='center'>";
	echo "<table border='0' width='95%' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	//echo "      <br>";

	echo "<table width='100%'>";
	echo "<tr>";
	echo "<td align='left'>";
	echo "      <b>$moduletitle List</b>";
	echo "</td>";
	echo "<td align='right'>";
	//echo "      <input type='button' class='btn' name='' onclick=\"window.location='rssadd.php'\" value='Add $moduletitle'>&nbsp; &nbsp;\n";
	echo "</td>";
	echo "</tr>";
	echo "</table>";

	$sql = "";
	$sql .= "select * from v_rss ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and rsscategory = '$rsscategory' ";
	$sql .= "and length(rssdeldate) = 0 ";
	$sql .= "or v_id = '$v_id' ";
	$sql .= "and rsscategory = '$rsscategory' ";
	$sql .= "and rssdeldate is null ";
	if (strlen($orderby)> 0) {
		$sql .= "order by $orderby $order ";
	}
	else {
		$sql .= "order by rssorder asc ";
	}
	//echo $sql;
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$resultcount = count($result);

	$c = 0;
	$rowstyle["0"] = "rowstyle0";
	$rowstyle["1"] = "rowstyle1";

	echo "<div align='left'>\n";
	echo "<table width='100%' border='0' cellpadding='2' cellspacing='0'>\n";
	echo "<tr>";
	echo thorderby('rsstitle', 'Title', $orderby, $order);
	echo thorderby('rsslink', 'Link', $orderby, $order);
	//echo thorderby('rsssubcategory', 'Template', $orderby, $order);
	echo thorderby('rssgroup', 'Group', $orderby, $order);
	echo thorderby('rssorder', 'Order', $orderby, $order);
	if ($resultcount == 0) { //no results
		echo "<td align='right' width='21'>\n";
	}
	else {
		echo "<td align='right' width='42'>\n";
	}
	echo "	<a href='rssadd.php' alt='add'>$v_link_label_add</a>\n";
	echo "</td>\n";
	echo "</tr>";

	if ($resultcount > 0) {
		foreach($result as $row) {
		//print_r( $row );
			echo "<tr style='".$rowstyle[$c]."'>\n";
				//echo "<td valign='top'><a href='rssupdate.php?rssid=".$row[rssid]."'>".$row[rssid]."</a></td>";
				//echo "<td valign='top'>".$row[rsscategory]."</td>";

				echo "<td valign='top' nowrap class='".$rowstyle[$c]."'>&nbsp;".$row[rsstitle]."&nbsp;</td>";                
				echo "<td valign='top' nowrap class='".$rowstyle[$c]."'>&nbsp;<a href='/index.php?c=".$row[rsslink]."'>".$row[rsslink]."</a>&nbsp;</td>";
				//echo "<td valign='top' class='".$rowstyle[$c]."'>".$row[rsssubcategory]."&nbsp;</td>";
				if (strlen($row[rssgroup]) > 0) {
					echo "<td valign='top' class='".$rowstyle[$c]."'>".$row[rssgroup]."</td>";
				}
				else {
					echo "<td valign='top' class='".$rowstyle[$c]."'>public</td>";
				}

				//echo "<td valign='top'>".$row[rssdesc]."</td>";
				//echo "<td valign='top'>".$row[rssimg]."</td>";
				//echo "<td valign='top'>&nbsp;".$row[rssoptional1]."&nbsp;</td>"; //priority

				//echo "<td valign='top' class='".$rowstyle[$c]."'>&nbsp;";
				//sif ($row[rssoptional2]=="100") {
				//    echo "Complete";
				//}
				//else {
				//    echo $row[rssoptional2]."%";
				//}
				//echo "&nbsp;</td>"; //completion status

				//echo "<td valign='top'>".$row[rssoptional3]."</td>";
				//echo "<td valign='top'>".$row[rssoptional4]."</td>";
				//echo "<td valign='top'>".$row[rssoptional5]."</td>";
				echo "<td valign='top' class='".$rowstyle[$c]."'>".$row[rssorder]."&nbsp;</td>";

				//echo "<td valign='top' align='center'>";
				//echo "  <input type='button' class='btn' name='' onclick=\"window.location='rssmoveup.php?menuparentid=".$row[menuparentid]."&rssid=".$row[rssid]."&rssorder=".$row[rssorder]."'\" value='<' title='".$row[rssorder].". Move Up'>";
				//echo "  <input type='button' class='btn' name='' onclick=\"window.location='rssmovedown.php?menuparentid=".$row[menuparentid]."&rssid=".$row[rssid]."&rssorder=".$row[rssorder]."'\" value='>' title='".$row[rssorder].". Move Down'>";
				//echo "</td>";

				echo "	<td valign='top' align='right'>\n";
				echo "		<a href='rssupdate.php?rssid=".$row[rssid]."' alt='edit'>$v_link_label_edit</a>\n";
				echo "		<a href='rssdelete.php?rssid=".$row[rssid]."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
				echo "	</td>\n";

				//echo "<td valign='top' align='right' class='".$rowstyle[$c]."'>";
				//echo "  <input type='button' class='btn' name='' onclick=\"if (confirm('Are you sure you wish to continue?')) { window.location='rssdelete.php?rssid=".$row[rssid]."' }\" value='Delete'>";
				//echo "</td>";

			echo "</tr>";

			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $rowcount);

	} //end if results

	echo "<tr>\n";
	echo "<td colspan='6' align='left'>\n";

	echo "	<table border='0' width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='center' nowrap>$pagingcontrols</td>\n";
	echo "		<td width='33.3%' align='right'>\n";
	echo "			<a href='rssadd.php' alt='add'>$v_link_label_add</a>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";

	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</div>\n";

	echo "  <br>";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	//echo "<input type='button' class='btn' name='' onclick=\"window.location='rsssearch.php'\" value='Search'>&nbsp; &nbsp;\n";
	//echo "<input type='button' class='btn' name='' onclick=\"window.location='rssadd.php'\" value='Add $moduletitle'>&nbsp; &nbsp;\n";
	echo "</div>";

	echo "<br><br>";
	require_once "includes/footer.php";

	unset ($resultcount);
	unset ($result);
	unset ($key);
	unset ($val);
	unset ($c);

?>
