<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category AccountController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class AccountController extends MI_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('gravatar');
		$this->load->model('codeigniter/auth/user_model');
	}

	public function index()
	{
		if ($this->auth->is_logged_in())
		{
			/**
			 * @var $data Array Merge MI_Controller $this->data with a local array
			 */
			$data = array();
	        $data = array_merge($data, $this->data);

	        /**
			 * Setup Local Variables to Pass to View
			 */
	        $data['title'] = 'Account';
			$data['description'] = '';
			$data['keywords'] = '';
			$data['message'] = $this->session->flashdata('message');

	        /**
			 * Copy View Partials as Local Variables to Pass to Main Views
			 */
	        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);
			$data['account_menu'] = $this->load->view('browser/main/_partials/menu_account', $data, TRUE);
        
	        /**
			 * Load Views
			 */
	        $this->load->view('browser/main/_partials/header', $data);
	        $this->load->view('browser/main/account/index', array('message' => $message));
	        $this->load->view('browser/main/_partials/footer', $data);
		}
		else if( !$this->auth->is_logged_in() && $message = $this->session->flashdata('message'))
		{
			/**
			 * @var $data Array Merge MI_Controller $this->data with a local array
			 */
			$data = array();
	        $data = array_merge($data, $this->data);

	        /**
			 * Setup Local Variables to Pass to View
			 */
	        $data['title'] = 'Account';
			$data['description'] = '';
			$data['keywords'] = '';
			$data['message'] = $this->session->flashdata('message');

	        /**
			 * Copy View Partials as Local Variables to Pass to Main Views
			 */
	        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);
			$data['account_menu'] = $this->load->view('browser/main/_partials/menu_account', $data, TRUE);
        
	        /**
			 * Load Views
			 */
	        $this->load->view('browser/main/_partials/header', $data);
	        $this->load->view('browser/main/account/general_message', array('message' => $message));
	        $this->load->view('browser/main/_partials/footer', $data);
		}
		else
		{
			redirect('/account/login');
		}
	}
	
	public function settings()
	{
		if ($this->auth->is_logged_in())
		{
			/**
			 * @var $data Array Merge MI_Controller $this->data with a local array
			 */
			$data = array();
	        $data = array_merge($data, $this->data);

	        /**
			 * Setup Local Variables to Pass to View
			 */
	        $data['title'] = 'Account Settubgs';
			$data['description'] = '';
			$data['keywords'] = '';
			$data['message'] = $this->session->flashdata('message');

	        /**
			 * Copy View Partials as Local Variables to Pass to Main Views
			 */
	        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);
			$data['account_menu'] = $this->load->view('browser/main/_partials/menu_account', $data, TRUE);
        
	        /**
			 * Load Views
			 */
	        $this->load->view('browser/main/_partials/header', $data);
	        $this->load->view('browser/main/account/settings', array('message' => $message));
	        $this->load->view('browser/main/_partials/footer', $data);
		}
		else
		{
			redirect('/account/login');
		}
	}

	/**
	 * Login user on the site
	 * @return void
	 *
 	 */
	public function login()
	{
		/**
		 * @var $data Array Merge MI_Controller $this->data with a local array
		 */
		$data = array();
        $data = array_merge($data, $this->data);
		
		// logged in
		if ($this->auth->is_logged_in())
		{
			redirect('');
		}
		// logged in, not activated
		elseif ($this->auth->is_logged_in(FALSE))
		{						
			redirect('/account/send_again/');
		}
		else
		{
			$data['login_by_username'] = ($this->config->item('login_by_username') && $this->config->item('use_username'));
			$data['login_by_email'] = $this->config->item('login_by_email');

			$this->form_validation->set_rules('login', 'Login', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('remember', 'Remember me', 'integer');

			// Get login for counting attempts to login
			if ($this->config->item('login_count_attempts') &&	($login = $this->input->post('login')))
			{
				$login = $this->security->xss_clean($login);
			}
			else
			{
				$login = '';
			}

			$data['use_recaptcha'] = $this->config->item('use_recaptcha');
			
			if ($this->auth->is_max_login_attempts_exceeded($login))
			{
				if ($data['use_recaptcha'])
				{
					$this->form_validation->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback__check_recaptcha');
				}
				else
				{
					$this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback__check_captcha');
				}
			}
			
			$data['errors'] = array();
			
			// user is using Janrain Engage Service
			if ($this->input->post('token'))
			{
				$this->engage->token($this->input->post('token'));
				$response = $this->engage->auth_info();
				$engage = $this->user_model->get_engage_user($response['profile']['identifier']);
				
				// janrain user exists, let them in
				if($engage)
				{
					$this->session->set_userdata(array(
						'user_id' => $engage['User']['id'],
						'role' => $engage['User']['role'],
						'username' => $engage['User']['username'],
						'email' => $engage['User']['email'],
						'name' => $engage['User']['display_name'],
						'status' => ($engage['User']['activated'] === 1) ? '1':'0'
					));
					
					// use existing login method to make sure use completed their activation ( they will get taken to home page if they have )
					redirect('/account/login');
				}
				// janrain user does not exist, they need to register first
				else
				{					
					$this->session->set_flashdata('message', 'Your ' . $response['profile']['providerName'] . ' Account is not registered with ESSpree.  Register Account?');
					$this->session->set_flashdata('engage', $response);
					redirect('/account/register');
				}
			}
			else
			{
				// validation ok
				if ($this->form_validation->run())
				{								
					// success
					if ($this->auth->login(	$this->form_validation->set_value('login'), $this->form_validation->set_value('password'), $this->form_validation->set_value('remember'), $data['login_by_username'], $data['login_by_email']))
					{
						redirect('');
					}
					else
					{
						$errors = $this->auth->get_error_message();
					
						// banned user
						if (isset($errors['banned']))
						{
							$this->_show_message($this->lang->line('auth_message_banned').' '.$errors['banned']);
						}
						// not activated user
						elseif (isset($errors['not_activated']))
						{
							redirect('/account/send_again/');
						}
						// fail
						else
						{
							foreach ($errors as $k => $v)
							{
								$data['errors'][$k] = $this->lang->line($v);
							}
						}
					}
				}
			
				$data['show_captcha'] = FALSE;
			
				if ($this->auth->is_max_login_attempts_exceeded($login))
				{
					$data['show_captcha'] = TRUE;
				
					if ($data['use_recaptcha'])
					{
						$data['recaptcha_html'] = $this->_create_recaptcha();
					}
					else
					{
						$data['captcha_html'] = $this->_create_captcha();
					}
				}
			}

	        /**
			 * Setup Local Variables to Pass to View
			 */
	        $data['title'] = 'Account Login';
			$data['description'] = '';
			$data['keywords'] = '';
			$data['message'] = $this->session->flashdata('message');

	        /**
			 * Copy View Partials as Local Variables to Pass to Main Views
			 */
	        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);
			$data['account_menu'] = $this->load->view('browser/main/_partials/menu_account', $data, TRUE);
        
	        /**
			 * Load Views
			 */
	        $this->load->view('browser/main/_partials/header', $data);
	        $this->load->view('browser/main/account/login', $data);
	        $this->load->view('browser/main/_partials/footer', $data);
		}
	}

	/**
	 * Logout user
	 *
	 * @return void
	 */
	public function logout()
	{
		$this->auth->logout();
		$this->_show_message($this->lang->line('auth_message_logged_out'));
	}

	/**
	 * Register user on the site
	 *
	 * @return void
	 */
	public function register()
	{
		/**
		 * @var $data Array Merge MI_Controller $this->data with a local array
		 */
		$data = array();
        $data = array_merge($data, $this->data);

		// logged in
		if ($this->auth->is_logged_in())
		{
			redirect('');
		}
		// logged in, not activated
		elseif ($this->auth->is_logged_in(FALSE))
		{
			redirect('/account/send_again/');
		}
		// registration is off
		elseif (!$this->config->item('allow_registration'))
		{
			$this->_show_message($this->lang->line('auth_message_registration_disabled'));
		}
		else
		{
			// user is using Janrain Engage Service
			$engage = $this->session->flashdata('engage');
			if ($this->input->post('token') || is_array($engage))
			{
				if(is_array($engage))
				{
					$response = $engage;
					$user = $this->user_model->get_engage_user($response['profile']['identifier']);
				}
				else
				{
					$this->engage->token($this->input->post('token'));
					$response = $this->engage->auth_info();
					$user = $this->user_model->get_engage_user($response['profile']['identifier']);
				}
				
				// janrain user already exists, no need to register just log them in
				if($user)
				{
					$this->session->set_flashdata('message', 'You have already registered! Please login with your ' . $response['profile']['providerName'] . ' Account');
					redirect('/account/login');
				}
				// janrain user does not exist, pre-populate registration form
				else
				{
					
					$this->user_model->add_engage_user($response);
					
					$username = (isset($response['profile']['preferredUsername']))
						? $response['profile']['preferredUsername']
						: '';
						
					$first_name = (isset($response['profile']['name']))
						? $response['profile']['name']['givenName']
						: '';
						
					$last_name = (isset($response['profile']['name']))
						? $response['profile']['name']['familyName']
						: '';
						
					$email = (isset($response['profile']['email']))
						? $response['profile']['email']
						: '';
					
					$data['engage_username'] = $username;
					$data['engage_fname'] = $first_name;
					$data['engage_lname'] = $last_name;
					$data['engage_email'] = $email;
					$data['engage_identifier'] = $response['profile']['identifier'];
					$data['engage_access'] = TRUE;
				}
			}
			else
			{
				$use_username = $this->config->item('use_username');
			
				if ($use_username)
				{
					$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length').']|max_length['.$this->config->item('username_max_length').']|alpha_dash');
				}
			
				$this->form_validation->set_rules('fname', 'First Name', 'trim|required|xss_clean|alpha');
				$this->form_validation->set_rules('lname', 'Last Name', 'trim|required|xss_clean|alpha');
				$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
				$this->form_validation->set_rules('role', 'Role', 'trim|required|xss_clean|alpha');
				$this->form_validation->set_rules('engage_identifier', 'Engage Identifier', 'trim|xss_clean');
				$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length').']|max_length['.$this->config->item('password_max_length').']');
				$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');

				$captcha_registration = $this->config->item('captcha_registration');
				$use_recaptcha = $this->config->item('use_recaptcha');
			
				if ($captcha_registration)
				{
					if ($use_recaptcha)
					{
						$this->form_validation->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback__check_recaptcha');
					}
					else
					{
						$this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback__check_captcha');
					}
				}
			
				$data['errors'] = array();

				$email_activation = $this->config->item('email_activation');

				// validation ok
				if ($this->form_validation->run())
				{
					// success
					if ( !is_null($register = $this->auth->create_user($use_username ? $this->form_validation->set_value('username') : '', $this->form_validation->set_value('email'), $this->form_validation->set_value('password'), $email_activation, $this->form_validation->set_value('fname'), $this->form_validation->set_value('lname'), $this->form_validation->set_value('role'))))
					{
						$data['site_name'] = $this->config->item('website_name');
						
						// check if jainrain service should be connected to user
						$engage_identifier = $this->form_validation->set_value('engage_identifier');
						if( !empty($engage_identifier) && !empty($register['user_id']) )
						{
							$this->user_model->connect_engage_user($engage_identifier, $register['user_id']);
						}
					
						// send "activate" email
						if ($email_activation)
						{
							$data['activation_period'] = $this->config->item('email_activation_expire') / 3600;

							$this->_send_email('activate', $register['email'], array_merge($data, $register));
						
							unset($register['password']);

							$this->_show_message($this->lang->line('auth_message_registration_completed_1'));
						}
						else
						{
							// send "welcome" email
							if ($this->config->item('email_account_details'))
							{
								$this->_send_email('welcome', $register['email'], array_merge($data, $register));
							}
						
							unset($register['password']);

							$this->_show_message($this->lang->line('auth_message_registration_completed_2').' '.anchor('/auth/login/', 'Login'));
						}
					}
					else
					{
						$errors = $this->auth->get_error_message();
						foreach ($errors as $k => $v)
						{
							$data['errors'][$k] = $this->lang->line($v);
						}
					}
				}
				if ($captcha_registration)
				{
					if ($use_recaptcha)
					{
						$data['recaptcha_html'] = $this->_create_recaptcha();
					}
					else
					{
						$data['captcha_html'] = $this->_create_captcha();
					}
				}
			}
			
			$data['use_username'] = $use_username;
			$data['captcha_registration'] = $captcha_registration;
			$data['use_recaptcha'] = $use_recaptcha;

	        /**
			 * Setup Local Variables to Pass to View
			 */
	        $data['title'] = 'Register';
			$data['description'] = '';
			$data['keywords'] = '';
			$data['message'] = $this->session->flashdata('message');

	        /**
			 * Copy View Partials as Local Variables to Pass to Main Views
			 */
	        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);
			$data['account_menu'] = $this->load->view('browser/main/_partials/menu_account', $data, TRUE);
        
	        /**
			 * Load Views
			 */
	        $this->load->view('browser/main/_partials/header', $data);
	        $this->load->view('browser/main/account/register', $data);
	        $this->load->view('browser/main/_partials/footer', $data);
		}
	}

	/**
	 * Send activation email again, to the same or new email address
	 *
	 * @return void
	 */
	public function send_again()
	{
		/**
		 * @var $data Array Merge MI_Controller $this->data with a local array
		 */
		$data = array();
        $data = array_merge($data, $this->data);		
		
		// not logged in or activated
		if (!$this->auth->is_logged_in(FALSE))
		{
			redirect('/account/login/');
		}
		else
		{
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$data['errors'] = array();
			
			// validation ok
			if ($this->form_validation->run())
			{
				// success
				if (!is_null($data = $this->auth->change_email($this->form_validation->set_value('email'))))
				{			
					$data['site_name']	= $this->config->item('website_name');
					$data['activation_period'] = $this->config->item('email_activation_expire') / 3600;

					$this->_send_email('activate', $data['email'], $data);
					$this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));
				}
				else
				{
					$errors = $this->auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}

	        /**
			 * Setup Local Variables to Pass to View
			 */
	        $data['title'] = 'Send Again';
			$data['description'] = '';
			$data['keywords'] = '';

	        /**
			 * Copy View Partials as Local Variables to Pass to Main Views
			 */
	        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);
			$data['account_menu'] = $this->load->view('browser/main/_partials/menu_account', $data, TRUE);
        
	        /**
			 * Load Views
			 */
	        $this->load->view('browser/main/_partials/header', $data);
	        $this->load->view('browser/main/account/send_again', $data);
	        $this->load->view('browser/main/_partials/footer', $data);
		}
	}

	/**
	 * Activate user account.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	public function activate()
	{
		$user_id = $this->uri->segment(3);
		$new_email_key = $this->uri->segment(4);

		// success
		if ($this->auth->activate_user($user_id, $new_email_key))
		{
			$this->auth->logout();
			$this->_show_message($this->lang->line('auth_message_activation_completed').' '.anchor('/auth/login/', 'Login'));
		}
		// fail
		else
		{
			$this->_show_message($this->lang->line('auth_message_activation_failed'));
		}
	}

	/**
	 * Generate reset code (to change password) and send it to user
	 *
	 * @return void
	 */
	public function forgot_password()
	{
		/**
		 * @var $data Array Merge MI_Controller $this->data with a local array
		 */
		$data = array();
        $data = array_merge($data, $this->data);		
		
		// logged in
		if ($this->auth->is_logged_in())
		{
			redirect('');
		}
		// logged in, not activated
		elseif ($this->auth->is_logged_in(FALSE))
		{
			redirect('/account/send_again/');
		}
		else
		{
			$this->form_validation->set_rules('login', 'Email or login', 'trim|required|xss_clean');

			$data['errors'] = array();
			
			// validation ok
			if ($this->form_validation->run())
			{
				if (!is_null($validate = $this->auth->forgot_password($this->form_validation->set_value('login'))))
				{
					$data['site_name'] = $this->config->item('website_name');

					// Send email with password activation link
					$this->_send_email('forgot_password', $data['email'], $data);
					$this->_show_message($this->lang->line('auth_message_new_password_sent'));
				}
				else
				{
					$errors = $this->auth->get_error_message();
					foreach ($errors as $k => $v)
					{
						$data['errors'][$k] = $this->lang->line($v);
					}
				}
			}

	        /**
			 * Setup Local Variables to Pass to View
			 */
	        $data['title'] = 'Forgot Password';
			$data['description'] = '';
			$data['keywords'] = '';

	        /**
			 * Copy View Partials as Local Variables to Pass to Main Views
			 */
	        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);
			$data['account_menu'] = $this->load->view('browser/main/_partials/menu_account', $data, TRUE);
        
	        /**
			 * Load Views
			 */
	        $this->load->view('browser/main/_partials/header', $data);
	        $this->load->view('browser/main/account/forgot_password', $data);
	        $this->load->view('browser/main/_partials/footer', $data);
		}
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user).
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	public function reset_password()
	{
		/**
		 * @var $data Array Merge MI_Controller $this->data with a local array
		 */
		$data = array();
        $data = array_merge($data, $this->data);		
		
		$user_id = $this->uri->segment(3);
		$new_pass_key = $this->uri->segment(4);

		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length').']|max_length['.$this->config->item('password_max_length').']|alpha_dash');
		$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

		$data['errors'] = array();

		// validation ok
		if ($this->form_validation->run())
		{
			// success
			if (!is_null($data = $this->auth->reset_password($user_id, $new_pass_key, $this->form_validation->set_value('new_password'))))
			{
				$data['site_name'] = $this->config->item('website_name');

				// Send email with new password
				$this->_send_email('reset_password', $data['email'], $data);
				$this->_show_message($this->lang->line('auth_message_new_password_activated').' '.anchor('/auth/login/', 'Login'));
			}
			// fail
			else
			{
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		}
		else
		{
			// Try to activate user by password key (if not activated yet)
			if ($this->config->item('email_activation'))
			{
				$this->auth->activate_user($user_id, $new_pass_key, FALSE);
			}

			if (!$this->auth->can_reset_password($user_id, $new_pass_key))
			{
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		}

        /**
		 * Setup Local Variables to Pass to View
		 */
        $data['title'] = 'Reset Password';
		$data['description'] = '';
		$data['keywords'] = '';

        /**
		 * Copy View Partials as Local Variables to Pass to Main Views
		 */
        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);
		$data['account_menu'] = $this->load->view('browser/main/_partials/menu_account', $data, TRUE);
       
        /**
		 * Load Views
		 */
        $this->load->view('browser/main/_partials/header', $data);
        $this->load->view('browser/main/account/reset_password', $data);
        $this->load->view('browser/main/_partials/footer', $data);
	}

	/**
	 * Change user password
	 *
	 * @return void
	 */
	public function change_password()
	{
		/**
		 * @var $data Array Merge MI_Controller $this->data with a local array
		 */
		$data = array();
        $data = array_merge($data, $this->data);		
		
		// not logged in or not activated
		if (!$this->auth->is_logged_in())
		{
			redirect('/account/login/');
		}
		else
		{
			$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length').']|max_length['.$this->config->item('password_max_length').']|alpha_dash');
			$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

			$data['errors'] = array();
			
			// validation ok
			if ($this->form_validation->run())
			{
				// success
				if ($this->auth->change_password($this->form_validation->set_value('old_password'),	$this->form_validation->set_value('new_password')))
				{
					$this->_show_message($this->lang->line('auth_message_password_changed'));
				}
				// fail
				else
				{
					$errors = $this->auth->get_error_message();
					foreach ($errors as $k => $v)
					{
						$data['errors'][$k] = $this->lang->line($v);
					}
				}
			}

	        /**
			 * Setup Local Variables to Pass to View
			 */
	        $data['title'] = 'Change Password';
			$data['description'] = '';
			$data['keywords'] = '';

	        /**
			 * Copy View Partials as Local Variables to Pass to Main Views
			 */
	        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);
			$data['account_menu'] = $this->load->view('browser/main/_partials/menu_account', $data, TRUE);
       
	        /**
			 * Load Views
			 */
	        $this->load->view('browser/main/_partials/header', $data);
	        $this->load->view('browser/main/account/change_password', $data);
	        $this->load->view('browser/main/_partials/footer', $data);
		}
	}

	/**
	 * Change user email
	 *
	 * @return void
	 */
	public function change_email()
	{
		/**
		 * @var $data Array Merge MI_Controller $this->data with a local array
		 */
		$data = array();
        $data = array_merge($data, $this->data);		
		
		// not logged in or not activated
		if (!$this->auth->is_logged_in())
		{
			redirect('/account/login/');
		}
		else
		{
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$data['errors'] = array();
			
			// validation ok
			if ($this->form_validation->run())
			{
				// success
				if (!is_null($data = $this->auth->set_new_email($this->form_validation->set_value('email'), $this->form_validation->set_value('password'))))
				{
					$data['site_name'] = $this->config->item('website_name');

					// Send email with new email address and its activation link
					$this->_send_email('change_email', $data['new_email'], $data);
					$this->_show_message(sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']));
				}
				else
				{
					$errors = $this->auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}

	        /**
			 * Setup Local Variables to Pass to View
			 */
	        $data['title'] = 'Change Password';
			$data['description'] = '';
			$data['keywords'] = '';

	        /**
			 * Copy View Partials as Local Variables to Pass to Main Views
			 */
	        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);
			$data['account_menu'] = $this->load->view('browser/main/_partials/menu_account', $data, TRUE);
       
	        /**
			 * Load Views
			 */
	        $this->load->view('browser/main/_partials/header', $data);
	        $this->load->view('browser/main/account/change_email', $data);
	        $this->load->view('browser/main/_partials/footer', $data);
		}
	}

	/**
	 * Replace user email with a new one.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	public function reset_email()
	{
		$user_id = $this->uri->segment(3);
		$new_email_key = $this->uri->segment(4);

		// success
		if ($this->auth->activate_new_email($user_id, $new_email_key))
		{	
			$this->auth->logout();
			$this->_show_message($this->lang->line('auth_message_new_email_activated').' '.anchor('/auth/login/', 'Login'));
		} 
		// fail
		else
		{																
			$this->_show_message($this->lang->line('auth_message_new_email_failed'));
		}
	}

	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @return void
	 */
	public function unregister()
	{
		/**
		 * @var $data Array Merge MI_Controller $this->data with a local array
		 */
		$data = array();
        $data = array_merge($data, $this->data);		
		
		// not logged in or not activated
		if (!$this->auth->is_logged_in())
		{
			redirect('/account/login/');
		}
		else
		{
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

			$data['errors'] = array();

			// validation ok
			if ($this->form_validation->run())
			{
				// success
				if ($this->auth->delete_user($this->form_validation->set_value('password')))
				{
					$this->_show_message($this->lang->line('auth_message_unregistered'));
				}
				// fail
				else
				{
					$errors = $this->auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}

	        /**
			 * Setup Local Variables to Pass to View
			 */
	        $data['title'] = 'Unregister';
			$data['description'] = '';
			$data['keywords'] = '';

	        /**
			 * Copy View Partials as Local Variables to Pass to Main Views
			 */
	        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);
			$data['account_menu'] = $this->load->view('browser/main/_partials/menu_account', $data, TRUE);
       
	        /**
			 * Load Views
			 */
	        $this->load->view('browser/main/_partials/header', $data);
	        $this->load->view('browser/main/account/unregister', $data);
	        $this->load->view('browser/main/_partials/footer', $data);
		}
	}

	function vanilla_auth()
	{
		if(isset($this->session->userdata) && isset($this->session->userdata['user_id']) && isset($this->session->userdata['name']) && isset($this->session->userdata['email']))
		{
			echo "UniqueID={$this->session->userdata['user_id']}\nName={$this->session->userdata['name']}\nEmail={$this->session->userdata['email']}\n";
		}
		
		exit();
	}

	/**
	 * Show info message
	 *
	 * @param	string
	 * @return	void
	 */
	private function _show_message($message)
	{
		$this->session->set_flashdata('message', $message);
		redirect('/account/');
	}

	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	private function _send_email($type, $email, &$data)
	{
		$this->load->library('email');
		$this->email->from($this->config->item('webmaster_email'), $this->config->item('website_name'));
		$this->email->reply_to($this->config->item('webmaster_email'), $this->config->item('website_name'));
		$this->email->to($email);
		$this->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $this->config->item('website_name')));
		$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE));
		$this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, TRUE));
		$this->email->send();
	}

	/**
	 * Create CAPTCHA image to verify user as a human
	 *
	 * @return	string
	 */
	private function _create_captcha()
	{
		$this->load->helper('captcha');

		$cap = create_captcha(array(
			'img_path'		=> './'.$this->config->item('captcha_path'),
			'img_url'		=> base_url().$this->config->item('captcha_path'),
			'font_path'		=> './'.$this->config->item('captcha_fonts_path'),
			'font_size'		=> $this->config->item('captcha_font_size'),
			'img_width'		=> $this->config->item('captcha_width'),
			'img_height'	=> $this->config->item('captcha_height'),
			'show_grid'		=> $this->config->item('captcha_grid'),
			'expiration'	=> $this->config->item('captcha_expire')
		));

		// Save captcha params in session
		$this->session->set_flashdata(array(
			'captcha_word' => $cap['word'],
			'captcha_time' => $cap['time']
		));

		return $cap['image'];
	}

	/**
	 * Callback function. Check if CAPTCHA test is passed.
	 *
	 * @param	string
	 * @return	bool
	 */
	private function _check_captcha($code)
	{
		$time = $this->session->flashdata('captcha_time');
		$word = $this->session->flashdata('captcha_word');

		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);

		if ($now - $time > $this->config->item('captcha_expire'))
		{
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
			return FALSE;
		}
		elseif (($this->config->item('captcha_case_sensitive') && $code != $word) || strtolower($code) != strtolower($word))
		{
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		
		return TRUE;
	}

	/**
	 * Create reCAPTCHA JS and non-JS HTML to verify user as a human
	 *
	 * @return	string
	 */
	private function _create_recaptcha()
	{
		$this->load->helper('recaptcha');

		// Add custom theme so we can get only image
		$options = "<script>var RecaptchaOptions = {theme: '".$this->config->item('recaptcha_theme')."', custom_theme_widget: 'recaptcha_widget'};</script>\n";

		// Get reCAPTCHA JS and non-JS HTML
		$html = recaptcha_get_html($this->config->item('recaptcha_public_key'));

		return $options.$html.'<br />';
	}

	/**
	 * Callback function. Check if reCAPTCHA test is passed.
	 *
	 * @return	bool
	 */
	private function _check_recaptcha()
	{
		$this->load->helper('recaptcha');

		$resp = recaptcha_check_answer($this->config->item('recaptcha_private_key'), $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);

		if (!$resp->is_valid)
		{
			$this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		
		return TRUE;
	}
}

/* End of file AccountController.php */
/* Location: ./application/controllers/AccountController.php */