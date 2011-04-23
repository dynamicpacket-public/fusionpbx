<?php
	//application details
		$apps[$x]['name'] = "Status";
		$apps[$x]['guid'] = 'CACA8695-9CA7-B058-56E7-4EA94EA1C0E8';
		$apps[$x]['category'] = 'Core';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Displays system information such as RAM, CPU and Hard Drive information.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'System Status';
		$apps[$x]['menu'][0]['guid'] = '5243E0D2-0E8B-277A-912E-9D8B5FCDB41D';
		$apps[$x]['menu'][0]['parent_guid'] = '0438B504-8613-7887-C420-C837FFB20CB1';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/system/system.php';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

		$apps[$x]['menu'][1]['title']['en'] = 'Registrations';
		$apps[$x]['menu'][1]['guid'] = '17DBFD56-291D-8C1C-BC43-713283A9DD5A';
		$apps[$x]['menu'][1]['parent_guid'] = '0438B504-8613-7887-C420-C837FFB20CB1';
		$apps[$x]['menu'][1]['category'] = 'internal';
		$apps[$x]['menu'][1]['path'] = '/core/status/v_status_registrations.php?show_reg=1&profile=internal';
		$apps[$x]['menu'][1]['groups'][] = 'admin';
		$apps[$x]['menu'][1]['groups'][] = 'superadmin';

		$apps[$x]['menu'][2]['title']['en'] = 'SIP Status';
		$apps[$x]['menu'][2]['guid'] = 'B7AEA9F7-D3CF-711F-828E-46E56E2E5328';
		$apps[$x]['menu'][2]['parent_guid'] = '0438B504-8613-7887-C420-C837FFB20CB1';
		$apps[$x]['menu'][2]['category'] = 'internal';
		$apps[$x]['menu'][2]['path'] = '/core/status/v_status.php';
		$apps[$x]['menu'][2]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'system_status_cpu';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'system_status_ram';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'system_status_hdd';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';
?>