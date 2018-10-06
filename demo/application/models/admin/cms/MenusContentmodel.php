<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/* * * Users Model Class
*
* @package ClubInquire
* @subpackage Admin
*
* * */

class Menuscontentmodel extends CI_Model {
	
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
		$this->db->from("tbl_menus");  
		$this->db->where("tbl_menus.mnuId", $id);
		return $this->db->get()->row();
	}
}
?>
