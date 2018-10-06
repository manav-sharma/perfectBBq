<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

 /* * * Cmsmodel Class
 * @package home quity exchange 
 * @subpackage Frontend
 * @category Category
 ****/

class Cmsmodel extends CI_Model {
	

    public function __construct() {
        parent::__construct();
    }
	
	/**
	* @ Function Name		: cmsData
	* @ Function Params	: 
	* @ Function Purpose 	: get Page data
	* @ Function Returns	: 
	*/
	
    function cmsData($variable ="") {		
		$this->db->select('*');
        $this->db->from("tbl_cms");
		$this->db->where("cmsVariable",$variable);
        $result = $this->db->get()->row_array();
        
        if(!empty($result))
			return $result;
    }
}
/* End of file cmsmodel.php */
/* Location: ./application/model/frontend/cmsmodel.php */
?>
