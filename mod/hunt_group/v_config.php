<?php
	//application details
		$apps[$x]['name'] = "Hunt Group";
		$apps[$x]['guid'] = '0610F841-2E27-4C5F-7926-08AB3AAD02E0';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'A Hunt Group is a list of destinations that can be called in sequence or simultaneously.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Hunt Group';
		$apps[$x]['menu'][0]['guid'] = '632F87DE-7F86-B68F-C629-4C2D2B3CE545';
		$apps[$x]['menu'][0]['parent_guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/hunt_group/v_hunt_group.php';
		$apps[$x]['menu'][0]['groups'][] = 'user';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'hunt_group_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';
		
		$apps[$x]['permissions'][1]['name'] = 'hunt_group_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';
	
		$apps[$x]['permissions'][2]['name'] = 'hunt_group_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';
	
		$apps[$x]['permissions'][3]['name'] = 'hunt_group_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

?>