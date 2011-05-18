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


if (count($_POST)>0) {
	$rsssubid = check_str($_POST["rsssubid"]);
	$rssid = check_str($_POST["rssid"]);
	$rsssubtitle = check_str($_POST["rsssubtitle"]);
	$rsssublink = check_str($_POST["rsssublink"]);
	$rsssubdesc = check_str($_POST["rsssubdesc"]);
	$rsssuboptional1 = check_str($_POST["rsssuboptional1"]);
	$rsssuboptional2 = check_str($_POST["rsssuboptional2"]);
	$rsssuboptional3 = check_str($_POST["rsssuboptional3"]);
	$rsssuboptional4 = check_str($_POST["rsssuboptional4"]);
	$rsssuboptional5 = check_str($_POST["rsssuboptional5"]);
	$rsssubadddate = check_str($_POST["rsssubadddate"]);
	$rsssubadduser = check_str($_POST["rsssubadduser"]);


	require_once "includes/header.php";

	echo "<div align='center'>";
	echo "<table border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "      <br>";


	$sql = "";
	$sql .= "select * from v_rss_sub ";
	$sql .= "where ";
	if (strlen($v_id) > 0) { $sql .= "and rsssubid = '$v_id' "; }
	if (strlen($rsssubid) > 0) { $sql .= "and rsssubid like '%$rsssubid%' "; }
	if (strlen($rssid) > 0) { $sql .= "and rssid like '%$rssid%' "; }
	if (strlen($rsssubtitle) > 0) { $sql .= "and rsssubtitle like '%$rsssubtitle%' "; }
	if (strlen($rsssublink) > 0) { $sql .= "and rsssublink like '%$rsssublink%' "; }
	if (strlen($rsssubdesc) > 0) { $sql .= "and rsssubdesc like '%$rsssubdesc%' "; }
	if (strlen($rsssuboptional1) > 0) { $sql .= "and rsssuboptional1 like '%$rsssuboptional1%' "; }
	if (strlen($rsssuboptional2) > 0) { $sql .= "and rsssuboptional2 like '%$rsssuboptional2%' "; }
	if (strlen($rsssuboptional3) > 0) { $sql .= "and rsssuboptional3 like '%$rsssuboptional3%' "; }
	if (strlen($rsssuboptional4) > 0) { $sql .= "and rsssuboptional4 like '%$rsssuboptional4%' "; }
	if (strlen($rsssuboptional5) > 0) { $sql .= "and rsssuboptional5 like '%$rsssuboptional5%' "; }
	if (strlen($rsssubadddate) > 0) { $sql .= "and rsssubadddate like '%$rsssubadddate%' "; }
	if (strlen($rsssubadduser) > 0) { $sql .= "and rsssubadduser like '%$rsssubadduser%' "; }
	$sql .= "and length(rsssubdeldate) = 0 ";
	$sql .= "or ";
	if (strlen($v_id) > 0) { $sql .= "and rsssubid = '$v_id' "; }
	if (strlen($rsssubid) > 0) { $sql .= "and rsssubid like '%$rsssubid%' "; }
	if (strlen($rssid) > 0) { $sql .= "and rssid like '%$rssid%' "; }
	if (strlen($rsssubtitle) > 0) { $sql .= "and rsssubtitle like '%$rsssubtitle%' "; }
	if (strlen($rsssublink) > 0) { $sql .= "and rsssublink like '%$rsssublink%' "; }
	if (strlen($rsssubdesc) > 0) { $sql .= "and rsssubdesc like '%$rsssubdesc%' "; }
	if (strlen($rsssuboptional1) > 0) { $sql .= "and rsssuboptional1 like '%$rsssuboptional1%' "; }
	if (strlen($rsssuboptional2) > 0) { $sql .= "and rsssuboptional2 like '%$rsssuboptional2%' "; }
	if (strlen($rsssuboptional3) > 0) { $sql .= "and rsssuboptional3 like '%$rsssuboptional3%' "; }
	if (strlen($rsssuboptional4) > 0) { $sql .= "and rsssuboptional4 like '%$rsssuboptional4%' "; }
	if (strlen($rsssuboptional5) > 0) { $sql .= "and rsssuboptional5 like '%$rsssuboptional5%' "; }
	if (strlen($rsssubadddate) > 0) { $sql .= "and rsssubadddate like '%$rsssubadddate%' "; }
	if (strlen($rsssubadduser) > 0) { $sql .= "and rsssubadduser like '%$rsssubadduser%' "; }
	$sql .= "and rsssubdeldate is null ";

	$sql = trim($sql);
	if (substr($sql, -5) == "where"){ $sql = substr($sql, 0, (strlen($sql)-5)); }
	if (substr($sql, -3) == " or"){ $sql = substr($sql, 0, (strlen($sql)-5)); }
	$sql = str_replace ("where and", "where", $sql);
	$sql = str_replace ("or and", "or", $sql);

	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$resultcount = count($result);

	$c = 0;
	$rowstyle["0"] = "background-color: #F5F5DC;";
	$rowstyle["1"] = "background-color: #FFFFFF;";

	echo "<b>Search Results</b><br>";
	echo "<div align='left'>\n";
	echo "<table border='0' cellpadding='1' cellspacing='1'>\n";
	echo "<tr><td colspan='100%'><img src='/images/spacer.gif' width='100%' height='1' style='background-color: #BBBBBB;'></td></tr>";

	if ($resultcount == 0) { //no results
		echo "<tr><td>&nbsp;</td></tr>";
	}
	else { //received results

		echo "<tr>";
		  echo "<th nowrap>&nbsp; &nbsp; Sub ID&nbsp; &nbsp; </th>";
		  echo "<th nowrap>&nbsp; &nbsp; Id&nbsp; &nbsp; </th>";
		  echo "<th nowrap>&nbsp; &nbsp; Title&nbsp; &nbsp; </th>";
		  //echo "<th nowrap>&nbsp; &nbsp; Link&nbsp; &nbsp; </th>";
		  //echo "<th nowrap>&nbsp; &nbsp; Rsssubdesc&nbsp; &nbsp; </th>";
		  //echo "<th nowrap>&nbsp; &nbsp; Rsssuboptional1&nbsp; &nbsp; </th>";
		  //echo "<th nowrap>&nbsp; &nbsp; Rsssuboptional2&nbsp; &nbsp; </th>";
		  //echo "<th nowrap>&nbsp; &nbsp; Rsssuboptional3&nbsp; &nbsp; </th>";
		  //echo "<th nowrap>&nbsp; &nbsp; Rsssuboptional4&nbsp; &nbsp; </th>";
		  //echo "<th nowrap>&nbsp; &nbsp; Rsssuboptional5&nbsp; &nbsp; </th>";
		  //echo "<th nowrap>&nbsp; &nbsp; Rsssubadddate&nbsp; &nbsp; </th>";
		  //echo "<th nowrap>&nbsp; &nbsp; Rsssubadduser&nbsp; &nbsp; </th>";
		echo "</tr>";
		echo "<tr><td colspan='100%'><img src='/images/spacer.gif' width='100%' height='1' style='background-color: #BBBBBB;'></td></tr>\n";

		foreach($result as $row) {
		//print_r( $row );
			echo "<tr style='".$rowstyle[$c]."'>\n";
				echo "<td valign='top'><a href='rsssubupdate.php?rsssubid=".$row[rsssubid]."'>".$row[rsssubid]."</a></td>";
				echo "<td valign='top'>".$row[rssid]."</td>";
				echo "<td valign='top'>".$row[rsssubtitle]."</td>";
				//echo "<td valign='top'>".$row[rsssublink]."</td>";
				//echo "<td valign='top'>".$row[rsssubdesc]."</td>";
				//echo "<td valign='top'>".$row[rsssuboptional1]."</td>";
				//echo "<td valign='top'>".$row[rsssuboptional2]."</td>";
				//echo "<td valign='top'>".$row[rsssuboptional3]."</td>";
				//echo "<td valign='top'>".$row[rsssuboptional4]."</td>";
				//echo "<td valign='top'>".$row[rsssuboptional5]."</td>";
				//echo "<td valign='top'>".$row[rsssubadddate]."</td>";
				//echo "<td valign='top'>".$row[rsssubadduser]."</td>";
			echo "</tr>";

			echo "<tr><td colspan='100%'><img src='/images/spacer.gif' width='100%' height='1' style='background-color: #BBBBBB;'></td></tr>\n";
			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $result, $rowcount);

		echo "</table>\n";
		echo "</div>\n";


		echo "  <br><br>";
		echo "  </td>\n";
		echo "</tr>\n";

	} //end if results

	echo "</table>\n";
	echo "</div>";

	echo "<br><br>";
	require_once "includes/footer.php";

	unset ($resultcount);
	unset ($result);
	unset ($key);
	unset ($val);
	unset ($c);

	}
	else {

		echo "\n";    require_once "includes/header.php";
	echo "<div align='center'>";
	echo "<table border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "      <br>";


	echo "<form method='post' action=''>";
	echo "<table>";
	  echo "	<tr>";
	  echo "		<td>Sub ID:</td>";
	  echo "		<td><input type='text' class='txt' name='rsssubid'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>ID:</td>";
	  echo "		<td><input type='text' class='txt' name='rssid'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Sub Title:</td>";
	  echo "		<td><input type='text' class='txt' name='rsssubtitle'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Sub Link:</td>";
	  echo "		<td><input type='text' class='txt' name='rsssublink'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Sub Desc:</td>";
	  echo "		<td><input type='text' class='txt' name='rsssubdesc'></td>";
	  echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Rsssuboptional1:</td>";
	  //echo "		<td><input type='text' class='txt' name='rsssuboptional1'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Rsssuboptional2:</td>";
	  //echo "		<td><input type='text' class='txt' name='rsssuboptional2'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Rsssuboptional3:</td>";
	  //echo "		<td><input type='text' class='txt' name='rsssuboptional3'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Rsssuboptional4:</td>";
	  //echo "		<td><input type='text' class='txt' name='rsssuboptional4'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Rsssuboptional5:</td>";
	  //echo "		<td><input type='text' class='txt' name='rsssuboptional5'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Rsssubadddate:</td>";
	  //echo "		<td><input type='text' class='txt' name='rsssubadddate'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Rsssubadduser:</td>";
	  //echo "		<td><input type='text' class='txt' name='rsssubadduser'></td>";
	  //echo "	</tr>";
	echo "	<tr>";
	echo "		<td colspan='2' align='right'><input type='submit' name='submit' class='btn' value='Search'></td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";


	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";


require_once "includes/footer.php";

} //end if not post
?>
