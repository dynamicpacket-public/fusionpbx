<?php
	//application details
		$apps[$x]['name'] = "XML Editor";
		$apps[$x]['guid'] = '784772B5-6004-4FF3-CA21-CAD4ACAB158F';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'XML Editor is an easy ajax based xml editor.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'XML Editor';
		$apps[$x]['menu'][0]['guid'] = '16013877-606A-2A05-7D6A-C1B215839131';
		$apps[$x]['menu'][0]['parent_guid'] = '594D99C5-6128-9C88-CA35-4B33392CEC0F';
		$apps[$x]['menu'][0]['category'] = 'external';
		$apps[$x]['menu'][0]['path'] = '/mod/xml_edit/';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'xml_editor_view';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';
		
		$apps[$x]['permissions'][1]['name'] = 'xml_editor_save';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';
?>