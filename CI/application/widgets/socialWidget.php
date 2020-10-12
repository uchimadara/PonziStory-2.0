<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class social extends Widget{

    public function run() {
        if(strlen(FACEBOOK_WIDGET_CODE) < 10){
            return FALSE;
        }
        return '';//$this->render('facebook', array('url' => FACEBOOK_WIDGET_CODE));
    }

}
