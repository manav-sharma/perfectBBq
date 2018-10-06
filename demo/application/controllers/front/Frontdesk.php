<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
	
class Frontdesk extends MY_Controller {
	
	/**
	* @ Function Name		: __construct
	* @ Function Params	: 
	* @ Function Purpose 	: initilizing variable and providing pre functionalities
	* @ Function Returns	: 
	*/
	public function __construct() {
		parent::__construct();
	}
	
	
	/*******************Push Notifications Function********************/ 
	function send_push_notification($registatoin_ids, $message) {
		// Google Cloud Messaging API Key
		// Place your Google API Key
		define("GOOGLE_API_KEY", "AIzaSyA81yS1VSj1WZXY_T"); 
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        //print_r($headers);
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        // Close connection
        curl_close($ch);
        echo $result;
    }
	
	
	
	
	var $skey 	= "SuPerEncKey2016ByAbhi"; // you can change it
    public function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
	public function decode($value){
		
        if(!$value){return false;}
        $crypttext = $this->safe_b64decode($value); 
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }
	
	
	

	public function approveBidProposal($bidId,$hotelId){		
		        $bidId = $this->decode($bidId);
				$hotelId = $this->decode($hotelId);
				$user = $this->input->get('userId', TRUE);
				$gcm = $this->input->get('gcmId', TRUE);
				//if(isset($user) && !empty($user)){
				 $userId = $this->decode($user);
				
				//}else{
				 $gcmId = $this->decode($gcm);
				
				//}
		        $this->db->select('dateCreated,linkStatus');
				
              $this->db->where("(userId = '$userId' OR gcmId = '$gcmId') AND bidId = $bidId AND hotelId = $hotelId");
				/*if(isset($userId) && !empty($userId)){
					 $findBidQueryFetch = $this->db->get_where('tb_bidStatus', array('bidId' => $bidId,'hotelId' => $hotelId,'userId' => $userId));
					}else{
						 $findBidQueryFetch = $this->db->get_where('tb_bidStatus', array('bidId' => $bidId,'hotelId' => $hotelId,'gcmId' => $gcmId));
					}            
				*/
				$findBidQueryFetch = $this->db->get('tb_bidStatus');
			    $findBidQuery = $findBidQueryFetch->result_array();
			
			    If(!empty($findBidQuery)){					
				    if($findBidQuery[0]['linkStatus']=='Expire'){
					echo "Your link has been expired.As You already approve or reject the bid";
					exit;
				    }
				$currentdatetimeNotifications = date('Y-m-d G:i:s');	
			    $currentDateTime = strtotime(date('Y-m-d G:i:s'));
			    $biddingDateTime = strtotime($findBidQuery[0]['dateCreated']);
			    $expirationCalculation = round(($currentDateTime - $biddingDateTime)/3600,2);
			    if($expirationCalculation > 1){
					$dataUpdation = array('linkStatus' => 'Expire');
					$this->db->where("(userId = '$userId' OR gcmId = '$gcmId') AND bidId = $bidId AND hotelId = $hotelId");
					$this->db->update('tb_bidStatus',$dataUpdation);
					echo "Your link has been expired.However,This link was valid only for one hour.";
					exit;
				} else {
					       $notificationData = array(
						   'userId' => $userId,
						   'gcmId'  => $gcmId,
						   'bidId' => $bidId,
						   'hotelId' => $hotelId,
						   'status' => 'Approve',
						   'date' => $currentdatetimeNotifications
						   );
							$this->db->insert('tb_Notifications', $notificationData);
							//$bid_last_insert_id = $this->db->insert_id();
							
							$dataUpdation = array('linkStatus' => 'Expire');
							$this->db->where("(userId = '$userId' OR gcmId = '$gcmId') AND bidId = $bidId AND hotelId = $hotelId");
							$this->db->update('tb_bidStatus',$dataUpdation);
							
							/*************Start Push Notifications Call*******************/
							/*$this->db->select('gcm_apns_Id,deviceType');
							$findGCM_APNS_ID = $this->db->get_where('tb_pushNotifications', array('userId' => $userId));
							$sendNotificationsId = $findGCM_APNS_ID->result_array();
							if(!empty($sendNotificationsId)){
								$deviceId = $sendNotificationsId[0]['gcm_apns_Id'];
							    $deviceType = $sendNotificationsId[0]['deviceType'];
							    if($deviceType == 'android'){
									$composemessage = 'New Hotel bid response arrived';
									send_push_notification($deviceId,$composemessage);
								} elseif($deviceType == 'ios'){
									echo "Working Pending for IOS notifications";
									exit;
								} else{
									echo "For blackberry or windows";
									exit;
								}
							}*/
							/*************End Push Notifications Call*******************/
							echo "Thank you for your approval. Your response has been stored and the user will be notified soon.";
							exit;
					}
				} else {
					
					echo "Some thing went wrong with your Url";
					exit;
					
					
				}
	}
	
	public function rejectBidProposal($bidId,$hotelId){
		    $bidId = $this->decode($bidId); 
			$hotelId = $this->decode($hotelId); 
			$user = $this->input->get('userId', TRUE);
			$gcm = $this->input->get('gcmId', TRUE);
				//if(isset($user) && !empty($user)){
			$userId = $this->decode($user);
			
				//}else{
			 $gcmId = $this->decode($gcm);	
		
		       $this->db->select('dateCreated,linkStatus');
				
              $this->db->where("(userId = '$userId' OR gcmId = '$gcmId') AND bidId = $bidId AND hotelId = $hotelId");
				/*if(isset($userId) && !empty($userId)){
					 $findBidQueryFetch = $this->db->get_where('tb_bidStatus', array('bidId' => $bidId,'hotelId' => $hotelId,'userId' => $userId));
					}else{
						 $findBidQueryFetch = $this->db->get_where('tb_bidStatus', array('bidId' => $bidId,'hotelId' => $hotelId,'gcmId' => $gcmId));
					}            
				*/
				$findBidQueryFetch = $this->db->get('tb_bidStatus');
			    $findBidQuery = $findBidQueryFetch->result_array();
			  
			    If(!empty($findBidQuery)){
					
					if($findBidQuery[0]['linkStatus']=='Expire'){
					echo "Your link has been expired.As You already approve or reject the bid";
					exit;
				    }
				$currentdatetimeNotifications = date('Y-m-d G:i:s');
			    $currentDateTime = strtotime(date('Y-m-d G:i:s'));
			    $biddingDateTime = strtotime($findBidQuery[0]['dateCreated']);
			    $expirationCalculation = round(($currentDateTime - $biddingDateTime)/3600,2);
			    if($expirationCalculation > 1){
					$dataUpdation = array('linkstatus' => 'Expire');
					$this->db->where("(userId = '$userId' OR gcmId = '$gcmId') AND bidId = $bidId AND hotelId = $hotelId");
					$this->db->update('tb_bidStatus',$dataUpdation);
					echo "Your link has been expired.However,This link was valid only for one hour.";
					exit;
				} else {
					       $notificationData = array(
						   'userId' => $userId,
						   'gcmId'  => $gcmId,
						   'bidId' => $bidId,
						   'hotelId' => $hotelId,
						   'status' => 'Deny',
						   'date' => $currentdatetimeNotifications
						   );
							$this->db->insert('tb_Notifications', $notificationData);
							$dataUpdation = array('linkStatus' => 'Expire');
							$this->db->where("(userId = '$userId' OR gcmId = '$gcmId') AND bidId = $bidId AND hotelId = $hotelId");
							$this->db->update('tb_bidStatus',$dataUpdation);
							echo "Thank you ..Your rejection has been stored and will automatically notified the user soon.";
							exit;
					}
				} else {
					
					echo "Some thing went wrong with your Url";
					exit;
					
					
					 }
	}

}

/* End of file users.php */
/* Location: ./application/controllers/users.php */
