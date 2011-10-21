<?php
require_once "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
if (ifgroup("admin") || ifgroup("superadmin")) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//action add or update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$database_connection_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//clear the values
	$db_type = '';
	$db_host = '';
	$db_port = '';
	$db_name = '';
	$db_username = '';
	$db_password = '';
	$db_path = '';
	$db_description = '';

//get http post variables and set them to php variables
	if (count($_POST)>0) {
		$db_type = check_str($_POST["db_type"]);
		$db_host = check_str($_POST["db_host"]);
		$db_port = check_str($_POST["db_port"]);
		$db_name = check_str($_POST["db_name"]);
		$db_username = check_str($_POST["db_username"]);
		$db_password = check_str($_POST["db_password"]);
		$db_path = check_str($_POST["db_path"]);
		$db_description = check_str($_POST["db_description"]);
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$database_connection_id = check_str($_POST["database_connection_id"]);
	}

	//check for all required data
		//if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		//if (strlen($db_type) == 0) { $msg .= "Please provide: Type<br>\n"; }
		//if (strlen($db_host) == 0) { $msg .= "Please provide: Host<br>\n"; }
		//if (strlen($db_port) == 0) { $msg .= "Please provide: Port<br>\n"; }
		//if (strlen($db_name) == 0) { $msg .= "Please provide: Name<br>\n"; }
		//if (strlen($db_username) == 0) { $msg .= "Please provide: Username<br>\n"; }
		//if (strlen($db_password) == 0) { $msg .= "Please provide: Password<br>\n"; }
		//if (strlen($db_path) == 0) { $msg .= "Please provide: Path<br>\n"; }
		//if (strlen($db_description) == 0) { $msg .= "Please provide: Description<br>\n"; }
		if (strlen($msg) > 0 && strlen($_POST["persistformvar"]) == 0) {
			require_once "includes/header.php";
			require_once "includes/persistformvar.php";
			echo "<div align='center'>\n";
			echo "<table><tr><td>\n";
			echo $msg."<br />";
			echo "</td></tr></table>\n";
			persistformvar($_POST);
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
		}

	//add or update the database
	if ($_POST["persistformvar"] != "true") {
		if ($action == "add") {
			$sql = "insert into v_database_connections ";
			$sql .= "(";
			$sql .= "v_id, ";
			$sql .= "db_type, ";
			$sql .= "db_host, ";
			$sql .= "db_port, ";
			$sql .= "db_name, ";
			$sql .= "db_username, ";
			$sql .= "db_password, ";
			$sql .= "db_path, ";
			$sql .= "db_description ";
			$sql .= ")";
			$sql .= "values ";
			$sql .= "(";
			$sql .= "'$v_id', ";
			$sql .= "'$db_type', ";
			$sql .= "'$db_host', ";
			$sql .= "'$db_port', ";
			$sql .= "'$db_name', ";
			$sql .= "'$db_username', ";
			$sql .= "'$db_password', ";
			$sql .= "'$db_path', ";
			$sql .= "'$db_description' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);

			require_once "includes/header.php";
			echo "<meta http-equiv=\"refresh\" content=\"2;url=v_database_connections.php\">\n";
			echo "<div align='center'>\n";
			echo "Add Complete\n";
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
		} //if ($action == "add")

		if ($action == "update") {
			$sql = "update v_database_connections set ";
			$sql .= "v_id = '$v_id', ";
			$sql .= "db_type = '$db_type', ";
			$sql .= "db_host = '$db_host', ";
			$sql .= "db_port = '$db_port', ";
			$sql .= "db_name = '$db_name', ";
			$sql .= "db_username = '$db_username', ";
			$sql .= "db_password = '$db_password', ";
			$sql .= "db_path = '$db_path', ";
			$sql .= "db_description = '$db_description' ";
			$sql .= "where database_connection_id = '$database_connection_id'";
			$db->exec(check_sql($sql));
			unset($sql);

			require_once "includes/header.php";
			echo "<meta http-equiv=\"refresh\" content=\"2;url=v_database_connections.php\">\n";
			echo "<div align='center'>\n";
			echo "Update Complete\n";
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
		} //if ($action == "update")
	} //if ($_POST["persistformvar"] != "true") 
} //(count($_POST)>0 && strlen($_POST["persistformvar"]) == 0)

//pre-populate the form
	if (count($_GET)>0 && $_POST["persistformvar"] != "true") {
		$database_connection_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_database_connections ";
		$sql .= "where database_connection_id = '$database_connection_id' ";
		$prep_statement = $db->prepare(check_sql($sql));
		$prep_statement->execute();
		$result = $prep_statement->fetchAll();
		foreach ($result as &$row) {
			$db_type = $row["db_type"];
			$db_host = $row["db_host"];
			$db_port = $row["db_port"];
			$db_name = $row["db_name"];
			$db_username = $row["db_username"];
			$db_password = $row["db_password"];
			$db_path = $row["db_path"];
			$db_description = $row["db_description"];
			break; //limit to 1 row
		}
		unset ($prep_statement);
	}


//show the header
	require_once "includes/header.php";

//show the content
	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing=''>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "	  <br>";

	echo "<form method='post' name='frm' action=''>\n";
	echo "<div align='center'>\n";
	echo "<table width='100%'  border='0' cellpadding='3' cellspacing='0'>\n";
	echo "<tr>\n";
	if ($action == "add") {
		echo "<td align=\"left\" width='30%' nowrap=\"nowrap\"><b>Database Connection Add</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align=\"left\" width='30%' nowrap=\"nowrap\"><b>Database Connection Edit</b></td>\n";
	}
	echo "<td width='70%' align=\"right\"><input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_database_connections.php'\" value='Back'></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"left\" colspan='2'>\n";
	echo "Database connection information.<br /><br />\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	Type:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<select class='formfld' name='db_type'>\n";
	echo "	<option value=''></option>\n";
	if ($db_type == "sqlite") { 
		echo "	<option value='sqlite' SELECTED >sqlite</option>\n";
	}
	else {
		echo "	<option value='sqlite'>sqlite</option>\n";
	}
	if ($db_type == "mysql") { 
		echo "	<option value='mysql' SELECTED >mysql</option>\n";
	}
	else {
		echo "	<option value='mysql'>mysql</option>\n";
	}
	if ($db_type == "pgsql") { 
		echo "	<option value='pgsql' SELECTED >pgsql</option>\n";
	}
	else {
		echo "	<option value='pgsql'>pgsql</option>\n";
	}
	echo "	</select>\n";
	echo "<br />\n";
	echo "Select the database type.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	Host:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='db_host' maxlength='255' value=\"$db_host\">\n";
	echo "<br />\n";
	echo "Enter the host name.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	Port:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='db_port' maxlength='255' value=\"$db_port\">\n";
	echo "<br />\n";
	echo "Enter the port number.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='db_name' maxlength='255' value=\"$db_name\">\n";
	echo "<br />\n";
	echo "Enter the database name.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	Username:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='db_username' maxlength='255' value=\"$db_username\">\n";
	echo "<br />\n";
	echo "Enter the database username.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	Password:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='db_password' maxlength='255' value=\"$db_password\">\n";
	echo "<br />\n";
	echo "Enter the database password.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	Path:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='db_path' maxlength='255' value=\"$db_path\">\n";
	echo "<br />\n";
	echo "Enter the database file path.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	Description:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='db_description' maxlength='255' value=\"$db_description\">\n";
	echo "<br />\n";
	echo "Enter the description.\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	if ($action == "update") {
		echo "				<input type='hidden' name='database_connection_id' value='$database_connection_id'>\n";
	}
	echo "				<input type='submit' name='submit' class='btn' value='Save'>\n";
	echo "		</td>\n";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";

	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

//include the footer
	require_once "includes/footer.php";
?>
