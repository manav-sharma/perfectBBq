<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/* * * Users Model Class
*
* @package ClubInquire
* @subpackage Admin
*
* * */

class Appusersmodel extends CI_Model {
	
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
		$this->db->select("tbl_users.*, tbl_roles.id as permission");
		$this->db->from("tbl_users");
		$this->db->join('tbl_roles', 'tbl_roles.id = tbl_users.user_group_id', 'left');
		$this->db->where('tbl_users.usrUsername', $username);
		$this->db->where('tbl_users.usrPassword', md5($password)); 
		$str = $this->db->get();
		$final = $str->result(); 
		//echo "<pre>";
		//print_r($final);
		//exit;  
		
		if(isset($final['0']->id) && $final['0']->permission == 2)
		{
			$data = array(
				'id' => $final['0']->id,
				'name' => $final['0']->usrFirstName.'  '.$final['0']->usrLastName,
				'loggedInUserType' =>'adminUser',
				'logged_in' => TRUE,
				'cmsEditMode' => "show",
				'cmsEditMode' => TRUE
			);
			$this->session->set_userdata($data);
		}
		else if(isset($final['0']->id) && $final['0']->permission == 1){
			
			$data = array(
				'id' => $final['0']->id,
				'name' => $final['0']->usrFirstName.'  '.$final['0']->usrLastName,
				'loggedInUserType' =>'hotelUser',
				'logged_in' => TRUE,
				'cmsEditMode' => "show",
				'cmsEditMode' => TRUE
			);
			$this->session->set_userdata($data);
		}
		else{
			return array();
		}
		
		/*if (isset($final['0']->id) && permission($final['0']->id) ) {
			$data = array(
				'id' => $final['0']->id,
				'name' => $final['0']->usrFirstName.'  '.$final['0']->usrLastName,
				'logged_in' => TRUE,
				'cmsEditMode' => "show",
				'cmsEditMode' => TRUE
			);
			$this->session->set_userdata($data);
		}else{
			return array();
		}*/
		
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
	function updateAdminPassword($email,$newPassword) { 
		$this->db->where('usrEmail', $email);
		$this->db->update('tbl_users', array("usrPassword " => md5($newPassword))); 
	}
	
	/**
	* @ Function Name		: changePassword
	* @ Function Params		: $id {integer}, $oldPassword {string}, $newPassword {string}
	* @ Function Purpose 	: change admin passord 
	* @ Function Returns	: 
	*/
	function changePassword($id, $oldPassword, $newPassword) {
		// check for old password
		$this->db->select("admPassword");
		$this->db->where("admId", $id);
		$result = $this->db->get("tbl_admin");
		$resultpass = $result->row();
		$originalpassword = (isset($resultpass->admPassword)) ? $resultpass->admPassword : "";
	
		if ($originalpassword == md5($oldPassword)) {
			$this->db->where("admId", $id);
			if ($this->db->update('tbl_admin', array("admPassword" => md5($newPassword)))) {
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
		$this->db->where("userid IN (" . $id . ")", "", FALSE);  
		if ($this->db->delete('tb_appUser') == True){
				//$this->db->where("id IN (" . $id . ")", "", FALSE);
				//$this->db->delete('tbl_user_profile');
			return TRUE;
		} else {
			return FALSE;
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
			'userStatus' => $status,
		);
		$this->db->where('userid IN (' . $id . ')', '', false);
		return $this->db->update('tb_appUser', $data);
	}

	/**
	* @ Function Name		: getCountries
	* @ Function Params		: 
	* @ Function Purpose 	: fetch list of countries
	* @ Function Returns	: 
	*/
	public function getCountries() {
		$this->db->select('geoId,geoName');
		$this->db->where('geoType', 2);
		$this->db->where('geoIsActive', 'y');
		$this->db->where('geoIsTrash', 'n');
		$this->db->from('tbl_geography');
		$this->db->order_by('geoName', 'asc');
		$result = $this->db->get();
		$country = $result->result();
		return $country;
	}
	
	
	
	
	public function getHotelsType() {
		$this->db->select('hotelTypeId,hotelType');
		$this->db->from('tb_hoteType');
		$this->db->order_by('hotelTypeId', 'asc');
		$result = $this->db->get();
		$hotelsType = $result->result();
		return $hotelsType;
	}
	
	public function getRoomCategory() {
		$this->db->select('roomCatId,roomCategory');
		$this->db->from('tb_roomCategory');
		$this->db->order_by('roomCatId', 'asc');
		$result = $this->db->get();
		$roomCategory = $result->result();
		return $roomCategory;
	}


	/**
	* @ Function Name		: addUser
	* @ Function Params		: 
	* @ Function Purpose 	: function to add new user by admin
	* @ Function Returns	: 
	**/
	public function addUser($code) {
		$return = FALSE; 
        $dob = $this->input->post('txtDob');  
		if(isset($dob) && !empty($dob))
			$dob = date('Y-m-d', strtotime($dob));

		$userdetails = array(
			'user_group_id'			=> $this->input->post('groupname'),
			'usrUsername' 			=> $this->input->post('username'),
			'usrPassword' 			=> md5($this->input->post('password')), 
			'usrEmail' 				=> $this->input->post('email'),
			'usrStatus' 			=> '1',
			'usrDateCreated' 		=> date('Y-m-d H:i:s'),
			'usrUniqueCode' 		=> $code,
			'usrFirstName' 			=> $this->input->post('txtFirstName'),
			'usrLastName' 			=> $this->input->post('txtLastName')
		);
		
		if ($this->db->insert('tbl_users', $userdetails)) {
			$return = $this->db->insert_id();
				$userProfile = array( 
					'user_id'		 		=> $return,
					'usrGender'				=> $this->input->post('txtGender'),
					'usrDOB'				=>$dob,
					'usrCountryId' 			=> $this->input->post('cmbCountry'),
					'usrState' 				=> $this->input->post('cmbState'),
					'usrCity' 				=> $this->input->post('cmbCity'),
					'usrContact' 			=> $this->input->post('usrContact'),
					'usrAddress' 			=> $this->input->post('usraddress'),
					'usrDescription' 		=> $this->input->post('aboutyourself')
				);
			$return = $this->db->insert('tbl_user_profile', $userProfile);
		}
		return $return;
	}
	
	/**
	* @ Function Name		: groupList
	* @ Function Params		: 
	* @ Function Purpose 	: function to get all group  info
	* @ Function Returns	: 
	**/
	
	function groupList() { 
		$this->db->select("*");
		$this->db->from("tbl_roles"); 
		return $this->db->get()->result();;
	}
	
	/**
	* @ Function Name		: getDetails
	* @ Function Params		: 
	* @ Function Purpose 	: function to edit existing user by admin
	* @ Function Returns	: 
	**/
	
	function getDetails($id) {
		$this->db->select("*");
		$this->db->from("tbl_users"); 
		$this->db->join('tbl_user_profile', 'tbl_user_profile.user_id = tbl_users.id', 'left');
		$this->db->where("tbl_users.id", $id);
		return $this->db->get()->row();
	}
	
	/**
	* @ Function Name		: editUserData
	* @ Function Params		: 
	* @ Function Purpose 	: function to edit existing user by admin
	* @ Function Returns	: 
	**/
	public function editUserData($usrId) {
		$return = FALSE;
		$id = $usrId;
        $dob = $this->input->post('txtDob'); 
		if(isset($dob) && !empty($dob))
			$dob = date('Y-m-d', strtotime($dob));
 
		$userdetails = array(
			'user_group_id'			=> $this->input->post('groupname'),
			'usrUsername' 			=> $this->input->post('username'),  
			'usrEmail' 				=> $this->input->post('email'),
			'usrStatus' 			=> '1', 
			'usrFirstName' 			=> $this->input->post('txtFirstName'),
			'usrLastName' 			=> $this->input->post('txtLastName')
		);
	
		$pass = $this->input->post('password');

		if(!empty($pass) && $pass!=''){	
			$pwd=md5($pass);
			$userdetails['usrPassword']=$pwd;			
		}	

		$where = array('id' => $id);
		if ($this->db->update('tbl_users', $userdetails, $where)) {
				$userProfile = array(  
					'usrGender'				=> $this->input->post('txtGender'),
					'usrDOB'				=>$dob,
					'usrCountryId' 			=> $this->input->post('cmbCountry'),
					'usrState' 				=> $this->input->post('cmbState'),
					'usrCity' 				=> $this->input->post('cmbCity'),
					'usrContact' 			=> $this->input->post('usrContact'),
					'usrAddress' 			=> $this->input->post('usraddress'),
					'usrDescription' 		=> $this->input->post('aboutyourself')
				);
				$where1 = array('user_id' => $usrId);
				$this->db->update('tbl_user_profile', $userProfile, $where1); 
			return TRUE;
		} else {
			return FALSE;
		}
		return $return;
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
    public function getUnCode($email){
        $this->db->select("usrUniqueCode");
        $this->db->from("tbl_users");
        $this->db->where("usrEmail",$email,true);
        $res = $this->db->get();
        return $res->row();
    }
	
	/* all city list */
	
	public function allcityList($statId=''){
		$this->db->select('geoId,geoName');
		$this->db->where('geoType',4);
		$this->db->where('geoIsActive','y');
		$this->db->where('geoIsTrash','n');
		$this->db->order_by('geoName','asc');
		$this->db->from('tbl_geography');
		$result=$this->db->get();
		$allcitylist=$result->result();
		return $allcitylist;
	}

	/* select city  asper state Id */
	public function cityList($statId=''){
		if(isset($statId) && $statId!=''){
			$val=$statId;
		}
		$this->db->select('geoId,geoName');
		$this->db->where('geoPid', $val);
		$this->db->where('geoType',4);
		$this->db->where('geoIsActive','y');
		$this->db->where('geoIsTrash','n');
		$this->db->order_by('geoName','asc');
		$this->db->from('tbl_geography');
		$result=$this->db->get();
		$selectedStats=$result->result();
		return $selectedStats;
	}
	
	/* Get all state List */
	
	public function allstateList(){	
		$this->db->select('geoId,geoName');
		$this->db->where('geoType',3);
		$this->db->where('geoIsActive','y');
		$this->db->where('geoIsTrash','n');
		$this->db->order_by('geoName','asc');
		$this->db->from('tbl_geography');
		$result=$this->db->get();
		$allstateList=$result->result();
		return $allstateList;
	}
	
	/* select state asper country Id */
	
	public function stateList($countryId=''){	
		if (isset($countryId) && $countryId != '') {
            $val = $countryId;
        }else {
			$val = '';
		}
		$this->db->select('geoId,geoName');
		$this->db->where('geoPid',$val);
		$this->db->where('geoType',3);
		$this->db->where('geoIsActive','y');
		$this->db->where('geoIsTrash','n');
		$this->db->order_by('geoName','asc');
		$this->db->from('tbl_geography');
		$result=$this->db->get();
		$slctstateList=$result->result();
		return $slctstateList;
	}
	
	public function getAdminEmail(){
		$this->db->select('admEmail');
		$this->db->where('admId',$this->session->userdata('id'));
		return $this->db->get('tbl_admin')->row();
	}
	
	public function changeEmail(){
		$this->db->where('admId',$this->session->userdata('id'));
		if($this->db->update('tbl_admin',array('admEmail'=>$this->input->post('txtadminEmail')))){
			return TRUE;
		}else{
			return FASLE;
		}
	}

}
?>
