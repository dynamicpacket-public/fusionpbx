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
if (permission_exists('xml_cdr_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//additional includes
	require_once "includes/header.php";

//page title and description
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td align='left' width='50%' nowrap='nowrap'><b>Call Detail Record Statistics</b></td>\n";
	echo "		<td>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align='left' colspan='2'>\n";
	echo "			Call Detail Records Statics summarize the call information. \n";
	echo "			<br />\n";
	echo "			<br />\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";

//get a list of assigned extensions for this user
	$sql = "";
	$sql .= "select * from v_extensions ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and user_list like '%|".$_SESSION["username"]."|%' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$x = 0;
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$extension_array[$x]['extension_id'] = $row["extension_id"];
		$extension_array[$x]['extension'] = $row["extension"];
		$x++;
	}
	unset ($prepstatement, $x);

//show all call detail records to admin and superadmin. for everyone else show only the call details for extensions assigned to them
	if (!ifgroup("admin") && !ifgroup("superadmin")) {
		// select caller_id_number, destination_number from v_xml_cdr where v_id = '1' 
		// and (caller_id_number = '1001' or destination_number = '1001' or destination_number = '*991001')
		$sqlwhere = "where v_id = '$v_id' and ( ";
		if (count($extension_array) > 0) {
			$x = 0;
			foreach($extension_array as $value) {
				if ($x==0) {
					if ($value['extension'] > 0) { $sqlwhere .= "caller_id_number = '".$value['extension']."' \n"; } //source
				}
				else {
					if ($value['extension'] > 0) { $sqlwhere .= "or caller_id_number = '".$value['extension']."' \n"; } //source
				}
				if ($value['extension'] > 0) { $sqlwhere .= "or destination_number = '".$value['extension']."' \n"; } //destination
				if ($value['extension'] > 0) { $sqlwhere .= "or destination_number = '*99".$value['extension']."' \n"; } //destination
				$x++;
			}
		}
		$sqlwhere .= ") ";
	}
	else {
		//superadmin or admin
		$sqlwhere = "where v_id = '$v_id' ".$sqlwhere;
	}

//create the sql query to get the xml cdr records
	if (strlen($orderby) == 0)  { $orderby  = "start_epoch"; }
	if (strlen($order) == 0)  { $order  = "desc"; }

//calculate the seconds in different time frames
	$seconds_hour = 3600;
	$seconds_day = $seconds_hour * 24;
	$seconds_week = $seconds_day * 7;
	$seconds_month = $seconds_week * 4;

//get the call volume in the past hour
	$sql = "";
	$sql .= " select count(*) as count from v_xml_cdr ";
	$sql .= $sqlwhere;
	$sql .= "and start_epoch BETWEEN ".(time()-$seconds_hour)." AND ".time()." ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll(PDO::FETCH_ASSOC);
	$resultcount = count($result);
	unset ($prepstatement, $sql);
	if ($resultcount > 0) {
		foreach($result as $row) {
			$call_volume_hour .= $row['count'];
		}
	}
	unset($prepstatement, $result, $resultcount, $sql);

//get the call volume in a day
	$sql = "";
	$sql .= " select count(*) as count from v_xml_cdr ";
	$sql .= $sqlwhere;
	$sql .= "and start_epoch BETWEEN ".(time()-$seconds_day)." AND ".time()." ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll(PDO::FETCH_ASSOC);
	$resultcount = count($result);
	unset ($prepstatement, $sql);
	if ($resultcount > 0) {
		foreach($result as $row) {
			$call_volume_day .= $row['count'];
		}
	}
	unset($prepstatement, $result, $resultcount, $sql);

//get the call volume in a week
	$sql = "";
	$sql .= " select count(*) as count from v_xml_cdr ";
	$sql .= $sqlwhere;
	$sql .= "and start_epoch BETWEEN ".(time()-$seconds_week)." AND ".time()." ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll(PDO::FETCH_ASSOC);
	$resultcount = count($result);
	unset ($prepstatement, $sql);
	if ($resultcount > 0) {
		foreach($result as $row) {
			$call_volume_week .= $row['count'];
		}
	}
	unset($prepstatement, $result, $resultcount, $sql);	

//get the call volume in a month
	$sql = "";
	$sql .= " select count(*) as count from v_xml_cdr ";
	$sql .= $sqlwhere;
	$sql .= "and start_epoch BETWEEN ".(time()-$seconds_month)." AND ".time()." ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll(PDO::FETCH_ASSOC);
	$resultcount = count($result);
	unset ($prepstatement, $sql);
	if ($resultcount > 0) { 
		foreach($result as $row) {
			$call_volume_month .= $row['count'];
		}
	}
	unset($prepstatement, $result, $resultcount, $sql);	

//set the style
	$c = 0;
	$rowstyle["0"] = "rowstyle0";
	$rowstyle["1"] = "rowstyle1";

//show the results
	echo "<table width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "<tr>\n";
	echo "	<th>Call Volume</th>\n";
	echo "	<th>&nbsp;</th>\n";
	echo "</tr>\n";

	echo "<tr >\n";
	echo "	<td valign='top' class='".$rowstyle[$c]."'>Hour</td>\n";
	echo "	<td valign='top' class='".$rowstyle[$c]."'>".$call_volume_hour."</td>\n";
	echo "</tr >\n";
	if ($c==0) { $c=1; } else { $c=0; }
	
	echo "<tr >\n";
	echo "	<td valign='top' class='".$rowstyle[$c]."'>Day</td>\n";
	echo "	<td valign='top' class='".$rowstyle[$c]."'>".$call_volume_day."</td>\n";
	echo "</tr >\n";
	if ($c==0) { $c=1; } else { $c=0; }

	echo "<tr >\n";
	echo "	<td valign='top' class='".$rowstyle[$c]."'>Week</td>\n";
	echo "	<td valign='top' class='".$rowstyle[$c]."'>".$call_volume_week."</td>\n";
	echo "</tr >\n";
	if ($c==0) { $c=1; } else { $c=0; }

	echo "<tr >\n";
	echo "	<td valign='top' class='".$rowstyle[$c]."'>Month</td>\n";
	echo "	<td valign='top' class='".$rowstyle[$c]."'>".$call_volume_month."</td>\n";
	echo "</tr >\n";
	if ($c==0) { $c=1; } else { $c=0; }

	echo "</table>";
	echo "</div>";
	echo "<br><br>";
	echo "<br><br>";

//show the footer
	require_once "includes/footer.php";
?>