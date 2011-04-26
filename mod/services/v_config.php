<?php
	//application details
		$apps[$x]['name'] = "Services";
		$apps[$x]['guid'] = '35FF1F56-513A-1F6C-A393-955838FF12EE';
		$apps[$x]['category'] = 'System';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Monitor System Services.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Services';
		$apps[$x]['menu'][0]['guid'] = 'C28F14E9-E5AD-E992-0931-D5F5F0DB6A79';
		$apps[$x]['menu'][0]['parent_guid'] = '0438B504-8613-7887-C420-C837FFB20CB1';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/services/v_services.php';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'services_view';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'services_add';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'services_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'services_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

	// CREATE TABLE v_services 
		$apps[$x]['db'][0]['table'] = 'v_services';
		$apps[$x]['db'][0]['fields'][0]['name'] = 'service_id';
		$apps[$x]['db'][0]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][0]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['description'] = '';
		$apps[$x]['db'][0]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][0]['fields'][1]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][1]['description'] = '';
		$apps[$x]['db'][0]['fields'][2]['name'] = 'v_service_name';
		$apps[$x]['db'][0]['fields'][2]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][2]['description'] = '';
		$apps[$x]['db'][0]['fields'][3]['name'] = 'v_service_type';
		$apps[$x]['db'][0]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][3]['description'] = '';
		$apps[$x]['db'][0]['fields'][4]['name'] = 'v_service_data';
		$apps[$x]['db'][0]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][4]['description'] = '';
		$apps[$x]['db'][0]['fields'][5]['name'] = 'v_service_cmd_start';
		$apps[$x]['db'][0]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][5]['description'] = '';
		$apps[$x]['db'][0]['fields'][6]['name'] = 'v_service_cmd_stop';
		$apps[$x]['db'][0]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][6]['description'] = '';
		$apps[$x]['db'][0]['fields'][7]['name'] = 'v_service_cmd_restart';
		$apps[$x]['db'][0]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][7]['description'] = '';
		$apps[$x]['db'][0]['fields'][8]['name'] = 'v_service_desc';
		$apps[$x]['db'][0]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][8]['description'] = '';

?>
