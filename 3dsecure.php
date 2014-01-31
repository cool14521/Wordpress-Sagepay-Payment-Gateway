<?php
require_once('../../../wp-load.php');
require_once('../../../wp-config.php'); 
$obj_sage = new SagePayGateway();
echo $obj_sage->process_3d_secure_verification();
?>