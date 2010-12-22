<?php
/* 
 * just output the $json var as json.
 */
Configure::write('debug', 0);
if (isset($json['redirect'])) {
    $json['redirect'] = $html->url($json['redirect']);
}
elseif (isset($redirect)) {
    $json['redirect'] = $html->url($redirect);
    $json['success']  = true;
}

if ($session->read("Message")) {
        $json['message'] = ($session->read("Message.flash.message")) ? $session->read("Message.flash.message") : $session->read("Message.auth.message");
        $status = explode('/',$session->read("Message.flash.layout"));
        $json['success'] = ($status[1]=='success') ? true : false;
        if (!isset($json['redirect'])) {
            $json['redirect'] = ($session->read('Auth.redirect')) ? $html->url($session->read('Auth.redirect')) : false;
        }
        if ($json['redirect']) $session->del('Auth.redirect');
        if (!$json['redirect'] || $json['success'])
                $session->del('Message');
}
    
die(json_encode($json));

?>
