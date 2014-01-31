<?php
require_once('../../../wp-load.php');
require_once('../../../wp-config.php'); 
$data['MD'] = $_POST['MD'];
$data['PaRes'] = $_POST['PARes'];
$secure = new SecureAuth($data);
$obj_sage = new SagePayGateway();
if($secure->status == 'success') {    
	$obj_sage->process_completed();	
} else {
	$obj_sage->process_incompleted($secure->error);	
}
?>