<?php
	//application details
		$apps[$x]['name'] = "Auto Attendant";
		$apps[$x]['guid'] = 'D998A959-3DB2-6E02-A53F-795E7B8E11D4';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Deprecated';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Auto Attendant';
		$apps[$x]['menu'][0]['guid'] = '963785C6-4DF6-9BF1-E4A7-7183774CC741';
		$apps[$x]['menu'][0]['parent_guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['groups'][] = 'hidden';
		$apps[$x]['menu'][0]['path'] = '/mod/auto_attendant/v_auto_attendant.php';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'auto_attendant_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'auto_attendant_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'auto_attendant_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'auto_attendant_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';
?>