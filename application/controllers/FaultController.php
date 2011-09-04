<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category FaultController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class FaultController extends MI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

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
        $data['title'] = 'Error 403';
		$data['description'] = 'Permission Denied';
		$data['keywords'] = '';

        /**
		 * Copy View Partials as Local Variables to Pass to Main Views
		 */
        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);

		/**
		 * Set Output Header
		 */ 
		$this->output->set_status_header(403);

        /**
		 * Load Views
		 */
        $this->load->view('browser/main/_partials/header', $data);
        $this->load->view('browser/error/403', $data);
        $this->load->view('browser/main/_partials/footer', $data);
    }

    function code()
    {
        /**
		 * Fetch Error Code
		 */
        $error_code = $this->uri->segment(3);

        /**
		 * @var $data Array Merge MI_Controller $this->data with a local array
		 */
		$data = array();
        $data = array_merge($data, $this->data);

        /**
		 * Setup Local Variables to Pass to View
		 */
        $data['title'] = 'Error '.$error_code;
		$data['description'] = '';
		$data['keywords'] = '';

        /**
		 * Copy View Partials as Local Variables to Pass to Main Views
		 */
        $data['menu'] = $this->load->view('browser/main/_partials/menu', $data, TRUE);

		/**
		 * Set Output Header
		 */ 
		$this->output->set_status_header($error_code);

        /**
		 * Load Views
		 */
        $this->load->view('browser/main/_partials/header', $data);
        $this->load->view('browser/error/'.$error_code, $data);
        $this->load->view('browser/main/_partials/footer', $data);
    }
}

/* End of file FaultController.php */
/* Location: ./application/controllers/FaultController.php */