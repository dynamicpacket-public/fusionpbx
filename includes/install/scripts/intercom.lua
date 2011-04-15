--	intercom.lua
--	Part of FusionPBX
--	Copyright (C) 2010 Mark J Crane <markjcrane@fusionpbx.com>
--	All rights reserved.
--
--	Redistribution and use in source and binary forms, with or without
--	modification, are permitted provided that the following conditions are met:
--
--	1. Redistributions of source code must retain the above copyright notice,
--	   this list of conditions and the following disclaimer.
--
--	2. Redistributions in binary form must reproduce the above copyright
--	   notice, this list of conditions and the following disclaimer in the
--	   documentation and/or other materials provided with the distribution.
--
--	THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
--	INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
--	AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
--	AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
--	OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
--	SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
--	INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
--	CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
--	ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
--	POSSIBILITY OF SUCH DAMAGE.

pin_number = "";
max_tries = "3";
digit_timeout = "3000";

function trim (s)
	return (string.gsub(s, "^%s*(.-)%s*$", "%1"))
end

function explode ( seperator, str ) 
	local pos, arr = 0, {}
	for st, sp in function() return string.find( str, seperator, pos, true ) end do -- for each divider found
		table.insert( arr, string.sub( str, pos, st-1 ) ) -- attach chars left of current divider
		pos = sp + 1 -- jump past current divider
	end
	table.insert( arr, string.sub( str, pos ) ) -- attach chars right of last divider
	return arr
end

if ( session:ready() ) then
	session:answer();
	--get the dialplan variables and set them as local variables
		pin_number = session:getVariable("pin_number");
		domain_name = session:getVariable("domain_name");
		sounds_dir = session:getVariable("sounds_dir");
		extension_list = session:getVariable("extension_list");
		caller_id_name = session:getVariable("caller_id_name");
		caller_id_number = session:getVariable("caller_id_number");
		extension_table = explode(",",extension_list);
		sip_from_user = session:getVariable("sip_from_user");

	--set the sounds path for the language, dialect and voice
		default_language = session:getVariable("default_language");
		default_dialect = session:getVariable("default_dialect");
		default_voice = session:getVariable("default_voice");
		if (not default_language) then default_language = 'en'; end
		if (not default_dialect) then default_dialect = 'us'; end
		if (not default_voice) then default_voice = 'callie'; end

	if (caller_id_name) then
		--caller id name provided do nothing
	else
		effective_caller_id_name = session:getVariable("effective_caller_id_name");
		caller_id_number = effective_caller_id_name;
	end

	if (caller_id_number) then
		--caller id number provided do nothing
	else
		effective_caller_id_number = session:getVariable("effective_caller_id_number");
		caller_id_number = effective_caller_id_number;
	end

	--if the pin number is provided then require it
	if (pin_number) then
		min_digits = string.len(pin_number);
		max_digits = string.len(pin_number)+1;
		digits = session:playAndGetDigits(min_digits, max_digits, max_tries, digit_timeout, "#", sounds_dir.."/"..default_language.."/"..default_dialect.."/"..default_voice.."/custom/please_enter_the_pin_number.wav", "", "\\d+");
		if (digits == pin_number) then
			--pin is correct
		else
			session:streamFile(sounds_dir.."/"..default_language.."/"..default_dialect.."/"..default_voice.."/custom/your_pin_number_is_incorect_goodbye.wav");
			session:hangup("NORMAL_CLEARING");
			return;
		end
	end

	api = freeswitch.API();
	for index,value in pairs(extension_table) do
		extension_status = "show channels like "..value.."@"..domain_name;
		reply = api:executeString(extension_status);
		reply = trim(reply);
		if (reply == "0 total.") then
			--freeswitch.consoleLog("NOTICE", "extension "..value.." available\n");
			if (value == sip_from_user) then
				--this extension is the caller that initated the intercom
			else
				cmd_string = "bgapi originate {sip_auto_answer=true,hangup_after_bridge=false,origination_caller_id_name='"..caller_id_name.."',origination_caller_id_number="..caller_id_number.."}user/"..value.."@"..domain_name.." conference:intercom@default+flags{mute} inline";
				api:executeString(cmd_string);
			end
			--freeswitch.consoleLog("NOTICE", "cmd_string "..cmd_string.."\n");

		else
			--call status busy
		end
	end

	--session:execute("info");

	--send main call to the conference room
		session:execute("conference", "intercom@default+flags{endconf}");

end