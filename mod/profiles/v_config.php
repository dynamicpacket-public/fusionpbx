<?php
	//application details
		$apps[$x]['name'] = "SIP Profiles";
		$apps[$x]['guid'] = '5414B2D9-FD7C-F4FA-3C31-EECC387BD1E4';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Use this to configure your SIP profiles.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'SIP Profiles';
		$apps[$x]['menu'][0]['guid'] = '3FE562D4-B9D2-74D2-7DEF-BFF4707831E2';
		$apps[$x]['menu'][0]['parent_guid'] = '594D99C5-6128-9C88-CA35-4B33392CEC0F';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/profiles/v_profiles.php';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'sip_profiles_view';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'sip_profiles_add';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'sip_profiles_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'sip_profiles_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';
?>