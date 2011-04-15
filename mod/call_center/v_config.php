<?php
	//application details
		$apps[$x]['name'] = "Call Center";
		$apps[$x]['guid'] = '95788E50-9500-079E-2807-FD530B0EA370';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = '';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Call Center';
		$apps[$x]['menu'][0]['guid'] = '6C072B29-5B6C-49FC-008E-95E24C77DE99';
		$apps[$x]['menu'][0]['parent_guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/call_center/v_call_center_queue.php';
		$apps[$x]['menu'][0]['groups'][] = 'agent';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'call_center_queues_view';
		$apps[$x]['permissions'][0]['groups'][] = 'agent';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'call_center_queues_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'call_center_queues_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'call_center_queues_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][4]['name'] = 'call_center_agents_view';
		$apps[$x]['permissions'][0]['groups'][] = 'agent';
		$apps[$x]['permissions'][4]['groups'][] = 'admin';
		$apps[$x]['permissions'][4]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][5]['name'] = 'call_center_agents_add';
		$apps[$x]['permissions'][5]['groups'][] = 'admin';
		$apps[$x]['permissions'][5]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][6]['name'] = 'call_center_agents_edit';
		$apps[$x]['permissions'][6]['groups'][] = 'admin';
		$apps[$x]['permissions'][6]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][7]['name'] = 'call_center_agents_delete';
		$apps[$x]['permissions'][7]['groups'][] = 'admin';
		$apps[$x]['permissions'][7]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][8]['name'] = 'call_center_tiers_view';
		$apps[$x]['permissions'][0]['groups'][] = 'agent';
		$apps[$x]['permissions'][8]['groups'][] = 'admin';
		$apps[$x]['permissions'][8]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][9]['name'] = 'call_center_tiers_add';
		$apps[$x]['permissions'][9]['groups'][] = 'admin';
		$apps[$x]['permissions'][9]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][10]['name'] = 'call_center_tiers_edit';
		$apps[$x]['permissions'][10]['groups'][] = 'admin';
		$apps[$x]['permissions'][10]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][11]['name'] = 'call_center_tiers_delete';
		$apps[$x]['permissions'][11]['groups'][] = 'admin';
		$apps[$x]['permissions'][11]['groups'][] = 'superadmin';
?>