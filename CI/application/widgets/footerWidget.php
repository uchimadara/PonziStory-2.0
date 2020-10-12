<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class footer extends Widget
{
    function run()
    {
        $data['guest'] = !$this->ion_auth->logged_in();
       
        $this->render('footer', $data);
    }
}