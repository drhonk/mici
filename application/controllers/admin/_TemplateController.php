<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category @CONTROLLER@Controller
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * @todo 	To use this as a template replace:
 * 
 * 			@CONTROLLER@ = Controller Name ( title case )
 *			@MODEL@ = Model Name ( lower case )
 * 			@MODEL_GET@ = Variable Name ( lower case & typically plural name of @MODEL@ )
 *
 *			NOTE:  There is a view folder named _template with templates for this controller
 *			ALSO:  Don't use this template unless you are familiar with MICI
 */

/**
 * Begin Document
 */
class @CONTROLLER@Controller extends MI_Controller
{
    /**
     * Construct
     */
    function __construct()
    {
		parent::__construct();

		// Load CI Models
		$this->load->model('codeigniter/@MODEL@_model');
    }

    function index()
    {
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
		    if ($this->input->post('action') == 'delete')
		    {
				foreach ($this->input->post('@MODEL_GET@') as $@MODEL@_id)
				{
				    $post_data = $this->@MODEL@_model->delete($@MODEL@_id);
				    if(is_array($post_data))
				    {
						$this->session->set_flashdata('error_message', $post_data['error']);
						$this->session->set_flashdata('error_code', $post_data['code']);
				    }
				}
		    }

			// flush memcache
			if (class_exists('Memcache'))
			{
			    $memcache = new Memcache();
			    $memcache->connect('localhost', 11211);
			    $memcache->flush();
			}

		    redirect('/admin/@MODEL@');
		}

		$data = array();
		$data = array_merge($data, $this->data);

		$data['@MODEL_GET@'] = $this->@MODEL@_model->get();

		$data['error_message'] = $this->session->flashdata('error_message');
		$data['error_code'] = $this->session->flashdata('error_code');

	    $data['display_message'] = $this->session->flashdata('display_message');
		$data['display_message_type'] = $this->session->flashdata('display_message_type');

		if( !empty($data['error_message']))
		{
		    $this->firephp->error($data['error_message']);
		}

		$data['sidebar'] = $this->load->view('admin/partials/sidebar', $data, TRUE);

		$this->load->view('admin/partials/header', $data);
		$this->load->view('admin/@MODEL@/index', $data);
		$this->load->view('admin/partials/footer', $data);
    }

    function add()
    {
		// form submission via POST
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
		    $clean_post = array();
		    foreach ($_POST as $key => $val)
		    {
				$clean_post[$key] = $this->security->xss_clean(utf8_cleaner($val));
		    }

		    $post_data = $this->@MODEL@_model->add($clean_post);
		    if(is_array($post_data))
		    {
				$this->session->set_flashdata('error_message', $post_data['error']);
				$this->session->set_flashdata('error_code', $post_data['code']);
				$this->session->set_flashdata('post_data', serialize($clean_post));
		    }
		    unset($clean_post);

			// flush memcache
			if (class_exists('Memcache'))
			{
				$memcache = new Memcache();
				$memcache->connect('localhost', 11211);
				$memcache->flush();
			}

		    // redirect
		    redirect('/admin/@MODEL@');
		}
		// not form submission
		else
		{
			$data = array();
			$data = array_merge($data, $this->data);

			$data['error_message'] = $this->session->flashdata('error_message');
			$data['error_code'] = $this->session->flashdata('error_code');
			$data['post_data'] = unserialize($this->session->flashdata('post_data'));

			if( !empty($data['error_message']))
			{
				$this->firephp->error($data['error_message']);
				$this->firephp->error($data['post_data']);
			}

			$data['sidebar'] = $this->load->view('admin/partials/sidebar', $data, TRUE);

			$this->load->view('admin/partials/header', $data);
			$this->load->view('admin/@MODEL@/detail', $data);
			$this->load->view('admin/partials/footer', $data);
		}
    }

    function edit()
    {
		// form submission via POST
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
		    $clean_post = array();
		    foreach ($_POST as $key => $val)
		    {
				$clean_post[$key] = $this->security->xss_clean(utf8_cleaner($val));
		    }

		    $post_data = $this->@MODEL@_model->update($this->uri_array['id'], $clean_post);
		    if(is_array($post_data))
		    {
				$this->session->set_flashdata('error_message', $post_data['error']);
				$this->session->set_flashdata('error_code', $post_data['code']);
				$this->session->set_flashdata('post_data', serialize($clean_post));
		    }
		    unset($clean_post);

			// flush memcache
			if (class_exists('Memcache'))
			{
			    $memcache = new Memcache();
			    $memcache->connect('localhost', 11211);
			    $memcache->flush();
			}

		    // redirect
		    redirect('/admin/@MODEL@');
		}
		// not form submission
		else
		{
		    $data = array();
		    $data = array_merge($data, $this->data);

		    $data['error_message'] = $this->session->flashdata('error_message');
		    $data['error_code'] = $this->session->flashdata('error_code');
		    $data['post_data'] = unserialize($this->session->flashdata('post_data'));

		    if( !empty($data['error_message']))
		    {
				$this->firephp->error($data['error_message']);
				$this->firephp->error($data['post_data']);
		    }

		    $data['id'] = $this->uri_array['id'];
		    $data['@MODEL@'] = $this->@MODEL@_model->get($data['id']);

		    $data['sidebar'] = $this->load->view('admin/partials/sidebar', $data, TRUE);

		    $this->load->view('admin/partials/header', $data);
		    $this->load->view('admin/@MODEL@/detail', $data);
		    $this->load->view('admin/partials/footer', $data);
		}
    }
}

/* End of file @CONTROLLER@Controller.php */
/* Location: ./application/controllers/admin/@CONTROLLER@Controller.php */