<?php

if (!session_id()) {
    session_start();
}
use Facebook\Facebook;

$fb = new Facebook([
    'app_id' => FACEBOOK_APP_ID,
    'app_secret' => FACEBOOK_APP_SECRET,
    'default_graph_version' => 'v2.5',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email'];
$loginUrl = $helper->getLoginUrl(URL_SITE.'/controller/marketplace/aplicacao/fblogin_callback', $permissions);

header('Location: '.$loginUrl);
