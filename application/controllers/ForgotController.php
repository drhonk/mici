<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category ForgotController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 * @author Andy Whitesides <Andy@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class ForgotController extends MI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    /**
	 * Forgot Password Recovery Page
	 * 
	 * @example http://www.esspree.com/forgot
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
		 * Setup Local Variables to Pass to View
		 */
        $data['title'] = 'Forgot Password';
		$data['description'] = '';
		$data['keywords'] = '';

        /**
		 * Copy View Partials as Local Variables to Pass to Main Views
		 */
        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);
        
        /**
		 * Load Views
		 */
        $this->load->view('browser/main/_partials/header', $data);
        $this->load->view('browser/main/account/pwd_recovery', $data);
        $this->load->view('browser/main/_partials/footer', $data);
    }
}

/* End of file ForgotController.php */
/* Location: ./application/controllers/ForgotController.php */