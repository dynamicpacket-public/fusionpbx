<?php
	//application details
		$apps[$x]['name'] = "XMPP Manager";
		$apps[$x]['guid'] = '740F1C0D-6D82-FCDE-3873-0FC9779789EC';
		$apps[$x]['category'] = '';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Allow User to Open a Flash Phone for his Extension.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'XMPP Manager';
		$apps[$x]['menu'][0]['guid'] = '1808365B-0F7C-7555-89D0-31B3D9A75ABB';
		$apps[$x]['menu'][0]['parent_guid'] = 'BC96D773-EE57-0CDD-C3AC-2D91ABA61B55';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/xmpp/v_xmpp.php';
		$apps[$x]['menu'][0]['groups'][] = 'user';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'xmpp_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'xmpp_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'xmpp_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'xmpp_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

	//database details
		$apps[$x]['db'][0]['table'] = 'v_xmpp';
		$apps[$x]['db'][0]['fields'][0]['name'] = 'xmpp_profile_id';
		$apps[$x]['db'][0]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][0]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY AUTOINCREMENT';
		$apps[$x]['db'][0]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['description'] = 'primary key';
		$apps[$x]['db'][0]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][0]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][1]['description'] = 'primary key';
		$apps[$x]['db'][0]['fields'][2]['name'] = 'profile_name';
		$apps[$x]['db'][0]['fields'][2]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][2]['description'] = '';
		$apps[$x]['db'][0]['fields'][3]['name'] = 'username';
		$apps[$x]['db'][0]['fields'][3]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][3]['description'] = '';
		$apps[$x]['db'][0]['fields'][4]['name'] = 'password';
		$apps[$x]['db'][0]['fields'][4]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][4]['description'] = '';
		$apps[$x]['db'][0]['fields'][5]['name'] = 'dialplan';
		$apps[$x]['db'][0]['fields'][5]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][5]['description'] = '';
		$apps[$x]['db'][0]['fields'][6]['name'] = 'context';
		$apps[$x]['db'][0]['fields'][6]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][6]['description'] = '';
		$apps[$x]['db'][0]['fields'][7]['name'] = 'rtp_ip';
		$apps[$x]['db'][0]['fields'][7]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][7]['description'] = '';
		$apps[$x]['db'][0]['fields'][8]['name'] = 'ext_rtp_ip';
		$apps[$x]['db'][0]['fields'][8]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][8]['description'] = '';
		$apps[$x]['db'][0]['fields'][9]['name'] = 'auto_login';
		$apps[$x]['db'][0]['fields'][9]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][9]['description'] = '';
		$apps[$x]['db'][0]['fields'][10]['name'] = 'sasl_type';
		$apps[$x]['db'][0]['fields'][10]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][10]['description'] = '';
		$apps[$x]['db'][0]['fields'][11]['name'] = 'xmpp_server';
		$apps[$x]['db'][0]['fields'][11]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][11]['description'] = '';
		$apps[$x]['db'][0]['fields'][12]['name'] = 'tls_enable';
		$apps[$x]['db'][0]['fields'][12]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][12]['description'] = '';
		$apps[$x]['db'][0]['fields'][13]['name'] = 'usr_rtp_timer';
		$apps[$x]['db'][0]['fields'][13]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][13]['description'] = '';
		$apps[$x]['db'][0]['fields'][14]['name'] = 'default_exten';
		$apps[$x]['db'][0]['fields'][14]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][14]['description'] = '';
		$apps[$x]['db'][0]['fields'][15]['name'] = 'vad';
		$apps[$x]['db'][0]['fields'][15]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][15]['description'] = 'in/out/both';
		$apps[$x]['db'][0]['fields'][16]['name'] = 'avatar';
		$apps[$x]['db'][0]['fields'][16]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][16]['description'] = 'example: /path/to/tiny.jpg';
		$apps[$x]['db'][0]['fields'][17]['name'] = 'candidate_acl';
		$apps[$x]['db'][0]['fields'][17]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][17]['description'] = '';
		$apps[$x]['db'][0]['fields'][18]['name'] = 'local_network_acl';
		$apps[$x]['db'][0]['fields'][18]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][18]['description'] = '';
		$apps[$x]['db'][0]['fields'][19]['name'] = 'enabled';
		$apps[$x]['db'][0]['fields'][19]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][19]['description'] = '';
		$apps[$x]['db'][0]['fields'][20]['name'] = 'description';
		$apps[$x]['db'][0]['fields'][20]['type'] = 'text'; 
		$apps[$x]['db'][0]['fields'][20]['description'] = '';

?>
