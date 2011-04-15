<?php
	//application details
		$apps[$x]['name'] = "Traffic Graph";
		$apps[$x]['guid'] = '99932B6E-6560-A472-25DD-22E196262187';
		$apps[$x]['category'] = 'System';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '';
		$apps[$x]['license'] = 'Mozilla Public License 1.1';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en'] = 'Uses SVG to show the network traffic.';

	//menu details
		$apps[$x]['menu'][0]['title']['en'] = 'Traffic Graph';
		$apps[$x]['menu'][0]['guid'] = '05AC3828-DC2B-C0E2-282C-79920F5349E0';
		$apps[$x]['menu'][0]['parent_guid'] = '0438B504-8613-7887-C420-C837FFB20CB1';
		$apps[$x]['menu'][0]['category'] = 'internal';
		$apps[$x]['menu'][0]['path'] = '/mod/traffic_graph/status_graph.php?width=660&height=330';
		$apps[$x]['menu'][0]['groups'][] = 'admin';
		$apps[$x]['menu'][0]['groups'][] = 'superadmin';

	//permission details
		$apps[$x]['permissions'][0]['name'] = 'traffic_graph_view';
		$apps[$x]['permissions'][0]['groups'][] = 'admin';
		$apps[$x]['permissions'][0]['groups'][] = 'superadmin';
?>