<?php
	//application details
		$apps[$x]['name'] = "PHP Editor";
		$apps[$x]['guid'] = '0A36722F-EEE1-889E-BAA9-2CE05B09E365';
		$apps[$x]['category'] = 'System';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'PHP Editor for files in the main website directory.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'PHP Editor';
		$apps[$x]['menu'][0]['guid'] = 'EAE1F2D6-789B-807C-CC26-44501E848693';
		$apps[$x]['menu'][0]['parent_guid'] = '594D99C5-6128-9C88-CA35-4B33392CEC0F';
		$apps[$x]['menu'][0]['category'] = 'external';
		$apps[$x]['menu'][0]['path'] = '/mod/php_edit/index.php';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'php_editor_view';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'php_editor_save';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';
?>