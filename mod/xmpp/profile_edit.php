<script type="text/javascript" language="JavaScript">
function enable_change(enable_over) {
  var endis;
  endis = !(document.iform.enable.checked || enable_over);
  document.iform.range_from.disabled = endis;
  document.iform.range_to.disabled = endis;
}

function show_advanced_config() {
  document.getElementById("showadvancedbox").innerHTML='';
  aodiv = document.getElementById('showadvanced');
  aodiv.style.display = "block";
}

function hide_advanced_config() {
  document.getElementById("showadvancedbox").innerHTML='';
  aodiv = document.getElementById('showadvanced');
  aodiv.style.display = "block";
}
</script>

<div align='center'>
<table width='100%' border='0' cellpadding='0' cellspacing='2'>

<tr class='border'>
  <td align=\"left\">
      <br>

<form method='post' name='ifrm' action=''>

<div align='center'> 
<table width='100%'  border='0' cellpadding='6' cellspacing='0'> 
<tr> 
<td colspan='2'> 
<table width="100%" border="0" cellpadding="0" cellspacing="0"> 
	<tr> 
		<td align='left' width="50%"> 
			<strong>Profile Edit</strong><br> 
		</td>		<td width='50%' align='right'> 
			<input type='submit' name='submit' class='btn' value='Save'> 
			<!-- <input type='button' class='btn' name='' alt='copy' onclick="if (confirm('Do you really want to copy this?')){window.location='v_gateways_copy.php?id=1';}" value='Copy'>  -->
			<input type='button' class='btn' name='' alt='back' onclick="window.location='v_xmpp.php'" value='Back'> 
		</td> 
	</tr>	<tr>		<td align='left' colspan='2'> 
			Defines a connections to a Jabber, GTalk, or other XMPP Provider server. <br /> 
		</td> 
	</tr> 
</table> 
<br /> 
</td> 
</tr> 
<tr> 
<td width="30%" class='vncellreq' valign='top' align='left' nowrap> 
    Profile Name:
</td> 
<td width="70%" class='vtable' align='left'> 
    <input class='formfld' type='text' name='profile_name' maxlength='255' value="<?php echo $profile['profile_name']; ?>"> 
<br /> 
Enter the profile name here.
</td> 
</tr> 
<tr> 
<td class='vncellreq' valign='top' align='left' nowrap> 
    Username:
</td> 
<td class='vtable' align='left'> 
    <input class='formfld' type='text' name='username' maxlength='255' value="<?php echo $profile['username'];?>"> 
<br /> 
Enter the username here.
</td> 
</tr> 
<tr> 
<td class='vncellreq' valign='top' align='left' nowrap> 
    Password:
</td> 
<td class='vtable' align='left'> 
    <input class='formfld' type='password' name='password' id='password' maxlength='50' onfocus="document.getElementById('show_password').innerHTML = 'Password: '+document.getElementById('password').value;" value="<?php echo $profile['password'];?>"> 
<br /> 
<span onclick="document.getElementById('show_password').innerHTML = ''">Enter the password here. </span><span id='show_password'></span> 
</td> 
</tr> 
<tr> 
<td class='vncellreq' valign='top' align='left' nowrap> 
    RTP IP:
</td> 
<td class='vtable' align='left'> 
    <input class='formfld' type='text' name='rtp_ip' maxlength='255' value="<?php echo $profile['rtp_ip'];?>">
<br /> 
Enter the domain or IP address of the proxy.
</td> 
</tr> 
<tr> 
<td class='vncellreq' valign='top' align='left' nowrap> 
    Auto-Login:
</td> 
<td class='vtable' align='left'> 
    <select class='formfld' name='auto_login'> 
    <option value='true' SELECTED>true</option> 
    <option value='false'>false</option> 
    </select> 
<br /> 
Choose whether to automattically login. 
</td> 
</tr> 
<tr> 
<td style='padding: 0px;' colspan='2' class='' valign='top' align='left' nowrap> 
	<div id="showadvancedbox"> 
		<table width="100%" border="0" cellpadding="6" cellspacing="0"> 
		<tr> 
		<td width="30%" valign="top" class="vncell">Show Advanced</td> 
		<td width="70%" class="vtable"> 
			<input type="button" onClick="show_advanced_config()" value="Advanced"></input></a> 
		</td> 
		</tr> 
		</table> 
	</div> 
	<div id="showadvanced" style="display:none"> 
	<table width="100%" border="0" cellpadding="6" cellspacing="0"> 
<tr> 
<td class='vncellreq' valign='top' align='left' nowrap> 
    SASL Type:
</td> 
<td class='vtable' align='left'> 
    <select class='formfld' name='sasl_type'> 
    <option value='plain' SELECTED>plain</option> 
    <option value='md5'>md5</option> 
    </select> 
<br /> 
Choose SASL Type. Plain or MD5
</td> 
</tr> 
<tr> 
<td width='30%' class='vncell' valign='top' align='left' nowrap> 
    XMPP Server:
</td> 
<td width='70%' class='vtable' align='left'> 
    <input class='formfld' type='text' name='xmpp_server' maxlength='255' value="<?php echo $profile['xmpp_server'];?>"> 
<br /> 
Enter alternate XMPP server if the server where the jabber is hosted is not the same as the one in the Username eg: Google
</td> 
</tr> 
<tr> 
<td class='vncell' valign='top' align='left' nowrap> 
    Default Extension:
</td> 
<td class='vtable' align='left'> 
    <input class='formfld' type='text' name='default_exten' maxlength='255' value="<?php echo $profile['default_exten'];?>"> 
<br /> 
default extension (if one cannot be determined)
</td> 
</tr> 
<tr> 
<td class='vncell' valign='top' align='left' nowrap> 
    Enable TLS:
</td> 
<td class='vtable' align='left'> 
    <select class='formfld' name='tls_enable'> 
    <option value='true' SELECTED>true</option> 
    <option value='false'>false</option> 
    </select> 
<br /> 
Enable TLS or not
</td> 
</tr> 
<tr> 
<td class='vncell' valign='top' align='left' nowrap> 
    Use RTP Timer
</td> 
<td class='vtable' align='left'> 
    <select class='formfld' name='use_rtp_timer'> 
    <option value='true' SELECTED>true</option> 
    <option value='false'>false</option> 
    </select> 
<br /> 
disable to trade async for more calls
</td> 
</tr> 
<tr> 
<td class='vncell' valign='top' align='left' nowrap> 
	Voice Activity Detection
</td> 
<td class='vtable' align='left'> 
	<select class='formfld' name='vad'> 
	<option value='none' SELECTED>none</option> 
	<option value='in'>in</option> 
	<option value='out'>out</option> 
	<option value='both'>both</option> 
</select> 
<br /> 
Which direction are we doing VAD?
</td> 
</tr> 
<tr> 
<td class='vncell' valign='top' align='left' nowrap> 
    candidate-acl
</td> 
<td class='vtable' align='left'> 
    <input class='formfld' type='text' name='candidate_acl' maxlength='255' value="<?php echo $profile['candidate-acl'];?>"> 
<br /> 
candidate-acl
</td> 
</tr> 
<tr> 
<td class='vncell' valign='top' align='left' nowrap> 
    local-network-acl
</td> 
<td class='vtable' align='left'> 
    <input class='formfld' type='text' name='local_network_acl' maxlength='255' value="<?php echo $profile['local_network_acl'];?>"> 
<br /> 
local network ACL
</td> 
</tr> 
	</table> 
	</div></td> 
</tr> 
<tr> 
<td class='vncellreq' valign='top' align='left' nowrap> 
    Context:
</td> 
<td class='vtable' align='left'> 
    <input class='formfld' type='text' name='context' maxlength='255' value="public"> 
<br /> 
Enter the context here.
</td> 
</tr> 
<tr> 
<td class='vncellreq' valign='top' align='left' nowrap> 
    Enabled:
</td> 
<td class='vtable' align='left'> 
    <select class='formfld' name='enabled'> 
    <option value='true' SELECTED >true</option> 
    <option value='false'>false</option> 
    </select> 
<br /> 
 
</td> 
</tr> 
<tr> 
<td class='vncell' valign='top' align='left' nowrap> 
    Profile Description:
</td> 
<td class='vtable' align='left'> 
    <input class='formfld' type='text' name='description' value=''> 
<br /> 
Enter the description of the Profile here.
</td> 
</tr> 
	<tr> 
		<td colspan='2' align='right'> 
				<input type='hidden' name='profile_id' value='<?php echo $profile['xmpp_profile_id']; ?>'> 
				<input type='submit' name='submit' class='btn' value='Save'> 
		</td> 
	</tr></table></form>	</td>	</tr></table></div>

