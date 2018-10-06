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
class Basic extends REST_Controller 
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
	 * @desc Function used to get category list with there parents (If have).
	 **/
    public function allBasic_get() 
    { 
		$select = "bbqId,bbqTitleEng,bbqTitleDutch,bbqLongDescEng,bbqLongDescDutch,bbqShortDescEng,bbqShortDescDutch,bbqImage";
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
		$Where = "bbqStatus=1";
        $data = $this->db->select($select)
				->from('tbl_bbqBasic')
				->where($Where)
				->limit($limit, $offset)    
				->get()->result();
		##GET TOTAL COUNT#####################################
		$this->db->where($Where);
		$this->db->from('tbl_bbqBasic');
		$totalRecords =  $this->db->count_all_results();  
		
		
		if (!empty($data) ) {
			$basicData = array();
			foreach ($data as $dKey => $dVal) {
				$arr = array();
				$arr['bbqId'] = $dVal->bbqId;
				$arr['bbqTitleEng'] = $dVal->bbqTitleEng;
				$arr['bbqTitleDutch'] = $dVal->bbqTitleDutch;
				$arr['bbqLongDescEng'] = $dVal->bbqLongDescEng;
				$arr['bbqLongDescDutch'] = $dVal->bbqLongDescDutch;
				$arr['bbqShortDescEng'] = $dVal->bbqShortDescEng;
				$arr['bbqShortDescDutch'] = $dVal->bbqShortDescDutch;
				$image_base_path = SITE_URL."bbq_images/";
				$arr['bbqImage'] = $image_base_path.$dVal->bbqImage;
				array_push($basicData,$arr);
			}
			//$basicData['status'] = true;
			//$basicData['total_records'] = $totalRecords;
			
			
			$response = [
				'status' => true,
				'data' => $basicData,
				'total_records' => $totalRecords,
				'per_page' => $limit
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
	}
	
	/**
	 * @desc Function used to get bbq basic detail.
	 * @param id (int)
	 **/
    public function basicDetail_get() 
    { 
		$basicId = $this->get('id');
		if (isset($basicId) && $basicId != "") {
			$sql = "select bbqId,bbqTitleEng,bbqTitleDutch,bbqLongDescEng,bbqLongDescDutch,bbqShortDescEng,bbqShortDescDutch,bbqImage from tbl_bbqBasic where bbqStatus = 1 and bbqId = ".$basicId;
			$basData = $this->db->query($sql)->row();
			if (!empty($basData) ) {
				$sql = "select bbqIngId,bbqIngNameEng,bbqIngNameDutch from tbl_bbqBasicIngredient where bbqId = ".$basicId;
				$basicIngData = $this->db->query($sql)->result();
				
				$basicData = array();
				$basicData['bbqId'] = $basData->bbqId;
				$basicData['bbqTitleEng'] = $basData->bbqTitleEng;
				$basicData['bbqTitleDutch'] = $basData->bbqTitleDutch;
				$basicData['bbqLongDescEng'] = $basData->bbqLongDescEng;
				$basicData['bbqLongDescDutch'] = $basData->bbqLongDescDutch;
				$basicData['bbqShortDescEng'] = $basData->bbqShortDescEng;
				$basicData['bbqShortDescDutch'] = $basData->bbqShortDescDutch;
				$image_base_path = SITE_URL."bbq_images/";
				$basicData['bbqImage'] =  $image_base_path.$basData->bbqImage;
				
				$ingredientArr = array();
				if (!empty($basicIngData)) {
					foreach ($basicIngData as $ingKey => $ingVal) {
						$arr = array();
						$arr['bbqIngId'] = $ingVal->bbqIngId;
						$arr['bbqIngNameEng'] = $ingVal->bbqIngNameEng;
						$arr['bbqIngNameDutch'] = $ingVal->bbqIngNameDutch;
						array_push($ingredientArr,$arr);
					}
				}
				$basicData['bbqIngredient'] = $ingredientArr;
				
				$response = [
					'status' => true,
					'data' => $basicData,
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
				'message' => 'ID is not define.'
			], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
	}
	
}
