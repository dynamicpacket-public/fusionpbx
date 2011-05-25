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

//check the permission
	if (strlen($_SERVER['HTTP_USER_AGENT']) > 0) {
		require_once "includes/checkauth.php";
		if (permission_exists('upgrade_schema') || permission_exists('upgrade_svn') || ifgroup("superadmin")) {
			//echo "access granted";
		}
		else {
			echo "access denied";
			exit;
		}
	}

//upgrade the code with SVN
	if (permission_exists('zzz')) {
		$display_results = false;
		require_once "core/upgrade/upgrade_svn.php";
	}

//upgrade the database schema
	if (permission_exists('zzz')) {
		$display_results = false;
		require_once "core/upgrade/upgrade_schema.php";
	}

//include the header
	require_once "includes/header.php";

//show the content
	echo "<div align='center'>\n";
	echo "<table width='40%'>\n";
	echo "<tr>\n";
	echo "<th align='left'>Message</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td class='rowstyle1'><strong>Upgrade Completed</strong></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</div>\n";

	echo "<br />\n";
	echo "<br />\n";
	echo "<br />\n";
	echo "<br />\n";
	echo "<br />\n";
	echo "<br />\n";
	echo "<br />\n";

//include the footer
	require_once "includes/footer.php";
?>