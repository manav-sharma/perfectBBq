<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
	
class Basic extends MY_Controller {
	
	/**
	* @ Function Name		: __construct
	* @ Function Params	: 
	* @ Function Purpose 	: initilizing variable and providing pre functionalities
	* @ Function Returns	: 
	*/
	public function __construct() {
		parent::__construct();   
		$this->load->model('admin/basic/Basicmodel'); 
	}
	/**
	* @ Function Name		: index
	* @ Function Params	: 
	* @ Function Purpose 	: Managing categories and login and controls redirection
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
                $sortBy = "bbqDateCreated_desc";
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
			
            $bbqStatus=$this->input->post("bbqStatus");    
        
            $array = array('bbqStatus'); 
			$likeConditionArray = compact($array);
			  
			 $customConditionVar=  array(  
				array( 
					'tableWithCondition'		=> "(bbqTitleEng like '%".'FieldValue'."%')",
					'fieldName'					=> 'bbqTitleEng'
				),
				array( 
					'tableWithCondition'		=> "(bbqTitleDutch like '%".'FieldValue'."%')",
					'fieldName'					=> 'bbqTitleDutch'
				),
				array(
					'tableWithCondition'		=> "DATE_FORMAT(bbqDateCreated ,'%Y-%m-%d') >= 'FieldValue'",
					'fieldName'					=> 'bbqDateFrom',
					'condition'					=> 'AND',
					'Value'						=> 'From'
				), 
				array(
					'tableWithCondition'		=> "DATE_FORMAT(bbqDateCreated ,'%Y-%m-%d') <= 'FieldValue'",
					'fieldName'					=> 'bbqDateTo',
					'condition'					=> 'AND',
					'Value'						=> 'To'
				)
			);
			    
			
            $result = getList('tbl_bbqBasic', "*", $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar);
            /* Export CSV functionality - START */
            if ($this->input->post("exportCSV") == "exportCSV") {
				$this->__exposrtCSV($result);
            }
            /* Export CSV functionality - END */
            $data["basicListing"] = $result;
            $data["postData"] = $this->input->post();
            $data["sortBy"] = $orderingVar["sortBy"];
            $data["orderBy"] = $orderingVar["orderBy"];
            $data['title'] = "BBQ Basic";
            $this->load->view('admin/basic/home', $data);
        } else {
            $data['title'] = "Administrator Login";
            $message = "Please login to your account to access internal pages";
            $this->form_validation->_error_array['password'] = $message;
            $this->load->view('admin/users/login', $data);
        }
	} 
	
	/**
	* @ Function Name		: __exposrtCSV
	* @ Function Params	: $dataArray {array} 
	* @ Function Purpose 	: export CSV
	* @ Function Returns	: 
	*/
    private function __exposrtCSV($dataArray) {
        $array = array(array('Title (English)','Title (German)','Long Description (English)','Long Description (German)','Short Description (English)','Short Description (German)','Image','Status','Date Created'));
		 
        foreach ($dataArray as $key => $basic) {
            $tempArray = array();
			$status='';
			if($basic->bbqStatus)
				$status='Active';
			else
				$status='Inactie';
				
            $tempArray['Title (English)'] = $basic->bbqTitleEng;
            $tempArray['Title (German)'] = $basic->bbqTitleDutch;
            $tempArray['Long Description (English)'] = $basic->bbqLongDescEng;
            $tempArray['Long Description (German)'] = $basic->bbqLongDescDutch;
            $tempArray['Short Description (English)'] = $basic->bbqShortDescEng;
            $tempArray['Short Description (German)'] = $basic->bbqShortDescDutch;
            $tempArray['Image'] = SITE_URL."bbq_images/".$basic->bbqImage;
            $tempArray['Status'] = $status;
            $tempArray['Date Created'] = date(ADMIN_DATE_FORMAT, strtotime($basic->bbqDateCreated));
            $array[] = $tempArray;
        } 
        $this->load->helper('csv');
        echo array_to_csv($array, 'basic(' . date('d-M-Y') . ').csv');
        exit;
    }
	
	/**
	* @ Function Name		: addBasic
	* @ Function Params	: 
	* @ Function Purpose 	: admin can add new BBQ basic
	* @ Function Returns	: 
	*/
    function addBasic() 
    {
		$data = array();
		$submit = $this->input->post('btnSubmit');
		if (!empty($_POST)) {
			$rul = array(
				array(
					'field' => 'txtTitleEng',
					'label' => 'Name (Eng)',
					'rules' => 'trim|required|is_unique[tbl_bbqBasic.bbqTitleEng]'
				),
				array(
					'field' => 'txtTitleDutch',
					'label' => 'Name (German)',
					'rules' => 'trim|required|is_unique[tbl_bbqBasic.bbqTitleDutch]'
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
				
				$basicPostData = array();
				$basicPostData['bbqTitleEng'] = $postData['txtTitleEng'];
				$basicPostData['bbqTitleDutch'] = $postData['txtTitleDutch'];
				$basicPostData['bbqLongDescEng'] = stripslashes($postData['txtLongDescEng']);
				$basicPostData['bbqLongDescDutch'] = stripslashes($postData['txtLongDescDutch']);
				$basicPostData['bbqShortDescEng'] = stripslashes($postData['txtShortDescEng']);
				$basicPostData['bbqShortDescDutch'] = stripslashes($postData['txtShortDescDutch']);
				$basicPostData['bbqImage'] = $filename;
				$basicPostData['bbqDateCreated'] = date('Y-m-d H:i:s');
				if ($insert_id = $this->Basicmodel->addBasic($basicPostData)) {
					$basicIngEngData = $postData['txtIngEng'];
					$basicIngDutchData = $postData['txtIngDutch'];
					foreach ($basicIngEngData as $ingKey => $ingVal) {
						$ingArr = array();
						$ingArr['bbqId'] = $insert_id;
						$ingArr['bbqIngNameEng'] = $ingVal;
						$ingArr['bbqIngNameDutch'] = $basicIngDutchData[$ingKey];
						if (trim($ingVal) != "" || trim($basicIngDutchData[$ingKey]) != "") {
							$this->Basicmodel->addBasicIng($ingArr);
						}
					}
					$this->session->set_flashdata('item', '<div class="warning pos">BBQ Basic added successfully.</div>');
					redirect('admin/basic');
				} else {
					$this->session->set_flashdata('item', '<div class="warning neg">There is some error while creating BBQ basic. Please try again!</div>');
				}
			}
		}
		$data['title'] = "Add New BBQ-Basic"; 
		$this->load->view('admin/basic/addBasic', $data);
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
		$this->db->where("bbqTitleEng", $title);
		$result = $this->db->get("tbl_bbqBasic");
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
		$this->db->where("bbqTitleDutch", $title);
		$result = $this->db->get("tbl_bbqBasic");
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
	* @ Function Name		: editBasic
	* @ Function Params	: 
	* @ Function Purpose 	: Edit BBQ basic functionality by id
	* @ Function Returns	: 
	*/
    function editBasic($id='') {
		$data = array();
		$basicData = $this->Basicmodel->getBasicDetail($id);
		$basicInfo 	= array(
			"bbqId" => $basicData->bbqId,
			"bbqTitleEng" => $basicData->bbqTitleEng,
			"bbqTitleDutch" => $basicData->bbqTitleDutch,
			"bbqLongDescEng" => $basicData->bbqLongDescEng,
			"bbqLongDescDutch" => $basicData->bbqLongDescDutch,
			"bbqShortDescEng" => $basicData->bbqShortDescEng,
			"bbqShortDescDutch" => $basicData->bbqShortDescDutch,
			"bbqImage" => $basicData->bbqImage,
			"bbqStatus" => $basicData->bbqStatus,
		); 
		$data['detail'] = $basicInfo;
		$basicIngData = $this->Basicmodel->getBasicIng($id);
		$data['basicIng'] = $basicIngData;
		
		if (!empty($_POST)) {
			$oldTitleEng = $basicData->bbqTitleEng;
			$newTitleEng = $_POST['txtTitleEng'];
			
			if (trim($oldTitleEng) == trim($newTitleEng)) {
				$rule1 = array(
					'field' => 'txtTitleEng',
					'label' => 'Name (Eng)',
					'rules' => 'trim|required'
				);
			} else {
				$rule1 = array(
					'field' => 'txtTitleEng',
					'label' => 'Name (Eng)',
					'rules' => 'trim|required|is_unique[tbl_bbqBasic.bbqTitleEng]'
				);
			}
			$oldTitleDutch = $basicData->bbqTitleDutch;
			$newTitleDutch = $_POST['txtTitleDutch'];
			if (trim($oldTitleDutch) == trim($newTitleDutch)) {
				$rule2 = array(
					'field' => 'txtTitleDutch',
					'label' => 'Name (German)',
					'rules' => 'trim|required'
				);
			} else {
				$rule2 = array(
					'field' => 'txtTitleDutch',
					'label' => 'Name (German)',
					'rules' => 'trim|required|is_unique[tbl_bbqBasic.bbqTitleDutch]'
				);
			}
			 
			$rul = array();
			array_push($rul,$rule1,$rule2);
			$this->form_validation->set_rules($rul);
			if ($this->form_validation->run()) {
				$filename = $basicData->bbqImage;
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
				$bbqId = $this->input->post('txtId');
				$postData = $this->input->post();
				$basicPostData = array();
				$basicPostData['bbqTitleEng'] = $postData['txtTitleEng'];
				$basicPostData['bbqTitleDutch'] = $postData['txtTitleDutch'];
				$basicPostData['bbqLongDescEng'] = stripslashes($postData['txtLongDescEng']);
				$basicPostData['bbqLongDescDutch'] = stripslashes($postData['txtLongDescDutch']);
				$basicPostData['bbqShortDescEng'] = stripslashes($postData['txtShortDescEng']);
				$basicPostData['bbqShortDescDutch'] = stripslashes($postData['txtShortDescDutch']);
				$basicPostData['bbqImage'] = $filename;
				$basicPostData['bbqDateModified'] = date('Y-m-d H:i:s');
				
				$res = $this->Basicmodel->editBasicData($basicPostData, $bbqId);
				/* Code end's here */
				if ($res) {
					$this->db->where('bbqId', $bbqId);
					$this->db->delete('tbl_bbqBasicIngredient'); //Delete the ingredients
					$basicIngEngData = $postData['txtIngEng'];
					$basicIngDutchData = $postData['txtIngDutch'];
					foreach ($basicIngEngData as $ingKey => $ingVal) {
						$ingArr = array();
						$ingArr['bbqId'] = $bbqId;
						$ingArr['bbqIngNameEng'] = $ingVal;
						$ingArr['bbqIngNameDutch'] = $basicIngDutchData[$ingKey];
						if (trim($ingVal) != "" || trim($basicIngDutchData[$ingKey]) != "") {
							$this->Basicmodel->addBasicIng($ingArr);
						}
					}
					
					$message = "<div class='warning pos'>Selected BBQ basic edited successfully</div>";
					$this->session->set_flashdata('item', $message);
				} else {
					$message = "<div class='warning neg'>Selected BBQ basic edit unsuccessful</div>";
					$this->session->set_flashdata('item', $message);
				}
				redirect('admin/basic');
			}
		} 
		$data['title'] = "Edit BBQ-Basic"; 
		$this->load->view('admin/basic/editBasic', $data);
    }
	
	/**
	* @ Function Name		: active
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: make bbq basic status active
	* @ Function Returns	: 
	*/
    function active($id = '') { 
        $result = false;
        if (!empty($id)) {
            $result = $this->Basicmodel->status($id, '1');
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Basicmodel->status($id, '1');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Active status set for selected basic(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected basic(s), please try again.<div>');
        }
        redirect('admin/basic');
    }
	
	/**
	* @ Function Name		: delete
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: function used to delete basic(s)
	* @ Function Returns	: 
	*/
    function delete($id = '') { 
        $result = false;
        if (!empty($id)) {
			$delIdArr = array();
			array_push($delIdArr, $id);
            $result = $this->Basicmodel->deleteBasic($delIdArr);
        } else {
            $id_arr = $this->input->post('chkBox');
            $result = $this->Basicmodel->deleteBasic($id_arr);
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Selected bbq basic(s) deleted successully</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">Selected bbq basic(s) is not deleted, please try again</div>');
        }
		redirect('admin/basic');
	}
		
	/**
	* @ Function Name		: inactive
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: make basic status inactive
	* @ Function Returns	: 
	*/
    function inactive($id = '') {
        $result = false;
        if (!empty($id)) {
            $result = $this->Basicmodel->status($id, '0');
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Basicmodel->status($id, '0');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Inactive status set for selected basic(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected basic(s), please try again.</div>');
        }
        redirect('admin/basic');
    } 
}
?>
