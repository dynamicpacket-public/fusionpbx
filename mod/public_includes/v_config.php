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
?>