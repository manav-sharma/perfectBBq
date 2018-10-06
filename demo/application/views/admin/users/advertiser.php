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
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin'); ?>">Users</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
    <div class="column2">
        <a href="<?php echo site_url('admin/users/addAdvertiser'); ?>" class="btn">Register New Advertisers</a>
    </div>

    <div class="clear"></div>
</div>
<div id="pageContent">
    <?php
    $attributes = array('id' => 'listingForm', 'name' => 'users');
    echo form_open('admin/users/home', $attributes);
    ?>
    <input type="hidden" value="filter" name="filterOrSort" id="filterOrSort" />
    <?php $value = $this->session->flashdata('item');
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
                <select id="cmbActions" style="width:auto;" name="user">
                    <option value="">Select</option>
                    <option value="active">Activate</option>
                    <option value="inactive">Inactivate</option>
                    <option value="delete">Delete</option>
                </select>
                <?php if (count($userListing)) { ?>
                    <input type="radio" name="exportCSV" value='exportCSV' id="exportCSV" class="hidden" />
                    <label for="exportCSV" style="display:inline;" class="radioCSVExport" >
                        <a  title="Export to CSV" class="actionIcn toolTip <?php echo ((isset($sortBy) && $sortBy == "usrFirstName") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
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
                    <col width="150">
                    <col width="200">
                    <col width="100">
                    <col width="200">
                    <col width="140">
                    <col width="80">		  
                    <col width="90">
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='fname_<?php echo ((isset($sortBy) && $sortBy == "fname") ? ($orderBy) : 'asc'); ?>' 
                                id="fname" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "fname") ? "checked='checked'" : ""); ?> />
                            <label for="fname" class="radioSubmitClass" >
                                <a class="sorting 
                                    <?php echo ((!empty($sortBy) && $sortBy == "fname") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    First Name
                                </a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='email_<?php echo ((isset($sortBy) && $sortBy == "email") ? ($orderBy) : 'asc'); ?>' 
                                id="email" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "email") ? "checked='checked'" : ""); ?> />
                            <label for="email" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((!empty($sortBy) && $sortBy == "email") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Email
                                </a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='phone_<?php echo ((!empty($sortBy) && $sortBy == "phone") ? ($orderBy) : 'asc'); ?>'
                                id="phone" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "phone") ? "checked='checked'" : ""); ?> />
                            <label for="phone" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((!empty($sortBy) && $sortBy == "phone") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Contact Number
                                </a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='usrAddress_<?php echo ((!empty($sortBy) && $sortBy == "usrAddress") ? ($orderBy) : 'asc'); ?>'
                                id="usrAddress" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "usrAddress") ? "checked='checked'" : ""); ?> />
                            <label for="usrAddress" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((!empty($sortBy) && $sortBy == "usrAddress") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Address
                                </a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='created_at_<?php echo ((!empty($sortBy) && $sortBy == "created_at") ? ($orderBy) : 'asc'); ?>' 
                                id="created_at" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "created_at") ? "checked='checked'" : ""); ?>  />
                            <label for="created_at" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((!empty($sortBy) && $sortBy == "created_at") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
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
                            <input type="text" value="<?php echo isset($postData['txtFname']) ? $postData['txtFname'] : ''; ?>" class="" name="txtFname" maxlength="100" />
                        </td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtEmail']) ? $postData['txtEmail'] : ''; ?>" class="" name="txtEmail" maxlength="255" />
                        </td>

                        <td>
                            <input type="text" value="<?php echo isset($postData['txtPhone']) ? $postData['txtPhone'] : ''; ?>" class="" name="txtPhone" maxlength="20" />
                        </td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtAddress']) ? $postData['txtAddress'] : ''; ?>" class="" name="txtAddress" maxlength="20" />
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
                                    <input type="checkbox" value="<?php echo $val->user_id; ?>" class="checkbox selectRow" name="chkBox[]"  id="check"/>
                                </td>
                                <?php
                                $fname = $val->fname;
                                ?>
                                <td title="<?php echo ucwords(isset($fname) ? $fname : "&nbsp;"); ?>" style="vertical-align: middle;">
                                    <?php echo ucwords(isset($fname) ? $fname : "&nbsp;"); ?>
                                </td>
                                <td style="vertical-align: middle;">
                                    <?php echo $val->email; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:left">
                                    <?php 
                                        if ($val->phone) 
                                            echo $val->phone;
                                        else 
                                            echo '--&raquo;--';
                                    ?>
                                </td>
                                <td style="vertical-align: middle; text-align:left">                    <?php 
                                            echo $val->usrAddress;
                                    ?>
                                </td>
                                <td style="vertical-align: middle;">
                                    <?php 
                                    if (isset($val->created_at)) {
                                        echo date(ADMIN_DATE_FORMAT, strtotime($val->created_at));
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
                                    <select class="selActions" id="<?php echo $val->user_id; ?>" name="advertiser">
                                        <option value="">Actions</option>   
                                        <option value="editadvertiser">Edit</option>
                                        <option value="report/advertiser">View Report</option>
                                        <option value="advertiseractive">Activate</option>
                                        <option value="advertiserinactive">Inactivate</option>
                                        <option value="delete">Delete</option>
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
