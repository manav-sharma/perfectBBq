<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/* * * Users Model Class
*
* @package ClubInquire
* @subpackage Admin
*
* * */

class Categorymodel extends CI_Model {
	
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
	* @ Function Name		: deleteCategory
	* @ Function Params		: $id {Array}
	* @ Function Purpose 	: Functionality to delete the category with all subcategories and recipes related to this.
	* @ Function Returns	: 
	*/
	function deleteCategory($ids) {
		//Get all recipe id by category start
		$recipeIdArr = array();
		foreach ($ids as $iKey => $iVal) {
			$this->db->select("*");
			$this->db->where("catId", $iVal);
			$result = $this->db->get("tbl_recipe");
			$recData = $result->result();
			if (!empty($recData)) {
				foreach ($recData as $recKey => $recVal) {
					$recId = $recVal->recId;
					array_push($recipeIdArr, $recId);
				}
			}
		}
		//Get all recipe id by category end
		
		if (!empty($recipeIdArr)) {
			//Get all ingredient id start
			foreach ($recipeIdArr as $rKey => $rVal) {
				$this->db->select("recIngId");
				$this->db->where("recId", $rVal);
				$result = $this->db->get("tbl_recipeIngredient");
				$recIngData = $result->result();
				if (!empty($recIngData)) {
					foreach ($recIngData as $ingKey => $ingVal) {
						$recIngId = $ingVal->recIngId;
						$this->db->where('recIngId', $recIngId);
						$this->db->delete('tbl_recipeIngredient'); //Delete the ingredients
					}
				} 
			}
			//Get all ingredient id end
			
			//Get all recipe timing start
			$recipeTimeArr = array();
			foreach ($recipeIdArr as $rKey => $rVal) {
				$this->db->select("recTimeId");
				$this->db->where("recId", $rVal);
				$result = $this->db->get("tbl_recipeTiming");
				$recTimeData = $result->result();
				if (!empty($recTimeData)) {
					foreach ($recTimeData as $timeKey => $timeVal) {
						$timeId = $timeVal->recTimeId;
						array_push($recipeTimeArr, $timeId);
					}
				}
			}
			//Get all recipe timing end
			
			//Get all recipe time interval start
			if (!empty($recipeTimeArr)) {
				foreach ($recipeTimeArr as $tKey => $tVal) {
					$this->db->select("recIntervalId");
					$this->db->where("recTimeId", $tVal);
					$result = $this->db->get("tbl_recipeTimeInterval");
					$recTimeIntervalData = $result->result();
					if (!empty($recTimeIntervalData)) {
						foreach ($recTimeIntervalData as $recTIKey => $recTIVal) {
							$tiId = $recTIVal->recIntervalId;
							$this->db->where('recIntervalId', $tiId);
							$this->db->delete('tbl_recipeTimeInterval'); //Delete the time interval
						}
					}
				}
				
				//Delete the recipe time start
				foreach ($recipeTimeArr as $tKey => $tVal) {
					$this->db->where('recTimeId', $tVal);
					$this->db->delete('tbl_recipeTiming');
				}
				//Delete the recipe time end
			}
			//Get all recipe time interval end
			
			//Delete the recipe start
			if (!empty($recipeIdArr)) {
				foreach ($recipeIdArr as $rKey => $rVal) {
					$this->db->where('recId', $rVal);
					$this->db->delete('tbl_recipe');
				}
			}
			//Delete the recipe end
			
		}
		
		foreach ($ids as $catKey => $catVal) {
			$this->db->where('catId', $catVal);
			$this->db->delete('tbl_category');
		}
		return TRUE;
	}

	/**
	* @ Function Name		: status
	* @ Function Params		: $id {Array/integer}, $status {active/inactive}
	* @ Function Purpose 	: update status category by id
	* @ Function Returns	: 
	*/
	function status($id, $status) {
		$data = array(
			'catStatus' => $status,
		);
		$this->db->where('catId IN (' . $id . ')', '', false);
		return $this->db->update('tbl_category', $data);
	}

	/**
	* @ Function Name		: addCategory
	* @ Function Params		: 
	* @ Function Purpose 	: function to add new category by admin
	* @ Function Returns	: 
	**/
	public function addCategory($catData) {
		$return = FALSE; 
        $return = $this->db->insert('tbl_category', $catData);
		return $return;
	}
	
	/**
	* @ Function Name		: getCategoryDetail
	* @ Function Params		: 
	* @ Function Purpose 	: function to get detail of category
	* @ Function Returns	: 
	**/
	
	function getCategoryDetail($id) {
		$this->db->select("*");
		$this->db->from("tbl_category"); 
		$this->db->where("catId", $id);
		return $this->db->get()->row();
	}
	
	/**
	* @ Function Name		: allActiveCatgegory
	* @ Function Params		: 
	* @ Function Purpose 	: function to get all active category
	* @ Function Returns	: 
	**/
	
	function allActiveCatgegory() 
	{
		$this->db->select("*");
		$this->db->where("catStatus", '1');
		$result = $this->db->get("tbl_category");
		return $result->result();
	}
	
	/**
	* @ Function Name		: getSubCategory
	* @ Function Params		: 
	* @ Function Purpose 	: function to get all active sub category by id
	* @ Function Returns	: 
	**/
	
	function getSubCategory($parentId) 
	{ 
		$this->db->select("*");
		$this->db->where("catParentId", $parentId);
		$result = $this->db->get("tbl_category");
		return $result->result();
	}
	
	/**
	* @ Function Name		: editCategoryData
	* @ Function Params		: 
	* @ Function Purpose 	: function to edit existing category by admin
	* @ Function Returns	: 
	**/
	public function editCategoryData($data, $catId) {
		$return = FALSE;
		$where = array('catId' => $catId);
		$return = $this->db->update('tbl_category', $data, $where); 
		return $return;
	}
}
?>
