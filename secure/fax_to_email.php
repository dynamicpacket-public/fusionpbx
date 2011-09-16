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
include "v_config_cli.php";

ob_end_clean();
ob_start();

echo "\n---------------------------------\n";

$php_version = substr(phpversion(), 0, 1);
if ($php_version == '4') {
	$domain = $_REQUEST["domain"];
	$fax_email = $_REQUEST["email"];
	$fax_extension = $_REQUEST["extension"];
	$fax_name = $_REQUEST["name"];
	$fax_messages = $_REQUEST["messages"];
	$fax_retry = $_REQUEST["retry"];
}
else {
	$tmp_array = explode("=", $_SERVER["argv"][1]);
	$fax_email = $tmp_array[1];
	unset($tmp_array);

	$tmp_array = explode("=", $_SERVER["argv"][2]);
	$fax_extension = $tmp_array[1];
	unset($tmp_array);

	$tmp_array = explode("=", $_SERVER["argv"][3]);
	$fax_name = $tmp_array[1];
	unset($tmp_array);

	$tmp_array = explode("=", $_SERVER["argv"][4]);
	$fax_messages = $tmp_array[1];
	unset($tmp_array);

	$tmp_array = explode("=", $_SERVER["argv"][5]);
	$domain = $tmp_array[1];
	unset($tmp_array);

	$tmp_array = explode("=", $_SERVER["argv"][6]);
	$fax_retry = $tmp_array[1];
	unset($tmp_array);
}

//echo "fax_email $fax_email\n";
//echo "fax_extension $fax_extension\n";
//echo "fax_name $fax_name\n";
//echo "cd $dir_fax; /usr/bin/tiff2png ".$dir_fax.'/'.$fax_name.".png\n";

if (strlen($domain) > 0) {
	$dir_fax = $v_storage_dir.'/fax/'.$domain.'/'.$fax_extension.'/inbox';
}
else {
	$dir_fax = $v_storage_dir.'/fax/'.$fax_extension.'/inbox';
}

$fax_file_warning = "";
if (file_exists($dir_fax.'/'.$fax_name.".tif")) {
	if (!file_exists($dir_fax.'/'.$fax_name.".pdf")) {
		//echo "cd $dir_fax; /usr/bin/tiff2pdf -f -o ".$fax_name.".pdf ".$dir_fax.'/'.$fax_name.".tif\n";
		$tmp_tiff2pdf = exec("which tiff2pdf");
		if (strlen($tmp_tiff2pdf) > 0) {
			exec("cd ".$dir_fax."; ".$tmp_tiff2pdf." -f -o ".$fax_name.".pdf ".$dir_fax.'/'.$fax_name.".tif");
		}
	}
}
else {
	$fax_file_warning = " Fax image not available on server.";
}

$tmp_subject = "Fax Received: ".$fax_name;
$tmp_textplain  = "\nFax Received:\n";
$tmp_textplain .= "Name: ".$fax_name."\n";
$tmp_textplain .= "Extension: ".$fax_extension."\n";
$tmp_textplain .= "Messages: ".$fax_messages."\n";
$tmp_textplain .= $fax_file_warning."\n";
if ($fax_retry == 'yes') {
  $tmp_textplain .= "This message arrived earlier and has been queued until now due to email server issues.\n";
}
$tmp_texthtml = $tmp_textplain;

//set php ini values
	ini_set(max_execution_time,900); //15 minutes
	ini_set('memory_limit', '96M');

//open the file for writing
	$fp = fopen($tmp_dir."/fax_to_email.txt", "w");

//send the email
	include "class.phpmailer.php";
	include "class.smtp.php"; // optional, gets called from within class.phpmailer.php if not already loaded
	$mail = new PHPMailer();

	$mail->IsSMTP(); // set mailer to use SMTP
	if ($v_smtpauth == "true") {
		$mail->SMTPAuth = $v_smtpauth; // turn on/off SMTP authentication
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

	echo "v_smtpfrom: $v_smtpfrom\n";
	echo "v_smtpfromname: $v_smtpfromname\n";
	echo "tmp_subject: $tmp_subject\n";

	$mail->From       = $v_smtpfrom;
	$mail->FromName   = $v_smtpfromname;
	$mail->Subject    = $tmp_subject;
	$mail->AltBody    = $tmp_textplain;   // optional, comment out and test
	$mail->MsgHTML($tmp_texthtml);

	$tmp_to = $fax_email;
	$tmp_to = str_replace(";", ",", $tmp_to);
	$tmp_to_array = explode(",", $tmp_to);
	foreach($tmp_to_array as $tmp_to_row) {
		if (strlen($tmp_to_row) > 0) {
			echo "tmp_to_row: $tmp_to_row\n";
			$mail->AddAddress($tmp_to_row);
		}
	}

	if (strlen($fax_name) > 0) {
		if (!file_exists($dir_fax.'/'.$fax_name.".pdf")) {
			$mail->AddAttachment($dir_fax.'/'.$fax_name.'.tif');  // tif attachment
		}
		if (file_exists($dir_fax.'/'.$fax_name.".pdf")) {
			$mail->AddAttachment($dir_fax.'/'.$fax_name.'.pdf');  // pdf attachment
		}
		//$filename='fax.tif'; $encoding = "base64"; $type = "image/tif";
		//$mail->AddStringAttachment(base64_decode($strfax),$filename,$encoding,$type);
	}

	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
		$email_status=$mail;
	}
	else {
		echo "Message sent!";
		$email_status="ok";
	}

//echo "test:".$faxresult.$faxsender.$faxpages;

$content = ob_get_contents(); //get the output from the buffer
ob_end_clean(); //clean the buffer

fwrite($fp, $content);
fclose($fp);

// the following files are created:
//     /usr/local/freeswitch/storage/fax
//        emailed_faxes.log - this is a log of all the faxes we have successfully emailed.  (note that we need to work out how to rotate this log)
//        failed_fax_emails.log - this is a log of all the faxes we have failed to email.  This log is in the form of instructions that we can re-execute in order to retry.
//            Whenever this exists there should be an at job present to run it sometime in the next 3 minutes (check with atq).  If we succeed in sending the messages
//            this file will be removed.
//     /tmp
//        fax_email_retry.sh - this is the renamed failed_fax_emails.log and is created only at the point in time that we are trying to re-send the emails.  Note however
//            that this will continue to exist even if we succeed as we do not delete it when finished.
//        failed_fax_emails.sh - this is created when we have a email we need to re-send.  At the time it is created, an at job is created to execute it in 3 minutes time,
//            this allows us to try sending the email again at that time.  If the file exists but there is no at job this is because there are no longer any emails queued
//            as we have successfully sent them all.
if (stristr(PHP_OS, 'WIN')) {
	//not compatible with windows
}
else {
	$fax_to_email_queue_dir = $v_storage_dir."/fax";

	// note that we need to IDENTIFY the error condition and only make this happen if the error occurs - currently we do it every time and this is bad!
	if ($email_status == 'ok') {
		$fp = fopen($fax_to_email_queue_dir."/emailed_faxes.log", "a");
		fwrite($fp, $fax_name." received on ".$fax_extension." emailed to ".$fax_email." ".$fax_messages."\n");
		fclose($fp);
	} else {
		// create an instruction log to email messages once the connection to the mail server has been restored
		$fp = fopen($fax_to_email_queue_dir."/failed_fax_emails.log", "a");
		fwrite($fp, $php_dir."/php ".$v_secure."/fax_to_email.php email=$fax_email extension=$fax_extension name=$fax_name messages='$fax_messages' retry=yes\n");
		fclose($fp);

		// create a script to do the delayed mailing
		$fp = fopen($tmp_dir."/failed_fax_emails.sh", "w");
		fwrite($fp, "rm ".$tmp_dir."/fax_email_retry.sh\n");
		fwrite($fp, "mv ".$fax_to_email_queue_dir."/failed_fax_emails.log ".$tmp_dir."/fax_email_retry.sh\n");
		fwrite($fp, "chmod 777 ".$tmp_dir."/fax_email_retry.sh\n");
		fwrite($fp, $tmp_dir."/fax_email_retry.sh\n");
		fclose($fp);
		$tmp_response = exec("chmod 777 ".$tmp_dir."/failed_fax_emails.sh");
		// note we use batch in order to execute when system load is low.  Alternatively this could be replaced with AT.
		$tmp_response = exec("batch -f ".$tmp_dir."/failed_fax_emails.sh now + 3 minutes");
	}
}

?>
