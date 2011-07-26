<?php
	//application details
		$apps[$x]['name'] = "Hunt Group";
		$apps[$x]['guid'] = '0610F841-2E27-4C5F-7926-08AB3AAD02E0';
		$apps[$x]['category'] = 'PBX';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'A Hunt Group is a list of destinations that can be called in sequence or simultaneously.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Hunt Group';
		$apps[$x]['menu'][0]['guid'] = '632F87DE-7F86-B68F-C629-4C2D2B3CE545';
		$apps[$x]['menu'][0]['parent_guid'] = 'FD29E39C-C936-F5FC-8E2B-611681B266B5';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/hunt_group/v_hunt_group.php';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'hunt_group_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';
		
		$apps[$x]['permissions'][1]['name'] = 'hunt_group_add';
		$apps[$x]['permissions'][1]['groups'][] = 'admin';
		$apps[$x]['permissions'][1]['groups'][] = 'superadmin';
	
		$apps[$x]['permissions'][2]['name'] = 'hunt_group_edit';
		$apps[$x]['permissions'][2]['groups'][] = 'admin';
		$apps[$x]['permissions'][2]['groups'][] = 'superadmin';
	
		$apps[$x]['permissions'][3]['name'] = 'hunt_group_delete';
		$apps[$x]['permissions'][3]['groups'][] = 'admin';
		$apps[$x]['permissions'][3]['groups'][] = 'superadmin';

		$apps[$x]['permissions'][4]['name'] = 'hunt_group_call_forward';

	//schema details
		$apps[$x]['db'][0]['table'] = 'v_hunt_group';
		$apps[$x]['db'][0]['fields'][0]['name'] = 'hunt_group_id';
		$apps[$x]['db'][0]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][0]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][0]['fields'][0]['description'] = '';
		$apps[$x]['db'][0]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][0]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][0]['fields'][1]['description'] = '';
		$apps[$x]['db'][0]['fields'][2]['name'] = 'huntgroupextension';
		$apps[$x]['db'][0]['fields'][2]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][2]['description'] = '';
		$apps[$x]['db'][0]['fields'][3]['name'] = 'huntgroupname';
		$apps[$x]['db'][0]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][3]['description'] = '';
		$apps[$x]['db'][0]['fields'][4]['name'] = 'huntgrouptype';
		$apps[$x]['db'][0]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][4]['description'] = '';
		$apps[$x]['db'][0]['fields'][5]['name'] = 'huntgroupcontext';
		$apps[$x]['db'][0]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][5]['description'] = '';
		$apps[$x]['db'][0]['fields'][6]['name'] = 'huntgrouptimeout';
		$apps[$x]['db'][0]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][6]['description'] = '';
		$apps[$x]['db'][0]['fields'][7]['name'] = 'huntgrouptimeoutdestination';
		$apps[$x]['db'][0]['fields'][7]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][7]['description'] = '';
		$apps[$x]['db'][0]['fields'][8]['name'] = 'huntgrouptimeouttype';
		$apps[$x]['db'][0]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][8]['description'] = '';
		$apps[$x]['db'][0]['fields'][9]['name'] = 'huntgroupringback';
		$apps[$x]['db'][0]['fields'][9]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][9]['description'] = '';
		$apps[$x]['db'][0]['fields'][10]['name'] = 'huntgroupcidnameprefix';
		$apps[$x]['db'][0]['fields'][10]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][10]['description'] = '';
		$apps[$x]['db'][0]['fields'][11]['name'] = 'huntgrouppin';
		$apps[$x]['db'][0]['fields'][11]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][11]['description'] = '';
		$apps[$x]['db'][0]['fields'][12]['name'] = 'huntgroupcallerannounce';
		$apps[$x]['db'][0]['fields'][12]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][12]['description'] = '';
		$apps[$x]['db'][0]['fields'][13]['name'] = 'hunt_group_call_prompt';
		$apps[$x]['db'][0]['fields'][13]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][13]['description'] = '';
		$apps[$x]['db'][0]['fields'][14]['name'] = 'hunt_group_user_list';
		$apps[$x]['db'][0]['fields'][14]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][14]['description'] = '';
		$apps[$x]['db'][0]['fields'][15]['name'] = 'hunt_group_enabled';
		$apps[$x]['db'][0]['fields'][15]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][15]['description'] = '';
		$apps[$x]['db'][0]['fields'][16]['name'] = 'huntgroupdescr';
		$apps[$x]['db'][0]['fields'][16]['type'] = 'text';
		$apps[$x]['db'][0]['fields'][16]['description'] = '';

		$apps[$x]['db'][1]['table'] = 'v_hunt_group_destinations';
		$apps[$x]['db'][1]['fields'][0]['name'] = 'hunt_group_destination_id';
		$apps[$x]['db'][1]['fields'][0]['type']['pgsql'] = 'serial';
		$apps[$x]['db'][1]['fields'][0]['type']['sqlite'] = 'integer PRIMARY KEY';
		$apps[$x]['db'][1]['fields'][0]['type']['mysql'] = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$apps[$x]['db'][1]['fields'][0]['description'] = '';
		$apps[$x]['db'][1]['fields'][1]['name'] = 'v_id';
		$apps[$x]['db'][1]['fields'][1]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][1]['description'] = '';
		$apps[$x]['db'][1]['fields'][2]['name'] = 'hunt_group_id';
		$apps[$x]['db'][1]['fields'][2]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][2]['description'] = '';
		$apps[$x]['db'][1]['fields'][3]['name'] = 'destinationdata';
		$apps[$x]['db'][1]['fields'][3]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][3]['description'] = '';
		$apps[$x]['db'][1]['fields'][4]['name'] = 'destinationtype';
		$apps[$x]['db'][1]['fields'][4]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][4]['description'] = '';
		$apps[$x]['db'][1]['fields'][5]['name'] = 'destinationprofile';
		$apps[$x]['db'][1]['fields'][5]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][5]['description'] = '';
		$apps[$x]['db'][1]['fields'][6]['name'] = 'destination_timeout';
		$apps[$x]['db'][1]['fields'][6]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][6]['description'] = '';
		$apps[$x]['db'][1]['fields'][7]['name'] = 'destinationorder';
		$apps[$x]['db'][1]['fields'][7]['type'] = 'numeric';
		$apps[$x]['db'][1]['fields'][7]['description'] = '';
		$apps[$x]['db'][1]['fields'][8]['name'] = 'destination_enabled';
		$apps[$x]['db'][1]['fields'][8]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][8]['description'] = '';
		$apps[$x]['db'][1]['fields'][9]['name'] = 'destinationdescr';
		$apps[$x]['db'][1]['fields'][9]['type'] = 'text';
		$apps[$x]['db'][1]['fields'][9]['description'] = '';

?>
