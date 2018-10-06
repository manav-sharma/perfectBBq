<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @ Function Name		: pr
 * @ Function Params	: $data {mixed}, $kill {boolean}
 * @ Function Purpose 	: formatted display of value of varaible
 * @ Function Returns	: foramtted string
 */
function pr($data, $kill = true) {
    $str = "";
    if ($data != '') {
        $str .= str_repeat("=", 25) . " " . ucfirst(gettype($data)) . " " . str_repeat("=", 25);
        $str .= "<pre>";
        if (is_array($data)) {
            $str .= print_r($data, true);
        }
        if (is_object($data)) {
            $str .= print_r($data, true);
        }
        if (is_string($data)) {
            $str .= print_r($data, true);
        }
        $str .= "</pre>";
    } else {
        $str .= str_repeat("=", 22) . " Empty Data " . str_repeat("=", 22);
    }

    if ($kill) {
        die($str .= str_repeat("=", 55));
    }
    echo $str;
}

/**
 *
 * @param type $filename
 * @return type 
 */
if (!function_exists('current_file_name')) {

    function current_file_name($filename = '') {
        return basename(str_replace('\\', '/', $filename), ".php");
    }

}

/**
 *
 * @param type $filename
 * @return type 
 */
if (!function_exists('current_file_dir')) {

    function current_file_dir($filename = '') {
        return basename(dirname(str_replace('\\', '/', $filename))) . '/';
    }

}

if (!function_exists('objectToArray')) {

    function objectToArray($obj) {
        print_r($obj);
        echo is_object($obj);
        if (is_object($obj)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $obj = get_object_vars($obj);
        }
    }

}

function getList($tableName, $selectString, $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar, $whereNotIn = array(), $groupby = array()) {
    $args = func_get_args();
    //pr($args);

    $CI = & get_instance();
    $CI->db->start_cache();
    $CI->db->select($selectString, FALSE);
    $CI->db->from($tableName);
    foreach ($joinTableVar as $joinKey => $joinArray) {
        if ((isset($joinArray['tableName']) && ( $joinArray['tableName'] != "")) && (isset($joinArray['joinCondition']) && ( $joinArray['joinCondition'] != "")) && (isset($joinArray['joinType']) && ( $joinArray['joinType'] != ""))) {
            $CI->db->join($joinArray['tableName'], $joinArray['joinCondition'], $joinArray['joinType']);
        } elseif ((isset($joinArray['tableName']) && ( $joinArray['tableName'] != "")) && (isset($joinArray['joinCondition']) && ( $joinArray['joinCondition'] != ""))) {
            $CI->db->join($joinArray['tableName'], $joinArray['joinCondition']);
        } elseif ((isset($joinArray['tableName']) && ( $joinArray['tableName'] != ""))) {
            $CI->db->join($joinArray['tableName'], FALSE);
        }
    }
    foreach ($customConditionVar as $filterKey => $filterVal) {
        if (!empty($filterVal)) {
            $field = $filterVal['fieldName'];
            $notValue = isset($filterVal['Value']) ? $filterVal['Value'] : "";
            if (isset($_POST[$field]) && !empty($_POST[$field]) && $_POST[$field] != $notValue) {
                $query = str_replace("FieldValue", $_POST[$field], $filterVal['tableWithCondition']);
                if (isset($filterVal['condition']) && !empty($filterVal['condition']) && $filterVal['condition'] == 'OR')
                    $CI->db->or_where($query);
                else
                    $CI->db->where($query);
            }
        }
    }
    foreach ($whereConditionVar as $filterKey => $filterVal) {
        if ($filterVal != "") {
            if (count($joinTableVar) > 0) {
                if ($CI->db->field_exists($filterKey, $tableName)) {
                    $CI->db->where($tableName . "." . $filterKey, $filterVal);
                } else {
                    foreach ($joinTableVar as $joinKey => $joinArray) {
                        if (isset($joinArray['tableName']) && ( $joinArray['tableName'] != "") && $CI->db->field_exists($filterKey, $joinArray['tableName'])) {
                            $CI->db->where($joinArray['tableName'] . "." . $filterKey, $filterVal);
                            break;
                        }
                    }
                }
            } else {
                $CI->db->where($filterKey, $filterVal);
            }
        }
    }

    foreach ($whereNotIn as $filterKey => $filterVal) {
        if ($filterVal != "") {
            if (count($joinTableVar) > 0) {
                if ($CI->db->field_exists($filterKey, $tableName)) {
                    $CI->db->where_not_in($tableName . "." . $filterKey, $filterVal);
                } else {
                    foreach ($joinTableVar as $joinKey => $joinArray) {
                        if (isset($joinArray['tableName']) && ( $joinArray['tableName'] != "") && $CI->db->field_exists($filterKey, $joinArray['tableName'])) {
                            $CI->db->where_not_in($joinArray['tableName'] . "." . $filterKey, $filterVal);
                            break;
                        }
                    }
                }
            } else {
                $CI->db->where_not_in($filterKey, $filterVal);
            }
        }
    }

    foreach ($likeConditionArray as $filterKey => $filterVal) {
        if ($filterVal != "") {
            if (count($joinTableVar) > 0) {
                if ($CI->db->field_exists($filterKey, $tableName)) {
                    $CI->db->like($tableName . "." . $filterKey, $filterVal);
                } else {
                    foreach ($joinTableVar as $joinKey => $joinArray) {
                        if (isset($joinArray['tableName']) && ( $joinArray['tableName'] != "") && $CI->db->field_exists($filterKey, $joinArray['tableName'])) {
                            $CI->db->like($joinArray['tableName'] . "." . $filterKey, $filterVal);
                            break;
                        }
                    }
                }
            } else {
                $CI->db->like($filterKey, $filterVal);
            }
        }
    }
    $config['pgn'] = (isset($_POST["pgn"]) ? $_POST["pgn"] : 1);
    $config['ipp'] = (isset($_POST["ipp"]) ? $_POST["ipp"] : 100);


    $config['totalRows'] = $CI->db->count_all_results();
    if (isset($groupby) && !empty($groupby)) {
        $CI->db->group_by($groupby['field']);
        if (isset($groupby['having']) && !empty($groupby['having'])) {
            $CI->db->having($groupby['having']);
        }
    }
    $CI->db->order_by($orderingVar["sortBy"], $orderingVar["orderBy"]);
    $limit = $config['ipp'];
   
    $offset = $config['ipp'] * ($config['pgn'] - 1);
    $CI->db->limit($limit, $offset);
    $result = $CI->db->get()->result();

    //echo $CI->db->last_query(); 

    $CI->db->flush_cache();
    $CI->db->stop_cache();
    $CI->activepagination->setPaginationVariable($config);
    $CI->db->flush_cache();
    /* ends up here */
    return $result;
}

function getRecipeList($tableName, $selectString, $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar, $whereNotIn = array(), $groupby = array()) {
    $args = func_get_args();
    //pr($args);

    $CI = & get_instance();
    $CI->db->start_cache();
    $CI->db->select($selectString, FALSE);
    $CI->db->from($tableName);
    foreach ($joinTableVar as $joinKey => $joinArray) {
        if ((isset($joinArray['tableName']) && ( $joinArray['tableName'] != "")) && (isset($joinArray['joinCondition']) && ( $joinArray['joinCondition'] != "")) && (isset($joinArray['joinType']) && ( $joinArray['joinType'] != ""))) {
            $CI->db->join($joinArray['tableName'], $joinArray['joinCondition'], $joinArray['joinType']);
        } elseif ((isset($joinArray['tableName']) && ( $joinArray['tableName'] != "")) && (isset($joinArray['joinCondition']) && ( $joinArray['joinCondition'] != ""))) {
            $CI->db->join($joinArray['tableName'], $joinArray['joinCondition']);
        } elseif ((isset($joinArray['tableName']) && ( $joinArray['tableName'] != ""))) {
            $CI->db->join($joinArray['tableName'], FALSE);
        }
    }
    foreach ($customConditionVar as $filterKey => $filterVal) {
        if (!empty($filterVal)) {
            $field = $filterVal['fieldName'];
            $notValue = isset($filterVal['Value']) ? $filterVal['Value'] : "";
            if (isset($_POST[$field]) && !empty($_POST[$field]) && $_POST[$field] != $notValue) {
                $query = str_replace("FieldValue", $_POST[$field], $filterVal['tableWithCondition']);
                if (isset($filterVal['condition']) && !empty($filterVal['condition']) && $filterVal['condition'] == 'OR')
                    $CI->db->or_where($query);
                else
                    $CI->db->where($query);
            }
        }
    }
    foreach ($whereConditionVar as $filterKey => $filterVal) {
        if ($filterVal != "") {
            if (count($joinTableVar) > 0) {
                if ($CI->db->field_exists($filterKey, $tableName)) {
                    $CI->db->where($tableName . "." . $filterKey, $filterVal);
                } else {
                    foreach ($joinTableVar as $joinKey => $joinArray) {
                        if (isset($joinArray['tableName']) && ( $joinArray['tableName'] != "") && $CI->db->field_exists($filterKey, $joinArray['tableName'])) {
                            $CI->db->where($joinArray['tableName'] . "." . $filterKey, $filterVal);
                            break;
                        }
                    }
                }
            } else {
                $CI->db->where($filterKey, $filterVal);
            }
        }
    }

    foreach ($whereNotIn as $filterKey => $filterVal) {
        if ($filterVal != "") {
            if (count($joinTableVar) > 0) {
                if ($CI->db->field_exists($filterKey, $tableName)) {
                    $CI->db->where_not_in($tableName . "." . $filterKey, $filterVal);
                } else {
                    foreach ($joinTableVar as $joinKey => $joinArray) {
                        if (isset($joinArray['tableName']) && ( $joinArray['tableName'] != "") && $CI->db->field_exists($filterKey, $joinArray['tableName'])) {
                            $CI->db->where_not_in($joinArray['tableName'] . "." . $filterKey, $filterVal);
                            break;
                        }
                    }
                }
            } else {
                $CI->db->where_not_in($filterKey, $filterVal);
            }
        }
    }

     foreach ($likeConditionArray as $filterKey => $filterVal) {
        if ($filterVal != "") {
            if (count($joinTableVar) > 0) {
                if ($CI->db->field_exists('recStatus', 'tbl_recipe')) {
                    $CI->db->like("rec.recStatus", $filterVal);
                } else {
                    foreach ($joinTableVar as $joinKey => $joinArray) {
                        if (isset($joinArray['tableName']) && ( $joinArray['tableName'] != "") && $CI->db->field_exists($filterKey, $joinArray['tableName'])) {
                            $CI->db->like($joinArray['tableName'] . "." . $filterKey, $filterVal);
                            break;
                        }
                    }
                }
            } else {
                $CI->db->like($filterKey, $filterVal);
            }
        }
    }
    $config['pgn'] = (isset($_POST["pgn"]) ? $_POST["pgn"] : 1);
    $config['ipp'] = (isset($_POST["ipp"]) ? $_POST["ipp"] : 100);


    $config['totalRows'] = $CI->db->count_all_results();
    if (isset($groupby) && !empty($groupby)) {
        $CI->db->group_by($groupby['field']);
        if (isset($groupby['having']) && !empty($groupby['having'])) {
            $CI->db->having($groupby['having']);
        }
    }
    $CI->db->order_by($orderingVar["sortBy"], $orderingVar["orderBy"]);
    $limit = $config['ipp'];
   
    $offset = $config['ipp'] * ($config['pgn'] - 1);
    $CI->db->limit($limit, $offset);
    $result = $CI->db->get()->result();

    //echo $CI->db->last_query(); 

    $CI->db->flush_cache();
    $CI->db->stop_cache();
    $CI->activepagination->setPaginationVariable($config);
    $CI->db->flush_cache();
    /* ends up here */
    return $result;
}

function getCategoryList($tableName, $selectString, $orderingVar, $whereConditionVar, $likeConditionArray, $customConditionVar, $joinTableVar, $whereNotIn = array(), $groupby = array()) {
    $args = func_get_args();
    //pr($args);

    $CI = & get_instance();
    $CI->db->start_cache();
    $CI->db->select($selectString, FALSE);
    $CI->db->from($tableName);
    foreach ($joinTableVar as $joinKey => $joinArray) {
        if ((isset($joinArray['tableName']) && ( $joinArray['tableName'] != "")) && (isset($joinArray['joinCondition']) && ( $joinArray['joinCondition'] != "")) && (isset($joinArray['joinType']) && ( $joinArray['joinType'] != ""))) {
            $CI->db->join($joinArray['tableName'], $joinArray['joinCondition'], $joinArray['joinType']);
        } elseif ((isset($joinArray['tableName']) && ( $joinArray['tableName'] != "")) && (isset($joinArray['joinCondition']) && ( $joinArray['joinCondition'] != ""))) {
            $CI->db->join($joinArray['tableName'], $joinArray['joinCondition']);
        } elseif ((isset($joinArray['tableName']) && ( $joinArray['tableName'] != ""))) {
            $CI->db->join($joinArray['tableName'], FALSE);
        }
    }
    foreach ($customConditionVar as $filterKey => $filterVal) {
        if (!empty($filterVal)) {
            $field = $filterVal['fieldName'];
            $notValue = isset($filterVal['Value']) ? $filterVal['Value'] : "";
            if (isset($_POST[$field]) && !empty($_POST[$field]) && $_POST[$field] != $notValue) {
                $query = str_replace("FieldValue", $_POST[$field], $filterVal['tableWithCondition']);
                if (isset($filterVal['condition']) && !empty($filterVal['condition']) && $filterVal['condition'] == 'OR')
                    $CI->db->or_where($query);
                else
                    $CI->db->where($query);
            }
        }
    }
    foreach ($whereConditionVar as $filterKey => $filterVal) {
        if ($filterVal != "") {
            if (count($joinTableVar) > 0) {
                if ($CI->db->field_exists($filterKey, $tableName)) {
                    $CI->db->where($tableName . "." . $filterKey, $filterVal);
                } else {
                    foreach ($joinTableVar as $joinKey => $joinArray) {
                        if (isset($joinArray['tableName']) && ( $joinArray['tableName'] != "") && $CI->db->field_exists($filterKey, $joinArray['tableName'])) {
                            $CI->db->where($joinArray['tableName'] . "." . $filterKey, $filterVal);
                            break;
                        }
                    }
                }
            } else {
                $CI->db->where($filterKey, $filterVal);
            }
        }
    }

    foreach ($whereNotIn as $filterKey => $filterVal) {
        if ($filterVal != "") {
            if (count($joinTableVar) > 0) {
                if ($CI->db->field_exists($filterKey, $tableName)) {
                    $CI->db->where_not_in($tableName . "." . $filterKey, $filterVal);
                } else {
                    foreach ($joinTableVar as $joinKey => $joinArray) {
                        if (isset($joinArray['tableName']) && ( $joinArray['tableName'] != "") && $CI->db->field_exists($filterKey, $joinArray['tableName'])) {
                            $CI->db->where_not_in($joinArray['tableName'] . "." . $filterKey, $filterVal);
                            break;
                        }
                    }
                }
            } else {
                $CI->db->where_not_in($filterKey, $filterVal);
            }
        }
    }

    foreach ($likeConditionArray as $filterKey => $filterVal) {
        if ($filterVal != "") {
            if (count($joinTableVar) > 0) {
                if ($CI->db->field_exists('catStatus', 'tbl_category')) {
                    $CI->db->like("c1.catStatus", $filterVal);
                } else {
                    foreach ($joinTableVar as $joinKey => $joinArray) {
                        if (isset($joinArray['tableName']) && ( $joinArray['tableName'] != "") && $CI->db->field_exists($filterKey, $joinArray['tableName'])) {
                            $CI->db->like($joinArray['tableName'] . "." . $filterKey, $filterVal);
                            break;
                        }
                    }
                }
            } else {
                $CI->db->like($filterKey, $filterVal);
            }
        }
    }
    $config['pgn'] = (isset($_POST["pgn"]) ? $_POST["pgn"] : 1);
    $config['ipp'] = (isset($_POST["ipp"]) ? $_POST["ipp"] : 100);


    $config['totalRows'] = $CI->db->count_all_results();
    if (isset($groupby) && !empty($groupby)) {
        $CI->db->group_by($groupby['field']);
        if (isset($groupby['having']) && !empty($groupby['having'])) {
            $CI->db->having($groupby['having']);
        }
    }
    $CI->db->order_by($orderingVar["sortBy"], $orderingVar["orderBy"]);
    $limit = $config['ipp'];
   
    $offset = $config['ipp'] * ($config['pgn'] - 1);
    $CI->db->limit($limit, $offset);
    $result = $CI->db->get()->result();

    //echo $CI->db->last_query(); 

    $CI->db->flush_cache();
    $CI->db->stop_cache();
    $CI->activepagination->setPaginationVariable($config);
    $CI->db->flush_cache();
    /* ends up here */
    return $result;
}

function is_date($str) {
    try {
        $dt = new DateTime(trim($str));
    } catch (Exception $e) {
        return false;
    }
    $month = $dt->format('m');
    $day = $dt->format('d');
    $year = $dt->format('Y');
    if (checkdate($month, $day, $year)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Method for cat tree dropdown
 */
function catDropDown($cats = array(), $catId = "") {
    if (count($cats) > 0) {
        foreach ($cats as $cat) {
            ?>
            <option value="<?php echo $cat->catId; ?>" <?php if ($cat->catId == $catId) { ?>selected="selected"<?php } ?> >-<?php echo $cat->catName; ?></option>
            <?php
            catDropDownSec($cat->subCats, $catId);
        }
    }
}

/**
 * Method for cat tree dropdown
 */
function catDropDownSec($cats = array(), $catId = "") {
    if (count($cats) > 0) {
        foreach ($cats as $cat) {
            ?>
            <option value="<?php echo $cat->catId; ?>" <?php if ($cat->catId == $catId) { ?>selected="selected"<?php } ?>>--<?php echo $cat->catName; ?></option>;
            <?php
            if (isset($cat->subCats)) {
                catDropDownThird($cat->subCats);
            }
        }
    }
}

function str_rand($length = 8, $seeds = 'alphanum') {
    // Possible seeds
    $seedings['alpha'] = 'abcdefghijklmnopqrstuvwqyz';
    $seedings['numeric'] = '0123456789';
    $seedings['alphanum'] = '-abcdefghijklmnopqrstuvwqyz-0123456789-';
    $seedings['hexidec'] = '0123456789abcdef';

    // Choose seed
    if (isset($seedings[$seeds])) {
        $seeds = $seedings[$seeds];
    }

    // Seed generator
    list($usec, $sec) = explode(' ', microtime());
    $seed = (float) $sec + ((float) $usec * 100000);
    mt_srand($seed);

    // Generate
    $str = '';
    $seeds_count = strlen($seeds);

    for ($i = 0; $length > $i; $i++) {
        $str .= $seeds{mt_rand(0, $seeds_count - 1)};
    }

    return strtoupper($str);
}

/**
 * Method to authorise exess
 */
function authorize() {
    $ci = & get_instance();
    $id = $ci->session->userdata("uid");
    if ($id == "") {
        $ci->session->set_flashdata("message", "<div class='warning neg'>Please login first to access internal pages.</div>");
        redirect("users/login");
    }
}

/**
 * Method to get user details by Id
 */
function get_user() {
    $ci = & get_instance();
    $id = $ci->session->userdata("uid");
    $ci->db->select("*");
    $ci->db->where("usrId", $id, true);
    $res = $ci->db->get("tbl_users");
    return $res->row();
}

/**
 * Convert number into word
 */
function convertNumberToWordsForIndia($number) {
    //A function to convert numbers into Indian readable words with Cores, Lakhs and Thousands.
    $words = array(
        '0' => '', '1' => 'one', '2' => 'two', '3' => 'three', '4' => 'four', '5' => 'five',
        '6' => 'six', '7' => 'seven', '8' => 'eight', '9' => 'nine', '10' => 'ten',
        '11' => 'eleven', '12' => 'twelve', '13' => 'thirteen', '14' => 'fouteen', '15' => 'fifteen',
        '16' => 'sixteen', '17' => 'seventeen', '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'fourty', '50' => 'fifty', '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninty');

    //First find the length of the number
    $number_length = strlen($number);
    //Initialize an empty array
    $number_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0);
    $received_number_array = array();

    //Store all received numbers into an array
    for ($i = 0; $i < $number_length; $i++) {
        $received_number_array[$i] = substr($number, $i, 1);
    }

    //Populate the empty array with the numbers received - most critical operation
    for ($i = 9 - $number_length, $j = 0; $i < 9; $i++, $j++) {
        $number_array[$i] = $received_number_array[$j];
    }
    $number_to_words_string = "";
    //Finding out whether it is teen ? and then multiplying by 10, example 17 is seventeen, so if 1 is preceeded with 7 multiply 1 by 10 and add 7 to it.
    for ($i = 0, $j = 1; $i < 9; $i++, $j++) {
        if ($i == 0 || $i == 2 || $i == 4 || $i == 7) {
            if ($number_array[$i] == "1") {
                $number_array[$j] = 10 + $number_array[$j];
                $number_array[$i] = 0;
            }
        }
    }

    $value = "";
    for ($i = 0; $i < 9; $i++) {
        if ($i == 0 || $i == 2 || $i == 4 || $i == 7) {
            $value = $number_array[$i] * 10;
        } else {
            $value = $number_array[$i];
        }
        if ($value != 0) {
            $number_to_words_string.= $words["$value"] . " ";
        }
        if ($i == 1 && $value != 0) {
            $number_to_words_string.= "Trillions ";
        }
        if ($i == 3 && $value != 0) {
            $number_to_words_string.= "Millions ";
        }
        if ($i == 5 && $value != 0) {
            $number_to_words_string.= "Thousand ";
        }
        if ($i == 6 && $value != 0) {
            $number_to_words_string.= "Hundred &amp; ";
        }
    }
    if ($number_length > 9) {
        $number_to_words_string = "Sorry This does not support more than 99 Crores";
    }
    return ucwords(strtolower($number_to_words_string));
}

// Function to get result in html option
function getHTMLOptions($list = array(), $idName = '', $valueName = '') {
    $HTMLOptions = array();
    if (!is_array($list))
        return '';
    foreach ($list as $row) {
        if (is_array($row) or is_object($row)) {
            if (empty($idName) or empty($valueName))
                list($id, $value) = array($row->geoId, $row->geoName);
            else
                list($id, $value) = array($row->geoId, $row->geoName);

            $HTMLOptions[$id] = $value;
        }
    }
    return $HTMLOptions;
}

function get_admin_email() {
    $CI = & get_instance();
    $CI->db->select('email');
    $CI->db->where('user_type','admin');
    $result = $CI->db->get('user')->row();
    return $result->email;
}

function getImageDimensions($img, $req_width, $req_height) {

    list($w, $h) = getimagesize($img);

    if ($w < $req_width && $h == $req_height) {
        $style = 'height:' . $req_height . 'px;width:' . $w . 'px';
    } else if ($w == $req_width && $h < $req_height) {
        $m = floor(($req_height - $h) / 2);
        $style = 'width:' . $req_width . 'px;height:' . $h . 'px;margin:' . $m . 'px 0';
    } else {
        $style = 'width:' . $req_width . 'px;height:' . $req_height . 'px;';
    }
    return $style;
}

function permission($id = null) {
    $CI = & get_instance();
    $CI->db->select("*");
    $CI->db->from("tbl_users");
    $CI->db->join('tbl_roles', 'tbl_roles.id = tbl_users.user_group_id', 'left');
    $CI->db->where('tbl_users.id', $id);
    $result = $CI->db->get()->row();
    if (isset($result->permission) && $result->permission == 1)
        return true;
    else
        return false;
}

/**
 * @ Function Name		: getSubMenu
 * @ Function Params		: 
 * @ Function Purpose 	: get  SubMenu menu
 * @ Function Returns	: 
 * */
function sub_children($id, $parent) {
    static $child;
    $CI = & get_instance();
    $CI->db->select('me.*,cm.cmsTitle,cm.cmsVariable,menct.*');
    $CI->db->join('tbl_cms cm', 'cm.cmsid = me.page_id', 'LEFT');
    $CI->db->join('tbl_menucustomlink menct', 'menct.menulink_id  = me.id', 'LEFT');
    $CI->db->where('me.parent_page_id', $parent);
    $CI->db->where('me.menu_id', $id);
    $result = $CI->db->get("tbl_menulinks me")->result_array();

    if (count(array_filter($result)) > 0) {
        foreach ($result as $key => $subchild) {
            $subchild = sub_children($id, $subchild['id']);
            if (!empty($subchild))
                $result[$key]['subchild'] = $subchild;
        }
    }
    return $result;
}

/**
 * @ Function Name		: getCategories
 * @ Function Params	: NULL
 * @ Function Purpose 	: get the category/sub-category list
 * @ Function Returns	: array
 */
function getmenus($id, $level = 0) {
    static $result_array;
    $CI = & get_instance();
    $CI->db->select('me.*,cm.cmsTitle,cm.cmsVariable,menct.*');
    $CI->db->join('tbl_cms cm', 'cm.cmsid = me.page_id', 'LEFT');
    $CI->db->join('tbl_menucustomlink menct', 'menct.menulink_id = me.id', 'LEFT');
    $CI->db->where('me.parent_page_id', $level);
    $CI->db->where('me.menu_id', $id);
    $CI->db->order_by('me.id', 'ASC');
    $rows = $CI->db->get('tbl_menulinks me')->result_array();

    foreach ($rows as $key => $r) {

        $result_array[] = $r;
        $subchild = sub_children($id, $r['id']);
        array_push($result_array[$key], $subchild);
    }

    return $result_array;
}

/**
 * @ Function Name		: creatSubMenu
 * @ Function Params	: NULL
 * @ Function Purpose 	: sub-category list
 * @ Function Returns	: string
 */
function creatSubMenu($menu, $i, $url) {
    $content = '';
    foreach ($menu as $key => $values) {

        $content.='<li id="list_' . $values['page_id'] . '">';
        if (isset($values['menulink_name']) && !empty($values['menulink_name'])) {
            $content.="<a href=" . $values['menulink_url'] . ">" . $values['menulink_name'] . "</a>";
        } else {
            $content.="<a href=" . $url . $values['cmsVariable'] . ">" . $values['cmsTitle'] . "</a>";
        }


        if (!empty($values['subchild'])) {
            $content.='<ul>';
            $content.=creatSubMenu($values['subchild'], $i, $url) . '</ul>';
        } else {
            $content.='</li>';
        }
    }
    return $content;
}

/**
 * @ Function Name		: creatMenu
 * @ Function Params	: NULL
 * @ Function Purpose 	: get the category
 * @ Function Returns	: array
 */
function creatMenu($name, $level = 0, $url) {
    $name = strtolower(preg_replace('/\s+/', '', $name));
    $CI = & get_instance();
    $CI->db->select('mnuId');
    $CI->db->where('mnuName', $name);
    $CI->db->where('mnuStatus', '1');
    $rows = $CI->db->get('tbl_menus')->row();

    if (!empty($rows))
        $menus = getmenus($rows->mnuId, 0);

    $menuLinks = '<ul class="mainMenu">';
    if (!empty($menus)):
        foreach ($menus as $key => $value) :
            //echo '<prE>'; print_r($value);
            $menuLinks.="<li class='mjs-nestedSortable-leaf' id='list_" . $value['page_id'] . "'>";
            if (isset($value['menulink_name']) && !empty($value['menulink_name'])) {
                $menuLinks.="<a href=" . $value['menulink_url'] . ">" . $value['menulink_name'] . "</a>";
            } else {
                $menuLinks.="<a href=" . $url . $value['cmsVariable'] . ">" . $value['cmsTitle'] . "</a>";
            }
            if (!empty($value[0])) :
                $menuLinks.='<ul>' . creatSubMenu($value[0], 1, $url) . '</ul>';
            endif;
            $menuLinks.='</li>';
        endforeach;
    endif;
    $menuLinks.='</ul>';
    return $menuLinks;
}

/**
 * @ Function Name		: getSubMenu
 * @ Function Params		: 
 * @ Function Purpose 	: get  SubMenu menu
 * @ Function Returns	: 
 * */
function submenu($menu, $i) {
    $content = '';
    $uniqid = uniqid();
    foreach ($menu as $key => $values) {

        if (isset($values['menulink_url']) && !empty($values['menulink_url'])) {
            $content.='<li class="mjs-nestedSortable-leaf ' . $uniqid . '" id="list_' . $values['page_id'] . ',' . $values['menulink_name'] . ',' . $values['menulink_url'] . '">';
        } else {
            $content.='<li class="mjs-nestedSortable-leaf ' . $uniqid . '" id="list_' . $values['page_id'] . '">';
        }
        if (isset($values['cmsTitle']) && !empty($values['cmsTitle'])) {
            $content.='<div><span class="disclose"><span></span></span>' . $values['cmsTitle'];
        } else {
            $content.='<div><span class="disclose"><span></span></span>' . $values['menulink_name'];
        }
        $content.="<span class='floatRight' onclick=deleteLi('" . $uniqid . "')>Delete</span>";
        if (isset($values['cmsTitle']) && !empty($values['cmsTitle'])) {
            $content.='<span class="customChanges"><a>Page</a></span></div>';
        } else {
            $content.='<span class="customChanges"><a>Custom Url</a></span></div>';
        }


        if (!empty($values['subchild'])) {
            $content.='<ol>';
            $content.=submenu($values['subchild'], $i) . '</ol>';
        } else {
            $content.='</li>';
        }
    }
    return $content;
}

/**    
 * Function to match multiple values in an array
 * @Params: $needles{:array to match}, $arr{:array main}
 * @return: true/false 
 **/
function in_array_all($needles, $arr) {
   return !array_diff($needles, $arr);
}

/**    
 * Function to unserialize value in an array
 * @Params: $arr{:array}
 * @return: {:array} 
 **/
function unserialize_images($arr){
    if(!empty($arr->images))
    {    
        $arr->images = unserialize($arr->images);
    }
    
    return $arr;
}

/**    
 * Function to generate random string
 * Generates a strong password of N length containing at least one lower case letter, one uppercase letter, one digit, and one special character.
 * @Params: 
 * @return: string
 **/
function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
{
    $sets = array();
    if(strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
    if(strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    if(strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
    if(strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';
    $all = '';
    $password = '';
    foreach($sets as $set)
    {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
    }
    $all = str_split($all);
    for($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];
    $password = str_shuffle($password);
    if(!$add_dashes)
            return $password;
    $dash_len = floor(sqrt($length));
    $dash_str = '';
    while(strlen($password) > $dash_len)
    {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
    }
    $dash_str .= $password;
    return $dash_str;
}
