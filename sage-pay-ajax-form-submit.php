<?php
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	die('Not valid request');
}
require_once('../../../wp-load.php');
require_once('../../../wp-config.php'); 
require_once('Validator.php'); 

$obj_sage = new SagePayGateway();
$obj_sage->process_sagepay();
?>