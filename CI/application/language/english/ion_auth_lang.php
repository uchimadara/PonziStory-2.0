<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Lang - English
*
* Author: Ben Edmunds
*           ben.edmunds@gmail.com
*         @benedmunds
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.14.2010
*
* Description:  English language file for Ion Auth messages and errors
*
*/

// Account Creation
$lang['account_creation_successful']         = 'Account Successfully Created';
$lang['account_creation_unsuccessful']       = 'Unable to Create Account';
$lang['account_creation_duplicate_email']    = '* already in use';
$lang['account_creation_duplicate_username'] = '* already in use';

// Password
$lang['password_change_successful']   = 'Password Successfully Changed';
$lang['password_change_unsuccessful'] = 'Unable to Change Password';
$lang['forgot_password_successful']   = 'Your password has been reset, and emailed to you.';
$lang['forgot_password_unsuccessful'] = 'Unable to Reset Password';

// Activation
$lang['activate_successful']           = 'Account Activated';
$lang['activate_unsuccessful']         = 'Unable to Activate Account';
$lang['deactivate_successful']         = 'Account De-Activated';
$lang['deactivate_unsuccessful']       = 'Unable to De-Activate Account';
$lang['activation_email_successful']   = 'Activation Email Sent';
$lang['activation_email_unsuccessful'] = 'Unable to Send Activation Email';

// Login / Logout
$lang['login_successful']                    = 'Logged In Successfully';
$lang['login_unsuccessful']                  = 'Your Username or Password is incorrect';
$lang['login_deleted']                  = 'Your Account Was Deleted';
$lang['login_unsuccessful_not_active']       = 'Account is inactive (If you have registered before,Check BLACKPAGE for your username)';
$lang['login_unsuccessful_not_active_email'] = 'Account is inactive, please check your email for instructions or click <a class="activation_link containerLink" data-target="activationFrm" href="#">here</a> to receive another activation email.';
$lang['logout_successful']                   = 'Logged Out Successfully';

// Account Changes
$lang['update_successful']   = 'Account Information Successfully Updated';
$lang['update_unsuccessful'] = 'Unable to Update Account Information';
$lang['delete_successful']   = 'User Deleted';
$lang['delete_unsuccessful'] = 'Unable to Delete User';