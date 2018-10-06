<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/* * * Users Model Class
*
* @package ClubInquire
* @subpackage Admin
*
* * */

class Recipemodel extends CI_Model {
	
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
	* @ Function Name		: status
	* @ Function Params		: $id {Array/integer}, $status {active/inactive}
	* @ Function Purpose 	: update status of recipe by id
	* @ Function Returns	: 
	*/
	function status($id, $status) {
		$data = array(
			'recStatus' => $status,
		);
		$this->db->where('recId IN (' . $id . ')', '', false);
		return $this->db->update('tbl_recipe', $data);
	}
	
	/**
	* @ Function Name		: timeStatus
	* @ Function Params		: $id {Array/integer}, $status {active/inactive}
	* @ Function Purpose 	: update status of recipe timing by id
	* @ Function Returns	: 
	*/
	function timeStatus($id, $status) {
		$data = array(
			'recTimeStatus' => $status,
		);
		$this->db->where('recTimeId IN (' . $id . ')', '', false);
		return $this->db->update('tbl_recipeTiming', $data);
	}

	/**
	* @ Function Name		: addRecipe
	* @ Function Params		: 
	* @ Function Purpose 	: function to add new recipe by admin
	* @ Function Returns	: 
	**/
	public function addRecipe($recData) {
		$return = FALSE; 
        $return = $this->db->insert('tbl_recipe', $recData);
        $insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	/**
	* @ Function Name		: addRecipeIng
	* @ Function Params		: 
	* @ Function Purpose 	: function to add new Recipe Ingredient by admin
	* @ Function Returns	: 
	**/
	public function addRecipeIng($ingArr) {
		$return = FALSE; 
        $return = $this->db->insert('tbl_recipeIngredient', $ingArr);
        $insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	
	
	/**
	* @ Function Name		: addRecipeTiming
	* @ Function Params		: 
	* @ Function Purpose 	: function to add recipe timing by admin
	* @ Function Returns	: 
	**/
	public function addRecipeTiming($timeArr) { //echo "<pre>"; print_r($timeArr);die;
		$return = FALSE; 
        $return = $this->db->insert('tbl_recipeTiming', $timeArr);
        $insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	/**
	* @ Function Name		: deleteRecipeTiming
	* @ Function Params		: 
	* @ Function Purpose 	: function to delete recipe timing by admin
	* @ Function Returns	: 
	**/
	public function deleteRecipeTiming($recipeId) {
		$this->db->where('recId', $recipeId);
		$this->db->delete('tbl_recipeTiming'); //Delete the timing
		return true; 
	}
	
	/**
	* @ Function Name		: addRecipeTimeInterval
	* @ Function Params		: 
	* @ Function Purpose 	: function to add recipe time interval by admin
	* @ Function Returns	: 
	**/
	public function addRecipeTimeInterval($intervalArr) { 
		$return = FALSE; 
        $return = $this->db->insert('tbl_recipeTimeInterval', $intervalArr);
        $insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	/**
	* @ Function Name		: updateRecipeTimeInterval
	* @ Function Params		: 
	* @ Function Purpose 	: function to update recipe time interval by admin
	* @ Function Returns	: 
	**/
	public function updateRecipeTimeInterval($intervalArr, $intervalId) {// echo "<pre>"; print_r($intervalArr);die;
		$return = FALSE;
		$where = array('recIntervalId' => $intervalId);
		$return = $this->db->update('tbl_recipeTimeInterval', $intervalArr, $where); 
		return $return;
	}
	
	/**
	* @ Function Name		: editRecipeTiming
	* @ Function Params		: 
	* @ Function Purpose 	: function to edit existing recipe timing by id
	* @ Function Returns	: 
	**/
	public function editRecipeTiming($data, $timeId) { //echo "<pre>"; print_r($data);die;
		$return = FALSE;
		$where = array('recTimeId' => $timeId);
		$return = $this->db->update('tbl_recipeTiming', $data, $where); 
		return $return;
	}
	
	/**
	* @ Function Name		: getRecipeDetail
	* @ Function Params		: 
	* @ Function Purpose 	: function to get detail of recipe
	* @ Function Returns	: 
	**/
	
	function getRecipeDetail($id) {
		$this->db->select("*");
		$this->db->from("tbl_recipe"); 
		$this->db->where("recId", $id);
		return $this->db->get()->row();
	}
	
	/**
	* @ Function Name		: getRecipeTimeDetail
	* @ Function Params		: 
	* @ Function Purpose 	: function to get time detail of recipe
	* @ Function Returns	: 
	**/
	
	function getRecipeTimeDetail($id) {
		$this->db->select("*");
		$this->db->from("tbl_recipeTiming"); 
		$this->db->where("recTimeId", $id);
		return $this->db->get()->row();
	}
	
	/**
	* @ Function Name		: getRecipeTimeDetailByRecipeId
	* @ Function Params		: 
	* @ Function Purpose 	: function to get temperature detail of recipe
	* @ Function Returns	: 
	**/
	
	function getRecipeTimeDetailByRecipeId($recId) {
		$this->db->select("*");
		$this->db->from("tbl_recipeTiming"); 
		$this->db->where("recId", $recId);
		return $this->db->get()->row();
	}
	
	/**
	* @ Function Name		: getTimeIntervalDetail
	* @ Function Params		: 
	* @ Function Purpose 	: function to get detail of time interval
	* @ Function Returns	: 
	**/
	
	function getTimeIntervalDetail($id) {
		$this->db->select("*");
		$this->db->from("tbl_recipeTimeInterval"); 
		$this->db->where("recTimeId", $id);
		return $this->db->get()->row();
	}
	
	/**
	* @ Function Name		: getRecipeTimeIntervalDetail
	* @ Function Params		: 
	* @ Function Purpose 	: function to get detail of time interval by interval ID
	* @ Function Returns	: 
	**/
	
	function getRecipeTimeIntervalDetail($intervalId) { 
		$this->db->select("tbl_recipeTimeInterval.*,tbl_recipeTiming.recTotalSec,tbl_recipeTiming.recId,tbl_recipeTiming.recTimeMin,tbl_recipeTiming.recTimeSec");
		$this->db->from("tbl_recipeTimeInterval"); 
		$this->db->join("tbl_recipeTiming","tbl_recipeTiming.recTimeId=tbl_recipeTimeInterval.recTimeId"); 
		$this->db->where("recIntervalId", $intervalId);
		return $this->db->get()->row();
	}
	
	
	/**
	* @ Function Name		: getRecipeIng
	* @ Function Params		: 
	* @ Function Purpose 	: function to get all recipe ingredient
	* @ Function Returns	: 
	**/
	
	function getRecipeIng($recId) 
	{
		$this->db->select("*");
		$this->db->where("recId", $recId);
		$result = $this->db->get("tbl_recipeIngredient");
		return $result->result();
	}
	
	/**
	* @ Function Name		: editRecipeData
	* @ Function Params		: 
	* @ Function Purpose 	: function to edit existing recipe by id
	* @ Function Returns	: 
	**/
	public function editRecipeData($data, $recId) {
		$return = FALSE;
		$where = array('recId' => $recId);
		$return = $this->db->update('tbl_recipe', $data, $where); 
		return $return;
	}
	
	/**
	* @ Function Name		: deleteRecipe
	* @ Function Params		: $id {Array}
	* @ Function Purpose 	: Functionality to delete the recipe and its detail.
	* @ Function Returns	: 
	*/
	function deleteRecipe($ids) 
	{
		//Get all ingredient id start
		foreach ($ids as $recKey => $recVal) {
			$this->db->select("recIngId");
			$this->db->where("recId", $recVal);
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
		
		//Get all time data by recipe ID start
		foreach ($ids as $recKey => $recVal) {
			$this->db->select("recTimeId");
			$this->db->where("recId", $recVal);
			$result = $this->db->get("tbl_recipeTiming");
			$recTimingData = $result->result();
			if (!empty($recTimingData)) {
				foreach ($recTimingData as $timeKey => $timeVal) {
					$recTimeId = $timeVal->recTimeId;
					$this->db->where('recTimeId', $recTimeId);
					$this->db->delete('tbl_recipeTimeInterval'); //Delete the time intervals
				}
			}
		}
		//Get all time data by recipe ID end
		
		foreach ($ids as $recKey => $recVal) {
			$this->db->where('recId', $recVal);
			$this->db->delete('tbl_recipeTiming'); //Delete the timing
			
			$this->db->where('recId', $recVal);
			$this->db->delete('tbl_recipe');
		}
		return TRUE;
	}
	
	/**
	* @ Function Name		: deleteInterval
	* @ Function Params		: $id {Array}
	* @ Function Purpose 	: Functionality to delete the time intervals.
	* @ Function Returns	: 
	*/
	function deleteInterval($id) {
        $this->db->where("recIntervalId IN (" . $id . ")", "", false);
        if ($this->db->delete('tbl_recipeTimeInterval') == true) {
            return true;
        } else {
            return false;
        }
    }
}
?>
