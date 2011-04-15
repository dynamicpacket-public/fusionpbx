<?php
	//application details
		$apps[$x]['name'] = "Variables";
		$apps[$x]['guid'] = '54E08402-C1B8-0A9D-A30A-F569FC174DD8';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Define variables that are used by the switch, provisioning, and more.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Variables';
		$apps[$x]['menu'][0]['guid'] = '7A4E9EC5-24B9-7200-89B8-D70BF8AFDD8F';
		$apps[$x]['menu'][0]['parent_guid'] = '02194288-6D56-6D3E-0B1A-D53A2BC10788';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/vars/v_vars.php';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'variables_view';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'variables_add';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'variables_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][3]['name'] = 'variables_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';
?>