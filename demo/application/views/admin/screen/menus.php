<?php
$data['scripts'][] = 'admin/cmsmenus.js'; 
$data['validate']	='menus';
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
        <a href="<?php echo site_url('admin/menus/addmenu'); ?>" class="btn">Add New Menu</a>
    </div>
	 
    <div class="clear"></div>
</div>
<div id="pageContent">
    <?php
    $attributes = array('id' => 'menus', 'name' => 'menus');
    echo form_open('admin/menus', $attributes);
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
                <select id="cmbActions" style="width:auto;" name="menu">
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
                    
                    <col width="150">
                    <col width="125">
                    <col width="80">	 
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='mnuName_<?php echo ((isset($sortBy) && $sortBy == "mnuName") ? ($orderBy) : 'asc'); ?>' id="mnuName" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "mnuName") ? "checked='checked'" : ""); ?> />
                            <label for="mnuName" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "mnuName") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Name</a>
                            </label>
                        </th>
                                                
                        <th scope="col">
                            <input type="radio" name="sortBy" value='mnuDateCreated_<?php echo ((isset($sortBy) && $sortBy == "mnuDateCreated") ? ($orderBy) : 'asc'); ?>' id="mnuDateCreated" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "mnuDateCreated") ? "checked='checked'" : ""); ?>  />
                            <label for="mnuDateCreated" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "mnuDateCreated") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Date Created</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='mnuStatus_<?php echo ((isset($sortBy) && $sortBy == "mnuStatus") ? ($orderBy) : 'asc'); ?>' id="mnuStatus" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "mnuStatus") ? "checked='checked'" : ""); ?> />
                            <label for="mnuStatus" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "mnuStatus") ? ("sort" . ucfirst($orderBy)) : ''); ?>"  >Status</a>
                            </label>
                        </th>
                        <th scope="col">Actions</th>
                    </tr>
                    <tr class="filter">
                        <td>&nbsp;</td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtName']) ? $postData['txtName'] : ''; ?>" class="" name="txtName" maxlength="100" />
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
					
                    $total_menus = count($pageinfo);
                    if ($total_menus > 0) {
                        $i = 0;
                        foreach ($pageinfo as $val) {
                            if ($i % 2 == 1) {
                                $class = "alternateRow";
                            } else {
                                $class = "";
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <td ><input type="checkbox" value="<?php echo $val->mnuId; ?>" class="checkbox selectRow" name="chkBox[]"  id="check"/></td>  
								 <td style="vertical-align: middle;"><?php echo ucfirst($val->mnuName); ?></td> 
                               
                                <td style="vertical-align: middle;"><?php if(isset($val->mnuDateCreated)){echo date(ADMIN_DATE_FORMAT, strtotime($val->mnuDateCreated));} ?></td> 
                                <td style="vertical-align: middle;"><?php
                        $status = $val->mnuStatus;
                        if ($status == 1) {
                            echo 'Active';
                        } else if ($status == 2){
                            echo 'Deactivated';
                        } else {
                            echo 'Inactive';
                        }
                                ?></td>
                               <td style="vertical-align: middle;">
                                    <select class="selActions" id="<?php echo $val->mnuId; ?>" name="menu">
                                        <option value="">Actions</option>   
                                        <option value="editmenu">Edit</option>
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