<?php
	//application details
		$apps[$x]['name'] = 'DB Connections';
		$apps[$x]['guid'] = '8D229B6D-1383-FCEC-74C6-4CE1682479E2';
		$apps[$x]['category'] = '';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = '';

	//menu details
		$apps[$x]['menu'][$y]['title']['en'] = 'DB Connections';
		$apps[$x]['menu'][$y]['guid'] = 'EBBD754D-CA74-D5B1-A77E-9206BA3ECC3F';
		$apps[$x]['menu'][$y]['parent_guid'] = '594D99C5-6128-9C88-CA35-4B33392CEC0F';
		$apps[$x]['menu'][$y]['category'] = 'internal';
		$apps[$x]['menu'][$y]['path'] = '/mod/database_connections/v_database_connections.php';
		$apps[$x]['menu'][$y]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][$y]['name'] = 'database_connection_view';
		$apps[$x]['permissions'][$y]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'database_connection_add';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'database_connection_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'database_connection_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

	//schema details
		$y = 0; //table array index
		$z = 0; //field array index
		$apps[$x]['db'][$y]['table'] = 'v_database_connections';

		$apps[$x]['db'][$y]['fields'][$z]['name'] = 'database_connection_id';
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = 'v_id';
		$apps[$x]['db'][$y]['fields'][$z]['type'] = 'text';
		$apps[$x]['db'][$y]['fields'][$z]['description'] = '';
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = 'db_type';
		$apps[$x]['db'][$y]['fields'][$z]['type'] = 'text';
		$apps[$x]['db'][$y]['fields'][$z]['description'] = 'Select the database type.';
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = 'db_host';
		$apps[$x]['db'][$y]['fields'][$z]['type'] = 'text';
		$apps[$x]['db'][$y]['fields'][$z]['description'] = 'Enter the host name.';
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = 'db_port';
		$apps[$x]['db'][$y]['fields'][$z]['type'] = 'text';
		$apps[$x]['db'][$y]['fields'][$z]['description'] = 'Enter the port number.';
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = 'db_name';
		$apps[$x]['db'][$y]['fields'][$z]['type'] = 'text';
		$apps[$x]['db'][$y]['fields'][$z]['description'] = 'Enter the database name.';
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = 'db_username';
		$apps[$x]['db'][$y]['fields'][$z]['type'] = 'text';
		$apps[$x]['db'][$y]['fields'][$z]['description'] = 'Enter the database username.';
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = 'db_password';
		$apps[$x]['db'][$y]['fields'][$z]['type'] = 'text';
		$apps[$x]['db'][$y]['fields'][$z]['description'] = 'Enter the database password.';
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = 'db_path';
		$apps[$x]['db'][$y]['fields'][$z]['type'] = 'text';
		$apps[$x]['db'][$y]['fields'][$z]['description'] = 'Enter the database file path.';
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = 'db_description';
		$apps[$x]['db'][$y]['fields'][$z]['type'] = 'text';
		$apps[$x]['db'][$y]['fields'][$z]['description'] = 'Enter the description.';
		$z++;
?>
