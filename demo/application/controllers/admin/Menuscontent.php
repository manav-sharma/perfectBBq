<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
	
class Menuscontent extends MY_Controller {	
	/**
	* @ Function Name		: __construct
	* @ Function Params	: 
	* @ Function Purpose 	: initilizing variable and providing pre functionalities
	* @ Function Returns	: 
	*/
	public function __construct() {
		parent::__construct();   
		$this->load->model('admin/cms/MenusContentmodel');
		$this->load->model('admin/cms/Menusmodel');  
		$this->load->model('admin/cms/Pagesmodel');  
	}
	/**
	* @ Function Name		: index
	* @ Function Params	: 
	* @ Function Purpose 	: Managing menus
	* @ Function Returns	: 
	*/
	public function index() {
		$data['title'] = "CMS Menus"; 
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
                $sortBy = "mnuDateCreated_desc";
            }
			
		$sortBy = explode("_", $sortBy);
		$orderBy = $sortBy[1];
		$sortBy = $sortBy[0]; 
		
            if ($filterOrSort == "sort") {
                $orderBy = ($orderBy == "desc") ? "asc" : "desc";
            }
			
		$orderingVar['orderBy'] = $data['orderBy'] = $orderBy;
		$orderingVar['sortBy'] = $data['sortBy'] = $sortBy;
		 
		$array = array('mnuName','cmsStatus');
		
		$likeConditionArray = compact($array);
		  
			$customConditionVar=  array(  
					 array( 
						'tableWithCondition'		=> "(tbl_menus.mnuName like '%".'FieldValue'."%')",
						'fieldName'					=> 'txtName'
					),
					array(
						'tableWithCondition'		=> "DATE_FORMAT(tbl_menus.mnuDateCreated ,'%Y-%m-%d') >= 'FieldValue'",
						'fieldName'					=> 'txtDateFrom',
						'condition'					=> 'AND',
						'Value'						=> 'From'
					), 
					array(
						'tableWithCondition'		=> "DATE_FORMAT(tbl_menus.mnuDateCreated ,'%Y-%m-%d') <= 'FieldValue'",
						'fieldName'					=> 'txtDateTo',
						'condition'					=> 'AND',
						'Value'						=> 'To'
					) 
						
			);
			    
			
        $result = getList('tbl_menus', "*", $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar,$paging); 
		$data["pageinfo"] = $result;
		$data["postData"] = $this->input->post();
		$data["sortBy"] = $orderingVar["sortBy"];
		$data["orderBy"] = $orderingVar["orderBy"];
		
		$this->load->view('admin/cms/menusContent', $data);
	}
	/**
	* @ Function Name		: manageLinks
	* @ Function Params	: 
	* @ Function Purpose 	: Managing links inside menus
	* @ Function Returns	: 
	*/
	public function	manageLinks($id='') { 
	   
		$menuData = $this->Menusmodel->getDetails($id);
		$pagesData = $this->Pagesmodel->getActivePages();
		
		
		$pageInfo 	= array(
			"mnuId"			=> $menuData->mnuId,
			"mnuName" 		=> $menuData->mnuName,
			"mnuStatus" 	=> $menuData->mnuStatus
		); 
		
		$data['detail'] = $pageInfo;
		$data['pages'] = $pagesData;
		$data['menus'] = $this->Menusmodel->getmenus($id,0);  
		$data['title'] = "Menus Links"; 
		$this->load->view('admin/cms/manageLinks',$data);
	}
}
/* End page.php file */
/* Location: ./application/controllers/cms/page.php */
?>
