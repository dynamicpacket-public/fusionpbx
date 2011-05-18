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
require_once "admin/edit/config.php";
require_once "admin/edit/header.php";
require_once "includes/checkauth.php";
if (permission_exists('grammar_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//show the content
    echo "<div align='left'>";
    echo "<table width='175'  border='0' cellpadding='0' cellspacing='2'>\n";

    echo "<tr class='border'>\n";
    echo "	<td align=\"left\">\n";
    echo "      <br>";


    $sql = "";
    $sql .= "select * from tblcliplibrary ";

    $prepstatement = $db->prepare(check_sql($sql));
    $prepstatement->execute();
    $result = $prepstatement->fetchAll();
    $resultcount = count($result);

    $c = 0;
    $rowstyle["0"] = "background-color: #F5F5DC;";
    $rowstyle["1"] = "background-color: #FFFFFF;";

    echo "<div align='left'>\n";
    echo "<table width='100%' border='0' cellpadding='1' cellspacing='1'>\n";
    echo "<tr><td colspan='1'><img src='/images/spacer.gif' width='100%' height='1' style='background-color: #BBBBBB;'></td></tr>";

    if ($resultcount == 0) { //no results
        echo "<tr><td>&nbsp;</td></tr>";
    }
    else { //received results

        echo "<tr>";
          //echo "<th nowrap>&nbsp; &nbsp; Id &nbsp;</th>";
          echo "<th nowrap>&nbsp; &nbsp; Clipname &nbsp;</th>";
          //echo "<th nowrap>&nbsp; &nbsp; Clipfolder&nbsp; &nbsp; </th>";
          //echo "<th nowrap>&nbsp; &nbsp; Cliptextstart&nbsp; &nbsp; </th>";
          //echo "<th nowrap>&nbsp; &nbsp; Cliptextend&nbsp; &nbsp; </th>";
          //echo "<th nowrap>&nbsp; &nbsp; Clipdesc&nbsp; &nbsp; </th>";
          //echo "<th nowrap>&nbsp; &nbsp; Cliporder&nbsp; &nbsp; </th>";
        echo "</tr>";
        echo "<tr><td colspan='1'><img src='/images/spacer.gif' width='100%' height='1' style='background-color: #BBBBBB;'></td></tr>\n";

        foreach($result as $row) {
            echo "<tr style='".$rowstyle[$c]."'>\n";
                //echo "<td valign='top'><a href='update.php?id=".$row[id]."'>".$row[id]."</a></td>";
                echo "<td valign='top'><a href='/edit/update.php?id=".$row[id]."'>".$row[clipname]."</a></td>";
                //echo "<td valign='top'>".$row[clipfolder]."</td>";
                //echo "<td valign='top'>".$row[cliptextstart]."</td>";
                //echo "<td valign='top'>".$row[cliptextend]."</td>";
                //echo "<td valign='top'>".$row[clipdesc]."</td>";
                //echo "<td valign='top'>".$row[cliporder]."</td>";
            echo "</tr>";

            echo "<tr><td colspan='1'><img src='/images/spacer.gif' width='100%' height='1' style='background-color: #BBBBBB;'></td></tr>\n";
            if ($c==0) { $c=1; } else { $c=0; }
        } //end foreach
		unset($sql, $result, $rowcount);
        echo "</table>\n";
        echo "</div>\n";

        echo "  </td>\n";
        echo "</tr>\n";
    } //end if results
    echo "</table>\n";
    
    echo "<table width='175'><tr><td align='right'>\n"; 
    echo "<input type='button' class='btn' name='' onclick=\"window.location='clipsearch.php'\" value='Search'>&nbsp; &nbsp;\n";
    echo "<input type='button' class='btn' name='' onclick=\"window.location='clipadd.php'\" value='Add'>&nbsp; &nbsp;\n";
    echo "</td></tr><table>\n";
    echo "</div>";
    echo "<br><br>";

//show the footer
    require_once "admin/edit/footer.php";
?>
