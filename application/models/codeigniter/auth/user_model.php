<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Models
 * @category User_model
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class User_model extends MI_Model
{
    function __construct()
    {
        parent::__construct();
    }

	function add_engage_user($data)
	{
		try
		{
	        $engage = new JanrainEngage();
			$engage->User_id = NULL;
	        $engage->identifier = $data['profile']['identifier'];
			$engage->provider_name = $data['profile']['providerName'];
			$engage->display_name = (isset($data['profile']['displayName'])) ? $data['profile']['displayName'] : NULL;
			$engage->preferred_username = (isset($data['profile']['preferredUsername'])) ? $data['profile']['preferredUsername'] : NULL;
			$engage->gender = (isset($data['profile']['gender'])) ? $data['profile']['gender'] : NULL;
			$engage->birthday = (isset($data['profile']['birthday'])) ? $data['profile']['birthday'] : NULL;
			$engage->utc_offest = (isset($data['profile']['utcOffset'])) ? $data['profile']['utcOffset'] : NULL;
			$engage->email = (isset($data['profile']['email'])) ? $data['profile']['email'] : NULL;
			$engage->verfied_email = (isset($data['profile']['verifiedEmail'])) ? $data['profile']['verifiedEmail'] : NULL;
			$engage->url = (isset($data['profile']['url'])) ? $data['profile']['url'] : NULL;
			$engage->phone_number = (isset($data['profile']['phoneNumber'])) ? $data['profile']['phoneNumber'] : NULL;
			$engage->photo = (isset($data['profile']['photo'])) ? $data['profile']['photo'] : NULL;
			$engage->save();
			
			if(isset($data['profile']['name']) && is_array($data['profile']['name']))
			{
				$engage_name = new JanrainEngageName();
				$engage_name->Engage_id = $engage->id;
				$engage_name->formatted = (isset($data['profile']['name']['formatted'])) ? $data['profile']['name']['formatted'] : NULL;
				$engage_name->family_name = (isset($data['profile']['name']['familyName'])) ? $data['profile']['name']['familyName'] : NULL;
				$engage_name->given_name = (isset($data['profile']['name']['givenName'])) ? $data['profile']['name']['givenName'] : NULL;
				$engage_name->middle_name = (isset($data['profile']['name']['middleName'])) ? $data['profile']['name']['middleName'] : NULL;
				$engage_name->honorific_prefix = (isset($data['profile']['name']['honorificPrefix'])) ? $data['profile']['name']['honorificPrefix'] : NULL;
				$engage_name->honorific_suffix = (isset($data['profile']['name']['honorificSuffix'])) ? $data['profile']['name']['honorificSuffix'] : NULL;
				$engage_name->save();
			}
			
			if(isset($data['profile']['address']) && is_array($data['profile']['address']))
			{
				$engage_address = new JanrainEngageAddress();
				$engage_address->Engage_id = $engage->id;
				$engage_address->formatted = (isset($data['profile']['address']['formatted'])) ? $data['profile']['address']['formatted'] : NULL;
				$engage_address->street_address = (isset($data['profile']['address']['streetAddress'])) ? $data['profile']['address']['streetAddress'] : NULL;
				$engage_address->locality = (isset($data['profile']['address']['locality'])) ? $data['profile']['address']['locality'] : NULL;
				$engage_address->region = (isset($data['profile']['address']['region'])) ? $data['profile']['address']['region'] : NULL;
				$engage_address->postal_code = (isset($data['profile']['address']['postalCode'])) ? $data['profile']['address']['postalCode'] : NULL;
				$engage_address->country = (isset($data['profile']['address']['country'])) ? $data['profile']['address']['country'] : NULL;
				$engage_address->save();
			}

	        return TRUE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return NULL;
		}
	}
	
	function connect_engage_user($identifier, $user_id)
	{
		try
		{
			$engage = Doctrine::getTable('JanrainEngage')->findOneByIdentifier($identifier);
			$engage->User_id = $user_id;
			$engage->save();

	        return TRUE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return NULL;
		}
	}
	
	function get_engage_user($identifier)
	{
		try
		{
			$user = Doctrine_Query::create()
				->from('JanrainEngage e')
				->where("e.identifier = '{$identifier}'")
				->andWhere("e.User_id IS NOT NULL")
				->leftJoin('e.User u')
				->limit(1)
				->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

	        if (count($user) === 1)
	        {
	            return $user[0];
	        }

	        return NULL;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return NULL;
		}
	}

    /**
     * Get user record by Id
     *
     * @param	int
     * @param	bool
     * @return	object
     */
    public function get_user_by_id($user_id, $activated)
    {
		try
		{
	        $q = Doctrine_Query::create()
				->from('User u')
				->select('u.*')
				->where("u.id = {$user_id}")
				->andWhere('u.activated = ?', $activated)
				->execute();

	        if ($q->count() == 1)
	        {
	            return $q[0];
	        }

	        return NULL;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return NULL;
		}
    }

    /**
     * Get user record by login (username or email)
     *
     * @param	string
     * @return	object
     */
    public function get_user_by_login($login)
    {
        try
		{
			$q = Doctrine_Query::create()
				->from('User u')
				->select('u.*')
				->where("LOWER(u.username) = '".strtolower($login)."'")
				->orWhere("LOWER(u.email) = '".strtolower($login)."'")
				->execute();

	        if ($q->count() === 1)
	        {
	            return $q[0];
	        }

	        return NULL;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return NULL;
		}
    }

    /**
     * Get user record by username
     *
     * @param	string
     * @return	object
     */
    public function get_user_by_username($username)
    {
        try
		{
			if ($user = Doctrine::getTable('User')->findOneByUsername("'{$username}'"))
        	{
            	return $user;
        	}

        	return NULL;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return NULL;
		}
    }

    /**
     * Get user record by email
     *
     * @param	string
     * @return	object
     */
    public function get_user_by_email($email)
    {
        try
		{
			if ($user = Doctrine::getTable('User')->findOneByEmail("'{$email}'"))
	        {
	            return $user;
	        }

	        return NULL;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return NULL;
		}
    }

    /**
     * Check if username is available for registering
     *
     * @param	string
     * @return	bool
     */
    public function is_username_available($username)
    {
        try
		{
			$q = Doctrine_Query::create()
	                ->from('User u')
	                ->select('u.id')
	                ->where("LOWER(u.username) = '".strtolower($username)."'")
	                ->execute();

	        if ($q->count() === 1)
	        {
	            return FALSE;
	        }

	        return TRUE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return TRUE;
		}
    }

    /**
     * Check if email available for registering
     *
     * @param	string
     * @return	bool
     */
    public function is_email_available($email)
    {
        try
		{
			$q = Doctrine_Query::create()
	                ->from('User u')
	                ->select('u.id')
	                ->where("LOWER(u.email) = '".strtolower($email)."'")
	                ->execute();

	        if ($q->count() === 1)
	        {
	            return FALSE;
	        }

	        return TRUE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return TRUE;
		}
    }

    /**
     * Create new user record
     *
     * @param	array
     * @param	bool
     * @return	array
     */
    public function create_user($data, $activated = TRUE)
    {
        try
		{
			$data['activated'] = $activated
	            ? 1
	            : 0;

	        $user = new User();
			$user->activated = 0;
	        $user->username = $data['username'];
	        $user->password = $data['password'];
	        $user->email = $data['email'];
	        $user->role = $data['role'];
			$user->display_name = $data['display_name'];

	        if (isset($data['new_email_key']))
	        {
	            $user->new_email_key = $data['new_email_key'];
	        }

	        $user->save();

	        return array('user_id' => $user->id);

	        return NULL;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return NULL;
		}
    }

    /**
     * Activate user if activation key is valid.
     * Can be called for not activated users only.
     *
     * @param	int
     * @param	string
     * @param	bool
     * @return	bool
     */
    function activate_user($user_id, $activation_key, $activate_by_email)
    {
        try
		{
			if ($activate_by_email)
	        {
	            $q = Doctrine_Query::create()
	                    ->from('User u')
	                    ->select('u.id')
	                    ->where("u.id = {$user_id}")
	                    ->andWhere("u.new_email_key = '{$activation_key}'")
	                    ->andWhere('u.activated = 0');
	        }
	        else
	        {
	            $q = Doctrine_Query::create()
	                    ->from('User u')
	                    ->select('u.id')
	                    ->where("u.id = {$user_id}")
	                    ->andWhere("u.new_password_key = '{$activation_key}'")
	                    ->andWhere('u.activated = 0');
	        }

	        $q->execute();
	        if ($q->count() === 1)
	        {
	            if ($user = Doctrine::getTable('User')->findOneById($user_id))
	            {
	                $user->activated = 1;
	                $user->new_email_key = NULL;
	                $user->save();

	                return TRUE;
	            }
	        }
	
	        return FALSE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return FALSE;
		}
    }

    /**
     * Purge table of non-activated users
     *
     * @param	int
     * @return	void
     */
    public function purge_na($expire_period = 172800)
    {
        try
		{
			Doctrine_Query::create()
				->delete('User u')
				->where('u.activated = 0')
				->andWhere('UNIX_TIMESTAMP(u.created_at) < ?', time() - $expire_period)
				->execute();
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return NULL;
		}
    }

    /**
     * Delete user record
     *
     * @param	int
     * @return	bool
     */
    public function delete_user($user_id)
    {
        try
		{
			if ($this->delete_profile($user_id) > 0)
	        {
	            $q = Doctrine_Query::create()
	                    ->delete('User u')
	                    ->where("u.id = {$user_id}")
	                    ->execute();

	            return TRUE;
	        }

	        return FALSE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return FALSE;
		}
    }

    /**
     * Set new password key for user.
     * This key can be used for authentication when resetting user's password.
     *
     * @param	int
     * @param	string
     * @return	bool
     */
    public function set_password_key($user_id, $new_pass_key)
    {
        try
		{
			if ($user = Doctrine::getTable('User')->findOneById($user_id))
	        {
	            $user->new_password_key = $new_pass_key;
	            $user->new_password_requested = date('Y-m-d H:i:s');
	            $user->save();

	            return TRUE;
	        }

	        return FALSE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return FALSE;
		}
    }

    /**
     * Check if given password key is valid and user is authenticated.
     *
     * @param	int
     * @param	string
     * @param	int
     * @return	void
     */
    public function can_reset_password($user_id, $new_pass_key, $expire_period = 900)
    {
        try
		{
			$q = Doctrine_Query::create()
	                ->from('User u')
	                ->select('u.id')
	                ->where("u.id = {$user_id}")
	                ->andWhere("u.new_password_key = '{$new_pass_key}'")
	                ->andWhere('UNIX_TIMESTAMP(u.new_password_requested) > ?', (time() - $expire_period))
	                ->execute();

	        if ($q->count() === 1)
	        {
	            return TRUE;
	        }

	        return FALSE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return FALSE;
		}
    }

    /**
     * Change user password if password key is valid and user is authenticated.
     *
     * @param	int
     * @param	string
     * @param	string
     * @param	int
     * @return	bool
     */
    public function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900)
    {
        try
		{
			$q = Doctrine_Query::create()
	                ->from('User u')
	                ->select('u.id')
	                ->where("u.id = {$user_id}")
	                ->andWhere("u.new_password_key = '{$new_pass_key}'")
	                ->andWhere('UNIX_TIMESTAMP(u.new_password_requested) >= ?', time() - $expire_period)
	                ->execute();

	        if ($q->count() === 1)
	        {
	            $user = $q[0];
	            $user->password = $new_pass;
	            $user->new_password_key = NULL;
	            $user->new_password_requested = NULL;
	            $user->save();

	            return TRUE;
	        }

	        return FALSE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return FALSE;
		}
    }

    /**
     * Change user password
     *
     * @param	int
     * @param	string
     * @return	bool
     */
    public function change_password($user_id, $new_pass)
    {
        try
		{
			if ($user = Doctrine::getTable('User')->findOneById($user_id))
	        {
	            $user->password = $new_pass;
	            $user->save();

	            return TRUE;
	        }

	        return FALSE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return FALSE;
		}
    }

    /**
     * Set new email for user (may be activated or not).
     * The new email cannot be used for login or notification before it is activated.
     *
     * @param	int
     * @param	string
     * @param	string
     * @param	bool
     * @return	bool
     */
    public function set_new_email($user_id, $new_email, $new_email_key, $activated)
    {
		try
		{
	        if ($user = Doctrine::getTable('User')->findOneById($user_id))
	        {
	            if ($user->activated === 1)
	            {
	                $user->email = $new_email;
	            }
	            else
	            {
	                $user->new_email = $new_email;
	            }

	            $user->new_email_key = $new_email_key;
	            $user->save();

	            return TRUE;
	        }

	        return FALSE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return FALSE;
		}
    }

    /**
     * Activate new email (replace old email with new one) if activation key is valid.
     *
     * @param	int
     * @param	string
     * @return	bool
     */
    public function activate_new_email($user_id, $new_email_key)
    {
        try
		{
			$q = Doctrine_Query::create()
	                ->from('User u')
	                ->select('u.id')
	                ->where("u.id = {$user_id}")
					->andWhere("u.new_email_key = '{$new_email_key}'")
	                ->execute();

	        if ($q->count() === 1)
	        {
	            $user = $q[0];
	            $user->email = $user->new_email;
	            $user->new_email = NULL;
	            $user->new_email_key = NULL;
	            $user->save();

	            return TRUE;
	        }
	
	        return FALSE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return FALSE;
		}
    }

    /**
     * Update user login info, such as IP-address or login time, and
     * clear previously generated (but not activated) passwords.
     *
     * @param	int
     * @param	bool
     * @param	bool
     * @return	void
     */
    public function update_login_info($user_id, $record_ip, $record_time)
    {
        try
		{
			$user = Doctrine::getTable('User')->findOneById($user_id);
	        $user->new_password_key = NULL;
	        $user->new_password_requested = NULL;
	        $user->save();
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return NULL;
		}
    }

    /**
     * Ban user
     *
     * @param	int
     * @param	string
     * @return	void
     */
    public function ban_user($user_id, $reason = NULL)
    {
        try
		{
			$user = Doctrine::getTable('User')->findOneById($user_id);
	        $user->banned = 1;
	        $user->ban_reason = $reason;
	        $user->save();
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return NULL;
		}
    }

    /**
     * Unban user
     *
     * @param	int
     * @return	void
     */
    public function unban_user($user_id)
    {
        try
		{
			$user = Doctrine::getTable('User')->findOneById($user_id);
	        $user->banned = 0;
	        $user->ban_reason = NULL;
	        $user->save();
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return NULL;
		}
    }

    /**
     * Delete user profile
     *
     * @param	int
     * @return	void
     */
    private function delete_profile($user_id)
    {
        try
		{
			Doctrine_Query::create()
	            ->delete('Autologin')
	            ->where("user_id = {$user_id}")
	            ->execute();
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return NULL;
		}
		
		try
		{
	        Doctrine_Query::create()
	            ->delete('UserProfile')
	            ->where("user_id = {$user_id}")
	            ->execute();

	        return TRUE;
		}
		catch (Doctrine_Connection_Exception $e)
		{
			log_message('error', $e->getMessage());
			return FALSE;
		}
    }
}

/* End of file user_model.php */
/* Location: ./application/models/codeigniter/auth/user_model.php */