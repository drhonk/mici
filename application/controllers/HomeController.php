<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category HomeController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 * @author Andy Whitesides <Andy@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class HomeController extends MI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

	/**
	 * Home Page
	 * 
	 * @example http://www.esspree.com/
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
        $data['title'] = 'Welcome';
		$data['description'] = '';
		$data['keywords'] = '';

		/**
		 * Load Views
		 */
        $this->load->view('browser/main/home/home', $data);
    }
}

/* End of file HomeController.php */
/* Location: ./application/controllers/HomeController.php */