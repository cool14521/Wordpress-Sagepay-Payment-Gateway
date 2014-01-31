<?php
/*
 Plugin Name: Wordpress Custom SagePay Payment Gateway
 Plugin URI: http://www.pragmasoftwares.com
 Version: 1.0
 Author: Pankaj D.
 Description: Integrate Sage Pay payment gateway in your any wordpress application. It support 3d secure direct payment method. Some coding is needed.
 Author URI: http://profiles.wordpress.org/pankajpragma
 */
# Plugin Version
if ( !defined( 'SAGEPAY_VERSION' ) )	define( 'SAGEPAY_VERSION', '1.0.0' ); 
#sage-pay/index.php
if ( !defined( 'SAGEPAY_PLUGIN_BASENAME' ) ) define( 'SAGEPAY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );		
if ( !defined( 'SAGEPAY_PLUGIN_NAME' ) ) define( 'SAGEPAY_PLUGIN_NAME', trim( dirname( SAGEPAY_PLUGIN_BASENAME ), '/' ) ); 		
//Sagepay
if ( !defined( 'SAGEPAY_PLUGIN_DIR' ) ) define( 'SAGEPAY_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . SAGEPAY_PLUGIN_NAME );
//	var/www/wp-content/plugins/sage-pay
if ( !defined( 'SAGEPAY_PLUGIN_URL' ) )	define( 'SAGEPAY_PLUGIN_URL', WP_PLUGIN_URL . '/' . SAGEPAY_PLUGIN_NAME ); 
# Plugin Name
if ( !defined( 'SAGEPAY_TITLE' ) )	define( 'SAGEPAY_TITLE', 'Sage Pay' );

define(SAGEPAY_ENV, 'DEVELOPMENT'); # Environment LIVE, DEVELOPMENT
define(SAGEPAY_TYPE, 'PAYMENT');  // Transaction type
define(SAGEPAY_PROTOCOL_VERSION, '2.22'); // SagePay protocol vers no
define(SAGEPAY_VENDOR, 'michaelstrophie');  // Your SagePay vendor name
define(SAGEPAY_CURRENCY, 'GBP');  // currency USD, GBP
      

require('sagepay.class.php');
wp_enqueue_script( 'my_sagepay_script', SAGEPAY_PLUGIN_URL.'/js/script.js', array( 'jquery' ));
wp_enqueue_style( 'my_sagepay_styles', SAGEPAY_PLUGIN_URL.'/css/style.css');

class SagePayGateway {

    private $_html = '';

    public function __construct() {
      
    }

    public function sagepay_button($params = Array()) {
		$_SESSION['SAGEPAY_DATA'] = $params;
		$_html = '<div class="sage-pay-container" >';
		$_html .= '<a class="load-sage-pay" href="'.SAGEPAY_PLUGIN_URL.'/sage-pay-ajax-form.php"  data-value="" > <img src="'.SAGEPAY_PLUGIN_URL.'/img/sage_pay_secure4.png" style="width: 100px;" /></a>
		     <div id="toPopup1" class="toPopup" style="min-width: 550px;" >
				<div class="close"></div>			 
				 <!--your content start-->
				<div id="popup_content">
					 Loading....
				</div>
				<!--your content end-->
			</div>
			<!--toPopup end-->
			<div class="loader"></div>
			<div id="backgroundPopup"></div>
			</div>
		';
    	return $_html;
    }
	
	public function sagepay_form()
	{
		$data = Array();
		if(SAGEPAY_ENV == 'DEVELOPMENT')
		{
			$data['CardHolder'] = 'Vuxanere Jemizoyi';
			$data['CardNumber'] = '4929000000006';
			$data['StartDateMonth'] = '01';
			$data['StartDateYear'] = '05';
			$data['ExpiryDateMonth'] = date('m');
			$data['ExpiryDateYear'] = date('Y')+2;
			$data['CardType'] = 'VISA';
			$data['IssueNumber'] = '';
			$data['CV2'] = '123';
			$data['BillingFirstnames'] = 'Tester';
			$data['BillingSurname'] = 'Testing';
			$data['BillingAddress1'] = '88';
			$data['BillingAddress2'] = '432 Testing Road';
			$data['BillingCity'] = 'Test Town';
			$data['BillingCountry'] = 'GB';
			$data['BillingPostCode'] = '412';
			$data['Amount'] = trim($arr['Amount']);
		}
		return $data;
	}
	public function process_sagepay()
	{
		// pass the card and billing data to a static method in the
		// sagepay class to be formatted and returned.
		$data = SagePay::formatRawData($_POST);
		$validator = new Validator($data);
		$validator->filledIn("BillingFirstnames");
		$validator->filledIn("BillingSurname");
		$validator->filledIn("BillingAddress1");
		$validator->filledIn("BillingCity");
		$validator->filledIn("BillingCountry");
		$validator->filledIn("CardType");
		$validator->filledIn("CardNumber");
		$validator->filledIn("CV2");
		$validator->filledIn("ExpiryDateMonth");
		$validator->filledIn("ExpiryDateYear");
		$validator->filledIn("Amount");
		$errors = $validator->getErrors();
		$id = $validator->getId();
		$error_message = Array(
		'BillingFirstnames' => 'First name can not be left blank',
		'BillingSurname' => 'Last name can not be left blank',
		'BillingAddress1' => 'Address can not be left blank',
		'BillingCity' => 'City can not be left blank',
		'BillingCountry' => 'Country can not be left blank',
		'Amount' => 'Amount can not be left blank',
		'CardNumber' => 'Card number can not be left blank',
		'ExpiryDateMonth' => 'Expiry month can not be left blank',
		'ExpiryDateYear' => 'Expiry year can not be left blank',
		'CardType' => 'Card type can not be left blank',
		'CV2' => 'CV2 can not be left blank',
		);

		if(!empty($errors)) {
		echo "Error:<br>";
		foreach($errors as $key => $value) {
		echo $error_message[$key]."<br>";
		} 
		exit;
		}

		$description = isset($_SESSION['SAGEPAY_DATA']['description'])?$_SESSION['SAGEPAY_DATA']['description']:'';
		if(!empty($description))
		$data['description'] = $description;

		// instantiate the SagePay object, passing it this formatted data.
		$payment = new SagePay($data);
		// execute the payment request

		$payment->execute();

		if($payment->status == '3dAuth') {
			  // SagePay has returned a request for 3DSecure authentication
			  // returned by SagePay on request for 3DSecure authentication
			  $_SESSION['payment']['acsurl'] = $payment->acsurl;
			  // returned by SagePay on request for 3DSecure authentication
			  $_SESSION['payment']['pareq'] = $payment->pareq;
			  // Store the transaction code that you set for passing to 3DSecure
			  $_SESSION['payment']['vendorTxCode'] = $payment->vendorTxCode;
			  // returned by SagePay on request for 3DSecure authentication
			  $_SESSION['payment']['md'] = $payment->md;
			  // set a flag so your code knows to load the 3D Secure page.
			  $secure_auth = true;
			  echo "3dAuth";
			  exit;
		} else if($payment->status == 'success') {
			  // Transaction successful. Redirect to your complete page
			  echo "success";
			  exit;
		} else {			 
			 echo $_SESSION['error'] = $payment->error;  
		}
	}

	public function process_3d_secure_verification()
	{
		$_html .= '<html><head><title>3D Secure Verification</title></head>';
		$_html .= '<body>';
		$_html .= '<form name="form" action="'.$_SESSION['payment']['acsurl'].'" method="POST">
			<input type="hidden" name="PaReq" value="'.$_SESSION['payment']['pareq'].'" />
			<input type="hidden" name="TermUrl" value="'.SAGEPAY_PLUGIN_URL . '/3dcallback.php?VendorTxCode=' . $_SESSION['payment']['vendorTxCode'].'"  />
			<input type="hidden" name="MD" value="'.$_SESSION['payment']['md'].'"  />
			<input type="submit" value="Proceed to 3D secure authentication" />
		  </form>
		  <script type="text/javascript">document.form.submit();</script>
		</body>
		</html>';

		return $_html;
	}

	public function process_3d_callback()
	{
		$data = array();
		$data['MD'] = $_POST['MD'];
		$data['PAReq'] = $_POST['PaRes'];
		$data['PaRes'] = $_POST['PaRes'];
		$data['VendorTxCode'] = $_GET['VendorTxCode'];
		$_SESSION['mdx'] = $_POST['MDX'];

		$_html .= '<html>';
		$_html .= '<head>
		  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		  <link rel="stylesheet" type="text/css" href="images/directKitStyle.css">
		  <title>3D-Secure Redirect</title>
		  <script language="Javascript"> function OnLoadEvent() { document.form.submit(); } </script>
		</head>';

		$_html .= '<body OnLoad="OnLoadEvent();">
		  <form name="form" action="'.SAGEPAY_PLUGIN_URL.'/3dcomplete.php" method="POST"/>
			<input type="hidden" name="PARes" value="'.$data['PaRes'].'"/>
			<input type="hidden" name="MD" value="'.$data['MD'].'"/>
			Redirecting.......
			<noscript>
			  <center><p>Please click button below to Authorise your card</p><input type="submit" value="Go"/></p></center>
			</noscript>
		  </form>
		</body>
		</html>';

		return $_html;
	}
	
	public function process_completed()
	{
		# Access default data here $_SESSION['SAGEPAY_DATA']
		$VPSTxId = $_SESSION['transaction']['VPSTxId'];
		$TxAuthNo = $_SESSION['transaction']['TxAuthNo'];
		$Sagepay_data = $_SESSION['SAGEPAY_DATA'];

		# Perform here your operation after successful payment
	  
		
	}

	function process_incompleted($error)
	{
		die($error);
	}
	
	  public static function countries() {
                return array(
                        'AF' => 'Afghanistan',
                        'AX' => 'Åland Islands',
                        'AL' => 'Albania',
                        'DZ' => 'Algeria',
                        'AS' => 'American Samoa',
                        'AD' => 'Andorra',
                        'AO' => 'Angola',
                        'AI' => 'Anguilla',
                        'AQ' => 'Antarctica',
                        'AG' => 'Antigua and Barbuda',
                        'AR' => 'Argentina',
                        'AM' => 'Armenia',
                        'AW' => 'Aruba',
                        'AU' => 'Australia',
                        'AT' => 'Austria',
                        'AZ' => 'Azerbaijan',
                        'BS' => 'Bahamas',
                        'BH' => 'Bahrain',
                        'BD' => 'Bangladesh',
                        'BB' => 'Barbados',
                        'BY' => 'Belarus',
                        'BE' => 'Belgium',
                        'BZ' => 'Belize',
                        'BJ' => 'Benin',
                        'BM' => 'Bermuda',
                        'BT' => 'Bhutan',
                        'BO' => 'Bolivia',
                        'BA' => 'Bosnia and Herzegovina',
                        'BW' => 'Botswana',
                        'BV' => 'Bouvet Island',
                        'BR' => 'Brazil',
                        'IO' => 'British Indian Ocean Territory',
                        'BN' => 'Brunei Darussalam',
                        'BG' => 'Bulgaria',
                        'BF' => 'Burkina Faso',
                        'BI' => 'Burundi',
                        'KH' => 'Cambodia',
                        'CM' => 'Cameroon',
                        'CA' => 'Canada',
                        'CV' => 'Cape Verde',
                        'KY' => 'Cayman Islands',
                        'CF' => 'Central African Republic',
                        'TD' => 'Chad',
                        'CL' => 'Chile',
                        'CN' => 'China',
                        'CX' => 'Christmas Island',
                        'CC' => 'Cocos (Keeling) Islands',
                        'CO' => 'Colombia',
                        'KM' => 'Comoros',
                        'CG' => 'Congo',
                        'CD' => 'Congo, the Democratic Republic of the',
                        'CK' => 'Cook Islands',
                        'CR' => 'Costa Rica',
                        'HR' => 'Croatia',
                        'CU' => 'Cuba',
                        'CY' => 'Cyprus',
                        'CZ' => 'Czech Republic',
                        'CI' => 'Côte d\'Ivoire',
                        'DK' => 'Denmark',
                        'DJ' => 'Djibouti',
                        'DM' => 'Dominica',
                        'DO' => 'Dominican Republic',
                        'EC' => 'Ecuador',
                        'EG' => 'Egypt',
                        'SV' => 'El Salvador',
                        'GQ' => 'Equatorial Guinea',
                        'ER' => 'Eritrea',
                        'EE' => 'Estonia',
                        'ET' => 'Ethiopia',
                        'FK' => 'Falkland Islands (Malvinas)',
                        'FO' => 'Faroe Islands',
                        'FJ' => 'Fiji',
                        'FI' => 'Finland',
                        'FR' => 'France',
                        'GF' => 'French Guiana',
                        'PF' => 'French Polynesia',
                        'TF' => 'French Southern Territories',
                        'GA' => 'Gabon',
                        'GM' => 'Gambia',
                        'GE' => 'Georgia',
                        'DE' => 'Germany',
                        'GH' => 'Ghana',
                        'GI' => 'Gibraltar',
                        'GR' => 'Greece',
                        'GL' => 'Greenland',
                        'GD' => 'Grenada',
                        'GP' => 'Guadeloupe',
                        'GU' => 'Guam',
                        'GT' => 'Guatemala',
                        'GG' => 'Guernsey',
                        'GN' => 'Guinea',
                        'GW' => 'Guinea-Bissau',
                        'GY' => 'Guyana',
                        'HT' => 'Haiti',
                        'HM' => 'Heard Island and McDonald Islands',
                        'VA' => 'Holy See (Vatican City State)',
                        'HN' => 'Honduras',
                        'HK' => 'Hong Kong',
                        'HU' => 'Hungary',
                        'IS' => 'Iceland',
                        'IN' => 'India',
                        'ID' => 'Indonesia',
                        'IR' => 'Iran, Islamic Republic of',
                        'IQ' => 'Iraq',
                        'IE' => 'Ireland',
                        'IM' => 'Isle of Man',
                        'IL' => 'Israel',
                        'IT' => 'Italy',
                        'JM' => 'Jamaica',
                        'JP' => 'Japan',
                        'JE' => 'Jersey',
                        'JO' => 'Jordan',
                        'KZ' => 'Kazakhstan',
                        'KE' => 'Kenya',
                        'KI' => 'Kiribati',
                        'KP' => 'Korea, Democratic People\'s Republic of',
                        'KR' => 'Korea, Republic of',
                        'KW' => 'Kuwait',
                        'KG' => 'Kyrgyzstan',
                        'LA' => 'Lao People\'s Democratic Republic',
                        'LV' => 'Latvia',
                        'LB' => 'Lebanon',
                        'LS' => 'Lesotho',
                        'LR' => 'Liberia',
                        'LY' => 'Libyan Arab Jamahiriya',
                        'LI' => 'Liechtenstein',
                        'LT' => 'Lithuania',
                        'LU' => 'Luxembourg',
                        'MO' => 'Macao',
                        'MK' => 'Macedonia, the former Yugoslav Republic of',
                        'MG' => 'Madagascar',
                        'MW' => 'Malawi',
                        'MY' => 'Malaysia',
                        'MV' => 'Maldives',
                        'ML' => 'Mali',
                        'MT' => 'Malta',
                        'MH' => 'Marshall Islands',
                        'MQ' => 'Martinique',
                        'MR' => 'Mauritania',
                        'MU' => 'Mauritius',
                        'YT' => 'Mayotte',
                        'MX' => 'Mexico',
                        'FM' => 'Micronesia, Federated States of',
                        'MD' => 'Moldova, Republic of',
                        'MC' => 'Monaco',
                        'MN' => 'Mongolia',
                        'ME' => 'Montenegro',
                        'MS' => 'Montserrat',
                        'MA' => 'Morocco',
                        'MZ' => 'Mozambique',
                        'MM' => 'Myanmar',
                        'NA' => 'Namibia',
                        'NR' => 'Nauru',
                        'NP' => 'Nepal',
                        'NL' => 'Netherlands',
                        'AN' => 'Netherlands Antilles',
                        'NC' => 'New Caledonia',
                        'NZ' => 'New Zealand',
                        'NI' => 'Nicaragua',
                        'NE' => 'Niger',
                        'NG' => 'Nigeria',
                        'NU' => 'Niue',
                        'NF' => 'Norfolk Island',
                        'MP' => 'Northern Mariana Islands',
                        'NO' => 'Norway',
                        'OM' => 'Oman',
                        'PK' => 'Pakistan',
                        'PW' => 'Palau',
                        'PS' => 'Palestinian Territory, Occupied',
                        'PA' => 'Panama',
                        'PG' => 'Papua New Guinea',
                        'PY' => 'Paraguay',
                        'PE' => 'Peru',
                        'PH' => 'Philippines',
                        'PN' => 'Pitcairn',
                        'PL' => 'Poland',
                        'PT' => 'Portugal',
                        'PR' => 'Puerto Rico',
                        'QA' => 'Qatar',
                        'RE' => 'Reunion ﻿Réunion',
                        'RO' => 'Romania',
                        'RU' => 'Russian Federation',
                        'RW' => 'Rwanda',
                        'BL' => 'Saint Barthélemy',
                        'SH' => 'Saint Helena',
                        'KN' => 'Saint Kitts and Nevis',
                        'LC' => 'Saint Lucia',
                        'MF' => 'Saint Martin (French part)',
                        'PM' => 'Saint Pierre and Miquelon',
                        'VC' => 'Saint Vincent and the Grenadines',
                        'WS' => 'Samoa',
                        'SM' => 'San Marino',
                        'ST' => 'Sao Tome and Principe',
                        'SA' => 'Saudi Arabia',
                        'SN' => 'Senegal',
                        'RS' => 'Serbia',
                        'SC' => 'Seychelles',
                        'SL' => 'Sierra Leone',
                        'SG' => 'Singapore',
                        'SK' => 'Slovakia',
                        'SI' => 'Slovenia',
                        'SB' => 'Solomon Islands',
                        'SO' => 'Somalia',
                        'ZA' => 'South Africa',
                        'GS' => 'South Georgia and the South Sandwich Islands',
                        'ES' => 'Spain',
                        'LK' => 'Sri Lanka',
                        'SD' => 'Sudan',
                        'SR' => 'Suriname',
                        'SJ' => 'Svalbard and Jan Mayen',
                        'SZ' => 'Swaziland',
                        'SE' => 'Sweden',
                        'CH' => 'Switzerland',
                        'SY' => 'Syrian Arab Republic',
                        'TW' => 'Taiwan',
                        'TJ' => 'Tajikistan',
                        'TZ' => 'Tanzania, United Republic of',
                        'TH' => 'Thailand',
                        'TL' => 'Timor-Leste',
                        'TG' => 'Togo',
                        'TK' => 'Tokelau',
                        'TO' => 'Tonga',
                        'TT' => 'Trinidad and Tobago',
                        'TN' => 'Tunisia',
                        'TR' => 'Turkey',
                        'TM' => 'Turkmenistan',
                        'TC' => 'Turks and Caicos Islands',
                        'TV' => 'Tuvalu',
                        'UG' => 'Uganda',
                        'UA' => 'Ukraine',
                        'AE' => 'United Arab Emirates',
                        'GB' => 'United Kingdom',
                        'US' => 'United States',
                        'UM' => 'United States Minor Outlying Islands',
                        'UY' => 'Uruguay',
                        'UZ' => 'Uzbekistan',
                        'VU' => 'Vanuatu',
                        'VE' => 'Venezuela, Bolivarian Republic of',
                        'VN' => 'Viet Nam',
                        'VG' => 'Virgin Islands, British',
                        'VI' => 'Virgin Islands, U.S.',
                        'WF' => 'Wallis and Futuna',
                        'EH' => 'Western Sahara',
                        'YE' => 'Yemen',
                        'ZM' => 'Zambia',
                        'ZW' => 'Zimbabwe'
                );
        }
}