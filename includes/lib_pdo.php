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
 Raymond Chandler <intralanman@gmail.com>
 */

//set defaults
	if (strlen($dbtype) > 0) { 
		$db_type = $dbtype; 
	}
	if (strlen($dbhost) > 0) { 
		$db_host = $dbhost; 
	}
	if (strlen($dbport) > 0) { 
		$db_port = $dbport; 
	}
	if (strlen($dbname) > 0) { 
		$db_name = $dbname; 
	}
	if (strlen($dbusername) > 0) { 
		$db_username = $dbusername; 
	}
	if (strlen($dbpassword) > 0) { 
		$db_password = $dbpassword; 
	}

if (!function_exists('get_db_field_names')) {
	function get_db_field_names($db, $table, $db_name='fusionpbx') {
		$query = sprintf('SELECT * FROM %s LIMIT 1', $table);
		foreach ($db->query($query, PDO::FETCH_ASSOC) as $row) {
			return array_keys($row);
		}

		// if we're still here, we need to try something else
		$fields 	= array();
		$driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
		if ($driver == 'sqlite') {
			$query 		= sprintf("Pragma table_info(%s);", $table);
			$stmt 		= $db->prepare($query);
			$result 	= $stmt->execute();
			$rows 		= $stmt->fetchAll();
			//printf('<pre>%s</pre>', print_r($rows, true));
			$row_count 	= count($rows);
			//printf('<pre>%s</pre>', print_r($rows, true));
			for ($i = 0; $i < $row_count; $i++) {
				array_push($fields, $rows[$i]['name']);
			}
			return $fields;
		} else {
			$query 		= sprintf("SELECT * FROM information_schema.columns
		WHERE table_schema='%s' AND table_name='%s';"
		, $db_name, $table
		);
		$stmt 		= $db->prepare($query);
		$result 	= $stmt->execute();
		$rows 		= $stmt->fetchAll();
		$row_count 	= count($rows);
		//printf('<pre>%s</pre>', print_r($rows, true));
		for ($i = 0; $i < $row_count; $i++) {
			array_push($fields, $rows[$i]['COLUMN_NAME']);
		}
		return $fields;
		}
	}
}


if ($db_type == "sqlite") {

	if (strlen($dbfilename) == 0) {
		//if (strlen($_SERVER["SERVER_NAME"]) == 0) { $_SERVER["SERVER_NAME"] = "http://localhost"; }
		$server_name = $_SERVER["SERVER_NAME"];
		$server_name = str_replace ("www.", "", $server_name);
		$server_name = str_replace ("example.net", "example.com", $server_name);
		//$server_name = str_replace (".", "_", $server_name);
		$dbfilenameshort = $server_name;
		$dbfilename = $server_name.'.db';
	}
	else {
		$dbfilenameshort = $dbfilename;
	}

	$filepath = $v_secure;
	$dbfilepath = $v_secure;
	$dbfilepath = realpath($dbfilepath);
	if (file_exists($dbfilepath.'/'.$dbfilename)) {
		//echo "main file exists<br>";
	}
	else {

		//--- begin: create the sqlite db file -----------------------------------------
		$filename = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/sql/sqlite.sql';
		$file_contents = file_get_contents($filename);
		try {
			//$db = new PDO('sqlite2:example.db'); //sqlite 2
			//$dbimg = new PDO('sqlite::memory:'); //sqlite 3
			$db_sql = new PDO('sqlite:'.$dbfilepath.'/'.$dbfilename); //sqlite 3
			$db_sql->beginTransaction();
		}
		catch (PDOException $error) {
			print "error: " . $error->getMessage() . "<br/>";
			die();
		}

		//replace \r\n with \n then explode on \n
		$file_contents = str_replace("\r\n", "\n", $file_contents);

		//loop line by line through all the lines of sql code
		$stringarray = explode("\n", $file_contents);
		$x = 0;
		foreach($stringarray as $sql) {
			try {
				$db_sql->query($sql);
			}
			catch (PDOException $error) {
				echo "error: " . $error->getMessage() . " sql: $sql<br/>";
				//die();
			}
			$x++;
		}
		unset ($file_contents, $sql);
		$db_sql->commit();
		//--- end: create the sqlite db -----------------------------------------

		if (is_writable($dbfilepath.'/'.$dbfilename)) { //is writable
			//use database in current location
		}
		else { //not writable
			echo "The database ".$dbfilepath."/".$dbfilename." is not writeable.";
			exit;
		}

	}

	if (!function_exists('phpmd5')) {
		function phpmd5($string) {
			return md5($string);
		}
	}
	if (!function_exists('phpunix_timestamp')) {
		function phpunix_timestamp($string) {
			return strtotime($string);
		}
	}
	if (!function_exists('phpnow')) {
		function phpnow() {
			//return date('r');
			return date("Y-m-d H:i:s");
		}
	}

	if (!function_exists('phpleft')) {
		function phpleft($string, $num) {
			return substr($string, 0, $num);
		}
	}

	if (!function_exists('phpright')) {
		function phpright($string, $num) {
			return substr($string, (strlen($string)-$num), strlen($string));
		}
	}

	if (!function_exists('phpsqlitedatatype')) {
		function phpsqlitedatatype($string, $field) {

			//--- Begin: Get String Between start and end characters -----
			$start = '(';
			$end = ')';
			$ini = stripos($string,$start);
			if ($ini == 0) return "";
			$ini += strlen($start);
			$len = stripos($string,$end,$ini) - $ini;
			$string = substr($string,$ini,$len);
			//--- End: Get String Between start and end characters -----

			$strdatatype = '';
			$stringarray = explode(',', $string);
			foreach($stringarray as $lnvalue) {

				//$strdatatype .= "-- ".$lnvalue ." ".strlen($lnvalue)." delim ".strrchr($lnvalue, " ")."---<br>";
				//$delimpos = stripos($lnvalue, " ");
				//$strdatatype .= substr($value,$delimpos,strlen($value))." --<br>";

				$fieldlistarray = explode (" ", $value);
				//$strdatatype .= $value ."<br>";
				//$strdatatype .= $fieldlistarray[0] ."<br>";
				//echo $fieldarray[0]."<br>\n";
				if ($fieldarray[0] == $field) {
					//$strdatatype = $fieldarray[1]." ".$fieldarray[2]." ".$fieldarray[3]." ".$fieldarray[4]; //strdatatype
				}
				unset($fieldarray, $string, $field);
			}

			//$strdatatype = $string;
			return $strdatatype;
		}
	} //end function


	//database connection
	try {
		//$db = new PDO('sqlite2:example.db'); //sqlite 2
		//$db = new PDO('sqlite::memory:'); //sqlite 3
		$db = new PDO('sqlite:'.$dbfilepath.'/'.$dbfilename); //sqlite 3

		//Add additional functions to SQLite so that they are accessible inside SQL
		//bool PDO::sqliteCreateFunction ( string function_name, callback callback [, int num_args] )
		$db->sqliteCreateFunction('md5', 'phpmd5', 1);
		$db->sqliteCreateFunction('unix_timestamp', 'phpunix_timestamp', 1);
		$db->sqliteCreateFunction('now', 'phpnow', 0);
		$db->sqliteCreateFunction('sqlitedatatype', 'phpsqlitedatatype', 2);
		$db->sqliteCreateFunction('strleft', 'phpleft', 2);
		$db->sqliteCreateFunction('strright', 'phpright', 2);
	}
	catch (PDOException $error) {
		print "error: " . $error->getMessage() . "<br/>";
		die();
	}
} //end if db_type sqlite


if ($db_type == "mysql") {
	//database connection
	try {
		if (strlen($db_host) == 0 && strlen($db_port) == 0) {
			//if both host and port are empty use the unix socket
			$db = new PDO("mysql:host=$db_host;unix_socket=/var/run/mysqld/mysqld.sock;dbname=$db_name", $db_username, $db_password);
		}
		else {
			if (strlen($db_port) == 0) {
				//leave out port if it is empty
				$db = new PDO("mysql:host=$db_host;dbname=$db_name;", $db_username, $db_password, array(
				PDO::ATTR_ERRMODE,
				PDO::ERRMODE_EXCEPTION
				));
			}
			else {
				$db = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name;", $db_username, $db_password, array(
				PDO::ATTR_ERRMODE,
				PDO::ERRMODE_EXCEPTION
				));
			}
		}
	}
	catch (PDOException $error) {
		print "error: " . $error->getMessage() . "<br/>";
		die();
	}
} //end if db_type mysql


if ($db_type == "pgsql") {
	//database connection
	try {
		if (strlen($db_host) > 0) {
			if (strlen($db_port) == 0) { $db_port = "5432"; }
			$db = new PDO("pgsql:host=$db_host port=$db_port dbname=$db_name user=$db_username password=$db_password");
		}
		else {
			$db = new PDO("pgsql:dbname=$db_name user=$db_username password=$db_password");
		}
	}
	catch (PDOException $error) {
		print "error: " . $error->getMessage() . "<br/>";
		die();
	}
} //end if db_type pgsql

//domain list
	if (!is_array($_SESSION["domains"])) {
		$sql = "select * from v_system_settings ";
		$prepstatement = $db->prepare($sql);
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach($result as $row) {
			//get the values from the db and set them as session variables
				$_SESSION['domains'][$row['v_id']]['v_id'] = $row['v_id'];
				$_SESSION['domains'][$row['v_id']]['domain'] = $row['v_domain'];
				$_SESSION['domains'][$row['v_id']]['template_name'] = $row['v_template_name'];
			//get v_id, and check for an assigned template 
				if ($row['v_domain'] == $_SERVER['HTTP_HOST'] || $row['v_domain'] == 'www.'.$_SERVER['HTTP_HOST']) {
					$_SESSION["v_id"] = $row["v_id"];
					$_SESSION["template_name"] = $row["v_template_name"];
					$_SESSION["v_template_name"] = $row["v_template_name"];
					$_SESSION["v_domain"] = $row['v_domain'];
					$_SESSION["v_time_zone"] = $row['v_time_zone'];
					if (strlen($row["v_time_zone"]) > 0) {
						date_default_timezone_set($_SESSION["v_time_zone"]);
					}
				}
		}
		unset($result, $prepstatement);
	}

//set the context
	if (strlen($_SESSION["context"]) == 0) {
		if (count($_SESSION["domains"]) > 1) {
			$_SESSION["context"] = $_SESSION["v_domain"];
		}
		else {
			$_SESSION["context"] = 'default';
		}
	}

//set the v_id variable from the session
	if (strlen($_SESSION["v_id"]) > 0) { 
		$v_id = $_SESSION["v_id"];
	}
	else {
		$v_id = '1';
	}

?>