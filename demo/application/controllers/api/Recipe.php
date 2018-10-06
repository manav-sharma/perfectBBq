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
        header("Content-Type: application/json; charset=UTF-8");
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
	}

	/**
	 * @desc Function used to get all recipe by category.
	 * @param CategoryId (int)
	 **/
    public function recipeByCategory_get() 
    { 
		$categoryId = $this->get('categoryId');
		if (isset($categoryId) && $categoryId != "") {
			$select = "rec.recId,rec.recTitleEng,rec.recTitleDutch,rec.recShortDescEng,rec.recShortDescDutch,rec.recLongDescEng,rec.recLongDescDutch,rec.recTempMode,rec.recThickMode,rec.recMin,rec.recSec,rec.recImage,rec.recGrilled,cat.catTitleEng,cat.catTitleDutch,cat.catAdvImage";
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
				->order_by('rec.recTitleEng','asc')    
				->get()->result();
			//Prepare data in array start
			$dataArr = array();
			foreach ($data as $dtKey => $dtVal) {
				$arr = array();
				$arr['recId'] = $dtVal->recId;
				$arr['recTitleEng'] = $dtVal->recTitleEng;
				$arr['recTitleDutch'] = $dtVal->recTitleDutch;
				$arr['recGrilled'] = $dtVal->recGrilled;
				//$arr['recShortDescEng'] = $dtVal->recShortDescEng;
				//$arr['recShortDescDutch'] = $dtVal->recShortDescDutch;
				//$arr['recLongDescEng'] = $dtVal->recLongDescEng;
				//$arr['recLongDescDutch'] = $dtVal->recLongDescDutch;
				$arr['recTempMode'] = $dtVal->recTempMode;
				$arr['recThickMode'] = $dtVal->recThickMode;
				if ($dtVal->recTempMode == "" && $dtVal->recThickMode == "") {
					$arr['recMin'] = $dtVal->recMin;
					$arr['recSec'] = $dtVal->recSec;
				}
				$recImage = $dtVal->recImage;
				if (trim($recImage) != "") {
					$arr['recImage'] = SITE_URL."bbq_images/".$dtVal->recImage;
				} else {
					$arr['recImage'] = '';
				}
				$arr['catTitleEng'] = $dtVal->catTitleEng;
				$arr['catTitleDutch'] = $dtVal->catTitleDutch;
				$catImage = $dtVal->catAdvImage;
				if (trim($catImage) != "") {
					$arr['catImage'] = SITE_URL."bbq_images/".$dtVal->catAdvImage;
				} else {
					$arr['catImage'] = '';
				}
				array_push($dataArr,$arr);
			}
			//Prepare data in array end
			
			
			//get advertisment data start 
			$catWhere = "c3.catId = ".$categoryId;
			$this->db->where($catWhere);
			$this->db->from('tbl_category c3');
			$parentResult = $this->db->get()->result();
			$parentAdvMode = $parentResult[0]->catAdvSwitch;
			$mainAdvArr = array();
			if ($parentAdvMode == 1) {
				$advData = $this->db->select("advId,advTitleEng,advTitleDutch,advImage")
				 ->where("FIND_IN_SET($categoryId,advCat)> 0")
				 ->from('tbl_advertisement')
				 ->get()->result();
				 if (!empty($advData)) {
					foreach ($advData as $advKey => $adVal) {
						$advArr = array();
						$advArr['advId'] = $adVal->advId;
						$advArr['advTitleEng'] = $adVal->advTitleEng;
						$advArr['advTitleDutch'] = $adVal->advTitleDutch;
						$advImage = $adVal->advImage;
						if (trim($advImage) != "") {
							$advArr['advImage'] = SITE_URL."bbq_images/".$adVal->advImage;
						} else {
							$advArr['advImage'] = '';
						}
						array_push($mainAdvArr,$advArr);
					}
				}
			}
			//get advertisment data end
			
				
			$this->db->where("rec.recStatus = 1 and rec.catId = ".$categoryId);
			$this->db->from('tbl_recipe rec');
			$totalRecords =  $this->db->count_all_results();  	
			if (!empty($data) ) {
				$response = [
					'status' => true,
					'data' => $dataArr,
					'advMode' => $parentAdvMode,
					'advData' => $mainAdvArr,
					'total_records' => $totalRecords,
				];
				// Set the response and exit
				$this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			} else {
				// Set the response and exit
				$this->response([
						'status' => false,
						'total_records' => $totalRecords,
						'message' => 'No record were found'
					], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'Category id is not define.'
			], REST_Controller::HTTP_OK);// OK (200) being the HTTP response code
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
			$sql = "select rec.recId,rec.recTitleEng,rec.recTitleDutch,rec.recShortDescEng,rec.recShortDescDutch,rec.recLongDescEng,rec.recLongDescDutch,rec.recTempMode,rec.recThickMode,rec.recMin,rec.recSec,rec.recImage,rec.recGrilled,cat.catTitleEng,cat.catTitleDutch,cat.catAdvImage from tbl_recipe rec left join tbl_category cat on rec.catId=cat.catId where rec.recStatus = 1 and rec.recId = ".$recipeId;
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
				if ($recData->recTempMode == "" && $recData->recThickMode == "") {
					$recipeData['recMin'] = $recData->recMin;
					$recipeData['recSec'] = $recData->recSec;
				}
				$recipeData['recGrilled'] = $recData->recGrilled;
				$recipeData['catTitleEng'] = $recData->catTitleEng;
				$recipeData['catTitleDutch'] = $recData->catTitleDutch;
				$image_base_path = SITE_URL."bbq_images/";
				$recImage = $recData->recImage;
				if (trim($recImage) != "") {
					$recipeData['recImage'] =  $image_base_path.$recData->recImage;
				} else {
					$recipeData['recImage'] = '';
				}
				
				$catImage = $recData->catAdvImage;
				if (trim($catImage) != "") {
					$recipeData['catImage'] =  $image_base_path.$recData->catAdvImage;
				} else {
					$recipeData['catImage'] = '';
				}
				
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
			], REST_Controller::HTTP_OK);// OK (200) being the HTTP response code
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
		$thickWhere = "";
		if (isset($thickness)) {
			if (trim($thickness) != "") {
				$thickWhere = " and recT.recThickness= '".$thickness."cm'";
			} else {
				$thickWhere = " and recT.recThickness is null";
			}
		}
		$cookStyle = $this->get('cookStyle');
		$cookWhere = "";
		if (isset($cookStyle)) {
			if (trim($cookStyle) != "") {
				$cookStyleName = "";
				if ($cookStyle == '1') {
					$cookStyleName = "Rare";
				} else if ($cookStyle == '2') {
					$cookStyleName = "Medium rare";
				} else if ($cookStyle == '3') {
					$cookStyleName = "Medium";
				} else if ($cookStyle == '4') {
					$cookStyleName = "Medium Well";
				} else if ($cookStyle == '5') {
					$cookStyleName = "Well Done";
				}
				$cookWhere = " and recT.recCookingStyle= '".$cookStyleName."'";
			} else {
				$cookWhere = " and recT.recCookingStyle is null";
			}
		}
		if (isset($recipeId) && trim($recipeId) != "") {
			$recTsql = "select recT.recTimeId,recT.recThickness,recT.recCookingStyle,recT.recTimeMin,recT.recTimeSec,recT.recTemp,recT.recBowlTemp,rec.recTitleEng,rec.recTitleDutch,rec.recShortDescEng,rec.recShortDescDutch,rec.recLongDescEng,rec.recLongDescDutch,rec.recImage,rec.recGrilled from tbl_recipeTiming recT left join tbl_recipe rec on recT.recId=rec.recId where recT.recTimeStatus = 1 and recT.recId = ".$recipeId.$thickWhere.$cookWhere;
			//echo $recTsql;die;
			$recTData = $this->db->query($recTsql)->row();
			$totalRecords =  count($recTData);
			if (!empty($recTData) ) {
				$recTimeId = $recTData->recTimeId;
				$recTIsql = "select recIntervalId,recIntervalTitleEng,recIntervalTitleDutch,recIntervalDescEng,recIntervalDescDutch,recIntervalTimeMin,recIntervalTimeSec from tbl_recipeTimeInterval where recTimeId = ".$recTimeId." order by recIntervalTotalSec asc";
				$recTIData = $this->db->query($recTIsql)->result();
				//echo "<pre>"; print_r($recTIData);die;
				$recipeTimeData = array();
				$recipeTimeData['recTimeId'] = $recTData->recTimeId;
				$recipeTimeData['recThickness'] = $recTData->recThickness;
				$recipeTimeData['recCookingStyle'] = $recTData->recCookingStyle;
				$recipeTimeData['recTimeMin'] = $recTData->recTimeMin;
				$recipeTimeData['recTimeSec'] = $recTData->recTimeSec;
				$recipeTimeData['recTemp'] = $recTData->recTemp;
				$recipeTimeData['recBowlTemp'] = $recTData->recBowlTemp;
				$recipeTimeData['recTitleEng'] = $recTData->recTitleEng;
				$recipeTimeData['recTitleDutch'] = $recTData->recTitleDutch;
				$recipeTimeData['recGrilled'] = $recTData->recGrilled;
				//$recipeTimeData['recShortDescEng'] = $recTData->recShortDescEng;
				//$recipeTimeData['recShortDescDutch'] = $recTData->recShortDescDutch;
				//$recipeTimeData['recLongDescEng'] = $recTData->recLongDescEng;
				//$recipeTimeData['recLongDescDutch'] = $recTData->recLongDescDutch;
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
			], REST_Controller::HTTP_OK);// OK (200) being the HTTP response code
		}
	}
}
