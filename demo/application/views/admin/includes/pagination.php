<?php // Pagination Variables
	$pagingOption['nextPageNumber'] = $pagingOption['currentPageNumber']+1;
	$pagingOption['prevPageNumber'] = $pagingOption['currentPageNumber']-1;
?>
<dl class="paginations">
    <dt>&nbsp;</dt>
    <dd><strong>Page</strong> <?php if ($pagingOption['prevPageURL'] == "") { ?> <a href="javascript:void(0);" class="prevPage firstPage"></a> <?php } else { ?> <a for="<?php echo $pagingOption['prevPageNumber']; ?>" class="prevPage" id="prevPage"></a> <?php } ?>
        <select name="pgn" id="pagingList" class="paging" onchange="submitForm()" >
            <?php for ($i = 1; $i <= $pagingOption['totalPages']; $i++) { ?>
                <option value="<?php echo $i ?>" <?php if ($i == $pagingOption['currentPageNumber']) { ?> selected="selected"<?php } ?>><?php echo $i ?></option><?php echo $i; ?></option>
            <?php } ?>          
        </select>
        <?php if ($pagingOption['nextPageURL'] == "") { ?> <a href="javascript:void(0);" class="nextPage lastPage"></a><?php } else { ?>  <a for="<?php echo $pagingOption['nextPageNumber']; ?>" class="nextPage" id="nextPage" ></a> <?php } ?>
        of <?php echo $pagingOption['totalPages']; ?> page(s) | <strong>Showing</strong>
        <select name="ipp" id="pagingItemsPerPage" class="paging"  >
            <option value="10" <?php if ($pagingOption['itemsPerPage'] == 10) { ?>selected="selected"<?php } ?>>10</option>
            <option value="20" <?php if ($pagingOption['itemsPerPage'] == 20) { ?>selected="selected"<?php } ?>>20</option>
            <option value="30" <?php if ($pagingOption['itemsPerPage'] == 30) { ?>selected="selected"<?php } ?>>30</option>
            <option value="40" <?php if ($pagingOption['itemsPerPage'] == 40) { ?>selected="selected"<?php } ?>>40</option>
            <option value="50" <?php if ($pagingOption['itemsPerPage'] == 50) { ?>selected="selected"<?php } ?>>50</option>
            <option value="100" <?php if ($pagingOption['itemsPerPage'] == 100) { ?>selected="selected"<?php } ?>>100</option>
        </select>
        Records Per Page. 
    </dd>
</dl>