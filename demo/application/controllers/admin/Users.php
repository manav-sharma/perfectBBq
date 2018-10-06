<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends MY_Controller {

    /**
     * @ Function Name		: __construct
     * @ Function Params	: 
     * @ Function Purpose 	: initilizing variable and providing pre functionalities
     * @ Function Returns	: 
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('admin/users/Usersmodel');
    }

    /**
     * @ Function Name		: index
     * @ Function Params	: 
     * @ Function Purpose 	: Managing user login and controls redirection
     * @ Function Returns	: 
     */
    public function index() {
        $data['title'] = "Administrator Login";
        if ($this->session->userdata('logged_in') == TRUE) {
            redirect('admin/users/home');
        } else {
            $this->load->view('admin/users/login', $data);
        }
    }

    /**
     * @ Function Name		: login
     * @ Function Params	: 
     * @ Function Purpose 	: provide complete logic for admin login
     * @ Function Returns	: 
     */
    public function login() {
        $username = $this->input->post('txtUserName');
        $password = $this->input->post('txtPassword');
        $chkRememberMe = $this->input->post('chk_remember_me');
        $chkRememberMe = $chkRememberMe == 'y' ? true : false;
        $data['username'] = $username;
        $data['password'] = $password;
        $data['title'] = "Administrator Login";
        $config = array(
            array(
                'field' => 'txtUserName',
                'label' => 'Username',
                'rules' => 'required'
            ),
            array(
                'field' => 'txtPassword',
                'label' => 'Password',
                'rules' => 'required'
            )
        );
        $this->form_validation->set_rules($config);
        $result = array();
        if (isset($_POST) && (!empty($_POST))) {
            if ($this->form_validation->run() == TRUE) {
                $result = $this->Usersmodel->getLoginResult($username, $password);
                if (count($result) == 0) {
                    $message = '<div class="warning neg">Please enter correct credentials to login.</div>';
                    $this->session->set_flashdata('item', $message);
                    redirect('admin/users/login');
                    return;
                }
            }
        }
        //setting user credential in cookies
        if ($chkRememberMe === true) {
            $this->input->set_cookie('admin_name', $username, '86500', '', '/', '');
            $this->input->set_cookie('admin_pass', $password, '86500', '', '/', '');
        } else if ($chkRememberMe === false) {
            $this->input->set_cookie('admin_name', '', '86500', '', '/', '');
            $this->input->set_cookie('admin_pass', '', '86500', '', '/', '');
        }
        
        if ($this->session->userdata('logged_in') == true) {
            if ($this->session->userdata('logged_in') == true && $this->session->userdata('loggedInUserType') == 'admin') {
				redirect('admin/category');
			}
        } else {
            $this->load->view('admin/users/login', $data);
        }
    }

    /**
     * @ Function Name		: home
     * @ Function Params	: $limit, $sortBy, $orderBy
     * @ Function Purpose 	: maintain user listing with sortin, ordring and limit the list
     * @ Function Returns	: 
     */
    function home() 
    {
		redirect('/admin/category', 'refresh');
        /*error_reporting(E_ALL);        ini_set('display_errors', 1);*/
        if ($this->session->userdata('logged_in') == true && $this->session->userdata('loggedInUserType') == 'admin')
        {
            $orderingVar = array();
            $paging = array();
            $joinTableVar = array();
            $whereConditionVar = array();
            $likeConditionArray = array();
            $customConditionVar = '';
            $filterOrSort = $this->input->post("filterOrSort"); 
            //sorting parameters
            $sortBy = $this->input->post('sortBy');
            
            if (!$sortBy) {
                $orderBy = 'desc';
                $sortBy = 'created_at';
            }
            else if($sortBy == 'created_at_desc' || $sortBy == 'created_at_asc')
            { 
                $orderBy = 'asc';
                $sortBy = 'created_at';
            }
            else
            {
                $sortBy = explode("_", $sortBy);
                $orderBy = $sortBy[1];
                $sortBy = $sortBy[0];
            }

            
            if ($filterOrSort == "sort") {
                $orderBy = ($orderBy == "desc") ? "asc" : "desc";
            }

            $orderingVar['orderBy'] = $data['orderBy'] = $orderBy;
            $orderingVar['sortBy'] = $data['sortBy'] = $sortBy;

            // paging set 
            // Join parameters 
            $whereConditionVar = array('user_type'=>'advertiser');
            
            //if(!empty($this->input->post("cmbStatus")) )
            {                
                $whereConditionVar = array('user_type'=>'advertiser', 'status' => $this->input->post("cmbStatus") );
            }
            

            $joinTableVar = array(
                array(
                    //'tableName' => 'tb_hotel',
                    //'joinCondition' => 'tb_hotel.userId = tbl_users.id',
                    //'joinType' => 'LEFT'
                )
            );

//            $usrEmail = $this->input->post("txtEmail");
//            $hotelContact = $this->input->post("txtPhone");
//            $usrStatus = $this->input->post("cmbStatus");            

            $likeConditionArray = array();

            $customConditionVar = array(
                array(
                    'tableWithCondition' => "(user.fname like '%" . 'FieldValue' . "%' or user.lname like '%" . 'FieldValue' . "%' or concat_ws( ' ' , 'user.fname' , 'user.lname' ) like '%" . 'FieldValue' . "%')",
                    'fieldName' => 'txtFname'
                ),
                array(
                    'tableWithCondition' => "DATE_FORMAT(user.created_at ,'%Y-%m-%d') >= 'FieldValue'",
                    'fieldName' => 'txtDateFrom',
                    'condition' => 'AND',
                    'Value' => 'From'
                ),
                array(
                    'tableWithCondition' => "DATE_FORMAT(user.created_at ,'%Y-%m-%d') <= 'FieldValue'",
                    'fieldName' => 'txtDateTo',
                    'condition' => 'AND',
                    'Value' => 'To'
                ),
                array(
                    'tableWithCondition' => "user.email like '%" . 'FieldValue' . "%'",
                    'fieldName' => 'txtEmail'
                ),
                array(
                    'tableWithCondition' => "user.phone like '%" . 'FieldValue' . "%'",
                    'fieldName' => 'txtPhone'
                ),
                array(
                    'tableWithCondition' => "user.usrAddress like '%" . 'FieldValue' . "%'",
                    'fieldName' => 'txtAddress'
                )
            );


            $result = getList('user', "*", $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar, $paging);

            /* Export CSV functionality - START */
            if ($this->input->post("exportCSV") == "exportCSV") {
                $this->__exposrtCSV($result);
            }
            /* Export CSV functionality - END */
            $data["userListing"] = $result;
            $data["postData"] = $this->input->post();
            $data["sortBy"] = $orderingVar["sortBy"];
            $data["orderBy"] = $orderingVar["orderBy"];
            $data['title'] = "Registered Advertisers";
            $this->load->view('admin/users/advertiser', $data);
        }
    }

    /**
     * @ Function Name		: home
     * @ Function Params	: $limit, $sortBy, $orderBy
     * @ Function Purpose 	: maintain user listing with sortin, ordring and limit the list
     * @ Function Returns	: 
     */
    function homedialog() {
        if ($this->session->userdata('logged_in') == TRUE) {
            $orderingVar = array();
            $joinTableVar = array();
            $whereConditionVar = array();
            $likeConditionArray = array();
            $customConditionVar = array();
            $filterOrSort = $this->input->post("filterOrSort");
            //sorting parameters
            $sortBy = $this->input->post('sortBy');
            if (!$sortBy) {
                $sortBy = "usrDateCreated_desc";
            }
            $sortBy = explode("_", $sortBy);
            $orderBy = $sortBy[1];
            $sortBy = $sortBy[0];
            $orderingVar['ipp'] = ($this->input->post("ipp") ? $this->input->post("ipp") : 10);
            $orderingVar['pgn'] = ($this->input->post("pgn") ? $this->input->post("pgn") : 1);
            if ($filterOrSort == "sort") {
                $orderBy = ($orderBy == "desc") ? "asc" : "desc";
            }
            $orderingVar['orderBy'] = $data['orderBy'] = $orderBy;
            $orderingVar['sortBy'] = $data['sortBy'] = $sortBy;
            // Filter parameters 
            $name = $this->input->post('txtName');
            $usrEmail = $this->input->post('txtEmailVal');

            $usrContact = $this->input->post('txtContactVal');
            $usrStatus = $this->input->post('cmbStatus');
            $array = array('usrEmail', 'usrContact', 'usrStatus'); //'geoName',
            $likeConditionArray = compact($array);
            if ($name != "") {
                $customConditionVar[] = array("parameter1" => "(tbl_users.usrFirstName like '%" . $name . "%' or tbl_users.usrLastName like '%" . $name . "%' or concat_ws( ' ' , `usrFirstName` , `usrLastName` ) like '%" . $name . "%')");
            }
            $created_on = $this->input->post('txtDateFrom');
            $created_to = $this->input->post('txtDateTo');
            $createdOn = ($created_on != "From") ? $created_on : "";
            $createdTo = ($created_to != "To") ? $created_to : "";
            if (!empty($createdOn) && !empty($createdTo)) {
                $createdOn = $this->utility->dateFormat($createdOn, 'Y-m-d');
                $createdTo = $this->utility->dateFormat($createdTo, 'Y-m-d');
                $customConditionVar[] = array("parameter1" => "DATE_FORMAT(user.created_at ,'%Y-%m-%d') >= '$createdOn'");
                $customConditionVar[] = array("parameter1" => "DATE_FORMAT(user.created_at ,'%Y-%m-%d') <= '$createdTo'");
            } else if (!empty($createdOn) && empty($createdTo)) {
                $createdOn = $this->utility->dateFormat($createdOn, 'Y-m-d');
                $customConditionVar[] = array("parameter1" => "DATE_FORMAT(user.created_at ,'%Y-%m-%d') >= '$createdOn'");
            } else if (empty($createdOn) && !empty($createdTo)) {
                $createdTo = $this->utility->dateFormat($createdTo, 'Y-m-d');
                $customConditionVar[] = array("parameter1" => "DATE_FORMAT(user.created_at ,'%Y-%m-%d') <= '$createdTo'");
            }
            $result = getList('user', "*", $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar);


            /* Export CSV functionality - START */
            if ($this->input->post("exportCSV") == "exportCSV") {

                $this->__exposrtCSV($result);
            }
            /* Export CSV functionality - END */
            $data["userListing"] = $result;
            $data["postData"] = $this->input->post();
            $data["sortBy"] = $orderingVar["sortBy"];
            $data["orderBy"] = $orderingVar["orderBy"];
            $data['title'] = "Users";
            $this->load->view('admin/users/home1', $data);
        } else {
            $data['title'] = "Administrator Login";
            $message = "Please login to your account to access internal pages";
            $this->form_validation->_error_array['password'] = $message;
            $this->load->view('admin/users/login', $data);
        }
    }

    public function ValidateUserName() {
        $validateId = $_REQUEST['fieldId'];
        $respons = array();
        $respons[0] = $validateId;
        $respons[1] = true;
        echo json_encode($respons);
    }

    /**
     * @ Function Name		: __exposrtCSV
     * @ Function Params	: $dataArray {array} 
     * @ Function Purpose 	: export CSV
     * @ Function Returns	: 
     */
    private function __exposrtCSV($dataArray) {

        $array = array(array('Advertiser Name', 'Email Address', 'Contact Number', 'Date Created', 'Status'));

        foreach ($dataArray as $key => $cat) {
            $tempArray = array();
            $status = '';
            if ($cat->status)
                $status = 'Active';
            else
                $status = 'Inactive';

            $tempArray['Advertiser Name'] = ucfirst($cat->fname);
            $tempArray['Email Address'] = $cat->email;
            $tempArray['Contact Number'] = $cat->phone;
            $tempArray['Date Created'] = date(ADMIN_DATE_FORMAT, strtotime($cat->created_at));
            $tempArray['Status'] = $status;
            $array[] = $tempArray;
        }

        $this->load->helper('csv');
        echo array_to_csv($array, 'registeredadvertisers(' . date('d-M-Y') . ').csv');
        exit;
    }
   
    /**
     * @ Function Name		: __exposrtCSV
     * @ Function Params	: $dataArray {array} 
     * @ Function Purpose 	: export CSV
     * @ Function Returns	: 
     */
    private function __exportAdsCSV($dataArray) {

        $array = array(array('Ad Title', 'Ad Price (USD)', 'Ad Duration (Days)', 'No Of Views', 'Points Accumulated', 'Expiry Date'));

        foreach ($dataArray as $key => $cat) {
            $tempArray = array();
           
            $tempArray['Ad Title'] = ucfirst($cat->ad_title);
            $tempArray['Ad Price'] = $cat->ad_price;
            $tempArray['Ad Duration'] = $cat->ad_duration;
            $tempArray['No Of Views'] = $cat->total_views;
            $tempArray['Points Accumulated'] = $cat->points_accumulated;
            $tempArray['Expiry Date'] = date(ADMIN_DATE_FORMAT, strtotime($cat->expired_at));
            
            $array[] = $tempArray;
        }

        $this->load->helper('csv');
        echo array_to_csv($array, 'advertiser_adsreport(' . date('d-M-Y') . ').csv');
        exit;
    }

    /**
     * @ Function Name		: delete
     * @ Function Params	: $id {array/integer}
     * @ Function Purpose 	: delete user/customer functionality
     * @ Function Returns	: 
     */
    function delete($id = '') {
        $result = false;
        if (!empty($id)) {
            $result = $this->Usersmodel->deleteUsers($id);
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Usersmodel->deleteUsers($id);
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Selected Advertiser(s) deleted successully</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">Selected Advertiser(s) is not deleted, please try again</div>');
        }
        redirect('admin/users/home');
    }
    
    /**
     * @ Function Name		: active
     * @ Function Params	: $id {array/integer}
     * @ Function Purpose 	: make user status active
     * @ Function Returns	: 
     */
    function active($id = '') {
        $result = false;
        if (!empty($id)) {
            $result = $this->Usersmodel->status($id, '1');
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Usersmodel->status($id, '1');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Active status set for selected user(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected user(s), please try again.<div>');
        }
        redirect('admin/users/home');
    }

    function advertiserActive($id = '') {
        $result = false;
        if (!empty($id)) {
            $result = $this->Usersmodel->status($id, '1');
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Usersmodel->status($id, '1');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Active status set for selected advertiser(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected advertiser(s), please try again.<div>');
        }
        redirect('admin/users/home');
    }

    /**
     * @ Function Name		: inactive
     * @ Function Params	: $id {array/integer}
     * @ Function Purpose 	: make user status inactive
     * @ Function Returns	: 
     */
    function inactive($id = '') {
        $result = false;
        if (!empty($id)) {
            $result = $this->Usersmodel->status($id, '0');
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Usersmodel->status($id, '0');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Inactive status set for selected user(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected user(s), please try again.</div>');
        }
        redirect('admin/users/home');
    }

    function advertiserInactive($id = '') {

        $result = false;
        if (!empty($id)) {
            $result = $this->Usersmodel->status($id, '0');
        }
        else 
        {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Usersmodel->status($id, '0');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Inactive status set for selected Advertiser(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected Advertiser(s), please try again.</div>');
        }
        redirect('admin/users/home');
    }

    /**
     * @ Function Name		: logout
     * @ Function Params	: 
     * @ Function Purpose 	: maintain user logout functionality
     * @ Function Returns	: 
     */
    function logout() {
        $this->session->sess_destroy();
        redirect('admin/users/login');
    }

    /**
     * @ Function Name		: changePassword
     * @ Function Params	: 
     * @ Function Purpose 	: maintain admin password change functionality
     * @ Function Returns	: 
     */
    function changePassword() {
        if ($this->session->userdata('logged_in') == TRUE) {
            if (!empty($_POST)) {

                $config = array(
                    array(
                        'field' => 'txtOldPassword',
                        'label' => 'Old Password',
                        'rules' => 'trim|required'
                    ),
                    array(
                        'field' => 'txtNewPassword',
                        'label' => 'New Password',
                        'rules' => 'trim|required'
                    ),
                    array(
                        'field' => 'txtConfirmNewPassword',
                        'label' => 'Confirm Password',
                        'rules' => 'trim|required|matches[txtNewPassword]'
                    )
                );
                $this->form_validation->set_rules($config);
                if ($this->form_validation->run() != FALSE) {

                    $id = $this->session->userdata('user_id');
                    $oldPassword = $this->input->post('txtOldPassword');
                    $newPassword = $this->input->post('txtNewPassword');
                    $result = $this->Usersmodel->changePassword($id, $oldPassword, $newPassword);
                    redirect('admin/users/changePassword');
                }
            }
            $data['title'] = "Change Password";
            $this->load->view('admin/users/changepassword', $data);
        } else {
            $data['title'] = "Administrator Login";
            $message = "Please login to your account to access internal pages";
            $this->form_validation->_error_array['password'] = $message;
            $this->load->view('admin/users/login', $data);
        }
    }

    /**
     * @ Function Name	: forgottenpassword
     * @ Function Params	: 
     * @ Function Purpose 	: maintain admin forgot password functionality
     * @ Function Returns	: 
     */
    function forgottenpassword() {
		$username = $this->input->post('txtUserName');
        $buttong = $this->input->post('btnSubmit');
        $data['username'] = $username;
        $result = $data;
        if (!empty($_POST)) {
		      if ($this->form_validation->run() == TRUE) { 
		        $result = $this->Usersmodel->getUsernameResult($username);
		        if (count($result) == 0) {
                    $this->session->set_flashdata('item', '<div class="warning neg">Username does not exists.</div>');
                    redirect('admin/users/forgottenpassword');
                } else {
                    $this->load->helper('string');
                    $password = $this->generateRandomString();
                    //$password= random_string('alnum',5); 
                    $this->Usersmodel->updateAdminPassword($result['0']->email, $password);
                    $email = $this->_sendAdminPassword($result['0'], $password);
                    $this->session->set_flashdata('item', '<div class="warning pos">Email has been sent to your email address.</div>');
                    redirect('admin/users/login');
                }
            } else {
                $this->session->set_flashdata('item', '<div class="warning neg">Username does not exists.</div>');
                redirect('admin/users/forgottenpassword');
            }
        }
        if ($this->session->userdata('logged_in') == TRUE) {
            redirect('admin/users/home');
        } else {
            $data['title'] = "Forgotten Password";
            $this->load->view('admin/users/forgottenpassword', $data);
        }
    }

    /**
     * @ Function Name		: _sendAdminPassword
     * @ Function Params	: 
     * @ Function Purpose 	: sends mail to admin on forgot password
     * @ Function Returns	: 
     */
    private function _sendAdminPassword($adminArray = array(), $password) {
		
		
		
        
        $from = 'admin@estays.com';
        $to = $adminArray->email;
        $name = $adminArray->fname;
        $password = $password;
        $siteURL = $this->config->item('siteName');

        $subject = $siteURL . " Password Recovery";

        $message .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
		$message .= '<tr>';
		$message .= '<td height="26" style="font-family:Tahoma, Arial, sans-serif; font-size:15px;color:#828282;"><strong>Hi Administrator,</strong></td>';
		$message .= '</tr>';
		$message .= '<tr>';
		$message .= '<td style="font-family:Tahoma, Arial, sans-serif; font-size:15px; line-height:22px; color:#828282; line-height:15px; padding-bottom:10px;">You will find your login data below. Please keep this information secure & safe.</td>';
		$message .= '</tr>';
		$message .= '<tr>';
		$message .= '<td height="5"></td>';
		$message .= '</tr>';
		$message .= '<tr>';
		$message .= '<td align="left">';
		$message .= '<table width="100%" border="0" bgcolor="#313131" cellspacing="1" cellpadding="6" style="font-family:" Roboto", sans-serif;"">';
		$message .= '<tr  bgcolor="#3a383a">';
		$message .= '<td style="border-top:#1D7DC6 solid 0px; font-size:14px; color:#ffffff; font-family: "Roboto" , sans-serif; font-weight:normal; font-family: "Roboto", sans-serif; " colspan="2">';
		$message .= '<strong style="color:#FFF;">' . $siteURL . ' User Credentials</strong>';
		$message .= '</td>';
		$message .= '</tr>';
		$message .= '<tr  bgcolor="#ffffff">';
		$message .= '<td  style="color:#535258; font-weight:normal;font-size:14px; " width="100"><strong>User name</strong></td>';
		$message .= '<td width="470" style="color:#535258;font-size:14px;">' . @$name . '</td>';
		$message .= '</tr>';
		$message .= '<tr  bgcolor="#ffffff">';
		$message .= '<td  style="color:#535258; font-weight:normal; font-size:14px;" width="100"><strong>Password</strong></td>';
		$message .= '<td width="470" style="color:#535258;font-size:14px;">' . @$password . '</td>';
		$message .= '</tr>';
		$message .= '</table>';
		$message .= '</td>';
		$message .= '</tr>';
		$message .= '<tr>';
		$message .= '<td style="color:#313131;padding-top:15px; font-size:14px;">Regards,<br />';
		$message .= 'Customer Support Team<br />';
		$message .= 'Ureap</td>';
		$message .= '</tr>';
		$message .= '</table>';


        $body = getNotificationTheme($subject, $subject, $message);

        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($body);
        $this->email->set_mailtype('html');
       //~ echo $body; die;
        if ($this->email->send()) {
			
            return TRUE;
           
        } else {
            return FALSE;
        }
    }

    /**
     * @ Function Name		: addUser
     * @ Function Params	: 
     * @ Function Purpose 	: admin can add new user
     * @ Function Returns	: 
     */
    function addUser() {
        $data = array();
        $submit = $this->input->post('btnSubmit');
        if (!empty($_POST)) {
            $this->form_validation->set_message('is_unique', 'This %s is already registered. Please choose another.');
            if ($this->form_validation->run()) {
                $code = md5($this->input->post('username') . time());
                if ($insert_id = $this->Usersmodel->addUser($code)) {
                    $this->_sendWelcomeMail();
                    $this->session->set_flashdata('item', '<div class="warning pos">User added successfully. Welcome mail has been sent.</div>');
                    redirect('admin/users/home');
                } else {
                    $this->session->set_flashdata('item', '<div class="warning neg">There is some error while creating an account. Please try again!</div>');
                }
            }
        }
        $data['title'] = "Add New User";
        $data['countries'] = $this->Usersmodel->getCountries();
        $data['allstateList'] = $this->Usersmodel->allstateList();
        $data['allcityList'] = $this->Usersmodel->allcityList();
        $data['groupName'] = $this->Usersmodel->groupList();

        $this->load->view('admin/users/addeditusers', $data);
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

    function uploadHotelpic() {
        if (isset($_FILES['hotelprofilePic']) && !empty($_FILES['hotelprofilePic'])) {

            $temp = explode(".", $_FILES["hotelprofilePic"]["name"]);
            $extension = end($temp);

            $config['upload_path'] = 'hotelImages/';
            $config['overwrite'] = TRUE;
            $config['file_name'] = $_POST['hotelName'];
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('hotelprofilePic')) {
                $hotelImagePath = array(
                    'iamgepath' => $config['file_name'],
                );
                return $hotelImagePath['iamgepath'] . '.' . $extension;
            } else {
                $arr = array(
                    'error' => 'There is problem while uploading data',
                );
                return $arr;
            }
        }
    }

    public function checkPhone($str) {
        $pattern = '/^\(?([+0-9]{0,4})\)?[-. ]?([-0-9]{0,6})[-. ]?([-0-9]{0,10})$/';
        preg_match($pattern, $str, $matches, PREG_OFFSET_CAPTURE);
        if (empty($matches)) {
            $this->form_validation->set_message('checkPhone', 'The %s field must be valid.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * @ Function Name		: _sendWelcomeMail
     * @ Function Params	: 
     * @ Function Purpose 	: welcome mail for useres added by admin
     * @ Function Returns	: 
     */
    private function _sendWelcomeMailHotelregis($hotelPasswrod) {
        $from = 'admin@estays.com';
        $userName = $this->input->post("username");
        $email = $this->input->post("email");
        $hotelName = $this->input->post("hotelName");
        $password = $hotelPasswrod;

        $subject = "Congratulation! Your Account has been successfully created.";

        $message = '';
        $message .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
        $message .= '<tr>';
        $message .= '<td height="26" style="font-size:15px; font-weight:500; color:#690f2b;  ">Dear ' . ucfirst($userName) . ',</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td style="font-size:13px; color:#363636; line-height:18px; padding-bottom:10px;">Your registration was successful. You will find your registration data below. Please keep this information secure & safe.</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td height="5"></td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td align="left">';
        $message .= '<table width="287" border="0" bgcolor="#007f5b" cellspacing="1" cellpadding="6" style=" color:#212121;">';
        $message .= '<tr  bgcolor="#007f5b">';
        $message .= '<td colspan="2" style="border-top:#1f2c67 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize;   padding:8px;">Login Details are:</strong></td>';
        $message .= '</tr>';
        $message .= '<tr style="font-size:13px;">';
        $message .= '<td  bgcolor="#ffffff">User name</td>';
        $message .= '<td  bgcolor="#ffffff">' . @$userName . '</td>';
        $message .= '</tr>';
        $message .= '<tr style="font-size:13px;">';
        $message .= '<td  bgcolor="#ffffff">Password</td>';
        $message .= '<td  bgcolor="#ffffff">' . @$password . '</td>';
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '</td>';
        $message .= '</tr>';
        $message .= '<tr style="color:#292828; font-size:13px; line-height:19px;">';
        $message .= '<td height="25"> Thank you for your continued support. </td>';
        $message .= '</tr>';

        $body = getNotificationTheme($subject, $subject, $message);

        $this->email->from($from);
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($body);
        $this->email->set_mailtype('html');
        //echo $body; die;
        $this->email->send();
        return TRUE;
    }

    private function _sendWelcomeMail() {
        //$from = get_admin_email();
        $from = 'admin@estays.com';
        $userName = $this->input->post("username");
        $email = $this->input->post("email");
        $first_name = $this->input->post("txtFirstName");
        $last_name = $this->input->post("txtLastName");
        $password = $this->input->post("password");
        $unCode = $this->Usersmodel->getUnCode($email);
        $subject = "Congratulation! Your Account has been successfully created.";

        $message = '';
        $message .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
        $message .= '<tr>';
        $message .= '<td height="26" style="font-size:15px; font-weight:500; color:#690f2b;  ">Dear ' . ucfirst($first_name) . ' ' . ucfirst($last_name) . ',</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td style="font-size:13px; color:#363636; line-height:18px; padding-bottom:10px;">Your registration was successful. You will find your registration data below. Please keep this information secure & safe.</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td height="5"></td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td align="left">';
        $message .= '<table width="287" border="0" bgcolor="#007f5b" cellspacing="1" cellpadding="6" style=" color:#212121;">';
        $message .= '<tr  bgcolor="#007f5b">';
        $message .= '<td colspan="2" style="border-top:#1f2c67 solid 0px; font-size:14px; font-weight:400; color:#ffffff; text-transform:capitalize;   padding:8px;">Login Details are:</strong></td>';
        $message .= '</tr>';
        $message .= '<tr style="font-size:13px;">';
        $message .= '<td  bgcolor="#ffffff">User name</td>';
        $message .= '<td  bgcolor="#ffffff">' . @$userName . '</td>';
        $message .= '</tr>';
        $message .= '<tr style="font-size:13px;">';
        $message .= '<td  bgcolor="#ffffff">Password</td>';
        $message .= '<td  bgcolor="#ffffff">' . @$password . '</td>';
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '</td>';
        $message .= '</tr>';
        $message .= '<tr style="color:#292828; font-size:13px; line-height:19px;">';
        $message .= '<td height="25"> Thank you for your continued support. </td>';
        $message .= '</tr>';

        $body = getNotificationTheme($subject, $subject, $message);

        $this->email->from($from);
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($body);
        $this->email->set_mailtype('html');
        $this->email->send();
        return TRUE;
    }

    function checkPassAndConfPass($str) {
        if ($str != '') {
            $confPass = $this->input->post("txtConfirmNewPassword");
            if ($str == $confPass) {
                return true;
            } else {
                $this->form_validation->set_message('checkPassAndConfPass', 'Confirm password must match to password.');
                return false;
            }
        } else {
            return true;
        }
    }

    
    function advertiserEditProfile() {

        $advertiserId = $this->session->userdata('user_id');
        
        ###REDIRECT TO LOGIN IF NO ADERTISER ID FOUND
        if(empty($advertiserId) )
        {
            return $this->load->view('admin/users/login', $data);
        }
        
        $data = array();
        if (!empty($_POST)) {

            $advertiserData = $this->Usersmodel->getUserDetails($advertiserId);
            
            if($this->input->post('username') != $advertiserData->username) {
                $isUniqueUserName =  '|is_unique[user.username]';
            } else {
                $isUniqueUserName =  '';
            }

            if($this->input->post('email') != $advertiserData->email) {
                $isUniqueEmail =  '|is_unique[user.email]';
            } else {
                $isUniqueEmail=  '';
            }

            
            $rules = array(
                //    array(
                //        'field' => 'username',
                //        'label' => 'User Name',
                //        'rules' => "required|min_length[6]$isUniqueUserName"#|is_unique[user.username]|callback_username_check
                //    ),
                array(
                    'field' => 'fname',
                    'label' => 'First Name',
                    'rules' => 'required|max_length[60]'
                ),
                array(
                    'field' => 'lname',
                    'label' => 'Last Name',
                    'rules' => 'required|max_length[60]'
                ),
                array(
                    'field' => 'phone',
                    'label' => 'Advertiser Contact',
                    //'rules' => 'required|regex_match[/^[0-9]{10}$/]'
                    'rules' => 'required|numeric|min_length[5]|max_length[15]'
                ),
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => "required|valid_email|max_length[255]$isUniqueEmail"
                ),
                array(
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'trim|min_length[6]|max_length[20]'
                ),
                array(
                    'field' => 'confirmPassword',
                    'label' => 'Confirm Password',
                    'rules' => 'trim|matches[password]|min_length[6]|max_length[20]'
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
                    //'rules' => 'required|regex_match[/^[0-9]{10}$/]'
                    'rules' => 'numeric|required|min_length[5]|max_length[15]'
                )
            );
            $this->form_validation->set_rules($rules);
            $this->form_validation->set_message('min_length', '%s: the minimum of characters is %s');
            $this->form_validation->set_message('max_length', '%s: the maximum of characters is %s');
            $this->form_validation->set_message('is_unique', 'This %s is already registered. Please choose another.');
            if ($this->form_validation->run())
            {
                ############################File Upload##############################
                //if ($_FILES['hotelprofilePic']['tmp_name'] == '') {
                //    $hotelMainPic = $this->input->post('hotelprofilePic');
                //} else {
                //    $Rteurn = $this->uploadHotelpic();
                //    $hotelMainPic = $Rteurn;
                //}
                ################################File Upload*#########################

                ####$code = md5($this->input->post('username') . time());
                $res = $this->Usersmodel->editAdvertiserProfile($advertiserId);
                /* Code end's here */
                if ($res) {
                    $message = "<div class='warning pos'>Profile edited successfully</div>";
                    $this->session->set_flashdata('item', $message);
                } else {
                    $message = "<div class='warning neg'>Profile edit unsuccessful</div>";
                    $this->session->set_flashdata('item', $message);
                }
                redirect('admin/users/advertisereditprofile');
            }
        }
        $advertiserData = $this->Usersmodel->getUserDetails($advertiserId);
        $data['detail'] = $advertiserData;
        $data['title'] = "Update Profile";
        $this->load->view('admin/users/updateprofileadvertiser', $data);
    }
    
    
    function editAdvertiser($id = '') {

        $data = array();
        if (!empty($_POST)) {

            $advertiserData = $this->Usersmodel->getUserDetails($id);
            
            //if($this->input->post('username') != $advertiserData->username) {
            //    $isUniqueUserName =  '|is_unique[user.username]';
            //} else {
            //    $isUniqueUserName =  '';
            //}

            if($this->input->post('email') != $advertiserData->email) {
                $isUniqueEmail =  '|is_unique[user.email]';
            } else {
                $isUniqueEmail=  '';
            }

            
            $rul = array(
                //array(
                //    'field' => 'username',
                //    'label' => 'User Name',
                //    'rules' => "required|min_length[6]$isUniqueUserName"#|is_unique[user.username]|callback_username_check
                //),
                array(
                    'field' => 'fname',
                    'label' => 'First Name',
                    'rules' => 'required|max_length[60]'
                ),
                array(
                    'field' => 'lname',
                    'label' => 'Last Name',
                    'rules' => 'required|max_length[60]'
                ),
                array(
                    'field' => 'phone',
                    'label' => 'Advertiser Contact',
                    //'rules' => 'required|regex_match[/^[0-9]{10}$/]'
                    'rules' => 'required|numeric|min_length[5]|max_length[15]'
                ),
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => "required|valid_email|max_length[255]$isUniqueEmail"
                ),
                array(
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'trim|min_length[6]|max_length[20]'
                ),
                array(
                    'field' => 'confirmPassword',
                    'label' => 'Confirm Password',
                    'rules' => 'trim|matches[password]|min_length[6]|max_length[20]'
                )
            );
            $this->form_validation->set_rules($rul);
            $this->form_validation->set_message('min_length', '%s: the minimum of characters is %s');
            $this->form_validation->set_message('max_length', '%s: the maximum of characters is %s');
            $this->form_validation->set_message('is_unique', 'This %s is already registered. Please choose another.');
            if ($this->form_validation->run())
            {
                ############################File Upload##############################
                //if ($_FILES['hotelprofilePic']['tmp_name'] == '') {
                //    $hotelMainPic = $this->input->post('hotelprofilePic');
                //} else {
                //    $Rteurn = $this->uploadHotelpic();
                //    $hotelMainPic = $Rteurn;
                //}
                ################################File Upload*#########################

                ####$code = md5($this->input->post('username') . time());
                $uid = $this->input->post('user_id');
                $res = $this->Usersmodel->editAdvertiserData($uid);
                /* Code end's here */
                if ($res) {
                    $message = "<div class='warning pos'>Selected Advertiser edited successfully</div>";
                    $this->session->set_flashdata('item', $message);
                } else {
                    $message = "<div class='warning neg'>Selected Advertiser edit unsuccessful</div>";
                    $this->session->set_flashdata('item', $message);
                }
                redirect('admin/users/home');
            }
        }
        $advertiserData = $this->Usersmodel->getUserDetails($id);
        $data['detail'] = $advertiserData;
        $data['title'] = "Edit Advertiser";
        $this->load->view('admin/users/addeditadvertiser', $data);
    }
    
    
    function addAdvertiser() {

        $data = array();
        if (!empty($_POST)) {

            $rul = array(
                //array(
                //    'field' => 'username',
                //    'label' => 'User Name',
                //    'rules' => 'required|min_length[6]|is_unique[user.username]'#|callback_username_check
                //),
                array(
                    'field' => 'fname',
                    'label' => 'First Name',
                    'rules' => 'required|max_length[60]'
                ),
                array(
                    'field' => 'lname',
                    'label' => 'Last Name',
                    'rules' => 'required|max_length[60]'
                ),
                array(
                    'field' => 'phone',
                    'label' => 'Advertiser Contact',
                    //'rules' => 'required|regex_match[/^[0-9]{10}$/]'
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
                    //'rules' => 'required|regex_match[/^[0-9]{10}$/]'
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
                ############################File Upload##############################
                //if ($_FILES['hotelprofilePic']['tmp_name'] == '') {
                //    $hotelMainPic = $this->input->post('hotelprofilePic');
                //} else {
                //    $Rteurn = $this->uploadHotelpic();
                //    $hotelMainPic = $Rteurn;
                //}
                ################################File Upload*#########################

                ####$code = md5($this->input->post('username') . time());
                
                $res = $this->Usersmodel->addAdvertiser();
                /* Code end's here */
                if ($res) {
                    $message = "<div class='warning pos'>Advertiser created successfully</div>";
                    $this->session->set_flashdata('item', $message);
                } else {
                    $message = "<div class='warning neg'>Advertiser create unsuccessful</div>";
                    $this->session->set_flashdata('item', $message);
                }
                redirect('admin/users/home');
            }
        }
//        $advertiserData = $this->Usersmodel->getUserDetails($id);
//        $data['detail'] = $advertiserData;
        $data['title'] = "Add Advertiser";
        $this->load->view('admin/users/addeditadvertiser', $data);
    }

    public function hotelname_check($str) {
        if (!preg_match("/^([a-z0-9 ])+$/i", $str)) {
            $this->form_validation->set_message('hotelname_check', 'The %s field can only be alpha numeric');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function username_check($str) {
        if (((!preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str))) || ((preg_match('#[0-9]#', $str) && !preg_match('#[a-zA-Z]#', $str)))) {
            $this->form_validation->set_message('username_check', 'Username Must be combination of alphabets and numbers only');
            return FALSE;
        } else {

            return TRUE;
        }
    }

    public function changeEmail() {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('txtadminEmail', 'Admin Email', 'required|valid_email');
            if ($this->form_validation->run() != false) {                
                
                $result = $this->Usersmodel->changeEmail();
                if ($result) {
                    $this->session->set_flashdata('item', '<div class="warning pos">Email Updated successfully</div>');
                    redirect('admin/users/changeEmail');
                } else {
                    $this->session->set_flashdata('item', '<div class="warning neg">There is some error while Updating email, Please try again!</div>');
                }
            }
        }
        $adminEmail = $this->Usersmodel->getLoggedUserEmail();
		

        $data['email'] = isset($adminEmail->email) ? $adminEmail->email : '';
        $data['title'] = "Change Email";
        $this->load->view('admin/users/changeemail', $data);
    }

    public function report($userType,$id){
        //redirect("admin/report/$userType");
        if ($this->session->userdata('logged_in') == true && $this->session->userdata('loggedInUserType') == 'admin')
        {
            $orderingVar = array();
            $paging = array();
            $joinTableVar = array();
            $whereConditionVar = array();
            $likeConditionArray = array();
            $customConditionVar = '';
            $filterOrSort = $this->input->post("filterOrSort");
            //sorting parameters
            $sortBy = $this->input->post('sortBy');
            
            if (!$sortBy) {
                $orderBy = 'desc';
                $sortBy = 'ads.expired_at';
            }
            else
            {             
                $sortBy = explode("_", $sortBy);
                $lengthSortBy = count($sortBy);
                
                $orderBy = end($sortBy);
                
                $sortByTemp = '';                
                
                for ($index = 0; $index < ($lengthSortBy-1); $index++) {
                    $sortByTemp .= "{$sortBy[$index]}_";
                }                
                
                $sortBy = substr($sortByTemp, 0, -1);                
            }

            
            if ($filterOrSort == "sort") {
                $orderBy = ($orderBy == "desc") ? "asc" : "desc";
            }

            $orderingVar['orderBy'] = $data['orderBy'] = $orderBy;
            $orderingVar['sortBy'] = $data['sortBy'] = $sortBy;

            $whereConditionVar = array(
                //'status' => $this->input->post("cmbStatus"),
                'user_id'=>$id
            );
           
            $joinTableVar = array(
               /* array(
                    'tableName' => 'user',
                    'joinCondition' => 'user.user_id = ads.user_id',
                    'joinType' => 'LEFT'
                )*/
            );

            $likeConditionArray = array();
               
            $customConditionVar = array(
                array(
                    'tableWithCondition' => "(ads.ad_title like '%" . 'FieldValue' . "%')",
                    'fieldName' => 'txtAdTitle'
                ),
                array(
                    'tableWithCondition' => "(ads.ad_price like '%" . 'FieldValue' . "%')",
                    'fieldName' => 'txtadPrice'
                ), 
                array(
                    'tableWithCondition' => "(ads.total_views like '%" . 'FieldValue' . "%')",
                    'fieldName' => 'txttotal_views'
                ), 
                array(
                    'tableWithCondition' => "DATE_FORMAT(ads.expired_at ,'%Y-%m-%d') >= 'FieldValue'",
                    'fieldName' => 'txtDateFrom',
                    'condition' => 'AND',
                    'Value' => 'From'
                ),
                array(
                    'tableWithCondition' => "DATE_FORMAT(ads.expired_at ,'%Y-%m-%d') <= 'FieldValue'",
                    'fieldName' => 'txtDateTo',
                    'condition' => 'AND',
                    'Value' => 'To'
                )
            );

            $ads = getList('ads', "ad_title, ad_price, total_views, creditBalance, ad_credits, created_at, expired_at", $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar, $paging);
         
            $this->db->select('count(ads.ad_id) as total_ads, sum(ad_price) as total_spending')
                ->from('ads')
                ->where(['user_id'=>$id]);
            $result = $this->db->get()->row_array();
        
            array_map(function($ad){
                $exptime = strtotime($ad->expired_at);
                $createtime = strtotime($ad->created_at);
                if($exptime<=0)
                    $exptime = time();

                $timediff = $exptime - $createtime;
                $ad->ad_duration = floor($timediff / (60*60*24) );
                $ad->points_accumulated = $ad->ad_credits - $ad->creditBalance;
            },$ads);

            $result['ads'] = $ads;
            //echo '<pre>'; print_r($result); die;

            /* Export CSV functionality - START */
            if ($this->input->post("exportCSV") == "exportCSV") {
                $this->__exportAdsCSV($ads);
            }
            /* Export CSV functionality - END */
            
            $data["adsDetail"] = $result;
            $data["postData"] = $this->input->post();
            $data["sortBy"] = $orderingVar["sortBy"];
            $data["orderBy"] = $orderingVar["orderBy"];
            $data['title'] = "Campaigns";
            $this->load->view('admin/users/advertiser_report', $data);
        
        }
        
    }
    
}

/* End of file users.php */
/* Location: ./application/controllers/users.php */
