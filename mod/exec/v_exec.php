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
if (permission_exists('exec_command_line') || permission_exists('exec_php_command') || permission_exists('exec_switch')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

if (count($_POST)>0) {
	$shellcmd = trim($_POST["shellcmd"]);
	$phpcmd = trim($_POST["phpcmd"]);
	$switchcmd = trim($_POST["switchcmd"]);
}


	require_once "includes/header.php";


	//--- Begin: Edit Area -----------------------------------------------------
		echo "    <script language=\"javascript\" type=\"text/javascript\" src=\"".PROJECT_PATH."/includes/edit_area/edit_area_full.js\"></script>\n";
		echo "    <!-- -->\n";

		echo "	<script language=\"Javascript\" type=\"text/javascript\">\n";
		echo "		// initialisation //load,\n";
		echo "		editAreaLoader.init({\n";
		echo "			id: \"shellcmd\"	// id of the textarea to transform //, |, help\n";
		echo "			,start_highlight: false\n";
		echo "			,display: \"later\"\n";
		echo "			,font_size: \"8\"\n";
		echo "			,allow_toggle: true\n";
		echo "			,language: \"en\"\n";
		echo "			,syntax: \"html\"\n";
		echo "			,toolbar: \"search, go_to_line,|, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help\" //new_document,\n";
		echo "			,plugins: \"charmap\"\n";
		echo "			,charmap_default: \"arrows\"\n";
		echo "\n";
		echo "    });\n";
		echo "\n";
		echo "\n";
		echo "		editAreaLoader.init({\n";
		echo "			id: \"phpcmd\"	// id of the textarea to transform //, |, help\n";
		echo "			,start_highlight: false\n";
		echo "			,display: \"later\"\n";
		echo "			,font_size: \"8\"\n";
		echo "			,allow_toggle: true\n";
		echo "			,language: \"en\"\n";
		echo "			,syntax: \"php\"\n";
		echo "			,toolbar: \"search, go_to_line,|, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help\" //new_document,\n";
		echo "			,plugins: \"charmap\"\n";
		echo "			,charmap_default: \"arrows\"\n";
		echo "\n";
		echo "    });\n";
		echo "\n";
		echo "		editAreaLoader.init({\n";
		echo "			id: \"switchcmd\"	// id of the textarea to transform //, |, help\n";
		echo "			,start_highlight: false\n";
		echo "			,display: \"later\"\n";
		echo "			,font_size: \"8\"\n";
		echo "			,allow_toggle: true\n";
		echo "			,language: \"en\"\n";
		echo "			,syntax: \"php\"\n";
		echo "			,toolbar: \"search, go_to_line,|, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help\" //new_document,\n";
		echo "			,plugins: \"charmap\"\n";
		echo "			,charmap_default: \"arrows\"\n";
		echo "\n";
		echo "    });\n";
		echo "    </script>";
	//--- End: Edit Area -------------------------------------------------------

	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "      <br>";



	echo "<form method='post' name='frm' action=''>\n";

	echo "<div align='center'>\n";
	echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";

	echo "<tr>\n";
	echo "<td align='left' width='30%' nowrap><b>Execute Command</b></td>\n";
	echo "<td width='70%' align='right'>\n";
	//echo "	<input type='button' class='btn' name='' alt='back' onclick=\"window.location='index.php'\" value='Back'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	if (permission_exists('exec_command_line')) {
		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap>\n";
		echo "    Shell command:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "    <textarea name='shellcmd' id='shellcmd' rows='7' class='txt' wrap='off'>$shellcmd</textarea\n";
		echo "<br />\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
	if (permission_exists('exec_php_command')) {
		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap>\n";
		echo "    PHP command:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "    <textarea name='phpcmd' id='phpcmd' rows='10' class='txt' wrap='off'>$phpcmd</textarea\n";
		echo "<br />\n";
		echo "Use the following link as a reference for PHP: <a href='http://php.net/manual/en/index.php' target='_blank'>PHP Manual</a>\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
	if (permission_exists('exec_switch')) {
		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap>\n";
		if ($v_name == "freeswitch") {
			echo "    Switch Command:\n";
		}
		else {
			echo "    ".ucfirst($v_name)." Command:\n";
		}
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "    <textarea name='switchcmd' id='switchcmd' rows='7' class='txt' wrap='off'>$switchcmd</textarea\n";
		echo "<br />\n";
		echo "For a list of the valid commands use: help\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	echo "			<input type='submit' name='submit' class='btn' value='Execute'>\n";
	echo "		</td>\n";
	echo "	</tr>";
	echo "</table>";

//POST to PHP variables
	if (count($_POST)>0) {

		echo "	<tr>\n";
		echo "		<td colspan='2' align='left'>\n";

		//shellcmd
		if (strlen($shellcmd) > 0 && permission_exists('exec_command_line')) {
			echo "<b>shell command:</b>\n";
			echo "<!--\n";
			$shell_result = shell_exec($shellcmd);
			echo "-->\n";
			echo "<pre>\n";
			echo htmlentities($shell_result);
			echo "</pre>\n";
		}

		//phpcmd
		if (strlen($phpcmd) > 0 && permission_exists('exec_php_command')) {
			echo "<b>php command:</b>\n";
			echo "<pre>\n";
			$php_result = eval($phpcmd);
			echo htmlentities($php_result);
			echo "</pre>\n";
		}

		//fs cmd
		if (strlen($switchcmd) > 0 && permission_exists('exec_switch')) {
			echo "<b>switch command:</b>\n";
			echo "<pre>\n";
			$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
			if ($fp) {
				$switch_result = event_socket_request($fp, 'api '.$switchcmd);
				//$switch_result = eval($switchcmd);
				echo htmlentities($switch_result);
			}
			echo "</pre>\n";
		}
		echo "		</td>\n";
		echo "	</tr>";
	}

	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";
	echo "</form>";

//show the footer
	require_once "includes/footer.php";
?>
