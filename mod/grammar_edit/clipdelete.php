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
if (permission_exists('grammar_save')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}
require_once "config.php";

$id = $_GET["id"];
if (strlen($id)>0) {
    $sql = "";
    $sql .= "delete from tblcliplibrary ";
    $sql .= "where id = '$id' ";
    $prepstatement = $db->prepare(check_sql($sql));
    $prepstatement->execute();
    unset($sql,$db);
}

require_once "header.php";
echo "<meta http-equiv=\"refresh\" content=\"1;url=clipoptions.php\">\n";
echo "Delete Complete";
require_once "footer.php";
return;

?>
