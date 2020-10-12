<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class paymentSelect extends Widget
{
    function run()
    {
        $ci = get_instance();
        $userData = $ci->ion_auth->user()->row();

        $ci->load->model('payment_method_model', 'PaymentMethod');

        $paymentMethods = $ci->PaymentMethod->enabled()->getList($userData->id);
        $balances       = $ci->PaymentMethod->getUserBalances($userData->id);
        $fees           = $ci->PaymentMethod->getFees('deposit');

        $this->render('payment_select', compact('userData', 'paymentMethods', 'balances', 'fees'));
    }
}