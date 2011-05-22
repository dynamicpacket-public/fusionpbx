<?php
	//application details
		$apps[$x]['name'] = "Virtual Tables";
		$apps[$x]['guid'] = '8E98D409-8134-D33C-BE70-FCEE63D67A64';
		$apps[$x]['category'] = 'System';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Provides the ability to quickly define information to store and dynamically makes tools available to view, add, edit, delete, and search.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Virtual Tables';
		$apps[$x]['menu'][0]['guid'] = '6BE94B46-2126-947F-2365-0BEA23651A6B';
		$apps[$x]['menu'][0]['parent_guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/virtual_tables/v_virtual_tables.php';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'virtual_tables_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'virtual_tables_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'virtual_tables_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'virtual_tables_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][4]['name'] = 'virtual_tables_data_view';
		$apps[$x]['permissions'][4]['groups'][] = 'admin';
		$apps[$x]['permissions'][4]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][5]['name'] = 'virtual_tables_data_add';
		$apps[$x]['permissions'][5]['groups'][] = 'admin';
		$apps[$x]['permissions'][5]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][6]['name'] = 'virtual_tables_data_edit';
		$apps[$x]['permissions'][6]['groups'][] = 'admin';
		$apps[$x]['permissions'][6]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][7]['name'] = 'virtual_tables_data_delete';
		$apps[$x]['permissions'][7]['groups'][] = 'admin';
		$apps[$x]['permissions'][7]['groups'][] = 'superadmin';

	//schema details
		$apps[$x]['db'][0]['table'] = 'v_virtual_table_data';
		$apps[$x]['db'][0]['fields'][0]['name'] = 'virtual_table_data_id';
		$apps[$x]['db'][0]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][0]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['description'] = '';
		$apps[$x]['db'][0]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][0]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][1]['description'] = '';
		$apps[$x]['db'][0]['fields'][2]['name'] = 'virtual_table_id';
		$apps[$x]['db'][0]['fields'][2]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][2]['description'] = '';
		$apps[$x]['db'][0]['fields'][3]['name'] = 'virtual_data_row_id';
		$apps[$x]['db'][0]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][3]['description'] = '';
		$apps[$x]['db'][0]['fields'][4]['name'] = 'virtual_field_name';
		$apps[$x]['db'][0]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][4]['description'] = '';
		$apps[$x]['db'][0]['fields'][5]['name'] = 'virtual_data_field_value';
		$apps[$x]['db'][0]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][5]['description'] = '';
		$apps[$x]['db'][0]['fields'][6]['name'] = 'virtual_data_add_user';
		$apps[$x]['db'][0]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][6]['description'] = '';
		$apps[$x]['db'][0]['fields'][7]['name'] = 'virtual_data_add_date';
		$apps[$x]['db'][0]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][7]['description'] = '';
		$apps[$x]['db'][0]['fields'][8]['name'] = 'virtual_data_del_user';
		$apps[$x]['db'][0]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][8]['description'] = '';
		$apps[$x]['db'][0]['fields'][9]['name'] = 'virtual_data_del_date';
		$apps[$x]['db'][0]['fields'][9]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][9]['description'] = '';
		$apps[$x]['db'][0]['fields'][10]['name'] = 'virtual_table_parent_id';
		$apps[$x]['db'][0]['fields'][10]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][10]['description'] = '';
		$apps[$x]['db'][0]['fields'][11]['name'] = 'virtual_data_parent_row_id';
		$apps[$x]['db'][0]['fields'][11]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][11]['description'] = '';

		$apps[$x]['db'][1]['table'] = 'v_virtual_table_data_types_name_value';
		$apps[$x]['db'][1]['fields'][0]['name'] = 'virtual_table_data_types_name_value_id';
		$apps[$x]['db'][1]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][1]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][1]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][1]['fields'][0]['description'] = '';
		$apps[$x]['db'][1]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][1]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][1]['description'] = '';
		$apps[$x]['db'][1]['fields'][2]['name'] = 'virtual_table_id';
		$apps[$x]['db'][1]['fields'][2]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][2]['description'] = '';
		$apps[$x]['db'][1]['fields'][3]['name'] = 'virtual_table_field_id';
		$apps[$x]['db'][1]['fields'][3]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][3]['description'] = '';
		$apps[$x]['db'][1]['fields'][4]['name'] = 'virtual_data_types_name';
		$apps[$x]['db'][1]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][4]['description'] = '';
		$apps[$x]['db'][1]['fields'][5]['name'] = 'virtual_data_types_value';
		$apps[$x]['db'][1]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][5]['description'] = '';

		$apps[$x]['db'][2]['table'] = 'v_virtual_table_fields';
		$apps[$x]['db'][2]['fields'][0]['name'] = 'virtual_table_field_id';
		$apps[$x]['db'][2]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][2]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][2]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][2]['fields'][0]['description'] = '';
		$apps[$x]['db'][2]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][2]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][2]['fields'][1]['description'] = '';
		$apps[$x]['db'][2]['fields'][2]['name'] = 'virtual_table_id';
		$apps[$x]['db'][2]['fields'][2]['type'] = 'numeric';
		$apps[$x]['db'][2]['fields'][2]['description'] = '';
		$apps[$x]['db'][2]['fields'][3]['name'] = 'virtual_field_label';
		$apps[$x]['db'][2]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][3]['description'] = '';
		$apps[$x]['db'][2]['fields'][4]['name'] = 'virtual_field_name';
		$apps[$x]['db'][2]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][4]['description'] = '';
		$apps[$x]['db'][2]['fields'][5]['name'] = 'virtual_field_type';
		$apps[$x]['db'][2]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][5]['description'] = '';
		$apps[$x]['db'][2]['fields'][6]['name'] = 'virtual_field_list_hidden';
		$apps[$x]['db'][2]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][6]['description'] = '';
		$apps[$x]['db'][2]['fields'][7]['name'] = 'virtual_field_column';
		$apps[$x]['db'][2]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][7]['description'] = '';
		$apps[$x]['db'][2]['fields'][8]['name'] = 'virtual_field_required';
		$apps[$x]['db'][2]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][8]['description'] = '';
		$apps[$x]['db'][2]['fields'][9]['name'] = 'virtual_field_order';
		$apps[$x]['db'][2]['fields'][9]['type'] = 'numeric';
		$apps[$x]['db'][2]['fields'][9]['description'] = '';
		$apps[$x]['db'][2]['fields'][10]['name'] = 'virtual_field_order_tab';
		$apps[$x]['db'][2]['fields'][10]['type'] = 'numeric';
		$apps[$x]['db'][2]['fields'][10]['description'] = '';
		$apps[$x]['db'][2]['fields'][11]['name'] = 'virtual_field_desc';
		$apps[$x]['db'][2]['fields'][11]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][11]['description'] = '';
		$apps[$x]['db'][2]['fields'][12]['name'] = 'virtual_field_value';
		$apps[$x]['db'][2]['fields'][12]['type'] = 'text';
		$apps[$x]['db'][2]['fields'][12]['description'] = '';

		$apps[$x]['db'][3]['table'] = 'v_virtual_tables';
		$apps[$x]['db'][3]['fields'][0]['name'] = 'virtual_table_id';
		$apps[$x]['db'][3]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][3]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][3]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][3]['fields'][0]['description'] = '';
		$apps[$x]['db'][3]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][3]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][3]['fields'][1]['description'] = '';
		$apps[$x]['db'][3]['fields'][2]['name'] = 'virtual_table_category';
		$apps[$x]['db'][3]['fields'][2]['type'] = 'text';
		$apps[$x]['db'][3]['fields'][2]['description'] = '';
		$apps[$x]['db'][3]['fields'][3]['name'] = 'virtual_table_label';
		$apps[$x]['db'][3]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][3]['fields'][3]['description'] = '';
		$apps[$x]['db'][3]['fields'][4]['name'] = 'virtual_table_name';
		$apps[$x]['db'][3]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][3]['fields'][4]['description'] = '';
		$apps[$x]['db'][3]['fields'][5]['name'] = 'virtual_table_auth';
		$apps[$x]['db'][3]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][3]['fields'][5]['description'] = '';
		$apps[$x]['db'][3]['fields'][6]['name'] = 'virtual_table_captcha';
		$apps[$x]['db'][3]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][3]['fields'][6]['description'] = '';
		$apps[$x]['db'][3]['fields'][7]['name'] = 'virtual_table_parent_id';
		$apps[$x]['db'][3]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][3]['fields'][7]['description'] = '';
		$apps[$x]['db'][3]['fields'][8]['name'] = 'virtual_table_desc';
		$apps[$x]['db'][3]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][3]['fields'][8]['description'] = '';

?>
