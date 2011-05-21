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
if (permission_exists('public_includes_add') || permission_exists('public_includes_edit') ) {
	//access granted
}
else {
	echo "access denied";
	exit;
}
//set the action as an add or an update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$public_includes_detail_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
		$public_include_id = check_str($_REQUEST["id2"]);
	}

	if (isset($_REQUEST["id2"])) {
		$public_include_id = check_str($_REQUEST["id2"]);
	}

//set the http values as variables
	if (count($_POST)>0) {
		//$v_id = check_str($_POST["v_id"]);
		if (isset($_POST["public_include_id"])) {
			$public_include_id = check_str($_POST["public_include_id"]);
		}
		$tag = check_str($_POST["tag"]);
		$fieldtype = check_str($_POST["fieldtype"]);
		$fielddata = check_str($_POST["fielddata"]);
		$fieldorder = check_str($_POST["fieldorder"]);
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

    $msg = '';
    if ($action == "update") {
        $public_includes_detail_id = check_str($_POST["public_includes_detail_id"]);
    }

    //check for all required data
        if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
        if (strlen($public_include_id) == 0) { $msg .= "Please provide: public_include_id<br>\n"; }
        if (strlen($tag) == 0) { $msg .= "Please provide: Tag<br>\n"; }
        if (strlen($fieldtype) == 0) { $msg .= "Please provide: Type<br>\n"; }
        //if (strlen($fielddata) == 0) { $msg .= "Please provide: Data<br>\n"; }
        if (strlen($fieldorder) == 0) { $msg .= "Please provide: Order<br>\n"; }
        if (strlen($msg) > 0 && strlen($_POST["persistformvar"]) == 0) {
            require_once "includes/header.php";
            require_once "includes/persistformvar.php";
            echo "<div align='center'>\n";
            echo "<table><tr><td>\n";
            echo $msg."<br />";
            echo "</td></tr></table>\n";
            persistformvar($_POST);
            echo "</div>\n";
            require_once "includes/footer.php";
            return;
        }

	//add or update the database
	if ($_POST["persistformvar"] != "true") {
		if ($action == "add" && permission_exists('public_includes_add')) {
			$sql = "insert into v_public_includes_details ";
			$sql .= "(";
			$sql .= "v_id, ";
			$sql .= "public_include_id, ";
			$sql .= "tag, ";
			$sql .= "fieldtype, ";
			$sql .= "fielddata, ";
			$sql .= "fieldorder ";
			$sql .= ")";
			$sql .= "values ";
			$sql .= "(";
			$sql .= "'$v_id', ";
			$sql .= "'$public_include_id', ";
			$sql .= "'$tag', ";
			$sql .= "'$fieldtype', ";
			$sql .= "'$fielddata', ";
			$sql .= "'$fieldorder' ";
			$sql .= ")";
			$db->exec(check_sql($sql));
			unset($sql);

			//synchronize the xml config
			sync_package_v_public_includes();

			require_once "includes/header.php";
			echo "<meta http-equiv=\"refresh\" content=\"2;url=v_public_includes_edit.php?id=".$public_include_id."\">\n";
			echo "<div align='center'>\n";
			echo "Add Complete\n";
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
		} //if ($action == "add")

		if ($action == "update" && permission_exists('public_includes_edit')) {
			$sql = "update v_public_includes_details set ";
			//$sql .= "v_id = '$v_id', ";
			$sql .= "public_include_id = '$public_include_id', ";
			$sql .= "tag = '$tag', ";
			$sql .= "fieldtype = '$fieldtype', ";
			$sql .= "fielddata = '$fielddata', ";
			$sql .= "fieldorder = '$fieldorder' ";
			$sql .= "where public_includes_detail_id = '$public_includes_detail_id'";
			$db->exec(check_sql($sql));
			unset($sql);

			//synchronize the xml config
			sync_package_v_public_includes();

			require_once "includes/header.php";
			echo "<meta http-equiv=\"refresh\" content=\"2;url=v_public_includes_edit.php?id=".$public_include_id."\">\n";
			echo "<div align='center'>\n";
			echo "Update Complete\n";
			echo "</div>\n";
			require_once "includes/footer.php";
			return;
	   } //if ($action == "update")
	} //if ($_POST["persistformvar"] != "true")
} //(count($_POST)>0 && strlen($_POST["persistformvar"]) == 0)

//pre-populate the form
	if (count($_GET)>0 && $_POST["persistformvar"] != "true") {
		$public_includes_detail_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_public_includes_details ";
		$sql .= "where public_includes_detail_id = '$public_includes_detail_id' ";
		$sql .= "and v_id = '$v_id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$v_id = $row["v_id"];
			$public_include_id = $row["public_include_id"];
			$tag = $row["tag"];
			$fieldtype = $row["fieldtype"];
			$fielddata = $row["fielddata"];
			$fieldorder = $row["fieldorder"];
			break; //limit to 1 row
		}
		unset ($prepstatement);
	}

//include the header
    require_once "includes/header.php";

//show the content
    echo "<div align='center'>";
    echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";
    echo "<tr class='border'>\n";
    echo "	<td align=\"left\">\n";
    echo "      <br>";

    echo "<form method='post' name='frm' action=''>\n";
    echo "<div align='center'>\n";
    echo "<table width='100%'  border='0' cellpadding='6' cellspacing='0'>\n";
    echo "<tr>\n";
    if ($action == "add") {
        echo "<td align='left' width='30%' nowrap><b>Public Includes Detail Add</b></td>\n";
    }
    if ($action == "update") {
        echo "<td align='left' width='30%' nowrap><b>Public Includes Detail Update</b></td>\n";
    }
    echo "<td width='70%' align='right'><input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_public_includes_edit.php?id=".$public_include_id."'\" value='Back'></td>\n";
    echo "</tr>\n";

    //echo "<tr>\n";
    //echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
    //echo "    public_include_id:\n";
    //echo "</td>\n";
    //echo "<td class='vtable' align='left'>\n";
    //echo "  <input class='formfld' type='text' name='public_include_id' maxlength='255' value='$public_include_id'>\n";
    //echo "<br />\n";
    //echo "\n";
    //echo "</td>\n";
    //echo "</tr>\n";
    ?>
    <script type="text/javascript">
    function public_include_details_tag_onchange() {
        var tag = document.getElementById("form_tag").value;
        if (tag == "condition") {
          document.getElementById("label_fieldtype").innerHTML = "Field";
          document.getElementById("label_fielddata").innerHTML = "Expression";
        }
        else if (tag == "action") {
          document.getElementById("label_fieldtype").innerHTML = "Application";
          document.getElementById("label_fielddata").innerHTML = "Data";
        }
        else if (tag == "anti-action") {
          document.getElementById("label_fieldtype").innerHTML = "Application";
          document.getElementById("label_fielddata").innerHTML = "Data";
        }
        else if (tag == "param") {
          document.getElementById("label_fieldtype").innerHTML = "Name";
          document.getElementById("label_fielddata").innerHTML = "Value";
        }
        if (tag == "") {
          document.getElementById("label_fieldtype").innerHTML = "Type";
          document.getElementById("label_fielddata").innerHTML = "Data";
        }
    }
    </script>
    <?php
    echo "<tr>\n";
    echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
    echo "    Tag:\n";
    echo "</td>\n";
    echo "<td class='vtable' align='left'>\n";
    echo "                <select name='tag' class='formfld' id='form_tag' onchange='public_include_details_tag_onchange();'>\n";
    echo "                <option></option>\n";
    switch (htmlspecialchars($tag)) {
    case "condition":
        echo "                <option selected='yes'>condition</option>\n";
        echo "                <option>action</option>\n";
        echo "                <option>anti-action</option>\n";
        //echo "                <option>param</option>\n";
        break;
    case "action":
        echo "                <option>condition</option>\n";
        echo "                <option selected='yes'>action</option>\n";
        echo "                <option>anti-action</option>\n";
        //echo "                <option>param</option>\n";
        break;
    case "anti-action":
        echo "                <option>condition</option>\n";
        echo "                <option>action</option>\n";
        echo "                <option selected='yes'>anti-action</option>\n";
        //echo "                <option>param</option>\n";
        break;
    case "param":
        echo "                <option>condition</option>\n";
        echo "                <option>action</option>\n";
        echo "                <option>anti-action</option>\n";
        //echo "                <option selected='yes'>param</option>\n";
        break;
    default:
        echo "                <option>condition</option>\n";
        echo "                <option>action</option>\n";
        echo "                <option>anti-action</option>\n";
        //echo "                <option>param</option>\n";
    }
    echo "                </select>\n";

    //condition
        //field expression
    //action
        //application
        //data
    //antiaction
        //application
        //data
    //param
        //name
        //value
    //echo "    <input class='formfld' type='text' name='tag' maxlength='255' value=\"$tag\">\n";
    echo "<br />\n";
    echo "\n";
    echo "</td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td id='label_fieldtype' class='vncellreq' valign='top' align='left' nowrap>\n";
    echo "    Type:\n";
    echo "</td>\n";
    echo "<td class='vtable' align='left'>\n";
    echo "    <input class='formfld' type='text' name='fieldtype' maxlength='255' value=\"$fieldtype\">\n";
    echo "<br />\n";
    echo "\n";
    echo "</td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td  id='label_fielddata' class='vncell' valign='top' align='left' nowrap>\n";
    echo "    Data:\n";
    echo "</td>\n";
    echo "<td class='vtable' align='left'>\n";
    echo "    <input class='formfld' type='text' name='fielddata' maxlength='255' value=\"$fielddata\">\n";
    echo "<br />\n";
    echo "\n";
    echo "</td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
    echo "    Order:\n";
    echo "</td>\n";
    echo "<td class='vtable' align='left'>\n";
    echo "              <select name='fieldorder' class='formfld'>\n";
    //echo "              <option></option>\n";
    if (strlen(htmlspecialchars($fieldorder))> 0) {
        echo "              <option selected='yes' value='".htmlspecialchars($fieldorder)."'>".htmlspecialchars($fieldorder)."</option>\n";
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
    echo "\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "	<tr>\n";
    echo "		<td colspan='2' align='right'>\n";
    echo "				<input type='hidden' name='public_include_id' value='$public_include_id'>\n";
    if ($action == "update") {
        echo "				<input type='hidden' name='public_includes_detail_id' value='$public_includes_detail_id'>\n";
    }
    echo "				<input type='submit' name='submit' class='btn' value='Save'>\n";
    echo "		</td>\n";
    echo "	</tr>";
    echo "</table>";
    echo "</form>";

    echo "    <table width='100%' cellpadding='0' cellspacing='0'>\n";
    echo "    <tr>\n";
    echo "    <td align='left'>\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    <b>Example</b>\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    If the inbound call matches the DID 12085551234 then proceed to the action.\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    <table cellpadding='3'>\n";
    echo "    <tr><th class=\"vncellreq\" width='75' align=\"left\">Tag:</th><td  class=\"vtable\">condition</td></tr>\n";
    echo "    <tr><th class=\"vncellreq\" align=\"left\">Type:</th><td  class=\"vtable\">destination_number</td></tr>\n";
    echo "    <tr><th class=\"vncellreq\" align=\"left\">Data:</th><td  class=\"vtable\">^12085551234\$</td></tr>\n";
    echo "    </table>\n";
    echo "\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "\n";
    echo "    Transfer the inbound call to an auto attendant with extension of 5000.\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    <table cellpadding='3'>\n";
    echo "    <tr><th class=\"vncellreq\" width='75' align=\"left\">Tag:</th><td class=\"vtable\">action</td></tr>\n";
    echo "    <tr><th class=\"vncellreq\" align=\"left\">Application:</th><td class=\"vtable\">transfer</td></tr>\n";
    echo "    <tr><th class=\"vncellreq\" align=\"left\">Data:</th><td class=\"vtable\">5000 XML default</td></tr>\n";
    echo "    </table>\n";
    echo "\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "\n";
    echo "    Or transfer the inbound call to extension 1001.\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    <table cellpadding='3'>\n";
    echo "    <tr><th class=\"vncellreq\" width='75' align=\"left\">Tag:</th><td class=\"vtable\">action</td></tr>\n";
    echo "    <tr><th class=\"vncellreq\" align=\"left\">Application:</th><td class=\"vtable\">transfer</td></tr>\n";
    echo "    <tr><th class=\"vncellreq\" align=\"left\">Data:</th><td class=\"vtable\">1001 XML default</td></tr>\n";
    echo "    </table>\n";
    echo "\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "\n";
    echo "    Or bridge the inbound call a SIP URI.\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    <table cellpadding='3'>\n";
    echo "    <tr><th class=\"vncellreq\" width='75' align=\"left\">Tag:</th><td class=\"vtable\">action</td></tr>\n";
    echo "    <tr><th class=\"vncellreq\" align=\"left\">Application:</th><td class=\"vtable\">bridge</td></tr>\n";
    echo "    <tr><th class=\"vncellreq\" align=\"left\">Data:</th><td class=\"vtable\">sofia/internal/*98@\${domain}</td></tr>\n";
    echo "    </table>\n";
    echo "\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "\n";
    echo "    <br />\n";
    echo "    <b>SIP URI examples:</b>\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    voicemail: sofia/internal/*98@\${domain}<br />\n";
    echo "    external number: sofia/gateway/gatewayname/12081231234<br />\n";
    echo "    auto attendant: sofia/internal/5002@\${domain}<br />\n";
    echo "    user: /user/1001@\${domain}<br />\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "\n";
    echo "    <b>Conditions</b>\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    Conditions are pattern matching tags that help decide if the current call should be processed in this extension or not. When matching conditions against the current call you have several <b>fields</b> that you can compare against.\n";
    echo "    <ul>\n";
    echo "        <li><b>context</b></li>\n";
    echo "        <li><b>username</b> Extension Number, Also known as the extension number.</li>\n";
    echo "        <li><b>rdnis</b> Redirected Number, the directory number to which the call was last presented.</li>\n";
    echo "        <li><b>destination_number</b> Called Number, the number this call is trying to reach (within a given context)</li>\n";
    echo "        <li><b>public</b> Name of the public module that are used, the name is provided by each public module. Example: XML</li>\n";
    echo "        <li><b>caller_id_name</b> Name of the caller (provided by the User Agent that has called us).</li>\n";
    echo "        <li><b>caller_id_number</b> Directory Number of the party who called (callee) -- can be masked (hidden)</li>\n";
    echo "        <li><b>ani</b> Automatic Number Identification, the number of the calling party (callee) -- cannot be masked</li>\n";
    echo "        <li><b>ani2</b> The type of device placing the call [1]</li>\n";
    echo "        <li><b>uuid</b> Unique identifier of the current call? (looks like a GUID)</li>\n";
    echo "        <li><b>source</b> Name of the module that received the call (e.g. PortAudio)</li>\n";
    echo "        <li><b>chan_name</b> Name of the current channel (Example: PortAudio/1234). Give us examples when this one can be used.</li>\n";
    echo "        <li><b>network_addr</b> IP address of the signalling source for a VoIP call.</li>\n";
    echo "    </ul>\n";
    echo "    In addition to the above you can also do variables using the syntax \${variable} or api functions using the syntax %{api} {args}\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    Variables may be used in either the field or the expression, as follows\n";
    echo "\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "\n";
    echo "    <b>Action and Anti-Actions</b>\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    Actions are executed when the <b>condition matches</b>. Anti-Actions are executed when the <b>condition does NOT match</b>.\n";

    echo "    <br />\n";
    echo "    The following is a partial list of <b>applications</b>.\n";
    echo "    <ul>\n";
    echo "    <li><b>answer</b> answer the call</li>\n";
    echo "    <li><b>bridge</b> bridge the call</li>\n";
    echo "    <li><b>cond</b></li>\n";
    echo "    <li><b>db</b> is a a runtime database either sqlite by default or odbc</li>\n";
    echo "    <li><b>global_set</b> allows setting of global vars similar to the ones found in vars.xml</li>\n";
    echo "    <li><b>group</b> allows grouping of several extensions for things like ring groups</li>\n";
    echo "    <li><b>expr</b></li>\n";
    echo "    <li><b>hangup</b> hangs up the call</li>\n";
    echo "    <li><b>info</b> sends call info to the console</li>\n";
    echo "    <li><b>javascript</b> run javascript .js files</li>\n";
    echo "    <li><b>playback</b></li>\n";
    echo "    <li><b>reject</b> reject the call</li>\n";
    echo "    <li><b>respond</b></li>\n";
    echo "    <li><b>ring_ready</b></li>\n";
    echo "    <li><b>set</b> set a variable</li>\n";
    echo "    <li><b>set_user</b></li>\n";
    echo "    <li><b>sleep</b></li>\n";
    echo "    <li><b>sofia_contact</b></li>\n";
    echo "    <li><b>transfer</b> transfer the call to another extension or number</li>\n";
    echo "    <li><b>voicemail</b> send the call to voicemail</li>\n";
    echo "    </ul>\n";
    echo "\n";
    echo "\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "\n";
    echo "    <!--\n";
    echo "    <b>Param</b>\n";
    echo "    Example parameters by name and value<br />";

    echo "    <ul>\n";
    echo "    <li><b>codec-ms</b> 20</li>\n";
    echo "    <li><b>codec-prefs</b> PCMU@20i</li>\n";
    echo "    <li><b>debug</b> 1</li>\n";
    echo "    <li><b>public</b> XML</li>\n";
    echo "    <li><b>dtmf-duration</b> 100</li>\n";
    echo "    <li><b>rfc2833-pt</b>\" 101</li>\n";
    echo "    <li><b>sip-port</b> 5060</li>\n";
    echo "    <li><b>use-rtp-timer</b> true</li>\n";
    echo "    </ul>\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    -->\n";
    echo "    </td>";
    echo "    </tr>";
    echo "    </table>";
    echo "\n";
    echo "\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    <br />\n";
    echo "    <br />";

    echo "	</td>";
    echo "	</tr>";
    echo "</table>";
    echo "</div>";

//include the footer
	require_once "includes/footer.php";
?>
