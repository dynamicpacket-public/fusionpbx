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

if (ifgroup("admin") || ifgroup("superadmin")) {
	//access allowed
}
else {
	echo "access denied";
	return;
}

$username = check_str($_POST["username"]);
$password = check_str($_POST["password"]);
$confirmpassword = check_str($_POST["confirmpassword"]);
$userfirstname = check_str($_POST["userfirstname"]);
$userlastname = check_str($_POST["userlastname"]);
$usercompanyname = check_str($_POST["usercompanyname"]);
$userphysicaladdress1 = check_str($_POST["userphysicaladdress1"]);
$userphysicaladdress2 = check_str($_POST["userphysicaladdress2"]);
$userphysicalcity = check_str($_POST["userphysicalcity"]);
$userphysicalstateprovince = check_str($_POST["userphysicalstateprovince"]);
$userphysicalcountry = check_str($_POST["userphysicalcountry"]);
$userphysicalpostalcode = check_str($_POST["userphysicalpostalcode"]);
$usermailingaddress1 = check_str($_POST["usermailingaddress1"]);
$usermailingaddress2 = check_str($_POST["usermailingaddress2"]);
$usermailingcity = check_str($_POST["usermailingcity"]);
$usermailingstateprovince = check_str($_POST["usermailingstateprovince"]);
$usermailingcountry = check_str($_POST["usermailingcountry"]);
$usermailingpostalcode = check_str($_POST["usermailingpostalcode"]);
$userbillingaddress1 = check_str($_POST["userbillingaddress1"]);
$userbillingaddress2 = check_str($_POST["userbillingaddress2"]);
$userbillingcity = check_str($_POST["userbillingcity"]);
$userbillingstateprovince = check_str($_POST["userbillingstateprovince"]);
$userbillingcountry = check_str($_POST["userbillingcountry"]);
$userbillingpostalcode = check_str($_POST["userbillingpostalcode"]);
$usershippingaddress1 = check_str($_POST["usershippingaddress1"]);
$usershippingaddress2 = check_str($_POST["usershippingaddress2"]);
$usershippingcity = check_str($_POST["usershippingcity"]);
$usershippingstateprovince = check_str($_POST["usershippingstateprovince"]);
$usershippingcountry = check_str($_POST["usershippingcountry"]);
$usershippingpostalcode = check_str($_POST["usershippingpostalcode"]);
$userurl = check_str($_POST["userurl"]);
$userphone1 = check_str($_POST["userphone1"]);
$userphone1ext = check_str($_POST["userphone1ext"]);
$userphone2 = check_str($_POST["userphone2"]);
$userphone2ext = check_str($_POST["userphone2ext"]);
$userphonemobile = check_str($_POST["userphonemobile"]);
//$userphoneemergencymobile = check_str($_POST["userphoneemergencymobile"]);
$userphonefax = check_str($_POST["userphonefax"]);
$useremail = check_str($_POST["useremail"]);
$useremailemergency = check_str($_POST["useremailemergency"]);


if (count($_POST)>0 && check_str($_POST["persistform"]) != "1") {

	$msgerror = '';

	//--- begin captcha verification ---------------------
		//session_start(); //make sure sessions are started
		if (strtolower($_SESSION["captcha"]) != strtolower($_REQUEST["captcha"]) || strlen($_SESSION["captcha"]) == 0) {
			//$msgerror .= "Captcha Verification Failed<br>\n";
		}
		else {
			//echo "verified";
		}
	//--- end captcha verification -----------------------

	//username is already used.
	if (strlen($username) == 0) {
		$msgerror .= "Please provide a Username.<br>\n";
	}
	else {
		$sql = "SELECT * FROM v_users ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and username = '$username' ";
		$prepstatement = $db->prepare(check_sql($sql));
		$prepstatement->execute();
		if (count($prepstatement->fetchAll()) > 0) {
			$msgerror .= "Please choose a different Username.<br>\n";
		}
	}

	if (strlen($password) == 0) { $msgerror .= "Password cannot be blank.<br>\n"; }
	if ($password != $confirmpassword) { $msgerror .= "Passwords did not match.<br>\n"; }
	if (strlen($userfirstname) == 0) { $msgerror .= "Please provide a first name.<br>\n"; }
	if (strlen($userlastname) == 0) { $msgerror .= "Please provide a last name $userlastname.<br>\n"; }
	//if (strlen($usercompanyname) == 0) { $msgerror .= "Please provide a company name.<br>\n"; }
	//if (strlen($userphysicaladdress1) == 0) { $msgerror .= "Please provide a address.<br>\n"; }
	//if (strlen($userphysicaladdress2) == 0) { $msgerror .= "Please provide a userphysicaladdress2.<br>\n"; }
	//if (strlen($userphysicalcity) == 0) { $msgerror .= "Please provide a city.<br>\n"; }
	//if (strlen($userphysicalstateprovince) == 0) { $msgerror .= "Please provide a state.<br>\n"; }
	//if (strlen($userphysicalcountry) == 0) { $msgerror .= "Please provide a country.<br>\n"; }
	//if (strlen($userphysicalpostalcode) == 0) { $msgerror .= "Please provide a postal code.<br>\n"; }
	//if (strlen($userurl) == 0) { $msgerror .= "Please provide a url.<br>\n"; }
	//if (strlen($userphone1) == 0) { $msgerror .= "Please provide a phone number.<br>\n"; }
	//if (strlen($userphone2) == 0) { $msgerror .= "Please provide a userphone2.<br>\n"; }
	//if (strlen($userphonemobile) == 0) { $msgerror .= "Please provide a mobile number.<br>\n"; }
	//if (strlen($userphoneemergencymobile) == 0) { $msgerror .= "Please provide a emergency mobile.<br>\n"; }
	//if (strlen($userphonefax) == 0) { $msgerror .= "Please provide a fax number.<br>\n"; }
	if (strlen($useremail) == 0) { $msgerror .= "Please provide an email.<br>\n"; }
	//if (strlen($useremailemergency) == 0) { $msgerror .= "Please provide an emergency email.<br>\n"; }

	if (strlen($msgerror) > 0) {
		require_once "includes/header.php";
		echo "<div align='center'>";
		echo "<table><tr><td>";
		echo $msgerror;
		echo "</td></tr></table>";
		require_once "includes/persistform.php";
		echo persistform($_POST);
		echo "</div>";
		require_once "includes/footer.php";
		return;
	}

	$usertype = 'Individual';
	$usercategory = 'user';

	$sql = "insert into v_users ";
	$sql .= "(";
	$sql .= "v_id, ";
	$sql .= "username, ";
	$sql .= "password, ";
	$sql .= "usertype, ";
	$sql .= "usercategory, ";
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
	$sql .= "'$username', ";
	$sql .= "'".md5('e3.7d.12'.$password)."', ";
	$sql .= "'$usertype', ";
	$sql .= "'$usercategory', ";
	$sql .= "'$userfirstname', ";
	$sql .= "'$userlastname', ";
	$sql .= "'$usercompanyname', ";
	$sql .= "'$userphysicaladdress1', ";
	$sql .= "'$userphysicaladdress2', ";
	$sql .= "'$userphysicalcity', ";
	$sql .= "'$userphysicalstateprovince', ";
	$sql .= "'$userphysicalcountry', ";
	$sql .= "'$userphysicalpostalcode', ";
	$sql .= "'$usermailingaddress1', ";
	$sql .= "'$usermailingaddress2', ";
	$sql .= "'$usermailingcity', ";
	$sql .= "'$usermailingstateprovince', ";
	$sql .= "'$usermailingcountry', ";
	$sql .= "'$usermailingpostalcode', ";
	$sql .= "'$userbillingaddress1', ";
	$sql .= "'$userbillingaddress2', ";
	$sql .= "'$userbillingcity', ";
	$sql .= "'$userbillingstateprovince', ";
	$sql .= "'$userbillingcountry', ";
	$sql .= "'$userbillingpostalcode', ";
	$sql .= "'$usershippingaddress1', ";
	$sql .= "'$usershippingaddress2', ";
	$sql .= "'$usershippingcity', ";
	$sql .= "'$usershippingstateprovince', ";
	$sql .= "'$usershippingcountry', ";
	$sql .= "'$usershippingpostalcode', ";
	$sql .= "'$userurl', ";
	$sql .= "'$userphone1', ";
	$sql .= "'$userphone1ext', ";
	$sql .= "'$userphone2', ";
	$sql .= "'$userphone2ext', ";
	$sql .= "'$userphonemobile', ";
	$sql .= "'$userphonefax', ";
	$sql .= "'$useremail', ";
	$sql .= "'$useremailemergency', ";
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
	echo "<meta http-equiv=\"refresh\" content=\"3;url=index.php\">\n";
	echo "<div align='center'>Add Complete</div>";
	require_once "includes/footer.php";
	return;
}

//show the header
	require_once "includes/header.php";

//show the content
	echo "<div align='center'>";
	echo "<table width='90%' border='0' cellpadding='0' cellspacing='2'>\n";
	echo "<tr>\n";
	echo "	<td align=\"left\">\n";
	echo "      <br>";

	$tablewidth ='width="100%"';
	echo "<form method='post' action=''>";
	echo "<div class='borderlight' style='padding:10px;'>\n";

	echo "<table border='0' $tablewidth cellpadding='6' cellspacing='0'>";
	echo "	<tr>\n";
	echo "		<td width='80%'>\n";
	echo "			<b>To add a user, please fill out this form completely. All fields are required. </b><br>";
	echo "		</td>\n";
	echo "		<td width='20%' align='right'>\n";
	echo "			<input type='button' class='btn' name='back' alt='back' onclick=\"window.history.back()\" value='Back'>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";

	echo "<table border='0' $tablewidth cellpadding='6' cellspacing='0'>";
	echo "	<tr>";
	echo "		<td class='vncellreq' width='40%'>Username:</td>";
	echo "		<td class='vtable' width='60%'><input type='text' class='formfld' autocomplete='off' name='username' value='$username'></td>";
	echo "	</tr>";

	echo "	<tr>";
	echo "		<td class='vncellreq'>Password:</td>";
	echo "		<td class='vtable'><input type='password' class='formfld' autocomplete='off' name='password' value='$password'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class='vncellreq'>Confirm Password:</td>";
	echo "		<td class='vtable'><input type='password' class='formfld' autocomplete='off' name='confirmpassword' value='$confirmpassword'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class='vncellreq'>First Name:</td>";
	echo "		<td class='vtable'><input type='text' class='formfld' name='userfirstname' value='$userfirstname'></td>";
	echo "	</tr>";
	echo "	<tr>";
	echo "		<td class='vncellreq'>Last Name:</td>";
	echo "		<td class='vtable'><input type='text' class='formfld' name='userlastname' value='$userlastname'></td>";
	echo "	</tr>";
	//echo "	<tr>";
	//echo "		<td>Company Name:</td>";
	//echo "		<td><input type='text' class='formfld' name='usercompanyname' value='$usercompanyname'></td>";
	//echo "	</tr>";
	echo "	<tr>";
	echo "		<td class='vncellreq'>Email:</td>";
	echo "		<td class='vtable'><input type='text' class='formfld' name='useremail' value='$useremail'></td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

	echo "<div class='' style='padding:10px;'>\n";
	echo "<table $tablewidth>";
	echo "	<tr>";
	echo "		<td colspan='2' align='right'>";
	echo "       <input type='submit' name='submit' class='btn' value='Create Account'>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</form>";

	echo "	</td>";
	echo "	</tr>";
	echo "</table>";
	echo "</div>";

//show the footer
	require_once "includes/footer.php";
?>
