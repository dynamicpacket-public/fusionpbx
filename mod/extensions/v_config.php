<?php
	//application details
		$apps[$x]['name'] = "Extensions";
		$apps[$x]['guid'] = 'E68D9689-2769-E013-28FA-6214BF47FCA3';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Used Configure SIP extensions. ';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Extensions';
		$apps[$x]['menu'][0]['guid'] = 'D3036A99-9A9F-2AD6-A82A-1FE7BEBBE2D3';
		$apps[$x]['menu'][0]['parent_guid'] = 'BC96D773-EE57-0CDD-C3AC-2D91ABA61B55';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/extensions/v_extensions.php';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'extension_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'extension_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'extension_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'extension_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

	// CREATE TABLE v_extensions 
		$apps[$x]['db'][0]['table'] = 'v_extensions';
		$apps[$x]['db'][0]['fields'][0]['name'] = 'extension_id';
		$apps[$x]['db'][0]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][0]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['description'] = '';
		$apps[$x]['db'][0]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][0]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][1]['description'] = '';
		$apps[$x]['db'][0]['fields'][2]['name'] = 'extension';
		$apps[$x]['db'][0]['fields'][2]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][2]['description'] = '';
		$apps[$x]['db'][0]['fields'][3]['name'] = 'password';
		$apps[$x]['db'][0]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][3]['description'] = '';
		$apps[$x]['db'][0]['fields'][4]['name'] = 'user_list';
		$apps[$x]['db'][0]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][4]['description'] = '';
		$apps[$x]['db'][0]['fields'][5]['name'] = 'provisioning_list';
		$apps[$x]['db'][0]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][5]['description'] = '';
		$apps[$x]['db'][0]['fields'][6]['name'] = 'mailbox';
		$apps[$x]['db'][0]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][6]['description'] = '';
		$apps[$x]['db'][0]['fields'][7]['name'] = 'vm_password';
		$apps[$x]['db'][0]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][7]['description'] = '';
		$apps[$x]['db'][0]['fields'][8]['name'] = 'accountcode';
		$apps[$x]['db'][0]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][8]['description'] = '';
		$apps[$x]['db'][0]['fields'][9]['name'] = 'effective_caller_id_name';
		$apps[$x]['db'][0]['fields'][9]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][9]['description'] = '';
		$apps[$x]['db'][0]['fields'][10]['name'] = 'effective_caller_id_number';
		$apps[$x]['db'][0]['fields'][10]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][10]['description'] = '';
		$apps[$x]['db'][0]['fields'][11]['name'] = 'outbound_caller_id_name';
		$apps[$x]['db'][0]['fields'][11]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][11]['description'] = '';
		$apps[$x]['db'][0]['fields'][12]['name'] = 'outbound_caller_id_number';
		$apps[$x]['db'][0]['fields'][12]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][12]['description'] = '';
		$apps[$x]['db'][0]['fields'][13]['name'] = 'vm_enabled';
		$apps[$x]['db'][0]['fields'][13]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][13]['description'] = '';
		$apps[$x]['db'][0]['fields'][14]['name'] = 'vm_mailto';
		$apps[$x]['db'][0]['fields'][14]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][14]['description'] = '';
		$apps[$x]['db'][0]['fields'][15]['name'] = 'vm_attach_file';
		$apps[$x]['db'][0]['fields'][15]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][15]['description'] = '';
		$apps[$x]['db'][0]['fields'][16]['name'] = 'vm_keep_local_after_email';
		$apps[$x]['db'][0]['fields'][16]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][16]['description'] = '';
		$apps[$x]['db'][0]['fields'][17]['name'] = 'user_context';
		$apps[$x]['db'][0]['fields'][17]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][17]['description'] = '';
		$apps[$x]['db'][0]['fields'][18]['name'] = 'toll_allow';
		$apps[$x]['db'][0]['fields'][18]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][18]['description'] = '';
		$apps[$x]['db'][0]['fields'][19]['name'] = 'callgroup';
		$apps[$x]['db'][0]['fields'][19]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][19]['description'] = '';
		$apps[$x]['db'][0]['fields'][20]['name'] = 'auth_acl';
		$apps[$x]['db'][0]['fields'][20]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][20]['description'] = '';
		$apps[$x]['db'][0]['fields'][21]['name'] = 'cidr';
		$apps[$x]['db'][0]['fields'][21]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][21]['description'] = '';
		$apps[$x]['db'][0]['fields'][22]['name'] = 'sip_force_contact';
		$apps[$x]['db'][0]['fields'][22]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][22]['description'] = '';
		$apps[$x]['db'][0]['fields'][23]['name'] = 'nibble_account';
		$apps[$x]['db'][0]['fields'][23]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][23]['description'] = '';
		$apps[$x]['db'][0]['fields'][24]['name'] = 'enabled';
		$apps[$x]['db'][0]['fields'][24]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][24]['description'] = '';
		$apps[$x]['db'][0]['fields'][25]['name'] = 'description';
		$apps[$x]['db'][0]['fields'][25]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][25]['description'] = '';

?>
