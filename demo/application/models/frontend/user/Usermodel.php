<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/* * * Users Model Class
*
* @package 
* @subpackage Admin
*
* * */

class Usermodel extends CI_Model {
	
	/**
	 * @ Function Name		: constructor
	 * @ Function Returns	:
	 */
	
	public function __construct() {
		parent::__construct();		 
	}
	
	 /**
	* @ Function Name		: addAdvertiser
	* @ Function Params		: 
	* @ Function Purpose 	: add the advertisers
	* @ Function Returns	: 
	*/
	
	 public function addAdvertiser()

 {       
        $advertiserDetails = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password_hash' => md5($this->input->post('password')),
            'status' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'fname' => $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'phone' => $this->input->post('phone'),
            'cmpName' =>  $this->input->post('cname'),
            'cmpWebsite' =>  $this->input->post('cwebsite'),
            'cmpAddress' =>  $this->input->post('caddress'),
            'cmpPhone' =>  $this->input->post('cphone'),
            'gender' =>  $this->input->post('genderType'),
            'user_type' =>'advertiser',
            'social_id' => $this->input->post('social_id'),
            'social_media_type' => $this->input->post('social_media_type')
        );
        
        if($this->input->post('social_id'))
            $advertiserDetails['is_email_verified'] = '1';
        
        if ($this->db->insert('user', $advertiserDetails)) {
            return true;
        }
        return false;
    }
   
   
   
     /**
	* @ Function Name		: checkemail
	* @ Function Params		: $emailvalue {string}
	* @ Function Purpose 	: check admin detail by username
	* @ Function Returns	: 
	*/

     public function checkemail($emailvalue)
    {
		
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('email',$emailvalue);
		$query = $this->db->get();
		$num_rows = $query->num_rows();
		return $num_rows;
	
	}
  
}
