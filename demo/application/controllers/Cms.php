<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/** Pages Class
 *
 * @package Home Quity Exchange
 * @subpackage Frontend
 */
 
class Cms extends MY_Controller {
	/**
	* @ Function Name	: __constructor
	* @ Function Purpose 	: constructor function for class to load default files
	* @ Function Returns	: 
	**/
    public function __construct() {
        parent::__construct();
        $this->load->model("frontend/cms/cmsmodel");
        $this->data['select'] = "cms"; 
    }
	
	 /**
	* @ Function Name	: page
	* @ Function Params	: id(string)
	* @ Function Purpose 	: load page
	* @ Function Returns	:  
	*/
	
    function page($id) {
		$data['title'] = ucwords($this->cmsmodel->title($id));
		
		switch($id){
            case "proposalcontext":
                $this->data['select'] = "proposalcontext";
                break; 
			case "recommendedrulesandregulations":
                $this->data['select'] = "recommendedrulesandregulations";
                break; 	
            case "economicimpactconsiderations":
                $this->data['select'] = "economicimpactconsiderations";
                break; 	
			case "aboutus":
                $this->data['select'] = "aboutus";
                break; 	 	
                
        }
        if (!empty($this->_userdata)) {
            $userName = $this->session->userdata("uid");
            $data['user_details'] = $this->usersmodel->getUserDetails($userName);
        } 
        $data['cmsContent'] = $this->cmsmodel->editBox($id, 'db');
		$this->load->view('frontend/cms/cmsview',$data); 
    }
	 /**
	* @ Function Name	: mfm
	* @ Function Params	: 
	* @ Function Purpose 	: load library
	* @ Function Returns	:  
	*/
	
	function mfm(){
		$this->load->library("cms_mfm");
		//handles file uploads
		if(isset($_FILES['new_file']) && isset($_POST['return'])) {
			$no_script = false;     
			if(is_dir($_POST['return'])){
				$this->load->library('cms_upload',$_FILES['new_file']);
				if($this->cms_upload->uploaded){
					$this->cms_upload->file_new_name_body = $this->cms_mfm->format_filename(substr($_FILES['new_file']['name'],0,-4));
					if(isset($_POST['new_resize']) && $_POST['new_resize'] > 0){
						$this->cms_upload->image_resize         = true;
						$this->cms_upload->image_x              = (int)$_POST['new_resize'];
						$this->cms_upload->image_ratio_y        = true;
					}
					if(isset($_POST['new_greyscale']) && $_POST['new_greyscale']){
						$this->cms_upload->image_greyscale      = true;
					}
					if(isset($_POST['new_rotate']) && $_POST['new_rotate'] == 90 or $_POST['new_rotate'] == 180 or $_POST['new_rotate'] == 270){
						$this->cms_upload->image_rotate = $_POST['new_rotate'];
					}
					$this->cms_upload->mime_check = $no_script;
					$this->cms_upload->no_script = $no_script;
					$this->cms_upload->process($_POST['return'] . '/');
					if ($this->cms_upload->processed){
						$this->cms_upload->clean();
						$uploadstatus = 1;
					}else{
						//uncomment for upload debugging
						echo 'error : ' . $this->cms_upload->error;
						$uploadstatus = 2;
					}
				}
			}else{
				$uploadstatus = 3;
			}
		}
		$this->load->view("frontend/cms/mfm");
	}
	
     
     
     /**
	* @ Function Name	: cmsSave
	* @ Function Params	: 
	* @ Function Purpose 	: Saves the page updation in database
	* @ Function Returns	: return Saves the page updation in database.
	*/
	
    public function cmsSave(){
        $this->cmsmodel->save_content();
    }
	
	
	/**
	* @ Function Name		: index
	* @ Function Params	: 
	* @ Function Purpose 	: loda content page Now
	* @ Function Returns	: 
	*/
	
	public function content()
	{
		$data=array();
		$data['title']='Standard Content';
		$data['page']='frontend/content/content';
		$this->load->view('frontend/index',$data);
	}
} 
?>