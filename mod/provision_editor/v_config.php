<?php
	//application details
		$apps[$x]['name'] = "Provision Editor";
		$apps[$x]['guid'] = 'A1FD4CAF-C3C2-AF10-9630-2F3C62050B02';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Provision Editor is an easy ajax based editor.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Provision Editor';
		$apps[$x]['menu'][0]['guid'] = '57773542-A565-1A29-605D-6535DA1A0870';
		$apps[$x]['menu'][0]['parent_guid'] = '594D99C5-6128-9C88-CA35-4B33392CEC0F';
		$apps[$x]['menu'][0]['category'] = 'external';
		$apps[$x]['menu'][0]['path'] = '/mod/provision_editor/';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'provision_editor_view';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';
		
		$apps[$x]['permissions'][1]['name'] = 'provision_editor_save';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';
?>