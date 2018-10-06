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
class Search extends REST_Controller 
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
	 * @desc Function used to serach recipe data.
	 * @param keyword (string)
	 **/
    public function byKeyword_get() 
    { 
		$keyword = $this->get('keyword');
		if (isset($keyword) && $keyword != "") {
			$sreachQry = "select u.* from (select catId as 'id',catTitleEng as 'titleEng',catTitleDutch as 'titleDutch',catAdvImage as 'image','category' as 'type' from tbl_category where catStatus = 1 and (catTitleEng like '%".$keyword."%' or catTitleDutch like '%".$keyword."%') UNION select recId as 'id',recTitleEng as 'titleEng',recTitleDutch as 'titleDutch',recImage as 'image','recipe' as 'type' from tbl_recipe where recStatus = 1 and (recTitleEng like '%".$keyword."%' or recTitleDutch like '%".$keyword."%')) as u";
			$secQuery = $sreachQry;
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
			$sreachQry = $sreachQry. " limit ".$offset.",".$limit;
			
			$data = $this->db->query($sreachQry)->result();
			$searchResArr = array();
			if (!empty($data)) {
				foreach ($data as $key => $val) {
					$inner = array();
					$inner['id'] = $val->id;
					$inner['titleEng'] = $val->titleEng;
					$inner['titleDutch'] = $val->titleDutch;
					$image = $val->image;
					if (trim($image) != "") {
						$inner['image'] =  SITE_URL."bbq_images/".$val->image;
					} else {
						$inner['image'] = '';
					}
					$inner['type'] = $val->type;
					array_push($searchResArr,$inner);
				}
			}
			
			$SecData = $this->db->query($secQuery)->result();
			$totalRecords =  count($SecData);
			
			if (!empty($data) ) {
				$response = [
					'status' => true,
					'data' => $searchResArr,
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
				'message' => 'Keyword is not define.'
			], REST_Controller::HTTP_OK);// OK (200) being the HTTP response code
		}
	}
}
