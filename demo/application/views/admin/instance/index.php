<?php
$data['scripts'][] = 'admin/home.js';
$data['validate'] = 'listingForm';
$this->load->view('admin/includes/header.php', $data);
?>  
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
        <!--<a href="<?php echo site_url('admin/instance/add'); ?>" class="btn">Create New Instance</a>-->
    </div>

    <div class="clear"></div>
</div>
<div id="pageContent">
    <?php
    $attributes = array('id' => 'listingForm', 'name' => 'instance');
    echo form_open('admin/instance/lists', $attributes);
    ?>
    <input type="hidden" value="filter" name="filterOrSort" id="filterOrSort" />
    <?php 
    $value = $this->session->flashdata('item');
    if (!empty($value)) {
       echo $value; 
    } else {
        echo '<div class="warning hidden"></div>';
    }
    ?>
    <div class="stdListingWrapper">
        <dl id="actionBar">
            <dt>&nbsp;</dt>
            <dd> Select: <a href="javascript:void(0)" id="selectAll">All</a> l <a href="javascript:void(0)" id="selectNone">None</a> &nbsp;&nbsp;<strong>Actions</strong>
                <select id="cmbActions" style="width:auto;" name="instance">
                    <option value="">Select</option>
                    <!--<option value="active">Activate</option>
                    <option value="inactive">Inactivate</option>
                    <option value="delete">Delete</option>-->
                </select>
                <?php if (count($userListing)) { ?>
                    <input type="radio" name="exportCSV" value='exportCSV' id="exportCSV" class="hidden" />
                    <label for="exportCSV" style="display:inline;" class="radioCSVExport" >
                        <a  title="Export to CSV" class="actionIcn toolTip <?php echo ((isset($sortBy) && $sortBy == "instance_name") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                            <img src="<?php echo base_url(); ?>common/media/images/icons/icn.export.gif" alt="" width="18" height="18" />
                        </a>
                    </label>
                <?php } ?>

            </dd>
        </dl>
        <div class="stdListing">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <colgroup>
                    <col width="25">
                    <col width="200">
                    <col width="150">
                    <col width="150">
                    <col width="150">	  
                    <col width="90">
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='instance_name_<?php echo ((isset($sortBy) && $sortBy == "instance_name") ? ($orderBy) : 'asc'); ?>' 
                                id="instance_name" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "instance_name") ? "checked='checked'" : ""); ?> />
                            <label for="instance_name" class="radioSubmitClass" >
                                <a class="sorting 
                                    <?php echo ((!empty($sortBy) && $sortBy == "instance_name") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Instance Name
                                </a>
                            </label>
                        </th>
                        
                        
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='instance_price_<?php echo ((isset($sortBy) && $sortBy == "instance_price") ? ($orderBy) : 'asc'); ?>' 
                                id="instance_price" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "instance_price") ? "checked='checked'" : ""); ?> />
                            <label for="instance_price" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((!empty($sortBy) && $sortBy == "instance_price") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Price ($)
                                </a>
                            </label>
                        </th>
                        
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='date_added_<?php echo ((!empty($sortBy) && $sortBy == "date_added") ? ($orderBy) : 'asc'); ?>' 
                                id="date_added" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "date_added") ? "checked='checked'" : ""); ?>  />
                            <label for="date_added" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((!empty($sortBy) && $sortBy == "date_added") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Date Created
                                </a>
                            </label>
                        </th>
                        
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='status_<?php echo ((isset($sortBy) && $sortBy == "status") ? ($orderBy) : 'asc'); ?>'
                                id="status" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "status") ? "checked='checked'" : ""); ?> />
                            <label for="status" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "status") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Status
                                </a>
                            </label>
                        </th>
                        
                        <th scope="col">Actions</th>
                    </tr>
                    <tr class="filter">
                        <td>&nbsp;</td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtInstanceName']) ? $postData['txtInstanceName'] : ''; ?>" 
                                class="" name="txtInstanceName" maxlength="100" />
                        </td>                        
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtPrice']) ? $postData['txtPrice'] : ''; ?>" 
                                class="" name="txtPrice" maxlength="255" />
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
                            <input type="text" name="txtDateFrom" readonly id="dp1310711996720" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $from; ?>" onblur="if (this.value == '') {
                                        this.value = 'From';
                                    }" autocomplete="off"/>&nbsp;&nbsp;
                            <input type="text" name="txtDateTo" readonly id="dp1310711996721" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $to; ?>" onblur="if (this.value == '') {
                                        this.value = 'To';}" autocomplete="off"/>
                        </td>
                        
                        <td>
                            <select class="" name="cmbStatus">
                                <option value="">Select Status</option>
                                <option value="1" <?php echo ((@$postData['cmbStatus'] === "1") ? "selected='selected'" : ''); ?>>
                                    Active
                                </option>
                                <option value="0" <?php echo ((@$postData['cmbStatus'] === "0") ? "selected='selected'" : ''); ?> >
                                    Inactive 
                                </option>
                            </select>
                        </td>
                        <td>
                            <input type="submit" title="Tip: Fill up one or more fields on the left to filter the record list below." value="Apply Filter" class="btn" id="btnFilter" name="btnFilter" />
                        </td>
                    </tr>
                    <?php
                    //pr($userListing);
                    $total_users = count($userListing);
                    if ($total_users > 0) 
                    {
                        $i = 0;
                        foreach ($userListing as $val)
                        {
                            if ($i % 2 == 1) {
                                $class = "alternateRow";
                            } else {
                                $class = "";
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <td >
                                    <input type="checkbox" value="<?php echo $val->instance_id; ?>" class="checkbox selectRow" name="chkBox[]"  id="check"/>
                                </td>
                                <?php
                                $instanceName = $val->instance_name;
                                
                                $instanceName = implode(' ',explode('_', $instanceName) );
                                
                                ?>
                                <td title="<?php echo ucwords(isset($instanceName) ? $instanceName : "&nbsp;"); ?>" style="vertical-align: middle;">
                                    <?php echo ucwords(isset($instanceName) ? $instanceName : "&nbsp;"); ?>
                                </td>                                
                                
                                <td style="vertical-align: middle;">
                                    <?php echo number_format(round($val->instance_price, 2),2);?>
                                </td>
                                
                                <td style="vertical-align: middle;">
                                    <?php 
                                    if (isset($val->date_added)) {
                                        echo date(ADMIN_DATE_FORMAT, strtotime($val->date_added));
                                    } 
                                    ?>
                                </td> 
                                
                                <td style="vertical-align: middle;">
                                    <?php
                                        $status = $val->status;
                                        if ($status == 1) {
                                            echo 'Active';
                                        } else if ($status == 2) {
                                            echo 'Deactivated';
                                        } else {
                                            echo 'Inactive';
                                        }
                                    ?>
                                </td>
                                <td style="vertical-align: middle;">
                                    <select class="selActions" id="<?php echo $val->instance_id; ?>" name="instance">
                                        <option value="">Actions</option>   
                                        <option value="edit">Edit</option>
                                        <!--<option value="active">Activate</option>
                                        <option value="inactive">Inactivate</option>
                                        <option value="delete">Delete</option>-->
                                    </select>
                                </td>
                            </tr>
                    <?php
                            $i++;
                        }
                    }
                    else 
                    {
                    ?>
                        <script type="text/javascript">
                            setWarningMessage('neg', 'No result found to display.');
                        </script>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <?php $this->activepagination->getAdminPagination(); ?>
        
    </div>
    <?php echo form_close(); ?>
</div>
<?php $this->load->view('admin/includes/footer.php'); ?>
