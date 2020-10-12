<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists("user_link")) {

    function user_link($username, $account_level, $width = NULL) {

        if ($account_level != 'Free') {
            $img = array('src' => asset('frontend/img/about/' . strtolower($account_level) . '.jpg'));
            if ($width) {
                $img['style'] = '"width:' . $width . '"';
            }
            $username = img($img, FALSE) . ' ' . $username;
        }

        return anchor(user_url($username), $username, '');
    }

}
if (!function_exists("getIp")) {

    function getIp() {
        $ip = !empty($_SERVER['HTTP_CF_CONNECTING_IP']) ? htmlspecialchars((string) $_SERVER['HTTP_CF_CONNECTING_IP']) : FALSE;
        if (!$ip)
            $ip = !empty($_SERVER['HTTP_CLIENT_IP']) ? htmlspecialchars((string) $_SERVER['HTTP_CLIENT_IP']) : FALSE;

        if (!$ip)
            $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? htmlspecialchars((string) $_SERVER['HTTP_X_FORWARDED_FOR']) : FALSE;

        if (!$ip)
            $ip = !empty($_SERVER['REMOTE_ADDR']) ? htmlspecialchars((string) $_SERVER['REMOTE_ADDR']) : '0.0.0.0';

        // Hack because some of the IPs seems to be on the format: xxx.xxx.xxx.xxx, xxx.xxx.xxx.xxx
        $idx = strpos($ip, ',');
        if ($idx !== FALSE)
            $ip = substr($ip, 0, $idx);

        return $ip;
    }

}
