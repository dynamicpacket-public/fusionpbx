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
include "includes/config.php";
session_start();

//define the variable
	$v_menu = '';

$_SESSION["menu"] = ''; //force the menu to generate on every page load
if (strlen($_SESSION["menu"])==0) { //build menu it session menu has no length

	$menuwidth = '110';

	//echo "    <!-- http://www.seoconsultants.com/css/menus/horizontal/ -->\n";
	//echo "    <!-- http://www.tanfa.co.uk/css/examples/css-dropdown-menus.asp -->";

	$v_menu = "";
	$v_menu .= "    <!--[if IE]>\n";
	$v_menu .= "    <style type=\"text/css\" media=\"screen\">\n";
	$v_menu .= "    #menu{float:none;} /* This is required for IE to avoid positioning bug when placing content first in source. */\n";
	$v_menu .= "    /* IE Menu CSS */\n";
	$v_menu .= "    /* csshover.htc file version: V1.21.041022 - Available for download from: http://www.xs4all.nl/~peterned/csshover.html */\n";
	$v_menu .= "    body{behavior:url(/includes/csshover.htc);\n";
	$v_menu .= "    font-size:100%; /* to enable text resizing in IE */\n";
	$v_menu .= "    }\n";
	$v_menu .= "    #menu ul li{float:left;width:100%;}\n";
	$v_menu .= "    #menu h2, #menu a{height:1%;font:bold arial,helvetica,sans-serif;}\n";
	$v_menu .= "    </style>\n";
	$v_menu .= "    <![endif]-->\n";
	//$v_menu .= "    <style type=\"text/css\">@import url(\"/includes/menuh.css\");</style>\n";
	$v_menu .= "\n";

	$v_menu .= "\n";
	$v_menu .= "    <!-- End Grab This -->";

	$v_menu .= "<!-- Begin CSS Horizontal Popout Menu -->\n";
	$v_menu .= "<div id=\"menu\" style=\"position: relative; z-index:199; width:100%;\" align='left'>\n";
	$v_menu .= "\n";

	function builddbmenu($db, $sql, $menulevel) {

		global $v_id;
		$dbmenufull = '';

		if (strlen($sql)==0) { //default sql for base of the menu
			$sql = "select * from v_menu ";
			$sql .= "where v_id = '$v_id' ";
			$sql .= "and menu_parent_guid = '' ";
			$sql .= "or menu_parent_guid is null ";
			$sql .= "order by menuorder asc ";
		}
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();

		foreach($result as $field) {
			$menu_id = $field['menuid'];
			$menu_title = $field['menutitle'];
			$menu_str = $field['menustr'];
			$menu_category = $field['menucategory'];
			$menu_group = $field['menugroup'];
			$menu_desc = $field['menudesc'];
			$menu_guid = $field['menu_guid'];
			$menu_parent_guid = $field['menu_parent_guid'];
			$menu_order = $field['menuorder'];
			$menu_language = $field['menulanguage'];

			$menuatags = '';
			switch ($menu_category) {
				case "internal":
					$menu_tags = "href='".PROJECT_PATH."$menu_str'";
					break;
				case "external":
					$menu_str = str_replace ("<!--{project_path}-->", PROJECT_PATH, $menu_str);
					$menu_tags = "href='$menu_str' target='_blank'";
					break;
				case "email":
					$menu_tags = "href='mailto:$menu_str'";
					break;
			}

			if ($menulevel == "main") {
				$dbmenu  = "<ul class='menu_main'>\n";
				$dbmenu .= "<li>\n";
				if (strlen($_SESSION["username"]) == 0) {
					$dbmenu .= "<a $menu_tags style='padding: 0px 0px; border-style: none; background: none;'><h2 align='center' style=''>$menu_title</h2></a>\n";
				}
				else {
					if ($menu_str == "/login.php" || $menu_str == "/users/signup.php") {
						//hide login and sign-up when the user is logged in
					}
					else {
						$dbmenu .= "<a $menu_tags style='padding: 0px 0px; border-style: none; background: none;'><h2 align='center' style=''>$menu_title</h2></a>\n";
					}
				}
			}

			$menulevel = 0;
			$dbmenu .= builddbchildmenu($db, $menulevel, $menu_guid);

			if ($menulevel == "main") {
				$dbmenu .= "</li>\n";
				$dbmenu .= "</ul>\n\n";
			}

			if (strlen($menu_group)==0) { //public
				$dbmenufull .= $dbmenu;
			}
			else {
				//show only to designated group
				if (ifgroup($menu_group)) { 
					$dbmenufull .= $dbmenu;
				}
				else {
					//not authorized do not add to menu
				}
			}
		} //end for each

		unset($menu_title);
		unset($menu_strv);
		unset($menu_category);
		unset($menu_group);
		unset($menu_guid);
		unset($menu_parent_guid);
		unset($prepstatement, $sql, $result);

		return $dbmenufull;
	}


	function builddbchildmenu($db, $menulevel, $menu_guid) {

		global $v_id;
		$menulevel = $menulevel+1;

		$sql = "select * from v_menu ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and menu_parent_guid = '$menu_guid' ";
		$sql .= "order by menuorder asc ";
		$prepstatement2 = $db->prepare($sql);
		$prepstatement2->execute();
		$result2 = $prepstatement2->fetchAll();
		if (count($result2) > 0) {
			//child menu found
			$dbmenusub .= "<ul class='menu_sub'>\n";

			foreach($result2 as $row) {
				$menu_id = $row['menuid'];
				$menu_title = $row['menutitle'];
				$menu_str = $row['menustr'];
				$menu_category = $row['menucategory'];
				$menu_group = $row['menugroup'];
				$menu_guid = $row['menu_guid'];
				$menu_parent_guid = $row['menu_parent_guid'];

				$menuatags = '';
				switch ($menu_category) {
					case "internal":
						$menu_tags = "href='".PROJECT_PATH."$menu_str'";
						break;
					case "external":
						$menu_str = str_replace ("<!--{project_path}-->", PROJECT_PATH, $menu_str);
						$menu_tags = "href='$menu_str' target='_blank'";
						break;
					case "email":
						$menu_tags = "href='mailto:$menu_str'";
						break;
				}

				if (strlen($menu_group)==0) { //public
						$dbmenusub .= "<li>";

						//get sub menu for children
						$strchildmenu = builddbchildmenu($db, $menulevel, $menu_guid);

						if (strlen($strchildmenu) > 1) {
							$dbmenusub .= "<a $menu_tags>$menu_title</a>";
							$dbmenusub .= $strchildmenu;
							unset($strchildmenu);
						}
						else {
							$dbmenusub .= "<a $menu_tags>$menu_title</a>";
						}
						$dbmenusub .= "</li>\n";
				}
				else {
					//show only to designated group
					if (ifgroup($menu_group)) { 
						$dbmenusub .= "<li>";

						//get sub menu for children
						$strchildmenu = builddbchildmenu($db, $menulevel, $menu_guid);

						if (strlen($strchildmenu) > 1) {
							$dbmenusub .= "<a $menu_tags>$menu_title</a>";
							$dbmenusub .= $strchildmenu;
							unset($strchildmenu);
						}
						else {
							$dbmenusub .= "<a $menu_tags>$menu_title</a>";
						}
						$dbmenusub .= "</li>\n";
					}
					else {
						//not authorized do not add to menu
					}
				}
			}
			unset($sql, $result2);
			$dbmenusub .="</ul>\n";
			return $dbmenusub;
		}
		unset($prepstatement2, $sql);
	}

	$v_menu .= builddbmenu($db, "", "main"); //display the menu
	$v_menu .= "</div>\n";
	$_SESSION["menu"] = $v_menu;
}
else {
	//echo "from session";
}

?>
