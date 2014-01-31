<?php
require_once('../../../wp-load.php');
require_once('../../../wp-config.php'); 

$obj_sage = new SagePayGateway();
if(isset($_SESSION['transaction']['VPSTxId'])) {    
	$obj_sage->process_completed();	
} else {
	$obj_sage->process_incompleted();	
}
?>