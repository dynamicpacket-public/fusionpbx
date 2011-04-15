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

//check permissions
	if (ifgroup("admin") || ifgroup("superadmin")) {
		//access granted
	}
	else {
		echo "access denied";
		exit;
	}

//request form values and set them as variables
	$sip_profile_name = trim($_REQUEST["profile"]);

//define variables
	$c = 0;
	$rowstyle["0"] = "rowstyle0";
	$rowstyle["1"] = "rowstyle1";

//create the event socket connection
	$fp = event_socket_create($_SESSION['event_socket_ip_address'], $_SESSION['event_socket_port'], $_SESSION['event_socket_password']);
	if (!$fp) {
		$msg = "<div align='center'>Connection to Event Socket failed.<br /></div>"; 
	}

//show the error message or show the content
	if (strlen($msg) > 0) {
		echo "<div align='center'>\n";
		echo "<table width='40%'>\n";
		echo "<tr>\n";
		echo "<th align='left'>Message</th>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td class='rowstyle1'><strong>$msg</strong></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
	}
	else {
		//get sofia status profile information including registrations
			$cmd = "api sofia xmlstatus profile ".$sip_profile_name." reg";
			$xml_response = trim(event_socket_request($fp, $cmd));
			if ($xml_response == "Invalid Profile!") { $xml_response = "<error_msg>Invalid Profile!</error_msg>"; }
			$xml_response = str_replace("<profile-info>", "<profile_info>", $xml_response);
			$xml_response = str_replace("</profile-info>", "</profile_info>", $xml_response);
			try {
				$xml = new SimpleXMLElement($xml_response);
			}
			catch(Exception $e) {
				echo $e->getMessage();
				exit;
			}

		//show the header
			require_once "includes/header.php";

		//show the registrations
			echo "<table width='100%' border='0' cellspacing='0' cellpadding='5'>\n";
			echo "<tr>\n";
			echo "<td colspan='4'>\n";
			echo "	<b>Profile: ". $sip_profile_name."</b>\n";
			echo "</td>\n";
			echo "<td colspan='1' align='right'>\n";
			if (ifgroup("superadmin")) {
				echo "  <input type='button' class='btn' value='back' onclick=\"document.location.href='v_status.php';\" />\n";
			}
			else {
				echo "&nbsp;\n";
			}
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";

			echo "<table width='100%' border='0' cellspacing='0' cellpadding='5'>\n";
			echo "<tr>\n";
			//echo "	<th class='vncell'>Caller ID</th>\n";
			echo "	<th>User</th>\n";
			//echo "	<th class='vncell'>Contact</th>\n";
			//echo "	<th class='vncell'>sip-auth-user</th>\n";
			echo "	<th>Agent</th>\n";
			//echo "	<th class='vncell'>Host</th>\n";
			echo "	<th>IP</th>\n";
			echo "	<th>Port</th>\n";
			//echo "	<th class='vncell'>sip-auth-realm</th>\n";
			//echo "	<th class='vncell'>mwi-account</th>\n";
			echo "	<th>Status</th>\n";
			echo "</tr>\n";

			if (count($xml->registrations->registration) > 0) {
				$registration_count = 0;
				foreach ($xml->registrations->registration as $row) {
					$user_array = explode('@', $row->{'user'});
					$domain_name = $user_array[1];
					if ($domain_name == $v_domain) {
						echo "<tr>\n";
						//<td class='".$rowstyle[$c]."'>&nbsp;".$row->{'call-id'}."&nbsp;</td>\n";
						//echo "	<td class='".$rowstyle[$c]."'>&nbsp;".$row->{'user'}."&nbsp;</td>\n";
						//echo "	<td class='".$rowstyle[$c]."'>&nbsp;".$row->{'contact'}."&nbsp;</td>\n";
						echo "	<td class='".$rowstyle[$c]."'>&nbsp;".$row->{'sip-auth-user'}."&nbsp;</td>\n";
						echo "	<td class='".$rowstyle[$c]."'>&nbsp;".$row->{'agent'}."&nbsp;</td>\n";
						//echo "	<td class='".$rowstyle[$c]."'>&nbsp;".$row->{'host'}."&nbsp;</td>\n";
						echo "	<td class='".$rowstyle[$c]."'>&nbsp;<a href='http://".$row->{'network-ip'}."' target='_blank'>".$row->{'network-ip'}."</a>&nbsp;</td>\n";
						echo "	<td class='".$rowstyle[$c]."'>&nbsp;".$row->{'network-port'}."&nbsp;</td>\n";
						//echo "	<td class='".$rowstyle[$c]."'>&nbsp;".$row->{'sip-auth-realm'}."&nbsp;</td>\n";
						//echo "	<td class='".$rowstyle[$c]."'>&nbsp;".$row->{'mwi-account'}."&nbsp;</td>\n";
						echo "	<td class='".$rowstyle[$c]."'>&nbsp;".$row->{'status'}."&nbsp;</td>\n";
						echo "</tr>\n";
						$registration_count++;
					}
					if ($c==0) { $c=1; } else { $c=0; }
				}
				echo "<tr>\n";
				echo "<td colspan='5' align='right'>\n";
				echo "	<b>".$registration_count." registrations</b>\n";
				echo "</td>\n";
				echo "</tr>\n";
			}
			echo "</table>\n";

			fclose($fp);
			unset($xml);
	}

//add some space at the bottom of the page
	echo "<br />\n";
	echo "<br />\n";
	echo "<br />\n";

//get the footer
	require_once "includes/footer.php";

?>