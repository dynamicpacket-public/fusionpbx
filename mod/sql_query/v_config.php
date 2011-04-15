<?php
	//application details
		$apps[$x]['name'] = "SQL Query";
		$apps[$x]['guid'] = 'A8B8CA29-083D-FB9B-5552-CC272DE18EA6';
		$apps[$x]['category'] = 'System';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Run Structur Query Language commands.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'SQL Query';
		$apps[$x]['menu'][0]['guid'] = 'A894FED7-5A17-F695-C3DE-E32CE58B3794';
		$apps[$x]['menu'][0]['parent_guid'] = '594D99C5-6128-9C88-CA35-4B33392CEC0F';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/sql_query/v_sql_query.php';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'sql_query_execute';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'sql_query_backup';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';
?>