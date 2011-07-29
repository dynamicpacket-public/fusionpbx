<?php
/*
	FusionPBX
	Version: MPL 1.1

	The contents of this file are subject to the Mozilla Public License Version
	1.1 (the "License"); you may not use this file except in compliance with
	the License. You may obtain a copy of the License at
	http://www.mozilla.org/MPL/

	Software distributed under the License is distributed on an "AS IS" basis,
	WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
	for the specific language governing rights and limitations under the
	License.

	The Original Code is FusionPBX

	The Initial Developer of the Original Code is
	Mark J Crane <markjcrane@fusionpbx.com>
	Portions created by the Initial Developer are Copyright (C) 2008-2010
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/

//if there are multiple domains then update the public dir path to include the domain
	if (count($_SESSION["domains"]) > 1) {
		if (substr($v_dialplan_public_dir, -7) == "/public") {
			//clear out the old xml files
				$v_needle = '_v_';
				if($dh = opendir($v_dialplan_public_dir."/")) {
					$files = Array();
					while($file = readdir($dh)) {
						if($file != "." && $file != ".." && $file[0] != '.') {
							if(is_dir($dir . "/" . $file)) {
								//this is a directory
							} else {
								if (strpos($file, $v_needle) !== false && substr($file,-4) == '.xml') {
									unlink($v_dialplan_public_dir."/".$file);
								}
							}
						}
					}
					closedir($dh);
				}
			//add the domain to the public dir path
				$v_dialplan_public_dir = $v_dialplan_public_dir.'/'.$_SESSION['domains'][$v_id]['domain'];
				$sql .= "update v_system_settings set ";
				$sql .= "v_dialplan_public_dir = '".$v_dialplan_public_dir."' ";
				$sql .= "where v_id = '$v_id' ";
				$db->exec($sql);
				unset($sql);
				if ($display_type == "text") {
					echo "	Public Directory:	added domain\n";
				}
			//synch the xml files
				sync_package_v_public_includes();
		}
	}

//if the public directory doesn't exist then create it
	if (!is_dir($v_dialplan_public_dir)) { mkdir($v_dialplan_public_dir,0777,true); }

//if multiple domains then make sure that the dialplan/public/domain_name.xml file exists
	if (count($_SESSION["domains"]) > 1) {
		//make sure the public xml file includes the domain directory
		$file = $v_conf_dir."/dialplan/public/".$_SESSION['domains'][$v_id]['domain'].".xml";
		if (!file_exists($file)) {
			$fout = fopen($file,"w");
			$tmpxml = "<include>\n";
			$tmpxml .= "  <X-PRE-PROCESS cmd=\"include\" data=\"".$_SESSION['domains'][$v_id]['domain']."/*.xml\"/>\n";
			$tmpxml .= "</include>\n";
			fwrite($fout, $tmpxml);
			fclose($fout);
			unset($tmpxml,$file);
		}
	}

?>