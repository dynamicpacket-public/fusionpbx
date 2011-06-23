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
require_once "includes/config.php";
require_once "includes/checkauth.php";
require_once "config.php";
if (permission_exists('content_add')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

if (count($_POST)>0) {

	//get the http values and set them as variables
		$rsssubcategory = check_str($_POST["rsssubcategory"]);
		$rsstitle = check_str($_POST["rsstitle"]);
		$rsslink = check_str($_POST["rsslink"]);
		$rssdesc = check_str($_POST["rssdesc"]);
		$rssimg = check_str($_POST["rssimg"]);
		$rssoptional1 = check_str($_POST["rssoptional1"]);
		$rssoptional2 = check_str($_POST["rssoptional2"]);
		$rssoptional3 = check_str($_POST["rssoptional3"]);
		$rssoptional4 = check_str($_POST["rssoptional4"]);
		$rssoptional5 = check_str($_POST["rssoptional5"]);
		$rssgroup = check_str($_POST["rssgroup"]);
		$rssorder = check_str($_POST["rssorder"]);

	//insert the data into the database
		$sql = "insert into v_rss ";
		$sql .= "(";
		$sql .= "v_id, ";
		$sql .= "rsscategory, ";
		$sql .= "rsssubcategory, ";
		$sql .= "rsstitle, ";
		$sql .= "rsslink, ";
		$sql .= "rssdesc, ";
		$sql .= "rssimg, ";
		$sql .= "rssoptional1, ";
		$sql .= "rssoptional2, ";
		$sql .= "rssoptional3, ";
		$sql .= "rssoptional4, ";
		$sql .= "rssoptional5, ";
		$sql .= "rssgroup, ";
		$sql .= "rssorder, ";
		$sql .= "rssadddate, ";
		$sql .= "rssadduser ";
		$sql .= ")";
		$sql .= "values ";
		$sql .= "(";
		$sql .= "'$v_id', ";
		$sql .= "'$rsscategory', ";
		$sql .= "'$rsssubcategory', ";
		$sql .= "'$rsstitle', ";
		$sql .= "'$rsslink', ";
		$sql .= "'$rssdesc', ";
		$sql .= "'$rssimg', ";
		$sql .= "'$rssoptional1', ";
		$sql .= "'$rssoptional2', ";
		$sql .= "'$rssoptional3', ";
		$sql .= "'$rssoptional4', ";
		$sql .= "'$rssoptional5', ";
		$sql .= "'$rssgroup', ";
		$sql .= "'$rssorder', ";
		$sql .= "now(), ";
		$sql .= "'".$_SESSION["username"]."' ";
		$sql .= ")";
		$db->exec(check_sql($sql));
		unset($sql);

	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"2;url=rsslist.php\">\n";
	echo "<div align='center'>";
	echo "Add Complete";
	echo "</div>";
	require_once "includes/footer.php";
	return;
}

	require_once "includes/header.php";
	if (is_dir($_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/tiny_mce')) {
		if ($rssoptional1 == "text/html") {
			require_once "includes/wysiwyg.php";
		}
	}
	else {
		//--- Begin: Edit Area -----------------------------------------------------
			echo "    <script language=\"javascript\" type=\"text/javascript\" src=\"".PROJECT_PATH."/includes/edit_area/edit_area_full.js\"></script>\n";
			echo "    <!-- -->\n";

			echo "	<script language=\"Javascript\" type=\"text/javascript\">\n";
			echo "		editAreaLoader.init({\n";
			echo "			id: \"rssdesc\" // id of the textarea to transform //, |, help\n";
			echo "			,start_highlight: true\n";
			echo "			,font_size: \"8\"\n";
			echo "			,allow_toggle: false\n";
			echo "			,language: \"en\"\n";
			echo "			,syntax: \"html\"\n";
			echo "			,toolbar: \"search, go_to_line,|, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help\" //new_document,\n";
			echo "			,plugins: \"charmap\"\n";
			echo "			,charmap_default: \"arrows\"\n";
			echo "    });\n";
			echo "    </script>";
		//--- End: Edit Area -------------------------------------------------------
	}

	echo "<div align='center'>";
	echo "<table border='0' width='100%' cellpadding='0' cellspacing='2'>\n";

	echo "<tr class='border'>\n";
	echo "	<td align=\"left\" width='100%'>\n";
	//echo "      <br>";


	echo "<form method='post' action=''>";
	echo "<table width='100%' cellpadding='6' cellspacing='0'>";

	echo "<tr>\n";
	echo "<td width='30%' nowrap valign='top'><b>Content Add</b></td>\n";
	echo "<td width='70%' align='right' valign='top'><input type='button' class='btn' name='' alt='back' onclick=\"window.location='rsslist.php'\" value='Back'><br /><br /></td>\n";
	echo "</tr>\n";
	//echo "	<tr>";
	//echo "		<td class='vncellreq'>Category:</td>";
	//echo "		<td class='vtable'><input type='text' class='formfld' name='rsscategory' value='$rsscategory'></td>";
	//echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td class='vncellreq' nowrap>Sub Category:</td>";
	//echo "		<td class='vtable'><input type='text' class='formfld' name='rsssubcategory' value='$rsssubcategory'></td>";
	//echo "	</tr>";
	echo "	<tr>";
	echo "		<td width='30%' class='vncellreq' nowrap>Title:</td>";
	echo "		<td width='70%' class='vtable' width='100%'><input type='text' class='formfld' name='rsstitle' value='$rsstitle'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class='vncellreq'>Link:</td>";
	echo "		<td class='vtable'><input type='text' class='formfld' name='rsslink' value='$rsslink'></td>";
	echo "	</tr>";

	echo "	<tr>";
	echo "		<td class='vncellreq'>Group:</td>";
	echo "		<td class='vtable'>";
	//echo "            <input type='text' class='formfld' name='menuparentid' value='$menuparentid'>";

	//---- Begin Select List --------------------
	$sql = "SELECT * FROM v_groups ";
	$sql .= "where v_id = '$v_id' ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();

	echo "<select name=\"rssgroup\" class='formfld'>\n";
	echo "<option value=\"\">public</option>\n";
	$result = $prepstatement->fetchAll();
	//$count = count($result);
	foreach($result as $field) {
			if ($rssgroup == $field[groupid]) {
				echo "<option value='".$field[groupid]."' selected>".$field[groupid]."</option>\n";
			}
			else {
				echo "<option value='".$field[groupid]."'>".$field[groupid]."</option>\n";
			}
	}

	echo "</select>";
	unset($sql, $result);
	//---- End Select List --------------------

	echo "        </td>";
	echo "	</tr>";

	/*
	echo "	<tr>\n";
	echo "	<td width='20%' class=\"vncell\" style='text-align: left;'>\n";
	echo "		Template: \n";
	echo "	</td>\n";
	echo "	<td class=\"vtable\">\n";
	echo "<select id='rsssubcategory' name='rsssubcategory' class='formfld' style=''>\n";
	echo "<option value=''></option>\n";
	$theme_dir = $_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/themes';
	if ($handle = opendir($_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/themes')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != ".svn" && is_dir($theme_dir.'/'.$file)) {
				if ($file == $rsssubcategory) {
					echo "<option value='$file' selected='selected'>$file</option>\n";
				}
				else {
					echo "<option value='$file'>$file</option>\n";
				}
			}
		}
		closedir($handle);
	}
	echo "	</select>\n";
	echo "	<br />\n";
	echo "	Select a template to set as the default and then press save.<br />\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	*/

	echo "	<tr>";
	echo "		<td class='vncellreq'>Type:</td>";
	echo "		<td class='vtable'>";
	echo "            <select name=\"rssoptional1\" class='formfld'>\n";
	if ($rssoptional1 == "text/html") { echo "<option value=\"text/html\" selected>text/html</option>\n"; }
	else { echo "<option value=\"text/html\">text/html</option>\n"; }

	if ($rssoptional1 == "text/javascript") { echo "<option value=\"text/javascript\" selected>text/javascript</option>\n"; }
	else { echo "<option value=\"text/javascript\">text/javascript</option>\n"; }
	echo "            </select>";
	echo "        </td>";
	echo "	</tr>";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap='nowrap'>\n";
	echo "    Order:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "              <select name='rssorder' class='formfld'>\n";
	if (strlen(htmlspecialchars($rssorder))> 0) {
		echo "              <option selected='yes' value='".htmlspecialchars($rssorder)."'>".htmlspecialchars($rssorder)."</option>\n";
	}
	$i=0;
	while($i<=999) {
		if (strlen($i) == 1) {
			echo "              <option value='00$i'>00$i</option>\n";
		}
		if (strlen($i) == 2) {
			echo "              <option value='0$i'>0$i</option>\n";
		}
		if (strlen($i) == 3) {
			echo "              <option value='$i'>$i</option>\n";
		}
		$i++;
	}
	echo "              </select>\n";
	echo "<br />\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "	<tr>";
	//echo "		<td  class='vncellreq' valign='top'></td>";
	echo "		<td  class='' colspan='2' align='left'>";
	echo "            <strong>Content:</strong> ";
	if (is_dir($_SERVER["DOCUMENT_ROOT"].PROJECT_PATH.'/includes/tiny_mce')) {
		echo "            &nbsp; &nbsp; &nbsp; editor &nbsp; <a href='#' title='toggle' onclick=\"toogleEditorMode('rssdesc'); return false;\">on/off</a><br>";
	}
	else {
		echo "            <textarea name='rssdesc'  id='rssdesc' class='formfld' cols='20' style='width: 100%' rows='12' ></textarea>";
	}
	echo "        </td>";
	echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td class='vncellreq'>Image:</td>";
	//echo "		<td class='vtable'><input type='text' name='rssimg' value='$rssimg'></td>";
	//echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td class='vncellreq'>Priority:</td>";
	//echo "		<td class='vtable'>";
	//echo "            <input type='text' name='rssoptional1' value='$rssoptional1'>";
	//echo "            <select name=\"rssoptional1\" class='formfld'>\n";
	//echo "            <option value=\"$rssoptional1\">$rssoptional1</option>\n";
	//echo "            <option value=\"\"></option>\n";
	//echo "            <option value=\"low\">low</option>\n";
	//echo "            <option value=\"med\">med</option>\n";
	//echo "            <option value=\"high\">high</option>\n";
	//echo "            </select>";
	//echo "        </td>";
	//echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td class='vncellreq'>Status:</td>";
	//echo "		<td class='vtable'>";
	//echo "            <input type='text' name='rssoptional2' value='$rssoptional2'>";
	//echo "            <select name=\"rssoptional2\" class=\"formfld\">\n";
	//echo "            <option value=\"$rssoptional2\">$rssoptional2</option>\n";
	//echo "            <option value=\"\"></option>\n";
	//echo "            <option value=\"0\">0</option>\n";
	//echo "            <option value=\"10\">10</option>\n";
	//echo "            <option value=\"20\">20</option>\n";
	//echo "            <option value=\"30\">30</option>\n";
	//echo "            <option value=\"40\">40</option>\n";
	//echo "            <option value=\"50\">50</option>\n";
	//echo "            <option value=\"60\">60</option>\n";
	//echo "            <option value=\"70\">70</option>\n";
	//echo "            <option value=\"80\">80</option>\n";
	//echo "            <option value=\"90\">90</option>\n";
	//echo "            <option value=\"100\">100</option>\n";
	//echo "            </select>";
	//echo "        </td>";
	//echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td class='vncellreq'>Optional 3:</td>";
	//echo "		<td class='vtable'><input type='text' class='formfld' name='rssoptional3' value='$rssoptional3'></td>";
	//echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td class='vncellreq'>Optional 4:</td>";
	//echo "		<td class='vtable'><input type='text' class='formfld' name='rssoptional4' value='$rssoptional4'></td>";
	//echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td class='vncellreq'>Rssoptional5:</td>";
	//echo "		<td class='vtable'><input type='text' class='formfld' name='rssoptional5' value='$rssoptional5'></td>";
	//echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td class='vncellreq'>Rssadddate:</td>";
	//echo "		<td class='vtable'><input type='text' class='formfld' name='rssadddate' value='$rssadddate'></td>";
	//echo "	</tr>";

	echo "	<tr>";
	echo "		<td class='' colspan='2' align='right'>";
	echo "          <input type='submit' class='btn' name='submit' value='Add $moduletitle'>\n";
	echo "      </td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";


	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";


require_once "includes/footer.php";
?>
