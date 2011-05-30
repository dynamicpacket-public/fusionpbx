<?php
	//application details
		$apps[$x]['name'] = "Dialplan Manager";
		$apps[$x]['guid'] = '742714E5-8CDF-32FD-462C-CBE7E3D655DB';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'The dialplan is used to setup call destinations based on conditions and context. You can use the dialplan to send calls to gateways, auto attendants, external numbers, to scripts, or any destination.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Dialplan';
		$apps[$x]['menu'][0]['guid'] = 'B94E8BD9-9EB5-E427-9C26-FF7A6C21552A';
		$apps[$x]['menu'][0]['parent_guid'] = '';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/dialplan/v_dialplan_includes.php';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

		$apps[$x]['menu'][1]['title']['en'] = 'Dialplan Manager';
		$apps[$x]['menu'][1]['guid'] = '52929FEE-81D3-4D94-50B7-64842D9393C2';
		$apps[$x]['menu'][1]['parent_guid'] = 'B94E8BD9-9EB5-E427-9C26-FF7A6C21552A';
		$apps[$x]['menu'][1]['category'] = 'internal';
		$apps[$x]['menu'][1]['path'] = '/mod/dialplan/v_dialplan_includes.php';
		$apps[$x]['menu'][1]['groups'][] = 'admin';
		$apps[$x]['menu'][1]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'dialplan_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'dialplan_add';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'dialplan_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'dialplan_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][4]['name'] = 'dialplan_advanced_view';
		$apps[$x]['permissions'][4]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][5]['name'] = 'dialplan_advanced_edit';
		$apps[$x]['permissions'][5]['groups'][] = 'superadmin';

	//schema details
		$apps[$x]['db'][0]['table'] = 'v_dialplan_includes';
		$apps[$x]['db'][0]['fields'][0]['name'] = 'dialplan_include_id';
		$apps[$x]['db'][0]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][0]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['description'] = '';
		$apps[$x]['db'][0]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][0]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][1]['description'] = '';
		$apps[$x]['db'][0]['fields'][2]['name'] = 'extensionname';
		$apps[$x]['db'][0]['fields'][2]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][2]['description'] = '';
		$apps[$x]['db'][0]['fields'][3]['name'] = 'extension_number';
		$apps[$x]['db'][0]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][3]['description'] = '';	
		$apps[$x]['db'][0]['fields'][4]['name'] = 'extensioncontinue';
		$apps[$x]['db'][0]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][4]['description'] = '';
		$apps[$x]['db'][0]['fields'][5]['name'] = 'dialplanorder';
		$apps[$x]['db'][0]['fields'][5]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][5]['description'] = '';
		$apps[$x]['db'][0]['fields'][6]['name'] = 'context';
		$apps[$x]['db'][0]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][6]['description'] = '';
		$apps[$x]['db'][0]['fields'][7]['name'] = 'enabled';
		$apps[$x]['db'][0]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][7]['description'] = '';
		$apps[$x]['db'][0]['fields'][8]['name'] = 'descr';
		$apps[$x]['db'][0]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][8]['description'] = '';
		$apps[$x]['db'][0]['fields'][9]['name'] = 'opt1name';
		$apps[$x]['db'][0]['fields'][9]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][9]['description'] = '';
		$apps[$x]['db'][0]['fields'][10]['name'] = 'opt1value';
		$apps[$x]['db'][0]['fields'][10]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][10]['description'] = '';

		$apps[$x]['db'][1]['table'] = 'v_dialplan_includes_details';
		$apps[$x]['db'][1]['fields'][0]['name'] = 'dialplan_includes_detail_id';
		$apps[$x]['db'][1]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][1]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][1]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][1]['fields'][0]['description'] = '';
		$apps[$x]['db'][1]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][1]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][1]['description'] = '';
		$apps[$x]['db'][1]['fields'][2]['name'] = 'dialplan_include_id';
		$apps[$x]['db'][1]['fields'][2]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][2]['description'] = '';
		$apps[$x]['db'][1]['fields'][3]['name'] = 'parent_id';
		$apps[$x]['db'][1]['fields'][3]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][3]['description'] = '';
		$apps[$x]['db'][1]['fields'][4]['name'] = 'tag';
		$apps[$x]['db'][1]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][4]['description'] = '';
		$apps[$x]['db'][1]['fields'][5]['name'] = 'fieldtype';
		$apps[$x]['db'][1]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][5]['description'] = '';
		$apps[$x]['db'][1]['fields'][6]['name'] = 'fielddata';
		$apps[$x]['db'][1]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][6]['description'] = '';
		$apps[$x]['db'][1]['fields'][7]['name'] = 'fieldbreak';
		$apps[$x]['db'][1]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][7]['description'] = '';
		$apps[$x]['db'][1]['fields'][8]['name'] = 'field_inline';
		$apps[$x]['db'][1]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][8]['description'] = '';
		$apps[$x]['db'][1]['fields'][9]['name'] = 'field_group';
		$apps[$x]['db'][1]['fields'][9]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][9]['description'] = '';
		$apps[$x]['db'][1]['fields'][10]['name'] = 'fieldorder';
		$apps[$x]['db'][1]['fields'][10]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][10]['description'] = '';

?>
