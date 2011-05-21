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
if (permission_exists('public_includes_delete')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

if (count($_GET)>0) {
	$id = $_GET["id"];
}

if (strlen($id)>0) {

	$sql = "";
	$sql .= "select * from v_public_includes ";
	$sql .= "where v_id = '$v_id' ";
	$sql .= "and public_include_id = '$id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	foreach ($result as &$row) {
		$extensionname = $row["extensionname"];
		$publicorder = $row["publicorder"];
		//$enabled = $row["enabled"];
		break; //limit to 1 row
	}
	unset ($prepstatement, $sql);

	$publicincludefilename = $publicorder."_".$extensionname.".xml";
	if (file_exists($v_conf_dir."/dialplan/public/".$publicincludefilename)) {
		unlink($v_conf_dir."/dialplan/public/".$publicincludefilename);
	}
	unset($publicincludefilename, $publicorder, $extensionname);

	//delete child data
	$sql = "";
	$sql .= "delete from v_public_includes_details ";
	$sql .= "where public_include_id = '$id' ";
	$sql .= "and v_id = '$v_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	unset($sql);

	//delete parent data
	$sql = "";
	$sql .= "delete from v_public_includes ";
	$sql .= "where public_include_id = '$id' ";
	$sql .= "and v_id = '$v_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	unset($sql);

	//synchronize the xml config
	sync_package_v_public_includes();
}

//redirect the user
	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"2;url=v_public_includes.php\">\n";
	echo "<div align='center'>\n";
	echo "Delete Complete\n";
	echo "</div>\n";
	require_once "includes/footer.php";
	return;

?>