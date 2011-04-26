<?php
	//application details
		$apps[$x]['name'] = "Gateways";
		$apps[$x]['guid'] = '297AB33E-2C2F-8196-552C-F3567D2CAAF8';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Gateways provide access into other voice networks. These can be voice providers or other systems that require SIP registration.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Gateways';
		$apps[$x]['menu'][0]['guid'] = '237A512A-F8FE-1CE4-B5D7-E71C401D7159';
		$apps[$x]['menu'][0]['parent_guid'] = 'BC96D773-EE57-0CDD-C3AC-2D91ABA61B55';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/gateways/v_gateways.php';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'gateways_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'gateways_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'gateways_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'gateways_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

	// CREATE TABLE v_gateways 
		$apps[$x]['db'][0]['table'] = 'v_gateways';
		$apps[$x]['db'][0]['fields'][0]['name'] = 'gateway_id';
		$apps[$x]['db'][0]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][0]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['description'] = '';
		$apps[$x]['db'][0]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][0]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][1]['description'] = '';
		$apps[$x]['db'][0]['fields'][2]['name'] = 'gateway';
		$apps[$x]['db'][0]['fields'][2]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][2]['description'] = '';
		$apps[$x]['db'][0]['fields'][3]['name'] = 'username';
		$apps[$x]['db'][0]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][3]['description'] = '';
		$apps[$x]['db'][0]['fields'][4]['name'] = 'password';
		$apps[$x]['db'][0]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][4]['description'] = '';
		$apps[$x]['db'][0]['fields'][5]['name'] = 'auth_username';
		$apps[$x]['db'][0]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][5]['description'] = '';
		$apps[$x]['db'][0]['fields'][6]['name'] = 'realm';
		$apps[$x]['db'][0]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][6]['description'] = '';
		$apps[$x]['db'][0]['fields'][7]['name'] = 'from_user';
		$apps[$x]['db'][0]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][7]['description'] = '';
		$apps[$x]['db'][0]['fields'][8]['name'] = 'from_domain';
		$apps[$x]['db'][0]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][8]['description'] = '';
		$apps[$x]['db'][0]['fields'][9]['name'] = 'proxy';
		$apps[$x]['db'][0]['fields'][9]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][9]['description'] = '';
		$apps[$x]['db'][0]['fields'][10]['name'] = 'register_proxy';
		$apps[$x]['db'][0]['fields'][10]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][10]['description'] = '';
		$apps[$x]['db'][0]['fields'][11]['name'] = 'outbound_proxy';
		$apps[$x]['db'][0]['fields'][11]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][11]['description'] = '';
		$apps[$x]['db'][0]['fields'][12]['name'] = 'expire_seconds';
		$apps[$x]['db'][0]['fields'][12]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][12]['description'] = '';
		$apps[$x]['db'][0]['fields'][13]['name'] = 'register';
		$apps[$x]['db'][0]['fields'][13]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][13]['description'] = '';
		$apps[$x]['db'][0]['fields'][14]['name'] = 'register_transport';
		$apps[$x]['db'][0]['fields'][14]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][14]['description'] = '';
		$apps[$x]['db'][0]['fields'][15]['name'] = 'retry_seconds';
		$apps[$x]['db'][0]['fields'][15]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][15]['description'] = '';
		$apps[$x]['db'][0]['fields'][16]['name'] = 'extension';
		$apps[$x]['db'][0]['fields'][16]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][16]['description'] = '';
		$apps[$x]['db'][0]['fields'][17]['name'] = 'ping';
		$apps[$x]['db'][0]['fields'][17]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][17]['description'] = '';
		$apps[$x]['db'][0]['fields'][18]['name'] = 'caller_id_in_from';
		$apps[$x]['db'][0]['fields'][18]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][18]['description'] = '';
		$apps[$x]['db'][0]['fields'][19]['name'] = 'supress_cng';
		$apps[$x]['db'][0]['fields'][19]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][19]['description'] = '';
		$apps[$x]['db'][0]['fields'][20]['name'] = 'sip_cid_type';
		$apps[$x]['db'][0]['fields'][20]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][20]['description'] = '';
		$apps[$x]['db'][0]['fields'][21]['name'] = 'extension_in_contact';
		$apps[$x]['db'][0]['fields'][21]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][21]['description'] = '';
		$apps[$x]['db'][0]['fields'][22]['name'] = 'effective_caller_id_name';
		$apps[$x]['db'][0]['fields'][22]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][22]['description'] = '';
		$apps[$x]['db'][0]['fields'][23]['name'] = 'effective_caller_id_number';
		$apps[$x]['db'][0]['fields'][23]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][23]['description'] = '';
		$apps[$x]['db'][0]['fields'][24]['name'] = 'outbound_caller_id_name';
		$apps[$x]['db'][0]['fields'][24]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][24]['description'] = '';
		$apps[$x]['db'][0]['fields'][25]['name'] = 'outbound_caller_id_number';
		$apps[$x]['db'][0]['fields'][25]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][25]['description'] = '';
		$apps[$x]['db'][0]['fields'][26]['name'] = 'context';
		$apps[$x]['db'][0]['fields'][26]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][26]['description'] = '';
		$apps[$x]['db'][0]['fields'][27]['name'] = 'enabled';
		$apps[$x]['db'][0]['fields'][27]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][27]['description'] = '';
		$apps[$x]['db'][0]['fields'][28]['name'] = 'description';
		$apps[$x]['db'][0]['fields'][28]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][28]['description'] = '';

?>
