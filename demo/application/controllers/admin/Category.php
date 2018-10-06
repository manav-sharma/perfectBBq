<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
	
class Category extends MY_Controller {
	
	/**
	* @ Function Name		: __construct
	* @ Function Params	: 
	* @ Function Purpose 	: initilizing variable and providing pre functionalities
	* @ Function Returns	: 
	*/
	public function __construct() {
		parent::__construct();   
		$this->load->model('admin/category/Categorymodel'); 
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
                $sortBy = "c1.catDateCreated_desc";
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
			 
			$joinTableVar = array(
				array(
				'tableName'		=> 'tbl_category as c2',
				'joinCondition'	=> 'c1.catParentId=c2.catId',
				'joinType'		=> 'LEFT'
				)
			);
			
            $catStatus=$this->input->post("catStatus");    
        
            $array = array('catStatus'); 
			//print_r(compact($array));die;
            $likeConditionArray = compact($array);
			  
			 $customConditionVar=  array(  
				array( 
					'tableWithCondition'		=> "(c1.catTitleEng like '%".'FieldValue'."%')",
					'fieldName'					=> 'catTitleEng'
				),
				array( 
					'tableWithCondition'		=> "(c1.catTitleDutch like '%".'FieldValue'."%')",
					'fieldName'					=> 'catTitleDutch'
				),
				array(
					'tableWithCondition'		=> "DATE_FORMAT(c1.catDateCreated ,'%Y-%m-%d') >= 'FieldValue'",
					'fieldName'					=> 'catDateFrom',
					'condition'					=> 'AND',
					'Value'						=> 'From'
				), 
				array(
					'tableWithCondition'		=> "DATE_FORMAT(c1.catDateCreated ,'%Y-%m-%d') <= 'FieldValue'",
					'fieldName'					=> 'catDateTo',
					'condition'					=> 'AND',
					'Value'						=> 'To'
				),
				array( 
					'tableWithCondition'		=> "(c1.catLayout = 'FieldValue')",
					'fieldName'					=> 'catLayout'
				),
				array( 
					'tableWithCondition'		=> "(c1.catPosition = 'FieldValue')",
					'fieldName'					=> 'catPosition'
				)
			);
			    
			
            $result = getCategoryList('tbl_category as c1', "c1.catId, c1.catTitleEng, c1.catTitleDutch, c1.catStatus, c1.catParentId, if(c1.catParentId =0, '', c2.catTitleEng) as catParentNameEng, if(c1.catParentId =0, '', c2.catTitleDutch) as catParentNameDutch, if(c1.catAdvImage != '', c1.catAdvImage, '') as catAdvImage, c1.catAdvSwitch, c1.catLayout, c1.catPosition, c1.catDateCreated", $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar);
            /* Export CSV functionality - START */
            if ($this->input->post("exportCSV") == "exportCSV") {
				$this->__exposrtCSV($result);
            }
            /* Export CSV functionality - END */
            $data["categoryListing"] = $result;
            $data["postData"] = $this->input->post();
            $data["sortBy"] = $orderingVar["sortBy"];
            $data["orderBy"] = $orderingVar["orderBy"];
            $data['title'] = "Categories";
            $this->load->view('admin/category/home', $data);
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
        $array = array(array('Title (English)','Title (German)','Parent Title (English)','Parent Title (German)','Layout','Position','Status','Date Created'));
		 
        foreach ($dataArray as $key => $cat) {
            $tempArray = array();
			$status='';
			if($cat->catStatus)
				$status='Active';
			else
				$status='Inactie';
				
            $tempArray['Title (English)'] = $cat->catTitleEng;
            $tempArray['Title (German)'] = $cat->catTitleDutch;
            $tempArray['Parent Title (English)'] = $cat->catParentNameEng;
            $tempArray['Parent Title (German)'] = $cat->catParentNameDutch;
            $tempArray['Layout'] = $cat->catLayout;
            $tempArray['Position'] = $cat->catPosition;
            $tempArray['Status'] = $status;
            $tempArray['Date Created'] = date(ADMIN_DATE_FORMAT, strtotime($cat->catDateCreated));
            $array[] = $tempArray;
        } 
        $this->load->helper('csv');
        echo array_to_csv($array, 'category(' . date('d-M-Y') . ').csv');
        exit;
    }
	
	/**
	* @ Function Name		: addCategory
	* @ Function Params	: 
	* @ Function Purpose 	: admin can add new category
	* @ Function Returns	: 
	*/
    function addCategory() 
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
					'rules' => 'trim|required|is_unique[tbl_category.catTitleEng]'
				),
				array(
					'field' => 'txtTitleDutch',
					'label' => 'Name (German)',
					'rules' => 'trim|required|is_unique[tbl_category.catTitleDutch]'
				),
				array(
					'field' => 'txtLayout',
					'label' => 'Layout',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'txtPosition',
					'label' => 'Position',
					'rules' => 'trim|required'
				)
			);
			$this->form_validation->set_rules($rul);
			if ($this->form_validation->run()) { 
				$filename = "";
				if(isset($_FILES['txtAdvImage']) && !empty($_FILES['txtAdvImage'])  && $_FILES["txtAdvImage"]["name"] != "") 
				{
					$allowedExts = array("gif", "jpeg", "jpg", "png");
					$filename = time().$_FILES["txtAdvImage"]["name"];
					$config['upload_path'] = ROOT_PATH."/bbq_images/";			
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = $filename;
					$this->upload->initialize($config);
					$this->upload->do_upload('txtAdvImage');
				}
				$postData = $this->input->post();
				$catPostData = array();
				$catPostData['catTitleEng'] = $postData['txtTitleEng'];
				$catPostData['catTitleDutch'] = $postData['txtTitleDutch'];
				$catPostData['catParentId'] = $postData['txtCatParentId'];
				$catPostData['catAdvImage'] = $filename;
				$catPostData['catAdvSwitch'] = $postData['txtAdvSwitch'];
				$catPostData['catLayout'] = $postData['txtLayout'];
				$catPostData['catPosition'] = $postData['txtPosition'];
				$catPostData['catDateCreated'] = date('Y-m-d H:i:s');
				if ($insert_id = $this->Categorymodel->addCategory($catPostData)) {
					$this->session->set_flashdata('item', '<div class="warning pos">Category added successfully.</div>');
					redirect('admin/category');
				} else {
					$this->session->set_flashdata('item', '<div class="warning neg">There is some error while creating category. Please try again!</div>');
				}
			}
		}
		$data['title'] = "Add New Category"; 
		$this->load->view('admin/category/addCategory', $data);
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
		$this->db->where("catTitleEng", $title);
		$result = $this->db->get("tbl_category");
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
		$this->db->where("catTitleDutch", $title);
		$result = $this->db->get("tbl_category");
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
	* @ Function Name		: editCategory
	* @ Function Params	: 
	* @ Function Purpose 	: Edit category functionality by id
	* @ Function Returns	: 
	*/
    function editCategory($id='') { 
		$data = array();
		$allCat = $this->Categorymodel->allActiveCatgegory();
		$data['allCat'] = $allCat; 
		
		$categoryData = $this->Categorymodel->getCategoryDetail($id);
		$categoryInfo 	= array(
			"catId" => $categoryData->catId,
			"catTitleEng" => $categoryData->catTitleEng,
			"catTitleDutch" => $categoryData->catTitleDutch,
			"catStatus" => $categoryData->catStatus,
			"catParentId" => $categoryData->catParentId,
			"catAdvImage" => $categoryData->catAdvImage,
			"catAdvSwitch" => $categoryData->catAdvSwitch,
			"catLayout" => $categoryData->catLayout,
			"catPosition" => $categoryData->catPosition
		); 
		$data['detail'] = $categoryInfo;
		if (!empty($_POST)) { //echo "<pre>"; print_r($_POST);die;
			$oldTitleEng = $categoryData->catTitleEng;
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
					'label' => 'Name (German)',
					'rules' => 'trim|required|is_unique[tbl_category.catTitleEng]'
				);
			}
			$oldTitleDutch = $categoryData->catTitleDutch;
			$newTitleDutch = $_POST['txtTitleDutch'];
			if (trim($oldTitleDutch) == trim($newTitleDutch)) {
				$rule2 = array(
					'field' => 'txtTitleDutch',
					'label' => 'Name (Dutch)',
					'rules' => 'trim|required'
				);
			} else {
				$rule2 = array(
					'field' => 'txtTitleDutch',
					'label' => 'Name (German)',
					'rules' => 'trim|required|is_unique[tbl_category.catTitleDutch]'
				);
			}
			$rule3 = array(
					'field' => 'txtPosition',
					'label' => 'Position',
					'rules' => 'trim|required'
			);
			$rul = array();
			array_push($rul,$rule1,$rule2,$rule3);
			$this->form_validation->set_rules($rul);
			if ($this->form_validation->run()) {
				$filename = $categoryData->catAdvImage;
				if(isset($_FILES['txtAdvImage']) && !empty($_FILES['txtAdvImage']) && $_FILES["txtAdvImage"]["name"] != "") 
				{ 
					$allowedExts = array("gif", "jpeg", "jpg", "png");
					$filename = time().$_FILES["txtAdvImage"]["name"];
					$config['upload_path'] = ROOT_PATH."/bbq_images/";			
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = $filename;
					$this->upload->initialize($config);
					$this->upload->do_upload('txtAdvImage');
				}
				
				$catId = $this->input->post('txtId');
				$postData = $this->input->post();
				$catPostData = array();
				$catPostData['catTitleEng'] = $postData['txtTitleEng'];
				$catPostData['catTitleDutch'] = $postData['txtTitleDutch'];
				$catPostData['catParentId'] = $postData['txtCatParentId'];
				$catPostData['catAdvImage'] = $filename;
				$catPostData['catAdvSwitch'] = $postData['txtAdvSwitch'];
				$catPostData['catLayout'] = $postData['txtLayout'];
				$catPostData['catPosition'] = $postData['txtPosition'];
				$catPostData['catDateModified'] = date('Y-m-d H:i:s');
				//echo "<pre>"; print_r($catPostData);die;
				$res = $this->Categorymodel->editCategoryData($catPostData, $catId);
				/* Code end's here */
				if ($res) {
					$message = "<div class='warning pos'>Selected category edited successfully</div>";
					$this->session->set_flashdata('item', $message);
				} else {
					$message = "<div class='warning neg'>Selected category edit unsuccessful</div>";
					$this->session->set_flashdata('item', $message);
				}
				redirect('admin/category');
			}
		} 
		$data['title'] = "Edit Category"; 
		$this->load->view('admin/category/editCategory', $data);
    }
	
	/**
	* @ Function Name		: active
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: make category status active
	* @ Function Returns	: 
	*/
    function active($id = '') {
        $result = false;
        if (!empty($id)) {
			$allChild = $this->Categorymodel->getSubCategory($id);
			$ids = array();
			array_push($ids,$id);
			if (!empty($allChild)) {
				foreach ($allChild as $chKey => $chVal) {
					$catId = $chVal->catId;
					array_push($ids,$catId);
				}
			}
			$id = (is_array($ids) && count($ids) != 0) ? implode(",", $ids) : "0";
            $result = $this->Categorymodel->status($id, '1');
        } else {
            $id_arr = $this->input->post('chkBox');
            $ids = array();
            foreach ($id_arr as $key => $val) {
				array_push($ids,$val);
				$allChild = $this->Categorymodel->getSubCategory($val);
				foreach ($allChild as $chKey => $chVal) {
					$catId = $chVal->catId;
					array_push($ids,$catId);
				}
			}
            $id = (is_array($ids) && count($ids) != 0) ? implode(",", $ids) : "0";
            if ($id != 0) {
                $result = $this->Categorymodel->status($id, '1');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Active status set for selected category(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected category(s), please try again.<div>');
        }
        redirect('admin/category');
    }
	
	/**
	* @ Function Name		: delete
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: function used to delete category(s)
	* @ Function Returns	: 
	*/
    function delete($id = '') {
        $result = false;
        if (!empty($id)) {
			$delCatArr = array();
			array_push($delCatArr, $id);
			$getChildCategory = $this->Categorymodel->getSubCategory($id);
			if (!empty($getChildCategory)) {
				foreach ($getChildCategory as $catKey => $catVal) {
					$childCatId = $catVal->catId;
					array_push($delCatArr, $childCatId);
				}
			}
			//echo "<pre>"; print_r($delCatArr);die;
            $result = $this->Categorymodel->deleteCategory($delCatArr);
        } else {
			$delCatArr = array();
            $id_arr = $this->input->post('chkBox');
            foreach ($id_arr as $key => $val) {
				array_push($delCatArr, $val);
				$getChildCategory = $this->Categorymodel->getSubCategory($val);
				if (!empty($getChildCategory)) {
					foreach ($getChildCategory as $catKey => $catVal) {
						$childCatId = $catVal->catId;
						array_push($delCatArr, $childCatId);
					}
				}
			}
            $result = $this->Categorymodel->deleteCategory($delCatArr);
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Selected category(s) deleted successully</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">Selected category(s) is not deleted, please try again</div>');
        }
        redirect('admin/category');
	}
		
	/**
	* @ Function Name		: inactive
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: make category status inactive
	* @ Function Returns	: 
	*/
    function inactive($id = '') {
        $result = false;
        if (!empty($id)) {
			$allChild = $this->Categorymodel->getSubCategory($id);
			$ids = array();
			array_push($ids,$id);
			if (!empty($allChild)) {
				foreach ($allChild as $chKey => $chVal) {
					$catId = $chVal->catId;
					array_push($ids,$catId);
				}
			}
			$id = (is_array($ids) && count($ids) != 0) ? implode(",", $ids) : "0";
			$result = $this->Categorymodel->status($id, '0');
        } else {
            $id_arr = $this->input->post('chkBox');
            $ids = array();
            foreach ($id_arr as $key => $val) {
				array_push($ids,$val);
				$allChild = $this->Categorymodel->getSubCategory($val);
				foreach ($allChild as $chKey => $chVal) {
					$catId = $chVal->catId;
					array_push($ids,$catId);
				}
			}
            $id = (is_array($ids) && count($ids) != 0) ? implode(",", $ids) : "0";
            if ($id != 0) {
                $result = $this->Categorymodel->status($id, '0');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Inactive status set for selected category(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected category(s), please try again.</div>');
        }
        redirect('admin/category');
    } 
}
?>
