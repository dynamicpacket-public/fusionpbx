<?php
	//application details
		$apps[$x]['name'] = "User Manager";
		$apps[$x]['guid'] = '112124B3-95C2-5352-7E9D-D14C0B88F207';
		$apps[$x]['category'] = 'Core';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Add, edit, delete, and search for users.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Login';
		$apps[$x]['menu'][0]['guid'] = 'C85BF816-B88D-40FA-8634-11B456928AFA';
		$apps[$x]['menu'][0]['parent_guid'] = '';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/login.php';
		$apps[$x]['menu'][0]['order'] = '99';

		$apps[$x]['menu'][1]['title']['en'] = 'Logout';
		$apps[$x]['menu'][1]['guid'] = '0D29E9F4-0C9B-9D8D-CD2D-454899DC9BC4';
		$apps[$x]['menu'][1]['parent_guid'] = '02194288-6D56-6D3E-0B1A-D53A2BC10788';
		$apps[$x]['menu'][1]['category'] = 'internal';
		$apps[$x]['menu'][1]['path'] = '/logout.php';
		$apps[$x]['menu'][1]['groups'][] = 'user';
		//$apps[$x]['menu'][1]['groups'][] = 'admin';
		//$apps[$x]['menu'][1]['groups'][] = 'superadmin';

		$apps[$x]['menu'][2]['title']['en'] = 'User Manager';
		$apps[$x]['menu'][2]['guid'] = '0D57CC1E-1874-47B9-7DDD-FE1F57CEC99B';
		$apps[$x]['menu'][2]['parent_guid'] = 'BC96D773-EE57-0CDD-C3AC-2D91ABA61B55';
		$apps[$x]['menu'][2]['category'] = 'internal';
		$apps[$x]['menu'][2]['path'] = '/core/users/index.php';
		$apps[$x]['menu'][2]['groups'][] = 'admin';
		$apps[$x]['menu'][2]['groups'][] = 'superadmin';

		$apps[$x]['menu'][3]['title']['en'] = 'Group Manager';
		$apps[$x]['menu'][3]['guid'] = '3B4ACC6D-827B-F537-BF21-0093D94FFEC7';
		$apps[$x]['menu'][3]['parent_guid'] = '594D99C5-6128-9C88-CA35-4B33392CEC0F';
		$apps[$x]['menu'][3]['category'] = 'internal';
		$apps[$x]['menu'][3]['path'] = '/core/users/grouplist.php';
		$apps[$x]['menu'][3]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'user_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'user_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'user_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'user_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][4]['name'] = 'group_view';
		$apps[$x]['permissions'][4]['groups'][] = 'admin';
		$apps[$x]['permissions'][4]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][5]['name'] = 'group_add';
		$apps[$x]['permissions'][5]['groups'][] = 'admin';
		$apps[$x]['permissions'][5]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][6]['name'] = 'group_edit';
		$apps[$x]['permissions'][6]['groups'][] = 'admin';
		$apps[$x]['permissions'][6]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][7]['name'] = 'group_delete';
		$apps[$x]['permissions'][7]['groups'][] = 'admin';
		$apps[$x]['permissions'][7]['groups'][] = 'superadmin';
?>