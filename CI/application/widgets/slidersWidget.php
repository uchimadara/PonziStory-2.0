<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class sliders extends Widget
{
    function run(){
        if ($this->uri->uri_string() == '' AND defined('SLIDER')) {
                $this->render('slider');
        }
    }
}
