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
if (permission_exists('menu_delete')) {
	//access granted
}
else {
	echo "access denied";
	return;
}

if (count($_GET)>0) {
	//clear the menu session so it will rebuild with the update
		$_SESSION["menu"] = "";

	//get the id
		$menuid = check_str($_GET["menuid"]);

	//delete the item in the menu
		$sql  = "delete from v_menu ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and menuid = '$menuid' ";
		$db->exec(check_sql($sql));
		unset($sql);

	//redirect the user
		require_once "includes/header.php";
		echo "<meta http-equiv=\"refresh\" content=\"2;url=menu_list.php?menuid=$menuid\">\n";
		echo "<div align='center'>";
		echo "Delete Completed";
		echo "</div>";
		require_once "includes/footer.php";
		return;
}

?>
