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
class Ads extends REST_Controller {
    
    const ureapEmail = 'ureap@noreply.com';
    const limitPerPage = 10;

    function __construct() {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function ads_get() {
        
        $id = $this->get('id');

        // If the id parameter doesn't exist return all the users
        if (empty($id) ) {
            // Check if the users data store contains users (in case the database result returns NULL)
            
            ###PAGINATION CODE##########CALCULATE OFFSET AND LIMIT#######STARTS#####################
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
            ###PAGINATION CODE##########CALCULATE OFFSET AND LIMIT#######ENDS#######################
            
            $data = $this->db
                ->get_where('ads', ['status' => '1'], $limit, $offset)
                ->result();#result_array gets the results as array            
            
            ##GET TOTAL COUNT#####################################
            $this->db->where('status', '1');
            $this->db->from('ads');
            $totalRecords =  $this->db->count_all_results();       
            
            
            if (!empty($data) ) {

                $dataArr	=	array();
                foreach($data as $key=>$item) {
					if(isset($item->element)) {
						$item->element	=	unserialize($item->element);
					}
					$dataArr[$key] = $item;
				}
                
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
                        'message' => 'No users were found'
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
        
        
        

        // Find and return a single record for a particular user.

        $id = (int) $id;

        // Validate the id.
        if ($id <= 0) {
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // Get the user from the array, using the id as key for retreival.
        // Usually a model is to be used for this.
             
        $userData = $this->db
            ->get_where('ads', ['ad_id' => $id, 'status' => '1'] )
            ->row();
 
		if(isset($userData->element) && !empty($userData->element)) {
			$userData->element	=	unserialize($userData->element);
		}
			       
        if (!empty($userData)) {
            $this->set_response($userData, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                    'status' => false,
                    'message' => 'User could not be found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }
    
    /**
     * Credits ad credit point to user afer veiwing ad
     * @param $_POST['status'] ignored|watch_later|viewed
     * @return mixed user_id|status
     */
    public function awardCredits_post()
    {
        $adId = $this->post('ad_id');
        $userId = $this->post('user_id');
        $status = $this->post('status');
        
        if (!empty($adId) && !empty($userId) && !empty($status) )
        {
            if ($this->checkValidAd($this->post('ad_id')) ) 
            {                
                if ($this->checkValidAppuser($this->post('user_id')) ) 
                {
                    ###CHECK VALID STATUS VALUE###########
                    if(!in_array($this->post('status'), ['ignored', 'watch_later', 'viewed']))
                    {
                        $this->response([
                                'ad_id' => $this->post('ad_id'), 
                                'status' => false, 
                                'message' => 'Invalid Ad Status!!!'
                            ], REST_Controller::HTTP_NOT_ACCEPTABLE);
                    }           
                    
                    ###CHECK IF AD VIEWED ALREADY AND CREDITED####################################
                    if($this->checkUserAdStatusViewed($this->post('user_id'), $this->post('ad_id') ) )
                    {
                        $this->response([
                                'ad_id' => $this->post('ad_id'), 
                                'status' => false, 
                                'message' => 'Ad already viewed!!!'
                            ], REST_Controller::HTTP_NOT_ACCEPTABLE);
                    }                     
                    
                    
                    ###CHECK IF USER CREDITS LEFT > PRODUCT CREDITS REQUIRED##############
                    $userCreditsLeft = $this->getUserCreditsLeft($this->post('user_id') );
                    $adCredits = (int)$this->getAdCredits($this->post('ad_id') );
                                            
                    
                    ###TRANSACTION STARTS###################################################################
                    $this->db->trans_start();##pass true to test rollback
                                        
                    ###CHECK IF USER_ADS EXISTS#####################################
                    $userAdData = array(
                        'user_id' => $this->post('user_id'),
                        'ad_id' => $this->post('ad_id'),
                        'status' => $this->post('status'),
                        'credit_points' => $adCredits
                    );
                    
                    if($this->checkUserAdExists($this->post('user_id'), $this->post('ad_id'))) ###UPDATE
                    {
                        $this->db->where([
                                'user_id' => $this->post('user_id'), 
                                'ad_id'=>$this->post('ad_id')
                            ]);
                        $this->db->update('user_ads', $userAdData);
                    }
                    else #INSERT
                    {
                        $this->db->insert('user_ads', $userAdData);  
                    }                                         
                                            
                    #########UPDATE USER CREDITS LEFT IF status = 'viewed'################################
                    if($this->post('status') =='viewed')
                    {

                        $creditsLeft = $userCreditsLeft + $adCredits;
                        $userCreditData = array(
                            'credits_left' => $creditsLeft,
                        );                        

                        $this->db->where(array('user_id' => $this->post('user_id')));
                        $this->db->update('user', $userCreditData);
                    }

                    $this->db->trans_complete();                        
                    ##HANDLE FAILED TRANSACTION####################################################
                    if ($this->db->trans_status() === false)
                    {
                        #generate an error... or use the log_message() function to log your error
                        $this->response([
                                'user_id' => $this->post('user_id'), 
                                'ad_id' => $this->post('ad_id'), 
                                'status' => false, 
                                'message' => 'Credit Award Unsuccessful(Failed transaction)!!!'
                            ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                    ###TRANSACTION#######################################################ENDS############

                    return $this->response(
                        [
                            'ad_id' => $this->post('ad_id')
                        ], REST_Controller::HTTP_CREATED); // OK (200) being the HTTP response code
                }
                else 
                {
                    $this->response(['user_id' => 0, 'status' => false, 'message' => 'Invalid User!!!'], REST_Controller::HTTP_NOT_ACCEPTABLE);
                }                
            } 
            else 
            {
                $this->response(['ad_id' => 0, 'status' => false, 'message' => 'Invalid Ad!!!'], REST_Controller::HTTP_NOT_ACCEPTABLE);
            }
        }
        else 
        {
            $this->response([
                    'user_id' => $this->post('user_id'), 
                    'ad_id' => $this->post('ad_id'), 
                    'status' => $this->post('status'), 
                    'message' => 'Credit Award Unsuccessful!!!'
                ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    
    
    private function getAdCredits($productId)
    {
        $this->load->database();
        $row = $this->db
            ->get_where('ads', array('ad_id' => $productId, 'status' => '1') )
            ->result_array();
        return $row[0]['ad_credits'];
        
    }
    
    private function getUserCreditsLeft($userId)
    {
        $this->load->database();
        $row = $this->db
            ->get_where('user', array('user_id' => $userId, 'status' => '1') )
            ->result_array();
        return $row[0]['credits_left'];
    }
    
    /**
     * Check if product is valid and enabled(status1)
     * @param int $productId
     * @return bool true if valid product else false
     */
    private function checkValidAd($productId)
    {
        $this->load->database();
        $row = $this->db
            ->get_where('ads', array('ad_id' => $productId, 'status' => '1') )
            ->row();
        
        if (count($row) == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Check if user is enabled and valid user
     * @param int $userId
     * @return boolean true if user status =1 and valid
     */
    private function checkValidAppuser($userId)
    {
        $this->load->database();
        $row = $this->db
            ->get_where('user', array('user_id' => $userId, 'status' => '1') )
            ->row();
        
        if (count($row) == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Check if user_ads have row against user_id and ad_id
     * @param int $userId
     * @param int $adId
     * @return boolean true if row exists
     */
    private function checkUserAdExists($userId, $adId)
    {
        $this->load->database();
        $row = $this->db
            ->get_where('user_ads', array('user_id' => $userId, 'ad_id' => $adId) )
            ->row();
        
        if (count($row) == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Check if user_ads have row against user_id and ad_id and status='viewed'
     * @param int $userId
     * @param int $adId
     * @return boolean true if row exists
     */
    private function checkUserAdStatusViewed($userId, $adId)
    {
        $this->load->database();
        $row = $this->db
            ->get_where('user_ads', array('user_id' => $userId, 'ad_id' => $adId, 'status' => 'viewed') )
            ->row();
        
        if (count($row) == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Function will return all active user ads by userid
     * @param int $userId
     * @param int $adId
     * @return json data
     */
    public function userAds_get() {
        
        $user_id = $this->get('userid');
        $nogallery  = $this->get('instance');
        $id = (int) $user_id;

        // Validate the id.
        if ($id <= 0) {
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        
        if (!empty($user_id) ) {
          
            ###PAGINATION CODE##########CALCULATE OFFSET AND LIMIT#######STARTS#####################
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
            ###PAGINATION CODE##########CALCULATE OFFSET AND LIMIT#######ENDS#######################
            if($nogallery) {
               $SelectStr = 'ad_id,ad_title, expired_at, instance_type, ad_main_image';
               $Where = ['status' => '1', 'user_id'=>$user_id,'instance_type !='=>1];
            } else {
                $SelectStr = 'ad_id,ad_title, ad_description, creditBalance, points_per_user, expired_at, instance_type, isFeatured, ad_main_image, element';      $Where = ['status' => '1', 'user_id'=>$user_id];
            }
            
            $data = $this->db->select($SelectStr)
                ->get_where('ads', $Where, $limit, $offset)
                ->result_array();#result_array gets the results as array            
         
            ##GET TOTAL COUNT#####################################
            $this->db->where($Where);
            $this->db->from('ads');
            $totalRecords =  $this->db->count_all_results();       
            
            
            if (!empty($data) ) {

                $dataArr =  array();
               /* foreach($data as $key=>$item) 
                {
                    if(isset($item->element)) {
                        $item->element = json_decode($item->element);
                    }
                    $dataArr[$key] = $item;
                }
                */
                $baseUrl = $this->config->base_url();
                
                $response = [
                    'status' => true,
                    'base_url' =>$baseUrl.'ad_elements/',
                    'data' => $data,
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
                    ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
        
    }
    
    
    
}
