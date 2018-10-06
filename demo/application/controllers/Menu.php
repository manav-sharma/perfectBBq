<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {

	 /**
	* @ Function Name		: __construct
	* @ Function Params	: 
	* @ Function Purpose 	: initilizing variable and providing pre functionalities
	* @ Function Returns	: 
	*/
	public function __construct() {
		parent::__construct(); 
		$this->load->helper('form');
		$id = 'right';
		$url='http://localhost/projects/Codeigniterdemo/development/cms/page/';
		$data['menuUrl']= creatMenu($id,0,$url); 
		$this->load->view('frontend/menu',$data); 
	}
	/**
	* @ Function Name		: index
	* @ Function Params	: 
	* @ Function Purpose 	: Managing user login and controls redirection
	* @ Function Returns	: 
	*/
	public function index(){
		 
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */