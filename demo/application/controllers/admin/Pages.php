<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
	
class Pages extends MY_Controller {
	
	/**
	* @ Function Name		: __construct
	* @ Function Params	: 
	* @ Function Purpose 	: initilizing variable and providing pre functionalities
	* @ Function Returns	: 
	*/
	public function __construct() {
		parent::__construct();   
		$this->load->model('admin/cms/pagesmodel'); 
	}
	/**
	* @ Function Name		: index
	* @ Function Params	: 
	* @ Function Purpose 	: Managing pages and login and controls redirection
	* @ Function Returns	: 
	*/
	public function index() {
		$data['title'] = "CMS Pages"; 
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
                $sortBy = "cmsDateCreated_desc";
            }
			
		$sortBy = explode("_", $sortBy);
		$orderBy = $sortBy[1];
		$sortBy = $sortBy[0]; 
		
            if ($filterOrSort == "sort") {
                $orderBy = ($orderBy == "desc") ? "asc" : "desc";
            }
			
		$orderingVar['orderBy'] = $data['orderBy'] = $orderBy;
		$orderingVar['sortBy'] = $data['sortBy'] = $sortBy;
		
		$cmsStatus=$this->input->post("cmbStatus");
		$cmsTitleEng=$this->input->post("txtTtileEng");
		$cmsTitleDutch=$this->input->post("txtTtileDutch");
		$cmsContentEng=$this->input->post("txtdesEng"); 
		$cmsContentDutch=$this->input->post("txtdesDutch"); 
		$array = array('cmsTitleEng','cmsTitleDutch','cmsContentEng','cmsContentDutch','cmsStatus'); //'geoName',  
		$likeConditionArray = compact($array);  
		
			$customConditionVar=  array(   
					array(
						'tableWithCondition'		=> "DATE_FORMAT(tbl_cms.cmsDateCreated ,'%Y-%m-%d') >= 'FieldValue'",
						'fieldName'					=> 'txtDateFrom',
						'condition'					=> 'AND',
						'Value'						=> 'From'
					), 
					array(
						'tableWithCondition'		=> "DATE_FORMAT(tbl_cms.cmsDateCreated ,'%Y-%m-%d') <= 'FieldValue'",
						'fieldName'					=> 'txtDateTo',
						'condition'					=> 'AND',
						'Value'						=> 'To'
					)  	
			);
			    
			
        $result = getList('tbl_cms', "*", $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar,$paging); 
		$data["pageinfo"] = $result;
		$data["postData"] = $this->input->post();
		$data["sortBy"] = $orderingVar["sortBy"];
		$data["orderBy"] = $orderingVar["orderBy"];
		
		$this->load->view('admin/cms/pages', $data);
	} 
	
	/**
	* @ Function Name		: addUser
	* @ Function Params	: 
	* @ Function Purpose 	: admin can add new user
	* @ Function Returns	: 
	*/
    function addpage() {
            $data = array();
            $submit = $this->input->post('btnSubmit');
            if (!empty($_POST)) {
					$rul = array(
						array(
							'field' => 'txttileEng',
							'label' => 'Title (English)',
							'rules' => 'required'
						),
						array(
							'field' => 'txttileDutch',
							'label' => 'Title (German)',
							'rules' => 'required'
						),
						array(
							'field' => 'cmsContentEng',
							'label' => 'Content (English)',
							'rules' => 'required'
						)
						,array(
							'field' => 'cmsContentDutch',
							'label' => 'Content (German)',
							'rules' => 'required'
						)
					);
					$this->form_validation->set_rules($rul);
				if ($this->form_validation->run()) {
					  if ($insert_id = $this->pagesmodel->addpage()) { 
                        $this->session->set_flashdata('item', '<div class="warning pos">Page added successfully. </div>');
                        redirect('admin/pages');
                    } else {
                        $this->session->set_flashdata('item', '<div class="warning neg">There is some error while creating a page. Please try again!</div>');
                    }
				}
				
                  
				
			}
            $data['title'] 			= "Add New Page"; 
            $this->load->view('admin/cms/addeditpage', $data);
    }
	
	/**
	* @ Function Name		: editPage
	* @ Function Params	: 
	* @ Function Purpose 	: Edit page functionality by id
	* @ Function Returns	: 
	*/
    function editpage($id='') {
		$data = array();
		if (!empty($_POST)) {
			$rul = array(
				array(
					'field' => 'txttileEng',
					'label' => 'Title (English)',
					'rules' => 'required'
				),
				array(
					'field' => 'txttileDutch',
					'label' => 'Title (German)',
					'rules' => 'required'
				),
				array(
					'field' => 'cmsContentEng',
					'label' => 'Content (English)',
					'rules' => 'required'
				)
				,array(
					'field' => 'cmsContentDutch',
					'label' => 'Content (German)',
					'rules' => 'required'
				)
			);
			$this->form_validation->set_rules($rul);
			if ($this->form_validation->run()) {
				$uid = $this->input->post('cmsid');
				$res = $this->pagesmodel->editPageData($uid);
				/* Code end's here */
				if ($res) {
					$message = "<div class='warning pos'>Selected user edited successfully</div>";
					$this->session->set_flashdata('item', $message);
				} else {
					$message = "<div class='warning neg'>Selected user edit unsuccessful</div>";
					$this->session->set_flashdata('item', $message);
				}
				redirect('admin/pages');
			}
		} 
		$pageData = $this->pagesmodel->getDetails($id);
		    $pageInfo 	= array(
				"id"			=> $pageData->cmsid,
				"cmsTitleEng" 		=> $pageData->cmsTitleEng,
				"cmsTitleDutch" 		=> $pageData->cmsTitleDutch,
				"cmsContentEng"		=> $pageData->cmsContentEng,
				"cmsContentDutch"		=> $pageData->cmsContentDutch,
				"cmsStatus" 		=> $pageData->cmsStatus
			); 
		$data['detail'] = $pageInfo;
		$data['title'] = "Edit Page"; 
		$this->load->view('admin/cms/addeditpage', $data);
    }
	
	/**
	* @ Function Name		: active
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: make page status active
	* @ Function Returns	: 
	*/
    function active($id = '') {
        $result = false;
        if (!empty($id)) {
            $result = $this->pagesmodel->status($id, '1');
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->pagesmodel->status($id, '1');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Active status set for selected page(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected page(s), please try again.<div>');
        }
        redirect('admin/pages');
    }
	
	/**
	* @ Function Name		: delete
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: delete page functionality
	* @ Function Returns	: 
	*/
    function delete($id = '') {
        $result = false;
        if (!empty($id)) {
            $result = $this->pagesmodel->deletePage($id);
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->pagesmodel->deletePage($id);
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Selected page(s) deleted successully</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">Selected page(s) is not deleted, please try again</div>');
        }
        redirect('admin/pages');
	}
		
	/**
	* @ Function Name		: inactive
	* @ Function Params		: $id {array/integer}
	* @ Function Purpose 	: make page status inactive
	* @ Function Returns	: 
	*/
    function inactive($id = '') {
        $result = false;
        if (!empty($id)) {
            $result = $this->pagesmodel->status($id, '0');
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->pagesmodel->status($id, '0');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Inactive status set for selected page(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected page(s), please try again.</div>');
        }
        redirect('admin/pages');
    } 
}
/* End page.php file */
/* Location: ./application/controllers/cms/page.php */
?>
