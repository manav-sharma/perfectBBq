<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menus extends MY_Controller {

    /**
     * @ Function Name		: __construct
     * @ Function Params	: 
     * @ Function Purpose 	: initilizing variable and providing pre functionalities
     * @ Function Returns	: 
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('admin/cms/menusmodel');
        //$parentId=$this->menusmodel->getParentMenuId(1,4);
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

        $mnuName = $this->input->post("txtName");
        $mnuStatus = $this->input->post("cmbStatus");
        $array = array('mnuName', 'mnuStatus');

        $likeConditionArray = compact($array);
        $customConditionVar = array(
            array(
                'tableWithCondition' => "DATE_FORMAT(tbl_menus.mnuDateCreated ,'%Y-%m-%d') >= 'FieldValue'",
                'fieldName' => 'txtDateFrom',
                'condition' => 'AND',
                'Value' => 'From'
            ),
            array(
                'tableWithCondition' => "DATE_FORMAT(tbl_menus.mnuDateCreated ,'%Y-%m-%d') <= 'FieldValue'",
                'fieldName' => 'txtDateTo',
                'condition' => 'AND',
                'Value' => 'To'
            )
        );


        $result = getList('tbl_menus', "*", $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar, $paging);
        $data["pageinfo"] = $result;
        $data["postData"] = $this->input->post();
        $data["sortBy"] = $orderingVar["sortBy"];
        $data["orderBy"] = $orderingVar["orderBy"];

        $this->load->view('admin/cms/menus', $data);
    }

    /**
     * @ Function Name		: addUser
     * @ Function Params	: 
     * @ Function Purpose 	: admin can add new user
     * @ Function Returns	: 
     */
    function addmenu() {
        $data = array();
        $submit = $this->input->post('btnSubmit');
        if (!empty($_POST)) {
            $rul = array(
                array(
                    'field' => 'txtName',
                    'label' => 'Name',
                    'rules' => 'required'
                )
            );
            $this->form_validation->set_message('is_unique', 'This %s is already registered. Please choose another.');
            $this->form_validation->set_rules($rul);
            if ($this->form_validation->run()) {
                if ($insert_id = $this->menusmodel->addmenu()) {
                    $this->session->set_flashdata('item', '<div class="warning pos">Menu added successfully. </div>');
                    redirect('admin/menus');
                } else {
                    $this->session->set_flashdata('item', '<div class="warning neg">There is some error while creating a menu. Please try again!</div>');
                }
            } else {
                $this->session->set_flashdata('item', '<div class="warning neg">Please fill up all mandatory fields properly.</div>');
            }
        }
        $data['title'] = "Add New Menu";
        $this->load->view('admin/cms/addeditmenu', $data);
    }

    /**
     * @ Function Name		: editMenu
     * @ Function Params	: 
     * @ Function Purpose 	: Edit page functionality by id
     * @ Function Returns	: 
     */
    function editmenu($id = '') {
        $data = array();
        if (!empty($_POST)) {
            $rul = array(
                array(
                    'field' => 'txtName',
                    'label' => 'Name',
                    'rules' => 'required'
                )
            );
            $this->form_validation->set_rules($rul);
            if ($this->form_validation->run()) {
                $res = $this->menusmodel->editMenuData($id);
                /* Code end's here */
                if ($res) {
                    $message = "<div class='warning pos'>Selected menu edited successfully</div>";
                    $this->session->set_flashdata('item', $message);
                } else {
                    $message = "<div class='warning neg'>Selected menu edit unsuccessful</div>";
                    $this->session->set_flashdata('item', $message);
                }
                redirect('admin/menus');
            }
        }

        $pageData = $this->menusmodel->getDetails($id);

        $pageInfo = array(
            "mnuId" => $pageData->mnuId,
            "mnuName" => $pageData->mnuName,
            "mnuStatus" => $pageData->mnuStatus
        );
        $data['detail'] = $pageInfo;
        $data['title'] = "Edit Menu";
        $this->load->view('admin/cms/addeditmenu', $data);
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
            $result = $this->menusmodel->status($id, '1');
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->menusmodel->status($id, '1');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Active status set for selected page(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected page(s), please try again.<div>');
        }
        redirect('admin/menus');
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
            $result = $this->menusmodel->delete($id);
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->menusmodel->delete($id);
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Selected page(s) deleted successully</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">Selected page(s) is not deleted, please try again</div>');
        }
        redirect('admin/menus');
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
            $result = $this->menusmodel->status($id, '0');
        } else {
            $id_arr = $this->input->post('chkBox');
            $id = (is_array($id_arr) && count($id_arr) != 0) ? implode(",", $id_arr) : "0";
            if ($id != 0) {
                $result = $this->menusmodel->status($id, '0');
            }
        }
        if ($result == true) {
            $this->session->set_flashdata('item', '<div class="warning pos">Inactive status set for selected page(s) successfully.</div>');
        } else {
            $this->session->set_flashdata('item', '<div class="warning neg">No status is set for selected page(s), please try again.</div>');
        }
        redirect('admin/menus');
    }

    /**
     * @ Function Name		: addmenulink
     * @ Function Params		: $id {array/integer}
     * @ Function Purpose 	: make page status inactive
     * @ Function Returns	: 
     */
    function addmenulink() {

        $pageiDs = $this->input->post("lists");
        
        $segmentedURI = $this->uri->segment_array();
        
        $menuId = end($segmentedURI);
        
        $this->menusmodel->deleteMenu($menuId);
        $data = array('status', true);
        if (!empty($pageiDs)):

            $explodearrays = explode('&', $pageiDs);
            $menuOrder = 0;
            $data = array('status', false);
            if (!empty($explodearrays)):
                foreach ($explodearrays as $val):
                    parse_str($val, $arrays);
                    foreach ($arrays['list'] as $key => $value):
                        $info_key = explode(',', $key);

                        if (isset($info_key[2])) {
                            $url = $info_key[2];
                            $name = $info_key[1];
                            if ($value == 'null') {
                                $lastInsertId = $this->menusmodel->menulinks(0, 0, 0, $menuId, $url, $name);
                                $menuOrder = 0;
                            } else {
                                $url = $info_key[2];
                                $name = $info_key[1];
                                ++$menuOrder;
                                $parentId = $this->menusmodel->getParentMenuId($value, $menuId);
                                $this->menusmodel->menulinks(0, $parentId, $menuOrder, $menuId, $url, $name);
                            }
                        } else {
                            if ($value == 'null') {
                                $lastInsertId = $this->menusmodel->menulinks($key, 0, 0, $menuId, '', '');
                                $menuOrder = 0;
                            } else {
                                ++$menuOrder;
                                $parentId = $this->menusmodel->getParentMenuId($value, $menuId);
                                $this->menusmodel->menulinks($key, $parentId, $menuOrder, $menuId, '', '');
                            }
                        }
                    endforeach;
                endforeach;
                $data = array('status', true);
            endif;
        endif;
        echo json_encode($data);
    }

}

/* End page.php file */
/* Location: ./application/controllers/cms/page.php */
?>