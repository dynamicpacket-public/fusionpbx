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
	Copyright (C) 2008-2010 All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/
include "root.php";
require_once "includes/config.php";
require_once "includes/checkauth.php";
if (permission_exists('extension_add') || permission_exists('extension_edit')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

//set the action as an add or an update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$extension_id = check_str($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//get the http values and set them as php variables
	if (count($_POST)>0) {
		$extension = check_str($_POST["extension"]);
		$password = check_str($_POST["password"]);

		$user_list = check_str($_POST["user_list"]."|");
		$user_list = str_replace("\n", "|", "|".$user_list);
		$user_list = str_replace("\r", "", $user_list);
		$user_list = str_replace("||", "|", $user_list);
		$user_list = trim($user_list);

		$provisioning_list = check_str($_POST["provisioning_list"]."|");
		$provisioning_list = str_replace("\n", "|", "|".$provisioning_list);
		$provisioning_list = str_replace("\r", "", $provisioning_list);
		$provisioning_list = str_replace(" ", "", $provisioning_list);
		$provisioning_list = str_replace("||", "|", $provisioning_list);
		$provisioning_list = strtolower($provisioning_list);

		$vm_password = check_str($_POST["vm_password"]);
		$accountcode = check_str($_POST["accountcode"]);
		$effective_caller_id_name = check_str($_POST["effective_caller_id_name"]);
		$effective_caller_id_number = check_str($_POST["effective_caller_id_number"]);
		$outbound_caller_id_name = check_str($_POST["outbound_caller_id_name"]);
		$outbound_caller_id_number = check_str($_POST["outbound_caller_id_number"]);
		$vm_enabled = check_str($_POST["vm_enabled"]);
		$vm_mailto = check_str($_POST["vm_mailto"]);
		$vm_attach_file = check_str($_POST["vm_attach_file"]);
		$vm_keep_local_after_email = check_str($_POST["vm_keep_local_after_email"]);
		$user_context = check_str($_POST["user_context"]);
		$range = check_str($_POST["range"]);
		$autogen_users = check_str($_POST["autogen_users"]);
		$toll_allow = check_str($_POST["toll_allow"]);
		$callgroup = check_str($_POST["callgroup"]);
		$auth_acl = check_str($_POST["auth_acl"]);
		$cidr = check_str($_POST["cidr"]);
		$sip_force_contact = check_str($_POST["sip_force_contact"]);
		$sip_force_expires = check_str($_POST["sip_force_expires"]);
		$nibble_account = check_str($_POST["nibble_account"]);
		$enabled = check_str($_POST["enabled"]);
		$description = check_str($_POST["description"]);
	}

if (count($_POST)>0 && strlen($_POST["persistformvar"]) == 0) {

	$msg = '';
	if ($action == "update") {
		$extension_id = check_str($_POST["extension_id"]);
	}

	//check for all required data
		//if (strlen($v_id) == 0) { $msg .= "Please provide: v_id<br>\n"; }
		if (strlen($extension) == 0) { $msg .= "Please provide: Extension<br>\n"; }
		//if (strlen($user_list) == 0) { $msg .= "Please provide: User List<br>\n"; }
		//if (strlen($vm_password) == 0) { $msg .= "Please provide: Voicemail Password<br>\n"; }
		//if (strlen($accountcode) == 0) { $msg .= "Please provide: Account Code<br>\n"; }
		//if (strlen($effective_caller_id_name) == 0) { $msg .= "Please provide: Effective Caller ID Name<br>\n"; }
		//if (strlen($effective_caller_id_number) == 0) { $msg .= "Please provide: Effective Caller ID Number<br>\n"; }
		//if (strlen($outbound_caller_id_name) == 0) { $msg .= "Please provide: Outbound Caller ID Name<br>\n"; }
		//if (strlen($outbound_caller_id_number) == 0) { $msg .= "Please provide: Outbound Caller ID Number<br>\n"; }
		//if (strlen($vm_mailto) == 0) { $msg .= "Please provide: Voicemail Mail To<br>\n"; }
		//if (strlen($vm_attach_file) == 0) { $msg .= "Please provide: Voicemail Attach File<br>\n"; }
		//if (strlen($vm_keep_local_after_email) == 0) { $msg .= "Please provide: VM Keep Local After Email<br>\n"; }
		//if (strlen($user_context) == 0) { $msg .= "Please provide: User Context<br>\n"; }
		//if (strlen($toll_allow) == 0) { $msg .= "Please provide: Toll Allow<br>\n"; }
		//if (strlen($callgroup) == 0) { $msg .= "Please provide: Call Group<br>\n"; }
		//if (strlen($auth_acl) == 0) { $msg .= "Please provide: Auth ACL<br>\n"; }
		//if (strlen($cidr) == 0) { $msg .= "Please provide: CIDR<br>\n"; }
		//if (strlen($sip_force_contact) == 0) { $msg .= "Please provide: SIP Force Contact<br>\n"; }
		if (strlen($enabled) == 0) { $msg .= "Please provide: Enabled<br>\n"; }
		//if (strlen($description) == 0) { $msg .= "Please provide: Description<br>\n"; }
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

	//set the default user context
		if (ifgroup("superadmin")) {
			//allow a user assigned to super admin to change the user_context
		}
		else {
			//if the user_context was not set then set the default value
			if (strlen($user_context) == 0) { 
				if (count($_SESSION["domains"]) > 1) {
					$user_context = $v_domain;
				}
				else {
					$user_context = "default";
				}
			}
		}

	//add or update the database
	if ($_POST["persistformvar"] != "true") {
		if ($action == "add" && permission_exists('extension_add')) {
			$user_first_name = 'extension';
			$user_email = '';
			if ($autogen_users == "true") {
				$auto_user = $extension;
				for ($i=1; $i<=$range; $i++){
					$user_last_name = $auto_user;
					$user_password = generate_password();
					user_add($auto_user, $user_password, $user_first_name, $user_last_name, $user_email);
					$generated_users[$i]['username'] = $auto_user;
					$generated_users[$i]['password'] = $user_password;
					$auto_user++;
				}
			} else {
				$user_list_array = explode("|", $user_list);
				$i = 0;
				foreach($user_list_array as $auto_user){
					if (strlen($auto_user) > 0) {
						if (!user_exists($auto_user)) {
							$user_last_name = $extension;
							$user_password = generate_password();
							user_add($auto_user, $user_password, $user_first_name, $user_last_name, $user_email);
							$generated_users[$i]['username'] = $auto_user;
							$generated_users[$i]['password'] = $user_password;
							$i++;
						}
					}
				}
			}
			unset($auto_user);

			$db->beginTransaction();
			for ($i=1; $i<=$range; $i++) {
				if (extension_exists($extension)) {
					//extension exists
				}
				else {
					//extension does not exist add it
					$password = generate_password();
					$sql = "insert into v_extensions ";
					$sql .= "(";
					$sql .= "v_id, ";
					$sql .= "extension, ";
					$sql .= "password, ";
					$sql .= "user_list, ";
					$sql .= "provisioning_list, ";
					$sql .= "vm_password, ";
					$sql .= "accountcode, ";
					$sql .= "effective_caller_id_name, ";
					$sql .= "effective_caller_id_number, ";
					$sql .= "outbound_caller_id_name, ";
					$sql .= "outbound_caller_id_number, ";
					$sql .= "vm_enabled, ";
					$sql .= "vm_mailto, ";
					$sql .= "vm_attach_file, ";
					$sql .= "vm_keep_local_after_email, ";
					$sql .= "user_context, ";
					$sql .= "toll_allow, ";
					$sql .= "callgroup, ";
					$sql .= "auth_acl, ";
					$sql .= "cidr, ";
					$sql .= "sip_force_contact, ";
					if (strlen($sip_force_expires) > 0) {
						$sql .= "sip_force_expires, ";
					}
					if (strlen($nibble_account) > 0) {
						$sql .= "nibble_account, ";
					}
					$sql .= "enabled, ";
					$sql .= "description ";
					$sql .= ")";
					$sql .= "values ";
					$sql .= "(";
					$sql .= "'$v_id', ";
					$sql .= "'$extension', ";
					$sql .= "'$password', ";
					if ($autogen_users == "true") { 
						$sql .= "'|$extension|', ";
					} else {
						$sql .= "'$user_list', ";
					}
					$sql .= "'$provisioning_list', ";
					$sql .= "'#".generate_password(4, 1)."', ";
					$sql .= "'$accountcode', ";
					$sql .= "'$effective_caller_id_name', ";
					$sql .= "'$effective_caller_id_number', ";
					$sql .= "'$outbound_caller_id_name', ";
					$sql .= "'$outbound_caller_id_number', ";
					$sql .= "'$vm_enabled', ";
					$sql .= "'$vm_mailto', ";
					$sql .= "'$vm_attach_file', ";
					$sql .= "'$vm_keep_local_after_email', ";
					$sql .= "'$user_context', ";
					$sql .= "'$toll_allow', ";
					$sql .= "'$callgroup', ";
					$sql .= "'$auth_acl', ";
					$sql .= "'$cidr', ";
					$sql .= "'$sip_force_contact', ";
					if (strlen($sip_force_expires) > 0) {
						$sql .= "'$sip_force_expires', ";
					}
					if (strlen($nibble_account) > 0) {
						$sql .= "'$nibble_account', ";
					}
					$sql .= "'$enabled', ";
					$sql .= "'$description' ";
					$sql .= ")";
					$db->exec(check_sql($sql));
					unset($sql);
				}

				$extension++;
			}
			$db->commit();

			//syncrhonize configuration
				sync_package_v_extensions();

			//write the provision files
				if (strlen($provisioning_list)>0) {
					require_once "mod/provision/v_provision_write.php";
				}

			//prepare for alternating the row style
				$c = 0;
				$rowstyle["0"] = "rowstyle0";
				$rowstyle["1"] = "rowstyle1";

			//show the action and redirect the user
				require_once "includes/header.php";
				echo "<br />\n";
				echo "<div align='center'>\n";
				if (count($generated_users) == 0) {
					//action add
						echo "<meta http-equiv=\"refresh\" content=\"2;url=v_extensions.php\">\n";
						echo "	<table width='40%'>\n";
						echo "		<tr>\n";
						echo "			<th align='left'>Message</th>\n";
						echo "		</tr>\n";
						echo "		<tr>\n";
						echo "			<td class='rowstyle1'><strong>Add Complete</strong></td>\n";
						echo "		</tr>\n";
						echo "	</table>\n";
						echo "	<br />\n";
				}
				else {
					// auto-generate user with extension as login name
						echo "	<table width='40%' border='0' cellpadding='0' cellspacing='0'>\n";
						echo "		<tr>\n";
						echo "			<td colspan='2'><strong>New User Accounts</strong></td>\n";
						echo "		</tr>\n";
						echo "		<tr>\n";
						echo "			<th>Username</th>\n";
						echo "			<th>Password</th>\n";
						echo "		</tr>\n";
						foreach($generated_users as $tmp_user){
							echo "		<tr>\n";
							echo "			<td valign='top' class='".$rowstyle[$c]."'>".$tmp_user['username']."</td>\n";
							echo "			<td valign='top' class='".$rowstyle[$c]."'>".$tmp_user['password']."</td>\n";
							echo "		</tr>\n";
						}
						if ($c==0) { $c=1; } else { $c=0; }
						echo "	</table>";
				}
				echo "</div>\n";
				require_once "includes/footer.php";
				return;
		} //if ($action == "add")

		if ($action == "update" && permission_exists('extension_edit')) {
			$userfirstname='extension';$userlastname=$extension;$useremail='';
			$user_list_array = explode("|", $user_list);
			foreach($user_list_array as $tmp_user){
				$user_password = generate_password();
				user_add($tmp_user, $user_password, $userfirstname, $userlastname, $useremail);
			}
			unset($tmp_user);

			if (strlen($password) == 0) {
				$password = generate_password();
			}

			$sql = "update v_extensions set ";
			$sql .= "extension = '$extension', ";
			$sql .= "password = '$password', ";
			$sql .= "user_list = '$user_list', ";
			$sql .= "provisioning_list = '$provisioning_list', ";
			$sql .= "vm_password = '#$vm_password', ";
			$sql .= "accountcode = '$accountcode', ";
			$sql .= "effective_caller_id_name = '$effective_caller_id_name', ";
			$sql .= "effective_caller_id_number = '$effective_caller_id_number', ";
			$sql .= "outbound_caller_id_name = '$outbound_caller_id_name', ";
			$sql .= "outbound_caller_id_number = '$outbound_caller_id_number', ";
			$sql .= "vm_enabled = '$vm_enabled', ";
			$sql .= "vm_mailto = '$vm_mailto', ";
			$sql .= "vm_attach_file = '$vm_attach_file', ";
			$sql .= "vm_keep_local_after_email = '$vm_keep_local_after_email', ";
			$sql .= "user_context = '$user_context', ";
			$sql .= "toll_allow = '$toll_allow', ";
			$sql .= "callgroup = '$callgroup', ";
			$sql .= "auth_acl = '$auth_acl', ";
			$sql .= "cidr = '$cidr', ";
			$sql .= "sip_force_contact = '$sip_force_contact', ";
			if (strlen($sip_force_expires) > 0) {
				$sql .= "sip_force_expires = '$sip_force_expires', ";
			}
			if (strlen($nibble_account) > 0) {
				$sql .= "nibble_account = '$nibble_account', ";
			}
			$sql .= "enabled = '$enabled', ";
			$sql .= "description = '$description' ";
			$sql .= "where v_id = '$v_id' ";
			$sql .= "and extension_id = '$extension_id'";
			$db->exec(check_sql($sql));
			unset($sql);

			//syncrhonize configuration
				sync_package_v_extensions();

			//write the provision files
				if (strlen($provisioning_list)>0) {
					require_once "mod/provision/v_provision_write.php";
				}

			//show the action and redirect the user
				require_once "includes/header.php";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=v_extensions.php\">\n";
				echo "<br />\n";
				echo "<div align='center'>\n";

				//action update
					echo "	<table width='40%'>\n";
					echo "		<tr>\n";
					echo "			<th align='left'>Message</th>\n";
					echo "		</tr>\n";
					echo "		<tr>\n";
					echo "			<td class='rowstyle1'><strong>Update Complete</strong></td>\n";
					echo "		</tr>\n";
					echo "	</table>\n";
					echo "<br />\n";

				echo "</div>\n";
				require_once "includes/footer.php";
				return;
		} //if ($action == "update")
	} //if ($_POST["persistformvar"] != "true")
} //(count($_POST)>0 && strlen($_POST["persistformvar"]) == 0)

//pre-populate the form
	if (count($_GET)>0 && $_POST["persistformvar"] != "true") {
		$extension_id = $_GET["id"];
		$sql = "";
		$sql .= "select * from v_extensions ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and extension_id = '$extension_id' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		$result = $prepstatement->fetchAll();
		foreach ($result as &$row) {
			$extension = $row["extension"];
			$password = $row["password"];
			$user_list = $row["user_list"];
			$provisioning_list = $row["provisioning_list"];
			$provisioning_list = strtolower($provisioning_list);
			$vm_password = $row["vm_password"];
			$vm_password = str_replace("#", "", $vm_password); //preserves leading zeros
			$accountcode = $row["accountcode"];
			$effective_caller_id_name = $row["effective_caller_id_name"];
			$effective_caller_id_number = $row["effective_caller_id_number"];
			$outbound_caller_id_name = $row["outbound_caller_id_name"];
			$outbound_caller_id_number = $row["outbound_caller_id_number"];
			$vm_enabled = $row["vm_enabled"];
			$vm_mailto = $row["vm_mailto"];
			$vm_attach_file = $row["vm_attach_file"];
			$vm_keep_local_after_email = $row["vm_keep_local_after_email"];
			$user_context = $row["user_context"];
			$toll_allow = $row["toll_allow"];
			$callgroup = $row["callgroup"];
			$auth_acl = $row["auth_acl"];
			$cidr = $row["cidr"];
			$sip_force_contact = $row["sip_force_contact"];
			$sip_force_expires = $row["sip_force_expires"];
			$nibble_account = $row["nibble_account"];
			$enabled = $row["enabled"];
			$description = $row["description"];
			break; //limit to 1 row
		}
		unset ($prepstatement);
	}


//begin the page content
	require_once "includes/header.php";

	echo "<script type=\"text/javascript\" language=\"JavaScript\">\n";
	echo "\n";
	echo "function enable_change(enable_over) {\n";
	echo "	var endis;\n";
	echo "	endis = !(document.iform.enable.checked || enable_over);\n";
	echo "	document.iform.range_from.disabled = endis;\n";
	echo "	document.iform.range_to.disabled = endis;\n";
	echo "}\n";
	echo "\n";
	echo "function show_advanced_config() {\n";
	echo "	document.getElementById(\"showadvancedbox\").innerHTML='';\n";
	echo "	aodiv = document.getElementById('showadvanced');\n";
	echo "	aodiv.style.display = \"block\";\n";
	echo "}\n";
	echo "\n";
	echo "function hide_advanced_config() {\n";
	echo "	document.getElementById(\"showadvancedbox\").innerHTML='';\n";
	echo "	aodiv = document.getElementById('showadvanced');\n";
	echo "	aodiv.style.display = \"none\";\n";
	echo "}\n";
	echo "</script>";

	echo "<div align='center'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='2'>\n";
	echo "<tr class='border'>\n";
	echo "	<td align=\"left\">\n";
	echo "      <br>";

	echo "<form method='post' name='frm' action=''>\n";
	echo "<div align='center'>\n";
	echo "<table width='100%' border='0' cellpadding='6' cellspacing='0'>\n";
	echo "<tr>\n";
	if ($action == "add") {
		echo "<td width='30%' nowrap align='left' valign='top'><b>Extension Add</b></td>\n";
	}
	if ($action == "update") {
		echo "<td width='30%' nowrap align='left' valign='top'><b>Extension Edit</b></td>\n";
	}
	echo "<td width='70%' align='right' valign='top'>\n";
	echo "	<input type='submit' name='submit' class='btn' value='Save'>\n";
	echo "	<input type='button' class='btn' name='' alt='copy' onclick=\"if (confirm('Do you really want to copy this?')){window.location='v_extensions_copy.php?id=".$extension_id."';}\" value='Copy'>\n";
	echo "	<input type='button' class='btn' name='' alt='back' onclick=\"window.location='v_extensions.php'\" value='Back'>\n";
	echo "	<br /><br />\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Extension:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='extension' autocomplete='off' maxlength='255' value=\"$extension\">\n";
	echo "<br />\n";
	echo "Enter the extension here. The default configuration allows 2 - 7 digit extensions.\n";
	echo "</td>\n";
	echo "</tr>\n";

	if ($action == "update") {
		echo "<tr>\n";
		echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
		echo "    Password:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "    <input class='formfld' type='password' name='password' id='password' onfocus=\"document.getElementById('show_password').innerHTML = 'Password: '+document.getElementById('password').value;\" autocomplete='off' maxlength='50' value=\"$password\">\n";
		echo "<br />\n";
		echo "<span onclick=\"document.getElementById('show_password').innerHTML = ''\">Enter the password here. </span><span id='show_password'></span>\n";
		echo "</td>\n";
		echo "</tr>\n";
	}

	if ($action == "add") {
		echo "<tr>\n";
		echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
		echo "    Range:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "    <select class='formfld' name='range'>\n";
		echo "    <option value='1'>1</option>\n";
		echo "    <option value='2'>2</option>\n";
		echo "    <option value='3'>3</option>\n";
		echo "    <option value='4'>4</option>\n";
		echo "    <option value='5'>5</option>\n";
		echo "    <option value='6'>6</option>\n";
		echo "    <option value='7'>7</option>\n";
		echo "    <option value='8'>8</option>\n";
		echo "    <option value='9'>9</option>\n";
		echo "    <option value='10'>10</option>\n";
		echo "    <option value='15'>15</option>\n";
		echo "    <option value='20'>20</option>\n";
		echo "    <option value='25'>25</option>\n";
		echo "    <option value='30'>30</option>\n";
		echo "    <option value='35'>35</option>\n";
		echo "    <option value='40'>40</option>\n";
		echo "    <option value='45'>45</option>\n";
		echo "    <option value='50'>50</option>\n";
		echo "    <option value='75'>75</option>\n";
		echo "    <option value='100'>100</option>\n";
		echo "    <option value='150'>150</option>\n";
		echo "    <option value='200'>200</option>\n";
		echo "    <option value='250'>250</option>\n";
		echo "    <option value='500'>500</option>\n";
		echo "    <option value='500'>750</option>\n";
		echo "    <option value='1000'>1000</option>\n";
		echo "    <option value='5000'>5000</option>\n";
		echo "    </select>\n";
		echo "<br />\n";
		echo "Enter the number of extensions to create. Increments each extension by 1.<br />\n";
		echo "<input type=\"checkbox\" name=\"autogen_users\" value=\"true\"> Auto-generate user with extension as login name<br>\n";
		echo "\n";
		echo "</td>\n";
		echo "</tr>\n";
	}

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "		User List:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	$onchange = "document.getElementById('user_list').value += document.getElementById('username').value + '\\n';";
	$tablename = 'v_users'; $fieldname = 'username'; $fieldcurrentvalue = ''; $sqlwhereoptional = "where v_id = '$v_id'"; 
	echo htmlselectonchange($db, $tablename, $fieldname, $sqlwhereoptional, $fieldcurrentvalue, $onchange);
	echo "<br />\n";
	echo "Use the select list to add users to the userlist. This will assign users to this extension.\n";
	echo "<br />\n";
	echo "<br />\n";
	$user_list = str_replace("|", "\n", $user_list);
	echo "		<textarea name=\"user_list\" id=\"user_list\" class=\"formfld\" cols=\"30\" rows=\"3\" wrap=\"off\">$user_list</textarea>\n";
	echo "		<br>\n";
	echo "If a user is not in the select list it can be added manually to the user list and it will be created automatically.\n";
	echo "<br />\n";
	echo "</td>\n";
	echo "</tr>\n";

	if ($action == "update") {
		echo "<tr>\n";
		echo "<td class='vncell' valign='top' align='left' nowrap>\n";
		echo "    Voicemail Password:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "  <input class='formfld' type='password' name='vm_password' id='vm_password' onfocus=\"document.getElementById('show_vm_password').innerHTML = 'Password: '+document.getElementById('vm_password').value;\" maxlength='255' value='$vm_password'>\n";
		echo "<br />\n";
		echo "<span onclick=\"document.getElementById('show_vm_password').innerHTML = ''\">Enter the voicemail password here. </span><span id='show_vm_password'></span>\n";
		echo "</td>\n";
		echo "</tr>\n";
	}

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Account Code:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='accountcode' maxlength='255' value=\"$accountcode\">\n";
	echo "<br />\n";
	echo "Enter the account code here.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Effective Caller ID Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='effective_caller_id_name' maxlength='255' value=\"$effective_caller_id_name\">\n";
	echo "<br />\n";
	echo "Enter the effective caller id name here.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Effective Caller ID Number:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='effective_caller_id_number' maxlength='255' value=\"$effective_caller_id_number\">\n";
	echo "<br />\n";
	echo "Enter the effective caller id number here.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Outbound Caller ID Name:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='outbound_caller_id_name' maxlength='255' value=\"$outbound_caller_id_name\">\n";
	echo "<br />\n";
	echo "Enter the outbound caller id name here.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Outbound Caller ID Number:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='outbound_caller_id_number' maxlength='255' value=\"$outbound_caller_id_number\">\n";
	echo "<br />\n";
	echo "Enter the outbound caller id number here.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "	Phone Provisioning:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";

	$onchange = "document.getElementById('provisioning_list').value += document.getElementById('select_mac_address').value;";
	$onchange .= "document.getElementById('provisioning_list').value += ':'+document.getElementById('prov_line').value + '\\n'";

	$sql = "";
	$sql .= " select * from v_hardware_phones ";
	$prepstatement = $db->prepare(check_sql($sql));
	$prepstatement->execute();
	$result = $prepstatement->fetchAll();
	$resultcount = count($result);
	unset ($prepstatement, $sql);
	echo "<select name=\"select_mac_address\" id=\"select_mac_address\" class=\"formfld\">\n";
	echo "<option value=''></option>\n";

	foreach($result as $row) {
		//print_r( $row );
		if ($row[phone_mac_address] == $select_mac_address) {
			echo "<option value='".$row[phone_mac_address]."' selected>".$row[phone_mac_address]." ".$row[phone_model]." ".$row[phone_description]."</option>\n";
		}
		else {
			echo "<option value='".$row[phone_mac_address]."'>".$row[phone_mac_address]." ".$row[phone_model]." ".$row[phone_description]."</option>\n";
		}
		//$row[phone_mac_address]
		//$row[phone_vendor]
		//$row[phone_model]
		//$row[phone_provision_enable]
		//$row[phone_description]
		//$row[hardware_phone_id]
	} //end foreach
	unset($sql, $result, $rowcount);
	echo "</select>\n";
	echo "<br />\n";
	echo "Select a device to assign to this extension by its MAC addresses.\n";

	echo "<br />\n";
	echo "<br />\n";

	echo "	<select id='prov_line' name='prov_line' onchange=\"$onchange\" class='formfld'>\n";
	echo "	<option value=''></option>\n";
	echo "	<option value='1'>1</option>\n";
	echo "	<option value='2'>2</option>\n";
	echo "	<option value='3'>3</option>\n";
	echo "	<option value='4'>4</option>\n";
	echo "	<option value='5'>5</option>\n";
	echo "	<option value='6'>6</option>\n";
	echo "	<option value='7'>7</option>\n";
	echo "	<option value='8'>8</option>\n";
	echo "	<option value='9'>9</option>\n";
	echo "	<option value='10'>10</option>\n";
	echo "	<option value='11'>11</option>\n";
	echo "	<option value='12'>12</option>\n";
	echo "	<option value='13'>13</option>\n";
	echo "	<option value='14'>14</option>\n";
	echo "	<option value='15'>15</option>\n";
	echo "	<option value='16'>16</option>\n";
	echo "	<option value='17'>17</option>\n";
	echo "	<option value='18'>18</option>\n";
	echo "	<option value='19'>19</option>\n";
	echo "	<option value='20'>20</option>\n";
	echo "	<option value='21'>21</option>\n";
	echo "	<option value='22'>22</option>\n";
	echo "	<option value='23'>23</option>\n";
	echo "	<option value='24'>24</option>\n";
	echo "	<option value='25'>25</option>\n";
	echo "	<option value='26'>26</option>\n";
	echo "	<option value='27'>27</option>\n";
	echo "	<option value='28'>28</option>\n";
	echo "	<option value='29'>29</option>\n";
	echo "	<option value='30'>30</option>\n";
	echo "	<option value='31'>31</option>\n";
	echo "	<option value='32'>32</option>\n";
	echo "	</select>\n";
	echo "<br />\n";
	echo "Select a line number.<br>\n";
	echo "<br />\n";
	$provisioning_list = str_replace("|", "\n", $provisioning_list);
	echo "    <textarea name=\"provisioning_list\" id=\"provisioning_list\" class=\"formfld\" cols=\"30\" rows=\"3\" wrap=\"off\">$provisioning_list</textarea>\n";
	echo "    <br>\n";
	echo "If a MAC address is not in the select list it can be added manually.<br />MAC Address:Line Number\n";
	echo "<br />\n";
	echo "</td>\n";
	echo "</tr>\n";
	
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Voicemail Enabled:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='vm_enabled'>\n";
	echo "    <option value=''></option>\n";
	if ($vm_enabled == "true" || $vm_enabled == "") { 
		echo "    <option value='true' selected='selected'>true</option>\n";
	}
	else {
		echo "    <option value='true'>true</option>\n";
	}
	if ($vm_enabled == "false") { 
		echo "    <option value='false' selected >false</option>\n";
	}
	else {
		echo "    <option value='false'>false</option>\n";
	}
	echo "    </select>\n";
	echo "<br />\n";
	echo "Enable/disable voicemail for this extension.\n";
	echo "</td>\n";
	echo "</tr>\n";	

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Voicemail Mail To:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='vm_mailto' maxlength='255' value=\"$vm_mailto\">\n";
	echo "<br />\n";
	echo "Optional: Enter the email address to send voicemail to.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Voicemail Attach File:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='vm_attach_file'>\n";
	echo "    <option value=''></option>\n";
	if ($vm_attach_file == "true") { 
		echo "    <option value='true' selected >true</option>\n";
	}
	else {
		echo "    <option value='true'>true</option>\n";
	}
	if ($vm_attach_file == "false") { 
		echo "    <option value='false' selected >false</option>\n";
	}
	else {
		echo "    <option value='false'>false</option>\n";
	}
	echo "    </select>\n";
	echo "<br />\n";
	echo "Choose whether to attach the file to the email.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    VM Keep Local After Email:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='vm_keep_local_after_email'>\n";
	echo "    <option value=''></option>\n";
	if ($vm_keep_local_after_email == "true") { 
		echo "    <option value='true' selected >true</option>\n";
	}
	else {
		echo "    <option value='true'>true</option>\n";
	}
	if ($vm_keep_local_after_email == "false") { 
		echo "    <option value='false' selected >false</option>\n";
	}
	else {
		echo "    <option value='false'>false</option>\n";
	}
	echo "    </select>\n";
	echo "<br />\n";
	echo "Keep local file after sending the email. \n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Toll Allow:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='toll_allow' maxlength='255' value=\"$toll_allow\">\n";
	echo "<br />\n";
	echo "Enter the toll allow value here. example: domestic,international,local\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Call Group:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='callgroup' maxlength='255' value=\"$callgroup\">\n";
	echo "<br />\n";
	echo "Enter the user call group here. Groups available by default: sales, support, billing\n";
	echo "</td>\n";
	echo "</tr>\n";

	if (ifgroup("superadmin")) {
		if (strlen($user_context) == 0) { 
			if (count($_SESSION["domains"]) > 1) {
				$user_context = $v_domain;
			}
			else {
				$user_context = "default";
			}
		}
		echo "<tr>\n";
		echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
		echo "    User Context:\n";
		echo "</td>\n";
		echo "<td class='vtable' align='left'>\n";
		echo "    <input class='formfld' type='text' name='user_context' maxlength='255' value=\"$user_context\">\n";
		echo "<br />\n";
		echo "Enter the user context here.\n";
		echo "</td>\n";
		echo "</tr>\n";
	}

	//--- begin: showadvanced -----------------------
	echo "<tr>\n";
	echo "<td style='padding: 0px;' colspan='2' class='' valign='top' align='left' nowrap>\n";

	echo "	<div id=\"showadvancedbox\">\n";
	echo "		<table width=\"100%\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";
	echo "		<tr>\n";
	echo "		<td width=\"30%\" valign=\"top\" class=\"vncell\">Show Advanced</td>\n";
	echo "		<td width=\"70%\" class=\"vtable\">\n";
	echo "			<input type=\"button\" class='btn' onClick=\"show_advanced_config()\" value=\"Advanced\"></input></a>\n";
	echo "		</td>\n";
	echo "		</tr>\n";
	echo "		</table>\n";
	echo "	</div>\n";

	echo "	<div id=\"showadvanced\" style=\"display:none\">\n";
	echo "	<table width=\"100%\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";

	echo "<tr>\n";
	echo "<td width=\"30%\" class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Auth ACL:\n";
	echo "</td>\n";
	echo "<td width=\"70%\" class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='auth_acl' maxlength='255' value=\"$auth_acl\">\n";
	echo "<br />\n";
	echo "Enter the Auth ACL here.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    CIDR:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='cidr' maxlength='255' value=\"$cidr\">\n";
	echo "<br />\n";
	echo "Enter the cidr here.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    SIP Force Contact:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='sip_force_contact'>\n";
	echo "    <option value=''></option>\n";
	if ($sip_force_contact == "NDLB-connectile-dysfunction") { 
		echo "    <option value='NDLB-connectile-dysfunction' SELECTED >Rewrite contact IP and port</option>\n";
	}
	else {
		echo "    <option value='NDLB-connectile-dysfunction'>Rewrite contact IP and port</option>\n";
	}
	if ($sip_force_contact == "NDLB-tls-connectile-dysfunction") { 
		echo "    <option value='NDLB-tls-connectile-dysfunction' SELECTED >Rewrite contact port</option>\n";
	}
	else {
		echo "    <option value='NDLB-tls-connectile-dysfunction'>Rewrite contact port</option>\n";
	}
	echo "    </select>\n";
	echo "<br />\n";
	echo "Choose whether to rewrite the contact port, or rewrite both the contact IP and port.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    SIP Force Expires:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='sip_force_expires' maxlength='255' value=\"$sip_force_expires\">\n";
	echo "<br />\n";
	echo "Enter the sip force expire seconds.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Nibblebill Account:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <input class='formfld' type='text' name='nibble_account' maxlength='255' value=\"$nibble_account\">\n";
	echo "<br />\n";
	echo "Enter the account number for nibblebill to use.\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "	</table>\n";
	echo "	</div>";

	echo "</td>\n";
	echo "</tr>\n";
	//--- end: showadvanced -----------------------

	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap>\n";
	echo "    Enabled:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <select class='formfld' name='enabled'>\n";
	echo "    <option value=''></option>\n";
	if ($enabled == "true" || strlen($enabled) == 0) { 
		echo "    <option value='true' selected >true</option>\n";
	}
	else {
		echo "    <option value='true'>true</option>\n";
	}
	if ($enabled == "false") { 
		echo "    <option value='false' selected >false</option>\n";
	}
	else {
		echo "    <option value='false'>false</option>\n";
	}
	echo "    </select>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap>\n";
	echo "    Description:\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "    <textarea class='formfld' name='description' rows='4'>$description</textarea>\n";
	echo "<br />\n";
	echo "\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	if ($action == "update") {
		echo "				<input type='hidden' name='extension_id' value='$extension_id'>\n";
	}
	echo "				<input type='submit' name='submit' class='btn' value='Save'>\n";
	echo "		</td>\n";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";


	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";


require_once "includes/footer.php";
?>
