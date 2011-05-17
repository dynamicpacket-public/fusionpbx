<?php
	//application details
		$apps[$x]['name'] = "Music on Hold";
		$apps[$x]['guid'] = '1DAFE0F8-C08A-289B-0312-15BAF4F20F81';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Add, Delete, or Play Music on hold files.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Music on Hold';
		$apps[$x]['menu'][0]['guid'] = '1CD1D6CB-912D-DB32-56C3-E0D5699FEB9D';
		$apps[$x]['menu'][0]['parent_guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/music_on_hold/v_music_on_hold.php';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'music_on_hold_view';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][1]['name'] = 'music_on_hold_add';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][2]['name'] = 'music_on_hold_delete';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';
?>