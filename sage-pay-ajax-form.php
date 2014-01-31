<?php
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	die('Not valid request');
}
require_once('../../../wp-load.php');
require_once('../../../wp-config.php'); 

$obj_sage = new SagePayGateway();
$Amount = isset($_SESSION['SAGEPAY_DATA']['amount'])?$_SESSION['SAGEPAY_DATA']['amount']:'';
$data = $obj_sage->sagepay_form();
?>
<h3>Enter Credit Card Payment Information:</h3>
<form method="post" action="#" ENCTYPE="multipart/form-data" id="frm-sage-pay" >
 
<table width=518 border="0" cellpadding="3" cellspacing="3" bgcolor="#FFFFFF">
  <tr bgcolor="#E5E5E5">
    <td height="22" colspan="3" align="left" valign="middle"><strong>&nbsp;Billing Information (required)</strong></td>
  </tr>
  <tr>
    <td height="22" width="180" align="right" valign="middle">First Name:</td>
    <td colspan="2" align="left"><input name="BillingFirstnames" type="text" value="<?php echo isset($data['BillingFirstnames'])?$data['BillingFirstnames']:'' ;?>"  size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Last Name:</td>
    <td colspan="2" align="left"><input name="BillingSurname" type="text" value="<?php echo isset($data['BillingSurname'])?$data['BillingSurname']:'' ;?>"  size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Street Address:</td>
    <td colspan="2" align="left"><input name="BillingAddress1" type="text" value="<?php echo isset($data['BillingAddress1'])?$data['BillingAddress1']:'' ;?>"  size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Street Address (2):</td>
    <td colspan="2" align="left"><input name="BillingAddress2" type="text" value="<?php echo isset($data['BillingAddress2'])?$data['BillingAddress2']:'' ;?>"  size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">City:</td>
    <td colspan="2" align="left"><input name="BillingCity" type="text" value="<?php echo isset($data['BillingCity'])?$data['BillingCity']:'' ;?>"  size="50"></td>
  </tr>

  <tr>
    <td height="22" align="right" valign="middle">Zip/Postal Code:</td>
    <td colspan="2" align="left"><input name="BillingPostCode" type="text" value="<?php echo isset($data['BillingPostCode'])?$data['BillingPostCode']:'' ;?>"  size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Country:</td>
    <td colspan="2" align="left">
	<?php $countries = $obj_sage->countries(); ?>
	 <SELECT NAME="BillingCountry" >    
		<OPTION VALUE="" >--Select Country--</OPTION>
		<?php foreach($countries as $code => $country) { ?>
			 <OPTION VALUE="<?php echo $code; ?>" <?php if(isset($data['BillingCountry']) && $code == $data['BillingCountry']) { echo "SELECTED";   } ?>><?php echo $country; ?></OPTION>
		<?php } ?>
	 </SELECT>    
	</td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="left" valign="middle">&nbsp;</td>
  </tr>
  <tr bgcolor="#E5E5E5">
    <td height="22" colspan="3" align="left" valign="middle"><strong>&nbsp;Credit Card (required)</strong></td>
  </tr>
  
   <tr>
    <td height="22" align="right" valign="middle">Card Type:</td>
    <td colspan="2" align="left">
      <SELECT NAME="CardType" >        
		<option value="VISA">Visa</option>
		<option value="MC">MasterCard</option>
		<option value="DELTA">Visa Debit</option>
		<option value="UKE">Visa Electron</option>
      </SELECT>        
    </td>
  </tr>
   <tr>
    <td height="22" align="right" valign="middle">Name on Card:</td>
    <td colspan="2" align="left"><input name="CardHolder" type="text" value="<?php echo isset($data['CardHolder'])?$data['CardHolder']:'' ;?>" size="19" maxlength="255"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Credit Card Number:</td>
    <td colspan="2" align="left"><input name="CardNumber" type="text" value="<?php echo isset($data['CardNumber'])?$data['CardNumber']:'' ;?>"  size="19" maxlength="40"></td>
  </tr>
   <tr>
    <td height="22" align="right" valign="middle">Card Verification Number:</td>
    <td colspan="2" align="left">
	<input name="CV2" type="password" value="<?php echo isset($data['CV2'])?$data['CV2']:'' ;?>"  size="5" maxlength="4">&nbsp;
 
	</td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Expiry Date:</td>
    <td colspan="2" align="left">
      <SELECT NAME="ExpiryDateMonth" >
        <OPTION VALUE="" >--Month--</OPTION>
        <OPTION VALUE="01">January (01)</OPTION>
        <OPTION VALUE="02" <?php if(isset($data['ExpiryDateMonth'])) { echo "SELECTED"; } ?> >February (02)</OPTION>
        <OPTION VALUE="03">March (03)</OPTION>
        <OPTION VALUE="04">April (04)</OPTION>
        <OPTION VALUE="05">May (05)</OPTION>
        <OPTION VALUE="06">June (06)</OPTION>
        <OPTION VALUE="07">July (07)</OPTION>
        <OPTION VALUE="08">August (08)</OPTION>
        <OPTION VALUE="09">September (09)</OPTION>
        <OPTION VALUE="10">October (10)</OPTION>
        <OPTION VALUE="11">November (11)</OPTION>
        <OPTION VALUE="12">December (12)</OPTION>
      </SELECT> /
      <SELECT NAME="ExpiryDateYear">
        <OPTION VALUE="" >--Year--</OPTION>
		<?php for($i=date('Y');$i<=(date('Y')+20);$i++) { ?>
			 <OPTION VALUE="<?php echo substr($i, 2); ?>" <?php if(isset($data['ExpiryDateYear']) && $i == $data['ExpiryDateYear']) { echo "SELECTED";   } ?>><?php echo $i; ?></OPTION>
		<?php } ?>
       
      </SELECT>
    </td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="left" valign="middle" id="message-here" >&nbsp;</td>
  </tr>
</table>
<p>
<input name="Submit" type="button" value="Submit &gt;&gt;" id="btn-submit-form" >
&nbsp;
<input type="button"  onclick="close_sagepay_popup();" value="Close"  />
</p>
</form>
<script>
jQuery( document ).ready(function() {
	jQuery("#btn-submit-form").click(function(event) {
		loading();
		event.preventDefault();
		data = jQuery( "#frm-sage-pay" ).serialize(); 
		data += "&Amount=<?php echo $Amount; ?>";
		jQuery.when(send_data("<?php echo SAGEPAY_PLUGIN_URL.'/sage-pay-ajax-form-submit.php'; ?>", data)).done(function(output){	
			if(output == 'success')
			{
				window.location.href="<?php echo SAGEPAY_PLUGIN_URL.'/payment-complete.php'; ?>";
			}
			else if(output == '3dAuth')
			{
				window.location.href="<?php echo SAGEPAY_PLUGIN_URL.'/3dsecure.php'; ?>";
			}
			else
			{
			  jQuery('#message-here').html(output);
			}
		});	
	});
	jQuery("#BillingFirstnames").focus();
});
</script>