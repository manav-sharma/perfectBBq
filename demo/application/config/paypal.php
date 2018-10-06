<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
 * Sandbox / Test Mode
 * -------------------------
 * TRUE means you'll be hitting PayPal's sandbox/test servers.  FALSE means you'll be hitting the live servers.
 */
$config['Sandbox'] = TRUE;

$config['CurrencyCode'] = 'USD';
$config['currency_symbol'] = '$';

/* 
 * PayPal API Version
 * ------------------
 * The library is currently using PayPal API version 98.0.  
 * You may adjust this value here and then pass it into the PayPal object when you create it within your scripts to override if necessary.
 */
$config['APIVersion'] = '98.0';


$config['APIUsername'] = $config['Sandbox'] ? 'rajni-facilitator_api1.webworldexpertsindia.com' : 'PRODUCTION_USERNAME_GOES_HERE';
$config['APIPassword'] = $config['Sandbox'] ? '1406092188' : 'PRODUCTION_PASSWORD_GOES_HERE';
$config['APISignature'] = $config['Sandbox'] ? 'AiPC9BjkCyDFQXbSkoZcgqH3hpacAUwXDjY1j4PXXQi90988owea5iI4' : 'PRODUCTION_SIGNATURE_GOES_HERE';


$config['ApplicationID'] = $config['Sandbox'] ? 'APP-80W284485P519543T' : 'PRODUCTION_APP_ID_GOES_HERE';


/* End of file paypal.php */
/* Location: /application/config/paypal.php */
