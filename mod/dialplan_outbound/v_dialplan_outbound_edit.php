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
require_once "includes/paging.php";
if (permission_exists('outbound_route_add') || permission_exists('outbound_route_edit')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//action add or update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$dialplan_include_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//get the http values and set them as php variables
	if (count($_POST)>0) {
		$extensionname = check_str($_POST["extensionname"]);
		$dialplanorder = check_str($_POST["dialplanorder"]);
		$extensioncontinue = check_str($_POST["extensioncontinue"]);
		$context = check_str($_POST["context"]);
		$enabled = check_str($_POST["enabled"]);
		$descr = check_str($_POST["descr"]);
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$dialplan_include_id = check_str($_POST["dialplan_include_id"]);
	}

	//check for all required data
		if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br />\n"; }
		if (strlen($extensionname) == 0) { $msg .= "Please provide: Extension Name<br />\n"; }
		if (strlen($dialplanorder) == 0) { $msg .= "Please provide: Order<br />\n"; }
		if (strlen($extensioncontinue) == 0) { $msg .= "Please provide: Continue<br />\n"; }
		//if (strlen($context) == 0) { $msg .= "Please provide: Context<br />\n"; }
		if (strlen($enabled) == 0) { $msg .= "Please provide: Enabled<br />\n"; }
		//if (strlen($descr) == 0) { $msg .= "Please provide: Description<br />\n"; }
		if (strlen($msg) > 0 && strlen($_POST["persistformvar"]) == 0) {
			require_once "includes/header.php";
			require_once "includes/persistformvar.php";
			echo "<div align='center'>\n";
			echo "<table><tr><td>\n";
			echo $msg."<br />";
			echo "</td></tr></table>\n";
			persistformvar($_POST);
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
		}

	//remove the invalid characters from the extension name
		$extensionname = str_replace(" ", "_", $extensionname);
		$extensionname = str_replace("/", "", $extensionname);

	//add or update the database
		if ($_POST["persistformvar"] != "true") {
			if ($action == "add" && permission_exists('outbound_route_add')) {
				$sql = "insert into v_dialplan_includes ";
				$sql .= "(";
				$sql .= "v_id, ";
				$sql .= "extensionname, ";
				$sql .= "dialplanorder, ";
				$sql .= "extensioncontinue, ";
				$sql .= "context, ";
				$sql .= "enabled, ";
				$sql .= "descr ";
				$sql .= ")";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'$v_id', ";
				$sql .= "'$extensionname', ";
				$sql .= "'$dialplanorder', ";
				$sql .= "'$extensioncontinue', ";
				$sql .= "'$context', ";
				$sql .= "'$enabled', ";
				$sql .= "'$descr' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql);

				//synchronize the xml config
					sync_package_v_dialplan_includes();

				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_dialplan_outbound.php\">\n";
				echo "<div align='center'>\n";
				echo "Add Complete\n";
				echo "</div>\n";
				require_once "includes/footer.php";
				return;
			} //if ($action == "add")

			if ($action == "update" && permission_exists('outbound_route_edit')) {
				$sql = "update v_dialplan_includes set ";
				$sql .= "v_id = '$v_id', ";
				$sql .= "extensionname = '$extensionname', ";
				$sql .= "dialplanorder = '$dialplanorder', ";
				$sql .= "extensioncontinue = '$extensioncontinue', ";
				$sql .= "context = '$context', ";
				$sql .= "enabled = '$enabled', ";
				$sql .= "descr = '$descr' ";
				$sql .= "where v_id = '$v_id' ";
				$sql .= "and dialplan_include_id = '$dialplan_include_id'";
				$db->exec(check_sql($sql));
				unset($sql);

				//synchronize the xml config
				sync_package_v_dialplan_includes();
				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_dialplan_outbound.php\">\n";
				echo "<div align='center'>\n";
				echo "Update Complete\n";
				echo "</div>\n";
				require_once "includes/footer.php";
				return;
			} //if ($action == "update")
		} //if ($_POST["persistformvar"] != "true")
} //(count($_POST)>0 && strlen($_POST["persistformvar"]) == 0)

//pre-populate the form
	if (count($_GET)>0 && $_POST["persistformvar"] != "true") {
		$dialplan_include_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_dialplan_includes ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and dialplan_include_id = '$dialplan_include_id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$v_id = $row["v_id"];
			$extensionname = $row["extensionname"];
			$dialplanorder = $row["dialplanorder"];
			$extensioncontinue = $row["extensioncontinue"];
			$context = $row["context"];
			$enabled = $row["enabled"];
			$descr = $row["descr"];
			break; //limit to 1 row
		}
		unset ($prepstatement);
	}

//show the header
	require_once "includes/header.php";

//show the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "      <br>";

	echo "<form method='post' name='frm' action=''>\n";

	echo "<div align='center'>\n";

	echo "<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "    <td align='left' width='30%'><span class=\"vexpl\">\n";
	echo "        <strong>Outbound Routes</strong><br />\n";
	echo "        </span>\n";
	echo "    </td>\n";
	echo "    <td width='70%' align='right'>\n";
	echo "		<input type='button' class='btn' name='' alt='copy' onclick=\"if (confirm('Do you really want to copy this?')){window.location='v_dialplan_outbound_copy.php?id=".$dialplan_include_id."';}\" value='Copy'>\n";
	echo "		<input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_dialplan_outbound.php'\" value='Back'></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <td align='left' colspan='2'>\n";
	echo "        Outbound route general settings. Allows the outbound route to be customized.\n";
	echo "        <br />\n";
	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table>";
	echo "<br />\n";

	echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='extensionname' maxlength='255' value=\"$extensionname\">\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Order:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "              <select name='dialplanorder' class='formfld'>\n";
	//echo "              <option></option>\n";
	if (strlen(htmlspecialchars($dialplanorder))> 0) {
		echo "              <option selected='yes' value='".htmlspecialchars($dialplanorder)."'>".htmlspecialchars($dialplanorder)."</option>\n";
	}
	$i=0;
	while($i<=999) {
	  if (strlen($i) == 1) {
		echo "              <option value='00$i'>00$i</option>\n";
	  }
	  if (strlen($i) == 2) {
		echo "              <option value='0$i'>0$i</option>\n";
	  }
	  if (strlen($i) == 3) {
		echo "              <option value='$i'>$i</option>\n";
	  }

	  $i++;
	}
	echo "              </select>\n";
	//echo "  <input class='formfld' type='text' name='dialplanorder' maxlength='255' value='$dialplanorder'>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	//echo "<tr>\n";
	//echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	//echo "    Context:\n";
	//echo "</td>\n";
	//echo "<td class='vtable' align='left'>\n";
	//echo "    <input class='formfld' type='text' name='context' maxlength='255' value=\"$context\">\n";
	//echo "<br />\n";
	//echo "\n";
	//echo "</td>\n";
	//echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Continue:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='extensioncontinue'>\n";
	echo "    <option value=''></option>\n";
	if ($extensioncontinue == "true") { 
		echo "    <option value='true' SELECTED >true</option>\n";
	}
	else {
		echo "    <option value='true'>true</option>\n";
	}
	if ($extensioncontinue == "false") { 
		echo "    <option value='false' SELECTED >false</option>\n";
	}
	else {
		echo "    <option value='false'>false</option>\n";
	}
	echo "    </select>\n";
	echo "<br />\n";
	echo "Extension Continue in most cases this is false. default: false\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Enabled:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='enabled'>\n";
	echo "    <option value=''></option>\n";
	if ($enabled == "true") { 
		echo "    <option value='true' SELECTED >true</option>\n";
	}
	else {
		echo "    <option value='true'>true</option>\n";
	}
	if ($enabled == "false") { 
		echo "    <option value='false' SELECTED >false</option>\n";
	}
	else {
		echo "    <option value='false'>false</option>\n";
	}
	echo "    </select>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Description:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <textarea class='formfld' name='descr' rows='4'>$descr</textarea>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	if ($action == "update") {
		echo "				<input type='hidden' name='dialplan_include_id' value='$dialplan_include_id'>\n";
	}
	echo "				<input type='submit' name='submit' class='btn' value='Save'>\n";
	echo "		</td>\n";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";


	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

//dialplan details
	if ($action == "update" && permission_exists('outbound_route_edit')) {
		echo "<div align='center'>";
		echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

		echo "<tr class='border'>\n";
		echo "	<td align=\"center\">\n";
		echo "      <br>";

		echo "<table width=\"100%\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td align='left'><p><span class=\"vexpl\"><span class=\"red\"><strong>Conditions and Actions<br />\n";
		echo "        </strong></span>\n";
		echo "        The following conditions, actions and anti-actions are used in the dialplan to direct \n";
		echo "        call flow. Each is processed in order until you reach the action tag which tells what action to perform. \n";
		echo "        You are not limited to only one condition or action tag for a given extension.\n";
		echo "        </span></p></td>\n";
		echo "  </tr>\n";
		echo "</table>";
		echo "<br />\n";

		//show conditions
			$sql = "";
			$sql .= " select * from v_dialplan_includes_details ";
			$sql .= " where v_id = '$v_id' ";
			$sql .= " and dialplan_include_id = '$dialplan_include_id' ";
			$sql .= " and tag = 'condition' ";
			$sql .= " order by fieldorder asc";
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
			echo "<tr>\n";
			echo "<th align='center'>Tag</th>\n";
			echo "<th align='center'>Type</th>\n";
			echo "<th align='center'>Data</th>\n";
			echo "<th align='center'>Order</th>\n";
			echo "<td align='right' width='42'>\n";
			if (permission_exists('outbound_route_add')) {
				echo "	<a href='v_dialplan_outbound_details_edit.php?id2=".$dialplan_include_id."' alt='add'>$v_link_label_add</a>\n";
			}
			echo "</td>\n";
			echo "<tr>\n";

			if ($resultcount == 0) {
				//no results
			}
			else { //received results
				foreach($result as $row) {
					echo "<tr >\n";
					echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[tag]."</td>\n";
					echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fieldtype]."</td>\n";
					echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fielddata]."</td>\n";
					echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fieldorder]."</td>\n";
					echo "	<td valign='top' align='right'>\n";
					if (permission_exists('outbound_route_edit')) {
						echo "		<a href='v_dialplan_outbound_details_edit.php?id=".$row[dialplan_includes_detail_id]."&id2=".$dialplan_include_id."' alt='edit'>$v_link_label_edit</a>\n";
					}
					if (permission_exists('outbound_route_delete')) {
						echo "		<a href='v_dialplan_outbound_details_delete.php?id=".$row[dialplan_includes_detail_id]."&id2=".$dialplan_include_id."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
					}
					echo "	</td>\n";
					echo "</tr>\n";
					if ($c==0) { $c=1; } else { $c=0; }
				} //end foreach
				unset($sql, $result, $rowcount);
			} //end if results


		//show actions
			$sql = "";
			$sql .= " select * from v_dialplan_includes_details ";
			$sql .= " where v_id = '$v_id' ";
			$sql .= " and dialplan_include_id = '$dialplan_include_id' ";
			$sql .= " and tag = 'action' ";
			$sql .= " order by fieldorder asc";
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$result = $prepstatement->fetchAll();
			$resultcount = count($result);
			unset ($prepstatement, $sql);
			if ($resultcount == 0) {
				//no results
			}
			else { //received results
				foreach($result as $row) {
					echo "<tr >\n";
					echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[tag]."</td>\n";
					echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fieldtype]."</td>\n";
					echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fielddata]."</td>\n";
					echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fieldorder]."</td>\n";
					echo "	<td valign='top' align='right'>\n";
					if (permission_exists('outbound_route_edit')) {
						echo "		<a href='v_dialplan_outbound_details_edit.php?id=".$row[dialplan_includes_detail_id]."&id2=".$dialplan_include_id."' alt='edit'>$v_link_label_edit</a>\n";
					}
					if (permission_exists('outbound_route_delete')) {
						echo "		<a href='v_dialplan_outbound_details_delete.php?id=".$row[dialplan_includes_detail_id]."&id2=".$dialplan_include_id."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
					}
					echo "	</td>\n";
					echo "</tr>\n";
					if ($c==0) { $c=1; } else { $c=0; }
				} //end foreach
				unset($sql, $result, $rowcount);
			} //end if results

		//show anti-actions
			$sql = "";
			$sql .= " select * from v_dialplan_includes_details ";
			$sql .= " where v_id = '$v_id' ";
			$sql .= " and dialplan_include_id = '$dialplan_include_id' ";
			$sql .= " and tag = 'anti-action' ";
			$sql .= " order by fieldorder asc";
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$result = $prepstatement->fetchAll();
			$resultcount = count($result);
			unset ($prepstatement, $sql);
			if ($resultcount == 0) {
				//no results
			}
			else { //received results
				foreach($result as $row) {
					echo "<tr >\n";
					echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[tag]."</td>\n";
					echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fieldtype]."</td>\n";
					echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fielddata]."</td>\n";
					echo "	<td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fieldorder]."</td>\n";
					echo "	<td valign='top' align='right'>\n";
					if (permission_exists('outbound_route_edit')) {
						echo "		<a href='v_dialplan_outbound_details_edit.php?id=".$row[dialplan_includes_detail_id]."&id2=".$dialplan_include_id."' alt='edit'>$v_link_label_edit</a>\n";
					}
					if (permission_exists('outbound_route_delete')) {
						echo "		<a href='v_dialplan_outbound_details_delete.php?id=".$row[dialplan_includes_detail_id]."&id2=".$dialplan_include_id."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
					}
					echo "	</td>\n";
					echo "</tr>\n";
					if ($c==0) { $c=1; } else { $c=0; }
				} //end foreach
				unset($sql, $result, $rowcount);
			} //end if results

		echo "<tr>\n";
		echo "<td colspan='5'>\n";
		echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
		echo "	<tr>\n";
		echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
		echo "		<td width='33.3%' align='center' nowrap>$pagingcontrols</td>\n";
		echo "		<td width='33.3%' align='right'>\n";
		if (permission_exists('outbound_route_add')) {
			echo "			<a href='v_dialplan_outbound_details_edit.php?id2=".$dialplan_include_id."' alt='add'>$v_link_label_add</a>\n";
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
	} //end if update

//show the footer
	require_once "includes/footer.php";
?>
