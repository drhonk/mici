<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Libraries
 * @category Engage
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 * @version For Janrain API Version 2
 * @link http://documentation.janrain.com/api-request-response-format
 */

/**
 * Begin Document
 */
class Engage
{
	private $_error;
	private $_token;
	private $_api_key;
	private $_post_url;
	private $_token_url_login;
	private $_token_url_register;
	private $_response_data;
	private $_ci;

	/**
	 * Janrain Engage Library Constructor
	 */
	function __construct()
	{		
		// Get CodeIgniter instance
		$this->_ci =& get_instance();

		// Get info from Config File
		$this->_ci->config->load('engage');
		$this->_api_key = $this->_ci->config->item('rpx_api_key');
		$this->_post_url = $this->_ci->config->item('rpx_post_url');
		$this->_token_url_login = $this->_ci->config->item('rpx_token_url_login');
		$this->_token_url_register = $this->_ci->config->item('rpx_token_url_register');
	}
	
	/**
	 * cURL HTTP POST Request
	 * 
	 * Fetch Data from Janrain Engage Service API
	 * 
	 * @param 	$api_call	string
	 * 			Name of API Method to Call
	 * 
	 * @param 	$data 		array
	 * 			Array of data to post to API
	 * 
	 * @return mixed
	 */
	private function _curl_post($api_call, $data)
	{
		// Set format to JSON
		$data['format'] = 'json';

		// Initialize cURL session
		$curl = curl_init();

		// Setup cURL options
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_URL, $this->_post_url.'/'.$api_call);

		// Send that data...
		$this->response_data = curl_exec($curl);

		if( !curl_errno($curl))
		{
			// Get reponse data
			$response_body = $this->response_data;

			// Close cURL connection and flush data
			unset($response_data);
			curl_close($curl);

			return $response_body;
		}
		
		return FALSE;
	}
	
	/**
	 * Set token from Controller Response
	 * 
	 * @example	$error = $this->engage->get_last_error();
	 */
	function get_last_error()
	{
		return $this->_error;
	}

	/**
	 * Set token from Controller Response
	 * 
	 * @example $this->engage->token($this->input->post('token'));
	 */
	function token($token)
	{
		$this->_token = $token;
	}

	/**
	 * auth_info API Call ( REQUIRES PLUS, PRO or ENTERPRISE ACCOUNT )
	 * 
	 * The auth_info call is used in your token_url code, and is called after extracting 
	 * the token. Use the auth_info call to get information about the user currently 
	 * signing in to your web application.
	 * 
	 * @example $auth_info = $this->engage->auth_info();
	 * 
	 * @param 	$extended 	bool
	 * 			true' or 'false'(default). Return the extended Simple Registration and 
	 * 			HCard data in addition to the normalized Portable Contacts format.
	 * 
	 * @param 	$token_url	string
	 * 			Validate the specified token URL value against the token URL that was 
	 * 			originally sent. See 'Token URL mismatch' below for more details.
	 * 
	 * @return 	mixed
	 * 
	 * @link 	http://documentation.janrain.com/engage/api/auth_info
	 */
	function auth_info($extended=TRUE, $token_url=NULL)
	{
		$response = $this->_curl_post('auth_info',
			array(
				'apiKey' => $this->_api_key,
				'token' => $this->_token,
				'extended' => $extended,
				'tokenURL' => $token_url
			)
		);

		if($response !== FALSE)
		{
			return json_decode($response, TRUE);
		}
		
		return FALSE;
	}

	/**
	 * get_contacts API Call ( REQUIRES PRO or ENTERPRISE ACCOUNT )
	 * 
	 * Retrieve a list of contacts for an identifier in the Portable Contacts format. 
	 * The get_contacts call is made using the identifier returned in the auth_info call.
	 * 
	 * @example	$contacts = $this->engage->get_contacts($identifier);
	 * 
	 * @param 	$identifier	string
	 * 			The identifier returned from the auth_info API call. Identifiers from Google, 
	 * 			Yahoo, Windows Live, Facebook, MySpace, Twitter and LinkedIn are supported. 
	 * 			Google and Windows Live provide a contact's email address. Google and Yahoo 
	 * 			require additional setup in your Janrain Dashboard.
	 * 
	 * @return 	mixed
	 * 
	 * @link 	http://documentation.janrain.com/engage/api/get_contacts
	 */
	function get_contacts($identifier)
	{
		$response = $this->_curl_post('get_contacts',
			array(
				'apiKey' => $this->_api_key,
				'identifier' => $identifier
			)
		);

		if($response !== FALSE)
		{
			$contacts = json_decode($response, TRUE);
			return $contacts['response']['entry'];
		}
		
		return FALSE;
	}
	
	/**
	 * map API Call ( REQUIRES PRO or ENTERPRISE ACCOUNT )
	 * 
	 * Map an OpenID to a primary key. Future logins by this owner of this OpenID will return the mapped 
	 * primaryKey in the auth_info API response, which you may use to sign the user in.  The map call 
	 * is usually made right after a call to auth_info, when you already know who the user is because 
	 * they are signed in to your website with their legacy (or existing) account.
	 * 
	 * @example $map = $this->engage->map($identifier, $primary_key, TRUE);
	 * 
	 * @param 	$identifier	string
	 * 			The identifier returned from the auth_info API call.
	 * 
	 * @param 	$primary_key	int
	 * 			The primary key from your users table, as a string.
	 * 
	 * @param 	$overwrite	bool
	 * 			'true'(default) or 'false'. If 'false', only create the mapping if one 
	 * 			does not already exist for the specified identifier.
	 * 
	 * @return 	bool
	 * 
	 * @link 	http://documentation.janrain.com/mapping
	 */
	function map($identifier, $primary_key, $overwrite=TRUE)
	{		
		$response = $this->_curl_post('map',
			array(
				'apiKey' => $this->_api_key,
				'identifier' => $identifier,
				'primaryKey' => $primary_key,
				'overwrite' => $overwrite
			)
		);

		if($response !== FALSE)
		{
			$json = json_decode($response, TRUE);
			return ($json['stat'] === 'ok');
		}
		
		return FALSE;
	}

	/**
	 * unmap API Call ( REQUIRES PRO or ENTERPRISE ACCOUNT )
	 * 
	 * Remove (unmap) an OpenID from a primary key, and optionally unlink your application 
	 * from the user's account with the provider.
	 * 
	 * @example $unmap = $this->engage->unmap($identifier, FALSE. $primary_key, FALSE);
	 * 
	 * @param 	$identifier	string
	 * 			The identifier currently mapped to the primaryKey.
	 * 			NOTE:  Do not use with all_identifiers set to TRUE
	 * 
	 * @param 	$all_identifiers	bool
	 * 			'true' or 'false'(default). If true, all identifiers mapped to the 
	 * 			primaryKey should be removed.
	 * 			NOTE:  Do not use with identifier
	 * 
	 * @param 	$primary_key		int
	 * 			The primary key from your users table, as a string.
	 * 
	 * @param 	$unlink		bool
	 * 			'true' or 'false'(default). If 'true', unlink your application from the 
	 * 			user's account with the provider. Only Facebook is currently supported.
	 * 
	 * @return 	bool
	 * 
	 * @link 	http://documentation.janrain.com/mapping
	 */
	function unmap($identifier, $all_identifiers=FALSE, $primary_key, $unlink=FALSE)
	{		
		$response = $this->_curl_post('unmap',
			array(
				'apiKey' => $this->_api_key,
				'identifier' => $identifier,
				'all_identifiers' => $all_identifiers,
				'primaryKey' => $primary_key,
				'unlink' => $unlink
			)
		);

		if($response !== FALSE)
		{
			$json = json_decode($response, TRUE);
			return ($json['stat'] === 'ok');
		}
		
		return FALSE;
	}

	/**
	 * mappings API Call ( REQUIRES PRO or ENTERPRISE ACCOUNT )
	 * 
	 * Get all stored mappings for a particular primary key
	 * 
	 * @example $mappings = this->engage->mappings($primary_key);
	 * 
	 * @param 	$primary_key		int
	 * 			The primary key from your users table, as a string.
	 * 
	 * @return 	bool
	 * 
	 * @link 	http://documentation.janrain.com/mapping
	 */
	function mappings($primary_key)
	{
		$response = $this->_curl_post('mappings',
			array(
				'apiKey' => $this->_api_key,
				'primaryKey' => $primary_key
			)
		);

		if($response !== FALSE)
		{
			return json_decode($response, TRUE);
		}
		
		return FALSE;
	}
	
	/**
	 * all_mappings API Call ( REQUIRES PRO or ENTERPRISE ACCOUNT )
	 * 
	 * Get all stored mappings for a particular primary key
	 * 
	 * @example $mappings = this->engage->all_mappings();
	 * 
	 * @return 	bool
	 * 
	 * @link 	http://documentation.janrain.com/mapping
	 */
	function all_mappings()
	{
		$response = $this->_curl_post('all_mappings',
			array(
				'apiKey' => $this->_api_key
			)
		);

		if($response !== FALSE)
		{
			return json_decode($response, TRUE);
		}
		
		return FALSE;
	}
	
	/**
	 * set_status API Call ( REQUIRES PRO or ENTERPRISE ACCOUNT )
	 * 
	 * Set the status message for the account corresponding to an identifier. The set_status call 
	 * is made using the identifier returned in the auth_info call. The status message will be 
	 * set using the appropriate API for the specified identifier; for example, if the specified 
	 * identifier is a Facebook identifier, Janrain Engage will make an API call to Facebook to 
	 * set the status on the Facebook account.
	 * 
	 * @example	$status = $this->engage->set_status($identifier, 'Janrain Engage is Awesome!!!', '37.4220 -122.0843', TRUE);
	 * 
	 * @param 	$identifier	string
	 * 			The identifier returned from the auth_info API call.
	 * 
	 * @param 	$status		string
	 * 			The status message to set. No length restriction on the status is imposed by 
	 * 			Janrain Engage, however Twitter and LinkedIn limit status length to 140 characters. 
	 * 			See the truncate parameter.
	 * 
	 * @param 	$location	string
	 * 			This is a string containing location data associated with the content being published. 
	 * 			The string is latitude, followed by longitude, for example "37.4220 -122.0843". 
	 * 			Valid values for latitude are -90.0 to +90.0, with North being positive. Valid values 
	 * 			for longitude are -180.0 to +180.0 with East being positive.  In the cases of invalid 
	 * 			values in the location parameter, an invalid parameter exception is returned by the API. 
	 * 
	 * 			In the case of unsupported providers or users who have disabled location on their account 
	 * 			with the provider, the location value will be silently ignored.
	 * 
	 * @param 	$truncate	bool
	 * 			'true' (default) or 'false'. If 'true', truncate status when posting to providers which 
	 * 			impose status length restrictions (currently Twitter, Yahoo, and LinkedIn).
	 * 
	 * @return 	bool
	 * 
	 * @link 	http://documentation.janrain.com/set-status
	 */
	function set_status($identifier, $status, $location=NULL, $truncate=TRUE)
	{
		$response = $this->_curl_post('set_status', 
			array(
				'apiKey' => $this->_api_key,
				'identifier' => $identifier,
				'status' => $status,
				'location' => $location,
				'truncate' => $truncate
			)
		);

		if($response !== FALSE)
		{
			$json = json_decode($response, TRUE);
			return ($json['stat'] === 'ok');
		}
		
		return FALSE;
	}
	
	/**
	 * activity API Calls ( REQUIRES PRO or ENTERPRISE ACCOUNT )
	 * 
	 * Post an activity update to the user's activity stream. Here are some of our providers that support 
	 * activity posting: Facebook, LinkedIn, Twitter, MySpace, Yahoo!  Janrain Engage will make a best effort 
	 * to use all of the fields submitted in the activity request, but note that how they get presented 
	 * (and which ones are used) ultimately depends on the provider. 
	 * 
	 * This API will work if and only if: 
	 * 
	 * - Your Janrain Engage application has been configured to authenticate using the user's provider 
	 * - The user has already authenticated and has given consent to publish activity 
	 * 
	 * Otherwise, you will be given an error response indicating what was wrong. Detailed error responses 
	 * will also be given if the activity parameter does not meet the formatting requirements described below.
	 * 
	 * @example	$status = $this->engage->activity($identifier, urlencode(json_encode($activity)), TRUE, FALSE, '37.4220 -122.0843');
	 * 
	 * @param 	$identifier		string
	 * 			The user's identifier as received in an auth_info API response.
	 * 
	 * @param 	$activity		JSON
	 * 			The activity structure, JSON-encoded. 
	 * 			NOTE:  Be sure to URL-encode this value before submitting it.
	 * 
	 * @param 	$truncate		bool
	 * 			'true' (default) or 'false'. If 'true', truncate activity update text when posting 
	 * 			to providers which impose length restrictions (currently Twitter).
	 * 
	 * @param 	$url_shortening	bool
	 * 			A boolean indicating whether to provide the entire URL in the post/tweet, or 
	 * 			the shortened version.
	 * 
	 * @param 	$location		string
	 * 			This is a string containing location data associated with the content being published. 
	 * 			The string is latitude, followed by longitude, for example "37.4220 -122.0843". 
	 * 			Valid values for latitude are -90.0 to +90.0, with North being positive. Valid values 
	 * 			for longitude are -180.0 to +180.0 with East being positive.  In the cases of invalid 
	 * 			values in the location parameter, an invalid parameter exception is returned by the API. 
	 * 
	 * 			In the case of unsupported providers or users who have disabled location on their account 
	 * 			with the provider, the location value will be silently ignored.
	 * 
	 * 
	 * @return 	bool
	 * 
	 * @link 	http://documentation.janrain.com/activity
	 */
	function activity($identifier, $activity, $truncate=TRUE, $url_shortening=FALSE, $location=NULL)
	{
		$response = $this->_curl_post('activity',
			array(
				'apiKey' => $this->_api_key,
				'identifier' => $identifier,
				'activity' => $activity,
				'truncate' => $truncate,
				'url_shortening' => $url_shortening,
				'location' => $location
			)
		);

		if($response !== FALSE)
		{
			$json = json_decode($response, TRUE);
			return ($json['stat'] === 'ok');
		}
		
		return FALSE;
	}

	/**
	 * get_user_data API Call ( REQUIRES ENTERPRISE ACCOUNT )
	 * 
	 * Obtain an up-to-date copy of a user's profile as previously returned by an 
	 * auth_info API call. To use this API call, you must enable Offline Profile Access 
	 * from your application dashboard.
	 * 
	 * @example $user_data = $this->engage->get_user_data($identifier, TRUE);
	 * 
	 * @param 	$identifier	string
	 * 			The identifier returned from a previous auth_info API call.
	 * 
	 * @param	$extended		bool
	 * 			This feature is supported for identifiers from the following providers: 
	 * 			Facebook, LiveID, Twitter, MySpace, LinkedIn.
	 *  
	 * 			'true' or 'false'(default). Return the extended Simple Registration and 
	 * 			HCard data in addition to the normalized Portable Contacts format.
	 * 
	 * @return 	mixed
	 * 
	 * @link 	http://documentation.janrain.com/janrain-engage/janrain-engage-api/get-user-data
	 */
	function get_user_data($identifier, $extended=TRUE)
	{
		$response = $this->_curl_post('get_user_data',
			array(
				'apiKey' => $this->_api_key,
				'identifier' => $identifier,
				'extended' => $extended
			)
		);

		if($response !== FALSE)
		{
			return json_decode($response, TRUE);
		}
		
		return FALSE;
	}

	/**
	 * JavaScript to enable RPX Popup
	 * 
	 * Insert required Javascript Code into your Web Page
	 * 
	 * @example echo $this->engage->script();
	 * 
	 * @return 	string
	 */
	function script()
	{
		return '<script type="text/javascript" src="' . $this->_ci->config->item('rpx_script') . '"></script><script type="text/javascript">RPXNOW.overlay = true; RPXNOW.language_preference = "en"</script>'."\n";
	}

	/**
	 * Popup Link
	 * 
	 * Create Link to Launch Janrain Engage Popup pre-configured with your settings
	 * 
	 * @example echo $this->engage->popup('Login', 'login', 'my_class', 'my_id');
	 * 
	 * @param 	$link_text 	string	Text you want for the Popup Link
	 * @param 	$type 	string 	Either 'login' or 'register'
	 * @param 	$class 	string 	CSS Class you want to apply
	 * @param 	$id 	string 	Element ID you want to assign
	 * @return 	string
	 */
	function popup($link_text, $type='login', $class='rpxnow', $id='rpxnow')
	{
		$token_url = ($type == 'login')
			? $this->_token_url_login
			: $this->_token_url_register;
			
		return '<a class="' . $class . '" id="' . $id . '" onclick="return false;" href="' . $this->_ci->config->item('rpx_signin_url') . '?token_url=' . rawurlencode($token_url) . '">'.$link_text.'</a>'."\n";
	}

	/**
	 * Embeded RPX Login
	 * 
	 * Embed Janrain Engage iFrame into Web Page pre-configured with your settings
	 * 
	 * @example echo $this->engage->embed('login');
	 * 
	 * @param 	$type 	string 	Either 'login' or 'register'
	 * @return 	string
	 */
	function embed($type='login')
	{
		$token_url = ($type == 'login')
			? $this->_token_url_login
			: $this->_token_url_register;
		
		return '<iframe src="' . $this->_ci->config->item('rpx_embed_url') . '?token_url=' . rawurlencode($token_url) . '?ci_csrf_token=' . $this->_ci->security->get_csrf_hash() . '" scrolling="no" frameborder="0" seamless="seamless" style="width:400px; height:240px;"></iframe>'."\n";
	}
}

/* End of file engage.php */
/* Location: ./application/libraries/engage.php */