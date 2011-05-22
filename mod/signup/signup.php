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
require_once "includes/recaptchalib.php";
include "config.php";
include "v_fields.php";

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;

if (count($_POST)>0 && $_POST["persistform"] != "1") {

	$msgerror = '';

	$required[] = array('username', "Please provid a Username.<br>\n");
	$required[] = array('userfirstname', "Please provide a first name.<br>\n");
	$required[] = array('userlastname', "Please provide a last name.<br>\n");
	$required[] = array('userbillingaddress1', "Please provide a street address.<br>\n");
	$required[] = array('userbillingcity', "Please provide a city.<br>\n");
	$required[] = array('userbillingstateprovince', "Please provide a state.<br>\n");
	$required[] = array('userbillingcountry', "Please provide a country.<br>\n");
	$required[] = array('userbillingpostalcode',"Please provide a postal code.<br>\n");
	$required[] = array('userphone1', "Please provide a phone number.<br>\n");
	$required[] = array('useremail', "Please provide an email address.<br>\n");

	foreach($required as $x) {
		if (strlen($_REQUEST[$x[0]]) < 1) {
			$msgerror .= $x[1];
			$error_fields[] = $x[0];
		}
	}

	foreach ($_REQUEST as $field => $data){
		$request[$field] = check_str($data);
	}

	//username is already used.
	if (strlen($username) != 0) {
		$sql = "SELECT * FROM v_users ";
		$sql .= " where v_id = '$v_id' ";
		$sql .= " and username = '" . $request['username'] . "' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		if (count($prepstatement->fetchAll()) > 0) {
			$msgerror .= "Please choose a different Username.<br>\n";
		}
	}

	// make sure password fields match
	if ($request['password'] != $request['confirmpassword']) {
		$msgerror .= "Passwords did not match.<br>\n";
	}

	// email address atleast looks valid
	if (!in_array('useremail', $error_fields)) {
		$validator = new EmailAddressValidator;
		if (!$validator->check_email_address($request['useremail'])) {
			$msgerror .= "Please provide a VALID email address.<br>\n";
		}
	}

	if ($_POST["recaptcha_response_field"]) {
		$resp = recaptcha_check_answer ($privatekey,
						$_SERVER["REMOTE_ADDR"],
						$_POST["recaptcha_challenge_field"],
						$_POST["recaptcha_response_field"]);

		if (!$resp->is_valid) {
			# set the error code so that we can display it
			$msgerror .= "Captcha Verification Failed<br>\n";
			$error = $resp->error;
		}
	} else {
			$msgerror .= "Captcha Verification Failed<br>\n";
	}

	if (strlen($msgerror) > 0) {
		goto showform;
	}

	$sql = "insert into v_users ";
	$sql .= "(";
	$sql .= "v_id, ";
	$sql .= "username, ";
	$sql .= "password, ";
	$sql .= "userfirstname, ";
	$sql .= "userlastname, ";
	$sql .= "usercompanyname, ";
	$sql .= "userphysicaladdress1, ";
	$sql .= "userphysicaladdress2, ";
	$sql .= "userphysicalcity, ";
	$sql .= "userphysicalstateprovince, ";
	$sql .= "userphysicalcountry, ";
	$sql .= "userphysicalpostalcode, ";
	$sql .= "usermailingaddress1, ";
	$sql .= "usermailingaddress2, ";
	$sql .= "usermailingcity, ";
	$sql .= "usermailingstateprovince, ";
	$sql .= "usermailingcountry, ";
	$sql .= "usermailingpostalcode, ";
	$sql .= "userbillingaddress1, ";
	$sql .= "userbillingaddress2, ";
	$sql .= "userbillingcity, ";
	$sql .= "userbillingstateprovince, ";
	$sql .= "userbillingcountry, ";
	$sql .= "userbillingpostalcode, ";
	$sql .= "usershippingaddress1, ";
	$sql .= "usershippingaddress2, ";
	$sql .= "usershippingcity, ";
	$sql .= "usershippingstateprovince, ";
	$sql .= "usershippingcountry, ";
	$sql .= "usershippingpostalcode, ";
	$sql .= "userurl, ";
	$sql .= "userphone1, ";
	$sql .= "userphone1ext, ";
	$sql .= "userphone2, ";
	$sql .= "userphone2ext, ";
	$sql .= "userphonemobile, ";
	$sql .= "userphonefax, ";
	$sql .= "useremail, ";
	$sql .= "useremailemergency, ";
	$sql .= "useradddate, ";
	$sql .= "useradduser ";
	$sql .= ")";
	$sql .= "values ";
	$sql .= "(";
	$sql .= "'$v_id', ";
	$sql .= "'" . $request['username'] . "', ";
	$sql .= "'".md5('e3.7d.12'.$password)."', ";
	$sql .= "'" . $request['userfirstname'] . "', ";
	$sql .= "'" . $request['userlastname'] . "', ";
	$sql .= "'" . $request['usercompanyname'] . "', ";
	$sql .= "'" . $request['userphysicaladdress1'] . "', ";
	$sql .= "'" . $request['userphysicaladdress2'] . "', ";
	$sql .= "'" . $request['userphysicalcity'] . "', ";
	$sql .= "'" . $request['userphysicalstateprovince'] . "', ";
	$sql .= "'" . $request['userphysicalcountry'] . "', ";
	$sql .= "'" . $request['userphysicalpostalcode'] . "', ";
	$sql .= "'" . $request['usermailingaddress1'] . "', ";
	$sql .= "'" . $request['usermailingaddress2'] . "', ";
	$sql .= "'" . $request['usermailingcity'] . "', ";
	$sql .= "'" . $request['usermailingstateprovince'] . "', ";
	$sql .= "'" . $request['usermailingcountry'] . "', ";
	$sql .= "'" . $request['usermailingpostalcode'] . "', ";
	$sql .= "'" . $request['userbillingaddress1'] . "', ";
	$sql .= "'" . $request['userbillingaddress2'] . "', ";
	$sql .= "'" . $request['userbillingcity'] . "', ";
	$sql .= "'" . $request['userbillingstateprovince'] . "', ";
	$sql .= "'" . $request['userbillingcountry'] . "', ";
	$sql .= "'" . $request['userbillingpostalcode'] . "', ";
	$sql .= "'" . $request['usershippingaddress1'] . "', ";
	$sql .= "'" . $request['usershippingaddress2'] . "', ";
	$sql .= "'" . $request['usershippingcity'] . "', ";
	$sql .= "'" . $request['usershippingstateprovince'] . "', ";
	$sql .= "'" . $request['usershippingcountry'] . "', ";
	$sql .= "'" . $request['usershippingpostalcode'] . "', ";
	$sql .= "'" . $request['userurl'] . "', ";
	$sql .= "'" . $request['userphone1'] . "', ";
	$sql .= "'" . $request['userphone1ext'] . "', ";
	$sql .= "'" . $request['userphone2'] . "', ";
	$sql .= "'" . $request['userphone2ext'] . "', ";
	$sql .= "'" . $request['userphonemobile'] . "', ";
	$sql .= "'" . $request['userphonefax'] . "', ";
	$sql .= "'" . $request['useremail'] . "', ";
	$sql .= "'" . $request['useremailemergency'] . "', ";
	$sql .= "now(), ";
	$sql .= "'".$_SESSION["username"]."' ";
	$sql .= ")";
	$db->exec(check_sql($sql));
	unset($sql);

	//log the success
	//$logtype = 'user'; $logstatus='add'; $logadduser=$_SESSION["username"]; $logdesc= "username: ".$username." user added.";
	//logadd($db, $logtype, $logstatus, $logdesc, $logadduser, $_SERVER["REMOTE_ADDR"]);

	$groupid = 'user';
	$sql = "insert into v_group_members ";
	$sql .= "(";
	$sql .= "v_id, ";
	$sql .= "groupid, ";
	$sql .= "username ";
	$sql .= ")";
	$sql .= "values ";
	$sql .= "(";
	$sql .= "'$v_id', ";
	$sql .= "'$groupid', ";
	$sql .= "'$username' ";
	$sql .= ")";
	$db->exec(check_sql($sql));
	unset($sql);

	require_once "includes/header.php";
	echo "<meta http-equiv=\"refresh\" content=\"3;url=".PROJECT_PATH."/index.php\">\n";
	echo "<div align='center'>Add Complete</div>";
	require_once "includes/footer.php";
	// This should probably be an exit or die() call;
	return;
}

showform:

require_once "includes/header.php";

include "user_template.php";

require_once "includes/footer.php";
?>
