<?php
	//application details
		$apps[$x]['name'] = "Upgrade Schema";
		$apps[$x]['guid'] = '8B1D7EB5-1009-052C-E1A8-D1F4887A3F5C';
		$apps[$x]['category'] = 'Core';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Upgrade the database schema.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Upgrade Schema';
		$apps[$x]['menu'][0]['guid'] = '8C826E92-BE3C-0944-669A-24E5B915D562';
		$apps[$x]['menu'][0]['parent_guid'] = '594D99C5-6128-9C88-CA35-4B33392CEC0F';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/core/upgrade/upgrade_schema.php';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'upgrade_schema';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'upgrade_svn';

	//schema details
		$apps[$x]['db'][0]['table'] = 'v_src';
		$apps[$x]['db'][0]['fields'][0]['name'] = 'src_id';
		$apps[$x]['db'][0]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][0]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['description'] = '';
		$apps[$x]['db'][0]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][0]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][1]['description'] = '';
		$apps[$x]['db'][0]['fields'][2]['name'] = 'type';
		$apps[$x]['db'][0]['fields'][2]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][2]['description'] = '';
		$apps[$x]['db'][0]['fields'][3]['name'] = 'last_mod';
		$apps[$x]['db'][0]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][3]['description'] = '';
		$apps[$x]['db'][0]['fields'][4]['name'] = 'path';
		$apps[$x]['db'][0]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][4]['description'] = '';

?>
