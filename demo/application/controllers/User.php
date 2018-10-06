<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    /**
   * @ Function Name	: __construct
   * @ Function Params	: 
   * @ Function Purpose 	: initilizing variable and providing pre functionalities
   * @ Function Returns	: 
   */
   public function __construct(){
       parent::__construct();

        $this->load->helper(array('common'));
        $this->load->helper(array('form','url','email','cookie'));
        $this->load->library(array('form_validation','utility','email','session')); 
        $this->load->model('frontend/user/usermodel');

   }
   
    /**
    * @ Function Name		: _sendSignupEmail
    * @ Function Params		: 
    * @ Function Purpose 	: Send email to Advertiser to verify account 
    * @ Function Returns	: 
    */
   private function _sendSignupEmail($post){
        $fname =$post['fname'];
        $lname =$post['lname'];
        $email = $post['email'];
        $password = $post['password'];
        $socialId = $post['social_id'];
        $fullname = "$fname $lname";
         
        $verifyText = '';
        $verify_link = '';
        if(empty($socialId)) {
            $verify_link = '<tr><td><a href="'.base_url().'user/verify_email/'.urlencode($email).'">Click Here To Verify<a></td></tr>';
            $verifyText = 'Please verify your account before login.';
        }
        
        $to = $email;
        $from     = SUPPORT_EMAIL;
        $subject  = "Congratulations! You are successfully registered with ".SITE_NAME;			
        $message = '';
        $message .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
        $message .= '<tr>';
        $message .= '<td height="26" style="font-family:Tahoma, Arial, sans-serif; font-size:15px;color:#828282;"><strong>Dear '.@$fullname.'</strong>,</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td style="font-family:Roboto, Arial, sans-serif; font-size:15px; line-height:22px; color:#828282; line-height:15px; padding-bottom:10px;">Congratulations! Your have been successfully registered as an advertiser. Below has given your login details. '.$verifyText.'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td height="5"></td>';
        $message .= '</tr>';
        $message .= $verify_link;
        $message .= '<tr>';
        $message .= '<td height="10"></td>';
        $message .= '</tr>';
        
        $message .= '<tr>';
        $message .= '<td align="left">';
        $message .= '<table width="100%" border="0" bgcolor="#313131" cellspacing="1" cellpadding="6" style="font-family:" Roboto", sans-serif;"">';
        $message .= '<tr  bgcolor="#3a383a">';
        $message .= '<td style="border-top:#1D7DC6 solid 0px; font-size:14px; color:#ffffff; font-family: "Roboto" , sans-serif; font-weight:normal; font-family: "Roboto", sans-serif; " colspan="2">';
        $message .= '<strong style="color:#FFF;">Login Credentials</strong>';
        $message .= '</td>';
        $message .= '</tr>';
        $message .= '<tr  bgcolor="#ffffff">';
        $message .= '<td  style="color:#535258; font-weight:normal;font-size:14px; " width="100"><strong>Username:</strong></td>';
        $message .= '<td width="470" style="color:#535258;font-size:14px;">' .@$email . '</td>';
        $message .= '</tr>';
        $message .= '<tr  bgcolor="#ffffff">';
        $message .= '<td  style="color:#535258; font-weight:normal; font-size:14px;" width="100"><strong>Password:</strong></td>';
        $message .= '<td width="470" style="color:#535258;font-size:14px;">' .@$password . '</td>';
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td style="color:#313131;padding-top:15px; font-size:14px;">Regards,<br />';
        $message .= 'Customer support team<br />';
        $message .= SITE_NAME.'</td>';
        $message .= '</tr>';
        $message .= '</table>';
    		
        $body = getNotificationTheme($subject, '<font style="font-size:15px;">'. $subject .'</font>.', $message);
        $this->email->from($from,ucfirst(SITE_NAME));
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($body);
        $this->email->set_mailtype('html');
	
        $this->email->send();
   }
   
    /**
    * @ Function Name		: addAdvertiser
    * @ Function Params		: 
    * @ Function Purpose 	: add the advertisers
    * @ Function Returns	: 
    */
    function addAdvertiser() {

        $data = array();
        if (!empty($_POST)) {
            
            $rul = array(
                array(
                    'field' => 'fname',
                    'label' => 'First Name',
                    'rules' => 'required|alpha|max_length[60]'
                ),
                array(
                    'field' => 'lname',
                    'label' => 'Last Name',
                    'rules' => 'required|max_length[60]'
                ),
                array(
                    'field' => 'phone',
                    'label' => 'Advertiser Contact',
                    'rules' => 'required|numeric|min_length[5]|max_length[15]'
                ),
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'required|valid_email|is_unique[user.email]|max_length[255]'
                ),
                array(
                    'field' => 'cname',
                    'label' => 'Company Name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cwebsite',
                    'label' => 'Company Website',
                    'rules' => 'trim|valid_url'
                ),
                array(
                    'field' => 'cphone',
                    'label' => 'Company Phone',
                    'rules' => 'numeric|required|min_length[5]|max_length[15]'
                ),
                array(
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'trim|required|min_length[6]|max_length[20]'
                ),
                array(
                    'field' => 'confirmPassword',
                    'label' => 'Confirm Password',
                    'rules' => 'trim|required|matches[password]|min_length[6]|max_length[20]'
                )
               
            );
            $this->form_validation->set_rules($rul);
            $this->form_validation->set_message('min_length', '%s: the minimum of characters is %s');
            $this->form_validation->set_message('max_length', '%s: the maximum of characters is %s');
            $this->form_validation->set_message('is_unique', 'This %s is already registered. Please choose another.');
            if ($this->form_validation->run())
            {
               
                $res = $this->usermodel->addAdvertiser();
                /* Code end's here */
                if ($res) {
					## Send New Signup email ==>> ##
                    $this->_sendSignupEmail($_POST);
                    /************************/
                    $message = "Congratulations! Your account has been created successfully.";
                    $this->session->set_flashdata('item', $message);
                } else {
                    $message = "Oops! Your account could not be created.";
                    $this->session->set_flashdata('item', $message);
                }
                redirect('user/addAdvertiser');
            }
            
            $user_details = array(
                    'fname'  => $_POST['fname'],
                    'lname'  => $_POST['lname'],
                    'email' =>  $_POST['email'],
                    'genderType'  => $_POST['genderType'],
                    'phone'  => $_POST['phone']
                );
            $data['user_details'] = $user_details;
            
        }

         $this->load->library('facebook');
         $data['login_urls'] = $this->facebook->login_url(array('redirect_uri' => site_url('user/flogin'),'scope' => array("email")));
       
        if($this->session->flashdata('new_userprofile'))
        {
            $gprofile = $this->session->flashdata('new_userprofile'); 
            $user_details = array(
                    'fname'  => $gprofile['firstName'],
                    'lname'  => $gprofile['lastName'],
                    'email' =>  $gprofile['email'],
                    'social_id'  => $gprofile['identifier'],
                    'phone'  => $gprofile['phone'],
                    'genderType'  => $gprofile['genderType'],
                    'social_media_type' => 'GooglePlus',
                );
            $data['user_details'] = $user_details;
        }
       
        $data['title'] = "Sign up";
        $this->load->view('frontend/user/signup', $data);
        
    }
    
    /**
    * @ Function Name		: flogin
    * @ Function Params	: 
    * @ Function Purpose 	: Login through facebook
    * @ Function Returns	: 
    */
    public function flogin(){
		
	$this->load->library('facebook');
	   
        if(isset($_GET['error']) && $_GET['error']=='access_denied')        {
            $this->session->set_flashdata('msg','You have cancelled.');         redirect('user/addadvertiser');     
        }
         
        $user = '';
       // Check if user is logged in
        if ($this->facebook->is_authenticated())
        {
            // User logged in, get user details
            $user = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,gender,picture');
            
            if (isset($user['error']))
            {
                $user = '';
            }

        }else {
            $this->facebook->destroy_session();
            redirect('user/addadvertiser');    
        }
        
       if($user != "") {
			
            $data['title'] = "Sign up";
            $user_details = array(
                'fname'  => str_replace(' ','',$user['first_name']),
                'lname'  => str_replace(' ','',$user['last_name']),
                'email' => str_replace(' ','',$user['email']), 
                'social_id'  => $user['id'],
                'social_media_type' => 'Facebook',
            );
            $user_details['genderType'] = ($user['gender'] == 'male')?'m':'f';
            	
            $data['user_details'] = $user_details;
            $data['login_urls'] = $this->facebook->login_url(array('redirect_uri' => site_url('user/flogin'),'scope' => array("email")));
            $this->load->view('frontend/user/signup', $data);
       	
        } else {
            redirect('user/addadvertiser');
        }
    }
    
    function flogout(){
        $this->facebook->destroy_session();
        redirect('user/addadvertiser');   
    }

    /**
    * @ Function Name		: verify_email
    * @ Function Params	: 
    * @ Function Purpose 	: Function to verify user email
    * @ Function Returns	: 
    */
    public function verify_email($email) {
        $email = urldecode($email);
        $upd = array('is_email_verified'=>'1');
        $this->db->where(['email'=>$email]);
        if($this->db->update('user',$upd)) {
            $message = '<div class="warning pos">Thank you! your account has been verified successfully.</div>';
            $this->session->set_flashdata('item',$message);
            redirect('admin');
        }
            
    }
    
}

/* End of file User.php */
/* Location: ./application/controllers/user.php */
