<?php 
function make_xmpp_xml($input) {
	$xml_out .= "<include>\n";
	$xml_out .= "  <profile type=\"client\">\n";
	$xml_out .= sprintf("    <param name=\"name\" value=\"%s\"/>\n", $input['profile_name']);
	$xml_out .= sprintf("    <param name=\"login\" value=\"%s/talk\"/>\n", $input['profile_username']);
	$xml_out .= sprintf("    <param name=\"password\" value=\"%s\"/>\n", $input['profile_password']);
	$xml_out .= sprintf("    <param name=\"dialplan\" value=\"XML\"/>\n", $input['dialplan']);
	$xml_out .= sprintf("    <param name=\"context\" value=\"%s\"/>\n", $input['context']);
	$xml_out .= "    <param name=\"message\" value=\"Jingle all the way\"/>\n";
	$xml_out .= sprintf("    <param name=\"rtp-ip\" value=\"%s\"/>\n", $input['rtp_ip']);
	$xml_out .= sprintf("    <param name=\"ext-rtp-ip\" value=\"%s\"/>\n", $input['ext_rtp_ip']);
	$xml_out .= sprintf("    <param name=\"auto-login\" value=\"%s\"/>\n", $input['auto_login']);
	$xml_out .= sprintf("    <param name=\"sasl\" value=\"%s\"/>\n", $input['sasl_type']);
	$xml_out .= sprintf("    <param name=\"server\" value=\"%s\"/>\n", $input['xmpp_server']);
	$xml_out .= sprintf("    <param name=\"tls\" value=\"%s\"/>\n", $input['tls_enable']);
	$xml_out .= sprintf("    <param name=\"use-rtp-timer\" value=\"%s\"/>\n", $input['use_rtp_timer']);
	$xml_out .= sprintf("    <param name=\"exten\" value=\"%s\"/>\n", $input['default_exten']);
	$xml_out .= sprintf("    <param name=\"vad\" value=\"%s\"/>\n", $input['vad']);
	$xml_out .= sprintf("    <param name=\"candidate-acl\" value=\"%s\"/>\n", $input['candidate_acl']);
	$xml_out .= sprintf("    <param name=\"local-network-acl\" value=\"%s\"/>\n", $input['local_network_acl']);
	$xml_out .= "  </profile>\n";
	$xml_out .= "</include>\n";

	return $xml_out;
}

?>
