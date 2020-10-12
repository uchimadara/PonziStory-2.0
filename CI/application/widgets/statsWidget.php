<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class stats extends Widget{

    public function run($view = '') {

        $this->load->model('user_model', 'User');

        list($total, $upgrades) = $this->User->countActiveMembers();

        $data= array(
            'Total Members' => $total,
            'Total Upgraded'=> $this->User->countUpgradedMembers()
        );

        $data += $upgrades;
        $members     = $this->User->countMembers();


        if ($view == 'member') {
//            return "    Total Members: ".number_format($data['Total Upgraded']);
            return "    Total Members: ".number_format($members);
        } elseif ($view == 'admin') {

            $this->load->library('table');

            $template = array(
                'table_open' => '<table class="table fs14">'
            );

            $this->table->set_template($template);

            foreach ($data as $stat => $value) {
                $this->table->add_row($stat, $value);
            }

            return $this->table->generate();
        }
    }

}
