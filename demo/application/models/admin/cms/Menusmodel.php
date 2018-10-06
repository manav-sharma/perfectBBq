<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/* * * Users Model Class
*
* @package ClubInquire
* @subpackage Admin
*
* * */

class Menusmodel extends CI_Model {
	
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
	* @ Function Name		: getDetails
	* @ Function Params		: 
	* @ Function Purpose 	: function to edit existing page by admin
	* @ Function Returns	: 
	**/
	
	function getDetails($id) {
		$this->db->select("*");
		$this->db->from("tbl_menus");  
		$this->db->where("tbl_menus.mnuId", $id);
		return $this->db->get()->row();
	}
	/**
	* @ Function Name		: deleteUsers
	* @ Function Params		: $id {Array}
	* @ Function Purpose 	: delete users by id also delete from subscription list if user is subscribed
	* @ Function Returns	: 
	*/
	function deletePage($id) {  
		$this->db->where("mnuId IN (" . $id . ")", "", FALSE);  
		if ($this->db->delete('tbl_menus') == True){ 
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
			'mnuStatus' => $status,
		);
		$this->db->where('mnuId IN (' . $id . ')', '', false);
		return $this->db->update('tbl_menus', $data);
	}

	 

	/**
	* @ Function Name		: addmenu
	* @ Function Params		: 
	* @ Function Purpose 	: function to add new menu by admin
	* @ Function Returns	: 
	**/
	public function addmenu() {
		$return = FALSE;  
		$mnudetails = array(
			'mnuName'			=> strtolower(preg_replace('/\s+/', '',$this->input->post('txtName'))),
			'mnuStatus' 		=> $this->input->post('txtmnuStatus'), 
			'mnuDateCreated'  => date('Y-m-d'), 
		); 
		if ($this->db->insert('tbl_menus', $mnudetails)) { 
			return true;
		}
		return $return;
	}
	
	/**
	* @ Function Name		: addmenu
	* @ Function Params		: 
	* @ Function Purpose 	: function to add new menu by admin
	* @ Function Returns	: 
	**/
	public function editMenuData($id) {
		$return = FALSE;  
		$mnudetails = array(
			'mnuName'			=> strtolower(preg_replace('/\s+/', '',$this->input->post('txtName'))),strtolower(preg_replace('/\s+/', '',$this->input->post('txtName'))),
			'menu_title'		=> strtolower(preg_replace('/\s+/', '',$this->input->post('txtName'))),
			'mnuStatus' 		=> $this->input->post('txtmnuStatus')
		); 
		$where=array("mnuId"=>$id);
		if ($this->db->update('tbl_menus', $mnudetails,$where)) {  
			return true;
		}
		return $return;
	}
	
	
	/**
	* @ Function Name		: editUserData
	* @ Function Params		: 
	* @ Function Purpose 	: function to edit existing user by admin
	* @ Function Returns	: 
	**/
	public function editPageData($usrId) {
		$return = FALSE;
		$id = $usrId;  
		$pagedetails = array(
			'cmsTitle'			=> $this->input->post('txttile'),
			'cmsVariable' 		=> strtolower(preg_replace('/\s+/', '', $this->input->post('txttile'))),  
			'cmsContent' 		=> $this->input->post('cmsContent'),
			'cmsStatus' 		=> $this->input->post('txtcmsStatus'), 
			'cmsDateModified ' => date('Y-m-d'), 
		);
	  
		$where = array('mnuId' => $id);
		if ($this->db->update('tbl_menus', $pagedetails, $where)) { 
			return TRUE;
		} else {
			return FALSE;
		}
		return $return;
	}
	/**
	* @ Function Name		: menulinks
	* @ Function Params		: 
	* @ Function Purpose 	: add menu page
	* @ Function Returns	: 
	**/
	public function menulinks($pageId,$parentId,$sortOrder,$menuId,$url,$title) {

		 $return = FALSE; 
		$menudetails = array(
			'menu_id'			=> $menuId,
			'parent_page_id' 	=> $parentId,  
			'page_id' 			=> $pageId,
			'sort_order' 		=> $sortOrder
		);  
		$return= $this->db->insert('tbl_menulinks', $menudetails);
		$last_id = $this->db->insert_id();
		if(isset($url) && !empty($url))
		{
		$data['mec_menuinfo_id']    = $menuId;
		$data['menulink_id']    	= $last_id;
		$data['menulink_url']  		= $url;
		$data['menulink_name'] 		= $title;		
		$this->db->insert('tbl_menucustomlink',$data);
		}
		 
		return $this->db->insert_id(); 
	}
	
	
	/**
	* @ Function Name		: menulinks
	* @ Function Params		: 
	* @ Function Purpose 	: add menu page
	* @ Function Returns	: 
	**/
	public function getParentMenuId($pageId,$menuId) {
		$this->db->select("id");
		$this->db->from("tbl_menulinks");  
		$this->db->where("tbl_menulinks.menu_id", $menuId);
		$this->db->where("tbl_menulinks.page_id", $pageId); 
		$this->db->order_by("id", "DESC");  
		return $this->db->get()->row()->id;
	}
	
	/**
	* @ Function Name		: deleteMenu
	* @ Function Params		: 
	* @ Function Purpose 	: delete previous menu
	* @ Function Returns	: 
	**/
	public function deleteMenu($menuId) {
		$return = FALSE;  
		$this->db->where("menu_id IN (" . $menuId . ")", "", FALSE);  
		$return = $this->db->delete('tbl_menulinks');  
		$this->db->where("mec_menuinfo_id IN (" . $menuId . ")", "", FALSE);  
		$return = $this->db->delete('tbl_menucustomlink');  
		return $return;
	}
	 
	/**
	* @ Function Name		: deleteMenu
	* @ Function Params		: 
	* @ Function Purpose 	: delete previous menu
	* @ Function Returns	: 
	**/
	public function delete($menuId) {
		$return = FALSE;  
		$this->db->where("menu_id IN (" . $menuId . ")", "", FALSE);  
		$return = $this->db->delete('tbl_menulinks');  
		$this->db->where("mnuId IN (" . $menuId . ")", "", FALSE);  
		$return = $this->db->delete('tbl_menus'); 
		return $return;
	} 
		/**
	 * @ Function Name		: getCategories
	 * @ Function Params	: NULL
	 * @ Function Purpose 	: get the category/sub-category list
	 * @ Function Returns	: array
	 */
	public function getmenus($id,$level=0) { 
		static $result_array; 
		$this->db->select('*');
		$this->db->where('menu_id',$id);
		$query = $this->db->get('tbl_menulinks');
		$result = $query->result();
		 
					$this->db->select('me.*,cm.cmsTitle,cm.cmsVariable,t2ml.*'); 
					$this->db->join('tbl_cms cm','cm.cmsid = me.page_id','LEFT');
					$this->db->join('tbl_menucustomlink t2ml', 't2ml.menulink_id = me.id','LEFT');
					$this->db->where('me.parent_page_id', $level);
					$this->db->where('me.menu_id', $id);
					$this->db->order_by('me.id','ASC');
					$rows = $this->db->get('tbl_menulinks me')->result_array();
					
			
			foreach($rows as $key=>$r){  
				$result_array[]= $r;   
				$subchild =$this->sub_children($id,$r['id']); 
				array_push($result_array[$key],$subchild);
			}	
			
		// pr($result_array,false);
 
		return $result_array;	
	}
	/**
	* @ Function Name		: getSubMenu
	* @ Function Params		: 
	* @ Function Purpose 	: get  SubMenu menu
	* @ Function Returns	: 
	**/
		public function sub_children($id,$parent) {
			static $child;  
			$this->db->select('me.*,cm.cmsTitle,t2ml.*'); 	  
			$this->db->join('tbl_cms cm', 'cm.cmsid = me.page_id','LEFT');
			$this->db->join('tbl_menucustomlink t2ml', 't2ml.menulink_id = me.id','LEFT');
			$this->db->where('me.parent_page_id', $parent);
			$this->db->where('me.menu_id', $id);
			$result = $this->db->get("tbl_menulinks me")->result_array(); 
			/* echo $this->db->last_query();die; */
			if(count(array_filter($result))>0){  
				foreach($result as $key=>$subchild){ 
					$subchild =$this->sub_children($id,$subchild['id']);
						if(!empty($subchild))
						$result[$key]['subchild'] = $subchild; 
				} 
			} 
		return $result;
		} 
}
?>
