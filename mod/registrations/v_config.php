<?php
	//application details
		$apps[$x]['name'] = "Registrations";
		$apps[$x]['guid'] = '5D9E7CD7-629E-3553-4CF5-F26E39FEFA39';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Displays registrations from endpoints.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Registrations';
		$apps[$x]['menu'][0]['guid'] = '17DBFD56-291D-8C1C-BC43-713283A9DD5A';
		$apps[$x]['menu'][0]['parent_guid'] = '0438B504-8613-7887-C420-C837FFB20CB1';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/registrations/v_status_registrations.php?show_reg=1&profile=internal';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'registrations_domain';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'registrations_all';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

?>