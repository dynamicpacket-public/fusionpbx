<?php
	//application details
		$apps[$x]['name'] = "Menu Manager";
		$apps[$x]['guid'] = 'F4B3B3D2-6287-489C-2A00-64529E46F2D7';
		$apps[$x]['category'] = 'Core';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'The menu can be customized using this tool.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Menu Manager';
		$apps[$x]['menu'][0]['guid'] = 'DA3A9AB4-C28E-EA8D-50CC-E8405AC8E76E';
		$apps[$x]['menu'][0]['parent_guid'] = '02194288-6D56-6D3E-0B1A-D53A2BC10788';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/core/menu/menu_list.php';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

		$apps[$x]['menu'][1]['title']['en'] = 'System';
		$apps[$x]['menu'][1]['guid'] = '02194288-6D56-6D3E-0B1A-D53A2BC10788';
		$apps[$x]['menu'][1]['parent_guid'] = '';
		$apps[$x]['menu'][1]['category'] = 'internal';
		$apps[$x]['menu'][1]['path'] = '/index2.php';
		$apps[$x]['menu'][1]['order'] = '0';
		$apps[$x]['menu'][1]['groups'][] = 'user';
		$apps[$x]['menu'][1]['groups'][] = 'admin';
		$apps[$x]['menu'][1]['groups'][] = 'superadmin';

		$apps[$x]['menu'][2]['title']['en'] = 'Accounts';
		$apps[$x]['menu'][2]['guid'] = 'BC96D773-EE57-0CDD-C3AC-2D91ABA61B55';
		$apps[$x]['menu'][2]['parent_guid'] = '';
		$apps[$x]['menu'][2]['category'] = 'internal';
		$apps[$x]['menu'][2]['path'] = '/mod/extensions/v_extensions.php';
		$apps[$x]['menu'][2]['order'] = '1';
		$apps[$x]['menu'][2]['groups'][] = 'admin';
		$apps[$x]['menu'][2]['groups'][] = 'superadmin';

		$apps[$x]['menu'][3]['title']['en'] = 'Dialplan';
		$apps[$x]['menu'][3]['guid'] = 'B94E8BD9-9EB5-E427-9C26-FF7A6C21552A';
		$apps[$x]['menu'][3]['parent_guid'] = '';
		$apps[$x]['menu'][3]['category'] = 'internal';
		$apps[$x]['menu'][3]['path'] = '/mod/dialplan/v_dialplan_includes.php';
		$apps[$x]['menu'][3]['order'] = '2';
		$apps[$x]['menu'][3]['groups'][] = 'admin';
		$apps[$x]['menu'][3]['groups'][] = 'superadmin';

		$apps[$x]['menu'][4]['title']['en'] = 'Status';
		$apps[$x]['menu'][4]['guid'] = '0438B504-8613-7887-C420-C837FFB20CB1';
		$apps[$x]['menu'][4]['parent_guid'] = '';
		$apps[$x]['menu'][4]['category'] = 'internal';
		$apps[$x]['menu'][4]['path'] = '/mod/calls_active/v_calls_active_extensions.php';
		$apps[$x]['menu'][4]['order'] = '4';
		$apps[$x]['menu'][4]['groups'][] = 'user';
		$apps[$x]['menu'][4]['groups'][] = 'admin';
		$apps[$x]['menu'][4]['groups'][] = 'superadmin';

		$apps[$x]['menu'][5]['title']['en'] = 'Advanced';
		$apps[$x]['menu'][5]['guid'] = '594D99C5-6128-9C88-CA35-4B33392CEC0F';
		$apps[$x]['menu'][5]['parent_guid'] = '';
		$apps[$x]['menu'][5]['category'] = 'internal';
		$apps[$x]['menu'][5]['path'] = '/mod/exec/v_exec.php';
		$apps[$x]['menu'][5]['order'] = '5';
		$apps[$x]['menu'][5]['groups'][] = 'user';
		$apps[$x]['menu'][5]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'menu_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'menu_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'menu_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'menu_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][4]['name'] = 'menu_restore';
		$apps[$x]['permissions'][4]['groups'][] = 'superadmin';
?>