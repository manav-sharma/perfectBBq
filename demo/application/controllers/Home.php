<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * http://example.com/index.php/welcome
	 * - or -  
	 * http://example.com/index.php/welcome/index
	 * - or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	 
	 /**
	 * @ Function Name		: __construct
	 * @ Function Params	:
	 * @ Function Purpose 	: initilizing variable and providing pre functionalities
	 * @ Function Returns	:
	 * */
	 
	public function __construct() {
		
		parent::__construct();   	
	
		$this->load->model("frontend/cms/cmsmodel");
		$this->load->helper(array('form','url','email','common'));
        $this->load->library(array('form_validation','utility','email'));
	}
	
	/**
	* @ Function Name		: index
	* @ Function Params	: 
	* @ Function Purpose 	: load home page
	* @ Function Returns	: 
	*/
	public function index()
	{
		$data['title'] = 'Home Page';
		$this->load->view('welcome_message');
	}
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
