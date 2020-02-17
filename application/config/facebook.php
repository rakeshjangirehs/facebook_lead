<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Facebook API Configuration
| -------------------------------------------------------------------
|
| To get an facebook app details you have to create a Facebook app
| at Facebook developers panel (https://developers.facebook.com)
|
|  facebook_app_id               string   Your Facebook App ID.
|  facebook_app_secret           string   Your Facebook App Secret.
|  facebook_login_redirect_url   string   URL to redirect back to after login. (do not include base URL)
|  facebook_logout_redirect_url  string   URL to redirect back to after logout. (do not include base URL)
|  facebook_login_type           string   Set login type. (web, js, canvas)
|  facebook_permissions          array    Your required permissions.
|  facebook_graph_version        string   Specify Facebook Graph version. Eg v3.2
|  facebook_auth_on_load         boolean  Set to TRUE to check for valid access token on every page load.
*/
$config['facebook_app_id']                = '605982930242451';
$config['facebook_app_secret']            = '816c3d8ea340962e4e93de2e93d4cae4';
$config['facebook_login_redirect_url']    = 'index.php/users';
$config['facebook_logout_redirect_url']   = 'index.php/users/logout';
$config['facebook_login_type']            = 'website';
$config['facebook_permissions']           = array('manage_pages','leads_retrieval');
$config['facebook_graph_version']         = 'v3.2';
$config['facebook_auth_on_load']          = TRUE;

$config['hub_verify_token']               = 'milan';