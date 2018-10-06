<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/* * * Users Model Class
*
* @package ClubInquire
* @subpackage Admin
*
* * */

class Basicmodel extends CI_Model {
	
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
	* @ Function Name		: deleteBasic
	* @ Function Params		: $id {Array}
	* @ Function Purpose 	: Functionality to delete the bbq basic with all ingredient.
	* @ Function Returns	: 
	*/
	function deleteBasic($ids) {
		$bbqIdsArr = $ids;
		if (!empty($bbqIdsArr)) {
			foreach ($bbqIdsArr as $bKey => $bVal) {
				$this->db->where('bbqId', $bVal);
				$this->db->delete('tbl_bbqBasicIngredient'); //Delete the ingredients
			}
			
			foreach ($bbqIdsArr as $bKey => $bVal) {
				$this->db->where('bbqId', $bVal);
				$this->db->delete('tbl_bbqBasic'); //Delete the ingredients
			}
		}
		return TRUE;
	}

	/**
	* @ Function Name		: status
	* @ Function Params		: $id {Array/integer}, $status {active/inactive}
	* @ Function Purpose 	: update bbq basic status by id
	* @ Function Returns	: 
	*/
	function status($id, $status) {
		$data = array(
			'bbqStatus' => $status,
		);
		$this->db->where('bbqId IN (' . $id . ')', '', false);
		return $this->db->update('tbl_bbqBasic', $data);
	}

	/**
	* @ Function Name		: addBasic
	* @ Function Params		: 
	* @ Function Purpose 	: function to add new BBQ Basic by admin
	* @ Function Returns	: 
	**/
	public function addBasic($basicData) {
		$return = FALSE; 
        $return = $this->db->insert('tbl_bbqBasic', $basicData);
        $insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	/**
	* @ Function Name		: addBasicIng
	* @ Function Params		: 
	* @ Function Purpose 	: function to add new BBQ Basic Ingredient by admin
	* @ Function Returns	: 
	**/
	public function addBasicIng($ingArr) {
		$return = FALSE; 
        $return = $this->db->insert('tbl_bbqBasicIngredient', $ingArr);
        $insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	/**
	* @ Function Name		: getBasicDetail
	* @ Function Params		: 
	* @ Function Purpose 	: function to get detail of BBQ basic
	* @ Function Returns	: 
	**/
	
	function getBasicDetail($id) {
		$this->db->select("*");
		$this->db->from("tbl_bbqBasic"); 
		$this->db->where("bbqId", $id);
		return $this->db->get()->row();
	}
	
	/**
	* @ Function Name		: getBasicIng
	* @ Function Params		: 
	* @ Function Purpose 	: function to get all basic ingredient
	* @ Function Returns	: 
	**/
	
	function getBasicIng($basicId) 
	{
		$this->db->select("*");
		$this->db->where("bbqId", $basicId);
		$result = $this->db->get("tbl_bbqBasicIngredient");
		return $result->result();
	}
	
	
	/**
	* @ Function Name		: editBasicData
	* @ Function Params		: 
	* @ Function Purpose 	: function to edit existing BBQ basic by id
	* @ Function Returns	: 
	**/
	public function editBasicData($data, $bbqId) { //echo "<pre>"; print_r($data);die;
		$return = FALSE;
		$where = array('bbqId' => $bbqId);
		$return = $this->db->update('tbl_bbqBasic', $data, $where); 
		return $return;
	}
}
?>
