<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


	$config['appId']   = '230238010761588';
	$config['secret']  = '651a83b6cd450d89d5afd94fe311043e';

/*$config['appId']   = '540679022735765';
	$config['secret']  = '39e0cde3c313ac29dffadd9e42fd3e2f';*/
	
$config['facebook_app_id']              = '230238010761588';
$config['facebook_app_secret']          = '651a83b6cd450d89d5afd94fe311043e';
$config['facebook_login_type']          = 'web';
$config['facebook_login_redirect_url']  = 'user/flogin';
$config['facebook_logout_redirect_url'] = 'user/flogout';
$config['facebook_permissions']         = array('public_profile', 'publish_actions', 'email');
$config['facebook_graph_version']       = 'v2.6';
$config['facebook_auth_on_load']        = TRUE;
