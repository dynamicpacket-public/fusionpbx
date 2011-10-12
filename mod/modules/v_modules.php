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
if (permission_exists('modules_view')) {
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

$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
if (strlen($_GET["a"]) > 0) {
	if ($_GET["a"] == "stop") {
		$module_name = $_GET["m"];
		if ($fp) {
			$cmd = "api unload $module_name";
			$response = trim(event_socket_request($fp, $cmd));
			$msg = '<strong>Unload Module:</strong><pre>'.$response.'</pre>';
		}
	}
	if ($_GET["a"] == "start") {
		$module_name = $_GET["m"];
		if ($fp) {
			$cmd = "api load $module_name";
			$response = trim(event_socket_request($fp, $cmd));
			$msg = '<strong>Load Module:</strong><pre>'.$response.'</pre>';
		}
	}
}

if (!function_exists('switch_module_active')) {
	function switch_module_active($fp, $module_name) {
		if (!$fp) {
			$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
		}
		if ($fp) {
			$cmd = "api module_exists $module_name";
			$response = trim(event_socket_request($fp, $cmd));
			if ($response == "true") {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}
}

if (!function_exists('switch_module_exists')) {
	function switch_module_exists($modules, $module_name) {
		//set the default
			$result = false;
		//look for the module
			foreach ($modules as &$row) {
				if ($row['modulename'] == $module_name) {
					$result = true;
					break;
				}
			}
		//return the result
			return $result;
	}
}

if (!function_exists('switch_module_info')) {
	function switch_module_info($module_name) {
		$module_label = substr($module_name, 4);
		$module_label = ucwords(str_replace("_", " ", $module_label));
		$mod['module_label'] = $module_label;
		$mod['module_name'] = $module_name;
		$mod['module_enabled'] = 'false';
		$mod['module_default_enabled'] = 'false';
		$mod['module_desc'] = '';
		switch ($module_name) {
			case "mod_distributor":
				$mod['module_cat'] = 'Applications';
				$mod['module_desc'] = 'Round robin call distribution.';
				break;
			case "mod_say_ru":
				$mod['module_label'] = 'Russian';
				$mod['module_cat'] = 'Say';
				break;
			case "mod_cdr_sqlite":
				$mod['module_label'] = 'CDR SQLite';
				$mod['module_cat'] = 'Event Handlers';
				$mod['module_desc'] = 'SQLite call detail record handler.';
				break;
			case "mod_pocketsphinx":
				$mod['module_label'] = 'PocketSphinx';
				$mod['module_cat'] = 'ASR / TTS';
				$mod['module_desc'] = 'Speech Recognition.';
				break;
			case "mod_tts_commandline":
				$mod['module_label'] = 'TTS Commandline';
				$mod['module_cat'] = 'ASR / TTS';
				$mod['module_desc'] = 'Commandline text to speech engine.';
				break;
			case "mod_dialplan_asterisk":
				$mod['module_cat'] = 'Dialplan Interfaces';
				$mod['module_desc'] = 'Allows Asterisk dialplans.';
				break;
			case "mod_spidermonkey_socket":
				$mod['module_cat'] = 'Languages';
				$mod['module_desc'] = 'Javascript socket support.';
				break;
			case "mod_nibblebill":
				$mod['module_cat'] = 'Applications';
				$mod['module_desc'] = 'Billing module.';
				break;
			case "mod_spidermonkey_core_db":
				$mod['module_cat'] = 'Languages';
				$mod['module_desc'] = 'Javascript support for SQLite.';
				break;
			case "mod_curl":
				$mod['module_cat'] = 'Applications';
				$mod['module_desc'] = 'Allows scripts to make HTTP requests and return responses in plain text or JSON.';
				break;
			case "mod_db":
				$mod['module_label'] = 'DB';
				$mod['module_cat'] = 'Applications';
				$mod['module_desc'] = 'Database key / value storage functionality, dialing and limit backend.';
				$mod['module_enabled'] = 'true';
				$mod['module_default_enabled'] = 'true';
				break;
			case "mod_avmd":
				$mod['module_label'] = 'AVMD';
				$mod['module_cat'] = 'Applications';
				$mod['module_desc'] = 'Advanced voicemail beep detection.';
				break;
			case "mod_spidermonkey_teletone":
				$mod['module_cat'] = 'Languages';
				$mod['module_desc'] = 'Javascript teletone support.';
				break;
			case "mod_spidermonkey_curl":
				$mod['module_cat'] = 'Languages';
				$mod['module_desc'] = 'Javascript curl support.';
				break;
			case "mod_lcr":
				$mod['module_label'] = 'LCR';
				$mod['module_cat'] = 'Applications';
				$mod['module_desc'] = 'Least cost routing.';
				break;
			case "mod_cluechoo":
				$mod['module_cat'] = 'Applications';
				$mod['module_desc'] = 'A framework demo module.';
				break;
			case "mod_syslog":
				$mod['module_label'] = 'Syslog';
				$mod['module_cat'] = 'Loggers';
				$mod['module_desc'] = 'Send logs to a remote syslog server.';
				break;
			case "mod_cidlookup":
				$mod['module_label'] = 'CID Lookup';
				$mod['module_cat'] = 'Applications';
				$mod['module_desc'] = 'Lookup caller id info.';
				break;
			case "mod_bv":
				$mod['module_label'] = 'BV';
				$mod['module_cat'] = 'Codecs';
				$mod['module_desc'] = 'BroadVoice16 and BroadVoice32 audio codecs.';
				break;
			default:
				$mod['module_cat'] = 'Auto';
		}
		return $mod;
	}
}

//get the list of modules
	$sql = "";
	$sql .= " select * from v_modules ";
	$sql .= "where v_id = '1' ";
    if (strlen($orderby)> 0) { 
		$sql .= "order by $orderby $order "; 
	}
	else {
		$sql .= "order by modulecat,  modulelabel"; 
	}
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$modules = $prepstatement->fetchAll(PDO::FETCH_ASSOC);
	$module_count = count($modules);
	unset ($prepstatement, $sql);

//add missing modules for more module info see http://wiki.freeswitch.com/wiki/Modules
	if ($handle = opendir($v_mod_dir)) {
		$modules_new = '';
		$module_found = false;
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && substr($file, -3) == ".so") {
				$module_name = substr($file, 0, -3);
				if (!switch_module_exists($modules, $module_name)) {
					//set module found to true
						$module_found = true;
					//get the module array
						$mod = switch_module_info($module_name);
					//append the module label
						$modules_new .= "<li>".$mod['module_label']."</li>\n";
					//insert the data
						$sql = "insert into v_modules ";
						$sql .= "(";
						$sql .= "v_id, ";
						$sql .= "modulelabel, ";
						$sql .= "modulename, ";
						$sql .= "moduledesc, ";
						$sql .= "modulecat, ";
						$sql .= "moduleenabled, ";
						$sql .= "moduledefaultenabled ";
						$sql .= ")";
						$sql .= "values ";
						$sql .= "(";
						$sql .= "'1', ";
						$sql .= "'".$mod['module_label']."', ";
						$sql .= "'".$mod['module_name']."', ";
						$sql .= "'".$mod['module_desc']."', ";
						$sql .= "'".$mod['module_cat']."', ";
						$sql .= "'".$mod['module_enabled']."', ";
						$sql .= "'".$mod['module_default_enabled']."' ";
						$sql .= ")";
						$db->exec(check_sql($sql));
						unset($sql);
				}
			}
		}
		closedir($handle);
		if ($module_found) {
			sync_package_v_modules();
			$msg = "<strong>Added New Modules:</strong><br />\n";
			$msg .= "<ul>\n";
			$msg .= $modules_new;
			$msg .= "</ul>\n";
		}
	}

//show the msg
	if ($msg) {
		echo "<div align='center'>\n";
		echo "<table width='40%'>\n";
		echo "<tr>\n";
		echo "<th align='left'>Message</th>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td class='rowstyle1'>$msg</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
	}

//show the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"center\">\n";
	echo "      <br>";

	echo "<table width='100%' border='0'><tr>\n";
	echo "<td align='left' width='50%' nowrap><b>Module List</b></td>\n";
	echo "<td align='left' width='50%' align='right'>&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align='left'>\n";
	echo "Modules extend the features of the system. Use this page to enable or disable modules. ";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	$c = 0;
	$rowstyle["0"] = "rowstyle0";
	$rowstyle["1"] = "rowstyle1";

	echo "<div align='center'>\n";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";

	$tmp_module_header = "\n";
	$tmp_module_header .= "<tr>\n";
	//$tmp_module_header .= thorderby('modulecat', 'Module Category', $orderby, $order);
	$tmp_module_header .= thorderby('modulelabel', 'Label', $orderby, $order);
	//$tmp_module_header .= thorderby('modulename', 'Module Name', $orderby, $order);
	$tmp_module_header .= thorderby('moduledesc', 'Description', $orderby, $order);
	$tmp_module_header .= "<th>Status</th>\n";
	$tmp_module_header .= "<th>Action</th>\n";
	$tmp_module_header .= thorderby('moduleenabled', 'Enabled', $orderby, $order);
	//$tmp_module_header .= thorderby('moduledefaultenabled', 'Default Enabled', $orderby, $order);
	$tmp_module_header .= "<td align='right' width='42'>\n";
	$tmp_module_header .= "	<a href='v_modules_edit.php' alt='add'>$v_link_label_add</a>\n";
	$tmp_module_header .= "</td>\n";
	$tmp_module_header .= "<tr>\n";

	if ($module_count == 0) {
		//no results
	}
	else { //received results
		$prevmodulecat = '';
		foreach($modules as $row) {
			if ($prevmodulecat != $row["modulecat"]) {
				$c=0;
				if (strlen($prevmodulecat) > 0) {
					echo "<tr>\n";
					echo "<td colspan='6'>\n";
					echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
					echo "	<tr>\n";
					echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
					echo "		<td width='33.3%' align='center' nowrap>&nbsp;</td>\n";
					echo "		<td width='33.3%' align='right'>\n";
					if (permission_exists('modules_add')) {
						echo "			<a href='v_modules_edit.php' alt='add'>$v_link_label_add</a>\n";
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
				echo "	<b>".$row["modulecat"]."</b>&nbsp;</td></tr>\n";
				echo $tmp_module_header;
			}

			echo "<tr >\n";
			//echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row["modulecat"]."</td>\n";
			echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row["modulelabel"]."</td>\n";
			//echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row["modulename"]."</td>\n";
			echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row["moduledesc"]."&nbsp;</td>\n";
			if (switch_module_active($fp, $row["modulename"])) {
				echo "   <td valign='top' class='".$rowstyle[$c]."'>Running</td>\n";
				echo "   <td valign='top' class='".$rowstyle[$c]."'><a href='v_modules.php?a=stop&m=".$row["modulename"]."' alt='stop'>Stop</a></td>\n";
			}
			else {
				if ($row['moduleenabled']=="true") {
					echo "   <td valign='top' class='".$rowstyle[$c]."'><b>Stopped</b></td>\n";
				}
				else {
					echo "   <td valign='top' class='".$rowstyle[$c]."'>Stopped $notice</td>\n";
				}
				echo "   <td valign='top' class='".$rowstyle[$c]."'><a href='v_modules.php?a=start&m=".$row["modulename"]."' alt='start'>Start</a></td>\n";
			}
			echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row["moduleenabled"]."</td>\n";
			//echo "   <td valign='top' class='".$rowstyle[$c]."'>".$row["moduledefaultenabled"]."</td>\n";
			echo "   <td valign='top' align='right'>\n";
			if (permission_exists('modules_edit')) {
				echo "		<a href='v_modules_edit.php?id=".$row["module_id"]."' alt='edit'>$v_link_label_edit</a>\n";
			}
			if (permission_exists('modules_delete')) {
				echo "		<a href='v_modules_delete.php?id=".$row["module_id"]."' alt='delete' onclick=\"return confirm('Do you really want to delete this?')\">$v_link_label_delete</a>\n";
			}
			echo "   </td>\n";
			echo "</tr>\n";

			$prevmodulecat = $row["modulecat"];
			if ($c==0) { $c=1; } else { $c=0; }
		} //end foreach
		unset($sql, $modules, $rowcount);
	} //end if results

	echo "<tr>\n";
	echo "<td colspan='6'>\n";
	echo "	<table width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "	<tr>\n";
	echo "		<td width='33.3%' nowrap>&nbsp;</td>\n";
	echo "		<td width='33.3%' align='center' nowrap>$pagingcontrols</td>\n";
	echo "		<td width='33.3%' align='right'>\n";
	if (permission_exists('modules_add')) {
		echo "			<a href='v_modules_edit.php' alt='add'>$v_link_label_add</a>\n";
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

//show the footer
	require_once "includes/footer.php";
?>
