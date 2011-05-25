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
if (permission_exists('menu_edit')) {
	//access granted
}
else {
	echo "access denied";
	return;
}

//move down more than one level at a time
//update v_menu set menuorder = (menuorder+1) where menuorder > 2 or menuorder = 2

if (count($_GET)>0) {
	$menuid = check_str($_GET["menuid"]);
	$menuorder = check_str($_GET["menuorder"]);
	$menu_parent_guid = check_str($_GET["menu_parent_guid"]);

	$sql = "SELECT menuorder FROM v_menu ";
	$sql .= "where v_id = '".$v_id."' ";
	$sql .= "order by menuorder desc ";
	$sql .= "limit 1 ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$highestmenuorder = $row[menuorder];
	}
	unset($prepstatement);

	if ($menuorder != $highestmenuorder) {
		//clear the menu session so it will rebuild with the update
			$_SESSION["menu"] = "";

		//move the current item's order number up
			$sql  = "update v_menu set ";
			$sql .= "menuorder = (menuorder-1) "; //move down
			$sql .= "where v_id = '".$v_id."' ";
			$sql .= "and menuorder = ".($menuorder+1)." ";
			$db->exec(check_sql($sql));
			unset($sql);

		//move the selected item's order number down
			$sql  = "update v_menu set ";
			$sql .= "menuorder = (menuorder+1) "; //move up
			$sql .= "where v_id = '".$v_id."' ";
			$sql .= "and menuid = '$menuid' ";
			$db->exec(check_sql($sql));
			unset($sql);
	}

	//redirect the user
		require_once "includes/header.php";
		echo "<meta http-equiv=\"refresh\" content=\"1;url=menu_list.php?menuid=$menuid\">\n";
		echo "<div align='center'>";
		echo "Item Moved Down";
		echo "</div>";
		require_once "includes/footer.php";
		return;
}

?>
