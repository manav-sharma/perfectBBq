<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Utility
 * class for general  utitity of the project
 * @package fashboard
 * @author Admin
 * @copyright 2012
 * @version $Id$
 * @access public
 */
class Utility {

    var $CI;

    /**
     * Utility::__construct()
     * @function constructor of utility library class
     * @return void
     */
    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('session');
    }

     function show_messages($msgString='', $type='') {
        $successMessage = $this->CI->session->flashdata('item');
        $successMessage2 = $this->CI->session->userdata('successMessage');
        if (!empty($successMessage2)) {
            $this->CI->session->unset_userdata('successMessage');
            $successMessage = $successMessage2;
        }
        $negativeMessage = $this->CI->session->userdata('negativeMessage');
        if (!empty($negativeMessage)) {
            $this->CI->session->unset_userdata('negativeMessage');
        }
        $errors = validation_errors();
        if (!empty($errors)) {
            //validation error
            $liOccurence = substr_count($errors, '<li>');
            if($liOccurence > 1)
            {
                $message = '<div class="warning ">
                    Please fill up all mandatory form fields. Following are the validation rules:
                    <ul>
                    '.$errors."</ul></div>";
            }
            else
            {
                $errors = strip_tags($errors);
                $message = '<div class="warning ">'.$errors."</div>";                
            }
        } else if (!empty($successMessage)) {
            //success message through session
            $message = '<div class="warning ">' . $successMessage . '</div>';
        } else if (!empty($negativeMessage)) {
            //success message through session
            $liOccurence = substr_count($negativeMessage, '<li>');
            if($liOccurence > 1)
            {
                $message = '<div class="warning">
                    Please fill up all mandatory form fields. Following are the validation rules:
                    <ul>
                    '.$negativeMessage."</ul></div>";
            }
            else
            {
                $negativeMessage = strip_tags($negativeMessage);
                $message = '<div class="warning">'.$negativeMessage."</div>";                
            }
        } else if ($msgString != '' && $type != '') {
            //message pass through view
            if(($type == 'neg') || ($type == 'negativeMessage'))
            {
                $liOccurence = substr_count($msgString, '<li>');
                if($liOccurence > 1)
                {
                    $message = '<div class="warning">
                        Please fill up all mandatory form fields. Following are the validation rules:
                        <ul>
                        '.$msgString."</ul></div>";
                }
                else
                {
                    $msgString = strip_tags($msgString);
                    $message = '<div class="warning">'.$msgString."</div>";                
                }                
            }
            else
                $message = '<div class="warning' . $type . ' ">' . $msgString . '</div>';
        } else {
            $message = '<div class="warning hidden"></div>';
        }
        return $message;
    }
    /**
     * Utility::show_message()
     * 
     * @param string $msgString 
     * @param string $type as pos or neg
     * @return message html
     */
    function show_message($msgString='', $type='') {
        $successMessage = $this->CI->session->flashdata('item');
        $successMessage2 = $this->CI->session->userdata('successMessage');
        if (!empty($successMessage2)) {
            $this->CI->session->unset_userdata('successMessage');
            $successMessage = $successMessage2;
        }
        $negativeMessage = $this->CI->session->userdata('negativeMessage');
        if (!empty($negativeMessage)) {
            $this->CI->session->unset_userdata('negativeMessage');
        }
        $errors = validation_errors();
        if (!empty($errors)) {
            //validation error
            $liOccurence = substr_count($errors, '<li>');
            if($liOccurence > 1)
            {
                $message = '<div class="warning neg">
                    Please fill up all mandatory form fields. Following are the validation rules:
                    <ul>
                    '.$errors."</ul></div>";
            }
            else
            {
                $errors = strip_tags($errors);
                $message = '<div class="warning neg">'.$errors."</div>";                
            }
        } else if (!empty($successMessage)) {
            //success message through session
            $message = '<div class="warning pos">' . $successMessage . '</div>';
        } else if (!empty($negativeMessage)) {
            //success message through session
            $liOccurence = substr_count($negativeMessage, '<li>');
            if($liOccurence > 1)
            {
                $message = '<div class="warning neg">
                    Please fill up all mandatory form fields. Following are the validation rules:
                    <ul>
                    '.$negativeMessage."</ul></div>";
            }
            else
            {
                $negativeMessage = strip_tags($negativeMessage);
                $message = '<div class="warning neg">'.$negativeMessage."</div>";                
            }
        } else if ($msgString != '' && $type != '') {
            //message pass through view
            if(($type == 'neg') || ($type == 'negativeMessage'))
            {
                $liOccurence = substr_count($msgString, '<li>');
                if($liOccurence > 1){
                    $message = '<div class="warning neg">
                        Please fill up all mandatory form fields. Following are the validation rules:
                        <ul>
                        '.$msgString."</ul></div>";
                }else{
                    $msgString = strip_tags($msgString);
                    $message = '<div class="warning neg">'.$msgString."</div>";                
                }                
            }
            else
                $message = '<div class="warning ' . $type . ' ">' . $msgString . '</div>';
        } else {
            $message = '<div class="warning hidden"></div>';
        }
        return $message;
    }

    /**
     * Utility::dateFormat()
     * @function 
     * @param mixed $dateTime
     * @param string $format
     * @param $tsp if true then return timestamp
     * @return
     */
    function dateFormat($dateTime, $format="", $tsp=false) {
        if (empty($dateTime))
            return '';

        $y = $m = $d = $h = $i = $s = 0;

        $date = $dateTime;

        $dateArr = explode('/', $date);
        if (sizeof($dateArr) == 3) {
            $date = $dateArr[2] . '-' . $dateArr[0] . '-' . $dateArr[1];
        }

        list($y, $m, $d) = explode("-", $date);

        $y = (empty($y)) ? 0 : (int) $y;
        $m = (empty($m)) ? 0 : (int) $m;
        $d = (empty($d)) ? 0 : (int) $d;


        if (empty($y) or empty($m) or empty($d))
            return '';

        $timestamp = mktime(0, 0, 0, $m, $d, $y);

        if ($tsp == true) {
            return $timestamp;
        }

        return date($format, $timestamp);
    }

    /**
     * Utility::create_guid()
     * @function create unique guid
     * @param integer $limit charcter limit 
     * @return guid
     */
    public function createGuid($limit=5) {

        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz0123456789";
        srand((double) microtime() * 1000000);
        $i = 1;
        $pass = '';

        while ($i <= $limit) {

            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }
    
     /**
     * Utility::create_guid()
     * @function create unique guid
     * @param integer $limit charcter limit 
     * @return guid
     */
    public function isAdminLoggedIn() {
        $userid = $this->CI->session->userdata('userId');
        if(empty($userid))
        {
            redirect('admin/users/login');
        }
        else
        {
            return true;
        }
    }
    
    /**
     * Utility::create_guid()
     * @function create unique guid
     * @param integer $limit charcter limit 
     * @return guid
     */
    public function getUserDetails($userId) {
        $data = $this->CI->db->query("SELECT * FROM tbl_users  WHERE uid = '$userId' ");
        $row = $data->row();
        return $row;
    }
	
    /**
     * Utility::create_guid()
     * @function create unique guid
     * @param integer $limit charcter limit 
     * @return guid
     */
    public function getUserDetailsWithDp($userId) {
		$data = $this->CI->db->query("SELECT `usrp`.*, `grp`.`geo_name` as state_name, `grpc`.`geo_name` as city_name, `imgs`.`image_title`, `imgs`.`image_path`
			FROM (`tbl_user_profile` usrp)
			LEFT JOIN `tbl_geography` grp ON `usrp`.`state` = `grp`.`geoid`
			LEFT JOIN `tbl_geography` grpc ON `usrp`.`city` = `grpc`.`geoid`
			LEFT JOIN `tbl_user_images` imgs ON `usrp`.`display_pic` = `imgs`.`imageid`
			WHERE `usrp`.`uid` = '".$userId."'");
		$row = $data->row();
		return $row;
    }
    
    /**
     * Utility::create_guid()
     * @function create unique guid
     * @param integer $limit charcter limit 
     * @return guid
     */
    public function getEventDetailsById($eventId) {
       $data = $this->CI->db->query("SELECT * FROM tbl_events  WHERE eid = '$eventId' ");
        $row = $data->row();
        return $row;
    }
	
     /**
     * Method to get total stock for an event
     */
    public function getLocation($eventId) {
        
        $this->CI->db->select("evts.*,geos.geo_name as state_name,geoc.geo_name as city_name");
        $this->CI->db->from("tbl_events evts");
        $this->CI->db->where('eid', $eventId);
        $this->CI->db->join('tbl_geography geos','geos.geoid = evts.state');
        $this->CI->db->join('tbl_geography geoc','geoc.geoid = evts.city');
        $result = $this->CI->db->get();
        return $result->row();
    }
    
	
/*     * ********************************************************************************************************************* */

     /**
     * Utility::create_guid()
     * @function create unique guid
     * @param integer $limit charcter limit 
     * @return guid
     */
    public function getMessageNotification($eventId) {
        $sql = "SELECT * FROM tbl_user_messages  WHERE message_to = '$eventId' AND message_from <> '$eventId' AND is_read = '0' order by `id` DESC LIMIT 10";
        $data = $this->CI->db->query($sql);
        $row = $data->result_array();
        return $row;
    }


    /**
     * Method to get user's profile detail With Image
     */
    public function getUserProfileWithImage($uid="") {
        $this->CI->db->_reset_select();
        $this->CI->db->select("prf.uid,img.image_path,usr.username");
        $this->CI->db->join("tbl_user_images img", "prf.display_pic = img.imageid", "LEFT");
        $this->CI->db->join("tbl_users usr", "prf.uid = usr.uid", "LEFT");
        $this->CI->db->from("tbl_user_profile prf");
        $this->CI->db->where("prf.uid", $uid, TRUE);
        $res = $this->CI->db->get();
        return $res->row();
    }

    /**
     * Utility::create_guid()
     * @function create unique guid
     * @param integer $limit charcter limit 
     * @return guid
     */
    public function getFriendRequest($userId) {
        $this->CI->db->select("ufr.*,usr.first_name,usr.last_name");
        $this->CI->db->join("tbl_users usr", "ufr.uid = usr.uid", "LEFT");
        $this->CI->db->from("tbl_friend_requests ufr");
        $this->CI->db->where("ufr.fid", $userId);
        $this->CI->db->where("ufr.status", "0");
        $data = $this->CI->db->get();
        $row = $data->result_array();
        return $row;
    }

    /**
     * Utility::create_guid()
     * @function create unique guid
     * @param integer $limit charcter limit 
     * @return guid
     */
    public function getRequestAction($userId) {
        $sql = "SELECT `ufr`.*, `usr`.`first_name`, `usr`.`last_name` FROM (`tbl_friend_requests` ufr) JOIN `tbl_users` usr ON `usr`.`uid` = `ufr`.`fid` WHERE `ufr`.`uid` = '".$userId."' AND (`ufr`.`status` = '1' OR `ufr`.`status` = '2') AND `notification_status` = '0'";
        $data = $this->CI->db->query($sql);
        $row = $data->result_array();
        return $row;
    }

    
    
    /**
     * Utility::create_guid()
     * @function create unique guid
     * @param integer $limit charcter limit 
     * @return guid
     */
    public function getRequestActionNot($userId) {
        $sql = "SELECT `ufr`.*, `usr`.`first_name`, `usr`.`last_name` FROM (`tbl_friend_requests` ufr) JOIN `tbl_users` usr ON `usr`.`uid` = `ufr`.`fid` WHERE `ufr`.`uid` = '".$userId."' AND (`ufr`.`status` = '1' OR `ufr`.`status` = '2')";
        $data = $this->CI->db->query($sql);
        $row = $data->result_array();
        return $row;
    }
    
    
    
    function concat() {
        $vars = func_get_args();
        $array = array();
        foreach ($vars as $var) {
            if (is_array($var)) {
                foreach ($var as $val) {
                    $array[] = $val;
                }
            } else {
                $array[] = $var;
            }
        }
        return $array;
    }
    
    function aasort(&$array, $key) {
        $sorter = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }
        $array = $ret;
    }

    
    /**
     * Utility::checkEventTickets()
     * @function check event tickets
     * @param integer $limit charcter limit 
     * @return guid
     */
    public function checkEventTickets($userId = '',$eventId = '') {
        $sql = "SELECT * FROM tbl_user_event_tickets  WHERE uid = '$userId' AND eid = '$eventId'";
        $data = $this->CI->db->query($sql);
        $row = $data->result_array();
        return $row;
    }
    /*     * ******************************************************************************************************************** */
}

/* End of file utility.php */
/* Location: ./application/libraries/utility.php */