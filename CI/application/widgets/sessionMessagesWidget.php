<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SessionMessages extends Widget
{
    function run()
    {
        $data = null;

        if ($flashMessage = $this->session->flashdata('info'))
        {
            $data = array(
                'class'   => 'info',
                'message' => $flashMessage
            );
        }
        else if ($flashMessage = $this->session->flashdata('warning'))
        {
            $data = array(
                'class'   => 'info',
                'message' => $flashMessage
            );
        }
        else if ($flashMessage = $this->session->flashdata('success'))
        {
            $data = array(
                'class'   => 'success',
                'message' => $flashMessage
            );
        }
        else if ($flashMessage = $this->session->flashdata('error'))
        {
            $data = array(
                'class'   => 'error',
                'message' => $flashMessage
            );
        }

        if ($data)
            $this->render('messages', $data);
    }
}