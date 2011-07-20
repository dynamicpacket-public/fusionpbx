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
if (permission_exists('log_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//define variables
	$c = 0;
	$rowstyle["0"] = "rowstyle0";
	$rowstyle["1"] = "rowstyle1";

if (permission_exists('log_download')) {
	if ($_GET['a'] == "download") {
		if ($_GET['t'] == "logs") {
			$tmp = $v_log_dir.'/';
			$filename = $v_name.'.log';
		}
		session_cache_limiter('public');
		$fd = fopen($tmp.$filename, "rb");
		header("Content-Type: binary/octet-stream");
		header("Content-Length: " . filesize($tmp.$filename));
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		fpassthru($fd);
		exit;
	}
}

require_once "includes/header.php";
?>

<script language="Javascript" type="text/javascript" src="<?php echo PROJECT_PATH ?>/includes/edit_area/edit_area_full.js"></script>
<script language="Javascript" type="text/javascript">
	// initialisation
	editAreaLoader.init({
		id: "log"	// id of the textarea to transform
		,start_highlight: false
		,allow_toggle: true
		,display: "later"
		,language: "en"
		,syntax: "html"
		,toolbar: "search, go_to_line,|, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help"
		,syntax_selection_allow: "css,html,js,php,xml,c,cpp,sql"
		,show_line_colors: true
	});
</script>


<?php

echo "<br />\n";

echo "<table width='100%' cellpadding='0' cellspacing='0' border='0'>\n";
echo "<tr>\n";
echo "<td width='50%'>\n";
echo "	<b>Logs</b><br />\n";

echo "</td>\n";
echo "<td width='50%' align='right'>\n";
if (permission_exists('log_download')) {
	echo "  <input type='button' class='btn' value='download logs' onclick=\"document.location.href='v_log_viewer.php?a=download&t=logs';\" />\n";
}
echo "</tr>\n";
echo "</table>\n";
echo "<br />\n\n";
if (stristr(PHP_OS, 'WIN')) { 
	//windows detected
	//echo "<b>tail -n 1500 ".$v_log_dir."/".$v_name.".log</b><br />\n";
	echo "<textarea id='log' name='log' style='width: 100%' rows='30' wrap='off'>\n";
	echo tail($v_log_dir."/".$v_name.".log", 1500);
	echo "</textarea>\n";
}
else {
	//windows not detected
	//echo "<b>tail -n 1500 ".$v_log_dir."/".$v_name.".log</b><br />\n";
	echo "<textarea id='log' name='log' style='width: 100%' rows='30' style='' wrap='off'>\n";
	echo shell_exec("tail -n 1500 ".$v_log_dir."/".$v_name.".log");
	echo "</textarea>\n";
}

echo "</td>\n";
echo "</tr>";

if (permission_exists('log_path_view')) {
	echo "<tr>\n";
	echo "<td>\n";
	echo $v_log_dir.'/'.$v_name.".log<br /><br />\n";
	echo "</td>\n";
	echo "</tr>\n";
}

echo "</table>\n";
echo "</div>\n";

require_once "includes/footer.php";

?>