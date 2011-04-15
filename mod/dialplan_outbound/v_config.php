<?php
	//application details
		$apps[$x]['name'] = "Outbound Routes";
		$apps[$x]['guid'] = '8C914EC3-9FC0-8AB5-4CDA-6C9288BDC9A3';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Outbound dialplans have one or more conditions that are matched to attributes of a call. When a call matches the conditions the call is then routed to the gateway.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Outbound Routes';
		$apps[$x]['menu'][0]['guid'] = '17E14094-1D57-1106-DB2A-A787D34015E9';
		$apps[$x]['menu'][0]['parent_guid'] = 'B94E8BD9-9EB5-E427-9C26-FF7A6C21552A';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/dialplan_outbound/v_dialplan_outbound.php';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'outbound_route_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'outbound_route_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'outbound_route_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';
		
		$apps[$x]['permissions'][3]['name'] = 'outbound_route_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';
?>