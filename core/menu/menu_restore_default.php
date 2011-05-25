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
if (permission_exists('menu_restore')) {
	//access granted
}
else {
	echo "access denied";
	return;
}

//menu restore default
	require_once "includes/classes/menu_restore.php";
	$menu_restore = new menu_restore;
	$menu_restore->db = $db;
	$menu_restore->v_id = $v_id;
	$menu_restore->delete();
	$menu_restore->restore();

//unset the menu session variable
	$_SESSION["menu"] = "";

//unset the default template
	$_SESSION["template_content"] = '';

//show a message to the user
	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"2;url=menu_list.php\">\n";
	echo "<div align='center'>\n";
	echo "Restore Complete\n";
	echo "</div>\n";
	require_once "includes/footer.php";
	return;

?>
