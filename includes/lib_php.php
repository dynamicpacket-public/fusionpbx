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
	//error_reporting(E_ALL ^ E_NOTICE); //hide notices
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ); //hide notices and warnings
		//error_reporting(E_ALL);

	//session handling
		//start the session
			session_start();
		//check whether to timout the session
			if (isset($_SESSION['session_last_activity']) && (time() - $_SESSION['session_last_activity'] > 14400)) {
				// last request was more than 4 hours ago
				session_destroy();   // destroy session data in storage
				session_unset();     // unset $_SESSION variable for the runtime
			}
			$_SESSION['session_last_activity'] = time(); // update last activity time stamp
		//regenerate sessions to avoid session id attacks such as session fixation
			if (!isset($_SESSION['session_created'])) {
				$_SESSION['session_created'] = time();
			} else if (time() - $_SESSION['session_created'] > 1800) {
				// session started more than 30 minutes ago
				session_regenerate_id(true);    // change session ID for the current session an invalidate old session ID
				$_SESSION['session_created'] = time();  // update creation time
			}

	//get the document_root parent directory
		$document_root_parent = join(array_slice(explode("\\",realpath($_SERVER["DOCUMENT_ROOT"])),0,-1), '/');

	//detect the v_secure directory
		if (strlen($dbfilepath) == 0) {
			$tmp_path = $document_root_parent."/secure";
			if (file_exists($tmp_path)) { $v_secure = $tmp_path; }

			$tmp_path = realpath($_SERVER["DOCUMENT_ROOT"]).PROJECT_PATH."/secure";
			if (file_exists($tmp_path)) { $v_secure = $tmp_path; }
		}
		else {
			$v_secure = $dbfilepath;
		}
		$v_secure = str_replace("\\", "/", $v_secure);
		$v_secure = realpath($v_secure);

	//if magic quotes is enabled remove the slashes
		if (get_magic_quotes_gpc()) {
			$in = array(&$_GET, &$_POST, &$_COOKIE);
			while (list($k,$v) = each($in)) {
					foreach ($v as $key => $val) {
							if (!is_array($val)) {
									$in[$k][$key] = stripslashes($val);
									continue;
							}
							$in[] =& $in[$k][$key];
					}
			}
			unset($in);
		}

	//get the list of installed apps from the core and mod directories
		if (count($_SESSION['v_apps']) == 0) {
			$app_dir[] = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/core';
			$app_dir[] = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/mod';
			$x=0;
			foreach ($app_dir as &$dir) {
				$dir_list = opendir($dir);
				while (false !== ($file = readdir($dir_list))) {
					if ($file != "." AND $file != ".."){
						$new_path = $dir.'/'.$file;
						$level = explode('/',$new_path);
						include($new_path.'/v_config.php');
						if ($x > 10000) { break; };
						$x++;
					}
				}
			}
			$_SESSION['v_apps'] = $apps;
		}

?>
