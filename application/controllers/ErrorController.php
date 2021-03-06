<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category ErrorController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class ErrorController extends MI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $data = array();
        $data = array_merge($data, $this->data);

        $data['footer_menu'] = $this->load->view('_partials/browser/footer_menu', $data, TRUE);

        $error_code = 403;

        $this->output->set_status_header($error_code);
        $this->load->view('browser/error/'.$error_code, $data);
    }

    function code()
    {
        $data = array();
        $data = array_merge($data, $this->data);

        $data['footer_menu'] = $this->load->view('_partials/browser/footer_menu', $data, TRUE);

        $error_code = $this->uri->segment(3);

        $this->output->set_status_header($error_code);
        $this->load->view('browser/error/'.$error_code, $data);
    }

    function error_404()
    {
        $data = array();
        $data = array_merge($data, $this->data);

        $data['footer_menu'] = $this->load->view('_partials/browser/footer_menu', $data, TRUE);

        $this->output->set_status_header('404');
        $this->load->view('browser/error/404', $data);
    }
}

/* End of file ErrorController.php */
/* Location: ./application/controllers/ErrorController.php */