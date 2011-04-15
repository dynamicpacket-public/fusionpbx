<?php
	//application details
		$apps[$x]['name'] = "FlashPhoner";
		$apps[$x]['guid'] = 'FE45C76C-1A6E-0F0E-73DD-5B542AED2DD5';
		$apps[$x]['category'] = '';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Allow User to Open a Flash Phone for his Extension.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'FlashPhoner';
		$apps[$x]['menu'][0]['guid'] = '55E19438-63B9-DA36-415B-B0219F304426';
		$apps[$x]['menu'][0]['parent_guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/flashphoner/flashphoner.php';
		$apps[$x]['menu'][0]['groups'][] = 'user';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'flashphoner_view';
		$apps[$x]['permissions'][0]['groups'][] = 'user';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		//$apps[$x]['permissions'][1]['name'] = 'click_to_call_call';
		//$apps[$x]['permissions'][1]['groups'][] = 'user';
		//$apps[$x]['permissions'][1]['groups'][] = 'admin';
		//$apps[$x]['permissions'][1]['groups'][] = 'superadmin';
?>
