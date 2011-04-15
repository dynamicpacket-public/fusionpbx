<?php

// make sure the PATH_SEPARATOR is defined
	if (!defined("PATH_SEPARATOR")) {
		if ( strpos( $_ENV[ "OS" ], "Win" ) !== false ) { define("PATH_SEPARATOR", ";"); } else { define("PATH_SEPARATOR", ":"); }
	}

// make sure the document_root is set
	$_SERVER["SCRIPT_FILENAME"] = str_replace("\\", "/", $_SERVER["SCRIPT_FILENAME"]);
	$_SERVER["DOCUMENT_ROOT"] = str_replace($_SERVER["PHP_SELF"], "", $_SERVER["SCRIPT_FILENAME"]);
	//echo "DOCUMENT_ROOT: ".$_SERVER["DOCUMENT_ROOT"]."<br />\n";
	//echo "PHP_SELF: ".$_SERVER["PHP_SELF"]."<br />\n";
	//echo "SCRIPT_FILENAME: ".$_SERVER["SCRIPT_FILENAME"]."<br />\n";

// if the project directory exists then add it to the include path otherwise add the document root to the include path
	if (is_dir($_SERVER["DOCUMENT_ROOT"].'/fusionpbx')){
		define('PROJECT_PATH', '/fusionpbx');
		set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"].'/fusionpbx' );
	}
	else {
		define('PROJECT_PATH', '');
		set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] );
	}

?>