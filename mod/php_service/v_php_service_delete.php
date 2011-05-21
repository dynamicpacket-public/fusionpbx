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
if (permission_exists('php_service_delete')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

if (count($_GET)>0) {
	$id = check_str($_GET["id"]);
}

if (strlen($id)>0) {
	$sql = "";
	$sql .= "select * from v_php_service ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and php_service_id = '$id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$service_name = $row["service_name"];
		$tmp_service_name = str_replace(" ", "_", $service_name);
		break; //limit to 1 row
	}
	unset ($prepstatement, $result, $row);

	//delete the php service file
		unlink($v_secure.'/php_service_'.$tmp_service_name.'.php');
	//delete the start up script
		unlink($v_startup_script_dir.'/php_service_'.$tmp_service_name.'.sh');
	//delete the pid file
		unlink($tmp_dir.'/php_service_'.$tmp_service_name.'.pid');

	$sql = "";
	$sql .= "delete from v_php_service ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and php_service_id = '$id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	unset($sql);
}

require_once "includes/header.php";
echo "<meta http-equiv=\"refresh\" content=\"2;url=v_php_service.php\">\n";
echo "<div align='center'>\n";
echo "Delete Complete\n";
echo "</div>\n";

require_once "includes/footer.php";
return;

?>

