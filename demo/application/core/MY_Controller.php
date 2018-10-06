<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Application MY_Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class MY_Controller extends CI_Controller {
	var $data = array();
	protected $_userdata;
	
    /**
     * @ Function Name		: class constructor
     * @ Function Purpose 	: constructor function for class to load default files
     * @ Function Returns	: 
     */	
    public function __construct() 
    {
        parent::__construct();
        $current_url = $_SERVER['REQUEST_URI'];
        $this->data['select'] = "home";
        
		if(!strpos($current_url,'admin')){
			$this->data = array();
			$this->load->helper(array('form', 'email'));
			$this->load->library(array('form_validation', 'email', 'message', 'utility', 'paging'));
		} else{
			$this->load->helper(array('form', 'common', 'url', 'array', 'email', "csv"));
        	$this->load->library(array('form_validation', 'utility', 'message', 'activepagination', 'email', 'image_lib', 'upload'));
        }
		
		$this->form_validation->set_error_delimiters('<li>','</li>');
		
		// check if admin is logged in or not 
		if($this->uri->segment(1)=='admin' && $this->uri->segment(2)!='' && $this->uri->segment(3)!='login' && $this->uri->segment(3)!='forgottenpassword'){
			if ($this->session->userdata('logged_in') != TRUE) {
				$this->session->set_flashdata('item','<div class="warning neg">Please login to your account to access internal pages.</div>');
				redirect('admin/users/login');
				return;
	        }
		}
		
		
		// Sections to print with profiler
		$sections = array(
			'queries' 			=> TRUE,
			'benchmarks' 		=> FALSE,
			'config' 			=> FALSE,
			'controller_info' 	=> FALSE,
			'get'				=> FALSE,
			'http_headers'		=> FALSE,
			'memory_usage'		=> false,
			'post'				=> FALSE,
			'uri_string' 		=> FALSE
		);
		$this->output->set_profiler_sections($sections);
		$this->output->enable_profiler(false);
    }
	
    /**
	 * @ Function Name		: setPagination
	 * @ Function Purpose 	: intialize the pagination configuration
	 * @ Function Returns	: 
	 */	
    public function setPagination($base_url,$total_rows,$per_page,$uri_segment,$suffix='') {
       	$config['base_url'] = $base_url;
 		$config['total_rows'] = $total_rows;
  		$config['per_page'] = $per_page;
  		$config['first_link'] = FALSE;
  		$config['last_link'] = FALSE;
  		$config['full_tag_open'] = '<div class="pagination"><div>';
  		$config['full_tag_close'] = '</div></div>';
  		$config['num_links'] = 2;
  		$config['uri_segment'] = $uri_segment;
  		$config['prefix'] = 'page/';
  		$config['suffix'] = $suffix;
  		
  		$config['prev_link'] = 'Previous';
  		$config['next_link'] = 'Next';
  		$config['use_page_numbers'] = TRUE;
		$config['first_url'] = $base_url.$suffix;
		
  		$this->paging->initialize($config);
    }
}