<?php
	//application details
		$apps[$x]['name'] = "Sign Up";
		$apps[$x]['guid'] = 'D308E9C6-D907-5BA7-B3BE-6D3E09CF01AA';
		$apps[$x]['category'] = 'System';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Allows customers on the internet to signup for a user account.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Sign Up';
		$apps[$x]['menu'][0]['guid'] = 'A8F49F02-9BFB-65FF-4CD3-85DC3354E4C1';
		$apps[$x]['menu'][0]['parent_guid'] = '';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/users/usersupdate.php';
		$apps[$x]['menu'][0]['groups'][] = 'disabled';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'signup';
?>
