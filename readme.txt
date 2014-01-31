===  Wordpress Custom SagePay Payment Gateway ===
Contributors: pankajpragma
Tags: sage, sagepay, sage gateway, payment, wordpress sagepay, sage gateway, sage payment, sagepay api
Requires at least: 3.0.0
Tested up to: 3.8.1
Donate link: http://www.pragmasoftwares.com/donate/
Stable tag: sage, sagepay, sage gateway, payment, wordpress sagepay, sage gateway, sage payment, sagepay api
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integrate Sage Pay payment gateway in your any wordpress application. It support 3d secure direct payment method. Some coding is needed.

== Description ==
SOME CODING required. Integrate Sage pay payment gateway with your wordpress site, 3d secure direct payment method in your wordpress portal.

Easy steps to Go:

* Add below code where you want to show Sage payment 
`<?php
$obj_sagepay = new SagePayGateway(); 
echo $obj_sagepay->sagepay_button($parameters); 
?>`

* Specify required parameter like amount, description etc.. You can add your own data to access at after successfully payment.
`<?php
$parameters = Array(
'amount' => '100',
'description' => 'New order from your online store',
'var1' => 'value'
);
?>`

* See below variable in class in file "sage-pay.php" & change it according to your needs:
`<?php
define(SAGEPAY_ENV, 'DEVELOPMENT'); # Environment LIVE, DEVELOPMENT
define(SAGEPAY_TYPE, 'PAYMENT');  // Transaction type
define(SAGEPAY_PROTOCOL_VERSION, '2.22'); // SagePay protocol vers no
define(SAGEPAY_VENDOR, 'testvendor');  // Your SagePay vendor name
define(SAGEPAY_CURRENCY, 'GBP');  // currency USD, GBP
?>`
* Vendor name must be yours; you need to register here for sage pay test account https://test.sagepay.com/mysagepay/

* You need to register your server IP address in your Test and Live sage pay account.

* In "sage-pay.php", you will see function called "process_completed()", Write you own logic here you want to execute after successfully payment. You will receive here transaction id & data you sent in first step. All data available in session.

= Extended functionality =
* 3d secure and direct payment method access.
* Support Card type "Visa", "MasterCard", "Visa Debit" & "Visa Electron"
* Multiple currency handle 
* Test account in Test mode using variable SAGEPAY_ENV


== Installation ==
1. Unzip sage-pay.zip
2. Upload `sage-pay` folder to the `/wp-content/plugins/` directory
3. Activate the plugin "Wordpress Sage Pay" through the 'Plugins' menu in WordPress


== How to Update plugin ==
1. Get the latest Wordpress Sage Pay zip file from wordpress site
2. Unpack the zip file that you downloaded.
	
Now you can update plugin using one of following methods:

A) Deactivate old Wordpress Sage Pay plugins from admin panel, delete it and upload new plugin.

B) Login to Ftp and go to /wp-content/plugins/ . Take backup of `sage-pay` plugin and then Delete `sage-pay` folder from ftp and upload folder of latest downloaded plugin.
 	


== Screenshots ==
1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png

== Changelog ==

= 1.0.0 =
* It is beta version

== Upgrade Notice ==

= 1.0.0 =
 * It is beta version