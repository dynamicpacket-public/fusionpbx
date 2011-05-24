<?php
	//application details
		$apps[$x]['name'] = "SIP Status";
		$apps[$x]['guid'] = 'CACA8695-9CA7-B058-56E7-4EA94EA1C0E8';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Displays system information such as RAM, CPU and Hard Drive information.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'SIP Status';
		$apps[$x]['menu'][0]['guid'] = 'B7AEA9F7-D3CF-711F-828E-46E56E2E5328';
		$apps[$x]['menu'][0]['parent_guid'] = '0438B504-8613-7887-C420-C837FFB20CB1';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/sip_status/v_sip_status.php';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'system_status_sofia_status';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'system_status_sofia_status_profile';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'sip_status_switch_status';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';
?>