<?php
$data['scripts'][] = 'admin/basic/basic.js';
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
        <a href="<?php echo site_url('admin/basic/addBasic'); ?>" class="btn">Add New Basic</a>
    </div>
	 
    <div class="clear"></div>
</div>
<div id="pageContent">
    <?php
    $attributes = array('id' => 'listingForm', 'name' => 'basic');
    echo form_open('admin/basic/', $attributes);
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
                <select id="cmbBbqActions" style="width:auto;" name="basic">
                    <option value="">Select</option>
                    <option value="active">Activate</option>
                    <option value="inactive">Inactivate</option>
                    <option value="delete">Delete</option>
                </select>
				<?php if(count($basicListing)){ ?>
                <input type="radio" name="exportCSV" value='exportCSV' id="exportCSV" class="hidden" />
                <label for="exportCSV" style="display:inline;" class="radioCSVExport" >
                    <a  title="Export to CSV" class="actionIcn toolTip <?php echo ((isset($sortBy) && $sortBy == "strName") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
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
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <col width="100">
                    <col width="100">
                    <col width="100">	
                    <col width="100">	
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='bbqTitleEng_<?php echo ((isset($sortBy) && $sortBy == "bbqTitleEng") ? ($orderBy) : 'asc'); ?>' id="bbqTitleEng" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "bbqTitleEng") ? "checked='checked'" : ""); ?> />
                            <label for="bbqTitleEng" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "bbqTitleEng") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Title (In English)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='bbqTitleDutch_<?php echo ((isset($sortBy) && $sortBy == "bbqTitleDutch") ? ($orderBy) : 'asc'); ?>' id="bbqTitleDutch" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "bbqTitleDutch") ? "checked='checked'" : ""); ?> />
                            <label for="bbqTitleDutch" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "bbqTitleDutch") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Title (In German)</a>
                            </label>
                        </th>
                        
                        <th scope="col">
                            <input type="radio" name="sortBy" value='bbqShortDescEng_<?php echo ((isset($sortBy) && $sortBy == "bbqShortDescEng") ? ($orderBy) : 'asc'); ?>' id="bbqShortDescEng" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "bbqShortDescEng") ? "checked='checked'" : ""); ?> />
                            <label for="bbqShortDescEng" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "bbqShortDescEng") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Short Description (In English)</a>
                            </label>
                        </th>
                        
                        <th scope="col">
                            <input type="radio" name="sortBy" value='bbqShortDescDutch_<?php echo ((isset($sortBy) && $sortBy == "bbqShortDescDutch") ? ($orderBy) : 'asc'); ?>' id="bbqShortDescDutch" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "bbqShortDescDutch") ? "checked='checked'" : ""); ?> />
                            <label for="bbqShortDescDutch" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "bbqShortDescDutch") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Short Description (In German)</a>
                            </label>
                        </th>
                       
                        <th scope="col">
                            <input type="radio" name="sortBy" value='bbqDateCreated_<?php echo ((isset($sortBy) && $sortBy == "bbqDateCreated") ? ($orderBy) : 'asc'); ?>' id="bbqDateCreated" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "bbqDateCreated") ? "checked='checked'" : ""); ?>  />
                            <label for="bbqDateCreated" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "bbqDateCreated") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Date Created</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='bbqStatus_<?php echo ((isset($sortBy) && $sortBy == "bbqStatus") ? ($orderBy) : 'asc'); ?>' id="bbqStatus" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "bbqStatus") ? "checked='checked'" : ""); ?> />
                            <label for="bbqStatus" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "bbqStatus") ? ("sort" . ucfirst($orderBy)) : ''); ?>"  >Status</a>
                            </label>
                        </th>
                        <th scope="col">Image</th>
                        <th scope="col">Actions</th>
                    </tr>
                    <tr class="filter">
                        <td>&nbsp;</td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['bbqTitleEng']) ? $postData['bbqTitleEng'] : ''; ?>" class="" name="bbqTitleEng" maxlength="100" />
                        </td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['bbqTitleDutch']) ? $postData['bbqTitleDutch'] : ''; ?>" class="" name="bbqTitleDutch" maxlength="100" />
                        </td>
                        <td></td>
                        <td></td>
						<?php
							if (isset($postData['bbqDateFrom']) && !empty($postData['bbqDateFrom'])) {
								$from = $postData['bbqDateFrom'];
							} else {
								$from = 'From';
							}
							if (isset($postData['bbqDateTo']) && !empty($postData['bbqDateTo'])) {
								$to = $postData['bbqDateTo'];
							} else {
								$to = 'To';
							}
                        ?>
                        <td>
                            <input type="text" name="bbqDateFrom"  id="dp1310711996720" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $from; ?>" onblur="if(this.value==''){this.value='From';}" autocomplete="off"/>&nbsp;&nbsp;
                            <input type="text" name="bbqDateTo"  id="dp1310711996721" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $to; ?>" onblur="if(this.value==''){this.value='To';}" autocomplete="off"/>
                        </td>
                        <td>
                            <select class="" name="bbqStatus">
                                <option value="">Select Status</option>
                                <option value="1" <?php echo (($postData['bbqStatus'] === "1") ? "selected='selected'" : ''); ?>>
                                    Active
                                </option>
                                <option value="0" <?php echo (($postData['bbqStatus'] === "0") ? "selected='selected'" : ''); ?> >
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
                    $total_baic = count($basicListing);
                    if ($total_baic > 0) {
                        $i = 0;
                        foreach ($basicListing as $val) {
                            if ($i % 2 == 1) {
                                $class = "alternateRow";
                            } else {
                                $class = "";
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <td ><input type="checkbox" value="<?php echo $val->bbqId; ?>" class="checkbox selectRow" name="chkBox[]"  id="check"/></td>
                                <td title="<?php echo ucwords($val->bbqTitleEng); ?>"  style="vertical-align: middle;"><?php echo ucwords($val->bbqTitleEng); ?></td>
                                <td title="<?php echo ucwords($val->bbqTitleDutch); ?>"  style="vertical-align: middle;"><?php echo ucwords($val->bbqTitleDutch); ?></td>
                                <td style="vertical-align: middle;"><?php echo $val->bbqShortDescEng; ?></td>
                                <td style="vertical-align: middle;"><?php echo $val->bbqShortDescDutch; ?></td>
                                <td style="vertical-align: middle;"><?php if(isset($val->bbqDateCreated)){echo date(ADMIN_DATE_FORMAT, strtotime($val->bbqDateCreated));} ?></td> 
                                <td style="vertical-align: middle;">
									<?php
									$status = $val->bbqStatus;
									if ($status == 1) {
										echo 'Active';
									} else {
										echo 'Inactive';
									}
									?>
								</td>
								<td style="vertical-align: middle;">
									<?php
									$bbqImage = $val->bbqImage;
									$fullBbqImagePath = "";
									if (trim($bbqImage) != "" || $bbqImage != null) {
										$fullBbqImagePath = SITE_URL."bbq_images/".$bbqImage;
									?>
										<img height="100" width="100" src="<?=$fullBbqImagePath?>" />
									<?php
									} else {
										echo "";
									}
									?>
								</td>
								<td style="vertical-align: middle;">
                                    <select class="selBbqActions" id="<?php echo $val->bbqId; ?>" name="BBQ">
                                        <option value="">Actions</option>   
                                        <option value="editBasic">Edit</option>
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

<script>
	$(document).ready(function(){
		$('.selBbqActions').change(function () {
			var vale = $(this).val();
			if (vale.length != 0)
			{
				if (vale == 'delete' && !confirm('Are you sure you want to delete selected ' + $(this).attr('name') + '(s) and its ingredients?')) {
					$(".selActions option[selected]").removeAttr("selected");
					$(".selActions option[value='']").attr("selected", "selected");
					return false;
				}
				var id = $(this).attr("id");
				/* var valee = 'users/'+vale;   */
				var valee = $(formId).attr("name") + '/' + vale;
				var uri = siteurl + valee;
				var editu = uri + "/" + id;
				$(location).attr('href', editu);
				return true;
			} else
			{
				return false;
			}
		});
		
		$('#cmbBbqActions').on('change', function () {
			var vale = $(this).val();
			if ($(".selectRow:checked").length < 1) {
				switch (vale) {
					case 'delete':
					case 'active':
					case 'inactive':
						$("#cmbActions option[selected]").removeAttr("selected");
						$("#cmbActions option[value='']").attr("selected", "selected");
						appMessage('You have to select some Bbq ' + $(this).attr('name') + '(s)' +' before any action can be performed on them.', 'neg');
						break;
				}
				return false;
			} else if (vale == 'delete' && !confirm('Are you sure you want to delete selected Bbq ' + $(this).attr('name') + '(s) and its ingredient?')) {
				$("#cmbActions option[selected]").removeAttr("selected");
				$("#cmbActions option[value='']").attr("selected", "selected");
				return false;
			} else {

				var valee = $(formId).attr("name") + '/' + vale;
				var formurl = siteurl + valee;
				$(formId).attr("action", formurl);
				$(formId).attr("method", 'POST');
				$(formId).submit();
				return true;
			}
		});
	});
</script>
