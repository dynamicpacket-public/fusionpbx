<?php
	//application details
		$apps[$x]['name'] = "XML CDR";
		$apps[$x]['guid'] = '4A085C51-7635-FF03-F67B-86E834422848';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Call Detail Records with all information about the call.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Apps';
		$apps[$x]['menu'][0]['guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][0]['parent_guid'] = '';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/xml_cdr/v_xml_cdr.php';
		$apps[$x]['menu'][2]['order'] = '3';
		$apps[$x]['menu'][0]['groups'][] = 'user';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';
	
		$apps[$x]['menu'][1]['title']['en'] = 'Call Detail Records';
		$apps[$x]['menu'][1]['guid'] = '8F80E71A-31A5-6432-47A0-7F5A7B271F05';
		$apps[$x]['menu'][1]['parent_guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][1]['category'] = 'internal';
		$apps[$x]['menu'][1]['path'] = '/mod/xml_cdr/v_xml_cdr.php';
		$apps[$x]['menu'][1]['groups'][] = 'user';
		$apps[$x]['menu'][1]['groups'][] = 'admin';
		$apps[$x]['menu'][1]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'xml_cdr_view';
		$apps[$x]['permissions'][0]['groups'][] = 'user';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

?>