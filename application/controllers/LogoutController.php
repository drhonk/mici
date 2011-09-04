<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category LogoutController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 * @author Andy Whitesides <Andy@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class LogoutController extends MI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    /**
	 * Logout Page
	 * 
	 * @example http://www.esspree.com/logout
	 * @return void
	 * @access public
	 */
    function index()
    {
        /**
		 * @var $data Array Merge MI_Controller $this->data with a local array
		 */
		$data = array();
        $data = array_merge($data, $this->data);

		/**
		 * @todo Finish Logout Controller to remove authentication and redirect to home page
		 */
}

/* End of file LogoutController.php */
/* Location: ./application/controllers/LogoutController.php */