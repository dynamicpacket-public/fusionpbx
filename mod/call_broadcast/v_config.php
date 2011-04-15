<?php
	//application details
		$apps[$x]['name'] = "Call Broadcast";
		$apps[$x]['guid'] = 'EFC11F6B-ED73-9955-4D4D-3A1BED75A056';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Deprecated';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Call Broadcast';
		$apps[$x]['menu'][0]['guid'] = '50153BBF-78C5-B49E-7BD9-4B3E4B1134E6';
		$apps[$x]['menu'][0]['parent_guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';
		$apps[$x]['menu'][0]['path'] = '/mod/call_broadcast/v_call_broadcast.php';
	
	//permission details
		$apps[$x]['permissions'][0]['name'] = 'call_broadcast_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'call_broadcast_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'call_broadcast_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'call_broadcast_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';
?>