<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Config
 * @category Engage
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */

/**
 * Load INI File
 *
 * Fetch Configuration for Framework INI File
 */
$framework_ini = (array) unserialize(FRAMEWORK_INI);

/**
 * RPX Domain
 *
 * Enter your rpxnow.com domain here (no trailing slash)
 * @example https://example-app.rpxnow.com
 */
$config['rpx_domain'] = $framework_ini['auth']['rpx_domain'];

/**
 * RPX API Key
 *
 * Your Janrain Engage API key
 */
$config['rpx_api_key'] = $framework_ini['auth']['rpx_api_key'];

/**
 * RPX Post URL
 *
 * Enter your rpxnow.com Post URL
 * @example https://rpxnow.com/api/v2/auth_info
 */
$config['rpx_post_url'] = $framework_ini['auth']['rpx_post_url'];

/**
 * RPX Token URL
 *
 * Enter your Token URL for Existing Users
 * @example http://www.mywebsite.com/login
 */
$config['rpx_token_url_login'] = $framework_ini['auth']['rpx_token_url_login'];

/**
 * RPX Token URL
 *
 * Enter your Token URL for Registering Users
 * @example http://www.mywebsite.com/register
 */
$config['rpx_token_url_register'] = $framework_ini['auth']['rpx_token_url_register'];

/**
 * RPX Embed URL
 */
$config['rpx_embed_url'] = $framework_ini['auth']['rpx_domain'].'/openid/embed';

/**
 * RPX Sign In URL
 */
$config['rpx_signin_url'] = $framework_ini['auth']['rpx_domain'].'/openid/v2/signin';

/**
 * RPX Sign In URL
 */
$config['rpx_script'] = 'https://rpxnow.com/js/lib/rpx.js';

/* End of file engage.php */
/* Location: ./application/config/engage.php */