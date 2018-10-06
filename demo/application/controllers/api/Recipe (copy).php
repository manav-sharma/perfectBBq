<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Recipe extends REST_Controller 
{
    const limitPerPage = 10;
    function __construct() 
    { 
        parent::__construct();
	}

	/**
	 * @desc Function used to get all recipe by category.
	 * @param CategoryId (int)
	 **/
    public function recipeByCategory_get() 
    { 
		$categoryId = $this->get('categoryId');
		if (isset($categoryId) && $categoryId != "") {
			$select = "rec.recId,rec.recTitleEng,rec.recTitleDutch,rec.recShortDescEng,rec.recShortDescDutch,rec.recLongDescEng,rec.recLongDescDutch,rec.recTempMode,rec.recThickMode,rec.recImage,cat.catTitleEng,cat.catTitleDutch";
			//Pagination starts here
			$offset = 0;
			$limit = $this->get('limit');
			if(empty($limit ) )
			{
				$limit = self::limitPerPage;
			}
			
			$page = $this->get('page');
			if(!empty($page ) )
			{
				$offset = ($page*$limit) - $limit;
			}
			//Pagination ends here
			$Where = "cat.catStatus = 1 and rec.recStatus = 1 and rec.catId = ".$categoryId;
			
			$data = $this->db->select($select)
				->from('tbl_recipe rec')
				->join('tbl_category cat', 'rec.catId=cat.catId', 'left')
				->where($Where)
				->limit($limit, $offset)    
				->get()->result();
			//Prepare data in array start
			$dataArr = array();
			foreach ($data as $dtKey => $dtVal) {
				$arr = array();
				$arr['recId'] = $dtVal->recId;
				$arr['recTitleEng'] = $dtVal->recTitleEng;
				$arr['recTitleDutch'] = $dtVal->recTitleDutch;
				$arr['recShortDescEng'] = $dtVal->recShortDescEng;
				$arr['recShortDescDutch'] = $dtVal->recShortDescDutch;
				$arr['recLongDescEng'] = $dtVal->recLongDescEng;
				$arr['recLongDescDutch'] = $dtVal->recLongDescDutch;
				$arr['recTempMode'] = $dtVal->recTempMode;
				$arr['recThickMode'] = $dtVal->recThickMode;
				$recImage = $dtVal->recImage;
				if (trim($recImage) != "") {
					$arr['recImage'] = SITE_URL."bbq_images/".$dtVal->recImage;
				} else {
					$arr['recImage'] = '';
				}
				$arr['catTitleEng'] = $dtVal->catTitleEng;
				$arr['catTitleDutch'] = $dtVal->catTitleDutch;
				array_push($dataArr,$arr);
			}
			//Prepare data in array end
				
			$this->db->where("rec.recStatus = 1 and rec.catId = ".$categoryId);
			$this->db->from('tbl_recipe rec');
			$totalRecords =  $this->db->count_all_results();  	
			if (!empty($data) ) {
				$response = [
					'status' => true,
					'data' => $dataArr,
					'total_records' => $totalRecords,
				];
				// Set the response and exit
				$this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			} else {
				// Set the response and exit
				$this->response([
						'status' => false,
						'message' => 'No record were found'
					], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'Category id is not define.'
			], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code
		}
	}
	
	/**
	 * @desc Function used to get recipe detail.
	 * @param recipeId (int)
	 **/
    public function recipeDetail_get() 
    { 
		$recipeId = $this->get('recipeId');
		if (isset($recipeId) && $recipeId != "") {
			$sql = "select rec.recId,rec.recTitleEng,rec.recTitleDutch,rec.recShortDescEng,rec.recShortDescDutch,rec.recLongDescEng,rec.recLongDescDutch,rec.recTempMode,rec.recThickMode,rec.recImage,cat.catTitleEng,cat.catTitleDutch from tbl_recipe rec left join tbl_category cat on rec.catId=cat.catId where rec.recStatus = 1 and rec.recId = ".$recipeId;
			$recData = $this->db->query($sql)->row();
			if (!empty($recData) ) {
				$sql = "select recIngId,recIngNameEng,recIngNameDutch from tbl_recipeIngredient where recId = ".$recipeId;
				$recIngData = $this->db->query($sql)->result();
				
				$recipeData = array();
				$recipeData['recId'] = $recData->recId;
				$recipeData['recTitleEng'] = $recData->recTitleEng;
				$recipeData['recTitleDutch'] = $recData->recTitleDutch;
				$recipeData['recShortDescEng'] = $recData->recShortDescEng;
				$recipeData['recShortDescDutch'] = $recData->recShortDescDutch;
				$recipeData['recLongDescEng'] = $recData->recLongDescEng;
				$recipeData['recLongDescDutch'] = $recData->recLongDescDutch;
				$recipeData['recTempMode'] = $recData->recTempMode;
				$recipeData['recThickMode'] = $recData->recThickMode;
				$recipeData['catTitleEng'] = $recData->catTitleEng;
				$recipeData['catTitleDutch'] = $recData->catTitleDutch;
				$image_base_path = SITE_URL."bbq_images/";
				$recipeData['recImage'] =  $image_base_path.$recData->recImage;
				
				$ingredientArr = array();
				if (!empty($recIngData)) {
					foreach ($recIngData as $ingKey => $ingVal) {
						$arr = array();
						$arr['recIngId'] = $ingVal->recIngId;
						$arr['recIngNameEng'] = $ingVal->recIngNameEng;
						$arr['recIngNameDutch'] = $ingVal->recIngNameDutch;
						array_push($ingredientArr,$arr);
					}
				}
				$recipeData['recIngredient'] = $ingredientArr;
				
				
				$response = [
					'status' => true,
					'data' => $recipeData,
				];
					
				// Set the response and exit
				$this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				
			} else {
				// Set the response and exit
				$this->response([
						'status' => false,
						'message' => 'No record were found'
					], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'Recipe id is not define.'
			], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code
		}
	}
	
	/**
	 * @desc Function used to get recipe timing detail.
	 * @params recipeId (int), thickness (string), cookStyle (string)
	 **/
	public function recipeTiming_get()
	{
		$recipeId = $this->get('recipeId');
		$thickness = $this->get('thickness');
		$cookStyle = $this->get('cookStyle');
		if (isset($recipeId) && isset($thickness) && isset($cookStyle) && trim($recipeId) != "" && trim($thickness) != "" && trim($cookStyle) != "") {
			$recTsql = "select recT.recTimeId,recT.recThickness,recT.recCookingStyle,recT.recTimeMin,recT.recTimeSec,recT.recTemp,rec.recTitleEng,rec.recTitleDutch,rec.recShortDescEng,rec.recShortDescDutch,rec.recImage from tbl_recipeTiming recT left join tbl_recipe rec on recT.recId=rec.recId where recT.recTimeStatus = 1 and recT.recId = ".$recipeId." and recT.recThickness = '".$thickness."' and recT.recCookingStyle = '".$cookStyle."'";
			$recTData = $this->db->query($recTsql)->row();
			$totalRecords =  count($recTData);
			if (!empty($recTData) ) {
				$recTimeId = $recTData->recTimeId;
				$recTIsql = "select recIntervalId,recIntervalTitleEng,recIntervalTitleDutch,recIntervalDescEng,recIntervalDescDutch,recIntervalTimeMin,recIntervalTimeSec from tbl_recipeTimeInterval where recTimeId = ".$recTimeId;
				$recTIData = $this->db->query($recTIsql)->result();
				//echo "<pre>"; print_r($recTIData);die;
				$recipeTimeData = array();
				$recipeTimeData['recTimeId'] = $recTData->recTimeId;
				$recipeTimeData['recThickness'] = $recTData->recThickness;
				$recipeTimeData['recCookingStyle'] = $recTData->recCookingStyle;
				$recipeTimeData['recTimeMin'] = $recTData->recTimeMin;
				$recipeTimeData['recTimeSec'] = $recTData->recTimeSec;
				$recipeTimeData['recTemp'] = $recTData->recTemp;
				$recipeTimeData['recTitleEng'] = $recTData->recTitleEng;
				$recipeTimeData['recTitleDutch'] = $recTData->recTitleDutch;
				$recipeTimeData['recShortDescEng'] = $recTData->recShortDescEng;
				$recipeTimeData['recShortDescDutch'] = $recTData->recShortDescDutch;
				$image_base_path = SITE_URL."bbq_images/";
				$recipeTimeData['recImage'] =  $image_base_path.$recTData->recImage;
				
				$timeIntervalArr = array();
				if (!empty($recTIData)) {
					foreach ($recTIData as $tiKey => $tiVal) {
						$arr = array();
						$arr['recIntervalId'] = $tiVal->recIntervalId;
						$arr['recIntervalTitleEng'] = $tiVal->recIntervalTitleEng;
						$arr['recIntervalTitleDutch'] = $tiVal->recIntervalTitleDutch;
						$arr['recIntervalDescEng'] = $tiVal->recIntervalDescEng;
						$arr['recIntervalDescDutch'] = $tiVal->recIntervalDescDutch;
						$arr['recIntervalTimeMin'] = $tiVal->recIntervalTimeMin;
						$arr['recIntervalTimeSec'] = $tiVal->recIntervalTimeSec;
						array_push($timeIntervalArr,$arr);
					}
				}
				$recipeTimeData['recTimeInterval'] = $timeIntervalArr;
				
				$response = [
					'status' => true,
					'data' => $recipeTimeData,
				];
					
				// Set the response and exit
				$this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				
			} else {
				// Set the response and exit
				$this->response([
						'status' => false,
						'message' => 'No record were found'
					], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'Recipe id, Thickness, Cooking Style must be define properly.'
			], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code
		}
	}
}
