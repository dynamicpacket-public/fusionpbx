<?php
	//application details
		$apps[$x]['name'] = "Dialplan Manager";
		$apps[$x]['guid'] = '742714E5-8CDF-32FD-462C-CBE7E3D655DB';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'The dialplan is used to setup call destinations based on conditions and context. You can use the dialplan to send calls to gateways, auto attendants, external numbers, to scripts, or any destination.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Dialplan';
		$apps[$x]['menu'][0]['guid'] = 'B94E8BD9-9EB5-E427-9C26-FF7A6C21552A';
		$apps[$x]['menu'][0]['parent_guid'] = '';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/dialplan/v_dialplan_includes.php';
		$apps[$x]['menu'][0]['groups'][] = 'user';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';
	
		$apps[$x]['menu'][1]['title']['en'] = 'Dialplan Manager';
		$apps[$x]['menu'][1]['guid'] = '52929FEE-81D3-4D94-50B7-64842D9393C2';
		$apps[$x]['menu'][1]['parent_guid'] = 'B94E8BD9-9EB5-E427-9C26-FF7A6C21552A';
		$apps[$x]['menu'][1]['category'] = 'internal';
		$apps[$x]['menu'][1]['path'] = '/mod/dialplan/v_dialplan_includes.php';
		$apps[$x]['menu'][1]['groups'][] = 'user';
		$apps[$x]['menu'][1]['groups'][] = 'admin';
		$apps[$x]['menu'][1]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'dialplan_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'dialplan_add';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'dialplan_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'dialplan_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][4]['name'] = 'dialplan_advanced_view';
		$apps[$x]['permissions'][4]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][5]['name'] = 'dialplan_advanced_edit';
		$apps[$x]['permissions'][5]['groups'][] = 'superadmin';
?>