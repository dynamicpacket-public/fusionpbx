<?php
/* $Id$ */
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
	Ken Rice     <krice@tollfreegateway.com>
	Mark J Crane <markjcrane@fusionpbx.com>
*/
include "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
if (permission_exists('ticket_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}
require_once "includes/header.php";
require_once "includes/paging.php";

//get a list of assigned extensions for this user
$sql = "";
$sql .= "select * from v_tickets ";
$sql .= "where v_id = '$v_id' ";
$sql .= "order by ticket_status";
$prepstatement = $db->prepare(check_sql($sql));
$prepstatement->execute();
$x = 0;
$result = $prepstatement->fetchAll();
foreach ($result as &$row) {
	$tickets[$x] = $row;
	$x++;
}
unset ($prepstatement);

//include the view
include "ticket_list.php";

//include the footer
require_once "includes/footer.php";

?>
