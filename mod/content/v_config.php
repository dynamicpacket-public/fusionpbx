<?php
	//application details
		$apps[$x]['name'] = "Content Manager";
		$apps[$x]['guid'] = '892C8D0B-BFA5-1BDF-E090-A974DA7A7C5E';
		$apps[$x]['category'] = 'System';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Manage Content for any page in the interface.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Content Manager';
		$apps[$x]['menu'][0]['guid'] = '90397352-395C-40F6-2087-887144ABC06D';
		$apps[$x]['menu'][0]['parent_guid'] = '02194288-6D56-6D3E-0B1A-D53A2BC10788';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/content/rsslist.php';
		$apps[$x]['menu'][0]['groups'][] = 'user';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'content_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'content_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'content_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'content_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';
?>