<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Controller
 * @category TagController
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */
class TagController extends MI_Controller
{
    /**
     * Construct
     */
    function __construct()
    {
		parent::__construct();

		// Load CI Models
		$this->load->model('codeigniter/tag_model');
    }

    /**
     * Load the admin page and show if there is an upgrade required.
     */
    function index()
    {
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
		    if ($this->input->post('action') == 'delete')
		    {
				foreach ($this->input->post('tags') as $tag_id)
				{
				    $post_data = $this->tag_model->delete($tag_id);
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

		    redirect('/admin/tag');
		}

		$data = array();
		$data = array_merge($data, $this->data);

		$data['tags'] = $this->tag_model->get();

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
		$this->load->view('admin/tag/index', $data);
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

		    $post_data = $this->tag_model->add($clean_post);
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
		    redirect('/admin/tag');
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
			$this->load->view('admin/tag/detail', $data);
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

		    $post_data = $this->tag_model->update($this->uri_array['id'], $clean_post);
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
		    redirect('/admin/tag');
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
		    $data['tag'] = $this->tag_model->get($data['id']);

		    $data['sidebar'] = $this->load->view('admin/partials/sidebar', $data, TRUE);

		    $this->load->view('admin/partials/header', $data);
		    $this->load->view('admin/tag/detail', $data);
		    $this->load->view('admin/partials/footer', $data);
		}
    }
}

/* End of file TagController.php */
/* Location: ./application/controllers/admin/TagController.php */