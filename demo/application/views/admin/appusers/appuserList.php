<?php
$data['scripts'][] = 'admin/home.js';
$data['validate']='listingForm'; 
$this->load->view('admin/includes/header.php', $data); ?>  
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ""); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span>
            <a href="<?php echo site_url('admin'); ?>">App Users</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
    <!--<div class="column2">
        <a href="<?php //echo site_url('admin/appusers/addUser'); ?>" class="btn">Add New Users</a>
    </div>-->
	 
    <div class="clear"></div>
</div>
<div id="pageContent">
    <?php
    $attributes = array('id' => 'listingForm', 'name' => 'appusers');
    echo form_open('admin/appusers/appusersList', $attributes);
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
                <select id="cmbActions" style="width:auto;" name="user">
                    <option value="">Select</option>
                    <option value="active">Activate</option>
                    <option value="inactive">Inactivate</option>
                    <option value="delete">Delete</option>
                </select>
				<?php if(count($userListing)){ ?>
                <input type="radio" name="exportCSV" value='exportCSV' id="exportCSV" class="hidden" />
                <label for="exportCSV" style="display:inline;" class="radioCSVExport" >
                    <a  title="Export to CSV" class="actionIcn toolTip <?php echo ((isset($sortBy) && $sortBy == "usrFirstName") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                        <img src="<?php echo base_url(); ?>common/media/images/icons/icn.export.gif" alt="" width="18" height="18" />
                    </a>
                </label>
				<?php }?>

            </dd>
        </dl>
        <div class="stdListing">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <colgroup>
                    <col width="25">
                    <col width="250">
                    <col width="300">
                    <col width="150">
                    <col width="150">
                    <col width="100">		  
                    <col width="100">
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='username_<?php echo ((isset($sortBy) && $sortBy == "username") ? ($orderBy) : 'asc'); ?>' id="username" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "username") ? "checked='checked'" : ""); ?> />
                            <label for="username" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "username") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Full Name</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='useremail_<?php echo ((isset($sortBy) && $sortBy == "useremail") ? ($orderBy) : 'asc'); ?>' id="useremail" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "useremail") ? "checked='checked'" : ""); ?> />
                            <label for="useremail" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "useremail") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Email</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='usermobile_<?php echo ((isset($sortBy) && $sortBy == "usermobile") ? ($orderBy) : 'asc'); ?>' id="usermobile" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "usermobile") ? "checked='checked'" : ""); ?> />
                            <label for="usermobile" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "usermobile") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Contact Number</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='datecreation_<?php echo ((isset($sortBy) && $sortBy == "datecreation") ? ($orderBy) : 'asc'); ?>' id="datecreation" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "datecreation") ? "checked='checked'" : ""); ?>  />
                            <label for="datecreation" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "datecreation") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Date Created</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='userStatus_<?php echo ((isset($sortBy) && $sortBy == "userStatus") ? ($orderBy) : 'asc'); ?>' id="userStatus" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "userStatus") ? "checked='checked'" : ""); ?> />
                            <label for="userStatus" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "userStatus") ? ("sort" . ucfirst($orderBy)) : ''); ?>"  >Status</a>
                            </label>
                        </th>
                        
                        
                        <!--<th scope="col">
                            <input type="radio" name="sortBy" value='usrType_<?php //echo ((isset($sortBy) && $sortBy == "usrType") ? ($orderBy) : 'asc'); ?>' id="usrType" class="hidden" <?php //echo ((isset($sortBy) && $sortBy == "usrType") ? "checked='checked'" : ""); ?> />
                            <label for="usrType" class="radioSubmitClass" >
                                <a class="sorting <?php //echo ((isset($sortBy) && $sortBy == "usrType") ? ("sort" . ucfirst($orderBy)) : ''); ?>"  >User Type</a>
                            </label>
                        </th>-->
                        
                        
                        <th scope="col">Actions</th>
                    </tr>
                    <tr class="filter">
                        <td>&nbsp;</td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['username']) ? $postData['username'] : ''; ?>" class="" name="username" maxlength="100" />
                        </td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['useremail']) ? $postData['useremail'] : ''; ?>" class="" name="useremail" maxlength="255" />
                        </td>

                        <td>
                            <input type="text" value="<?php echo isset($postData['usermobile']) ? $postData['usermobile'] : ''; ?>" class="" name="usermobile" maxlength="20" />
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
                            <select class="" name="userStatus">
                                <option value="">Select Status</option>
                                <option value="Active" <?php echo ((@$postData['userStatus'] === "Active") ? "selected='selected'" : ''); ?>>
                                    Active
                                </option>
                                <option value="Inactive" <?php echo ((@$postData['userStatus'] === "Inactive") ? "selected='selected'" : ''); ?> >
                                    Inactive 
                                </option>
								</select>
                        </td>
                        
                        <!--<td>
                            <select class="" name="usrTypeSelect">
                                <option value="">Select User Type</option>
                                <option value="1" <?php //echo (($postData['usrTypeSelect'] === "1") ? "selected='selected'" : ''); ?>>
                                    Hotel Users
                                </option>
                                <option value="2" <?php //echo (($postData['usrTypeSelect'] === "2") ? "selected='selected'" : ''); ?> >
                                    Admin Users 
                                </option>
                            </select>
                        </td>-->
                        
                        
                        <td>
                            <input type="submit" title="Tip: Fill up one or more fields on the left to filter the record list below." value="Apply Filter" class="btn" id="btnFilter" name="btnFilter" />
                        </td>
                    </tr>
                    <?php
                    $total_users = count($userListing);
                    if ($total_users > 0) {
                        $i = 0;
                        foreach ($userListing as $val) {
							//echo "<pre>";
							//print_r($val);
                            if ($i % 2 == 1) {
                                $class = "alternateRow";
                            } else {
                                $class = "";
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <td ><input type="checkbox" value="<?php echo $val->userid; ?>" class="checkbox selectRow" name="chkBox[]"  id="check"/></td>
                                <?php /*$geoid = $val->usrCity;
                                $fullname = $val->usrFirstName . "&nbsp;" . $val->usrLastName;*/ ?>
                                <td title="<?php echo ucwords(isset($val->username) ? $val->username : "&nbsp;"); ?>"  style="vertical-align: middle;"><?php echo ucwords(isset($val->username) ? $val->username : "&nbsp;"); ?></td>
                                <td style="vertical-align: middle;"><?php echo $val->useremail; ?></td>
                                <td style="vertical-align: middle; text-align:left"><?php if($val->usermobile) echo $val->usermobile; else echo '--&raquo;--';?></td>
                                <td style="vertical-align: middle;"><?php if(isset($val->datecreation)){echo date(ADMIN_DATE_FORMAT, strtotime($val->datecreation));} ?></td> 
                                <td style="vertical-align: middle;"><?php echo $status = $val->userStatus; ?></td>
                                <td style="vertical-align: middle;">
                                    <select class="selActions" id="<?php echo $val->userid; ?>" name="user">
                                        <option value="">Actions</option>   
                                        <!--<option value="editUser">Edit</option>-->
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
