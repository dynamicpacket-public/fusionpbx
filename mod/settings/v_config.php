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
		$apps[$x]['menu'][0]['path'] = '/mod/settings/v_settings_edit.php?id=1';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';
	
	//permission details
		$apps[$x]['permissions'][0]['name'] = 'settings_view';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'settings_edit';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

	//schema details
		$y = 0;
		$apps[$x]['db'][0]['table'] = 'v_settings';
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'setting_id';
		$apps[$x]['db'][0]['fields'][$y]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][0]['fields'][$y]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][$y]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'v_id';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'numbering_plan';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'default_gateway';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'event_socket_ip_address';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'event_socket_port';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'event_socket_password';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'xml_rpc_http_port';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'xml_rpc_auth_realm';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'xml_rpc_auth_user';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'xml_rpc_auth_pass';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'admin_pin';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'smtphost';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'smtpsecure';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'smtpauth';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'smtpusername';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'smtppassword';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'smtpfrom';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'smtpfromname';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'mod_shout_decoder';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';
		$y++;
		$apps[$x]['db'][0]['fields'][$y]['name'] = 'mod_shout_volume';
		$apps[$x]['db'][0]['fields'][$y]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][$y]['description'] = '';

?>
