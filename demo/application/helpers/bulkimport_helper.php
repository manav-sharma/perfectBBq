<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function getCategoryId($data){
	
	$CI = & get_instance();
	$main_catId = 0;
	$sub_catId = 0;
	$sub_sub_catId = 0;
	$return = array();
	
	print_r($data);die;
	/*if(isset($data['Main Categorie']) && $data['Main Categorie']!=''){
		$CI->db->select('catId');
		$CI->db->from('tbl_categories');
		$CI->db->where('catName',$data['Main Categorie']);
		$cat_data = $CI->db->get()->row();
		
		if(empty($cat_data)){
			$CI->db->select_max('catSortingOrder');
			$CI->db->from('tbl_categories');
			$CI->db->where('catPId','0');
			$res = $CI->db->get()->row();
			$sortorder = $res->catSortingOrder;
			$sortorder++; 
			$CI->db->insert('tbl_categories',array('catPId'=> '0','catName'=>$data['Main Categorie'],'catSortingOrder'=>$sortorder,'catStatus'=>'1'));
			$return['catId'] = $main_catId =  $CI->db->insert_id();
		} else {
			$return['catId'] = $main_catId = $cat_data->catId;
		}
	}
	
	if(isset($data['Category-1']) && $data['Category-1']!=''){
		$CI->db->select('catId');
		$CI->db->from('tbl_categories');
		$CI->db->where('catName',$data['Category-1']);
		$subcat_data = $CI->db->get()->row();
		
		if(empty($subcat_data)){
			$CI->db->insert('tbl_categories',array('catPId'=> $main_catId,'catName'=>$data['Category-1'],'catSortingOrder'=>'0','catStatus'=>'1'));
			$return['catId'] = $sub_catId =  $CI->db->insert_id();
		} else {
			$return['catId'] = $sub_catId = $subcat_data->catId;
		}
	}
	
	if(isset($data['Category-2']) && $data['Category-2']!='') {
		$CI->db->select('catId');
		$CI->db->from('tbl_categories');
		$CI->db->where('catName',$data['Category-2']);
		$sub_subcat_data = $CI->db->get()->row();
		
		if(empty($sub_subcat_data)){
			$CI->db->insert('tbl_categories',array('catPId'=> $sub_catId,'catName'=>$data['Category-2'],'catSortingOrder'=>'0','catStatus'=>'1'));
			$return['catId'] = $sub_sub_catId =  $CI->db->insert_id();
		} else {
			$return['catId'] = $sub_sub_catId = $sub_subcat_data->catId;
		}
	}
	
	return $return['catId'];*/
}
 
?>