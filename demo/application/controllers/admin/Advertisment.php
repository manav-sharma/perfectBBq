<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
	
class Advertisment extends MY_Controller {
	
	/**
	* @ Function Name		: __construct
	* @ Function Params	: 
	* @ Function Purpose 	: initilizing variable and providing pre functionalities
	* @ Function Returns	: 
	*/
	public function __construct() {
		parent::__construct();   
		$this->load->model('admin/advertisment/Advertismentmodel'); 
		$this->load->model('admin/category/Categorymodel');
	}
	/**
	* @ Function Name		: index
	* @ Function Params	: 
	* @ Function Purpose 	: Managing advertisement and login and controls redirection
	* @ Function Returns	: 
	*/
	public function index() {
		if ($this->session->userdata('logged_in') == TRUE) {
            $orderingVar = array();
			//$paging = array();
            $joinTableVar = array();
            $whereConditionVar = array();
            $likeConditionArray = array();
            $customConditionVar = '';
            $filterOrSort = $this->input->post("filterOrSort");
            //sorting parameters
            $sortBy = $this->input->post('sortBy');
            if (!$sortBy) {
                $sortBy = "advDateCreated_desc";
            }
			
            $sortBy = explode("_", $sortBy);
            $orderBy = $sortBy[1];
            $sortBy = $sortBy[0]; 
			//custom
			$orderingVar['ipp'] = ($this->input->post("ipp") ? $this->input->post("ipp") : 100);
            $orderingVar['pgn'] = ($this->input->post("pgn") ? $this->input->post("pgn") : 1);
			
            if ($filterOrSort == "sort") {
                $orderBy = ($orderBy == "desc") ? "asc" : "desc";
            }
			
            $orderingVar['orderBy'] = $data['orderBy'] = $orderBy;
            $orderingVar['sortBy'] = $data['sortBy'] = $sortBy;
			
			// paging set 
			 
			
			 // Join parameters  
			 
			$joinTableVar = array();
			
            $advStatus=$this->input->post("advStatus");    
        
            $array = array('advStatus'); 
			$likeConditionArray = compact($array);
			  
			 $customConditionVar=  array(  
				array( 
					'tableWithCondition'		=> "(advTitleEng like '%".'FieldValue'."%')",
					'fieldName'					=> 'advTitleEng'
				),
				array( 
					'tableWithCondition'		=> "(advTitleDutch like '%".'FieldValue'."%')",
					'fieldName'					=> 'advTitleDutch'
				),
				array(
					'tableWithCondition'		=> "DATE_FORMAT(advDateCreated ,'%Y-%m-%d') >= 'FieldValue'",
					'fieldName'					=> 'advDateFrom',
					'condition'					=> 'AND',
					'Value'						=> 'From'
				), 
				array(
					'tableWithCondition'		=> "DATE_FORMAT(advDateCreated ,'%Y-%m-%d') <= 'FieldValue'",
					'fieldName'					=> 'advDateTo',
					'condition'					=> 'AND',
					'Value'						=> 'To'
				)
			);
			    
			
            $result = getList('tbl_advertisement', "*", $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar);
			// echo "<pre>"; print_r($result);die;
            /* Export CSV functionality - START */
            if ($this->input->post("exportCSV") == "exportCSV") {
				$this->__exposrtCSV($result);
            }
            /* Export CSV functionality - END */
            $data["advertismentListing"] = $result;
            $data["postData"] = $this->input->post();
            $data["sortBy"] = $orderingVar["sortBy"];
            $data["orderBy"] = $orderingVar["orderBy"];
            $data['title'] = "Advertisement";
            $this->load->view('admin/advertisment/home', $data);
        } else {
            $data['title'] = "Administrator Login";
            $message = "Please login to your account to access internal pages";
            $this->form_validation->_error_array['password'] = $message;
            $this->load->view('admin/users/login', $data);
        }
	} 
	
	/**
	* @ Function Name		: addAdv
	* @ Function Params	: 
	* @ Function Purpose 	: admin can add new Advertisement
	* @ Function Returns	: 
	*/
    function addAdv() 
    {
		$data = array();
		$allCat = $this->Categorymodel->allActiveCatgegory();
		$data['allCat'] = $allCat; 
		$submit = $this->input->post('btnSubmit');
		if (!empty($_POST)) {
			$rul = array(
				array(
					'field' => 'txtTitleEng',
					'label' => 'Name (Eng)',
					'rules' => 'trim|required|is_unique[tbl_advertisement.advTitleEng]'
				),
				array(
					'field' => 'txtTitleDutch',
					'label' => 'Name (German)',
					'rules' => 'trim|required|is_unique[tbl_advertisement.advTitleDutch]'
				),
			);
			$this->form_validation->set_rules($rul);
			if ($this->form_validation->run()) {
				$filename = "";
				if(isset($_FILES['txtImage']) && !empty($_FILES['txtImage'])  && $_FILES["txtImage"]["name"] != "") 
				{ 
					$allowedExts = array("gif", "jpeg", "jpg", "png");
					$filename = time().$_FILES["txtImage"]["name"];
					$config['upload_path'] = ROOT_PATH."/bbq_images/";			
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = $filename;
					$this->upload->initialize($config);
					$this->upload->do_upload('txtImage');
				}
				$postData = $this->input->post();
				
				$advPostData = array();
				$advPostData['advTitleEng'] = $postData['txtTitleEng'];
				$advPostData['advTitleDutch'] = $postData['txtTitleDutch'];
				$advPostData['advImage'] = $filename;
				$advPostData['advCat'] = implode(",",$postData['txtCatId']);
				$advPostData['advDateCreated'] = date('Y-m-d H:i:s');
				//echo "<pre>"; print_r($advPostData);die;
				if ($insert_id = $this->Advertismentmodel->addAdv($advPostData)) {
					$this->session->set_flashdata('item', '<div class="warning pos">Advertisment added successfully.</div>');
					redirect('admin/advertisment');
				} else {
					$this->session->set_flashdata('item', '<div class="warning neg">There is some error while creating Advertisment. Please try again!</div>');
				}
			}
		}
		$data['title'] = "Add New Advertisement"; 
		$this->load->view('admin/advertisment/addAdv', $data);
    }
    
    
    /**
	* @ Function Name		: checkTitleEngExist
	* @ Function Params	: 
	* @ Function Purpose 	: check title(Eng) existence.
	* @ Function Returns	: 
	*/
	public function checkTitleEngExist()
	{
		$validateError = "This Title is already exist!";
		$validateId    = $this->input->get('fieldId');
		$arrayToJs     = array();
		$arrayToJs[0]  = $validateId;
		$title     = $this->input->get('fieldValue');
		$this->db->select("*");
		$this->db->where("advTitleEng", $title);
		$result = $this->db->get("tbl_advertisement");
		$data = $result->result();
		
		if (!empty($data)) {
			$arrayToJs[1] = false;
			echo json_encode($arrayToJs);
			exit;
		} else {
			$arrayToJs[1] = true;
			echo json_encode($arrayToJs);
			exit;
		}
	}
	
	/**
	* @ Function Name		: checkTitleDutchExist
	* @ Function Params	: 
	* @ Function Purpose 	: check title(Dutch) existence.
	* @ Function Returns	: 
	*/
	public function checkTitleDutchExist()
	{
		$validateError = "This Title is already exist!";
		$validateId    = $this->input->get('fieldId');
		$arrayToJs     = array();
		$arrayToJs[0]  = $validateId;
		$title = $this->input->get('fieldValue');
		$this->db->select("*");
		$this->db->where("advTitleDutch", $title);
		$result = $this->db->get("tbl_advertisement");
		$data = $result->result();
		
		if (!empty($data)) {
			$arrayToJs[1] = false;
			echo json_encode($arrayToJs);
			exit;
		} else {
			$arrayToJs[1] = true;
			echo json_encode($arrayToJs);
			exit;
		}
	}
    
    
	/**
	* @ Function Name		: active
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: make advtisment status active
	* @ Function Returns	: 
	*/
    function active($id = '') {
        $result = false;
        if (!empty($id)) {
            $result = $this->Advertismentmodel->status($id, '1');
        } else { 
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Advertismentmodel->status($id, '1');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Active status set for selected advertisment(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected advertismentc(s), please try again.<div>');
        }
        redirect('admin/advertisment');
    }
	
	/**
	* @ Function Name		: delete
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: delete advertisment functionality
	* @ Function Returns	: 
	*/
    function delete($id = '') {
        $result = false;
        if (!empty($id)) {
            $result = $this->Advertismentmodel->deleteAdv($id);
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Advertismentmodel->deleteAdv($id);
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Selected advertisment(s) deleted successully</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">Selected advertisment(s) is not deleted, please try again</div>');
        }
        redirect('admin/advertisment');
	}
		
	/**
	* @ Function Name		: inactive
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: make advertisment status inactive
	* @ Function Returns	: 
	*/
    function inactive($id = '') {
        $result = false;
        if (!empty($id)) {
            $result = $this->Advertismentmodel->status($id, '0');
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Advertismentmodel->status($id, '0');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Inactive status set for selected advertisment(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected advertisment(s), please try again.</div>');
        }
        redirect('admin/advertisment');
    } 
}
?>
