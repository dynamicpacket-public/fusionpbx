	<div align='center'>
	<table width='90%' border='0' cellpadding='0' cellspacing='2'>
		<?php if (strlen($msgerror) > 0) { ?>
		<tr><td>
		<?php echo $msgerror; ?>
                </td></tr>
        <?php } ?>
	<tr>
		<td align="left">
	      <br>

	<?php $tablewidth ='width="100%"'; ?>
	<form method='post' action=''>

	  <b>Please fill out this form completely. All BOLD fields are required. </b><br>
	  <div class='borderlight' style='padding:10px;'>
	  <table <?php echo $tablewidth; ?> cellpadding='6' cellspacing='0'>
	  	<tr>
	  		<td class='vncellreq' width='40%'>Username:</td>
	  		<td  class='vtable' width='60%'><input type='text' class='formfld' autocomplete='off' name='username' value='<?php echo $request['username']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncellreq'>Password:</td>
	  		<td class='vtable'><input type='password' class='formfld' autocomplete='off' name='password' value='<?php echo $request['password']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncellreq'>Confirm Password:</td>
	  		<td class='vtable'><input type='password' class='formfld' autocomplete='off' name='confirmpassword' value='<?php echo $request['confirmpassword']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncell'>Company Name:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='usercompanyname' value='<?php echo $request['usercompanyname']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncellreq'>First Name:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='userfirstname' value='<?php echo $request['userfirstname']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncellreq'>Last Name:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='userlastname' value='<?php echo $request['userlastname']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncellreq'>Email:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='useremail' value='<?php echo $request['useremail']; ?>'></td>
	  	</tr>
		<tr>
			<td class='vncellreq'>Phone:</td>
			<td class='vtable'><input type='text' class='formfld' name='userphone1' value="<?php echo $request['userphone1']; ?>"></td>
		</tr>
		<tr>
			<td class='vncell'>Phone Ext:</td>
			<td class='vtable'><input type='text' class='formfld' name='userphone1ext' value="<?php echo $request['userphone1ext']; ?>"></td>
		</tr>
</table>
	      </div>
	  <br>

	  <b>Billing Address</b><br>
	  <div class='borderlight' style='padding:10px;'>
	  <table <?php echo $tablewidth; ?> cellpadding='6' cellspacing='0'>
	  	<tr>
	  		<td class='vncellreq' width='40%'>Address 1:</td>
	  		<td  class='vtable' width='60%'><input type='text' class='formfld' name='userbillingaddress1' value='<?php echo $request['userbillingaddress1']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncell'>Address 2:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='userbillingaddress2' value='<?php echo $request['userbillingaddress2']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncellreq'>City:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='userbillingcity' value='<?php echo $request['userbillingcity']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncellreq'>State/Province:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='userbillingstateprovince' value='<?php echo $request['userbillingstateprovince']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncellreq'>Country:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='userbillingcountry' value='<?php echo $request['userbillingcountry']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncellreq'>Postal Code:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='userbillingpostalcode' value='<?php echo $request['userbillingpostalcode']; ?>'></td>
	  	</tr>
	      </table>
	      </div>
	  <br>

	  <b>Shipping Address</b><br>
	  <div class='borderlight' style='padding:10px;'>
	  <table <?php echo $tablewidth; ?>>
	  <table <?php echo $tablewidth; ?> cellpadding='6' cellspacing='0'>
	  	<tr>
	  		<td class='vncell' width='40%'>Address 1:</td>
	  		<td class='vtable' width='60%'><input type='text' class='formfld' name='usershippingaddress1' value='<?php echo $request['usershippingaddress1']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncell'>Address 2:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='usershippingaddress2' value='<?php echo $request['usershippingaddress2']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncell'>City:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='usershippingcity' value='<?php echo $request['usershippingcity']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncell'>State/Province:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='usershippingstateprovince' value='<?php echo $request['usershippingstateprovince']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncell'>Country:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='usershippingcountry' value='<?php echo $request['usershippingcountry']; ?>'></td>
	  	</tr>
	  	<tr>
	  		<td class='vncell'>Postal Code:</td>
	  		<td class='vtable'><input type='text' class='formfld' name='usershippingpostalcode' value='<?php echo $request['usershippingpostalcode']; ?>'></td>
	  	</tr>
	      </table>
	      </div>
	  <br>

	<div class='' style='padding:10px;'>
	<table <?php echo $tablewidth;?>>
		<tr>
			<!-- <td valign='top'>
				<input type="checkbox" name="newsletter" value="newsletter" /> Yes, sign me up for news letter<br />
				<input type="checkbox" name="tos_agree" value="tos_agree" /> I have read and agree to the terms of service
			</td> -->
			<td colspan='2' align='center'><?php echo recaptcha_get_html($publickey, $error); ?></td>
		</tr>
		<tr>
			<td colspan='2' align='center'>
	       <input type='submit' name='submit' class='btn' value='Create Account'>
			</td>
		</tr>
	</table>
	</form>

		</td>
		</tr>
	</table>
	</div>
