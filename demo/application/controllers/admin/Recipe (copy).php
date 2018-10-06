<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
	
class Recipe extends MY_Controller {
	
	/**
	* @ Function Name		: __construct
	* @ Function Params	: 
	* @ Function Purpose 	: initilizing variable and providing pre functionalities
	* @ Function Returns	: 
	*/
	public function __construct() {
		parent::__construct();   
		$this->load->model('admin/recipe/Recipemodel'); 
		$this->load->model('admin/category/Categorymodel'); 
	}
	/**
	* @ Function Name		: index
	* @ Function Params	: 
	* @ Function Purpose 	: Managing recipes grid
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
                $sortBy = "rec.recDateCreated_desc";
            }
			
            $sortBy = explode("_", $sortBy);
            $orderBy = $sortBy[1];
            $sortBy = $sortBy[0]; 
            if (trim($sortBy) == 'catTitleEng' || trim($sortBy) == 'catTitleDutch') {
				$sortBy = "cat.".$sortBy;
			}
			//custom
			$orderingVar['ipp'] = ($this->input->post("ipp") ? $this->input->post("ipp") : 10);
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
				'tableName'		=> 'tbl_category as cat',
				'joinCondition'	=> 'rec.catId=cat.catId',
				'joinType'		=> 'LEFT'
				)
			);
			
            $recStatus=$this->input->post("recStatus");    
        
            $array = array('recStatus'); 
			//print_r(compact($array));die;
            $likeConditionArray = compact($array);
			  
			 $customConditionVar=  array(  
				array( 
					'tableWithCondition'		=> "(rec.recTitleEng like '%".'FieldValue'."%')",
					'fieldName'					=> 'recTitleEng'
				),
				array( 
					'tableWithCondition'		=> "(rec.recTitleDutch like '%".'FieldValue'."%')",
					'fieldName'					=> 'recTitleDutch'
				),
				array( 
					'tableWithCondition'		=> "(cat.catTitleEng like '%".'FieldValue'."%')",
					'fieldName'					=> 'catTitleEng'
				),
				array( 
					'tableWithCondition'		=> "(cat.catTitleDutch like '%".'FieldValue'."%')",
					'fieldName'					=> 'catTitleDutch'
				),
				array(
					'tableWithCondition'		=> "DATE_FORMAT(rec.recDateCreated ,'%Y-%m-%d') >= 'FieldValue'",
					'fieldName'					=> 'recDateFrom',
					'condition'					=> 'AND',
					'Value'						=> 'From'
				), 
				array(
					'tableWithCondition'		=> "DATE_FORMAT(rec.recDateCreated ,'%Y-%m-%d') <= 'FieldValue'",
					'fieldName'					=> 'catDateTo',
					'condition'					=> 'AND',
					'Value'						=> 'To'
				)
			);
			    
			
            $result = getRecipeList('tbl_recipe as rec', "rec.recId,rec.recTitleEng,rec.recTitleDutch,rec.recShortDescEng,rec.recShortDescDutch,rec.recLongDescEng,rec.recLongDescDutch,rec.recImage,rec.recStatus,rec.recDateCreated,cat.catTitleEng,cat.catTitleDutch", $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar);
            //echo "<pre>"; print_r($result);die;
            //echo $this->db->last_query();
            /* Export CSV functionality - START */
            if ($this->input->post("exportCSV") == "exportCSV") {
				$this->__exposrtCSV($result);
            }
            /* Export CSV functionality - END */
            $data["recipeListing"] = $result;
            $data["postData"] = $this->input->post();
            $data["sortBy"] = $orderingVar["sortBy"];
            $data["orderBy"] = $orderingVar["orderBy"];
            $data['title'] = "Recipies";
            $this->load->view('admin/recipe/home', $data);
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
        $array = array(array('Title (English)','Title (Dutch)','Description (English)','Description (Dutch)','Image','Category (English)','Category (Dutch)','Status','Date Created'));
		 
        foreach ($dataArray as $key => $rec) {
            $tempArray = array();
			$status='';
			if($rec->recStatus)
				$status='Active';
			else
				$status='Inactie';
				
            $tempArray['Title (English)'] = $rec->recTitleEng;
            $tempArray['Title (Dutch)'] = $rec->recTitleDutch;
            $tempArray['Description (English)'] = $rec->recShortDescEng;
            $tempArray['Description (Dutch)'] = $rec->recShortDescDutch;
            $tempArray['Image'] = SITE_URL."bbq_images/".$rec->recImage;
            $tempArray['Category (English)'] = $rec->catTitleEng;
            $tempArray['Category (Dutch)'] = $rec->catTitleDutch;
            $tempArray['Status'] = $status;
            $tempArray['Date Created'] = date(ADMIN_DATE_FORMAT, strtotime($rec->recDateCreated));
            $array[] = $tempArray;
        } 
        $this->load->helper('csv');
        echo array_to_csv($array, 'recipe(' . date('d-M-Y') . ').csv');
        exit;
    }
    
    
	
	/**
	* @ Function Name		: addRecipe
	* @ Function Params	: 
	* @ Function Purpose 	: admin can add new recipe
	* @ Function Returns	: 
	*/
    function addRecipe() 
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
					'rules' => 'trim|required|is_unique[tbl_recipe.recTitleEng]'
				),
				array(
					'field' => 'txtTitleDutch',
					'label' => 'Name (Dutch)',
					'rules' => 'trim|required|is_unique[tbl_recipe.recTitleDutch]'
				),
				array(
					'field' => 'txtCatId',
					'label' => 'Category',
					'rules' => 'trim|required'
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
				$recPostData = array();
				$recPostData['recTitleEng'] = $postData['txtTitleEng'];
				$recPostData['recTitleDutch'] = $postData['txtTitleDutch'];
				$recPostData['recShortDescEng'] = stripslashes($postData['txtDescEng']);
				$recPostData['recShortDescDutch'] = stripslashes($postData['txtDescDutch']);
				$recPostData['recLongDescEng'] = stripslashes($postData['txtLongDescEng']);
				$recPostData['recLongDescDutch'] = stripslashes($postData['txtLongDescDutch']);
				$recPostData['catId'] = $postData['txtCatId'];
				$recPostData['recTempMode'] = (isset($postData['txtTemprature']) && $postData['txtTemprature'] != "") ? $postData['txtTemprature'] : 'no';
				$recPostData['recThickMode'] = (isset($postData['txtThickness']) && $postData['txtThickness'] != "") ? $postData['txtThickness'] : 'no';
				$recPostData['recImage'] = $filename;
				$recPostData['recDateCreated'] = date('Y-m-d H:i:s');
				if ($insert_id = $this->Recipemodel->addRecipe($recPostData)) {
					$recIngEngData = $postData['txtIngEng'];
					$recIngDutchData = $postData['txtIngDutch'];
					foreach ($recIngEngData as $ingKey => $ingVal) {
						$ingArr = array();
						$ingArr['recId'] = $insert_id;
						$ingArr['recIngNameEng'] = $ingVal;
						$ingArr['recIngNameDutch'] = $recIngDutchData[$ingKey];
						if (trim($ingVal) != "" || trim($recIngDutchData[$ingKey]) != "") {
							$this->Recipemodel->addRecipeIng($ingArr);
						}
					}
					$this->session->set_flashdata('item', '<div class="warning pos">Recipe added successfully.</div>');
					redirect('admin/recipe');
				} else {
					$this->session->set_flashdata('item', '<div class="warning neg">There is some error while creating recipe. Please try again!</div>');
				}
			}
		}
		$data['title'] = "Add New Recipe"; 
		$this->load->view('admin/recipe/addRecipe', $data);
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
		$this->db->where("recTitleEng", $title);
		$result = $this->db->get("tbl_recipe");
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
		$this->db->where("recTitleDutch", $title);
		$result = $this->db->get("tbl_recipe");
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
	* @ Function Name		: editRecipe
	* @ Function Params	: 
	* @ Function Purpose 	: Edit recipe functionality by id
	* @ Function Returns	: 
	*/
    function editRecipe($id='') {
		$data = array();
		$allCat = $this->Categorymodel->allActiveCatgegory();
		$data['allCat'] = $allCat; 
		$recipeData = $this->Recipemodel->getRecipeDetail($id);
		$recInfo 	= array(
			"recId" => $recipeData->recId,
			"recTitleEng" => $recipeData->recTitleEng,
			"recTitleDutch" => $recipeData->recTitleDutch,
			"recShortDescEng" => $recipeData->recShortDescEng,
			"recShortDescDutch" => $recipeData->recShortDescDutch,
			"recLongDescEng" => $recipeData->recLongDescEng,
			"recLongDescDutch" => $recipeData->recLongDescDutch,
			"catId" => $recipeData->catId,
			"recTempMode" => $recipeData->recTempMode,
			"recThickMode" => $recipeData->recThickMode,
			"recImage" => $recipeData->recImage,
			"recStatus" => $recipeData->recStatus,
		);
		$recId = $recipeData->recId;
		$data['detail'] = $recInfo;
		$recipeIngData = $this->Recipemodel->getRecipeIng($id);
		$data['recipeIng'] = $recipeIngData;
		
		if (!empty($_POST)) {
			$oldTitleEng = $recipeData->recTitleEng;
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
					'rules' => 'trim|required|is_unique[tbl_recipe.recTitleEng]'
				);
			}
			$oldTitleDutch = $recipeData->recTitleDutch;
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
					'label' => 'Name (Dutch)',
					'rules' => 'trim|required|is_unique[tbl_recipe.recTitleDutch]'
				);
			}
			 
			$rul = array(
					array(
						'field' => 'txtCatId',
						'label' => 'Category',
						'rules' => 'trim|required'
					)
				);
			array_push($rul,$rule1,$rule2);
			$this->form_validation->set_rules($rul);
			
			$oldTempMode = $recipeData->recTempMode;
			$newTempMode = (isset($_POST['txtTemprature']) && $_POST['txtTemprature'] != "") ? $_POST['txtTemprature'] : 'no';
			if ($oldTempMode != $newTempMode) {
				$this->Recipemodel->deleteRecipeTiming($recId);
			}
			
			$oldThickMode = $recipeData->recThickMode;
			$newThickMode = (isset($_POST['txtThickness']) && $_POST['txtThickness'] != "") ? $_POST['txtThickness'] : 'no';
			if ($oldThickMode != $newThickMode) {
				$this->Recipemodel->deleteRecipeTiming($recId);
			}
			
			
			if ($this->form_validation->run()) {
				$filename = $recipeData->recImage;
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
				$recId = $this->input->post('txtId');
				$postData = $this->input->post();
				
				$recPostData = array();
				$recPostData['recTitleEng'] = $postData['txtTitleEng'];
				$recPostData['recTitleDutch'] = $postData['txtTitleDutch'];
				$recPostData['recShortDescEng'] = stripslashes($postData['txtDescEng']);
				$recPostData['recShortDescDutch'] = stripslashes($postData['txtDescDutch']);
				$recPostData['recLongDescEng'] = stripslashes($postData['txtLongDescEng']);
				$recPostData['recLongDescDutch'] = stripslashes($postData['txtLongDescDutch']);
				$recPostData['catId'] = $postData['txtCatId'];
				$recPostData['recImage'] = $filename;
				$recPostData['recTempMode'] = (isset($postData['txtTemprature']) && $postData['txtTemprature'] != "") ? $postData['txtTemprature'] : 'no';
				$recPostData['recThickMode'] = (isset($postData['txtThickness']) && $postData['txtThickness'] != "") ? $postData['txtThickness'] : 'no';
				$recPostData['recDateModified'] = date('Y-m-d H:i:s');
				$res = $this->Recipemodel->editRecipeData($recPostData, $recId);
				/* Code end's here */
				if ($res) { 
					$this->db->where('recId', $recId);
					$this->db->delete('tbl_recipeIngredient'); //Delete the ingredients
					$recIngEngData = $postData['txtIngEng'];
					$recIngDutchData = $postData['txtIngDutch'];
					foreach ($recIngEngData as $ingKey => $ingVal) {
						$ingArr = array();
						$ingArr['recId'] = $recId;
						$ingArr['recIngNameEng'] = $ingVal;
						$ingArr['recIngNameDutch'] = $recIngDutchData[$ingKey];
						if (trim($ingVal) != "" || trim($recIngDutchData[$ingKey]) != "") {
							$this->Recipemodel->addRecipeIng($ingArr);
						}
					}
					
					$message = "<div class='warning pos'>Selected Recipe edited successfully</div>";
					$this->session->set_flashdata('item', $message);
				} else {
					$message = "<div class='warning neg'>Selected Recpie edit unsuccessful</div>";
					$this->session->set_flashdata('item', $message);
				}
				redirect('admin/recipe');
			}
		} 
		$data['title'] = "Edit Recipe"; 
		$this->load->view('admin/recipe/editRecipe', $data);
    }
	
	/**
	* @ Function Name		: active
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: make recipe status active
	* @ Function Returns	: 
	*/
    function active($id = '') {
        $result = false;
        if (!empty($id)) {
            $result = $this->Recipemodel->status($id, '1');
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Recipemodel->status($id, '1');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Active status set for selected recipe(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected recipe(s), please try again.<div>');
        }
        redirect('admin/recipe');
    }
	
	/**
	* @ Function Name		: delete
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: function used to delete recipe(s)
	* @ Function Returns	: 
	*/
    function delete($id = '') { 
        $result = false;
        if (!empty($id)) {
			$delRecArr = array();
			array_push($delRecArr, $id);
			$result = $this->Recipemodel->deleteRecipe($delRecArr);
        } else { die("here");
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
            $this->session->set_flashdata('item', '<div class="warning pos">Selected recipe(s) deleted successully</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">Selected recipe(s) is not deleted, please try again</div>');
        }
        redirect('admin/recipe');
	}
	
	/**
	* @ Function Name		: inactive
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: make recipe status inactive
	* @ Function Returns	: 
	*/
    function inactive($id = '') {
        $result = false;
        if (!empty($id)) {
            $result = $this->Recipemodel->status($id, '0');
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Recipemodel->status($id, '0');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Inactive status set for selected recipe(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected recipe(s), please try again.</div>');
        }
        redirect('admin/recipe');
    } 
    
    /**
	* @ Function Name		: timeActive
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: make recipe time status active
	* @ Function Returns	: 
	*/
    function timeActive($id = '') {
		$recipeDetail = $this->Recipemodel->getRecipeTimeDetail($id);
		$recId = "";
		if (!empty($recipeDetail)) {
			$recId = $recipeDetail->recId;
		}
        $result = false;
        if (!empty($id)) {
            $result = $this->Recipemodel->timeStatus($id, '1');
        } else {
            $id_arr = $this->input->post('chkBox');
            $recipeDetail = $this->Recipemodel->getRecipeTimeDetail($id_arr[0]);
            if (!empty($recipeDetail)) {
				$recId = $recipeDetail->recId;
			}
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Recipemodel->timeStatus($id, '1');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Active status set for selected time(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected time(s), please try again.<div>');
        }
        redirect('admin/recipe/timing/'.$recId);
    }
    
    /**
	* @ Function Name		: timeInactive
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: make recipe time status inactive
	* @ Function Returns	: 
	*/
    function timeInactive($id = '') {
		$recipeDetail = $this->Recipemodel->getRecipeTimeDetail($id);
		$recId = "";
		if (!empty($recipeDetail)) {
			$recId = $recipeDetail->recId;
		}
        $result = false;
        if (!empty($id)) {
            $result = $this->Recipemodel->timeStatus($id, '0');
        } else {
            $id_arr = $this->input->post('chkBox');
            $recipeDetail = $this->Recipemodel->getRecipeTimeDetail($id_arr[0]);
            if (!empty($recipeDetail)) {
				$recId = $recipeDetail->recId;
			}
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Recipemodel->timeStatus($id, '0');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Inactive status set for selected recipe time(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected recipe time(s), please try again.</div>');
        }
        redirect('admin/recipe/timing/'.$recId);
    }
    
    
    /**
	* @ Function Name		: timing
	* @ Function Params	: 
	* @ Function Purpose 	: Managing recipes timing grid
	* @ Function Returns	: 
	*/
	public function timing($id = '') {
		if ($this->session->userdata('logged_in') == TRUE) {
			if (trim($id) == "" || trim($id) == 0) {
				redirect('admin/recipe');
			}
			$data = array();
			$data['recipeId'] = $id;
			$recipeData = $this->Recipemodel->getRecipeDetail($id);
			$data['recipeName'] = $recipeData->recTitleEng;
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
                $sortBy = "recTimeDateCreated_desc";
            }
			
            $sortBy = explode("_", $sortBy);
            $orderBy = $sortBy[1];
            $sortBy = $sortBy[0]; 
            
            //custom
			$orderingVar['ipp'] = ($this->input->post("ipp") ? $this->input->post("ipp") : 10);
            $orderingVar['pgn'] = ($this->input->post("pgn") ? $this->input->post("pgn") : 1);
			
            if ($filterOrSort == "sort") {
                $orderBy = ($orderBy == "desc") ? "asc" : "desc";
            }
			
            $orderingVar['orderBy'] = $data['orderBy'] = $orderBy;
            $orderingVar['sortBy'] = $data['sortBy'] = $sortBy;
			
			// paging set 
			 
			
			 // Join parameters  
			 
			$joinTableVar = array();
			
            $recTimeStatus=$this->input->post("recTimeStatus");    
        
            $array = array('recTimeStatus'); 
			//print_r(compact($array));die;
            $likeConditionArray = compact($array);
            
            $whereConditionVar = array('recId'=>$id);
			  
			 $customConditionVar=  array(  
				array( 
					'tableWithCondition'		=> "(recThickness like '%".'FieldValue'."%' || recCookingStyle like '%".'FieldValue'."%')",
					'fieldName'					=> 'recTimeTitle'
				),
				array( 
					'tableWithCondition'		=> "(recTimeMin ='FieldValue' || recTimeSec ='FieldValue')",
					'fieldName'					=> 'recTimeMinSec'
				),
				array( 
					'tableWithCondition'		=> "(recTemp ='FieldValue')",
					'fieldName'					=> 'recTimeTemp'
				),
				array(
					'tableWithCondition'		=> "DATE_FORMAT(recTimeDateCreated ,'%Y-%m-%d') >= 'FieldValue'",
					'fieldName'					=> 'recTimeDateFrom',
					'condition'					=> 'AND',
					'Value'						=> 'From'
				), 
				array(
					'tableWithCondition'		=> "DATE_FORMAT(recTimeDateCreated ,'%Y-%m-%d') <= 'FieldValue'",
					'fieldName'					=> 'recTimeDateTo',
					'condition'					=> 'AND',
					'Value'						=> 'To'
				)
			);
			
			    
			
            $result = getList('tbl_recipeTiming', "*", $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar);
            //echo "<pre>"; print_r($result);die;
            //echo $this->db->last_query();
            /* Export CSV functionality - START */
            if ($this->input->post("exportCSV") == "exportCSV") {
				$this->__exposrtTimingCSV($result);
            }
            /* Export CSV functionality - END */
            $data["recipeTimeListing"] = $result;
            $data["postData"] = $this->input->post();
            $data["sortBy"] = $orderingVar["sortBy"];
            $data["orderBy"] = $orderingVar["orderBy"];
            $data['title'] = "Timing";
            $this->load->view('admin/recipe/timing', $data);
        } else {
            $data['title'] = "Administrator Login";
            $message = "Please login to your account to access internal pages";
            $this->form_validation->_error_array['password'] = $message;
            $this->load->view('admin/users/login', $data);
        }
	}
	
	/**
	* @ Function Name		: __exposrtTimingCSV
	* @ Function Params	: $dataArray {array} 
	* @ Function Purpose 	: export CSV
	* @ Function Returns	: 
	*/
    private function __exposrtTimingCSV($dataArray) { 
        $array = array(array('Title','Time (Min:Sec)','Temprature','Status','Date Created'));
		 
        foreach ($dataArray as $key => $tim) {
            $tempArray = array();
			$status='';
			if($tim->recTimeStatus)
				$status='Active';
			else
				$status='Inactie';
				
            $tempArray['Title'] = $tim->recThickness." ".$tim->recCookingStyle;
            $tempArray['Time (Min:Sec)'] = $tim->recTimeMin.":".$tim->recTimeSec;
            $tempArray['Temprature'] = $tim->recTemp;
            $tempArray['Status'] = $status;
            $tempArray['Date Created'] = date(ADMIN_DATE_FORMAT, strtotime($tim->recTimeDateCreated));
            $array[] = $tempArray;
        } 
        $this->load->helper('csv');
        echo array_to_csv($array, 'time(' . date('d-M-Y') . ').csv');
        exit;
    }
    
    /**
	* @ Function Name		: addTiming
	* @ Function Params		: $id {integer}
	* @ Function Purpose 	: add timing for a recipe
	* @ Function Returns	: 
	*/
    function addTiming($id = '') {
		$data = array();
		$data['recipeId'] = $id;
		$recipeData = $this->Recipemodel->getRecipeDetail($id);
		$data['tempMode'] = $recipeData->recTempMode;
		$data['thickMode'] = $recipeData->recThickMode;
		$data['recipeName'] = $recipeData->recTitleEng;
		
		$submit = $this->input->post('btnSubmit');
		if (!empty($_POST)) {
			$postData = $this->input->post();
			$deleteRecipe = $this->Recipemodel->deleteRecipeTiming($postData['recipeId']);
			$timeTitle = $postData['title'];
			$tempMode = $postData['tempMode'];
			$thickMode = $postData['thickMode'];
			if (trim($tempMode) == 'yes' && trim($thickMode) == 'yes') {
				foreach ($timeTitle as $tKey => $tVal) {
					$success = 0;
					$timeArr = array();
					$titleArr = explode('-',$tVal);
					$timeArr['recThickness'] = $titleArr[0];
					$timeArr['recCookingStyle'] = $titleArr[1];
					$timeArr['recTimeMin'] = $postData['min'][$tKey];
					$timeArr['recTimeSec'] = $postData['sec'][$tKey];
					$timeArr['recTotalSec'] = $postData['totalSec'][$tKey];
					$timeArr['recTemp'] = $postData['temp'][$tKey];
					$timeArr['recId'] = $postData['recipeId'];
					$timeArr['recTimeDateCreated'] = date('Y-m-d H:i:s');
					//~ echo "<pre>"; print_r($timeArr);
					if ($insert_id = $this->Recipemodel->addRecipeTiming($timeArr)) {
						$success = 1;
					} else {
						$success = 0;
					}
				}
			} else if (trim($tempMode) == 'yes') {
				foreach ($timeTitle as $tKey => $tVal) {
					$success = 0;
					$timeArr = array();
					$timeArr['recCookingStyle'] = $postData['title'][$tKey];
					$timeArr['recTimeMin'] = $postData['min'][$tKey];
					$timeArr['recTimeSec'] = $postData['sec'][$tKey];
					$timeArr['recTotalSec'] = $postData['totalSec'][$tKey];
					$timeArr['recTemp'] = $postData['temp'][$tKey];
					$timeArr['recId'] = $postData['recipeId'];
					$timeArr['recTimeDateCreated'] = date('Y-m-d H:i:s');
					//~ echo "<pre>"; print_r($timeArr);
					if ($insert_id = $this->Recipemodel->addRecipeTiming($timeArr)) {
						$success = 1;
					} else {
						$success = 0;
					}
				}
			} else if (trim($thickMode) == 'yes') {
				foreach ($timeTitle as $tKey => $tVal) {
					$success = 0;
					$timeArr = array();
					$timeArr['recThickness'] = $postData['title'][$tKey];
					$timeArr['recTimeMin'] = $postData['min'][$tKey];
					$timeArr['recTimeSec'] = $postData['sec'][$tKey];
					$timeArr['recTotalSec'] = $postData['totalSec'][$tKey];
					$timeArr['recTemp'] = $postData['temp'][$tKey];
					$timeArr['recId'] = $postData['recipeId'];
					$timeArr['recTimeDateCreated'] = date('Y-m-d H:i:s');
					//~ echo "<pre>"; print_r($timeArr);
					if ($insert_id = $this->Recipemodel->addRecipeTiming($timeArr)) {
						$success = 1;
					} else {
						$success = 0;
					}
				}
			}
			if ($success == 1) {
				$this->session->set_flashdata('item', '<div class="warning pos">Recipe time added successfully.</div>');
			} else {
				$this->session->set_flashdata('item', '<div class="warning neg">There is some error while adding recipe timing. Please try again!</div>');
			}
			redirect('admin/recipe/timing/'.$postData['recipeId']);
		}
		$data['title'] = "Add Recipe Timing"; 
		$this->load->view('admin/recipe/addTiming', $data);
	}
	
	/**
	* @ Function Name		: editTiming
	* @ Function Params	: 
	* @ Function Purpose 	: Edit timing functionality by id
	* @ Function Returns	: 
	*/
    function editTiming($id='') {
		$data = array();
		$timeInfo = $this->Recipemodel->getRecipeTimeDetail($id);
		$recId = "";
		if (!empty($timeInfo)) {
			$recId = $timeInfo->recId;
		}
		$recipeData = $this->Recipemodel->getRecipeDetail($recId);
		$data['tempMode'] = $recipeData->recTempMode;
		$data['thickMode'] = $recipeData->recThickMode;
		$data['recipeName'] = $recipeData->recTitleEng;
		$data['detail'] = $timeInfo;
		if (!empty($_POST)) {
			$postData = $this->input->post();
			
			$timeId = $postData['timeId'];
			$timeArr = array();
			$titleArr = explode('-',$postData['title']);
			$timeArr['recThickness'] = $titleArr[0];
			$timeArr['recCookingStyle'] = $titleArr[1];
			$timeArr['recTimeMin'] = $postData['min'];
			$timeArr['recTimeSec'] = $postData['sec'];
			$timeArr['recTotalSec'] = $postData['totalSec'];
			$timeArr['recTemp'] = $postData['temp'];
			$timeArr['recTimeDateModified'] = date('Y-m-d H:i:s');
			if ($update_id = $this->Recipemodel->editRecipeTiming($timeArr, $timeId)) {
				$this->session->set_flashdata('item', '<div class="warning pos">Recipe time updated successfully.</div>');
				redirect('admin/recipe/timing/'.$postData['recId']);
			} else {
				$this->session->set_flashdata('item', '<div class="warning neg">There is some error while updating recipe timing. Please try again!</div>');
			}
			
		} 
		$data['title'] = "Edit Recipe Timing"; 
		$this->load->view('admin/recipe/editTiming', $data);
    }
    
    /**
	* @ Function Name		: interval
	* @ Function Params	: 
	* @ Function Purpose 	: Managing recipes time interval
	* @ Function Returns	: 
	*/
	public function interval($id = '') {
		if ($this->session->userdata('logged_in') == TRUE) {
			if (trim($id) == "" || trim($id) == 0) {
				redirect('admin/recipe');
			}
			$data = array();
			
			$timeInfo = $this->Recipemodel->getRecipeTimeDetail($id);
			$recId = "";
			if (!empty($timeInfo)) {
				$recId = $timeInfo->recId;
			}
			$recipeData = $this->Recipemodel->getRecipeDetail($recId);
			$data['recipeName'] = $recipeData->recTitleEng;
			
			$data['recId'] = $recId;
			$data['timeIntervalId'] = $id;
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
                $sortBy = "recIntervalDateCreated_desc";
            }
			
            $sortBy = explode("_", $sortBy);
            $orderBy = $sortBy[1];
            $sortBy = $sortBy[0]; 
            
            //custom
			$orderingVar['ipp'] = ($this->input->post("ipp") ? $this->input->post("ipp") : 10);
            $orderingVar['pgn'] = ($this->input->post("pgn") ? $this->input->post("pgn") : 1);
			
            if ($filterOrSort == "sort") {
                $orderBy = ($orderBy == "desc") ? "asc" : "desc";
            }
			
            $orderingVar['orderBy'] = $data['orderBy'] = $orderBy;
            $orderingVar['sortBy'] = $data['sortBy'] = $sortBy;
			
			// paging set 
			 
			
			 // Join parameters  
			 
			$joinTableVar = array();
			
           // $recTimeStatus=$this->input->post("recTimeStatus");    
        
           // $array = array('recTimeStatus'); 
			//print_r(compact($array));die;
           // $likeConditionArray = compact($array);
            
            $whereConditionVar = array('recTimeId'=>$id);
			  
			 $customConditionVar=  array(  
				array( 
					'tableWithCondition'		=> "(recIntervalTitleEng like '%".'FieldValue'."%')",
					'fieldName'					=> 'recIntTitleEng'
				),
				array( 
					'tableWithCondition'		=> "(recIntervalTitleDutch like '%".'FieldValue'."%')",
					'fieldName'					=> 'recIntTitleDutch'
				),
				array( 
					'tableWithCondition'		=> "(recIntervalTimeMin ='FieldValue' || recIntervalTimeSec ='FieldValue')",
					'fieldName'					=> 'recIntMinSec'
				),
				array(
					'tableWithCondition'		=> "DATE_FORMAT(recIntervalDateCreated ,'%Y-%m-%d') >= 'FieldValue'",
					'fieldName'					=> 'recIntDateFrom',
					'condition'					=> 'AND',
					'Value'						=> 'From'
				), 
				array(
					'tableWithCondition'		=> "DATE_FORMAT(recIntervalDateCreated ,'%Y-%m-%d') <= 'FieldValue'",
					'fieldName'					=> 'recIntDateTo',
					'condition'					=> 'AND',
					'Value'						=> 'To'
				)
			);
			
			    
			
            $result = getList('tbl_recipeTimeInterval', "*", $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar);
            //echo "<pre>"; print_r($result);die;
            //echo $this->db->last_query();
            /* Export CSV functionality - START */
            if ($this->input->post("exportIntervalCSV") == "exportIntervalCSV") {
				$this->__exportIntervalCSV($result);
            }
            /* Export CSV functionality - END */
            $data["recipeTimeIntervalListing"] = $result;
            $data["postData"] = $this->input->post();
            $data["sortBy"] = $orderingVar["sortBy"];
            $data["orderBy"] = $orderingVar["orderBy"];
            $data['title'] = "Interval";
            $this->load->view('admin/recipe/interval', $data);
        } else {
            $data['title'] = "Administrator Login";
            $message = "Please login to your account to access internal pages";
            $this->form_validation->_error_array['password'] = $message;
            $this->load->view('admin/users/login', $data);
        }
	}
	
	/**
	* @ Function Name		: __exportIntervalCSV
	* @ Function Params	: $dataArray {array} 
	* @ Function Purpose 	: export CSV
	* @ Function Returns	: 
	*/
    private function __exportIntervalCSV($dataArray) { 
        $array = array(array('Title (English)','Title (Dutch)','Description (English)','Description (Dutch)','Time (Min:Sec)','Date Created'));
		 
        foreach ($dataArray as $key => $int) {
            $tempArray = array();
			$tempArray['Title (English)'] = $int->recIntervalTitleEng;
			$tempArray['Title (Dutch)'] = $int->recIntervalTitleDutch;
			$tempArray['Description (English)'] = $int->recIntervalDescEng;
			$tempArray['Description (Dutch)'] = $int->recIntervalDescDutch;
            $tempArray['Time (Min:Sec)'] = $int->recIntervalTimeMin.":".$int->recIntervalTimeSec;
            $tempArray['Date Created'] = date(ADMIN_DATE_FORMAT, strtotime($int->recIntervalDateCreated));
            $array[] = $tempArray;
        } 
        $this->load->helper('csv');
        echo array_to_csv($array, 'timeInterval(' . date('d-M-Y') . ').csv');
        exit;
    }
	
	/**
	* @ Function Name		: addInterval
	* @ Function Params	: 
	* @ Function Purpose 	: admin can add new time interval
	* @ Function Returns	: 
	*/
    function addInterval($id = "") 
    {
		$data = array();
		$timeId = $id;
		$data['timeId'] = $timeId;
		
		$timeInfo = $this->Recipemodel->getRecipeTimeDetail($id);
		$recId = "";
		if (!empty($timeInfo)) {
			$recId = $timeInfo->recId;
		}
		$recipeData = $this->Recipemodel->getRecipeDetail($recId);
		$data['recipeName'] = $recipeData->recTitleEng;
		$data['recId'] = $recId;
		$timeInfo = $this->Recipemodel->getRecipeTimeDetail($id);
		$actualRecipeTime = "";
		$recTotalSec = 0;
		if (!empty($timeInfo)) {
			$recTotalSec = $timeInfo->recTotalSec;
			$actualRecipeTime = $timeInfo->recTimeMin.":".$timeInfo->recTimeSec;
		}
		$data['recTotalSec'] = $recTotalSec;
		$data['actualRecipeTime'] = $actualRecipeTime;
		$submit = $this->input->post('btnSubmit');
		if (!empty($_POST)) { 
			$rul = array(
				array(
					'field' => 'txtTitleEng',
					'label' => 'Name (Eng)',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'txtTitleDutch',
					'label' => 'Name (Dutch)',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'min',
					'label' => 'Time (In Min)',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'sec',
					'label' => 'Time (In Sec)',
					'rules' => 'trim|required'
				),
			);
			$this->form_validation->set_rules($rul);
			if ($this->form_validation->run()) { //echo "<pre>"; print_r($_POST);die;
				$postData = $this->input->post();
				$intervalPostData = array();
				$intervalPostData['recTimeId'] = $postData['timeId'];
				$intervalPostData['recIntervalTitleEng'] = $postData['txtTitleEng'];
				$intervalPostData['recIntervalTitleDutch'] = $postData['txtTitleDutch'];
				$intervalPostData['recIntervalDescEng'] = stripslashes($postData['txtDescEng']);
				$intervalPostData['recIntervalDescDutch'] = stripslashes($postData['txtDescDutch']);
				$intervalPostData['recIntervalTimeMin'] = $postData['min'];
				$intervalPostData['recIntervalTimeSec'] = $postData['sec'];
				$intervalPostData['recIntervalTotalSec'] = $postData['recTotalSec'];
				$intervalPostData['recIntervalDateCreated'] = date('Y-m-d H:i:s');
				if ($insert_id = $this->Recipemodel->addRecipeTimeInterval($intervalPostData)) {
					$this->session->set_flashdata('item', '<div class="warning pos">Interval added successfully.</div>');
					redirect('admin/recipe/interval/'.$timeId);
				} else {
					$this->session->set_flashdata('item', '<div class="warning neg">There is some error while creating interval. Please try again!</div>');
				}
			}
		}
		$data['title'] = "Add Time Interval"; 
		$this->load->view('admin/recipe/addInterval', $data);
    }
    
    /**
	* @ Function Name		: editInterval
	* @ Function Params	: 
	* @ Function Purpose 	: Edit timing interval functionality by id
	* @ Function Returns	: 
	*/
    function editInterval($intervalId='') {
		$data = array();
		$timeIntervalInfo = $this->Recipemodel->getRecipeTimeIntervalDetail($intervalId);
		$recTotalSec = "";
		$recTimeId = "";
		$recId = "";
		$actualRecipeTime = "";
		if (!empty($timeIntervalInfo)) {
			$recId = $timeIntervalInfo->recId;
			$recTotalSec = $timeIntervalInfo->recTotalSec;
			$recTimeId = $timeIntervalInfo->recTimeId;
			$actualRecipeTime = $timeIntervalInfo->recTimeMin.":".$timeIntervalInfo->recTimeSec;
		}
		$data['recTotalSec'] = $recTotalSec;
		$data['timeId'] = $recTimeId;
		$data['detail'] = $timeIntervalInfo;
		$data['recId'] = $recId;
		$data['actualRecipeTime'] = $actualRecipeTime;
		$recipeData = $this->Recipemodel->getRecipeDetail($recId);
		$data['recipeName'] = $recipeData->recTitleEng;
		
		
		if (!empty($_POST)) {
			$rul = array(
				array(
					'field' => 'txtTitleEng',
					'label' => 'Name (Eng)',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'txtTitleDutch',
					'label' => 'Name (Dutch)',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'min',
					'label' => 'Time (In Min)',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'sec',
					'label' => 'Time (In Sec)',
					'rules' => 'trim|required'
				),
			);
			$this->form_validation->set_rules($rul);
			if ($this->form_validation->run()) {
				$postData = $this->input->post();
				$intervalPostData = array();
				$intervalPostData['recTimeId'] = $postData['timeId'];
				$intervalPostData['recIntervalTitleEng'] = $postData['txtTitleEng'];
				$intervalPostData['recIntervalTitleDutch'] = $postData['txtTitleDutch'];
				$intervalPostData['recIntervalDescEng'] = stripslashes($postData['txtDescEng']);
				$intervalPostData['recIntervalDescDutch'] = stripslashes($postData['txtDescDutch']);
				$intervalPostData['recIntervalTimeMin'] = $postData['min'];
				$intervalPostData['recIntervalTimeSec'] = $postData['sec'];
				$intervalPostData['recIntervalTotalSec'] = $postData['recTotalSec'];
				$intervalPostData['recIntervalDateCreated'] = date('Y-m-d H:i:s');
				if ($upadte_id = $this->Recipemodel->updateRecipeTimeInterval($intervalPostData, $intervalId)) {
					$this->session->set_flashdata('item', '<div class="warning pos">Interval added successfully.</div>');
					redirect('admin/recipe/interval/'.$recTimeId);
				} else {
					$this->session->set_flashdata('item', '<div class="warning neg">There is some error while creating interval. Please try again!</div>');
				}
			}
		} 
		$data['title'] = "Edit Time Interval"; 
		$this->load->view('admin/recipe/editInterval', $data);
    }
    
    /**
	* @ Function Name		: deleteInterval
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: function used to delete time interval(s)
	* @ Function Returns	: 
	*/
    function deleteInterval($intervalId = '') {
		$result = false;
        if (!empty($intervalId)) {
			$timeIntervalInfo = $this->Recipemodel->getRecipeTimeIntervalDetail($intervalId);
			$timeId = $timeIntervalInfo->recTimeId;
            $result = $this->Recipemodel->deleteInterval($intervalId);
        } else {
            $id_arr = $this->input->post('chkBox');
            $timeIntervalInfo = $this->Recipemodel->getRecipeTimeIntervalDetail($id_arr[0]);
			$timeId = $timeIntervalInfo->recTimeId;
			$id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->Recipemodel->deleteInterval($id);
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Selected interval(s) deleted successully</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">Selected interval(s) is not deleted, please try again</div>');
        }
        redirect('admin/recipe/interval/'.$timeId);
	}
}
?>
