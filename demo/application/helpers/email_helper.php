<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * getNotificationTheme()
 * 
 * @param mixed $message
 * @param mixed $subject
 * @param string $filePath
 * @return
 */
function getNotificationTheme($emailTitle, $subject, $message) {
	// Call Codeigniter Instance
	$CI = & get_instance();
	$baseURL = $CI->config->item('base_url');
	$siteName = $CI->config->item('siteName');
	
	//Notification themem file path.
	$filePath = str_replace("\\", "/",dirname(dirname(dirname(__FILE__))))."/application/views/frontend/includes/notification.html";

	//Get HTML contents of theme file.
	$fileContents = file_get_contents($filePath);

	//Search with this patterns
	$searchPatterns[0] = '<<!--currentdate-->>';
	$searchPatterns[1] = '<<!--subject-->>';
	$searchPatterns[2] = '<<!--contents-->>';
	$searchPatterns[3] = '<<!--baseURL-->>';
	$searchPatterns[4] = '<<!--siteName-->>';
	$searchPatterns[5] = '<<!--emailTitle-->>';
	$searchPatterns[6] = '<<!--contactURL-->>';

	//Replace with this values
	$replaceBy[0] = date(FRONTEND_DATE_FORMAT);
	$replaceBy[1] = $subject;
	$replaceBy[2] = $message;
	$replaceBy[3] = $baseURL;
	$replaceBy[4] = $siteName;
	$replaceBy[5] = $emailTitle;
	$replaceBy[6] = $baseURL.'contactus';

	//Return the theme processed contents.
	return preg_replace($searchPatterns, $replaceBy, $fileContents);
}
