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
class Category extends REST_Controller 
{
    const limitPerPage = 10;
    function __construct() 
    { 
        parent::__construct();
	}

		
	/**
	 * @desc Function used to get category list with there parents (If have).
	 **/
    public function allCategory_get() 
    { 
		$select = "c1.catId,c1.catTitleEng,c1.catTitleDutch,c1.catParentId,if(c1.catParentId =0,'',c2.catTitleEng) as catParentNameEng,if(c1.catParentId =0,'',c2.catTitleDutch) as catParentNameDutch,if(c1.catAdvImage != '',c1.catAdvImage,'') as catAdvImage,c1.catAdvSwitch";
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
		$Where = "c1.catStatus=1";
		$data = $this->db->select($select)
				->from('tbl_category c1')
				->join('tbl_category c2', 'c1.catParentId=c2.catId', 'left')
				->where($Where)
				->limit($limit, $offset)    
				->get()->result();
		//Prepare data in array start
		$dataArr = array();
		foreach ($data as $dtKey => $dtVal) {
			$arr = array();
			$arr['catId'] = $dtVal->catId;
			$arr['catTitleEng'] = $dtVal->catTitleEng;
			$arr['catTitleDutch'] = $dtVal->catTitleDutch;
			$arr['catParentId'] = $dtVal->catParentId;
			$arr['catParentNameEng'] = $dtVal->catParentNameEng;
			$arr['catParentNameDutch'] = $dtVal->catParentNameDutch;
			$catImage = $dtVal->catAdvImage;
			if (trim($catImage) != "") {
				$arr['catImage'] = SITE_URL."bbq_images/".$dtVal->catAdvImage;
			} else {
				$arr['catImage'] = '';
			}
			$arr['catAdvSwitch'] = $dtVal->catAdvSwitch;
			if ($dtVal->catAdvSwitch == 1) {
				$advArr = array();
				$advData = $this->db->select("advId,advTitleEng,advTitleDutch,advImage")
				->from('tbl_advertisement')
				->get()->result();
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
				}
				$arr['advData'] = $advArr;
			}
			array_push($dataArr,$arr);
		}
		//Prepare data in array end
		
		##GET TOTAL COUNT#####################################
		$this->db->where($Where);
		$this->db->from('tbl_category c1');
		$totalRecords =  $this->db->count_all_results();
		
		
		
		
		if (!empty($data) ) {
			$response = [
				'status' => true,
				'data' => $dataArr,
				'total_records' => $totalRecords,
				'per_page' => $limit
			];
			
			// Set the response and exit
			$this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		} else {
			// Set the response and exit
			$this->response([
					'status' => false,
					'message' => 'No record were found'
				], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code
		}
	}
	
	/**
	 * @desc Function used to get all sub category list.
	 * @param parentCatId (int)
	 **/
    public function allSubCategory_get() 
    { 
		$parentCatId = $this->get('parentCatId');
		if (isset($parentCatId) && $parentCatId != "") {
			$select = "c1.catId,c1.catTitleEng,c1.catTitleDutch,c1.catParentId,if(c1.catParentId =0,'',c2.catTitleEng) as catParentNameEng,if(c1.catParentId =0,'',c2.catTitleDutch) as catParentNameDutch,if(c1.catAdvImage != '',c1.catAdvImage,'') as catAdvImage,c1.catAdvSwitch";
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
			$Where = "c1.catStatus=1 and c1.catParentId = ".$parentCatId;
			
			$data = $this->db->select($select)
				->from('tbl_category c1')
				->join('tbl_category c2', 'c1.catParentId=c2.catId', 'left')
				->where($Where)
				->limit($limit, $offset)    
				->get()->result();
			
			//Prepare data in array start
			$dataArr = array();
			foreach ($data as $dtKey => $dtVal) {
				$arr = array();
				$arr['catId'] = $dtVal->catId;
				$arr['catTitleEng'] = $dtVal->catTitleEng;
				$arr['catTitleDutch'] = $dtVal->catTitleDutch;
				$arr['catParentId'] = $dtVal->catParentId;
				$arr['catParentNameEng'] = $dtVal->catParentNameEng;
				$arr['catParentNameDutch'] = $dtVal->catParentNameDutch;
				$advImage = $dtVal->catAdvImage;
				if (trim($advImage) != "") {
					$arr['catImage'] = SITE_URL."bbq_images/".$dtVal->catAdvImage;
				} else {
					$arr['catImage'] = '';
				}
				
				$arr['catAdvSwitch'] = $dtVal->catAdvSwitch;
				if ($dtVal->catAdvSwitch == 1) {
					$advArr = array();
					$advData = $this->db->select("advId,advTitleEng,advTitleDutch,advImage")
								->from('tbl_advertisement')
								->get()->result();
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
					}
					$arr['advData'] = $advArr;
				}
				array_push($dataArr,$arr);
			}
			//Prepare data in array end
			
			$this->db->where($Where);
			$this->db->from('tbl_category c1');
			$totalRecords =  $this->db->count_all_results();  
			if (!empty($data) ) {
				$response = [
					'status' => true,
					'data' => $dataArr,
					'total_records' => $totalRecords,
					'per_page' => $limit
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
				'message' => 'Parent Category id is not define.'
			], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code
		}
	}
	
	/**
	 * @desc Function used to get advertisment by category.
	 * @param categoryId (int)
	 **/
    public function ImgByCategory_get() 
    { 
		$categoryId = $this->get('categoryId');
		if (isset($categoryId) && $categoryId != "") {
			$sql = "select catAdvImage,catAdvSwitch from tbl_category where catid = ".$categoryId;
			$advRow = $this->db->query($sql)->row();
			if (!empty($advRow) ) {
				$advData = array();
				$advImage = $advRow->catAdvImage;
				if (trim($advImage) != "") {
					$advData['catAdvImage'] = SITE_URL."bbq_images/".$advRow->catAdvImage;
				} else {
					$advData['catAdvImage'] = '';
				}
				$advData['catAdvSwitch'] = $advRow->catAdvSwitch;
				$response = [
					'status' => true,
					'data' => $advData,
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
}
