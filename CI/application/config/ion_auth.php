<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Config
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Added Awesomeness: Phil Sturgeon
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  10.01.2009
*
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
*
*/

    /**
     * Database.
     **/
    $config['database'] = 'default'; // must match config/database.php
    /**
	 * Tables.
	 **/
	$config['tables']['groups']          = 'groups';
	$config['tables']['users']           = 'users';
	$config['tables']['users_groups']    = 'users_groups';

	/**
	 * Site Title, example.com
	 */
	$config['site_title']		   =  SITE_NAME;

	/**
	 * Admin Email, admin@example.com
	 */
	$config['admin_email']		   = "superadmin@".SITE_DOMAIN;

    /**
     * Admin Email, admin@example.com
     */
    $config['admin_notify_email'] = "superadmin@".SITE_DOMAIN;
    $config['noreply_email'] = "noreply@".SITE_DOMAIN;

    /**
	 * Default group, use name
	 */
	$config['default_group']       = 'members';

	/**
	 * Default administrators group, use name
	 */
	$config['admin_group']         = array('admin','moderator');

	$config['support_group']         = array('support');
	/**
	 * Users table column and Group table column you want to join WITH.
	 * Joins from users.id
	 * Joins from groups.id
	 **/
	$config['join']['users']       = 'user_id';
	$config['join']['groups']      = 'group_id';

	/**
	 * A database column which is used to
	 * login with.
	 **/
	$config['identity']            = 'username';

	/**
	 * Minimum Required Length of Password
	 **/
	$config['min_password_length'] = 8;

	/**
	 * Maximum Allowed Length of Password
	 **/
	$config['max_password_length'] = 20;

	/**
	 * Email Activation for registration
	 **/
	$config['email_activation']    = FALSE;

	/**
	 * Allow users to be remembered and enable auto-login
	 **/
	$config['remember_users']      = TRUE;

	/**
	 * How long to remember the user (seconds)
	 **/
	$config['user_expire']         = 86500;

	/**
	 * Extend the users cookies everytime they auto-login
	 **/
	$config['user_extend_on_login'] = TRUE;

	/**
	 * Folder where email templates are stored.
     * Default : auth/
	 **/
	$config['email_templates']     = 'emails/auth/';

	/**
	 * activate Account Email Template
     * Default : activate.tpl.php
	 **/
	$config['email_activate']   = 'activate.php';

	/**
	 * Forgot Password Email Template
     * Default : forgot_password.tpl.php
	 **/
	$config['email_forgot_password']   = 'forgot_password.php';

	/**
	 * Forgot Password Complete Email Template
     * Default : new_password.tpl.php
	 **/
	$config['email_forgot_password_complete']   = 'new_password.php';

	/**
	 * Salt Length
	 **/
	$config['salt_length'] = 10;

	/**
	 * Should the salt be stored in the database?
	 * This will change your password encryption algorithm,
	 * default password, 'password', changes to
	 * fbaa5e216d163a02ae630ab1a43372635dd374c0 with default salt.
	 **/
	$config['store_salt'] = TRUE;

	/**
	 * Message Start Delimiter
	 **/
	$config['message_start_delimiter'] = '';

	/**
	 * Message End Delimiter
	 **/
	$config['message_end_delimiter'] = '';

	/**
	 * Error Start Delimiter
	 **/
	$config['error_start_delimiter'] = '';

	/**
	 * Error End Delimiter
	 **/
	$config['error_end_delimiter'] = '';

/* End of file ion_auth.php */
/* Location: ./system/application/config/ion_auth.php */
