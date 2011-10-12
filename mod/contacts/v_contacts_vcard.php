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
if (permission_exists('contacts_view')) {
	//access granted
}
else {
	echo "access denied";
	exit;
}

if (count($_GET)>0) {
	//create the vcard object
		require_once "includes/class_vcard.php";
		$vcard = new vcard();

	//get the contact id
		$contact_id = $_GET["id"];

	//get the contact's information
		$sql = "";
		$sql .= "select * from v_contacts ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and contact_id = '$contact_id' ";
		$prep_statement = $db->prepare(check_sql($sql));
		$prep_statement->execute();
		$result = $prep_statement->fetchAll();
		foreach ($result as &$row) {
			$type = $row["type"];
			$org = $row["org"];
			$n_given = $row["n_given"];
			$n_family = $row["n_family"];
			$nickname = $row["nickname"];
			$title = $row["title"];
			$role = $row["role"];
			$email = $row["email"];
			$url = $row["url"];
			$tz = $row["tz"];
			$note = $row["note"];
			break; //limit to 1 row
		}
		unset ($prep_statement);

		$vcard->data['company'] = $org;
		$vcard->data['display_name'] = $n_given." ".$n_family;
		$vcard->data['first_name'] = $n_given;
		$vcard->data['last_name'] = $n_family;
		$vcard->data['nickname'] = $nickname;
		$vcard->data['title'] = $title;
		$vcard->data['role'] = $role;
		$vcard->data['email1'] = $email;
		$vcard->data['url'] = $url;
		$vcard->data['timezone'] = $tz;
		$vcard->data['note'] = $note;

	//get the contact's telephone numbers
		$sql = "";
		$sql .= "select * from v_contacts_tel ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and contact_id = '$contact_id' ";
		$prep_statement = $db->prepare(check_sql($sql));
		$prep_statement->execute();
		$result = $prep_statement->fetchAll();
		foreach ($result as &$row) {
			$tel_type = $row["tel_type"];
			$tel_number = $row["tel_number"];
			$vcard->data[$tel_type.'_tel'] = $tel_number;
		}
		unset ($prep_statement);

	//get the contact's addresses
		$sql = "";
		$sql .= "select * from v_contacts_adr ";
		$sql .= "where v_id = '$v_id' ";
		$sql .= "and contact_id = '$contact_id' ";
		$prep_statement = $db->prepare(check_sql($sql));
		$prep_statement->execute();
		$result = $prep_statement->fetchAll();
		foreach ($result as &$row) {
			$adr_type = $row["adr_type"];
//			$adr_street = $row["adr_street"];
//			$adr_extended = $row["adr_extended"];
//			$adr_locality = $row["adr_locality"];
			$adr_region = $row["adr_region"];
//			$adr_postal_code = $row["adr_postal_code"];
			$adr_country = $row["adr_country"];
			$adr_latitude = $row["adr_latitude"];
			$adr_longitude = $row["adr_longitude"];
			$adr_type = strtolower(trim($adr_type));

			$vcard->data[$adr_type.'_address'] = $adr_street;
			$vcard->data[$adr_type.'_extended_address'] = $adr_extended;
			$vcard->data[$adr_type.'_city'] = $adr_locality;
			$vcard->data[$adr_type.'_state'] = $adr_region;
			$vcard->data[$adr_type.'_postal_code'] = $adr_postal_code;
			$vcard->data[$adr_type.'_country'] = $adr_country;
		}
		unset ($prep_statement);

	//download the vcard
		if ($_GET['type'] == "download") {
			$vcard->download();
		}

	//show the vcard in an text qr code
		if ($_GET['type'] == "text") {
			$vcard->build();
			$content = $vcard->card;
			echo $content;
		}

	//show the vcard in an image qr code
		if ($_GET['type'] == "image") {
			$vcard->build();
			$content = $vcard->card;

			//include
				require_once "includes/qr/qrcode.php";

			//error correction level
				//QR_ERROR_CORRECT_LEVEL_L : $e = 0;
				//QR_ERROR_CORRECT_LEVEL_M : $e = 1;
				//QR_ERROR_CORRECT_LEVEL_Q : $e = 2;
				//QR_ERROR_CORRECT_LEVEL_H : $e = 3;

			//get the qr object
				$qr = QRCode::getMinimumQRCode($content, QR_ERROR_CORRECT_LEVEL_L);

			//show the image
				header("Content-type: image/png");
				$im = $qr->createImage(5, 10);
				imagepng($im);
				imagedestroy($im);
		}

	//show the vcard in an html qr code
		if ($_GET['type'] == "html") {
			$qr->make();
			$qr->printHTML();
		}
}

/*
//additional un accounted fields
additional_name
name_prefix
name_suffix
department
work_po_box
home_po_box
home_extended_address
home_address
home_city
home_state
home_postal_code
home_country
pager_tel
email2
photo
birthday
sort_string
*/

?>