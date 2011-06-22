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
if (permission_exists('public_includes_add') || permission_exists('public_includes_edit')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//set the action as an add or an update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$public_include_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//set the http values as variables
	if (count($_POST)>0) {
		//$v_id = check_str($_POST["v_id"]);
		$extensionname = check_str($_POST["extensionname"]);
		$extensioncontinue = check_str($_POST["extensioncontinue"]);
		$publicorder = check_str($_POST["publicorder"]);
		$context = check_str($_POST["context"]);
		$enabled = check_str($_POST["enabled"]);
		$descr = check_str($_POST["descr"]);
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$public_include_id = check_str($_POST["public_include_id"]);
	}

	//check for all required data
		if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		if (strlen($extensionname) == 0) { $msg .= "Please provide: Extension Name<br>\n"; }
		if (strlen($publicorder) == 0) { $msg .= "Please provide: Order<br>\n"; }
		if (strlen($extensioncontinue) == 0) { $msg .= "Please provide: Continue<br>\n"; }
		//if (strlen($context) == 0) { $msg .= "Please provide: Context<br>\n"; }
		if (strlen($enabled) == 0) { $msg .= "Please provide: Enabled<br>\n"; }
		//if (strlen($descr) == 0) { $msg .= "Please provide: Description<br>\n"; }
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
		if ($action == "add" && permission_exists('public_includes_add')) {
			$sql = "insert into v_public_includes ";
			$sql .= "(";
			$sql .= "v_id, ";
			$sql .= "extensionname, ";
			$sql .= "publicorder, ";
			$sql .= "extensioncontinue, ";
			$sql .= "context, ";
			$sql .= "enabled, ";
			$sql .= "descr ";
			$sql .= ")";
			$sql .= "values ";
			$sql .= "(";
			$sql .= "'$v_id', ";
			$sql .= "'$extensionname', ";
			$sql .= "'$publicorder', ";
			$sql .= "'$extensioncontinue', ";
			$sql .= "'default', ";
			$sql .= "'$enabled', ";
			$sql .= "'$descr' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);

			//synchronize the xml config
			sync_package_v_public_includes();

			require_once "includes/header.php";
			echo "<meta http-equiv=\"refresh\" content=\"2;url=v_public_includes.php\">\n";
			echo "<div align='center'>\n";
			echo "Add Complete\n";
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
		} //if ($action == "add")

		if ($action == "update" && permission_exists('public_includes_edit')) {
			$sql = "";
			$sql .= "select * from v_public_includes ";
			$sql .= "where v_id = '$v_id' ";
			$sql .= "and public_include_id = '$public_include_id' ";
			$prepstatement = $db->prepare(check_sql($sql));
			$prepstatement->execute();
			$result = $prepstatement->fetchAll();
			foreach ($result as &$row) {
				$orig_extensionname = $row["extensionname"];
				$orig_publicorder = $row["publicorder"];
				//$enabled = $row["enabled"];
				break; //limit to 1 row
			}
			unset ($prepstatement, $sql);

			$publicincludefilename = $orig_publicorder."_".$orig_extensionname.".xml";
			if (file_exists($v_conf_dir."/dialplan/public/".$publicincludefilename)) {
				unlink($v_conf_dir."/dialplan/public/".$publicincludefilename);
			}
			unset($publicincludefilename, $orig_publicorder, $orig_extensionname);

			$sql = "update v_public_includes set ";
			$sql .= "extensionname = '$extensionname', ";
			$sql .= "publicorder = '$publicorder', ";
			$sql .= "extensioncontinue = '$extensioncontinue', ";
			$sql .= "context = '$context', ";
			$sql .= "enabled = '$enabled', ";
			$sql .= "descr = '$descr' ";
			$sql .= "where v_id = '$v_id' ";
			$sql .= "and public_include_id = '$public_include_id'";
			$db->exec(check_sql($sql));
			unset($sql);

			//synchronize the xml config
			sync_package_v_public_includes();

			require_once "includes/header.php";
			echo "<meta http-equiv=\"refresh\" content=\"2;url=v_public_includes.php\">\n";
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
		$public_include_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_public_includes ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and public_include_id = '$public_include_id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			//$v_id = $row["v_id"];
			$extensionname = $row["extensionname"];
			$publicorder = $row["publicorder"];
			$extensioncontinue = $row["extensioncontinue"];
			$context = $row["context"];
			$enabled = $row["enabled"];
			$descr = $row["descr"];
			break; //limit to 1 row
		}
		unset ($prepstatement);
	}

//include the header
	require_once "includes/header.php";

//show the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align='left'>\n";
	echo "      <br>";

	echo "<form method='post' name='frm' action=''>\n";
	echo "<div align='center'>\n";
	echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";
	echo "<tr>\n";
	if ($action == "add") {
		echo "<td width='30%' align='left'nowrap><b>Public Include Add</b></td>\n";
	}
	if ($action == "update") {
		echo "<td width='30%' align='left' nowrap><b>Public Include Update</b></td>\n";
	}
	echo "<td width='70%' align='right'>\n";
	echo "	<input type='button' class='btn' name='' alt='copy' onclick=\"if (confirm('Do you really want to copy this?')){window.location='v_public_includes_copy.php?id=".$public_include_id."';}\" value='Copy'>\n";
	echo "	<input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_public_includes.php'\" value='Back'>\n";
	echo "</td>\n";
	echo "</tr>\n";

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
	echo "              <select name='publicorder' class='formfld'>\n";
	//echo "              <option></option>\n";
	if (strlen(htmlspecialchars($publicorder))> 0) {
		echo "              <option selected='yes' value='".htmlspecialchars($publicorder)."'>".htmlspecialchars($publicorder)."</option>\n";
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
	//echo "  <input class='formfld' type='text' name='publicorder' maxlength='255' value='$publicorder'>\n";
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
	if (strlen($extensioncontinue) == 0) { $extensioncontinue = "false"; }
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
		echo "				<input type='hidden' name='public_include_id' value='$public_include_id'>\n";
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

//show the  v_public_details
	if ($action == "update") {
		echo "<div align='center'>";
		echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

		echo "<tr class='border'>\n";
		echo "	<td align=\"center\">\n";
		echo "      <br>";

		echo "<table width=\"100%\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td align='left'><p><span class=\"vexpl\"><span class=\"red\"><strong>Conditions and Actions<br />\n";
		echo "        </strong></span>\n";
		echo "        The following conditions, actions and anti-actions are used in the public to direct call flow. Each is processed in order until you reach the action tag which tells the system what action to perform. You are not limited to only one condition or action tag for a given extension.\n";
		echo "        </span></p></td>\n";
		echo "  </tr>\n";
		echo "</table>";
		echo "<br />\n";

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
		if (permission_exists('public_includes_add')) {
			echo "	<a href='v_public_includes_details_edit.php?id2=".$public_include_id."' alt='add'>$v_link_label_add</a>\n";
		}
		echo "</td>\n";
		echo "<tr>\n";

		//list the conditions
			$sql = "";
			$sql .= " select * from v_public_includes_details ";
			$sql .= " where public_include_id = '$public_include_id' ";
			$sql .= " and v_id = $v_id ";
			$sql .= " and tag = 'condition' ";
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
					echo "   <td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[tag]."</td>\n";
					echo "   <td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fieldtype]."</td>\n";
					echo "   <td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fielddata]."</td>\n";
					echo "   <td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fieldorder]."</td>\n";
					echo "   <td valign='top' align='right'>\n";
					if (permission_exists('public_includes_edit')) {
						echo "		<a href='v_public_includes_details_edit.php?id=".$row[public_includes_detail_id]."&id2=".$public_include_id."' alt='edit'>$v_link_label_edit</a>\n";
					}
					if (permission_exists('public_includes_delete')) {
						echo "		<a href='v_public_includes_details_delete.php?id=".$row[public_includes_detail_id]."&id2=".$public_include_id."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
					}
					echo "   </td>\n";
					echo "</tr>\n";
					if ($c==0) { $c=1; } else { $c=0; }
				} //end foreach
				unset($sql, $result, $rowcount);
			} //end if results

		//list the actions
			$sql = "";
			$sql .= " select * from v_public_includes_details ";
			$sql .= " where public_include_id = '$public_include_id' ";
			$sql .= " and v_id = $v_id ";
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
					echo "   <td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[tag]."</td>\n";
					echo "   <td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fieldtype]."</td>\n";
					echo "   <td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fielddata]."</td>\n";
					echo "   <td valign='top' class='".$rowstyle[$c]."'>&nbsp;&nbsp;".$row[fieldorder]."</td>\n";
					echo "   <td valign='top' align='right'>\n";
					if (permission_exists('public_includes_edit')) {
						echo "		<a href='v_public_includes_details_edit.php?id=".$row[public_includes_detail_id]."&id2=".$public_include_id."' alt='edit'>$v_link_label_edit</a>\n";
					}
					if (permission_exists('public_includes_delete')) {
						echo "		<a href='v_public_includes_details_delete.php?id=".$row[public_includes_detail_id]."&id2=".$public_include_id."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
					}
					echo "   </td>\n";
					echo "</tr>\n";
					if ($c==0) { $c=1; } else { $c=0; }
				} //end foreach
				unset($sql, $result, $rowcount);
			} //end if results

		//list the anti-actions
			$sql = "";
			$sql .= " select * from v_public_includes_details ";
			$sql .= " where public_include_id = '$public_include_id' ";
			$sql .= " and v_id = $v_id ";
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
					if (permission_exists('public_includes_edit')) {
						echo "		<a href='v_public_includes_details_edit.php?id=".$row[public_includes_detail_id]."&id2=".$public_include_id."' alt='edit'>$v_link_label_edit</a>\n";
					}
					if (permission_exists('public_includes_delete')) {
						echo "		<a href='v_public_includes_details_delete.php?id=".$row[public_includes_detail_id]."&id2=".$public_include_id."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
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
		if (permission_exists('public_includes_add')) {
			echo "			<a href='v_public_includes_details_edit.php?id2=".$public_include_id."' alt='add'>$v_link_label_add</a>\n";
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

//include the footer
	require_once "includes/footer.php";
?>
