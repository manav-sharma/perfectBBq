<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * * Users Model Class
 *
 * @package ClubInquire
 * @subpackage Admin
 *
 * * */

class Usersmodel extends CI_Model {

    /**
     * @ Function Name		: index
     * @ Function Params	: 
     * @ Function Purpose 	: Auto loading function, specify all the parameter which requires to be used 	global when this class loads 
     * @ Function Returns	: 
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @ Function Name		: getLoginResult
     * @ Function Params	    : $username {string}, $password {string}
     * @ Function Purpose 	: check admin by username and password
     * @ Function Returns	: 
     */
    function getLoginResult($username, $password) {
        $this->db->select("user.*");
        $this->db->from("user");
        #$this->db->where('user.username', $username);
        $this->db->where("(user.username = '$username' || user.email = '$username')");
        $this->db->where('user.password_hash', md5($password));
        $this->db->where('user.status', '1');
        $str = $this->db->get();
        $final = $str->result();

        if (!empty($final['0']->user_id)) {
            $data = array(
                'user_id' => $final['0']->user_id,
                'name' => $final['0']->fname . '  ' . $final['0']->lname,
                'loggedInUserType' => $final['0']->user_type,
                'logged_in' => true,
                'cmsEditMode' => "show",
                'cmsEditMode' => true
            );
            $this->session->set_userdata($data);
        }

        return $str->result();
    }

    /**
     * @ Function Name		: getUsernameResult
     * @ Function Params		: $username {string}
     * @ Function Purpose 	: check admin detail by username
     * @ Function Returns	: 
     */
    function getUsernameResult($username) {
        $this->db->select('*');
        $this->db->where('usrUsername', $username);
        $result = $this->db->get("tbl_users");
        return $result->result();
    }

    /**
     * @ Function Name		: updateAdminPassword
     * @ Function Params		: $username {string}
     * @ Function Purpose 	: update user password
     * @ Function Returns	: 
     */
    function updateAdminPassword($email, $newPassword) {
        $this->db->where('usrEmail', $email);
        $this->db->update('tbl_users', array("usrPassword " => md5($newPassword)));
        return true;
    }

    /**
     * @ Function Name		: changePassword
     * @ Function Params		: $id {integer}, $oldPassword {string}, $newPassword {string}
     * @ Function Purpose 	: change admin passord 
     * @ Function Returns	: 
     */
    function changePassword($id, $oldPassword, $newPassword) {
        // check for old password
        $this->db->select("password_hash");
        $this->db->where("user_id", $id);
        $result = $this->db->get("user");
        $resultpass = $result->row();

        $originalpassword = (isset($resultpass->password_hash)) ? $resultpass->password_hash : "";

        if ($originalpassword == md5($oldPassword)) {
            $this->db->where("user_id", $id);
            if ($this->db->update('user', array("password_hash" => md5($newPassword)))) {
                $this->session->set_flashdata('item', '<div class="warning pos">Your password has been changed successfully.</div>');
                $this->session->set_flashdata('class', 'pos');
                return true;
            } else {
                $this->session->set_flashdata('item', '<div class="warning neg">There is problem while changing password please try again.</div>');
                $this->session->set_flashdata('class', 'neg');
                return false;
            }
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">Your old password is wrong, please enter correct password.</div>');
            $this->session->set_flashdata('class', 'neg');
            return false;
        }
    }

    /**
     * @ Function Name		: deleteUsers
     * @ Function Params		: $id {Array}
     * @ Function Purpose 	: delete users by id also delete from subscription list if user is subscribed
     * @ Function Returns	: 
     */
    function deleteUsers($id) {
        $this->db->where("user_id IN (" . $id . ")", "", false);
        if ($this->db->delete('user') == true) {
            $this->db->where("user_id IN (" . $id . ")", "", false);
            ####DELETE USER DATA AS WELL AFTER DELETING USER############
            ###$this->db->delete('tbl_user_profile');
            return true;
        } else {
            return false;
        }
    }

    /**
     * @ Function Name		: status
     * @ Function Params		: $id {Array/integer}, $status {active/inactive}
     * @ Function Purpose 	: delete users by id
     * @ Function Returns	: 
     */
    function status($id, $status) {
        $data = array(
            'status' => $status,
        );
        $this->db->where('user_id IN (' . $id . ')', '', false);
        return $this->db->update('user', $data);
    }
    
    /**
     * @ Function Name		: addUser
     * @ Function Params		: 
     * @ Function Purpose 	: function to add new user by admin
     * @ Function Returns	: 
     * */
    public function addUser($code) {
        $return = FALSE;
        $dob = $this->input->post('txtDob');
        if (isset($dob) && !empty($dob))
            $dob = date('Y-m-d', strtotime($dob));

        $userdetails = array(
            'user_group_id' => $this->input->post('groupname'),
            'usrUsername' => $this->input->post('username'),
            'usrPassword' => md5($this->input->post('password')),
            'usrEmail' => $this->input->post('email'),
            'usrStatus' => '1',
            'usrDateCreated' => date('Y-m-d H:i:s'),
            'usrUniqueCode' => $code,
            'usrFirstName' => $this->input->post('txtFirstName'),
            'usrLastName' => $this->input->post('txtLastName')
        );

        if ($this->db->insert('tbl_users', $userdetails)) {
            $return = $this->db->insert_id();
            $userProfile = array(
                'user_id' => $return,
                'usrGender' => $this->input->post('txtGender'),
                'usrDOB' => $dob,
                'usrCountryId' => $this->input->post('cmbCountry'),
                'usrState' => $this->input->post('cmbState'),
                'usrCity' => $this->input->post('cmbCity'),
                'usrContact' => $this->input->post('usrContact'),
                'usrAddress' => $this->input->post('usraddress'),
                'usrDescription' => $this->input->post('aboutyourself')
            );
            $return = $this->db->insert('tbl_user_profile', $userProfile);
        }
        return $return;
    }

    function getUserDetails($id) {
        $this->db->select("*");
        $this->db->from("user");
        $this->db->where("user.user_id", $id);
        return $this->db->get()->row();
    }

    /**
     * @ Function Name		: editUserData
     * @ Function Params		: 
     * @ Function Purpose 	: function to edit existing user by admin
     * @ Function Returns	: 
     * */
    public function editUserData($usrId) {
        $return = FALSE;
        $id = $usrId;
        $dob = $this->input->post('txtDob');
        if (isset($dob) && !empty($dob))
            $dob = date('Y-m-d', strtotime($dob));

        $userdetails = array(
            'user_group_id' => $this->input->post('groupname'),
            'usrUsername' => $this->input->post('username'),
            'usrEmail' => $this->input->post('email'),
            'usrStatus' => '1',
            'usrFirstName' => $this->input->post('txtFirstName'),
            'usrLastName' => $this->input->post('txtLastName')
        );

        $pass = $this->input->post('password');

        if (!empty($pass) && $pass != '') {
            $pwd = md5($pass);
            $userdetails['usrPassword'] = $pwd;
        }

        $where = array('id' => $id);
        if ($this->db->update('tbl_users', $userdetails, $where)) {
            $userProfile = array(
                'usrGender' => $this->input->post('txtGender'),
                'usrDOB' => $dob,
                'usrCountryId' => $this->input->post('cmbCountry'),
                'usrState' => $this->input->post('cmbState'),
                'usrCity' => $this->input->post('cmbCity'),
                'usrContact' => $this->input->post('usrContact'),
                'usrAddress' => $this->input->post('usraddress'),
                'usrDescription' => $this->input->post('aboutyourself')
            );
            $where1 = array('user_id' => $usrId);
            $this->db->update('tbl_user_profile', $userProfile, $where1);
            return TRUE;
        } else {
            return FALSE;
        }
        return $return;
    }
    
    public function editAdvertiserProfile($usrId) {
                
        $advertiserDetails = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            ##'status' => '1',
            #'created_at' => date('Y-m-d H:i:s'),
            ##'usrUniqueCode' => $code,##CAN BE USED FOR EMAIL VEFICATIONS LATER
            'fname' => $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'phone' => $this->input->post('phone'),
        );
        
        $password = $this->input->post('password');
        
        if(!empty($password) )
        {
            $advertiserDetails = $advertiserDetails + array('password_hash' => md5($this->input->post('password') ));
        }

        $where = array('user_id' => $usrId);
        if ($this->db->update('user', $advertiserDetails, $where)) {            
            return true;
        } else {
            return false;
        }
        
        return false;
    }
    

    public function editAdvertiserData($usrId) {
                
        $advertiserDetails = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            ##'status' => '1',
            #'created_at' => date('Y-m-d H:i:s'),
            ##'usrUniqueCode' => $code,##CAN BE USED FOR EMAIL VEFICATIONS LATER
            'fname' => $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'phone' => $this->input->post('phone'),
        );
        
        $password = $this->input->post('password');
        
        if(!empty($password) )
        {
            $advertiserDetails = $advertiserDetails + array('password_hash' => md5($this->input->post('password') ));
        }

        $where = array('user_id' => $usrId);
        if ($this->db->update('user', $advertiserDetails, $where)) {            
            return true;
        } else {
            return false;
        }
        
        return false;
    }

    public function editAppUserData($usrId) {
                
        $advertiserDetails = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            ##'status' => '1',
            #'created_at' => date('Y-m-d H:i:s'),
            ##'usrUniqueCode' => $code,##CAN BE USED FOR EMAIL VEFICATIONS LATER
            'fname' => $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'phone' => $this->input->post('phone'),
        );
        
        $password = $this->input->post('password');
        
        if(!empty($password) )
        {
            $advertiserDetails = $advertiserDetails + array('password_hash' => md5($this->input->post('password') ));
        }

        $where = array('user_id' => $usrId);
        if ($this->db->update('user', $advertiserDetails, $where)) {            
            return true;
        } else {
            return false;
        }
        
        return false;
    }
    
    public function addAdvertiser($code, $Rteurn, $randomPassForEmail) {       
        $advertiserDetails = array(
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'status' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            ##'usrUniqueCode' => $code,##CAN BE USED FOR EMAIL VEFICATIONS LATER
            'fname' => $this->input->post('fname'),
            'lname' => $this->input->post('lname'),
            'phone' => $this->input->post('phone'),
            'user_type' => 'advertiser',
        );

        if ($this->db->insert('user', $advertiserDetails)) {
            ####$return = $this->db->insert_id();           
            return true;
        }
        return false;
    }

    function isUniqueEmail($userid, $email) {
        $this->db->where_not_in('usrId', $userid);
        $this->db->where('usrEmail', $email);
        $this->db->from('tbl_users');
        if ($this->db->count_all_results() > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Method to get unique code by user's email address
     */
    public function getUnCode($email) {
        $this->db->select("usrUniqueCode");
        $this->db->from("tbl_users");
        $this->db->where("usrEmail", $email, true);
        $res = $this->db->get();
        return $res->row();
    }

    public function getLoggedUserEmail() {
        $this->db->select('email');
        $this->db->where(array('user_id' => $this->session->userdata('id')));
        return $this->db->get('user')->row();
    }

    public function changeEmail() {        
        $data = array(
            'email' => $this->input->post('txtadminEmail')
        );
        $where = array('user_id' => $this->session->userdata('user_id') );
        if ($this->db->update('user', $data, $where)) {   
            return true;
        } else {
            return false;
        }
    }

}

?>
