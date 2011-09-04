<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Helpers
 * @category Gravatar
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */
/**
 * Begin Document
 */
if (!function_exists('get_gravatar'))
{
	/**
	 * Get either a Gravatar URL or complete image tag for a specified email address.
	 *
	 * @param string $email The email address
	 * @param boolean $secure Use HTTPS
	 * @param string $image_size Size in pixels, defaults to 80px [ 1 - 512 ]
	 * @param string $default_image Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ] - or custom image URL
	 * @param string $rating Maximum rating (inclusive) [ g | pg | r | x ]
	 * @param boolean $create_html True to return a complete IMG tag False for just the URL
	 * @param array $image_attributes Optional, additional key/value attributes to include in the IMG tag
	 * @return String containing either just a URL or a complete image tag
	 * @source http://gravatar.com/site/implement/images/php/
	 */
	function get_gravatar($email, $secure=FALSE, $image_size=80, $default_image='mm', $rating='g', $create_html=FALSE, $image_attributes=array() )
	{
		$url = ($secure)
			? 'http://www.gravatar.com/avatar/'
			: 'https://secure.gravatar.com/avatar/';
			
		$url .= md5(strtolower(trim($email))) . "?s={$image_size}&d={$default_image}&r={$rating}";
		
		if($create_html)
		{
			$url = '<img src="' . $url . '"';
			foreach($image_attributes as $key=>$val)
			{
				$url .= ' ' . $key . '="' . $val . '"';
			}
			$url .= ' />';
		}
		
		return $url;
	}
}

/* End of file gravatar_helper.php */
/* Location: ./application/helpers/gravatar_helper.php */