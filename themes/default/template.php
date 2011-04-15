<?php
//get the browser version
	$user_agent = http_user_agent();
	$browser_version =  $user_agent['version'];
	$browser_name =  $user_agent['name'];
	$browser_version_array = explode('.', $browser_version);

//set the doctype
	if ($browser_name == "Internet Explorer") {
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title><!--{title}--></title>
<!--{head}-->
<?php
//get the php self path and set a variable with only the directory path
	$php_self_array = explode ("/", $_SERVER['PHP_SELF']);
	$php_self_dir = '';
	foreach ($php_self_array as &$value) {
		if (substr($value, -4) != ".php") {
			$php_self_dir .= $value."/";
		}
	}
	unset($php_self_array);
	if (strlen(PROJECT_PATH) > 0) {
		$php_self_dir = substr($php_self_dir, strlen(PROJECT_PATH), strlen($php_self_dir));
	}
?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<style type='text/css'>

img {
	/*behavior: url('<!--{project_path}-->/includes/png.htc');*/
	border: none;
}

A {
	color: #004083;
	width: 100%;
}

body {
	margin-top: 0px;
	margin-bottom: 0px;
	margin-right: 0px;
	margin-left: 0px;
	/*background-image: url('<!--{project_path}-->/themes/default/background.jpg');*/
	/*background-repeat: repeat-x;*/
	/*background-attachment: fixed;*/
	/*background-color: #FFFFFF;*/
}

th {
	border-top: 1px solid #444444;
	border-bottom: 1px solid #444444;
	text-align: left;
	color: #FFFFFF;
	font-size: 12px;
	font-family: arial;
	font-weight: bold;
	/*background-color: #506eab;*/
	background-image: url('<!--{project_path}-->/themes/default/background_th.png');
	padding-top: 4px;
	padding-bottom: 4px;
	padding-right: 7px;
	padding-left: 7px;
}

th a:link{ color:#FFFFFF; }
th a:visited{ color:#FFFFFF; }
th a:hover{ color:#FFFFFF; }
th a:active{ color:#FFFFFF; }

td {
	color: #5f5f5f;
	font-size: 12px;
	font-family: arial;
}

INPUT.btn {
	font-family: verdana;
	font-size: 11px;
}

INPUT.button {
	font-family: verdana;
	font-size: 11px;
}

SELECT.txt {
	font-family: arial;
	font-size: 12px;
	width: 98.75%;
	border: solid 1px #CCCCCC;
	color: #666666;
	background-color: #EFEFEF;
	background-repeat:repeat-x;
	height: 19px;
}

TEXTAREA.txt {
	font-family: arial;
	font-size: 12px;
	width: 98.75%;
	border: solid 1px #CCCCCC;
	color: #666666;
	background-color: #EFEFEF;
	background-repeat:repeat-x;
	overflow: auto;
	padding: 4px;

	-moz-border-radius-topleft:5px;
	-webkit-border-top-left-radius:5px;
	border-top-left-radius:5px;

	-moz-border-radius-topright:5px;
	-webkit-border-top-right-radius:5px;
	border-top-right-radius:5px;

	-moz-border-radius-bottomleft:5px;
	-webkit-border-bottom-left-radius:5px;
	border-bottom-left-radius:5px;

	-moz-border-radius-bottomright:5px;
	-webkit-border-bottom-right-radius:5px;
	border-bottom-right-radius:5px;
}

INPUT.txt {
	font-family: arial;
	font-size: 12px;
	width: 98.75%;
	border: solid 1px #CCCCCC;
	color: #666666;
	background-color: #EFEFEF;
	background-repeat:repeat-x;
}

.formfld {
	border: solid 1px #CCCCCC;
	color: #666666;
	background-color: #F7F7F7;
	width: 50%;
	text-align: left;
	/*width: 300px;*/
	padding-left: 4px;

	-moz-border-radius-topleft:5px;
	-webkit-border-top-left-radius:5px;
	border-top-left-radius:5px;

	-moz-border-radius-topright:5px;
	-webkit-border-top-right-radius:5px;
	border-top-right-radius:5px;

	-moz-border-radius-bottomleft:5px;
	-webkit-border-bottom-left-radius:5px;
	border-bottom-left-radius:5px;

	-moz-border-radius-bottomright:5px;
	-webkit-border-bottom-right-radius:5px;
	border-bottom-right-radius:5px;
}

.vncell {
	border-bottom: 1px solid #999999;
	/*background-color: #639BC1;*/
	background-image: url('<!--{project_path}-->/themes/default/background_cell.gif');
	padding-right: 20px;
	padding-left: 8px;
	text-align: left;
	color: #444444;
}

.vncell a:link{ color:#444444; }
.vncell a:visited{ color:#444444; }
.vncell style0 a:hover{ color:#444444; }
.vncell a:active{ color:#444444; }


.vncellreq {
	background-image: url('<!--{project_path}-->/themes/default/background_cell.gif');
	border-bottom: 1px solid #999999;
	background-color: #639BC1;
	padding-right: 20px;
	padding-left: 8px;
	text-align: left;
	font-weight: bold;
	color: #444444;
}

.vtable {
	border-bottom: 1px solid #DFDFDF;
}

.listbg {
	border-bottom: 1px solid #999999;
	font-size: 11px;
	background-color: #990000;
	color: #444444;	
	padding-right: 16px;
	padding-left: 6px;
	padding-top: 4px;
	padding-bottom: 4px;
}

.rowstyle0 {
	background-image: url('<!--{project_path}-->/themes/default/background_cell.gif');
	border-bottom: 1px solid #999999;
	color: #444444;
	text-align: left;
	padding-top: 4px;
	padding-bottom: 4px;
	padding-right: 7px;
	padding-left: 7px;
}

.rowstyle0 a:link{ color:#444444; }
.rowstyle0 a:visited{ color:#444444; }
.rowstyle0 a:hover{ color:#444444; }
.rowstyle0 a:active{ color:#444444; }

.rowstyle1 {
	border-bottom: 1px solid #999999;
	background-color: #FFFFFF;
	text-align: left;
	padding-top: 4px;
	padding-bottom: 4px;
	padding-right: 7px;
	padding-left: 7px;
}

.rowstylebg {
	border-bottom: 1px solid #888888;
	background-color: #5F5F5F;
	color: #FFFFFF;
	text-align: left;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-right: 10px;
	padding-left: 10px;
}

.border {
	border: solid 1px #999999;
	/*background-color: #FFFFFF;*/
}

.headermain {
	/*background-color: #7FAEDE;*/
}

.frm {
	border: solid 1px #CCCCCC;
	color: #666666;
	background-color: #EFEFEF;

}

.smalltext {
	color: #BBBBBB;
	font-size: 11px;
	font-family: arial;
}

table {
	/*background:#ccc;*/
	/*margin:20px;*/
	/*border:#ccc 1px solid;*/
}

table th {
	padding:4px 10px
}
 
table td {
	/*background:#fff;*/
	/*padding:2px 10px 4px 10px*/
}

table tr.even td {
	background:#eee;
	background-image: url('<!--{project_path}-->/themes/default/background_cell.gif');
	border-bottom: 1px solid #999999;
	color: #333333;
}
 
table tr.odd td {
	border-bottom: 1px solid #999999;
	color: #000000;
}

table tr:first-child th:first-child {
	-moz-border-radius-topleft:7px;
	-webkit-border-top-left-radius:7px;
	border-top-left-radius:7px;
}

table tr:first-child th:last-of-type {
	-moz-border-radius-topright:7px;
	-webkit-border-top-right-radius:7px;
	border-top-right-radius:7px;
}

table tr:nth-last-child(-5) td:first-of-type {
	-moz-border-radius-bottomleft:7px;
	-webkit-border-bottom-left-radius:7px;
	border-bottom-left-radius:7px;
}

table tr:nth-last-child(-5) td:first-of-type {
	-moz-border-radius-topleft:7px;
	-webkit-border-top-left-radius:7px;
	border-bottom-top-radius:7px;
}


/* begin the menu css*/

	.menu_bar {
		background-image: url('<!--{project_path}-->/themes/default/background_black.png');
		-webkit-border-radius: 7px 7px 7px 7px;
		-moz-border-radius: 7px 7px 7px 7px;
		border-radius: 7px 7px 7px 7px;
		padding: 3px;
	}

	.menu_bg {
		<?php
			if ($browser_name == "Internet Explorer" && $browser_version_array[0] < '10' ) {
				echo "background-color: #FFFFFF;";
			}
			else {
				if (substr($_SERVER['PHP_SELF'], -9) != "login.php") {
					echo "background-image: url('<!--{project_path}-->/themes/default/menu_background.png');";
				}
				else {
					echo "background-image: url('<!--{project_path}-->/themes/default/login_background.png');";
				}
			}
		?>
		background-repeat: repeat-x;
		background-attachment: fixed;
		/*background-color: #FFFFFF;*/

		opacity: 0.9;
		filter:alpha(opacity=90);
		-moz-opacity:0.9;
		-khtml-opacity: 0.9;
		opacity: 0.9;

		-webkit-border-radius: 7px 7px 7px 7px;
		-moz-border-radius: 7px 7px 7px 7px;
		border-radius: 7px 7px 7px 7px;
		text-align: left;
		padding-top: 15px;
		padding-bottom: 25px;
		padding-left: 5px;
		padding-right:20px;
	}

	.main_content {
		<?php
			if ($browser_name == "Internet Explorer" && $browser_version_array[0] < '10' ) {
				echo "background-color: #FFFFFF;";
			}
			else {
				if (substr($_SERVER['PHP_SELF'], -9) != "login.php") {
					echo "background-image: url('<!--{project_path}-->/themes/default/menu_background.png');";
				}
				else {
					echo "background-image: url('<!--{project_path}-->/themes/default/login_background.png');";
				}
			}
		?>
		background-repeat: repeat-x;
		background-attachment: fixed;
		padding: 20px;
		opacity: 0.9;
		filter:alpha(opacity=90);
		-moz-opacity:0.9;
		-khtml-opacity: 0.9;
		opacity: 0.9;
		-webkit-border-radius: 7px 7px 7px 7px;
		-moz-border-radius: 7px 7px 7px 7px;
		border-radius: 7px 7px 7px 7px;
		text-align: left;
	}

	#menu{
		width:100%;
		float:left;
	}

	#menu a, #menu h2{
		font:bold 11px/16px arial,helvetica,sans-serif;
		display:block;
		/*border-color:#ccc #888 #555 #bbb;*/
		white-space:nowrap;
		margin:0;
		padding:3px 3px 3px 3px;
	}

	#menu h2{
		/*background:#222222 url(<!--{project_path}-->/css/images/expand3.gif) no-repeat 100% 100%;*/
		/*text-transform:uppercase*/
		width:118px;
	}

	#menu h2 h2{
		/*background:#4e4b56 url(<!--{project_path}-->/css/images/expand3.gif) no-repeat 100% 100%;*/
		/*text-transform:uppercase*/
		padding:3px 3px 3px 3px;
	}

	#menu a{
		<?php
		if ($browser_name == "Internet Explorer" && $browser_version_array[0] < '10' ) {
			echo "background:#333333;";
		}
		?>
		text-decoration:none;
		padding-left:7px;
		width:114px;
	}

	#menu a, #menu a:visited{
		color:#cccccc;
	}

	#menu .menu_sub {
		display:none;
		width:124px;
		background:#333333;
		background-color: rgba(20, 20, 20, 0.9);
		-webkit-border-radius: 12px 12px 12px 12px;
		-moz-border-radius: 12px 12px 12px 12px;
		border-radius: 12px 12px 12px 12px;
	}

	#menu a:hover{
		width:114px;
		color:#fd9c03;
		background:#1F1F1F;
	}

	#menu a:active{
		color:#fd9c03;
	}

	#menu ul{
		list-style:none;
		margin:0;
		padding:0;
		float:left;
		width:9em;
	}

	#menu li{
		position:relative;
	}

	#menu ul ul{
		position:absolute;
		z-index:500;
		top:auto;
		display:none;
	}

	#menu ul ul ul{
		top:0;
		left:100%;
	}

	/* Enter the more specific element (div) selector
	on non-anchor hovers for IE5.x to comply with the
	older version of csshover.htc - V1.21.041022. It
	improves IE's performance speed to use the older
	file and this method */

	div#menu h2:hover{
		-webkit-border-radius: 12px 12px 12px 12px;
		-moz-border-radius: 12px 12px 12px 12px;
		border-radius: 12px 12px 12px 12px;
		/*background:#1F1F1F url(<!--{project_path}-->/css/images/expand3.gif) no-repeat -999px -9999px;*/
	}

	div#menu li:hover{
		cursor:pointer;
		z-index:100;
	}

	div#menu li:hover ul ul,
	div#menu li li:hover ul ul,
	div#menu li li li:hover ul ul,
	div#menu li li li li:hover ul ul
	{display:none;}
	/*
	div#menu li:hover ul,
	div#menu li li:hover ul,
	div#menu li li li:hover ul,
	div#menu li li li li:hover ul
	{display:block;}
	*/
	#menu a.x, #menu a.x:visited{
		font-weight:bold;
		color:#000;
		/*background:#999999 url(<!--{project_path}-->/css/images/expand3.gif) no-repeat 100% 100%;*/
	}

	#menu a.x:hover{
		color:#fff;
		background:#000;
	}

	#menu a.x:active{
		color:#060;
		background:#ccc;
	}

	.menu_sub_vertical {
		width:118px;
		text-decoration:none;
		border-color:#ccc;
		border-width: 1px;
		border-bottom-style: solid;
		list-style-image: url(<!--{project_path}-->/themes/default/arrow.png);
		padding: 5px;
		opacity: 1.0;
	}

	.menu_sub_vertical a {
		text-decoration:none;
	}
/* end the menu css*/
</style>
<style type="text/css">
	/* Remove margins from the 'html' and 'body' tags, and ensure the page takes up full screen height */
	html, body {
		height:100%;
		margin:0;
		padding:0;
	}

	/* Set the position and dimensions of the background image. */
	#page-background {
		position:fixed; 
		top:0;
		left:0;
		width:100%;
		height:100%;
	}

	/* Specify the position and layering for the content that needs to 
	appear in front of the background image. Must have a higher z-index 
	value than the background image. Also add some padding to compensate 
	for removing the margin from the 'html' and 'body' tags. */
	#page {
		position:relative; 
		z-index:1;
		padding:10px;
	}

	.vtable {
		position:relative; 
		z-index:1;
		padding:10px;
		color: 000;
		/*
		box-shadow:5px -5px 10px #700;
		-webkit-box-shadow:5px -5px 10px #888;
		-moz-box-shadow:5px -5px 10px #334455;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		*/
		/*border: 1px solid #555555;*/
		/*padding: 10px;*/
		background-color: #FFFFFF;
		filter:alpha(opacity=90);
		-moz-opacity:0.9;
		-khtml-opacity: 0.9;
		opacity: 0.9;
	}
</style>

<script type="text/javascript">
<!--
function jsconfirm(title,msg,url) {
	if (confirm(msg)){
		window.location = url;
	}
	else{
	}
}
//-->
</script>

<SCRIPT language="JavaScript">
<!--
function confirmdelete(url) {
	var confirmed = confirm("Are you sure want to delete this.");
	if (confirmed == true) {
		window.location=url;
	}
}
//-->
</SCRIPT>
</head>
<body>
<?php

//get a random background image
	$dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/themes/default/images/backgrounds';
	$dir_list = opendir($dir);
	$v_background_array = array();
	while (false !== ($file = readdir($dir_list))) {
		if ($file != "." AND $file != ".."){
			$new_path = $dir.'/'.$file;
			$level = explode('/',$new_path);
			if (substr($new_path, -4) == ".svn") {
				//ignore .svn dir and subdir
			}
			elseif (substr($new_path, -3) == ".db") {
				//ignore .db files
			}
			else {
				$new_path = str_replace($_SERVER["DOCUMENT_ROOT"], "", $new_path);
				$v_background_array[] = $new_path;
			}
			if ($x > 1000) { break; };
			$x++;
		}
	}
	if (strlen($_SESSION['background_image'])== 0) {
		$_SESSION['background_image'] = $v_background_array[array_rand($v_background_array, 1)];
	}

	//show the background
	echo "<div id=\"page-background\"><img src=\"".$_SESSION['background_image']."\" width='100%' height='100%' alt=''></div>\n";
?>


<div id="page" align='center'>
<table width='90%' class='border.disabled' border='0' cellpadding='0' cellspacing='7'>
<tr>
<td align='left' valign='top' class='headermain' colspan='2' width='100%' height='70px;'>
	<a href='/'><img src='<!--{project_path}-->/themes/default/logo.png' /></a>
</td>
</tr>

<tr>
<td class='menu_bar' colspan='2' width='100%' height='30px'>
	<!--{menu}-->
</td>
</tr>

<tr>
<td class='menu_bg' valign='top' align='center' width='15%'>

<br />
<?php

//get the current page menu_parent_guid
	if ($db) {
		$sql = "select * from v_menu ";
		$sql .= "where v_id = '$v_id' ";
		if ($php_self_dir == "/") {
			$sql .= "and menustr = '/index2.php' ";
		}
		else {
			$sql .= "and menustr like '".$php_self_dir."%' ";
		}
		$sql .= "order by menuorder asc ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		$count = count($result);
		foreach($result as $field) {
			if (strlen($field['menu_parent_guid']) > 0) {
				$php_self_parent_guid = $field['menu_parent_guid'];
			}
			else {
				$php_self_parent_guid = $field['menu_guid'];
			}
			break;
		}
	}

if (!function_exists('builddbchildmenu2')) {
	function builddbchildmenu2($db, $menulevel, $menu_guid) {
			global $v_id;
			$menulevel = $menulevel+1;

				//--- Begin check for children -----------------------------------------
					$sql = "select * from v_menu ";
					$sql .= "where v_id = '$v_id' ";
					$sql .= "and menu_parent_guid = '$menu_guid' ";
					$sql .= "order by menuorder asc ";
					$prepstatement2 = $db->prepare($sql);
					$prepstatement2->execute();
					$result2 = $prepstatement2->fetchAll();

					if (count($result2) > 0) {
							$dbmenusub .= "<ul>\n";

							foreach($result2 as $row) {
								$menuid = $row['menuid'];
								$menutitle = $row['menutitle'];
								$menustr = $row['menustr'];
								$menucategory = $row['menucategory'];
								$menugroup = $row['menugroup'];
								$menu_guid = $row['menu_guid'];
								$menu_parent_guid = $row['menu_parent_guid'];

								$menuatags = '';
								switch ($menucategory) {
									case "internal":
										$menutags = "href='".PROJECT_PATH.$menustr."'";
										break;
									case "external":
										$menustr = str_replace ("<!--{project_path}-->", PROJECT_PATH, $menustr);
										$menutags = "href='".$menustr."' target='_blank'";
										break;
									case "email":
										$menutags = "href='mailto:$menustr'";
										break;
								}

								if (strlen($menugroup)==0) { //public
									if ($menutitle == "Logout" && strlen($_SESSION["username"]) > 0) {                                  
										$dbmenusub .= "<li class='menu_sub_vertical'>";
										$strchildmenu = builddbchildmenu2($db, $menulevel, $menu_guid);   //get sub menu for children
										if (strlen($strchildmenu) > 1) {
											$dbmenusub .= "<a $menutags>$menutitle</a>";
											$dbmenusub .= $strchildmenu;
											unset($strchildmenu);
										}
										else {
											$dbmenusub .= "<a $menutags>$menutitle</a>";
										}
										$dbmenusub .= "</li>\n";
										
									}        
								}
								else {
									if (ifgroup($menugroup)) { //viewable only to designated group
										
										$dbmenusub .= "<li class='menu_sub_vertical'>";
										$strchildmenu = builddbchildmenu2($db, $menulevel, $menu_guid);   //get sub menu for children
										if (strlen($strchildmenu) > 1) {
											$dbmenusub .= "<a $menutags>$menutitle</a>";
											$dbmenusub .= $strchildmenu;
											unset($strchildmenu);
										}
										else {
											$dbmenusub .= "<a $menutags>$menutitle</a>";
										}
										$dbmenusub .= "</li>\n";
									}
									else {
										//echo "not a member of: ".$menugroup." required by: $menutitle<br>\n";
										//not authorized do not add to menu
									}
									
								}
							}
							unset($sql, $result2);
							$dbmenusub .="</ul>\n";
							return $dbmenusub;
					}
					unset($prepstatement2, $sql);
				//--- End check for children -----------------------------------------
	}
}
$menulevel = '0'; //menu_parent_id
if ($db) {
	if (strlen($php_self_parent_guid) > 0) {
		echo builddbchildmenu2($db, $menulevel, $php_self_parent_guid);
	}
}

?>
</td>
<td class='main_content' align='left' valign='top' width='85%'>
<?php
if ($_SESSION["reload_xml"]) {
	if ($_SERVER["PHP_SELF"] != PROJECT_PATH."/core/status/v_status.php") {
		if(stristr($_SERVER["PHP_SELF"], "_edit") != FALSE) { 
			//found
		}
		else {
			echo "<div align='center'>\n";
			echo "<table border='0' width='400px'>\n";
			echo "<tr>\n";
			echo "<th align='left'>Message</th>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td class='rowstyle1'>\n";

			echo "	<table width='100%' border='0'>\n";
			echo "	<tr>\n";
			echo "	<td width='90%' align='left'>\n";
			echo "			The configuration has been changed. \n";
			echo "			Apply the changes in order for them to take effect. \n";
			echo "	</td>\n";
			echo "	<td align='right'>\n";
			echo "		<input type='button' class='btn' value='Apply Settings' onclick=\"document.location.href='".PROJECT_PATH."/core/status/v_cmd.php?cmd=api+reloadxml';\" />\n";
			echo "	</td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";

			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</div>\n";
		}
	}
}
?>
<!--{body}-->

<br /><br />
<br /><br />
<br /><br />
<br /><br />
<br /><br />
<br /><br />
<br /><br />
<br /><br />
<br /><br />
<br /><br />
<br /><br />
<br /><br />
<br /><br />
<br /><br />
<br /><br />
<br /><br />
<br /><br />

</td>
</tr>
</table>


</td>
</tr>
</table>
<?php
if (substr($_SERVER['PHP_SELF'], -9) != "login.php") {
	echo "<span class='smalltext'>\n";
	echo "	<a class='smalltext' target='_blank' href='http://www.fusionpbx.com'>fusionpbx.com</a>. Copyright 2008 - 2011. All Rights Reserved\n";
	echo "</span>\n";
}
else {
	echo "<!--\n";
	echo "	http://www.fusionpbx.com \n";
	echo "	Copyright 2008 - 2011 \n";
	echo "	All Rights Reserved\n";
	echo "-->\n";
}
?>
</td>
</tr>
</table>
</div>

</td>
</tr>
</table>

<br>

</body>
</html>
