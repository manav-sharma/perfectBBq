<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Active_pagination
 * 
 * @package yezzcontrol
 * @author Admin
 * @copyright 2011
 * @version $Id$
 * @access public
 */
class ActivePagination {
    /* These are defaults */

    var $TotalResults;
    var $CurrentPage = 1;
    var $TotalPages = 1;
    var $PageVarName = "pgn";
	var $Range;
    var $ResultsPerPage;
	var $StartRange;
	var $MidRange;
	var $EndRange;
    var $CI;
    public $pages;
    public $navElements;
    public $data=array();
    /**
     * Active_pagination::__construct()
     * 
     * @return
     */
    function __construct() {
        $this->CI = &get_instance();
		$this->MidRange = 7;
    }

    /**
     * Active_pagination::setPaginationVariable()
     * 
     * @param mixed $config
     * @return
     */
    function setPaginationVariable($config) {
        $this->ResultsPerPage = $config['ipp'];
        $this->TotalResults = $config['totalRows'];
        $this->CurrentPage = $config['pgn'];
    }

    /*
      function PagedResults($pageno,$sqlQuery)
      {
      $this->ResultsPerPage = $pageno;
      $resultSet = query($sqlQuery);
      $this->TotalResults = numRows($resultSet);
      $this->CurrentPage = $this->getCurrentPage();
      $this->sqlQuery = $sqlQuery;
      }

      function getSQLQuery()
      {
      if(isset($_GET[$this->PageVarName]) and $_GET[$this->PageVarName] == 'all') {
      return $this->sqlQuery;
      }else{
      return $this->sqlQuery ." LIMIT ".$this->getStartOffset().", ".$this->ResultsPerPage;
      }
      }
     */

    /* Start information functions */

    /**
     * Active_pagination::getTotalPages()
     * 
     * @return
     */
    function getTotalPages() {
        /* Make sure we don't devide by zero */
        if ($this->TotalResults != 0 && $this->ResultsPerPage != 0) {
            return $result = ceil($this->TotalResults / $this->ResultsPerPage);
        } else {
            return 1;
        }
    }

    /**
     * Active_pagination::getTotalPages()
     * 
     * @return
     */
    function getTotalItems() {
		return $this->TotalResults;
    }
	
    /**
     * Active_pagination::getStartOffset()
     * 
     * @return
     */
    function getStartOffset() {
        if ($this->CurrentPage == 0 || $this->CurrentPage < 0)
            $this->CurrentPage = 1;

        $offset = $this->ResultsPerPage * ($this->CurrentPage - 1);
        if ($offset != 0) {
            $offset;
        }
        return $offset;
    }

    /**
     * Active_pagination::getEndOffset()
     * 
     * @return
     */
    function getEndOffset() {
        if ($this->getStartOffset() > ($this->TotalResults - $this->ResultsPerPage)) {
            $offset = $this->TotalResults;
        } elseif ($this->getStartOffset() != 0) {
            $offset = $this->getStartOffset() + $this->ResultsPerPage - 1;
        } else {
            $offset = $this->ResultsPerPage;
        }
        return $offset;
    }

    /**
     * Active_pagination::getCurrentPage()
     * 
     * @return
     */
    function getCurrentPage() {
        if (isset($_GET[$this->PageVarName]) and $_GET[$this->PageVarName] == 'all') {
            return 1;
        } elseif (isset($_GET[$this->PageVarName])) {
            if ($_GET[$this->PageVarName] > $this->getTotalPages())
                return $this->getTotalPages();
            else
                return $_GET[$this->PageVarName];
        } else {
            return $this->CurrentPage;
        }
    }

    /**
     * Active_pagination::getPrevPage()
     * 
     * @return
     */
    function getPrevPage() {
        if ($this->CurrentPage > 1) {
            return $this->CurrentPage - 1;
        } else {
            return false;
        }
    }

    /**
     * Active_pagination::getNextPage()
     * 
     * @return
     */
    function getNextPage() {
        if ($this->CurrentPage < $this->getTotalPages()) {
            return $this->CurrentPage + 1;
        } else {
            return false;
        }
    }

    /**
     * Active_pagination::getStartNumber()
     * 
     * @return
     */
    function getStartNumber() {
        $links_per_page_half = $this->LinksPerPage / 2;
        /* See if curpage is less than half links per page */
        if ($this->CurrentPage <= $links_per_page_half || $this->TotalPages <= $this->LinksPerPage) {
            return 1;
            /* See if curpage is greater than TotalPages minus Half links per page */
        } elseif ($this->CurrentPage >= ($this->TotalPages - $links_per_page_half)) {
            return $this->TotalPages - $this->LinksPerPage + 1;
        } else {
            return $this->CurrentPage - $links_per_page_half;
        }
    }

    /**
     * Active_pagination::getEndNumber()
     * 
     * @return
     */
    function getEndNumber() {
        if ($this->TotalPages < $this->LinksPerPage) {
            return $this->TotalPages;
        } else {
            return $this->getStartNumber() + $this->LinksPerPage - 1;
        }
    }

    /**
     * Active_pagination::getNumbers()
     * 
     * @return
     */
    function getNumbers() {
        for ($i = $this->getStartNumber(); $i <= $this->getEndNumber(); $i++) {
            $numbers[] = $i;
        }
        return $numbers;
    }

    /**
     * Active_pagination::getPagingOption()
     * 
     * @param string $pt
     * @param string $su
     * @return
     */
    function getPagingOption($pt='', $su='') {
         $hostURL = current_url();
        $hostURL = explode("/page",$hostURL );
        $hostURL = isset($hostURL['0'])?$hostURL['0']:'';
        $pagingOptionVar = array(
            "prevPageURL" => ($this->getPrevPage()) ? $hostURL . '/page/' . $this->getPrevPage(). $this->getquerystring('pgn') : "",
            "nextPageURL" => ($this->getNextPage()) ? $hostURL . '/' . 'page' . '/' . $this->getNextPage() . $this->getquerystring('pgn')  : "",
            "curURL" => $hostURL . '/' . $this->getsubquerystring('pgn') . 'page' . '/',
            "viewAllURL" => $hostURL . '/' . $this->getsubquerystring('pgn') . 'page' . '/all',
            "currentPageNumber" => $this->getCurrentPage(),
            "totalPages" => $this->getTotalPages(),
            "itemsPerPage" => $this->ResultsPerPage,
            "hostURL" => $hostURL
        );
        if ($pt == 'pages')
            $pagingOptionVar['pagesNumber'] = $this->getPages();
        if ($su == 'no') {
            $pagingOptionVar["prevPageURL"] = $this->getPrevPage();
            $pagingOptionVar["nextPageURL"] = $this->getNextPage();
        }
        return $pagingOptionVar;
    }
    
    /**
     * Active_pagination::getPages()
     * 
     * @return
     */
    function getPages() {
        $total_pages = $this->TotalResults;
        $limit = $this->ResultsPerPage;
        $adjacents = "2";
        $hostURL = $current_url();
        $page = $this->getCurrentPage();

        $page = (int) (empty($page) ? 1 : $page);
        $page = ($page == 0 ? 1 : $page);

        if ($page)
            $start = ($page - 1) * $limit;
        else
            $start = 0;

        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($total_pages / $limit);
        $lpm1 = $lastpage - 1;

        $pagination = array();

        if ($lastpage > 1) {
            if ($lastpage < 7 + ($adjacents * 2)) {
                $tempArray = array();
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    array_push($tempArray, $counter);
                }
                $pagination = $tempArray;
            } elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($page < 1 + ($adjacents * 2)) {
                    $tempArray = array();
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        array_push($tempArray, $counter);
                    }
                    array_push($tempArray, "...");
                    array_push($tempArray, $lpm1);
                    array_push($tempArray, $lastpage);
                    $pagination = $tempArray;
                } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $tempArray = array();

                    array_push($tempArray, 1);
                    array_push($tempArray, 2);
                    array_push($tempArray, "...");

                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        array_push($tempArray, $counter);
                    }
                    array_push($tempArray, "...");
                    array_push($tempArray, $lpm1);
                    array_push($tempArray, $lastpage);
                    $pagination = $tempArray;
                } else {
                    $tempArray = array();

                    array_push($tempArray, 1);
                    array_push($tempArray, 2);
                    array_push($tempArray, "...");

                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        array_push($tempArray, $counter);
                    }

                    $pagination = $tempArray;
                }
            }
        }
        return $pagination;
    }

    /**
     * Active_pagination::getsubquerystring()
     * 
     * @param mixed $param
     * @return
     */
    function getsubquerystring($param) {
        $subquerysting = array();
        $querystring = explode('&', $_SERVER['QUERY_STRING']);
        foreach ($querystring as $val) {
            $tempvar = explode('=', $val);
            if ($tempvar[0] == $param)
                continue;
            else
                array_push($subquerysting, $val);
        }
        return implode('&amp;', $subquerysting);
    }
    
    /**
     * Active_pagination::getsubquerystring()
     * 
     * @param mixed $param
     * @return
     */
    function getquerystring($param) {
        $subquerysting = array();
        $querystring = explode('&', $_SERVER['QUERY_STRING']);
        foreach ($querystring as $val) {
			$tempvar = explode('=', $val);
        	if ($tempvar[0] == $param)
        		continue;
            else
				array_push($subquerysting, $val);
        }
        $q = implode('&amp;', $subquerysting);
        if(!empty($q)) $q = '?'.$q; 
        return $q;
    }

    /**
     * Active_pagination::getAdminPagination()
     * 
     * @return
     */
    function getAdminPagination() {
        $data['pagingOption'] = $this->getPagingOption();
        $this->CI->load->view('admin/includes/pagination', $data);
    }
    /**
     * method to make pohtos's pagination
     */
    function getPhotoPagination()
    {
        
        $data['pagingOption'] = $this->getPagingOption();
        $this->CI->load->view('frontend/includes/pagination', $data);
    }

    /**
     * Active_pagination::getFrontendPagination()
     * 
     * @return
     */
    function getFrontendPagination() {
        $data['pagingOption'] = $this->getPagingOption();
        $this->CI->load->view('frontend/includes/pagination', $data);
    }
    
    /**
     * Active_pagination::getFrontendPagination()
     * 
     * @return
     */
    function getFrontendSearchPagination() {
        $data['pagingOption'] = $this->getSearchPagingOption();
        $this->CI->load->view('frontend/includes/pagination', $data);
    }
    
	/**
     * Active_pagination::getFrontendPagination()
     * 
     * @return
     */
    function getFrontendProductPagination($getArray=array()) {
        $data['pagingOption'] = $this->getPagingOption();
        $data['getArray'] = $getArray;
		$this->CI->load->view('frontend/includes/pagination', $data);
    }
    
    /**
     * Method to get ajax pagination
     */
     function getFrontendAjaxPagination($postArray = "") {
        $data['pagingOption'] = $this->getPagingOption();
        $data['postArray'] = $postArray;
        $this->CI->load->view('frontend/includes/pagesajax', $data);
    }

    /**
     * Active_pagination::getMobilePagination()
     * 
     * @return
     */
    function getMobilePagination() {
        $data['pagingOption'] = $this->getPagingOption();
        $this->CI->load->view('mobileview/include/pagination', $data);
    }
    /**
     * Active_pagination::getPagingOption()--Replica For CLean URL like xyz/page/1
     * 
     * @param string $pt
     * @param string $su
     * @return
     */
    function getPagingOptionClean($pt='', $su='') {
        $siteUrl = site_url();
        $crtUrl = current_url();
        $currentController = explode($siteUrl, $crtUrl);

        $withPageUrl = explode("/page",$currentController['1']);
        $hostURL = $siteUrl."".$withPageUrl['0'];

        $pagingOptionVar = array(
            "prevPageURL" => ($this->getPrevPage()) ? $hostURL . '/page/'.$this->getPrevPage() : "",
            "nextPageURL" => ($this->getNextPage()) ? $hostURL . '/page/'. $this->getNextPage() : "",
            "curURL" => $hostURL . '?' . $this->getsubquerystring('pgn') . '&amp;pgn' . '=',
            "viewAllURL" => $hostURL . '?' . $this->getsubquerystring('pgn') . '&amp;pgn' . '=all',
            "currentPageNumber" => $this->getCurrentPage(),
            "totalPages" => $this->getTotalPages(),
            "itemsPerPage" => $this->ResultsPerPage,
            "hostURL" => $hostURL
        );

        if ($pt == 'pages')
            $pagingOptionVar['pagesNumber'] = $this->getPages();
        if ($su == 'no') {
            $pagingOptionVar["prevPageURL"] = $this->getPrevPage();
            $pagingOptionVar["nextPageURL"] = $this->getNextPage();
		}
        return $pagingOptionVar;
    }
    function paginate($array,$elements,$view){

                $this->data=$array;
                $this->navElements=$elements;
                $totalElements=count($this->data);
                $this->pages=intval($totalElements/$this->navElements);
                $pagingOption = $this->getPagingOptionClean();
                $pagenumber=$pagingOption['currentPageNumber'];
				$end=$pagenumber*$this->navElements;
				if($pagenumber<=1){
					for($i=0;$i<$end;$i++){
                        $data['feed'] = isset($this->data[$i])?(object)$this->data[$i]:'';
                         $this->CI->load->view($view,$data);
                        $feed = isset($this->data[$i])?(object)$this->data[$i]:'';?>
					<?php }
				}
				else{
					$start=$end-$this->navElements;
					for($i=$start;$i<$end;$i++){
                      $data['feed'] = isset($this->data[$i])?(object)$this->data[$i]:'';
                         $this->CI->load->view($view,$data);?>
					<?php }
				}

		}
        /**
         *
         * @param type $array
         * @param type $elements 
         */
        function paginateDdArray($array, $elements, $view) {
        $this->data = $array;
        $this->navElements = $elements;
        $totalElements = count($this->data);
        $this->pages = intval($totalElements / $this->navElements);
        $pagingOption = $this->getPagingOptionClean();
        $pagenumber = $pagingOption['currentPageNumber'];
        $end = $pagenumber * $this->navElements;
        pr($this->data,true);
        if ($pagenumber <= 1) {
            $keys = array_keys($array);
            for ($i = 0; $i < $end; $i++) {
                foreach ($keys as $key) {
                    $data['feed'] = isset($this->data[$key][$i]) ? (object) $this->data[$key][$i] : '';
                    $this->CI->load->view($view, $data);
                    $feed = isset($this->data[$key][$i]) ? (object) $this->data[$key][$i] : '';
                }
            }
        } else {
            $start = $end - $this->navElements;
            $keys = array_keys($array);
            for ($i = $start; $i < $end; $i++) {
                foreach ($keys as $key) {
                    $data['feed'] = isset($this->data[$key][$i]) ? (object) $this->data[$key][$i] : '';
                    $this->CI->load->view($view, $data);
                }
            }
        }
    }
    /**
     * Method for array Pagination Navigation
     */
    function navigation($array, $elements) {
        $this->data = $array;
        $this->navElements = $elements;
        $totalElements = count($this->data);
        $data['pagingOption'] = $this->getPagingOptionClean();
        $this->CI->load->view('frontend/includes/feedspagination', $data);
    }

}

/* End of file active_pagination.php */
/* Location: ./application/libraries/active_pagination.php */
