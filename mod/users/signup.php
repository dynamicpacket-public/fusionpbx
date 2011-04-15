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

//used to disable the page until it is needed
	require_once "includes/checkauth.php";
	if (ifgroup("superadmin")) {
		//echo "access granted";
		//exit;
	}
	else {
		echo "access denied";
		exit;
	}

$username = $_POST["username"];
$password = $_POST["password"];
$confirmpassword = $_POST["confirmpassword"];
$userfirstname = $_POST["userfirstname"];
$userlastname = $_POST["userlastname"];
$usercompanyname = $_POST["usercompanyname"];
$userphysicaladdress1 = $_POST["userphysicaladdress1"];
$userphysicaladdress2 = $_POST["userphysicaladdress2"];
$userphysicalcity = $_POST["userphysicalcity"];
$userphysicalstateprovince = $_POST["userphysicalstateprovince"];
$userphysicalcountry = $_POST["userphysicalcountry"];
$userphysicalpostalcode = $_POST["userphysicalpostalcode"];
$usermailingaddress1 = $_POST["usermailingaddress1"];
$usermailingaddress2 = $_POST["usermailingaddress2"];
$usermailingcity = $_POST["usermailingcity"];
$usermailingstateprovince = $_POST["usermailingstateprovince"];
$usermailingcountry = $_POST["usermailingcountry"];
$usermailingpostalcode = $_POST["usermailingpostalcode"];
$userbillingaddress1 = $_POST["userbillingaddress1"];
$userbillingaddress2 = $_POST["userbillingaddress2"];
$userbillingcity = $_POST["userbillingcity"];
$userbillingstateprovince = $_POST["userbillingstateprovince"];
$userbillingcountry = $_POST["userbillingcountry"];
$userbillingpostalcode = $_POST["userbillingpostalcode"];
$usershippingaddress1 = $_POST["usershippingaddress1"];
$usershippingaddress2 = $_POST["usershippingaddress2"];
$usershippingcity = $_POST["usershippingcity"];
$usershippingstateprovince = $_POST["usershippingstateprovince"];
$usershippingcountry = $_POST["usershippingcountry"];
$usershippingpostalcode = $_POST["usershippingpostalcode"];
$userurl = $_POST["userurl"];
$userphone1 = $_POST["userphone1"];
$userphone1ext = $_POST["userphone1ext"];
$userphone2 = $_POST["userphone2"];
$userphone2ext = $_POST["userphone2ext"];
$userphonemobile = $_POST["userphonemobile"];
//$userphoneemergencymobile = $_POST["userphoneemergencymobile"];
$userphonefax = $_POST["userphonefax"];
$useremail = $_POST["useremail"];
$useremailemergency = $_POST["useremailemergency"];


if (count($_POST)>0 && $_POST["persistform"] != "1") {


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
		$msgerror .= "Please provid a Username.<br>\n";
	}
	else {
        $sql = "SELECT * FROM v_users ";
		$sql .= " where v_id = '$v_id' ";
		$sql .= " and username = '$username' ";
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
	$sql .= "'$username', ";
	$sql .= "'".md5('e3.7d.12'.$password)."', ";
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
	//echo $sql;
	//exit;
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
	return;
}

	require_once "includes/header.php";


	require_once "includes/getcontent.php";
	//echo "<img src='/images/spacer.gif' width='100%' height='3' style='background-color: #FFFFFF;'>";


	echo "<div align='center'>";
	echo "<table width='90%' border='0' cellpadding='0' cellspacing='2'>\n";

	echo "<tr>\n";
	echo "	<td align=\"left\">\n";
	echo "      <br>";

	$tablewidth ='width="100%"';
	echo "<form method='post' action=''>";

	  echo "<b>To sign up as a member, please fill out this form completely. All fields are required. </b><br>";
	  echo "<div class='borderlight' style='padding:10px;'>\n";
	  echo "<table $tablewidth cellpadding='6' cellspacing='0'>";
	  echo "	<tr>";
	  echo "		<td class='vncellreq' width='40%'>Username:</td>";
	  echo "		<td  class='vtable' width='60%'><input type='text' class='formfld' autocomplete='off' name='username' value='$username'></td>";
	  //echo "		<td>$username</td>";
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
	  //echo "    </table>";
	  //echo "    </div>";
	  //echo "<br>";


/*
	  echo "<b>Physical Address</b><br>";
	  echo "<div class='borderlight' style='padding:10px;'>\n";
	  echo "<table $tablewidth>";
	  echo "	<tr>";
	  echo "		<td width='40%'>Address 1:</td>";
	  echo "		<td width='60%'><input type='text' class='formfld' name='userphysicaladdress1' value='$userphysicaladdress1'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Address 2:</td>";
	  echo "		<td><input type='text' class='txt' name='userphysicaladdress2' value='$userphysicaladdress2'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>City:</td>";
	  echo "		<td><input type='text' class='txt' name='userphysicalcity' value='$userphysicalcity'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>State/Province:</td>";
	  echo "		<td>";
	  //echo "            <input type='text' class='txt' name='userphysicalstateprovince' value='$userphysicalstateprovince'>";
	  //---- Begin Select List --------------------
      $sql = "SELECT * FROM v_states ";
	  $prepstatement = $db->prepare(check_sql($sql));
	  $prepstatement->execute();

	  echo "<select name=\"userphysicalstateprovince\" class='txt'>\n";
	  echo "<option value=\"\"></option>\n";
	  $result = $prepstatement->fetchAll();
	  //$catcount = count($result);
	  foreach($result as $field) {
		  if ($userbillingstateprovince == $field[abbrev]) {
			echo "<option value='".$field[abbrev]."' selected>".$field[state]."</option>\n";
		  }
		  else {
			echo "<option value='".$field[abbrev]."'>".$field[state]."</option>\n";
		  }
	  }

	  echo "</select>";
	  unset($sql, $result);
	  //---- End Select List --------------------
	  echo "        </td>";
	  echo "        </td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Country:</td>";
	  echo "		<td><input type='text' class='txt' name='userphysicalcountry' value='$userphysicalcountry'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Postal Code:</td>";
	  echo "		<td><input type='text' class='txt' name='userphysicalpostalcode' value='$userphysicalpostalcode'></td>";
	  echo "	</tr>";
	  echo "    </table>";
	  echo "    </div>";
	  echo "<br>";

	  echo "<b>Mailing Address</b><br>";
	  echo "<div class='borderlight' style='padding:10px;'>\n";
	  echo "<table $tablewidth>";
	  echo "	<tr>";
	  echo "		<td width='40%'>Address 1:</td>";
	  echo "		<td width='60%'><input type='text' class='txt' name='usermailingaddress1' value='$usermailingaddress1'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Address 2:</td>";
	  echo "		<td><input type='text' class='txt' name='usermailingaddress2' value='$usermailingaddress2'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>City:</td>";
	  echo "		<td><input type='text' class='txt' name='usermailingcity' value='$usermailingcity'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>State/Province:</td>";
	  echo "		<td><input type='text' class='txt' name='usermailingstateprovince' value='$usermailingstateprovince'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Country:</td>";
	  echo "		<td><input type='text' class='txt' name='usermailingcountry' value='$usermailingcountry'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Postal Code:</td>";
	  echo "		<td><input type='text' class='txt' name='usermailingpostalcode' value='$usermailingpostalcode'></td>";
	  echo "	</tr>";
	  echo "    </table>";
	  echo "    </div>";
	  echo "<br>";

	  echo "<b>Billing Address</b><br>";
	  echo "<div class='borderlight' style='padding:10px;'>\n";
	  echo "<table $tablewidth>";
	  echo "	<tr>";
	  echo "		<td width='40%'>Address 1:</td>";
	  echo "		<td width='60%'><input type='text' class='txt' name='userbillingaddress1' value='$userbillingaddress1'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Address 2:</td>";
	  echo "		<td><input type='text' class='txt' name='userbillingaddress2' value='$userbillingaddress2'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>City:</td>";
	  echo "		<td><input type='text' class='txt' name='userbillingcity' value='$userbillingcity'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>State/Province:</td>";
	  echo "		<td>";
	  //echo "            <input type='text' class='txt' name='userbillingstateprovince' value='$userbillingstateprovince'>";
	  //---- Begin Select List --------------------
      $sql = "SELECT * FROM v_states ";
	  $prepstatement = $db->prepare(check_sql($sql));
	  $prepstatement->execute();

	  echo "<select name=\"userbillingstateprovince\" class='txt'>\n";
	  echo "<option value=\"\"></option>\n";
	  $result = $prepstatement->fetchAll();
	  //$catcount = count($result);
	  foreach($result as $field) {
		  if ($userbillingstateprovince == $field[abbrev]) {
			echo "<option value='".$field[abbrev]."' selected>".$field[state]."</option>\n";
		  }
		  else {
			echo "<option value='".$field[abbrev]."'>".$field[state]."</option>\n";
		  }
	  }

	  echo "</select>";
	  unset($sql, $result);
	  //---- End Select List --------------------
	  echo "        </td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Country:</td>";
	  echo "		<td><input type='text' class='txt' name='userbillingcountry' value='$userbillingcountry'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Postal Code:</td>";
	  echo "		<td><input type='text' class='txt' name='userbillingpostalcode' value='$userbillingpostalcode'></td>";
	  echo "	</tr>";
	  echo "    </table>";
	  echo "    </div>";
	  echo "<br>";

	  echo "<b>Shipping Address</b><br>";
	  echo "<div class='borderlight' style='padding:10px;'>\n";
	  echo "<table $tablewidth>";
	  echo "	<tr>";
	  echo "		<td width='40%'>Address 1:</td>";
	  echo "		<td width='60%'><input type='text' class='txt' name='usershippingaddress1' value='$usershippingaddress1'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Address 2:</td>";
	  echo "		<td><input type='text' class='txt' name='usershippingaddress2' value='$usershippingaddress2'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>City:</td>";
	  echo "		<td><input type='text' class='txt' name='usershippingcity' value='$usershippingcity'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>State/Province:</td>";
	  echo "		<td><input type='text' class='txt' name='usershippingstateprovince' value='$usershippingstateprovince'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Country:</td>";
	  echo "		<td><input type='text' class='txt' name='usershippingcountry' value='$usershippingcountry'></td>";
	  echo "	</tr>";
	  echo "	<tr>";
	  echo "		<td>Postal Code:</td>";
	  echo "		<td><input type='text' class='txt' name='usershippingpostalcode' value='$usershippingpostalcode'></td>";
	  echo "	</tr>";
	  echo "    </table>";
	  echo "    </div>";
	  echo "<br>";
*/

	  //echo "<b>Additional Info</b><br>";
	  //echo "<div class='borderlight' style='padding:10px;'>\n";
	  //echo "<table $tablewidth>";
	  //echo "	<tr>";
	  //echo "		<td width='40%'>Website:</td>";
	  //echo "		<td width='60%'><input type='text' class='txt' name='userurl' value='$userurl'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Phone 1:</td>";
	  //echo "		<td><input type='text' class='txt' name='userphone1' value='$userphone1'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Phone 1 Ext:</td>";
	  //echo "		<td><input type='text' class='txt' name='userphone1ext' value='$userphone1ext'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Phone 2:</td>";
	  //echo "		<td><input type='text' class='txt' name='userphone2' value='$userphone2'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Phone 2 Ext:</td>";
	  //echo "		<td><input type='text' class='txt' name='userphone2ext' value='$userphone2ext'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Mobile:</td>";
	  //echo "		<td><input type='text' class='txt' name='userphonemobile' value='$userphonemobile'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Emergency Mobile:</td>";
	  //echo "		<td><input type='text' class='txt' name='userphoneemergencymobile' value='$userphoneemergencymobile'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Fax:</td>";
	  //echo "		<td><input type='text' class='txt' name='userphonefax' value='$userphonefax'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Email:</td>";
	  //echo "		<td><input type='text' class='txt' name='useremail' value='$useremail'></td>";
	  //echo "	</tr>";
	  //echo "	<tr>";
	  //echo "		<td>Emergency Email:</td>";
	  //echo "		<td><input type='text' class='txt' name='useremailemergency' value='$useremailemergency'></td>";
	  //echo "	</tr>";

	  /*
	  //--- begin captcha ---
	  echo "	<tr>";
	  echo "        <td>&nbsp;</td>\n";
	  echo "		<td align='right'>\n";
	  echo "	    <br>\n";
	  echo "			<script language=\"JavaScript\" type=\"text/javascript\">\n";
	  echo "				function genNewCaptcha(imgObj) {\n";
	  echo "					var randnum = Math.floor((1-1000)*Math.random()+1000);\n";
	  echo "					imgObj.src='/includes/captcha/img.php?x=' + randnum;\n";
	  echo "				}\n";
	  echo "			</script>\n";
	  echo "			<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
	  echo "				<tr>\n";
	  echo "					<td align=\"left\" colspan=\"2\" style=\"font-size: 11px;\">Please enter the text you see from the image below...</td>\n";
	  echo "				</tr>\n";
	  echo "				<tr>\n";
	  echo "\n";
	  echo "					<td align=\"center\" valign=\"bottom\" width=\"50%\"><img id=\"captchaimg\" src=\"/includes/captcha/img.php\" onclick=\"genNewCaptcha(this); document.getElementById('captcha').focus();\" onmouseover=\"this.style.cursor='hand';\" alt=\"Click for a new image.\"></td>\n";
	  echo "					<td align=\"center\" valign=\"bottom\" width=\"50%\"><input type=\"text\" class=\"txt\" style=\"text-align: center;\" name=\"captcha\" id=\"captcha\" size=\"15\" style=\"margin-top: 15px;\"></td>\n";
	  echo "				</tr>\n";
	  echo "				<td align=\"left\" colspan=\"2\" style=\"font-size: 9px;\"><br>Can't read the image text?  Click the image for a new one.</td>\n";
	  echo "			</table>\n";
	  
	  echo "  			<br>";
	  echo "        </td>";
	  echo "	</tr>";
	  //--- end captcha ---
	  */




	  echo "    </table>";
	  echo "    </div>";

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


require_once "includes/footer.php";
?>
