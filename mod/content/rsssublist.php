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
if (permission_exists('content_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

$rssid = $_GET["rssid"];
$orderby = $_GET["orderby"];
$order = $_GET["order"];

require_once "includes/header.php";


	echo "<div align='center'>";
	echo "<table width='500' border='0' cellpadding='0' cellspacing='2'>\n";
	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";

	echo "      <br>";
	echo "      <b>$moduletitle Details</b>";
	$sql = "";
	$sql .= "select * from v_rss ";
	$sql .= "where v_id = '$v_id'  ";
	$sql .= "and rssid = '$rssid'  ";
	$sql .= "and rsscategory = '$rsscategory' ";
	$sql .= "and length(rssdeldate) = 0 ";	
	$sql .= "or v_id = '$v_id'  ";
	$sql .= "and rssid = '$rssid'  ";
	$sql .= "and rsscategory = '$rsscategory' ";
	$sql .= "and rssdeldate is null  ";
	$sql .= "order by rssid asc ";

	//echo $sql;
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$resultcount = count($result);
	echo "<table border='0' width='100%'>";
	if ($resultcount == 0) { //no results
		echo "<tr><td>&nbsp;</td></tr>";
	}
	else { //received results
		foreach($result as $row) {
		  //print_r( $row );
			  //echo "<tr style='".$rowstyle[$c]."'>\n";
			  //echo "<tr>";
			  //echo "    <td valign='top'>Title</td>";
			  //echo "    <td valign='top'><a href='rssupdate.php?rssid=".$row[rssid]."'>".$row[rssid]."</a></td>";
			  //echo "</tr>";
			  //echo "<td valign='top'>".$row[rsscategory]."</td>";
			  
			  echo "<tr>";
			  echo "    <td valign='top'>Title: &nbsp;</td>";
			  echo "    <td valign='top'><b>".$row[rsstitle]."</b></td>";
			  echo "    <td valign='top' align='right'>";
			  echo "        <input type='button' class='btn' name='' onclick=\"window.location='rssupdate.php?rssid=".$row[rssid]."'\" value='Update'>";
			  echo "    </td>";
			  $rssdesc = $row[rssdesc];
			  //$rssdesc = str_replace ("\r\n", "<br>", $rssdesc);
			  //$rssdesc = str_replace ("\n", "<br>", $rssdesc);
			  echo "</tr>";              
			  
			  
			  echo "<tr>";
			  echo "    <td valign='top'>Template: &nbsp;</td>";
			  echo "     <td valign='top'>".$row[rsssubcategory]."</td>";
			  echo "</tr>";

			  echo "<tr>";
			  echo "    <td valign='top'>Group: &nbsp;</td>";
			  echo "     <td valign='top'>".$row[rssgroup]."</td>";
			  echo "</tr>";
			  
			  if (strlen($row[rssorder]) > 0) {
				  echo "<tr>";
				  echo "    <td valign='top'>Order: &nbsp;</td>";
				  echo "     <td valign='top'>".$row[rssorder]."</td>";
				  echo "</tr>";
			  }

			  //echo "<td valign='top'>".$row[rsslink]."</td>";
			  echo "    <td valign='top'>Description: &nbsp;</td>";
			  echo "    <td valign='top' colspan='2'>".$rssdesc."</td>";
			  //echo "<td valign='top'>".$row[rssimg]."</td>";

			  //echo "<tr>";
			  //echo "    <td valign='top'>Priority: &nbsp;</td>";
			  //echo "    <td valign='top' colspan='2'>".$row[rssoptional1]."</td>"; //priority
			  //echo "</tr>";

			  //echo "<tr>";
			  //echo "    <td valign='top'>Status: &nbsp;</td>"; //completion status
			  //echo "    <td valign='top' colspan='2'>";
			  //echo      $row[rssoptional2];
			  //if ($row[rssoptional2]=="100") {
			  //    echo "Complete";
			  //}
			  //else {
			  //    echo $row[rssoptional2]."%";
			  //}
			  //echo      "</td>"; //completion status
			  //echo "<td valign='top'>".$row[rssoptional3]."</td>";
			  //echo "<td valign='top'>".$row[rssoptional4]."</td>";
			  //echo "<td valign='top'>".$row[rssoptional5]."</td>";
			  //echo "<td valign='top'>".$row[rssadddate]."</td>";
			  //echo "<td valign='top'>".$row[rssadduser]."</td>";
			  //echo "<tr>";
			  //echo "    <td valign='top'>";
			  //echo "      <a href='rsssublist.php?rssid=".$row[rssid]."'>Details</a>";
			  //echo "        <input type='button' class='btn' name='' onclick=\"window.location='rsssublist.php?rssid=".$row[rssid]."'\" value='Details'>";
			  //echo "    </td>";
			  //echo "</tr>";

			  echo "</tr>";

			  //echo "<tr><td colspan='100%'><img src='/images/spacer.gif' width='100%' height='1' style='background-color: #BBBBBB;'></td></tr>\n";
			  if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
	}
	echo "</table>";
	unset($sql, $prepstatement, $result);


	if ($rsssubshow == 1) {

		echo "<br><br><br>";
		echo "<b>$rsssubtitle</b><br>";

		$sql = "";
		$sql .= "select * from v_rss_sub ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and rssid = '$rssid' ";
		$sql .= "and length(rsssubdeldate) = 0 ";
		$sql .= "or v_id = '$v_id' ";
		$sql .= "and rssid = '$rssid' ";
		$sql .= "and rsssubdeldate is null ";
		if (strlen($orderby)> 0) { $sql .= "order by $orderby $order "; }
		//echo $sql;

		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		$resultcount = count($result);

		$c = 0;
		$rowstyle["0"] = "background-color: #F5F5DC;";
		$rowstyle["1"] = "background-color: #FFFFFF;";

		echo "<div align='left'>\n";
		echo "<table width='100%' border='0' cellpadding='1' cellspacing='1'>\n";
		//echo "<tr><td colspan='100%'><img src='/images/spacer.gif' width='100%' height='1' style='background-color: #BBBBBB;'></td></tr>";

		if ($resultcount == 0) { //no results
			echo "<tr><td>&nbsp;</td></tr>";
		}
		else { //received results

			echo "<tr>";
			/*
			  echo "<th nowrap>&nbsp; &nbsp; ";
			  if (strlen($orderby)==0) {
				echo "<a href='?orderby=Rsssubid&order=desc' title='ascending'>Rsssubid</a>";
			  }
			  else {
				if ($order=="asc") {
					echo "<a href='?orderby=Rsssubid&order=desc' title='ascending'>Rsssubid</a>";
				}
				else {
					echo "<a href='?orderby=Rsssubid&order=asc' title='descending'>Rsssubid</a>";
				}
			  }
			  echo "&nbsp; &nbsp; </th>";

			  echo "<th nowrap>&nbsp; &nbsp; ";
			  if (strlen($orderby)==0) {
				echo "<a href='?orderby=Rssid&order=desc' title='ascending'>Rssid</a>";
			  }
			  else {
				if ($order=="asc") {
					echo "<a href='?orderby=Rssid&order=desc' title='ascending'>Rssid</a>";
				}
				else {
					echo "<a href='?orderby=Rssid&order=asc' title='descending'>Rssid</a>";
				}
			  }
			  echo "&nbsp; &nbsp; </th>";

			  echo "<th nowrap>&nbsp; &nbsp; ";
			  if (strlen($orderby)==0) {
				echo "<a href='?orderby=Rsssubtitle&order=desc' title='ascending'>Rsssubtitle</a>";
			  }
			  else {
				if ($order=="asc") {
					echo "<a href='?orderby=Rsssubtitle&order=desc' title='ascending'>Rsssubtitle</a>";
				}
				else {
					echo "<a href='?orderby=Rsssubtitle&order=asc' title='descending'>Rsssubtitle</a>";
				}
			  }
			  echo "&nbsp; &nbsp; </th>";

			  echo "<th nowrap>&nbsp; &nbsp; ";
			  if (strlen($orderby)==0) {
				echo "<a href='?orderby=Rsssublink&order=desc' title='ascending'>Rsssublink</a>";
			  }
			  else {
				if ($order=="asc") {
					echo "<a href='?orderby=Rsssublink&order=desc' title='ascending'>Rsssublink</a>";
				}
				else {
					echo "<a href='?orderby=Rsssublink&order=asc' title='descending'>Rsssublink</a>";
				}
			  }
			  echo "&nbsp; &nbsp; </th>";

			  echo "<th nowrap>&nbsp; &nbsp; ";
			  if (strlen($orderby)==0) {
				echo "<a href='?orderby=Rsssubdesc&order=desc' title='ascending'>Rsssubdesc</a>";
			  }
			  else {
				if ($order=="asc") {
					echo "<a href='?orderby=Rsssubdesc&order=desc' title='ascending'>Rsssubdesc</a>";
				}
				else {
					echo "<a href='?orderby=Rsssubdesc&order=asc' title='descending'>Rsssubdesc</a>";
				}
			  }
			  echo "&nbsp; &nbsp; </th>";

			  echo "<th nowrap>&nbsp; &nbsp; ";
			  if (strlen($orderby)==0) {
				echo "<a href='?orderby=Rsssuboptional1&order=desc' title='ascending'>Rsssuboptional1</a>";
			  }
			  else {
				if ($order=="asc") {
					echo "<a href='?orderby=Rsssuboptional1&order=desc' title='ascending'>Rsssuboptional1</a>";
				}
				else {
					echo "<a href='?orderby=Rsssuboptional1&order=asc' title='descending'>Rsssuboptional1</a>";
				}
			  }
			  echo "&nbsp; &nbsp; </th>";

			  echo "<th nowrap>&nbsp; &nbsp; ";
			  if (strlen($orderby)==0) {
				echo "<a href='?orderby=Rsssuboptional2&order=desc' title='ascending'>Rsssuboptional2</a>";
			  }
			  else {
				if ($order=="asc") {
					echo "<a href='?orderby=Rsssuboptional2&order=desc' title='ascending'>Rsssuboptional2</a>";
				}
				else {
					echo "<a href='?orderby=Rsssuboptional2&order=asc' title='descending'>Rsssuboptional2</a>";
				}
			  }
			  echo "&nbsp; &nbsp; </th>";

			  echo "<th nowrap>&nbsp; &nbsp; ";
			  if (strlen($orderby)==0) {
				echo "<a href='?orderby=Rsssuboptional3&order=desc' title='ascending'>Rsssuboptional3</a>";
			  }
			  else {
				if ($order=="asc") {
					echo "<a href='?orderby=Rsssuboptional3&order=desc' title='ascending'>Rsssuboptional3</a>";
				}
				else {
					echo "<a href='?orderby=Rsssuboptional3&order=asc' title='descending'>Rsssuboptional3</a>";
				}
			  }
			  echo "&nbsp; &nbsp; </th>";

			  echo "<th nowrap>&nbsp; &nbsp; ";
			  if (strlen($orderby)==0) {
				echo "<a href='?orderby=Rsssuboptional4&order=desc' title='ascending'>Rsssuboptional4</a>";
			  }
			  else {
				if ($order=="asc") {
					echo "<a href='?orderby=Rsssuboptional4&order=desc' title='ascending'>Rsssuboptional4</a>";
				}
				else {
					echo "<a href='?orderby=Rsssuboptional4&order=asc' title='descending'>Rsssuboptional4</a>";
				}
			  }
			  echo "&nbsp; &nbsp; </th>";

			  echo "<th nowrap>&nbsp; &nbsp; ";
			  if (strlen($orderby)==0) {
				echo "<a href='?orderby=Rsssuboptional5&order=desc' title='ascending'>Rsssuboptional5</a>";
			  }
			  else {
				if ($order=="asc") {
					echo "<a href='?orderby=Rsssuboptional5&order=desc' title='ascending'>Rsssuboptional5</a>";
				}
				else {
					echo "<a href='?orderby=Rsssuboptional5&order=asc' title='descending'>Rsssuboptional5</a>";
				}
			  }
			  echo "&nbsp; &nbsp; </th>";

			  echo "<th nowrap>&nbsp; &nbsp; ";
			  if (strlen($orderby)==0) {
				echo "<a href='?orderby=Rsssubadddate&order=desc' title='ascending'>Rsssubadddate</a>";
			  }
			  else {
				if ($order=="asc") {
					echo "<a href='?orderby=Rsssubadddate&order=desc' title='ascending'>Rsssubadddate</a>";
				}
				else {
					echo "<a href='?orderby=Rsssubadddate&order=asc' title='descending'>Rsssubadddate</a>";
				}
			  }
			  echo "&nbsp; &nbsp; </th>";

			  echo "<th nowrap>&nbsp; &nbsp; ";
			  if (strlen($orderby)==0) {
				echo "<a href='?orderby=Rsssubadduser&order=desc' title='ascending'>Rsssubadduser</a>";
			  }
			  else {
				if ($order=="asc") {
					echo "<a href='?orderby=Rsssubadduser&order=desc' title='ascending'>Rsssubadduser</a>";
				}
				else {
					echo "<a href='?orderby=Rsssubadduser&order=asc' title='descending'>Rsssubadduser</a>";
				}
			  }
			  echo "&nbsp; &nbsp; </th>";
			  */

			echo "</tr>";
			echo "<tr><td colspan='100%'><img src='/images/spacer.gif' width='100%' height='1' style='background-color: #BBBBBB;'></td></tr>\n";

			foreach($result as $row) {
			//print_r( $row );
				echo "<tr style='".$rowstyle[$c]."'>\n";
					//echo "<td valign='top'>".$rssid."</td>";
					//echo "<td valign='top'>&nbsp;<b>".$row[rsssubtitle]."</b>&nbsp;</td>";
					//echo "<td valign='top'>&nbsp;".$row[rsssublink]."&nbsp;</td>";
					echo "<td valign='top' width='200'>";
					echo "  <b>".$row[rsssubtitle]."</b>";
					echo "</td>";

					echo "<td valign='top'>".$row[rsssubadddate]."</td>";

					//echo "<td valign='top'>".$row[rsssuboptional1]."</td>";
					//echo "<td valign='top'>".$row[rsssuboptional2]."</td>";
					//echo "<td valign='top'>".$row[rsssuboptional3]."</td>";
					//echo "<td valign='top'>".$row[rsssuboptional4]."</td>";
					//echo "<td valign='top'>".$row[rsssuboptional5]."</td>";
					//echo "<td valign='top'>".$row[rsssubadduser]."</td>";

					echo "<td valign='top'>";
					echo "  <input type='button' class='btn' name='' onclick=\"if (confirm('Are you sure you wish to continue?')) { window.location='rsssubdelete.php?rssid=".$row[rssid]."&rsssubid=".$row[rsssubid]."' }\" value='Delete'>";
					echo "</td>";

					echo "<td valign='top' align='right'>";
					echo "  &nbsp;";
					echo "  <input type='button' class='btn' name='' onclick=\"window.location='rsssubupdate.php?rssid=".$rssid."&rsssubid=".$row[rsssubid]."'\" value='Update'>";
					echo "  &nbsp; \n";
					//echo "  <a href='rsssubupdate.php?rssid=".$rssid."&rsssubid=".$row[rsssubid]."'>Update</a>&nbsp;";
					echo "</td>";


					$rsssubdesc = $row[rsssubdesc];
					$rsssubdesc = str_replace ("\r\n", "<br>", $rsssubdesc);
					$rsssubdesc = str_replace ("\n", "<br>", $rsssubdesc);

					echo "</tr>";
					echo "<tr style='".$rowstyle[$c]."'>\n";
					echo "<td valign='top' width='300' colspan='4'>";
					echo "".$rsssubdesc."&nbsp;";
					echo "</td>";

					echo "</tr>";



				echo "</tr>";

				echo "<tr><td colspan='100%'><img src='/images/spacer.gif' width='100%' height='1' style='background-color: #BBBBBB;'></td></tr>\n";
				if ($c==0) { $c=1; } else { $c=0; }
			} //end foreach        unset($sql, $result, $rowcount);



		} //end if results

		echo "</table>\n";
		echo "</div>\n";


	} //if ($showrsssub == 1) {

	echo "  <br><br>";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	//echo "<input type='button' class='btn' name='' onclick=\"window.location='rsssubsearch.php'\" value='Search'>&nbsp; &nbsp;\n";
	if ($rsssubshow == 1) {
		echo "<input type='button' class='btn' name='' onclick=\"window.location='rsssubadd.php?rssid=".$rssid."'\" value='Add $rsssubtitle'>&nbsp; &nbsp;\n";
	}
	echo "</div>";

	echo "<br><br>";
	require_once "includes/footer.php";

	unset ($resultcount);
	unset ($result);
	unset ($key);
	unset ($val);
	unset ($c);

?>
