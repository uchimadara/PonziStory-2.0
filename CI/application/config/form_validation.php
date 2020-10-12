<?php
$config = array(
    'user/login'                     => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required'
        ),
//        array(
//            'field' => 'sum',
//            'label' => 'Sum',
//            'rules' => 'trim|required|callback_valid_sum'
//        )

    ),
    'user/register'                  => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|xss_clean|min_length[2]|max_length[12]|callback_user_check'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|min_length[6]|max_length[20]'
        ),
        array(
            'field' => 'passconf',
            'label' => 'Password Confirmation',
            'rules' => 'required|matches[password]'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|xss_clean|callback_email_check'
        ),
        array(
            'field' => 'phone',
            'label' => 'Phone number',
            'rules' => 'trim|required|xss_clean|numeric'
        ),
//        array(
//            'field' => 'secret_question',
//            'label' => 'Secret Question',
//            'rules' => 'trim|required|xss_clean'
//        ),
//        array(
//            'field' => 'secret_answer',
//            'label' => 'Secret Answer',
//            'rules' => 'trim|required|xss_clean'
//        ),
//        array(
//            'field' => 'sum',
//            'label' => 'Sum',
//            'rules' => 'trim|required|callback_valid_sum'
//        )
    ),
    'user/forgot_password'           => array(
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|xss_clean'
        ),
//        array(
//            'field' => 'sum',
//            'label' => 'Sum',
//            'rules' => 'trim|required|callback_valid_sum'
//        )
    ),
    'support'                        => array(
        array(
            'field' => 'email',
            'label' => 'email',
            'rules' => 'trim|required|xss_clean|max_length[100]|valid_email'
        ),
        array(
            'field' => 'subject',
            'label' => 'Subject',
            'rules' => 'trim|prep_for_form|required|xss_clean|max_length[100]'
        ),
        array(
            'field' => 'message',
            'label' => 'Message',
            'rules' => 'trim|prep_for_form|required|xss_clean|max_length[3000]'
        )
    ),

    'member_support'                 => array(
        array(
            'field' => 'subject',
            'label' => 'Subject',
            'rules' => 'trim|prep_for_form|required|xss_clean|max_length[100]'
        ),
        array(
            'field' => 'message',
            'label' => 'Message',
            'rules' => 'trim|required|xss_clean|max_length[3000]'
        )
    ),

    'admin_support'                  => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|prep_for_form|required|xss_clean|max_length[100]'
        ),
        array(
            'field' => 'subject',
            'label' => 'Subject',
            'rules' => 'trim|prep_for_form|required|xss_clean|max_length[100]'
        ),
        array(
            'field' => 'message',
            'label' => 'Message',
            'rules' => 'trim|required|xss_clean|max_length[3000]'
        )
    ),

    'support_reply'                  => array(
        array(
            'field' => 'message',
            'label' => 'Message',
            'rules' => 'trim|required|xss_clean|max_length[3000]'
        )
    ),

    'admin/user_update'              => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|min_length[2]|max_length[12]|xss_clean|callback_user_check'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|min_length[5]|valid_email|xss_clean|callback_email_check'
        ),
        array(
            'field' => 'password',
            'label' => 'New Password',
            'rules' => 'trim|xss_clean|min_length[6]|max_length[20]'
        ),
        array(
            'field' => 'day',
            'label' => 'Date of Birth',
            'rules' => 'trim|required|callback_valid_date'
        ),
        array(
            'field' => 'month',
            'label' => 'Date of Birth',
            'rules' => 'trim|required|callback_valid_date'
        ),
        array(
            'field' => 'year',
            'label' => 'Date of Birth',
            'rules' => 'trim|required|callback_valid_date'
        )
    ),

    'admin/mass_email'               => array(
        array(
            'field' => 'subject',
            'label' => 'Subject',
            'rules' => 'trim|required|min_length[3]|xss_clean'
        ),
        array(
            'field' => 'message',
            'label' => 'Message',
            'rules' => 'trim|required|min_length[3]|xss_clean'
        )
    ),
    'admin/email_user' => array(
        array(
            'field' => 'subject',
            'label' => 'Subject',
            'rules' => 'trim|required|min_length[3]|xss_clean'
        ),
        array(
            'field' => 'message',
            'label' => 'Message',
            'rules' => 'trim|required|min_length[3]|xss_clean'
        )
    ),

    'user/change_password'           => array(
        array(
            'field' => 'oldpass',
            'label' => 'Password',
            'rules' => 'required|xss_clean'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|xss_clean|min_length[6]|matches[passconf]'
        ),
        array(
            'field' => 'passconf',
            'label' => 'Password',
            'rules' => 'required|xss_clean'
        )
    ),
    'user/change_secret' => array(
        array(
            'field' => 'passwd',
            'label' => 'Password',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'secret_question',
            'label' => 'Secret Question',
            'rules' => 'trim|required|xss_clean|'
        ),
        array(
            'field' => 'secret_answer',
            'label' => 'Secret Answer',
            'rules' => 'trim|required|xss_clean'
        )
    ),
    'user/change_email'              => array(
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|xss_clean|valid_email|min_length[6]|max_length[150]'
        ),
          array(
              'field' => 'secret_answer',
              'label' => 'Secret Answer',
              'rules' => 'trim|required|callback_valid_secret'
          ),
    ),
    'user/change_country'            => array(
        array(
            'field' => 'country',
            'label' => 'Country',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'secret_answer',
            'label' => 'Secret Answer',
            'rules' => 'trim|required|callback_valid_secret'
        ),
    ),
    'user/change_phone'            => array(
        array(
            'field' => 'phone',
            'label' => 'Phone',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'secret_answer',
            'label' => 'Secret Answer',
            'rules' => 'trim|required|callback_valid_secret'
        ),
    ),
    'user/change_names'            => array(
        array(
            'field' => 'first_name',
            'label' => 'First Name',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last Name',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'secret_answer',
            'label' => 'Secret Answer',
            'rules' => 'trim|required|callback_valid_secret'
        ),
    ),
    'user/change_address'            => array(
        array(
            'field' => 'address',
            'label' => 'Address',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'city',
            'label' => 'City',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'state',
            'label' => 'Region / State',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'postal_code',
            'label' => 'Postcode / Zip',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'secret_answer',
            'label' => 'Secret Answer',
            'rules' => 'trim|required|callback_valid_secret'
        ),
    ),

    // payment proof
    'payment_proof' => array(
        array(
            'field' => 'method_id',
            'label' => 'Account',
            'rules' => 'trim|required|xss_clean|'
        ),
        array(
            'field' => 'from_account',
            'label' => 'Your account',
            'rules' => 'trim|required|xss_clean|'
        ),
        array(
            'field' => 'transaction_id',
            'label' => 'Transaction ID',
            'rules' => 'trim|required|xss_clean|'
        ),
        array(
            'field' => 'amount',
            'label' => 'Amount USD',
            'rules' => 'trim|required|xss_clean|decimal'
        ),
        array(
            'field' => 'currency',
            'label' => 'Currency',
            'rules' => 'trim|required|xss_clean|'
        ),
        array(
            'field' => 'currency_amount',
            'label' => 'Currency Amount',
            'rules' => 'trim|xss_clean|callback_currency_check'
        ),
        array(
            'field' => 'details',
            'label' => 'Transaction Details',
            'rules' => 'trim|required|xss_clean|'
        ),
    ),

    'btc_payment_proof' => array(
//        array(
//            'field' => 'amount',
//            'label' => 'Amount',
//            'rules' => 'trim|required|xss_clean|is_numeric'
//        ),
//        array(
//            'field' => 'transaction_id',
//            'label' => 'Transaction ID',
//            'rules' => 'trim|required|xss_clean|callback_tx_dup_check'
//        ),
        array(
            'field' => 'file_name',
            'label' => 'Proof of Payment',
            'rules' => 'xss_clean'
        ),
    ),

    // Payment Methods
    'ap_account'                     => array(
        array(
            'field' => 'account',
            'label' => 'Account',
            'rules' => 'trim|required|xss_clean|valid_email|callback_ap_account_check'
        ),
        array(
            'field' => 'confirm_account',
            'label' => 'Account Confirmation',
            'rules' => 'trim|required|xss_clean|matches[account]'
        ),
        array(
            'field' => 'secret_answer',
            'label' => 'Secret Answer',
            'rules' => 'trim|required|callback_valid_secret'
        ),
    ),

    'pp_account'                     => array(
        array(
            'field' => 'account',
            'label' => 'Account',
            'rules' => 'trim|required|xss_clean|valid_email|callback_pp_account_check'
        ),
        array(
            'field' => 'confirm_account',
            'label' => 'Account Confirmation',
            'rules' => 'trim|required|xss_clean|matches[account]'
        ),
        array(
            'field' => 'secret_answer',
            'label' => 'Secret Answer',
            'rules' => 'trim|required|callback_valid_secret'
        ),
    ),

    'st_account'                     => array(
        array(
            'field' => 'account',
            'label' => 'Account',
            'rules' => 'trim|required|xss_clean|callback_st_account_check'
        ),
        array(
            'field' => 'confirm_account',
            'label' => 'Account Confirmation',
            'rules' => 'trim|required|xss_clean|matches[account]'
        ),
        array(
            'field' => 'secret_answer',
            'label' => 'Secret Answer',
            'rules' => 'trim|required|callback_valid_secret'
        ),

    ),

    'pm_account'                     => array(
        array(
            'field' => 'account',
            'label' => 'Account',
            'rules' => 'trim|required|xss_clean|callback_pm_account_check'
        ),
        array(
            'field' => 'confirm_account',
            'label' => 'Account Confirmation',
            'rules' => 'trim|required|xss_clean|matches[account]'
        ),
        array(
            'field' => 'secret_answer',
            'label' => 'Secret Answer',
            'rules' => 'trim|required|callback_valid_secret'
        ),
    ),

    'admin_ap_account'               => array(
        array(
            'field' => 'account',
            'label' => 'Account',
            'rules' => 'trim|required|xss_clean|valid_email|callback_ap_account_check'
        )
    ),

    'admin_st_account'               => array(
        array(
            'field' => 'account',
            'label' => 'Account',
            'rules' => 'trim|required|xss_clean|callback_st_account_check'
        )
    ),

    'admin_pm_account'               => array(
        array(
            'field' => 'account',
            'label' => 'Account',
            'rules' => 'trim|required|xss_clean|callback_pm_account_check'
        )
    ),

    'admin_wu_account'               => array(
        array(
            'field' => 'first_name',
            'label' => 'First Name',
            'rules' => 'trim|required|xss_clean|'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last Name',
            'rules' => 'trim|required|xss_clean|'
        ),
        array(
            'field' => 'city',
            'label' => 'City',
            'rules' => 'trim|xss_clean'
        ),
        array(
            'field' => 'country',
            'label' => 'Country',
            'rules' => 'trim|xss_clean'
        )
    ),

    'admin_bw_account'               => array(
        array(
            'field' => 'bank_name',
            'label' => 'Bank Name',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'bank_address',
            'label' => 'Bank Adress',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'bank_city',
            'label' => 'Bank City',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'bank_country',
            'label' => 'Bank Country',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'fullname',
            'label' => 'Your Full Name',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'address',
            'label' => 'Your Address',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'city',
            'label' => 'Your City',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'country',
            'label' => 'Your Country',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'account_number',
            'label' => 'Account Number',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'bic_swift',
            'label' => 'BIC / SWIFT',
            'rules' => 'trim|xss_clean'
        ),
        array(
            'field' => 'iban',
            'label' => 'IBAN',
            'rules' => 'trim|xss_clean'
        ),
        array(
            'field' => 'info',
            'label' => 'Other Information',
            'rules' => 'trim|xss_clean'
        ),
    ),

    'wu_account'                     => array(
        array(
            'field' => 'first_name',
            'label' => 'First Name',
            'rules' => 'trim|required|xss_clean|'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last Name',
            'rules' => 'trim|required|xss_clean|'
        ),
        array(
            'field' => 'city',
            'label' => 'City',
            'rules' => 'trim|xss_clean'
        ),
        array(
            'field' => 'country',
            'label' => 'Country',
            'rules' => 'trim|xss_clean'
        ),
        array(
            'field' => 'secret_answer',
            'label' => 'Secret Answer',
            'rules' => 'trim|required|callback_valid_secret'
        ),
    ),

    'bw_account'                     => array(
        array(
            'field' => 'bank_name',
            'label' => 'Bank Name',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'bank_address',
            'label' => 'Bank Adress',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'bank_city',
            'label' => 'Bank City',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'bank_country',
            'label' => 'Bank Country',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'fullname',
            'label' => 'Your Full Name',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'address',
            'label' => 'Your Address',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'city',
            'label' => 'Your City',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'country',
            'label' => 'Your Country',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'account_number',
            'label' => 'Account Number',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'bic_swift',
            'label' => 'BIC / SWIFT',
            'rules' => 'trim|xss_clean'
        ),
        array(
            'field' => 'iban',
            'label' => 'IBAN',
            'rules' => 'trim|xss_clean'
        ),
        array(
            'field' => 'info',
            'label' => 'Other Information',
            'rules' => 'trim|xss_clean'
        ),
        array(
            'field' => 'secret_answer',
            'label' => 'Secret Answer',
            'rules' => 'trim|required|callback_valid_secret'
        ),
    ),

    'cashier_settings'               => array(
        array(
            'field' => 'percent',
            'label' => 'Percent',
            'rules' => 'trim|required|xss_clean|numeric|'
        ),
        array(
            'field' => 'fixed',
            'label' => 'Fixed',
            'rules' => 'trim|required|xss_clean|numeric'
        ),
        array(
            'field' => 'max',
            'label' => 'Max',
            'rules' => 'trim|xss_clean|numeric'
        )
    ),

    'admin/add_settings'             => array(
        array(
            'field' => 'name',
            'label' => 'Name',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'value',
            'label' => 'Value',
            'rules' => 'trim|required'
        )
    ),

    'admin/add_menu'             => array(
        array(
            'field' => 'name',
            'label' => 'Name',
            'rules' => 'trim|required'
        )
    ),


);