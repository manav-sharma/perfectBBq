<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Users extends REST_Controller {

    function __construct() {
        parent::__construct();
        header("Content-Type: application/json; charset=UTF-8");
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

        $this->load->helper(array('email'));
        $this->methods['user_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    var $skey = "SuPerEncKey2016ByAmrit"; // you can change it

    public function safe_b64encode($string) {

        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public function encode($value) {

        if (!$value) {
            return false;
        }
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext));
    }

    public function checkversion_post() {
        $appVersion = $this->post('appVersion');
        $webservicesappVersion = '1.0';
        if (($appVersion) != ($webservicesappVersion)) {
            $this->response(['status' => FALSE, 'message' => 'Please update
		  your application'], REST_Controller::HTTP_ACCEPTED);
        } else {

            $this->response(['status' => TRUE], REST_Controller::HTTP_ACCEPTED);
        }
    }

    private function checkEmailExistance($email, $userId = 0) {
      
        $where = array('usrEmail'=>$email);
        if(!empty($userId))
        $where = array('usrEmail'=>$email,'id !='=>$userId);        
        $row = $this->db->get_where('tbl_users', $where)->row();     
        if (count($row) == 1) {
            return 0;
        } else {

            return 1;
        }
    }
	
	  private function checkPhoneExistance($phone, $userId = 0) {
      
        $where = array('usrPhone'=>$phone);
        if(!empty($userId))
        $where = array('usrPhone'=>$phone,'id !='=>$userId);        
        $row = $this->db->get_where('tbl_users', $where)->row();     
        if (count($row) == 1) {
            return 0;
        } else {

            return 1;
        }
    }
	
	private function checkUsernameExistance($username)
    {
        $row = $this->db
            ->get_where('tbl_users', array('usrUsername' => $username) )
            ->row();        
        if (count($row) == 1) {
            return 0;
        } else {
            return 1;
        }
    }
	private function checkValidPhone($phone)
    {
		$returnVal = 1;
		
		if ( !preg_match("#^\+[1-9]\d{2}[-\s]?[0-9]+\d{8,15}$#", trim($phone)) ) {
		$returnVal = 0;
	}
	return $returnVal;
		
	}	
    
   private function checkfbidExistance($fbId){
     $row = $this->db
            ->get_where('tbl_users', array('usrFacebookId' => $fbId) )
            ->row();        
        if (count($row) == 1) {
            return 0;
        } else {
            return 1;
        }
   }
   
  function password_strength($password, $password_length) {
	$returnVal = True;
	if ( strlen($password) < $password_length ) {
		$returnVal = False;
	}
	if ( !preg_match("#[0-9]+#", $password) ) {
		$returnVal = False;
	}
	if ( !preg_match("#[a-z]+#", $password) ) {
		$returnVal = False;
	}
	if ( !preg_match("#[A-Z]+#", $password) ) {
		$returnVal = False;
	}
	if ( !preg_match("/[\'^£$%&*()}{@#~?><>,|=_+!-]/", $password) ) {
		$returnVal = False;
	}
	return $returnVal;
}

	/**
     * get User by Id(:UserId)
     * @param int $userId
     * @return array of user data
     */
    private function getNonPasswordUserById($userId)
    {
        $this->load->database();
        $row = $this->db
            ->get_where('tbl_users', array('id' => $userId, 'password_assigned' => '0') )
            ->row_array();
        
        return $row;
    }

    function uploadAppUserpic() {

        if (isset($_FILES['user_image']) && !empty($_FILES['user_image'])) {

            $temp = explode(".", $_FILES["user_image"]["name"]);
            $extension = end($temp);
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            if(!in_array($extension,$allowedExts) ) {			
				 $arr = array(
                    'error' => 'Only image files gif,jpeg,jpg,png supported.',
                );
                return $arr;
			}
            $config['upload_path'] = IMAGES_UPLOADS_PATH.'profile/';
            $config['overwrite'] = TRUE;
            $config['file_name'] = $_FILES["user_image"]["name"];
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);			
            if ($this->upload->do_upload('user_image')) {
				$upload_data = $this->upload->data();
                $appUserImagePath = array(
                    'image_name' => isset($upload_data['file_name']) ? $upload_data['file_name'] : '',
                );
                return $appUserImagePath;
            } else {
				return false;
            }
        }
    }

    private function generateRandomString($length = 10) {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%&' . strtotime(date("Y-m-d G:i:s"));
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
	
	/**
     * Generate Otp
     * @param string phone_number 
     */
   
	public function generateOtp_post() {
		  $phone = $this->post('phone_number');
		   //$valid_phone = $this->checkValidPhone($phone);		  
		 if ($phone != '') {			
			$otp = 	mt_rand(1000, 9999);	
			$result = $this->db->get_where('tbl_otp',  array('phone' => $phone ))->row();
			if($result->status == 1){
				 $this->response(['status' => FALSE, 'message' => 'Phone number is already registered.'], REST_Controller::HTTP_ACCEPTED);
			}			
			if($result->id){
					$otpData = array(
                        'otp' => $otp,
                        'phone' => $phone,
                        'created_at' => date('Y-m-d G:i:s')
                    );
					$this->db->where(array('id' => $result->id));
                    $add = $this->db->update('tbl_otp', $otpData);	
			}else{			
			$otpData = array(
                        'otp' => $otp,
                        'phone' => $phone,
                        'created_at' => date('Y-m-d G:i:s')
                    );
                  $add = $this->db->insert('tbl_otp', $otpData);			
			}			
		  if(!empty($add))
		  {
			
			  // Your Account SID and Auth Token from twilio.com/console
     /*   $sid = 'AC6b62e097a758dbf670f40ac0ae1ca21b';
        $token = '975ed8c32fdbe34ed1a78af10e607918';
        $fromNumber = '+15005550006';*/
        
        ###LIVE CREDS##################################
        $sid = 'AC56c291f8d3ce5581b5bba67670ed7c90';
        $token = '0aad666eddc0662ac13eda71726ce296';
        $fromNumber = '+17653924059';
        
        $client = new Client($sid, $token);        
        
        // Use the client to do fun stuff like send text messages!
        $client->messages->create(
            // the number you'd like to send the message to
            #'+15558675309',
            "$phone",
            array(
                // A Twilio phone number you purchased at twilio.com/console###MY API PHONE NUMBER : +12568184093
                'from' => $fromNumber,
                // the body of the text message you'd like to send
                'body' => 'Hi, Your OTP is '.$otp
            )
        );
		
		  $this->response(['status' => TRUE, 'message' => 'Otp has been sent to your registered phone number.', 'otp' => $otp], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		  }
          else
		  $this->response(['status' => FALSE, 'message' => 'There is some problem with data, please try again.'], REST_Controller::HTTP_ACCEPTED);
		} else {
          $this->response(['status' => FALSE, 'message' => 'There is some problem with data, please try again.'], REST_Controller::HTTP_ACCEPTED);
        }	
	}
	
	/**
     * Register using phone number
     * @param  phone_number,otp,user_name
     */

	public function registerPhone_post() {

			 $phone = $this->post('phone_number');
			 $otp = (int)$this->post('otp');
			 $user_name = $this->post('user_name');
             $device_id = $this->post('device_id');
             $device_type = $this->post('device_type');
             $password = $this->post('password');
			 $unique_username = $this->checkUsernameExistance($user_name);
			 $unique_phone = $this->checkPhoneExistance($phone);
			if($phone == '' ||  $otp== '' ||  $user_name== ''){
				$this->response(['status' => FALSE, 'message' => 'Send valid parameters.'], REST_Controller::HTTP_ACCEPTED);
			 }
			 
			if(!$unique_phone){
				$this->response(['status' => FALSE, 'message' => 'Phone number already in use, please try another.'], REST_Controller::HTTP_ACCEPTED);
			 }
			 
			 if(!$unique_username){
				$this->response(['status' => FALSE, 'message' => 'User name already in use, please try another.'], REST_Controller::HTTP_ACCEPTED);
			 }
			 
			 $result = $this->db->get_where('tbl_otp',  array('phone' => $phone, 'otp' => $otp, 'status' => '0' ))->row();
			
			 if (!empty($result)) {			
			    $otpData = array( 'status' => '1');
				$this->db->where(array('id' => $result->id));
                $add = $this->db->update('tbl_otp', $otpData);
				$this->load->library('Cencryption');
				$userData = array(
                        'usrUsername' => $user_name,
                        'registered_via' => '3',
                        'usrEmail' => '',
                        'usrPassword' => $this->cencryption->encryptText($password),
			'usrPhone' => $phone,
                        'gcm_reg_id' => $device_id,
                        'device_type' => $device_type,
                        'usrStatus' => '1',
                        'usrDateCreated' => date('Y-m-d G:i:s')
                    );
                  $this->db->insert('tbl_users', $userData);
				  
				$user_id = $this->db->insert_id();
				if($user_id)
				$this->response(['status' => TRUE, 'user_id' => $user_id, 'message' => 'User registered successfully.'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				else 
          $this->response(['status' => FALSE, 'message' => 'There is some problem during registration, please try again.'], REST_Controller::HTTP_ACCEPTED);
		  
		 }else {
          $this->response(['status' => FALSE, 'message' => 'There is some problem with data, please try again.'], REST_Controller::HTTP_ACCEPTED);
        }	
			
	}
		
	/**
     * Register using email
     * @param  email_address,user_name
     */

	public function registerEmail_post() 
	{
			 $email = $this->post('email_address');
			 $user_name = $this->post('user_name');
             $device_id = $this->post('device_id');
             $device_type = $this->post('device_type');
             $password = $this->post('password');
			 $unique_username = $this->checkUsernameExistance($user_name);
			 $unique_email = $this->checkEmailExistance($email);
			 if($email == '' || $user_name == ''){
				$this->response(['status' => FALSE, 'message' => 'No a valid Email or User Name.'], REST_Controller::HTTP_ACCEPTED);
			 }
			   //CHECK FOR VALID EMAIL
            elseif (!filter_var($this->post('email_address'), FILTER_VALIDATE_EMAIL) ) {
                $this->response(['status' => false, 'message' => 'Registration Unsuccessfull!!! Invalid email.'], REST_Controller::HTTP_ACCEPTED);
            }
			 elseif(!$unique_username ){
				$this->response(['status' => FALSE, 'message' => 'User name already in use, please try another.'], REST_Controller::HTTP_ACCEPTED);
			 }
			elseif(!$unique_email){
				$this->response(['status' => FALSE, 'message' => 'Email already in use, Send valid Email.'], REST_Controller::HTTP_ACCEPTED);
			 }			 
			 else {
				$userData = array(
                        'usrUsername' => $user_name,
                        'registered_via' => '1',
						'usrEmail' => $email,
                        'usrPassword' => $this->cencryption->encryptText($password),						
                        'gcm_reg_id' => $device_id,
                        'device_type' => $device_type,
                        'usrPhone' => '',
                        'usrStatus' => '1',
                        'usrDateCreated' => date('Y-m-d G:i:s')
                    );
                  $this->db->insert('tbl_users', $userData);
				  
				$user_id = $this->db->insert_id();
				if($user_id)
				$this->response(['status' => TRUE, 'user_id' => $user_id, 'message' => 'User registered successfully.'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				
			else 
          $this->response(['status' => FALSE, 'message' => 'There is some problem with data, please try again.'], REST_Controller::HTTP_ACCEPTED);    
				
			 }
	}
	/**
     * Set password
     * @param  user_id,password
     * */
    public function setPassword_post() {        
			 $user_id = $this->post('user_id');
			 $password = $this->post('password');
			if (!empty($user_id) && !empty($password) )
			{
            ###CHECK PASSWORD STRENGTH############################################
            $checkValidPassword = $this->password_strength($password, 6);
            if ( empty($checkValidPassword) )  {
                $this->response(
                    [
                        'status' => false, 
                        'message' => 'Password should consist of minimum 6 characters with one uppercase, one special character and one number.'
                    ], REST_Controller::HTTP_ACCEPTED);
            }            
            
            $user = $this->getNonPasswordUserById($user_id);
            
            ####CHECK VALID USER
            if(empty($user))
            {
                return $this->response(['status' => false, 'message' => 'Invalid User!!!'], REST_Controller::HTTP_ACCEPTED);
            }            
            
            $userStatus = $user['usrStatus'];
            if ($userStatus == '0') {
                $this->response(['status' => false, 'message' => 'You can not update your account as it has been blocked or Inactivated'], REST_Controller::HTTP_ACCEPTED);
            }         
            
            if (!empty($password) ) {
                $data['usrPassword'] = md5($password);
            }
            
                $data['password_assigned'] = '1';
            $this->db->where(array('id' => $user_id));
            $this->db->update('tbl_users', $data);
            $this->response(['status' => true, 'message' => 'Password has been set successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } 
        else
        {
            $this->response(['status' => false, 'message' => 'You are not authorize to update user detail'], REST_Controller::HTTP_ACCEPTED);
        }
	}
	*/
	
    /**
     * Register using fb api
     * @param  email_address,user_name
     */

	public function registerFbUser_post() {
			 $email = $this->post('email_address');
             $phone = $this->post('phone_number');
             $device_id = $this->post('device_id');
             $device_type = $this->post('device_type');
			 $fbId = $this->post('fb_id');             
			 $exist_fbid = $this->checkfbidExistance($fbId);
             $unique_email = $this->checkEmailExistance($email);
			 $unique_phone = $this->checkPhoneExistance($phone);
			 if($fbId == '' ){
				$this->response(['status' => FALSE, 'message' => 'No a valid Facebook Id.'], REST_Controller::HTTP_ACCEPTED);
			 }
			 elseif(!$exist_fbid ){
				$this->response(['status' => FALSE, 'message' => 'Facebook id already registered.'], REST_Controller::HTTP_ACCEPTED);
			 }
			 	   //CHECK FOR VALID EMAIL
            elseif (!filter_var($this->post('email_address'), FILTER_VALIDATE_EMAIL) ) {
                $this->response(['status' => false, 'message' => 'Registration Unsuccessfull!!! Invalid email.'], REST_Controller::HTTP_ACCEPTED);
            }
			elseif(!$unique_email){
				$this->response(['status' => FALSE, 'message' => 'Email already in use, please try another.'], REST_Controller::HTTP_ACCEPTED);
			 }
			elseif(!$unique_phone){
				$this->response(['status' => FALSE, 'message' => 'Phone number already in use, please try another.'], REST_Controller::HTTP_ACCEPTED);
			 }
			 else {
				$userData = array(
                        'usrUsername' => '',
                        'registered_via' => '2',
						'usrEmail' => $email,
                        'usrPhone' => $phone,
                        'gcm_reg_id' => $device_id,
                        'device_type' => $device_type,
                        'usrFacebookId' => $fbId,
                        'usrStatus' => '1',
                        'usrDateCreated' => date('Y-m-d G:i:s')
                    );
                  $this->db->insert('tbl_users', $userData);				  
				$user_id = $this->db->insert_id();
				if($user_id)
				$this->response(['status' => TRUE, 'user_id' => $user_id, 'message' => 'User registered successfully.'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				
			else 
          $this->response(['status' => FALSE, 'message' => 'There is some problem with data, please try again.'], REST_Controller::HTTP_ACCEPTED);    
				
			 }
	}
    
    /**
     * Login user
     * @param  email_address,phone_number,password
     */

    public function login_post()
    {
           $email = $this->post('email_address');
           $phone = $this->post('phone_number');            
           $password = $this->post('password');
           $device_id = $this->post('device_id');
           $device_type = $this->post('device_type');
		   $this->load->library('Cencryption');
		
            ##############FOR APP LOGIN WE USE 'email' POST FIELD instead of usrEmail################################
            if (!empty($email) &&  !empty($password))
            {
                  	   //CHECK FOR VALID EMAIL
           if ( !filter_var($this->post('email_address'), FILTER_VALIDATE_EMAIL) ) {
                $this->response(['status' => false, 'message' => 'Registration Unsuccessfull!!! Invalid email.'], REST_Controller::HTTP_ACCEPTED);
            } 
                $password = $this->cencryption->encryptText($password);
                
                $query = "select * from tbl_users where usrEmail = ? and usrPassword = ? and registered_via = '1'";
                $row = $this->db->query($query,  array($email, $password))->row();                
              
                if (count($row) == 1) {
                    if ($row->usrStatus == '0') { //Check for Account is Active Or Inactive
                        $this->response(['status' => false, 'message' => 'Your Account is Inactive or Blocked'], REST_Controller::HTTP_ACCEPTED);
                    }
                    
                    $updateData = array(                       
                        'gcm_reg_id' => $device_id,
                        'device_type' => $device_type                       
                    );                  
                    $this->db->where(array('id' => $row->id));
                    $action =  $this->db->update('tbl_users', $updateData);
                    
                    $loggedInuserId = $row->id; 
                    $image_base_path = IMAGES_SHOW_PATH."profile/";
                    $data['user_id'] = $row->id;                    
                    $data['name'] = $row->usrName;
                    $data['email_address'] = $row->usrEmail;
                    $data['username'] = $row->usrUsername;
                    $data['profile_pic'] = $row->profile_pic;                   
                    $data['gender'] = $row->usrGender;
                    $data['dob'] = $row->dob;
                    $data['phone_number'] = $row->usrPhone;
                    $data['registered_via'] = $row->registered_via;
                    $data['fb_id'] = $row->usrFacebookId;
                    $data['status'] = $row->usrStatus;

                    $this->response(['status' => true, 'data' => $data, 'image_base' => $image_base_path], REST_Controller::HTTP_ACCEPTED); // OK (202) being the HTTP response code The server successfully accepted a new resource
                }
                else 
                {
                    $this->response(['status' => false, 'message' => 'Either Email or Password is wrong. Please try again.'], REST_Controller::HTTP_ACCEPTED);
                }
            }
             ##############FOR APP LOGIN WE USE 'usrPhone' POST FIELD instead of usrPhone################################
            elseif (!empty($phone) && !empty($password) ){
                $password = $this->cencryption->encryptText($password); 
                $query = "select * from tbl_users where usrPhone = ? and usrPassword = ? and registered_via = '3'";

                $row = $this->db->query($query,  array($phone, $password))->row();                
                
                if (count($row) == 1) {
                    if ($row->usrStatus == '0') { //Check for Account is Active Or Inactive
                        $this->response(['status' => false, 'message' => 'Your Account is Inactive or Blocked'], REST_Controller::HTTP_ACCEPTED);
                    }
                    $updateData = array(                       
                        'gcm_reg_id' => $device_id,
                        'device_type' => $device_type                       
                    );                  
                    $this->db->where(array('id' => $row->id));
                    $action =  $this->db->update('tbl_users', $updateData);
                    
                    $loggedInuserId = $row->id; 
                    $image_base_path = IMAGES_SHOW_PATH."profile/";
                    $data['user_id'] = $row->id;                    
                    $data['name'] = $row->usrName;
                    $data['email_address'] = $row->usrEmail;
                    $data['username'] = $row->usrUsername;
                    $data['profile_pic'] = $row->profile_pic;                   
                    $data['gender'] = $row->usrGender;
                    $data['dob'] = $row->dob;
                    $data['phone_number'] = $row->usrPhone;
                    $data['registered_via'] = $row->registered_via;
                    $data['fb_id'] = $row->usrFacebookId;
                    $data['status'] = $row->usrStatus;
                    
                    $this->response(['status' => true, 'data' => $data, 'image_base' => $image_base_path], REST_Controller::HTTP_ACCEPTED); // OK (202) being the HTTP response code The server successfully accepted a new resource
                }
                else 
                {
                    $this->response(['status' => false, 'message' => 'Either Phone Number or Password is wrong. Please try again.'], REST_Controller::HTTP_ACCEPTED);
                }
            }
            else
            {
                $this->response(['status' => false, 'message' => 'Login Unsuccessful'], REST_Controller::HTTP_ACCEPTED);
            }
     }
     
    /**
     * Login facebook user
     * @param  fb_id
     */

    public function fbLogin_post()
    {
            ##############FOR APP LOGIN WE USE 'fb_id' POST FIELD ################################
            if($this->post('fb_id')) {
                $socialId = $this->post('fb_id');               
            }            
            if (!empty($socialId)) 
            {
                $query = "select * from tbl_users where usrFacebookId = ? and registered_via = '2'";

                $row = $this->db->query($query, 
                        array(
                            $socialId
                        )
                    )->row();                
                
                if (count($row) == 1) {
                    if ($row->usrStatus == '0') { //Check for Account is Active Or Inactive
                        $this->response(['user_id' => 0, 'status' => false, 'message' => 'Your Account is Inactive or Blocked'], REST_Controller::HTTP_ACCEPTED);
                    }
                    
                    $updateData = array(                       
                        'gcm_reg_id' => $device_id,
                        'device_type' => $device_type                       
                    );                  
                    $this->db->where(array('id' => $row->id));
                    $action =  $this->db->update('tbl_users', $updateData);
                    
                    $loggedInuserId = $row->id; 
                    $image_base_path = IMAGES_SHOW_PATH."profile/";
                    $data['user_id'] = $row->id;                    
                    $data['name'] = $row->usrName;
                    $data['email_address'] = $row->usrEmail;
                    $data['username'] = $row->usrUsername;
                    $data['profile_pic'] = $row->profile_pic;                   
                    $data['gender'] = $row->usrGender;
                    $data['dob'] = $row->dob;
                    $data['phone_number'] = $row->usrPhone;
                    $data['registered_via'] = $row->registered_via;
                    $data['fb_id'] = $row->usrFacebookId;
                    $data['status'] = $row->usrStatus;                    

                    $this->response(['status' => true, 'data' => $data, 'image_base' => $image_base_path], REST_Controller::HTTP_ACCEPTED); // OK (202) being the HTTP response code The server successfully accepted a new resource
                }
                else
                {
                    ## Return false if user not found ##
                    $this->response(['status' => false, 'message' => 'Either Username or Password is wrong. Please try again'], REST_Controller::HTTP_ACCEPTED);
          
                }
            } 
            else 
            {
                $this->response(['status' => false, 'message' => 'Login Unsuccessful'], REST_Controller::HTTP_ACCEPTED);
            }
     }
     
     /**
     * Get user profile
     * @param  user_id
     */
     
       public function userProfile_get() {
        
        $id = $this->get('user_id');
        $id = (int) $id;
        // Validate the id.
        if ($id <= 0) {
            // Invalid id, set the response and exit.
            $this->response(['status' => false, 'message' => 'There is some problem with data, please try again.'], REST_Controller::HTTP_ACCEPTED); // BAD_REQUEST (400) being the HTTP response code
        }             
        $row = $this->db
            ->get_where('tbl_users', ['id' => $id, 'usrStatus' => '1'])
            ->row();
        if (!empty($row)) {
            $image_base_path = IMAGES_SHOW_PATH."profile/";
            $gender = $row->usrGender;
			if ($gender == '0')
			$gender = 'Male';
			else
			$gender = 'Female';
			
            $data['status'] = $row->usrStatus;
            $data['user_id'] = $row->id;
            $data['user_image'] =  $image_base_path.$row->profile_pic;
            $data['user_gender'] = $gender;
            $data['user_DOB'] = $row->dob;
            $data['user_email'] = $row->usrEmail;
            $data['user_phoneno'] = $row->usrPhone;
            
            
            $this->set_response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                    'status' => false,
                    'message' => 'User could not be found'
                ], REST_Controller::HTTP_ACCEPTED); // NOT_FOUND (404) being the HTTP response code
        }
    }
     
  

    private function checkUserstatus($userId) {
        $this->db->select('usrStatus');
        $findstatusQuery = $this->db->get_where('tbl_users', array('id' => $userId));
        $getfindstatusQuery = $findstatusQuery->result_array();
        return $getfindstatusQuery[0]['usrStatus'];
    }

   /**
     * Update user profile
     * @param  user_id
     */

    public function updateUserDetail_post() {

        if ($this->post('user_id') != '') {
            $userId = $this->post('user_id');
            $email = $this->post('user_email');
			$phone = $this->post('user_phoneno');
            $dob = $this->post('user_DOB');
            $getcurrentuserstaus = $this->checkUserstatus($userId);			
            
            if (empty($getcurrentuserstaus)) {
                $this->response(['status' => FALSE, 'message' => 'You can not update your account as it has been blocked or Inactivated'], REST_Controller::HTTP_ACCEPTED);
            }            
             $unique_email = $this->checkEmailExistance($email, $userId);
			 $unique_phone = $this->checkPhoneExistance($phone, $userId);
            
           if(!$unique_email){
				$this->response(['status' => FALSE, 'message' => 'Email already in use, Send valid Email.'], REST_Controller::HTTP_ACCEPTED);
			 }
			 elseif(!$unique_phone){
				$this->response(['status' => FALSE, 'message' => 'Phone number already in use, please try another.'], REST_Controller::HTTP_ACCEPTED);
			 }
            
            $data = array(
                'usrGender' => $this->post('user_gender'),
                'usrEmail' => $email,
                'dob' =>  date('Y-m-d', strtotime($dob)), 
                'usrPhone' => $phone
            );

            if ($_FILES['user_image'] != '') {
                $Rteurn = $this->uploadAppUserpic();
                if(!$Rteurn)
                $this->response(['status' => FALSE, 'message' => 'Error in image upload.'], REST_Controller::HTTP_ACCEPTED);
                elseif($Rteurn['error'])
                $this->response(['status' => FALSE, 'message' => 'Only image files gif,jpeg,jpg,png supported.'], REST_Controller::HTTP_ACCEPTED);
                else
                $data['profile_pic'] = $Rteurn['image_name'];
            }
            $this->db->where(array('id' => $userId));
          $action =  $this->db->update('tbl_users', $data);
          if($action)
            $this->response(['status' => TRUE, 'message' => 'Profile updated successfully'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            else
             $this->response(['status' => FALSE, 'message' => 'There is some problem with data, please try again.'], REST_Controller::HTTP_ACCEPTED);
        } else {
            $this->response(['status' => FALSE, 'message' => 'You are not authorize to update user detail'], REST_Controller::HTTP_ACCEPTED);
        }
    }
    
    
    
    
    
    
    public function termsConditions_get() {

        $this->db->select('title,titleArab,termsConditions,termsConditionsArab');
        $findTextQuery = $this->db->get('tb_terms');
        $getfindTextQuery = $findTextQuery->result_array();
        if ($getfindTextQuery) {
            $this->response(['title' => $getfindTextQuery[0]['title'], 'titleArab' => $getfindTextQuery[0]['titleArab'], 'terms' => $getfindTextQuery[0]['termsConditions'], 'termsConditionsArab' => $getfindTextQuery[0]['termsConditionsArab'], 'status' => TRUE], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No records found'
                    ], REST_Controller::HTTP_ACCEPTED); // NOT_FOUND (404) being the HTTP response code
        }
    }  

    public function updateUserImage_post() {
        if ($this->post('appuserId') != '') {
            $userId = $this->post('appuserId');
            $getcurrentuserstaus = $this->checkUserstatus($userId);
            if ($getcurrentuserstaus == 'Inactive') {
                $this->response(['status' => FALSE, 'message' => 'You can not update your account as it has been blocked or Inactivated'], REST_Controller::HTTP_ACCEPTED);
            }
            $Rteurn = $this->uploadAppUserpic();
            $data['userimage'] = $Rteurn;

            $this->db->where(array('userid' => $userId));
            $this->db->update('tb_appUser', $data);
            $this->response(['status' => TRUE, 'message' => 'Successfull user image updation'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response(['status' => FALSE, 'message' => 'You are not authorize to update user detail'], REST_Controller::HTTP_ACCEPTED);
        }
    } 

}
