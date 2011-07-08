<?php
	//application details
		$apps[$x]['name'] = "Script Editor";
		$apps[$x]['guid'] = '17E628EE-CCFA-49C0-29CA-9894A0384B9B';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Script Editor can be used to edit lua, javascript or other scripts.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Script Editor';
		$apps[$x]['menu'][0]['guid'] = 'F1905FEC-0577-DAEF-6045-59D09B7D3F94';
		$apps[$x]['menu'][0]['parent_guid'] = '594D99C5-6128-9C88-CA35-4B33392CEC0F';
		$apps[$x]['menu'][0]['category'] = 'external';
		$apps[$x]['menu'][0]['path'] = '/mod/script_edit/index.php';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'script_editor_view';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'script_editor_save';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';
?>