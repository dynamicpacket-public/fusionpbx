<?php
	//application details
		$apps[$x]['name'] = "Voicemail Status";
		$apps[$x]['guid'] = '9ECD085E-8C0E-92F6-E727-E90F6BB57773';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Shows which extensions have voicemails and how many.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Voicemail Status';
		$apps[$x]['menu'][0]['guid'] = 'FF4CCD3D-E295-7875-04B4-54EB0C74ADC5';
		$apps[$x]['menu'][0]['parent_guid'] = '0438B504-8613-7887-C420-C837FFB20CB1';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/voicemail_status/v_voicemail.php';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'voicemail_status_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'voicemail_status_delete';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

?>