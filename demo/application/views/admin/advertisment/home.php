<?php
$data['scripts'][] = 'admin/advertisment/advertisment.js';
$data['validate']='listingForm'; 
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
        <a href="<?php echo site_url('admin/advertisment/addAdv'); ?>" class="btn">Add New Advertisement</a>
    </div>
	 
    <div class="clear"></div>
</div>
<div id="pageContent">
    <?php
    $attributes = array('id' => 'listingForm', 'name' => 'advertisment');
    echo form_open('admin/advertisment/', $attributes);
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
            <dd> Select: <a href="javascript:void(0)" id="selectAll">All</a> | <a href="javascript:void(0)" id="selectNone">None</a> &nbsp;&nbsp;<strong>Actions</strong>
                <select id="cmbActions" style="width:auto;" name="advertisment">
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
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <col width="100">
                    <col width="100">	
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='advTitleEng_<?php echo ((isset($sortBy) && $sortBy == "advTitleEng") ? ($orderBy) : 'asc'); ?>' id="advTitleEng" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "advTitleEng") ? "checked='checked'" : ""); ?> />
                            <label for="advTitleEng" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "advTitleEng") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Title (In English)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='advTitleDutch_<?php echo ((isset($sortBy) && $sortBy == "advTitleDutch") ? ($orderBy) : 'asc'); ?>' id="advTitleDutch" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "advTitleDutch") ? "checked='checked'" : ""); ?> />
                            <label for="advTitleDutch" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "advTitleDutch") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Title (In German)</a>
                            </label>
                        </th>
                        <th scope="col">Category</th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='advDateCreated_<?php echo ((isset($sortBy) && $sortBy == "advDateCreated") ? ($orderBy) : 'asc'); ?>' id="advDateCreated" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "advDateCreated") ? "checked='checked'" : ""); ?>  />
                            <label for="advDateCreated" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "advDateCreated") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Date Created</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='advStatus_<?php echo ((isset($sortBy) && $sortBy == "advStatus") ? ($orderBy) : 'asc'); ?>' id="advStatus" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "advStatus") ? "checked='checked'" : ""); ?> />
                            <label for="advStatus" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "advStatus") ? ("sort" . ucfirst($orderBy)) : ''); ?>"  >Status</a>
                            </label>
                        </th>
                        <th scope="col">Image</th>
                        <th scope="col">Actions</th>
                    </tr>
                    <tr class="filter">
                        <td>&nbsp;</td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['advTitleEng']) ? $postData['advTitleEng'] : ''; ?>" class="" name="advTitleEng" maxlength="100" />
                        </td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['advTitleDutch']) ? $postData['advTitleDutch'] : ''; ?>" class="" name="advTitleDutch" maxlength="100" />
                        </td>
                        <td></td>
                        <?php
							if (isset($postData['advDateFrom']) && !empty($postData['advDateFrom'])) {
								$from = $postData['advDateFrom'];
							} else {
								$from = 'From';
							}
							if (isset($postData['advDateTo']) && !empty($postData['advDateTo'])) {
								$to = $postData['advDateTo'];
							} else {
								$to = 'To';
							}
                        ?>
                        <td>
                            <input type="text" name="advDateFrom"  id="dp1310711996720" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $from; ?>" onblur="if(this.value==''){this.value='From';}" autocomplete="off"/>&nbsp;&nbsp;
                            <input type="text" name="advDateTo"  id="dp1310711996721" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $to; ?>" onblur="if(this.value==''){this.value='To';}" autocomplete="off"/>
                        </td>
                        <td>
                            <select class="" name="advStatus">
                                <option value="">Select Status</option>
                                <option value="1" <?php echo (($postData['advStatus'] === "1") ? "selected='selected'" : ''); ?>>
                                    Active
                                </option>
                                <option value="0" <?php echo (($postData['advStatus'] === "0") ? "selected='selected'" : ''); ?> >
                                    Inactive 
                                </option>
							</select>
                        </td>
                        <td></td>
                        <td>
                            <input type="submit" title="Tip: Fill up one or more fields on the left to filter the record list below." value="Apply Filter" class="btn" id="btnFilter" name="btnFilter" />
                        </td>
                    </tr>
                    <?php
                    $total_adv = count($advertismentListing);
                    if ($total_adv > 0) {
                        $i = 0;
                        foreach ($advertismentListing as $val) {
                            if ($i % 2 == 1) {
                                $class = "alternateRow";
                            } else {
                                $class = "";
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <td ><input type="checkbox" value="<?php echo $val->advId; ?>" class="checkbox selectRow" name="chkBox[]"  id="check"/></td>
                                <td title="<?php echo ucwords($val->advTitleEng); ?>"  style="vertical-align: middle;"><?php echo ucwords($val->advTitleEng); ?></td>
                                <td title="<?php echo ucwords($val->advTitleDutch); ?>"  style="vertical-align: middle;"><?php echo ucwords($val->advTitleDutch); ?></td>
                                <td>
									<?php
										$catId = $val->advCat;
										if (trim($catId) != "") {
											if (strpos($catId,",") > 0) {
												$catIdArr = explode(",",$catId);
												$catName = "";
												foreach ($catIdArr as $catKey => $catVal) {
													$sql ="SELECT catTitleEng FROM tbl_category where catId = ".$catVal;
													$query = $this->db->query($sql);
													$result = $query->result();
													if (!empty($result)) {
														$catTitle = $result[0]->catTitleEng;
														$catName .= ", ".$catTitle; 
													}
												}
												echo ltrim($catName,",");
											} else {
												$catName = "";
												$sql ="SELECT catTitleEng FROM tbl_category where catId = ".$catId;
												$query = $this->db->query($sql);
												$result = $query->result();
												if (!empty($result)) {
													$catTitle = $result[0]->catTitleEng;
													$catName = $catTitle; 
												}
												echo $catName;
											}
										}
									?>
                                </td>
                                <td style="vertical-align: middle;"><?php if(isset($val->advDateCreated)){echo date(ADMIN_DATE_FORMAT, strtotime($val->advDateCreated));} ?></td> 
                                <td style="vertical-align: middle;">
									<?php
									$status = $val->advStatus;
									if ($status == 1) {
										echo 'Active';
									} else {
										echo 'Inactive';
									}
									?>
								</td>
								<td style="vertical-align: middle;">
									<?php
									$advImage = $val->advImage;
									$fullAdvImagePath = "";
									if (trim($advImage) != "" || $advImage != null) {
										$fullAdvImagePath = SITE_URL."bbq_images/".$advImage;
									?>
										<img height="100" width="100" src="<?=$fullAdvImagePath?>" />
									<?php
									} else {
										echo "";
									}
									?>
								</td>
								<td style="vertical-align: middle;">
                                    <select class="selActions" id="<?php echo $val->advId; ?>" name="Advertisment">
                                        <option value="">Actions</option>   
                                        <option value="active">Activate</option>
                                        <option value="inactive">Inactivate</option>
                                        <option value="delete">Delete</option>
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
