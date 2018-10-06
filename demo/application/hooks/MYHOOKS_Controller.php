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
class MYHOOKS_Controller extends CI_Controller {
	 
	/**
	 * @ Function Name		: class constructor
	 * @ Function Purpose 	: constructor function for class to load default files
	 * @ Function Returns	: 
	 */	
	public function __construct() 
    {
        parent:: __construct();
        $current_url = $_SERVER['REQUEST_URI'];
		$this->load->helper('url'); 
    }
     
	public function index(){
		
		echo "hello check hooks";
		exit;
		 
	}	
}
?>
