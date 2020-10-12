<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class RMSListloader {
    function load($class, $options = NULL) {
        require_once($class.'.php');
        $classname = $class;
        if (is_null($options)) {
            return new $classname();
        } else {
            return new $classname($options);
        }
    }
}

?>