<?php
	//application details
		$apps[$x]['name'] = "System Settings";
		$apps[$x]['guid'] = '249F01D4-535F-E399-0018-59F9C88D4F52';
		$apps[$x]['category'] = 'Core';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Set the domain and paths.';
	
	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'System Settings';
		$apps[$x]['menu'][0]['guid'] = '03055A51-F8A2-6BDE-2A40-9743B2A2891F';
		$apps[$x]['menu'][0]['parent_guid'] = '594D99C5-6128-9C88-CA35-4B33392CEC0F';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/core/system_settings/v_system_settings.php';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';
	
	//permission details
		$apps[$x]['permissions'][0]['name'] = 'system_settings_view';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'system_settings_add';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'system_settings_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'system_settings_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';
?>