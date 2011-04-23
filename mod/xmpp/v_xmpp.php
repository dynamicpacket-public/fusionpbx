<?php
/* $Id$ */
/*
	v_xmpp.php
	Copyright (C) 2008, 2009 Ken Rice
	All rights reserved.

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:

	1. Redistributions of source code must retain the above copyright notice,
	   this list of conditions and the following disclaimer.

	2. Redistributions in binary form must reproduce the above copyright
	   notice, this list of conditions and the following disclaimer in the
	   documentation and/or other materials provided with the distribution.

	THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
	INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
	AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
	AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
	OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
	SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
	INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
	CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
	ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
	POSSIBILITY OF SUCH DAMAGE.
*/
include "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
require_once "includes/header.php";
require_once "includes/paging.php";

if ($_SESSION['db_tables']['v_xmpp'] != 'valid') {
	if ($db_type == "pgsql") {
		$sql = "select count(*) from pg_tables where schemaname='public' and tablename = 'v_xmpp'";
	} elseif ($db_type == "mysql") {
		$sql = "select count(*) from information_schema.tables where TABLE_SCHEMA='" . $db_name . "' and TABLE_NAME='roomlist';";
	} elseif ($db_type == "sqlite") {
		$sql = "select count(*) from sqlite_master WHERE type IN ('table','view') AND name = 'registrations';";
	}

	$row = $db->query($sql)->fetch();
	
	if ($row['count'] < 1) {
		include "db_create.php";

		echo sql_tables();		

		$create = $db->query(sql_tables())->fetch();
		$_SESSION['db_tables']['v_xmpp'] = 'valid';
	}
}

//get a list of assigned extensions for this user
$sql = "";
$sql .= "select * from v_xmpp ";
$sql .= "where v_id = '$v_id' ";

$prepstatement = $db->prepare(check_sql($sql));
$prepstatement->execute();

$x = 0;
$result = $prepstatement->fetchAll();
foreach ($result as &$row) {
	$profiles_array[$x] = $row;
	$x++;
}

unset ($prepstatement);

/*
if ($x > 0) {
	$key = guid();
	$client_ip = $_SERVER['REMOTE_ADDR'];
	$sql = sprintf("INSERT INTO v_flashphone_auth (auth_key, hostaddr, createtime, username) values ('%s', '%s', now(), '%s')",
			$key, $client_ip, $_SESSION["username"]);
	$db->exec(check_sql($sql));
}
*/

include "profile_list.php";

//show the footer
require_once "includes/footer.php";

?>
