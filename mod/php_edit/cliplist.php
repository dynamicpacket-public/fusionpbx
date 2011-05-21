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
require_once "includes/checkauth.php";
if (permission_exists('php_editor_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}
require_once "config.php";
//require_once "edit/header.php";

echo "<html>";
echo "<head>";


echo "<style>\n";
echo "TD {\n";
//echo "	font-family: tahoma;\n";
echo "	font-size: 11.5px;\n";
echo "}\n";
echo "A {\n";
echo "	text-decoration:none\n";
echo "}\n";
echo "</style>";


//$exampledatareturned = example("apples", 1);
//echo $exampledatareturned;

function isfile($filename) {
    if (@filesize($filename) > 0) { return true; } else { return false; }
}

function space($count) {
    $r=''; $i=0;
    while($i < $count) {
        $r .= '     ';
        $i++;
    }
    return $r;
}



    echo "<script type=\"text/javascript\" language=\"javascript\">\n";
    echo "    function makeRequest(url, strpost) {\n";
    //echo "        alert(url); \n";
    echo "        var http_request = false;\n";
    echo "\n";
    echo "        if (window.XMLHttpRequest) { // Mozilla, Safari, ...\n";
    echo "            http_request = new XMLHttpRequest();\n";
    echo "            if (http_request.overrideMimeType) {\n";
    echo "                http_request.overrideMimeType('text/xml');\n";
    echo "                // See note below about this line\n";
    echo "            }\n";
    echo "        } else if (window.ActiveXObject) { // IE\n";
    echo "            try {\n";
    echo "                http_request = new ActiveXObject(\"Msxml2.XMLHTTP\");\n";
    echo "            } catch (e) {\n";
    echo "                try {\n";
    echo "                    http_request = new ActiveXObject(\"Microsoft.XMLHTTP\");\n";
    echo "                } catch (e) {}\n";
    echo "            }\n";
    echo "        }\n";
    echo "\n";
    echo "        if (!http_request) {\n";
    echo "            alert('Giving up :( Cannot create an XMLHTTP instance');\n";
    echo "            return false;\n";
    echo "        }\n";
    echo "        http_request.onreadystatechange = function() { returnContent(http_request); };\n";
    echo "        if (http_request.overrideMimeType) {\n";
    echo "            http_request.overrideMimeType('text/html');\n";
    echo "        }\n";
    echo "        http_request.open('POST', url, true);\n";
    echo "\n";
    echo "\n";
    echo "        if (strpost.length == 0) {\n";
    //echo "            alert('none');\n";
    echo "            //http_request.send(null);\n";
    echo "            http_request.send('name=value&foo=bar');\n";
    echo "        }\n";
    echo "        else {\n";
    //echo "            alert(strpost);\n";
    echo "            http_request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');\n";
    //echo "            http_request.send('name=value&foo=bar');\n";
    echo "            http_request.send(strpost);\n";
    echo "        }\n";
    echo "\n";
    echo "    }\n";
    echo "\n";
    echo "    function returnContent(http_request) {\n";
    echo "\n";
    echo "        if (http_request.readyState == 4) {\n";
    echo "            if (http_request.status == 200) {\n";

    echo "                  parent.editAreaLoader.setValue('edit1', http_request.responseText); \n";
    //echo "                alert(http_request.responseText);\n";
    echo "\n";
    //echo "                //var xmldoc = http_request.responseXML;\n";
    //echo "                //var root_node = xmldoc.getElementsByTagName('doc').item(0);\n";
    //echo "                //alert(xmldoc.getElementByID('fr1').value);\n";
    //echo "                //alert(root_node.firstChild.data);\n";
    //echo "\n";
    echo "            }\n";
    echo "            else {\n";
    echo "                alert('There was a problem with the request.');\n";
    echo "            }\n";
    echo "        }\n";
    echo "\n";
    echo "    }\n";
    echo "</script>";




    echo "<SCRIPT LANGUAGE=\"JavaScript\">\n";
    //echo "// ---------------------------------------------\n";
    //echo "// --- http://www.codeproject.com/jscript/dhtml_treeview.asp\n";
    //echo "// --- Name:    Easy DHTML Treeview           --\n";
    //echo "// --- Author:  D.D. de Kerf                  --\n";
    //echo "// --- Version: 0.2          Date: 13-6-2001  --\n";
    //echo "// ---------------------------------------------\n";
    echo "function Toggle(node) {\n";
    echo "	// Unfold the branch if it isn't visible\n";
    echo "	if (node.nextSibling.style.display == 'none')	{\n";
    echo "  		// Change the image (if there is an image)\n";
    echo "  		if (node.childNodes.length > 0)	{\n";
    //echo "              node.style.color = '#FFFFFF';\n"; //FFFFFF
    //echo "              node.style.background = '#4682BF';\n"; //4682BF
    echo "    			if (node.childNodes.item(0).nodeName == \"IMG\") {\n";
    echo "    				node.childNodes.item(0).src = \"images/minus.gif\";\n";
    echo "    			}\n";

    echo "  		}\n";
    echo "  \n";
    echo "  		node.nextSibling.style.display = 'block';\n";
    echo "	}\n";
    echo "	// Collapse the branch if it IS visible\n";
    echo "	else	{\n";
    echo "  		// Change the image (if there is an image)\n";
    echo "  		if (node.childNodes.length > 0)	{\n";
    echo "    			if (node.childNodes.item(0).nodeName == \"IMG\") {\n";
    echo "    				node.childNodes.item(0).src = \"images/plus.gif\";\n";
    echo "    			}\n";
    echo "  		}\n";
    //echo "          node.style.color = '#000000';\n"; //FFFFFF
    //echo "          node.style.background = '#FFFFFF';\n"; //4682BF
    echo "  		node.nextSibling.style.display = 'none';\n";
    echo "	}\n";
    echo "\n";
    echo "}\n";
    echo "</SCRIPT>";

echo "<head>";
echo "<body onfocus='null;'>";

    echo "<div align='center' valign='1'>";
    echo "<table  width='100%' height='100%' border='0' cellpadding='0' cellspacing='2'>\n";

    echo "<tr class='border'>\n";
    echo "	<td align=\"left\" valign='top' nowrap>\n";
    //echo "      <br>";

    /*
    //echo "   <TR><TD>\n";
    echo "      <TABLE BORDER=0 cellpadding='0' cellspacing='0'><TR><TD><A onClick=\"Toggle(this)\"><IMG SRC=\"/images/plus.gif\"> <IMG SRC=\"/images/folder.gif\"> Menu </A><DIV style='display:none'>\n";
    echo "\n";
    //echo "            <TABLE BORDER=0 cellpadding='0' cellspacing='0'><TR><TD WIDTH=10></TD><TD align='bottom'><IMG SRC=\"/images/file.png\"> <a href='/index.php'>Home</a> <DIV style='display:none'>\n";
    //echo "            </DIV></TD></TR></TABLE>\n";
    echo "\n";
    echo "            <TABLE BORDER=0 cellpadding='0' cellspacing='0'><TR><TD WIDTH=10></TD><TD align='bottom'><IMG SRC=\"/images/file.png\"> <a href='/list.php'>Clip Edit</a> <DIV style='display:none'>\n";
    echo "            </DIV></TD></TR></TABLE>\n";
    echo "\n";
    echo "      </DIV></TD></TR></TABLE>\n";
    */

    echo "      <TABLE BORDER=0 cellpadding='0' cellspacing='0'><TR><TD><a href='javascript:void(0);' onclick=\"window.open('clipoptions.php?id=".$row[id]."','null','left=20,top=20,width=310,height=300,toolbar=0,resizable=0');\" style='text-decoration:none;' title=''><IMG SRC=\"images/folder.gif\" border='0'> Clip Library</a><DIV style=''>\n"; //display:none
    //echo "      <TABLE BORDER=0 cellpadding='0' cellspacing='0'><TR><TD><A onClick=\"Toggle(this)\"><IMG SRC=\"images/plus.gif\"> <IMG SRC=\"images/folder.gif\"> Clip Library</A><DIV style=''>\n"; //display:none



    //echo "\n";
    //echo "            <TABLE BORDER=0 cellpadding='0' cellspacing='0'><TR><TD WIDTH=10></TD><TD align='bottom'><IMG SRC=\"images/file.png\"> <a href='/edit/list.php'> Edit</a> <DIV style='display:none'>\n";
    //echo "            </DIV></TD></TR></TABLE>\n";
    //echo "\n";
    //echo "         <TABLE BORDER=0 cellpadding='0' cellspacing='0'><TR><TD WIDTH=10></TD><TD><A onClick=\"Toggle(this)\"><IMG SRC=\"/images/plus.gif\"> <IMG SRC=\"/images/folder.gif\"> PHP</A><DIV style='display:none'>\n";


	$sql = "";
	$sql .= "select * from tblcliplibrary ";
	$sql .= "order by clipfolder ";
	//$sql .= "and clipname asc ";

	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$resultcount = count($result);



    if ($resultcount == 0) { //no results

    }
    else { //received results
        $lastfolder = '';
        $tagopen = '';
        $x = 0;
        $currentdepth = 0;
        $previousdepth = 0;
        foreach($result as $row) {
        //print_r( $row );

            $currentdepth = count(explode ("/", $row[clipfolder]));
            //echo "$currentdepth < $previousdepth<br>zzz<br>";
            if ($currentdepth < $previousdepth) {
                $count = ($previousdepth - $currentdepth);
                $i=0;
                while($i < $count){
                    echo "</DIV></TD></TR></TABLE>\n";
                    $i++;
                }
                //echo  "count $count";
                //echo "true previousdepth ".$previousdepth." - currentdepth ".$currentdepth."=". ($previousdepth - $currentdepth);
                echo "</DIV></TD></TR></TABLE>\n";

            }



            if ($lastfolder != $row[clipfolder]) {

                $clipfoldername = str_replace ($previousfoldername, "", $row[clipfolder]);
                $clipfoldername = str_replace ("/", "", $clipfoldername);
                //this.style.color = '#FFFFFF';this.style.background = '#4682BF';
                echo "<TABLE BORDER=0 cellpadding='0' cellspacing='0'><TR><TD WIDTH=10></TD><TD><A href='javascript:void(0);' onClick=\"Toggle(this);\"><IMG SRC=\"images/plus.gif\" border='none'> <IMG SRC=\"images/folder.gif\" border='none'> &nbsp;".$clipfoldername." &nbsp; </A><DIV style='display:none'>\n\n";
                $tagopen = 1;
            }

            $previousdepth = $currentdepth;
            $previousfoldername = $row[clipfolder];

            echo "<textarea style='display:none' id='cliplibstart".$row[id]."'>".$row[cliptextstart]."</textarea>\n";
            echo "<textarea style='display:none' id='cliplibend".$row[id]."'>".$row[cliptextend]."</textarea>\n";
            echo "\n";
            echo "<TABLE BORDER=0 cellpadding='0' cellspacing='0'><TR><TD WIDTH=12></TD><TD align='bottom'><IMG SRC=\"images/file.png\" border='0'> \n";
            //echo "<a href='javascript:void(0);' onclick=\"parent.document.getElementById('clipname').value='".$row[clipname]."';parent.document.getElementById('clipid').value=".$row[id].";\">".$row[clipname]."</a>\n";

            //parent.document.getElementById('edit1').focus();
            echo "<a href='javascript:void(0);' onclick=\"parent.editAreaLoader.insertTags('edit1', document.getElementById('cliplibstart".$row[id]."').value, document.getElementById('cliplibend".$row[id]."').value);\">".$row[clipname]."</a>\n";
            //echo "<a href='javascript:void(0);' onclick=\"parent.editAreaLoader.insertTags('edit1', 'start', 'end');\">".$row[clipname]."</a>\n";

            //echo "<DIV style='display:none'></DIV>\n";
            echo "</TD></TR></TABLE>\n";
            echo "\n\n";


            //if ($lastfolder == $row[clipfolder]) {
            //    echo "</DIV></TD></TR></TABLE>\n";
            //    $tagopen = 0;
            //}
            $lastfolder = $row[clipfolder];

            if ($c==0) { $c=1; } else { $c=0; }
        } //end foreach
        unset($sql, $result, $rowcount);

    } //end if results

    //if ($currentdepth < $previousdepth) {
    //    echo "count: ".$previousdepth - $currentdepth;
    //    echo "</DIV></TD></TR></TABLE>\n";
    //}

    echo "\n";
    echo "      </DIV></TD></TR></TABLE>\n";


    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</div>";

    echo "<br><br>";
    //require_once "includes/footer.php";

    unset ($resultcount);
    unset ($result);
    unset ($key);
    unset ($val);
    unset ($c);

    echo "</body>";
    echo "</html>";

?>
