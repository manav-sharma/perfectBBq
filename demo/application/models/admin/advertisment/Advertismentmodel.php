<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/* * * Users Model Class
*
* @package ClubInquire
* @subpackage Admin
*
* * */

class Advertismentmodel extends CI_Model {
	
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
	* @ Function Name		: deleteUsers
	* @ Function Params		: $id {Array}
	* @ Function Purpose 	: delete users by id also delete from subscription list if user is subscribed
	* @ Function Returns	: 
	*/
	function deleteAdv($id) {  
		$this->db->where("advId IN (" . $id . ")", "", FALSE);  
		if ($this->db->delete('tbl_advertisement') == True){ 
			return TRUE;
		} else {
			return FALSE;
		}
	}


	/**
	* @ Function Name		: status
	* @ Function Params		: $id {Array/integer}, $status {active/inactive}
	* @ Function Purpose 	: update advertisment status by id
	* @ Function Returns	: 
	*/
	function status($id, $status) {
		$data = array(
			'advStatus' => $status,
		);
		$this->db->where('advId IN (' . $id . ')', '', false);
		return $this->db->update('tbl_advertisement', $data);
	}

	/**
	* @ Function Name		: addAdv
	* @ Function Params		: 
	* @ Function Purpose 	: function to add new advertisment by admin
	* @ Function Returns	: 
	**/
	public function addAdv($advData) {
		$return = FALSE; 
        $return = $this->db->insert('tbl_advertisement', $advData);
        $insert_id = $this->db->insert_id();
		return $insert_id;
	}
}
?>
