<?php
$data['scripts'][] = 'admin/cmspages.js'; 
$data['validate']	='screen';
$this->load->view('admin/includes/header.php', $data); ?>  
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ""); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
    <div class="column2">
        <a href="<?php echo site_url('admin/screen/addScreen'); ?>" class="btn">Add New Screen</a>
    </div>
	 
    <div class="clear"></div>
</div>
<div id="pageContent">
    <?php
    $attributes = array('id' => 'screen', 'name' => 'screen');
    echo form_open('admin/screen', $attributes);
    ?>
    <input type="hidden" value="filter" name="filterOrSort" id="filterOrSort" />
    <?php $value = $this->session->flashdata('item');
    if (!empty($value)) { ?>
        <?php echo $value; ?>
    <?php }else{
        echo '<div class="warning hidden"></div>';
    } ?>
    <div class="stdListingWrapper">
        <dl id="actionBar">
            <dt>&nbsp;</dt>
            <dd> Select: <a href="javascript:void(0)" id="selectAll">All</a> l <a href="javascript:void(0)" id="selectNone">None</a> &nbsp;&nbsp;<strong>Actions</strong>
                <select id="cmbActions" style="width:auto;" name="screen">
                    <option value="">Select</option>
                    <option value="active">Activate</option>
                    <option value="inactive">Inactivate</option>
                    <option value="delete">Delete</option>
                </select> 
            </dd>
        </dl>
        <div class="stdListing">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <colgroup>
                    <col width="25">
                    <col width="250">
                    <col width="250">
                    <col width="300">
                    <col width="300">
                    <col width="150">
                    <col width="125">
                    <col width="80">	 
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='cmsTitleEng_<?php echo ((isset($sortBy) && $sortBy == "cmsTitleEng") ? ($orderBy) : 'asc'); ?>' id="cmsTitleEng" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "cmsTitleEng") ? "checked='checked'" : ""); ?> />
                            <label for="cmsTitleEng" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "cmsTitleEng") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Title (In English)</a>
                            </label>
                        </th>
                        
                        <th scope="col">
                            <input type="radio" name="sortBy" value='cmsTitleDutch_<?php echo ((isset($sortBy) && $sortBy == "cmsTitleDutch") ? ($orderBy) : 'asc'); ?>' id="cmsTitleDutch" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "cmsTitleDutch") ? "checked='checked'" : ""); ?> />
                            <label for="cmsTitleDutch" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "cmsTitleDutch") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Title (In German)</a>
                            </label>
                        </th>
                        
                        <th scope="col">
                            <input type="radio" name="sortBy" value='cmsContentEng_<?php echo ((isset($sortBy) && $sortBy == "cmsContentEng_") ? ($orderBy) : 'asc'); ?>' id="cmsContentEng_" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "cmsContentEng_") ? "checked='checked'" : ""); ?> />
                            <label for="cmsContentEng_" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "cmsContentEng_") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Description (In English)</a>
                            </label>
                        </th>
                        
                        <th scope="col">
                            <input type="radio" name="sortBy" value='cmsContentDutch_<?php echo ((isset($sortBy) && $sortBy == "cmsContentDutch_") ? ($orderBy) : 'asc'); ?>' id="cmsContentDutch_" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "cmsContentDutch_") ? "checked='checked'" : ""); ?> />
                            <label for="cmsContentDutch_" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "cmsContentDutch_") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Description (In German)</a>
                            </label>
                        </th>
                         
                        <th scope="col">
                            <input type="radio" name="sortBy" value='cmsDateCreated_<?php echo ((isset($sortBy) && $sortBy == "cmsDateCreated") ? ($orderBy) : 'asc'); ?>' id="cmsDateCreated" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "cmsDateCreated") ? "checked='checked'" : ""); ?>  />
                            <label for="cmsDateCreated" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "cmsDateCreated") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Date Created</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='cmsStatus_<?php echo ((isset($sortBy) && $sortBy == "cmsStatus") ? ($orderBy) : 'asc'); ?>' id="cmsStatus" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "cmsStatus") ? "checked='checked'" : ""); ?> />
                            <label for="cmsStatus" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "cmsStatus") ? ("sort" . ucfirst($orderBy)) : ''); ?>"  >Status</a>
                            </label>
                        </th>
                        <th scope="col">Actions</th>
                    </tr>
                    <tr class="filter">
                        <td>&nbsp;</td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtTtileEng']) ? $postData['txtTtileEng'] : ''; ?>" class="" name="txtTtileEng" maxlength="100" />
                        </td>
                        
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtTtileDutch']) ? $postData['txtTtileDutch'] : ''; ?>" class="" name="txtTtileDutch" maxlength="100" />
                        </td>
                        
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtdesEng']) ? $postData['txtdesEng'] : ''; ?>" class="" name="txtdesEng" maxlength="255" />
                        </td>
                        
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtdesDutch']) ? $postData['txtdesDutch'] : ''; ?>" class="" name="txtdesDutch" maxlength="255" />
                        </td>
 
                        <?php
                        if (isset($postData['txtDateFrom']) && !empty($postData['txtDateFrom'])) {
                            $from = $postData['txtDateFrom'];
                        } else {
                            $from = 'From';
                        }
                        if (isset($postData['txtDateTo']) && !empty($postData['txtDateTo'])) {
                            $to = $postData['txtDateTo'];
                        } else {
                            $to = 'To';
                        }
                        ?>
                        <td>
                            <input type="text" name="txtDateFrom" readonly id="dp1310711996720" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $from; ?>" onblur="if(this.value==''){this.value='From';}" autocomplete="off"/>&nbsp;&nbsp;
                            <input type="text" name="txtDateTo" readonly id="dp1310711996721" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $to; ?>" onblur="if(this.value==''){this.value='To';}" autocomplete="off"/>
                        </td>
                        <td>
                            <select class="" name="cmbStatus">
                                <option value="">Select Status</option>
                                <option value="1" <?php echo (($postData['cmbStatus'] === "1") ? "selected='selected'" : ''); ?>>
                                    Active
                                </option>
                                <option value="0" <?php echo (($postData['cmbStatus'] === "0") ? "selected='selected'" : ''); ?> >
                                    Inactive 
                                </option>
								<!--<option value="2" <?php echo (($postData['cmbStatus'] === "2") ? "selected='selected'" : ''); ?> >
                                    Deactivated 
                                </option>-->
                            </select>
                        </td>
                        <td>
                            <input type="submit" title="Tip: Fill up one or more fields on the left to filter the record list below." value="Apply Filter" class="btn" id="btnFilter" name="btnFilter" />
                        </td>
                    </tr>
                    <?php
                    $total_pages = count($pageinfo);
                    if ($total_pages > 0) {
                        $i = 0;
                        foreach ($pageinfo as $val) {
                            if ($i % 2 == 1) {
                                $class = "alternateRow";
                            } else {
                                $class = "";
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <td ><input type="checkbox" value="<?php echo $val->cmsid; ?>" class="checkbox selectRow" name="chkBox[]"  id="check"/></td>  
								 <td style="vertical-align: middle;"><?php echo $val->cmsTitleEng; ?></td> 
								 <td style="vertical-align: middle;"><?php echo $val->cmsTitleDutch; ?></td> 
                                <td style="vertical-align: middle;"><?php echo substr(strip_tags($val->cmsContentEng),0,200) ?></td> 
                                <td style="vertical-align: middle;"><?php echo substr(strip_tags($val->cmsContentDutch),0,200) ?></td> 
                                <td style="vertical-align: middle;"><?php if(isset($val->cmsDateCreated)){echo date(ADMIN_DATE_FORMAT, strtotime($val->cmsDateCreated));} ?></td> 
                                <td style="vertical-align: middle;"><?php
									$status = $val->cmsStatus;
									if ($status == 1) {
										echo 'Active';
									} else if ($status == 2){
										echo 'Deactivated';
									} else {
										echo 'Inactive';
									}
                                ?></td>
                               <td style="vertical-align: middle;">
                                    <select class="selActions" id="<?php echo $val->cmsid; ?>" name="screen">
                                        <option value="">Actions</option>   
                                        <option value="editScreen">Edit</option>
                                        <option value="active">Activate</option>
                                        <option value="inactive">Inactivate</option>
                                        <option value="delete">Delete</option>
                                        <!--<option value="wishlist">Wishlist</option>-->
                                    </select>
                                </td>
                            </tr>
                            <?php
                            $i++;
                        } //foreach closes 
                    } else { //if closes 
                        ?>
                    <script type="text/javascript">
                        setWarningMessage('neg','No result found to display.');
                    </script>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php $this->activepagination->getAdminPagination(); ?>
    </div>
    <?php echo form_close(); ?>
</div>
<?php $this->load->view('admin/includes/footer.php'); ?>
