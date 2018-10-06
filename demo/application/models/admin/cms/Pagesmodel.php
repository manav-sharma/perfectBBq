<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/* * * Users Model Class
*
* @package ClubInquire
* @subpackage Admin
*
* * */

class Pagesmodel extends CI_Model {
	
	/**
	* @ Function Name		: index
	* @ Function Params	: 
	* @ Function Purpose 	: Auto loading function, specify all the parameter which requires to be used 	global when this class loads 
	* @ Function Returns	: 
	*/
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	* @ Function Name		: getDetails
	* @ Function Params		: 
	* @ Function Purpose 	: function to edit existing page by admin
	* @ Function Returns	: 
	**/
	
	function getDetails($id) {
		$this->db->select("*");
		$this->db->from("tbl_cms");  
		$this->db->where("tbl_cms.cmsid", $id);
		return $this->db->get()->row();
	}
	/**
	* @ Function Name		: deleteUsers
	* @ Function Params		: $id {Array}
	* @ Function Purpose 	: delete users by id also delete from subscription list if user is subscribed
	* @ Function Returns	: 
	*/
	function deletePage($id) {  
		$this->db->where("cmsid IN (" . $id . ")", "", FALSE);  
		if ($this->db->delete('tbl_cms') == True){ 
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	* @ Function Name		: status
	* @ Function Params		: $id {Array/integer}, $status {active/inactive}
	* @ Function Purpose 	: delete users by id
	* @ Function Returns	: 
	*/
	function status($id, $status) {
		$data = array(
			'cmsStatus' => $status,
		);
		$this->db->where('cmsid IN (' . $id . ')', '', false);
		return $this->db->update('tbl_cms', $data);
	}

	 

	/**
	* @ Function Name		: addUser
	* @ Function Params		: 
	* @ Function Purpose 	: function to add new user by admin
	* @ Function Returns	: 
	**/
	public function addpage() {
		$return = FALSE;  
		$pagedetails = array(
			'cmsTitleEng'			=> $this->input->post('txttileEng'),
			'cmsTitleDutch'			=> $this->input->post('txttileDutch'),
			'cmsVariable' 		=> strtolower(preg_replace('/\s+/', '', $this->input->post('txttile'))),  
			'cmsContentEng' 		=> $this->input->post('cmsContentEng'),
			'cmsContentDutch' 		=> $this->input->post('cmsContentDutch'),
			'cmsStatus' 		=> $this->input->post('txtcmsStatus'), 
			'cmsDateCreated'  => date('Y-m-d'), 
			'cmsDateModified'  => date('Y-m-d')
		); 
		if ($this->db->insert('tbl_cms', $pagedetails)) { 
			return true;
		}
		return $return;
	}
	
 
	/**
	* @ Function Name		: editUserData
	* @ Function Params		: 
	* @ Function Purpose 	: function to edit existing user by admin
	* @ Function Returns	: 
	**/
	public function editPageData($usrId) {
		$return = FALSE;
		$id = $usrId;  
		$pagedetails = array(
			'cmsTitleEng'			=> $this->input->post('txttileEng'),
			'cmsTitleDutch'			=> $this->input->post('txttileDutch'),
			'cmsVariable' 		=> strtolower(preg_replace('/\s+/', '', $this->input->post('txttile'))),  
			'cmsContentEng' 		=> $this->input->post('cmsContentEng'),
			'cmsContentDutch' 		=> $this->input->post('cmsContentDutch'),
			'cmsStatus' 		=> $this->input->post('txtcmsStatus'), 
			'cmsDateModified ' => date('Y-m-d'), 
		);
	  
		$where = array('cmsid' => $id);
		if ($this->db->update('tbl_cms', $pagedetails, $where)) { 
			return TRUE;
		} else {
			return FALSE;
		}
		return $return;
	}
	
	/**
	* @ Function Name		: getDetails
	* @ Function Params		: 
	* @ Function Purpose 	: function to edit existing page by admin
	* @ Function Returns	: 
	**/
	function getActivePages() {
		$this->db->select("cmsid,cmsTitleEng");
		$this->db->from("tbl_cms");  
		$this->db->where("tbl_cms.cmsStatus", '1');
		
		$result = $this->db->get();
		$getdata=$result->result_array();
		return $getdata;
	}
	
	function getHotelsDetails(){
		//echo "hello modal";
		//exit;
		
		$this->db->select("*");
		$this->db->from("tb_hotel"); 
		$this->db->join('tbl_users', 'tbl_users.id = tb_hotel.userId', 'left');
		//$this->db->where("tbl_users.id", $id);
		$result = $this->db->get();
		$getdata = $result->result_array();
		
		return $getdata;
		
		
	}
}
?>
