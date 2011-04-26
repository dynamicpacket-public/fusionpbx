<?php
	//application details
		$apps[$x]['name'] = "Inbound Routes";
		$apps[$x]['guid'] = 'C03B422E-13A8-BD1B-E42B-B6B9B4D27CE4';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'The public dialplan is used to route incoming calls to destinations based on one or more conditions and context.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Inbound Routes';
		$apps[$x]['menu'][0]['guid'] = 'B64B2BBF-F99B-B568-13DC-32170515A687';
		$apps[$x]['menu'][0]['parent_guid'] = 'B94E8BD9-9EB5-E427-9C26-FF7A6C21552A';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/public_includes/v_public_includes.php';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'public_includes_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'public_includes_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'public_includes_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'public_includes_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][4]['name'] = 'public_includes_copy';
		$apps[$x]['permissions'][4]['groups'][] = 'superadmin';

	// CREATE TABLE v_public_includes 
		$apps[$x]['db'][0]['table'] = 'v_public_includes';
		$apps[$x]['db'][0]['fields'][0]['name'] = 'public_include_id';
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
		$apps[$x]['db'][0]['fields'][3]['name'] = 'extensioncontinue';
		$apps[$x]['db'][0]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][3]['description'] = '';
		$apps[$x]['db'][0]['fields'][4]['name'] = 'publicorder';
		$apps[$x]['db'][0]['fields'][4]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][4]['description'] = '';
		$apps[$x]['db'][0]['fields'][5]['name'] = 'context';
		$apps[$x]['db'][0]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][5]['description'] = '';
		$apps[$x]['db'][0]['fields'][6]['name'] = 'opt1name';
		$apps[$x]['db'][0]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][6]['description'] = '';
		$apps[$x]['db'][0]['fields'][7]['name'] = 'opt1value';
		$apps[$x]['db'][0]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][7]['description'] = '';
		$apps[$x]['db'][0]['fields'][8]['name'] = 'enabled';
		$apps[$x]['db'][0]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][8]['description'] = '';
		$apps[$x]['db'][0]['fields'][9]['name'] = 'descr';
		$apps[$x]['db'][0]['fields'][9]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][9]['description'] = '';

	// CREATE TABLE v_public_includes_details 
		$apps[$x]['db'][1]['table'] = 'v_public_includes_details';
		$apps[$x]['db'][1]['fields'][0]['name'] = 'public_includes_detail_id';
		$apps[$x]['db'][1]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][1]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][1]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][1]['fields'][0]['description'] = '';
		$apps[$x]['db'][1]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][1]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][1]['description'] = '';
		$apps[$x]['db'][1]['fields'][2]['name'] = 'public_include_id';
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
		$apps[$x]['db'][1]['fields'][7]['name'] = 'fieldorder';
		$apps[$x]['db'][1]['fields'][7]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][7]['description'] = '';
		$apps[$x]['db'][1]['fields'][8]['name'] = 'fieldbreak';
		$apps[$x]['db'][1]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][8]['description'] = '';

?>
