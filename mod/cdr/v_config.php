<?php
	//application details
		$apps[$x]['name'] = "CDR CSV";
		$apps[$x]['guid'] = '08CAAF75-E30B-8B58-E4AD-D9CC76BA2F92';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Call detail records from CSV.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'CDR CSV';
		$apps[$x]['menu'][0]['guid'] = '57D6BEA3-EDD3-13C8-E841-CC4CD852B905';
		$apps[$x]['menu'][0]['parent_guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/cdr/v_cdr.php';
		$apps[$x]['menu'][0]['groups'][] = 'hidden';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'cdr_csv_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';
?>