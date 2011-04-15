<?php
	//application details
		$apps[$x]['name'] = "IVR Menu";
		$apps[$x]['guid'] = 'A5788E9B-58BC-BD1B-DF59-FFF5D51253AB';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'The IVR Menu plays a recording or a pre-defined phrase that presents the caller with options to choose from. Each option has a corresponding destination. The destinations can be extensions, voicemail, IVR menus, hunt groups, FAX extensions, and more.';
	
	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'IVR Menu';
		$apps[$x]['menu'][0]['guid'] = '72259497-A67B-E5AA-CAC2-0F2DCEF16308';
		$apps[$x]['menu'][0]['parent_guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/ivr_menu/v_ivr_menu.php';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';
	
	//permission details
		$apps[$x]['permissions'][0]['name'] = 'ivr_menu_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'ivr_menu_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'ivr_menu_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'ivr_menu_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';
?>