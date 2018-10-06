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
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin'); ?>">Users</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
    <div class="column2">
        <a href="<?php echo site_url('admin/users/addHotel'); ?>" class="btn">Register New Hotels</a>
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
                    <col width="140">
                    <col width="80">		  
                    <col width="90">
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='usrFirstName_<?php echo ((isset($sortBy) && $sortBy == "usrFirstName") ? ($orderBy) : 'asc'); ?>' id="usrFirstName" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "usrFirstName") ? "checked='checked'" : ""); ?> />
                            <label for="usrFirstName" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "usrFirstName") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Full Name</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='usrEmail_<?php echo ((isset($sortBy) && $sortBy == "usrEmail") ? ($orderBy) : 'asc'); ?>' id="usrEmail" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "usrEmail") ? "checked='checked'" : ""); ?> />
                            <label for="usrEmail" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "usrEmail") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Email</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='hotelContact_<?php echo ((isset($sortBy) && $sortBy == "hotelContact") ? ($orderBy) : 'asc'); ?>' id="hotelContact" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "hotelContact") ? "checked='checked'" : ""); ?> />
                            <label for="hotelContact" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "hotelContact") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Contact Number</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='usrDateCreated_<?php echo ((isset($sortBy) && $sortBy == "usrDateCreated") ? ($orderBy) : 'asc'); ?>' id="usrDateCreated" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "usrDateCreated") ? "checked='checked'" : ""); ?>  />
                            <label for="usrDateCreated" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "usrDateCreated") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Date Created</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='usrStatus_<?php echo ((isset($sortBy) && $sortBy == "usrStatus") ? ($orderBy) : 'asc'); ?>' id="usrStatus" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "usrStatus") ? "checked='checked'" : ""); ?> />
                            <label for="usrStatus" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "usrStatus") ? ("sort" . ucfirst($orderBy)) : ''); ?>"  >Status</a>
                            </label>
                        </th>
                        
                        
                        
                        <th scope="col">Actions</th>
                    </tr>
                    <tr class="filter">
                        <td>&nbsp;</td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtName']) ? $postData['txtName'] : ''; ?>" class="" name="txtName" maxlength="100" />
                        </td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtEmailVal']) ? $postData['txtEmailVal'] : ''; ?>" class="" name="txtEmailVal" maxlength="255" />
                        </td>

                        <td>
                            <input type="text" value="<?php echo isset($postData['txtContactVal']) ? $postData['txtContactVal'] : ''; ?>" class="" name="txtContactVal" maxlength="20" />
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
                    if ($total_users > 0) {
                        $i = 0;
                        foreach ($userListing as $val) {
							if ($i % 2 == 1) {
                                $class = "alternateRow";
                            } else {
                                $class = "";
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <td ><input type="checkbox" value="<?php echo $val->id; ?>" class="checkbox selectRow" name="chkBox[]"  id="check"/></td>
                                <?php $geoid = $val->city;
                                $fullname = $val->usrFirstName; ?>
                                <td title="<?php echo ucwords(isset($fullname) ? $fullname : "&nbsp;"); ?>"  style="vertical-align: middle;"><?php echo ucwords(isset($fullname) ? $fullname : "&nbsp;"); ?></td>
                                <td style="vertical-align: middle;"><?php echo $val->usrEmail; ?></td>
                                <td style="vertical-align: middle; text-align:left"><?php if($val->hotelContact) echo $val->hotelContact; else echo '--&raquo;--';?></td>
                                <td style="vertical-align: middle;"><?php if(isset($val->usrDateCreated)){echo date(ADMIN_DATE_FORMAT, strtotime($val->usrDateCreated));} ?></td> 
                                <td style="vertical-align: middle;"><?php
										$status = $val->usrStatus;
										if ($status == 1) {
											echo 'Active';
										} else if ($status == 2){
											echo 'Deactivated';
										} else {
											echo 'Inactive';
										}
                                ?></td>
                               <td style="vertical-align: middle;">
                                    <select class="selActions" id="<?php echo $val->id; ?>" name="user">
                                        <option value="">Actions</option>   
                                        <option value="editHotel">Edit</option>
                                        <option value="hotelactive">Activate</option>
                                        <option value="hotelinactive">Inactivate</option>
                                        <option value="hoteldelete">Delete</option>
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
