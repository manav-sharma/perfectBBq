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
        header("Content-Type: application/json; charset=UTF-8");
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
	}

		
	/**
	 * @desc Function used to get category list with there parents (If have).
	 **/
    public function allCategory_get() 
    { 
		$select = "c1.catId,c1.catTitleEng,c1.catTitleDutch,c1.catParentId,if(c1.catParentId =0,'',c2.catTitleEng) as catParentNameEng,if(c1.catParentId =0,'',c2.catTitleDutch) as catParentNameDutch,if(c1.catAdvImage != '',c1.catAdvImage,'') as catAdvImage,c1.catAdvSwitch,c1.catLayout,c1.catPosition";
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
		$Where = "c1.catStatus=1 and c1.catParentId = 0";
		$data = $this->db->select($select)
				->from('tbl_category c1')
				->join('tbl_category c2', 'c1.catParentId=c2.catId', 'left')
				->where($Where)
				->limit($limit, $offset)
				->order_by('c1.catPosition','asc')
				->get()->result();
		//echo $this->db->last_query();die;
		//Prepare data in array start
		$dataArr = array();
		foreach ($data as $dtKey => $dtVal) {
			$arr = array();
			if($dtVal->catParentNameEng == '' || $dtVal->catParentNameDutch == ''){
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
				$arr['catLayout'] = $dtVal->catLayout;
				$arr['catPosition'] = $dtVal->catPosition;
				array_push($dataArr,$arr);
			}
		}
		//Prepare data in array end
		$dataArr = array_values(array_filter($dataArr));
		##GET TOTAL COUNT#####################################
		$this->db->where($Where);
		$this->db->from('tbl_category c1');
		$totalRecords =  $this->db->count_all_results();
		
		//fetch all static pages start
		$pageArr = array();
		$pageData = $this->db->select('cmsid,cmsTitleEng,cmsTitleDutch')
				->from('tbl_cms')
				->where("cmsStatus='1'")
				->order_by('cmsid','desc')
				->get()->result();
		foreach ($pageData as $pKey => $pVal) {
			$arr = array();
			$arr['cmsId'] = $pVal->cmsid;
			$arr['cmsTitleEng'] = $pVal->cmsTitleEng;
			$arr['cmsTitleDutch'] = $pVal->cmsTitleDutch;
			array_push($pageArr,$arr);
		}
		//fetch all static pages end
		
		
		if (!empty($data) ) {
			$response = [
				'status' => true,
				'data' => $dataArr,
				'total_records' => $totalRecords,
				'per_page' => $limit,
				'page_data' => $pageArr
			];
			
			// Set the response and exit
			$this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		} else {
			// Set the response and exit
			$this->response([
					'status' => false,
					'total_records' => $totalRecords,
					'message' => 'No record were found.'
				], REST_Controller::HTTP_OK);// BAD_REQUEST (400) being the HTTP response code
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
			$select = "c1.catId,c1.catTitleEng,c1.catTitleDutch,c1.catParentId,if(c1.catParentId =0,'',c2.catTitleEng) as catParentNameEng,if(c1.catParentId =0,'',c2.catTitleDutch) as catParentNameDutch,if(c1.catAdvImage != '',c1.catAdvImage,'') as catAdvImage,c1.catAdvSwitch,c1.catLayout,c1.catPosition";
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
				->order_by('c1.catPosition','asc')
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
				$arr['catLayout'] = $dtVal->catLayout;
				$arr['catPosition'] = $dtVal->catPosition;
				array_push($dataArr,$arr);
			}
			//Prepare data in array end
			
			//get advertisment data start 
			$catWhere = "c3.catId = ".$parentCatId;
			$this->db->where($catWhere);
			$this->db->from('tbl_category c3');
			$parentResult = $this->db->get()->result();
			$parentAdvMode = $parentResult[0]->catAdvSwitch;
			$mainAdvArr = array();
			if ($parentAdvMode == 1) {
				$advData = $this->db->select("advId,advTitleEng,advTitleDutch,advImage")
				 ->where("FIND_IN_SET($parentCatId,advCat)> 0")
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
			$this->db->where($Where);
			$this->db->from('tbl_category c1');
			$totalRecords =  $this->db->count_all_results();
			
			if (!empty($data) ) {
				$response = [
					'status' => true,
					'data' => $dataArr,
					'advMode' => $parentAdvMode,
					'advData' => $mainAdvArr,
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
						'message' => 'No record were found.'
					], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'Parent Category id is not define.'
			], REST_Controller::HTTP_OK);// BAD_REQUEST (400) being the HTTP response code
		}
	}
	
	public function allScreens_get() 
    { 
		
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
		//$Where = "cmsStatus=1";
		$data = $this->db->select()
				->from('tbl_screen')				
				->where('cmsStatus','1')
				//->limit($limit, $offset)				
				->order_by("cmsDateCreated", "desc")   
				->get()->result();
		//Prepare data in array start
		
		$dataArr = array();
		foreach ($data as $dtKey => $dtVal) {
			$arr = array();
			
			$arr['cmsid'] = $dtVal->cmsid;
			$arr['cmsTitleEng'] = $dtVal->cmsTitleEng;
			$arr['cmsTitleDutch'] = $dtVal->cmsTitleDutch;
			$arr['cmsVariable'] = $dtVal->cmsVariable;
			$arr['cmsContentEng'] = $dtVal->cmsContentEng;
			$arr['cmsContentDutch'] = $dtVal->cmsContentDutch;
			
			$arr['cmsPrvContent'] = $dtVal->cmsPrvContent;
			$arr['cmsDateCreated'] = $dtVal->cmsDateCreated;
			array_push($dataArr,$arr);
		
		}
		//Prepare data in array end
		
		##GET TOTAL COUNT#####################################
		//$this->db->where($Where);
		//$this->db->from('tbl_screen');
		//$totalRecords =  $this->db->count_all_results();
		

		if (!empty($data) ) {
			$response = [
				'status' => true,
				'data' => $dataArr,
				//'total_records' => $totalRecords,
				//'per_page' => $limit
			];
			
			// Set the response and exit
			$this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		} else {
			// Set the response and exit
			$this->response([
					'status' => false,
					//'total_records' => $totalRecords,
					'message' => 'No record were found.'
				], REST_Controller::HTTP_OK);// BAD_REQUEST (400) being the HTTP response code
		}
	}
	
}
