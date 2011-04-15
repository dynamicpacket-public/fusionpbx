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
if(!isset($_SERVER["DOCUMENT_ROOT"])) { $_SERVER["DOCUMENT_ROOT"]=substr($_SERVER['SCRIPT_FILENAME'] , 0 , -strlen($_SERVER['PHP_SELF'])+1 );}
include "v_config_cli.php";

ini_set('max_execution_time',900); //15 minutes
ini_set('memory_limit', '96M');

$fd = fopen("php://stdin", "r");
$email = file_get_contents ("php://stdin");
fclose($fd);

if (file_exists('/tmp')) { $tmp_dir = '/tmp'; } else { $tmp_dir = ''; }
$fp = fopen($tmp_dir."/voicemailtoemail.txt", "w");

ob_end_clean();
ob_start();

//echo "raw message: \n".$email."\n";

//if there is a carriage return remove it and standardize on line feed
	$email = str_replace("\r\n", "\n", $email);

//get main header and body
	$tmparray = explode("\n\n", $email);
	$mainheader = $tmparray[0];
	$maincontent = substr($email, strlen($mainheader), strlen($email));

//echo "main content:\n".$maincontent."\n";

//get the boundary
	$tmparray = explode("\n", $mainheader);
	$contenttmp = $tmparray[1]; //Content-Type: multipart/mixed; boundary="XXXX_boundary_XXXX"
	$tmparray = explode('; ', $contenttmp); //boundary="XXXX_boundary_XXXX"
	$contenttmp = $tmparray[1];
	$tmparray = explode('=', $contenttmp); //"XXXX_boundary_XXXX"
	$boundary = $tmparray[1];
	//$boundary = trim($boundary,'"');
	$boundary = str_replace('"', '', $boundary);

//echo "boundary: $boundary\n";


//put the main headers into an array
	$mainheaderarray = explode("\n", $mainheader);
	//print_r($mainheaderarray);
	foreach ($mainheaderarray as $val) {
		$tmparray = explode(': ', $val);
		//print_r($tmparray);
		$var[$tmparray[0]] = trim($tmparray[1]);
	}

	$var['To'] = str_replace("<", "", $var['To']);
	$var['To'] = str_replace(">", "", $var['To']);

	echo "To: ".$var['To']."\n";
	echo "From: ".$var['From']."\n";
	echo "Subject: ".$var['Subject']."\n";
	//print_r($var);
	echo "\n\n";


// explode mime type multi-part into each part
	$maincontent = str_replace($boundary."--", $boundary, $maincontent);
	$tmparray = explode("--".$boundary, $maincontent);

//echo "explode mime type:\n".print_r($tmparray)."\n";

// loop through each mime part
	$i=0;
	foreach ($tmparray as $mimepart) {

		$mimearray = explode("\n\n", $mimepart);
		$subheader = $mimearray[0];
		$headermimearray = explode("\n", trim($subheader));

		$x=0;
		foreach ($headermimearray as $val) {
			if(stristr($val, ':') === FALSE) {
				$tmparray = explode('=', $val); //':' not found
				if (trim($tmparray[0]) == "boundary") {
					$subboundary = $tmparray[1];
					$subboundary = trim($subboundary,'"');
					//echo "subboundary: ".$subboundary."\n";
				}
			}
			else {
				$tmparray = explode(':', $val); //':' found
			}

			//print_r($tmparray);
			$var[trim($tmparray[0])] = trim($tmparray[1]);
		}
		//print_r($var);


		$contenttypearray = explode(' ', $headermimearray[0]);
		if ($contenttypearray[0] == "Content-Type:") {
			$contenttype = trim($contenttypearray[1]);

echo "type: ".$contenttype."\n";

			switch ($contenttype) {
			case "multipart/alternative;":

				$content = trim(substr($mimepart, strlen($subheader), strlen($mimepart)));

				$content = str_replace($subboundary."--", $subboundary, $content);
				$tmpsubarray = explode("--".$subboundary, $content);
					foreach ($tmpsubarray as $mimesubsubpart) {

						$mimesubsubarray = explode("\n\n", $mimesubsubpart);
						$subsubheader = $mimesubsubarray[0];

						$headersubsubmimeearray = explode("\n", trim($subsubheader));
						$subsubcontenttypearray = explode(' ', $headersubsubmimeearray[0]);
						//echo "subsubcontenttypearray[0] ".$subsubcontenttypearray[0]."\n";

						if ($subsubcontenttypearray[0] == "Content-Type:") {
							$subsubcontenttype = trim($subsubcontenttypearray[1]);
							switch ($subsubcontenttype) {
							case "text/plain;":
								$textplain = trim(substr($mimesubsubpart, strlen($subsubheader), strlen($mimesubsubpart)));
								//echo "text/plain: $textplain\n";
								break;
							case "text/html;":
								$texthtml = trim(substr($mimesubsubpart, strlen($subsubheader), strlen($mimesubsubpart)));
								//echo "text/html: $texthtml\n";
								break;
							}
						} //end if


					} //end foreach

					break;
			case "audio/wav;":
					$attachment_type = "audio/wav";
					$attachment_ext = ".wav";
					$attachment_str = trim(substr($mimepart, strlen($subheader), strlen($mimepart)));
					//echo "\n*** begin wav ***\n".$attachment_str."\n*** end wav ***\n";
				break;
			case "audio/mp3;":
					$attachment_type = "audio/mp3";
					$attachment_ext = ".mp3";
					$attachment_str = trim(substr($mimepart, strlen($subheader), strlen($mimepart)));
					//echo "\n*** begin mp3 ***\n".$attachment_str."\n*** end mp3 ***\n";
				break;
			}//end switch
		} //end if

		$i++;

	} //end foreach


//send the email

	include "class.phpmailer.php";
	include "class.smtp.php"; // optional, gets called from within class.phpmailer.php if not already loaded
	$mail = new PHPMailer();

	$mail->IsSMTP();                  	// set mailer to use SMTP
	if ($v_smtpauth == "true") {
		$mail->SMTPAuth = $v_smtpauth;      // turn on/off SMTP authentication
	}
	$mail->Host   = $v_smtphost;
	if (strlen($v_smtpsecure)>0) {
		$mail->SMTPSecure = $v_smtpsecure;
	}
	if ($v_smtpusername) {
		$mail->Username = $v_smtpusername;
		$mail->Password = $v_smtppassword;
	}
	$mail->SMTPDebug  = 2;


	$mail->From       = $v_smtpfrom;
	$mail->FromName   = $v_smtpfromname;
	$mail->Subject    = $var['Subject'];
	$mail->AltBody    = $textplain;   // optional, comment out and test
	$mail->MsgHTML($texthtml);


	$v_to = $var['To'];
	$v_to = str_replace(";", ",", $v_to);
	$v_to_array = explode(",", $v_to);
	if (count($v_to_array) == 0) {
		$mail->AddAddress($var['To']);
	}
	else {
		foreach($v_to_array as $v_to_row) {
			if (strlen($v_to_row) > 0) {
				echo "Add Address: $v_to_row\n";
				$mail->AddAddress($v_to_row);
			}
		}
	}

	if (strlen($attachment_str) > 0) {
			//$mail->AddAttachment($v_dir."/data/domain/example.wav");  // attachment
			$filename='voicemail'.date('Ymds').$attachment_ext;
			$encoding = "base64";
			$mail->AddStringAttachment(base64_decode($attachment_str),$filename,$encoding,$attachment_type);
	}
	unset($attachment_str);

	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	}
	else {
		echo "Message sent!";
	}

//echo phpinfo();

$content = ob_get_contents(); //get the output from the buffer
ob_end_clean(); //clean the buffer

fwrite($fp, $content);
fclose($fp);

?>