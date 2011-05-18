<?php
	//application details
		$apps[$x]['name'] = "IVR Menu";
		$apps[$x]['guid'] = 'A5788E9B-58BC-BD1B-DF59-FFF5D51253AB';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'The IVR Menu plays a recording or a pre-defined phrase that presents the caller with options to choose from. Each option has a corresponding destination. The destinations can be extensions, voicemail, IVR menus, hunt groups, FAX extensions, and more.';
	
	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'IVR Menu';
		$apps[$x]['menu'][0]['guid'] = '72259497-A67B-E5AA-CAC2-0F2DCEF16308';
		$apps[$x]['menu'][0]['parent_guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/ivr_menu/v_ivr_menu.php';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';
	
	//permission details
		$apps[$x]['permissions'][0]['name'] = 'ivr_menu_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'ivr_menu_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'ivr_menu_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'ivr_menu_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

	// CREATE TABLE v_ivr_menu 
		$apps[$x]['db'][0]['table'] = 'v_ivr_menu';
		$apps[$x]['db'][0]['fields'][0]['name'] = 'ivr_menu_id';
		$apps[$x]['db'][0]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][0]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['description'] = '';
		$apps[$x]['db'][0]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][0]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][1]['description'] = '';
		$apps[$x]['db'][0]['fields'][2]['name'] = 'ivr_menu_name';
		$apps[$x]['db'][0]['fields'][2]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][2]['description'] = '';
		$apps[$x]['db'][0]['fields'][3]['name'] = 'ivr_menu_extension';
		$apps[$x]['db'][0]['fields'][3]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][3]['description'] = '';
		$apps[$x]['db'][0]['fields'][4]['name'] = 'ivr_menu_greet_long';
		$apps[$x]['db'][0]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][4]['description'] = '';
		$apps[$x]['db'][0]['fields'][5]['name'] = 'ivr_menu_greet_short';
		$apps[$x]['db'][0]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][5]['description'] = '';
		$apps[$x]['db'][0]['fields'][6]['name'] = 'ivr_menu_invalid_sound';
		$apps[$x]['db'][0]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][6]['description'] = '';
		$apps[$x]['db'][0]['fields'][7]['name'] = 'ivr_menu_exit_sound';
		$apps[$x]['db'][0]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][7]['description'] = '';
		$apps[$x]['db'][0]['fields'][8]['name'] = 'ivr_menu_confirm_macro';
		$apps[$x]['db'][0]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][8]['description'] = '';
		$apps[$x]['db'][0]['fields'][9]['name'] = 'ivr_menu_confirm_key';
		$apps[$x]['db'][0]['fields'][9]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][9]['description'] = '';
		$apps[$x]['db'][0]['fields'][10]['name'] = 'ivr_menu_tts_engine';
		$apps[$x]['db'][0]['fields'][10]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][10]['description'] = '';
		$apps[$x]['db'][0]['fields'][11]['name'] = 'ivr_menu_tts_voice';
		$apps[$x]['db'][0]['fields'][11]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][11]['description'] = '';
		$apps[$x]['db'][0]['fields'][12]['name'] = 'ivr_menu_confirm_attempts';
		$apps[$x]['db'][0]['fields'][12]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][12]['description'] = '';
		$apps[$x]['db'][0]['fields'][13]['name'] = 'ivr_menu_timeout';
		$apps[$x]['db'][0]['fields'][13]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][13]['description'] = '';
		$apps[$x]['db'][0]['fields'][14]['name'] = 'ivr_menu_inter_digit_timeout';
		$apps[$x]['db'][0]['fields'][14]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][14]['description'] = '';
		$apps[$x]['db'][0]['fields'][15]['name'] = 'ivr_menu_max_failures';
		$apps[$x]['db'][0]['fields'][15]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][15]['description'] = '';
		$apps[$x]['db'][0]['fields'][16]['name'] = 'ivr_menu_max_timeouts';
		$apps[$x]['db'][0]['fields'][16]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][16]['description'] = '';
		$apps[$x]['db'][0]['fields'][17]['name'] = 'ivr_menu_digit_len';
		$apps[$x]['db'][0]['fields'][17]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][17]['description'] = '';
		$apps[$x]['db'][0]['fields'][18]['name'] = 'ivr_menu_direct_dial';
		$apps[$x]['db'][0]['fields'][18]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][18]['description'] = '';
		$apps[$x]['db'][0]['fields'][19]['name'] = 'ivr_menu_enabled';
		$apps[$x]['db'][0]['fields'][19]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][19]['description'] = '';
		$apps[$x]['db'][0]['fields'][20]['name'] = 'ivr_menu_desc';
		$apps[$x]['db'][0]['fields'][20]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][20]['description'] = '';

	//schema details
		$apps[$x]['db'][1]['table'] = 'v_ivr_menu_options';
		$apps[$x]['db'][1]['fields'][0]['name'] = 'ivr_menu_option_id';
		$apps[$x]['db'][1]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][1]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][1]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][1]['fields'][0]['description'] = '';
		$apps[$x]['db'][1]['fields'][1]['name'] = 'ivr_menu_id';
		$apps[$x]['db'][1]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][1]['description'] = '';
		$apps[$x]['db'][1]['fields'][2]['name'] = 'v_id';
		$apps[$x]['db'][1]['fields'][2]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][2]['description'] = '';
		$apps[$x]['db'][1]['fields'][3]['name'] = 'ivr_menu_options_digits';
		$apps[$x]['db'][1]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][3]['description'] = '';
		$apps[$x]['db'][1]['fields'][4]['name'] = 'ivr_menu_options_action';
		$apps[$x]['db'][1]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][4]['description'] = '';
		$apps[$x]['db'][1]['fields'][5]['name'] = 'ivr_menu_options_param';
		$apps[$x]['db'][1]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][5]['description'] = '';
		$apps[$x]['db'][1]['fields'][6]['name'] = 'ivr_menu_options_order';
		$apps[$x]['db'][1]['fields'][6]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][6]['description'] = '';
		$apps[$x]['db'][1]['fields'][7]['name'] = 'ivr_menu_options_desc';
		$apps[$x]['db'][1]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][7]['description'] = '';

?>
