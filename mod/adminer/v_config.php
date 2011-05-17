<?php
	//application details
		$apps[$x]['name'] = "Adminer";
		$apps[$x]['guid'] = '214B9F02-547B-D49D-F4E9-02987D9581C5';
		$apps[$x]['category'] = 'System';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '3.2.2';
		$apps[$x]['license'] = 'http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0';
		$apps[$x]['url'] = 'http://www.adminer.org/';
		$apps[$x]['description']['en'] = 'Adminer (formerly phpMinAdmin) is a full-featured database management tool written in PHP. Adminer is available for MySQL, PostgreSQL, SQLite, MS SQL and Oracle.';
	
	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Adminer';
		$apps[$x]['menu'][0]['guid'] = '1F59D07B-B4F7-4F9E-BDE9-312CF491D66E';
		$apps[$x]['menu'][0]['parent_guid'] = '594D99C5-6128-9C88-CA35-4B33392CEC0F';
		$apps[$x]['menu'][0]['category'] = 'external';
		$apps[$x]['menu'][0]['path'] = '<!--{project_path}-->/mod/adminer/index.php';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'adminer';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

?>
