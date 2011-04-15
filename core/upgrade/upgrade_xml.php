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

$dir_count = 0;
$file_count = 0;
$row_count = 0;
$svn_array = '';

clearstatcache();

function recur_dir($dir) {
	global $svn_array;
	global $dir_count;
	global $file_count;
	global $svn_path;
	global $row_count;

	$htmldirlist = '';
	$htmlfilelist = '';
	$dirlist = opendir($dir);
	while ($file = readdir ($dirlist)) {
		if ($file != '.' && $file != '..') {
			$newpath = $dir.'/'.$file;
			$level = explode('/',$newpath);

			if (substr($newpath, -4) == ".svn") {
				//ignore .svn dir and subdir
			}
			elseif (substr($newpath, -3) == ".db") {
				//ignore .db files
			}
			elseif (end($level) == "config.php") {
				//ignore config.php
			}
			elseif (substr(end($level), 0, 12) == "php_service_") {
				//ignore files that are prefixed with 'php_service_'
			}
			else {
				if (is_dir($newpath)) { //directories
					if (strlen($newpath) > 0) {
						$relative_path = substr($newpath, strlen($svn_path), strlen($newpath)); //remove the svn_path

						//echo $relative_path."<br />\n";
						$svn_array[$row_count]['type'] = 'directory';
						$svn_array[$row_count]['path'] = $relative_path;
						$svn_array[$row_count]['last_mod'] = '';
						$svn_array[$row_count]['md5'] = '';
						$svn_array[$row_count]['size'] = '';
						$row_count++;

						$dir_count++;
					}

					$dirname = end($level);
					recur_dir($newpath);
				}
				else { //files
					if (strlen($newpath) > 0) {
						$relative_path = substr($newpath, strlen($svn_path), strlen($newpath)); //remove the svn_path

						//echo $relative_path."<br />\n";
						$svn_array[$row_count]['type'] = 'file';
						$svn_array[$row_count]['path'] = $relative_path;
						$svn_array[$row_count]['last_mod'] = gmdate ("D, d M Y H:i:s T", filemtime($newpath));
						$svn_array[$row_count]['md5'] = md5_file($newpath);
						$svn_array[$row_count]['size'] = filesize($newpath); //round(filesize($newpath)/1024, 2);
						//echo $newpath."<br />\n";
						$row_count++;

						$file_count++;
					}
				}
			}
		}
	}

	closedir($dirlist);
}

$svn_path = "/usr/local/www/fusionpbx/";
//print_r($file_array);
echo recur_dir("/usr/local/www/fusionpbx");


//echo "<pre>\n";
//echo print_r($svn_array);
echo "<xml>\n";
foreach ($svn_array as $row) {
	if (strlen($row['path']) > 0) {
		echo "	<src>\n";
		echo "		<type>".$row['type']."</type>\n";
		echo "		<path>".$row['path']."</path>\n";
		echo "		<last_mod>".$row['last_mod']."</last_mod>\n";
		echo "		<md5>".$row['md5']."</md5>\n";
		echo "		<size>".$row['size']."</size>\n";
		echo "	</src>\n";
	}
}
echo "</xml>\n";
//echo "</pre>\n";

//echo "file count: ".$file_count."<br />\n";
//echo "dir count: ".$dir_count."<br />\n";
exit;

?>