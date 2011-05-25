<?php
	//application details
		$apps[$x]['name'] = "Settings";
		$apps[$x]['guid'] = 'B6B1B2E5-4BA5-044C-8A5C-18709A15EB60';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'PBX Settings.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Settings';
		$apps[$x]['menu'][0]['guid'] = '148EA42A-3711-3D64-181B-07A6A3C3ED60';
		$apps[$x]['menu'][0]['parent_guid'] = '02194288-6D56-6D3E-0B1A-D53A2BC10788';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/core/settings/v_settings_edit.php?id=1';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';
	
	//permission details
		$apps[$x]['permissions'][0]['name'] = 'settings_view';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'settings_edit';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

	//schema details
		$apps[$x]['db'][0]['table'] = 'v_settings';
		$apps[$x]['db'][0]['fields'][0]['name'] = 'setting_id';
		$apps[$x]['db'][0]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][0]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['description'] = '';
		$apps[$x]['db'][0]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][0]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][1]['description'] = '';
		$apps[$x]['db'][0]['fields'][2]['name'] = 'numbering_plan';
		$apps[$x]['db'][0]['fields'][2]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][2]['description'] = '';
		$apps[$x]['db'][0]['fields'][3]['name'] = 'default_gateway';
		$apps[$x]['db'][0]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][3]['description'] = '';
		$apps[$x]['db'][0]['fields'][4]['name'] = 'default_area_code';
		$apps[$x]['db'][0]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][4]['description'] = '';
		$apps[$x]['db'][0]['fields'][5]['name'] = 'event_socket_ip_address';
		$apps[$x]['db'][0]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][5]['description'] = '';
		$apps[$x]['db'][0]['fields'][6]['name'] = 'event_socket_port';
		$apps[$x]['db'][0]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][6]['description'] = '';
		$apps[$x]['db'][0]['fields'][7]['name'] = 'event_socket_password';
		$apps[$x]['db'][0]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][7]['description'] = '';
		$apps[$x]['db'][0]['fields'][8]['name'] = 'xml_rpc_http_port';
		$apps[$x]['db'][0]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][8]['description'] = '';
		$apps[$x]['db'][0]['fields'][9]['name'] = 'xml_rpc_auth_realm';
		$apps[$x]['db'][0]['fields'][9]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][9]['description'] = '';
		$apps[$x]['db'][0]['fields'][10]['name'] = 'xml_rpc_auth_user';
		$apps[$x]['db'][0]['fields'][10]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][10]['description'] = '';
		$apps[$x]['db'][0]['fields'][11]['name'] = 'xml_rpc_auth_pass';
		$apps[$x]['db'][0]['fields'][11]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][11]['description'] = '';
		$apps[$x]['db'][0]['fields'][12]['name'] = 'admin_pin';
		$apps[$x]['db'][0]['fields'][12]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][12]['description'] = '';
		$apps[$x]['db'][0]['fields'][13]['name'] = 'smtphost';
		$apps[$x]['db'][0]['fields'][13]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][13]['description'] = '';
		$apps[$x]['db'][0]['fields'][14]['name'] = 'smtpsecure';
		$apps[$x]['db'][0]['fields'][14]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][14]['description'] = '';
		$apps[$x]['db'][0]['fields'][15]['name'] = 'smtpauth';
		$apps[$x]['db'][0]['fields'][15]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][15]['description'] = '';
		$apps[$x]['db'][0]['fields'][16]['name'] = 'smtpusername';
		$apps[$x]['db'][0]['fields'][16]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][16]['description'] = '';
		$apps[$x]['db'][0]['fields'][17]['name'] = 'smtppassword';
		$apps[$x]['db'][0]['fields'][17]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][17]['description'] = '';
		$apps[$x]['db'][0]['fields'][18]['name'] = 'smtpfrom';
		$apps[$x]['db'][0]['fields'][18]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][18]['description'] = '';
		$apps[$x]['db'][0]['fields'][19]['name'] = 'smtpfromname';
		$apps[$x]['db'][0]['fields'][19]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][19]['description'] = '';
		$apps[$x]['db'][0]['fields'][20]['name'] = 'mod_shout_decoder';
		$apps[$x]['db'][0]['fields'][20]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][20]['description'] = '';
		$apps[$x]['db'][0]['fields'][21]['name'] = 'mod_shout_volume';
		$apps[$x]['db'][0]['fields'][21]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][21]['description'] = '';

?>
