<?php

/**
 * Method to get Book Details by Its ID
 * @param type $id (BookId)
 * @return Objetc 
 */
function getDetailById($id){
    $ci = get_instance();
    $ci->db->select("*")->from("tbl_books")->where("bookId",$id);
    $res = $ci->db->get();
    return $res->row();
}
/**
 * Method to count and display cart items
 */
function countCart()
{
    $ci = get_instance();
    $cartArray = $ci->session->userdata("shopping_cart");
    if(!is_array($cartArray))
    {
        echo 0;
    }else{
    echo count($cartArray);}
}

?>
