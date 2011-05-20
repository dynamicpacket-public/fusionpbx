<?php
	//application details
		$apps[$x]['name'] = "Exec";
		$apps[$x]['guid'] = '1DD98CA6-95F1-E728-7E8F-137FE18DC23C';
		$apps[$x]['category'] = 'System';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Provides a conventient way to execute system, PHP, and switch commands.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Command';
		$apps[$x]['menu'][0]['guid'] = '06493580-9131-CE57-23CD-D42D69DD8526';
		$apps[$x]['menu'][0]['parent_guid'] = '594D99C5-6128-9C88-CA35-4B33392CEC0F';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/exec/v_exec.php';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'exec_command_line';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'exec_php_command';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'exec_switch';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';
?>