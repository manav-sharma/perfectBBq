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
class Page extends REST_Controller 
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
	 * @desc Function used to get page detail.
	 * @param id (int)
	 **/
    public function pageDetail_get() 
    { 
		$pageId = $this->get('id');
		if (isset($pageId) && $pageId != "") {
			$sql = "select cmsid,cmsTitleEng,cmsTitleDutch,cmsContentEng,cmsContentDutch from tbl_cms where cmsStatus = '1' and cmsId = ".$pageId;
			$pgData = $this->db->query($sql)->row();
			if (!empty($pgData) ) {
				$pageData = array();
				$pageData['cmsId'] = $pgData->cmsid;
				$pageData['bbqTitleEng'] = $pgData->cmsTitleEng;
				$pageData['bbqTitleDutch'] = $pgData->cmsTitleDutch;
				$pageData['bbqLongDescEng'] = $pgData->cmsContentEng;
				$pageData['bbqLongDescDutch'] = $pgData->cmsContentDutch;
				
				$response = [
					'status' => true,
					'data' => $pageData,
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
