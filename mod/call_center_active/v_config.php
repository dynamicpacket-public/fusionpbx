<?php
	//application details
		$apps[$x]['name'] = "Call Center Active";
		$apps[$x]['guid'] = '3F159F62-CA2D-41B8-B3F0-C5519CEBBC5A';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Shows active calls, and agents in the call center queue.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Active Call Center';
		$apps[$x]['menu'][0]['guid'] = '7FB0DD87-E984-9980-C512-2C76B887AEB2';
		$apps[$x]['menu'][0]['parent_guid'] = '0438B504-8613-7887-C420-C837FFB20CB1';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/call_center_active/v_call_center_queue.php';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'call_center_active_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

?>