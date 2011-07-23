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
include "root.php";


//copy files from autoload_configs
	//recursive_copy($_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/autoload_configs', $v_conf_dir.'/autoload_configs');
	//$src_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/autoload_configs';
	//$dst_dir = $v_conf_dir."/autoload_configs";
	//$tmp_file = 'cdr_csv.conf.xml'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed to $dst_dir<br />\n"; }

//make a backup copy of the default config used with the 'Restore Default' buttons on the text areas.
	//if (!is_dir($v_conf_dir.".orig")) { mkdir($v_conf_dir.".orig".'',0777,true); }
	//recursive_copy($v_conf_dir, $v_conf_dir.".orig");
	$src_dir = $v_conf_dir;
	$dst_dir = $v_conf_dir.'.orig';
	exec ('cp -RLp '.$src_dir.' '.$dst_dir);

//copy the dialplan default.xml file
	$srcfile = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/dialplan/default.xml';
	$destfile = $v_conf_dir.'/dialplan/default.xml';
	if (file_exists($destfile)) { unlink($destfile); }
	if (!copy($srcfile, $destfile)) {
		//use an alternate method to copy the file
		exec ('cp -R '.$srcfile.' '.$destfile);
	}
	unset($srcfile, $destfile);

//copy sound files
	if (!is_dir($v_sounds_dir.'/custom/8000')) { mkdir($v_sounds_dir.'/custom/8000',0777,true); }
	//recursive_copy($_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/sounds', $v_sounds_dir);
	$src_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/sounds/custom/8000';
	$dst_dir = $v_sounds_dir.'/en/us/callie/custom/8000';
	$tmp_file = 'begin_recording.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'call_forward_has_been_deleted.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'call_forward_has_been_set.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'followme_menu.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'press_1_to_accept_2_to_reject_or_3_for_voicemail.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'please_enter_the_extension_number.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'please_enter_the_pin_number.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'please_enter_the_phone_number.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'please_say_your_name_and_reason_for_calling.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'please_enter_your_pin_number.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'your_pin_number_is_incorect_goodbye.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'please_enter_the_recording_number.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }

	$src_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/sounds/custom/16000';
	$dst_dir = $v_sounds_dir.'/en/us/callie/custom/16000';
	$tmp_file = 'begin_recording.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'call_forward_has_been_deleted.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'call_forward_has_been_set.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'followme_menu.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'press_1_to_accept_2_to_reject_or_3_for_voicemail.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'please_enter_the_extension_number.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'please_enter_the_pin_number.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'please_enter_the_phone_number.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'please_say_your_name_and_reason_for_calling.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'please_enter_your_pin_number.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'your_pin_number_is_incorect_goodbye.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'please_enter_the_recording_number.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }

//copy recordings files
	//recursive_copy($_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/recordings', $v_recordings_dir.'');
	//$src_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/recordings';
	//$dst_dir = $v_recordings_dir;
	//$tmp_file = 'auto_attendant_sales1_support2_billing3.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	//$tmp_file = 'call_transfer.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	//$tmp_file = 'simple_auto_attendant.wav'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }

//get the script files
	if (!is_dir($v_scripts_dir.'')) { mkdir($v_scripts_dir.'',0777,true); }
	//if (!is_dir($v_scripts_dir.'/javascript')) { mkdir($v_scripts_dir.'/javascript',0777,true); }
	//if (!is_dir($v_scripts_dir.'/lua')) { mkdir($v_scripts_dir.'/lua',0777,true); }
	//if (!is_dir($v_scripts_dir.'/perl')) { mkdir($v_scripts_dir.'/perl',0777,true); }
	//recursive_copy($_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/scripts', $v_scripts_dir);
	$src_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/scripts';
	$dst_dir = $v_scripts_dir;
	$tmp_file = 'call_broadcast_originate.js'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'call_forward.lua'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'disa.lua'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'huntgroup_originate.lua'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'intercom.lua'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'originate.js'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'recordings.lua'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'roku.lua'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }
	$tmp_file = 'fifo_member.lua'; if (!copy($src_dir.'/'.$tmp_file, $dst_dir.'/'.$tmp_file)) { echo "copy failed from ".$src_dir."/".$tmp_file." to ".$dst_dir."/".$tmp_file."<br />\n"; }

//copy additional the flash mp3 player
	$srcfile = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/install/htdocs/slim.swf';
	$destfile = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/mod/recordings/slim.swf';
	if (!copy($srcfile, $destfile)) {
		//use an alternate method to copy the file
		exec ('cp -R '.$srcfile.' '.$destfile);
	}
	unset($srcfile, $destfile);

?>