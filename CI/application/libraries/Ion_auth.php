<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth
*
* Author: Ben Edmunds
*		  ben.edmunds@gmail.com
*         @benedmunds
*
* Added Awesomeness: Phil Sturgeon
*
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  10.01.2009
*
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
*
* Requirements: PHP5 or above
*
*/

class Ion_auth
{
	/**
	 * CodeIgniter global
	 *
	 * @var string
	 **/
	protected $ci;

	/**
	 * account status ('not_activated', etc ...)
	 *
	 * @var string
	 **/
	protected $status;

	/**
	 * extra where
	 *
	 * @var array
	 **/
	public $_extra_where = array();

	/**
	 * extra set
	 *
	 * @var array
	 **/
	public $_extra_set = array();

	/**
	 * __construct
	 *
	 * @return void
	 * @author Ben
	 **/
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->config('ion_auth', TRUE);
		$this->ci->load->library('session');
		$this->ci->lang->load('ion_auth');
		$this->ci->load->model('ion_auth_model');
		$this->ci->load->helper('cookie');
        $this->ci->load->model('email_model', 'EmailQueue');

		//auto-login the user if they are remembered
		if (!$this->logged_in() && get_cookie('identity') && get_cookie('remember_code'))
		{
			$this->ci->ion_auth = $this;
			$this->ci->ion_auth_model->login_remembered_user();
		}

		$this->ci->ion_auth_model->trigger_events('library_constructor');
	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias'
	 *
	 **/
	public function __call($method, $arguments)
	{
		if (!method_exists( $this->ci->ion_auth_model, $method) )
		{
			throw new Exception('Undefined method Ion_auth::' . $method . '() called');
		}
		return call_user_func_array( array($this->ci->ion_auth_model, $method), $arguments);
	}


	/**
	 * forgotten password feature
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function forgotten_password($identity)    //changed $email to $identity
	{
		if ( $this->ci->ion_auth_model->forgotten_password($identity) )   //changed
		{
			// Get user information
			$user = $this->where($this->ci->config->item('identity', 'ion_auth'), $identity)->users()->row();  //changed to get_user_by_identity from email

			$data = array(
				'identity'		          => $user->{$this->ci->config->item('identity', 'ion_auth')},
                'username'                => $user->username,
				'forgotten_password_code' => $user->forgotten_password_code
			);

            $this->ci->EmailQueue->store($user->email, 'Password Reset', 'emails/auth/' . $this->ci->config->item('email_forgot_password', 'ion_auth'), $data, 10);

            $this->set_message('forgot_password_successful');
            return TRUE;
		}
		else
		{
			$this->set_error('forgot_password_unsuccessful');
			return FALSE;
		}
	}

	/**
	 * forgotten_password_complete
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function forgotten_password_complete($code)
	{
		$this->ci->ion_auth_model->trigger_events('pre_password_change');

		$identity = $this->ci->config->item('identity', 'ion_auth');
		$profile  = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

		if (!is_object($profile))
		{
			$this->ci->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$new_password = $this->ci->ion_auth_model->forgotten_password_complete($code, $profile->salt);

		if ($new_password)
		{
			$data = array(
				'identity'     => $profile->{$identity},
                'username'     => $profile->username,
				'new_password' => $new_password
			);

            $this->ci->EmailQueue->store($profile->email, 'New Password', 'emails/auth/' . $this->ci->config->item('email_forgot_password_complete', 'ion_auth'), $data, 10);

            $this->set_message('password_change_successful');
            $this->ci->ion_auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
            return TRUE;
  		}

		$this->ci->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
		return FALSE;
	}

	/**
	 * register
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function register($username, $password, $email, $additional_data = array(), $group_name = array()) //need to test email activation
	{
		$this->ci->ion_auth_model->trigger_events('pre_account_creation');

		$email_activation = $this->ci->config->item('email_activation', 'ion_auth');

		if (!$email_activation)
		{
			$id = $this->ci->ion_auth_model->register($username, $password, $email, $additional_data, $group_name);
			if ($id !== FALSE)
			{
				$this->set_message('account_creation_successful');
				$this->ci->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful'));
				return $id;
			}
			else
			{
				$this->set_error('account_creation_unsuccessful');
				$this->ci->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
				return FALSE;
			}
		}
		else
		{
			$id = $this->ci->ion_auth_model->register($username, $password, $email, $additional_data, $group_name);

            if (!$id || !$this->activation_email($id))
            {
                $this->set_error('account_creation_unsuccessful');
                $this->ci->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
                return FALSE;
            }

            $this->ci->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful'));

            return $id;
		}
	}

    /**
     * activation_email
     *
     * @return void
     * @author Alex
     **/
    public function activation_email($id)
    {
        $this->ci->ion_auth_model->trigger_events('pre_activation_email');

        $user = $this->ci->ion_auth_model->user($id)->row();
        if (!$user)
        {
            $this->set_error('activation_email_unsuccessful');
            $this->ci->ion_auth_model->trigger_events(array('post_activation_email', 'activation_email_unsuccessful'));
            return FALSE;
        }

        $deactivate = $this->ci->ion_auth_model->deactivate($id);

        if (!$deactivate)
        {
            $this->set_error('deactivate_unsuccessful');
            $this->ci->ion_auth_model->trigger_events(array('post_activation_email', 'activation_email_unsuccessful'));
            return FALSE;
        }

        $activation_code = $this->ci->ion_auth_model->activation_code;
        $identity        = $this->ci->config->item('identity', 'ion_auth');

        $data = array(
            'identity'   => $user->{$identity},
            'username'   => $user->username,
            'id'         => $user->id,
            'email'      => $user->email,
            'activation' => $activation_code,
        );

        $this->ci->EmailQueue->store($user->email, 'Account Activation', 'emails/auth/' . $this->ci->config->item('email_activate', 'ion_auth'), $data, 10); // Mark it as important!

        $this->ci->ion_auth_model->trigger_events(array('post_activation_email', 'activation_email_successful'));
        $this->set_message('activation_email_successful');

        return TRUE;
    }

	/**
	 * logout
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function logout()
	{
		$this->ci->ion_auth_model->trigger_events('logout');

		$identity = $this->ci->config->item('identity', 'ion_auth');
		$this->ci->session->unset_userdata($identity);
		$this->ci->session->unset_userdata('id');
		$this->ci->session->unset_userdata('user_id');

		//delete the remember me cookies if they exist
		if (get_cookie('identity'))
		{
			delete_cookie('identity');
		}
		if (get_cookie('remember_code'))
		{
			delete_cookie('remember_code');
		}

		$this->ci->session->sess_destroy();

		$this->set_message('logout_successful');
		return TRUE;
	}

	/**
	 * logged_in
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function logged_in()
	{
		$this->ci->ion_auth_model->trigger_events('logged_in');

		$identity = $this->ci->config->item('identity', 'ion_auth');

		return (bool) $this->ci->session->userdata($identity);
	}

	/**
	 * is_admin
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function is_admin()
	{
		$this->ci->ion_auth_model->trigger_events('is_admin');

		$admin_group = $this->ci->config->item('admin_group', 'ion_auth');

		return $this->in_group($admin_group);
	}


    public function is_support()
    {
        $this->ci->ion_auth_model->trigger_events('is_support');

        $support_group = $this->ci->config->item('support_group', 'ion_auth');

        return $this->in_group($support_group);
    }

    /**
	 * in_group
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 **/
	public function in_group($check_group, $id=false)
	{
		$this->ci->ion_auth_model->trigger_events('in_group');

		$users_groups = $this->ci->ion_auth_model->get_users_groups($id)->result();
		$groups = array();
		foreach ($users_groups as $group)
		{
			$groups[] = $group->name;
		}

		if (is_array($check_group))
		{
			foreach($check_group as $key => $value)
			{
				if (in_array($value, $groups))
				{
					return TRUE;
				}
			}
		}
		else
		{
			if (in_array($check_group, $groups))
			{
				return TRUE;
			}
		}

		return FALSE;
	}

}
