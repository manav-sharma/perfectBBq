<?php
$data['scripts'][] = 'admin/category/category.js';
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
        <a href="<?php echo site_url('admin/category/addCategory'); ?>" class="btn">Add New Category</a>
    </div>
	 
    <div class="clear"></div>
</div>
<div id="pageContent">
    <?php
    $attributes = array('id' => 'listingForm', 'name' => 'category');
    echo form_open('admin/category/', $attributes);
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
                <select id="cmbCatActions" style="width:auto;" name="category">
                    <option value="">Select</option>
                    <option value="active">Activate</option>
                    <option value="inactive">Inactivate</option>
                    <option value="delete">Delete</option>
                </select>
				<?php if(count($categoryListing)){ ?>
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
                    <col width="200">
                    <col width="200">
                    <col width="150">
                    <col width="125">
                    <col width="120">
                    <col width="200">
                    <col width="125">	
                    <col width="125">	
                    <col width="125">	
                    <col width="80">	
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='catTitleEng_<?php echo ((isset($sortBy) && $sortBy == "catTitleEng") ? ($orderBy) : 'asc'); ?>' id="catTitleEng" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "catTitleEng") ? "checked='checked'" : ""); ?> />
                            <label for="catTitleEng" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "catTitleEng") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Title (In English)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='catTitleDutch_<?php echo ((isset($sortBy) && $sortBy == "catTitleDutch") ? ($orderBy) : 'asc'); ?>' id="catTitleDutch" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "catTitleDutch") ? "checked='checked'" : ""); ?> />
                            <label for="catTitleDutch" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "catTitleDutch") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Title (In German)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='catParentId_<?php echo ((isset($sortBy) && $sortBy == "catParentId") ? ($orderBy) : 'asc'); ?>' id="catParentId" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "catParentId") ? "checked='checked'" : ""); ?> />
                            <label for="catParentId" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "catParentId") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Parent (English / German)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='catDateCreated_<?php echo ((isset($sortBy) && $sortBy == "catDateCreated") ? ($orderBy) : 'asc'); ?>' id="catDateCreated" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "catDateCreated") ? "checked='checked'" : ""); ?>  />
                            <label for="catDateCreated" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "catDateCreated") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Date Created</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='catStatus_<?php echo ((isset($sortBy) && $sortBy == "catStatus") ? ($orderBy) : 'asc'); ?>' id="catStatus" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "catStatus") ? "checked='checked'" : ""); ?> />
                            <label for="catStatus" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "catStatus") ? ("sort" . ucfirst($orderBy)) : ''); ?>"  >Status</a>
                            </label>
                        </th>
                        <th scope="col">Image</th>
                        <th scope="col">
							<input type="radio" name="sortBy" value='catAdvSwitch_<?php echo ((isset($sortBy) && $sortBy == "catAdvSwitch") ? ($orderBy) : 'asc'); ?>' id="catAdvSwitch" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "catAdvSwitch") ? "checked='checked'" : ""); ?> />
                            <label for="catAdvSwitch" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "catAdvSwitch") ? ("sort" . ucfirst($orderBy)) : ''); ?>"  >Advertisement Mode</a>
                            </label>
                        </th>
                        <th scope="col">
							<input type="radio" name="sortBy" value='catLayout_<?php echo ((isset($sortBy) && $sortBy == "catLayout") ? ($orderBy) : 'asc'); ?>' id="catLayout" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "catLayout") ? "checked='checked'" : ""); ?> />
                            <label for="catLayout" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "catLayout") ? ("sort" . ucfirst($orderBy)) : ''); ?>"  >Layout</a>
                            </label>
                        </th>
                        <th scope="col">
							<input type="radio" name="sortBy" value='catPosition_<?php echo ((isset($sortBy) && $sortBy == "catPosition") ? ($orderBy) : 'asc'); ?>' id="catPosition" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "catPosition") ? "checked='checked'" : ""); ?> />
                            <label for="catPosition" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "catPosition") ? ("sort" . ucfirst($orderBy)) : ''); ?>"  >Position</a>
                            </label>
                        </th>
                        <th scope="col">Actions</th>
                    </tr>
                    <tr class="filter">
                        <td>&nbsp;</td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['catTitleEng']) ? $postData['catTitleEng'] : ''; ?>" class="" name="catTitleEng" maxlength="100" />
                        </td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['catTitleDutch']) ? $postData['catTitleDutch'] : ''; ?>" class="" name="catTitleDutch" maxlength="100" />
                        </td>
                        <td></td>

                        <?php
                        if (isset($postData['catDateFrom']) && !empty($postData['catDateFrom'])) {
                            $from = $postData['catDateFrom'];
                        } else {
                            $from = 'From';
                        }
                        if (isset($postData['catDateTo']) && !empty($postData['catDateTo'])) {
                            $to = $postData['catDateTo'];
                        } else {
                            $to = 'To';
                        }
                        ?>
                        <td>
                            <input type="text" name="catDateFrom"  id="dp1310711996720" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $from; ?>" onblur="if(this.value==''){this.value='From';}" autocomplete="off"/>&nbsp;&nbsp;
                            <input type="text" name="catDateTo"  id="dp1310711996721" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $to; ?>" onblur="if(this.value==''){this.value='To';}" autocomplete="off"/>
                        </td>
                        <td>
                            <select class="" name="catStatus">
                                <option value="">Select Status</option>
                                <option value="1" <?php echo (($postData['catStatus'] === "1") ? "selected='selected'" : ''); ?>>
                                    Active
                                </option>
                                <option value="0" <?php echo (($postData['catStatus'] === "0") ? "selected='selected'" : ''); ?> >
                                    Inactive 
                                </option>
							</select>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['catLayout']) ? $postData['catLayout'] : ''; ?>" class="" name="catLayout" maxlength="100" />
                        </td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['catPosition']) ? $postData['catPosition'] : ''; ?>" class="" name="catPosition" maxlength="100" />
                        </td>
                        <td>
                            <input type="submit" title="Tip: Fill up one or more fields on the left to filter the record list below." value="Apply Filter" class="btn" id="btnFilter" name="btnFilter" />
                        </td>
                    </tr>
                    <?php
                    $total_category = count($categoryListing);
                    if ($total_category > 0) {
                        $i = 0;
                        foreach ($categoryListing as $val) {
                            if ($i % 2 == 1) {
                                $class = "alternateRow";
                            } else {
                                $class = "";
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <td ><input type="checkbox" value="<?php echo $val->catId; ?>" class="checkbox selectRow" name="chkBox[]"  id="check"/></td>
                                <td title="<?php echo ucwords($val->catTitleEng); ?>"  style="vertical-align: middle;"><?php echo ucwords($val->catTitleEng); ?></td>
                                <td title="<?php echo ucwords($val->catTitleDutch); ?>"  style="vertical-align: middle;"><?php echo ucwords($val->catTitleDutch); ?></td>
                                <?php
                                $catParentEng = $val->catParentNameEng;
                                $catParentDutch = $val->catParentNameDutch;
                                $catParent = "";
                                if (trim($catParentEng) != "" && trim($catParentDutch) != "") {
									$catParent = ucwords($catParentEng)." / ".ucwords($catParentDutch);
								} else if (trim($catParentEng) != "") {
									$catParent = ucwords($catParentEng);
								} else if (trim($catParentDutch) != "") {
									$catParent = ucwords($catParentDutch);
								} else {
									$catParent = '---';
								}
                                ?>
                                <td title="<?php echo $catParent; ?>"  style="vertical-align: middle;"><?php echo $catParent; ?></td>
                                
                                <td style="vertical-align: middle;"><?php if(isset($val->catDateCreated)){echo date(ADMIN_DATE_FORMAT, strtotime($val->catDateCreated));} ?></td> 
                                <td style="vertical-align: middle;">
									<?php
									$status = $val->catStatus;
									if ($status == 1) {
										echo 'Active';
									} else {
										echo 'Inactive';
									}
									?>
								</td>
								<td style="vertical-align: middle;">
									<?php
									$advImage = $val->catAdvImage;
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
									<?php
									$advMode = $val->catAdvSwitch;
									if (trim($advMode) == 1) {
										echo "On";
									} else {
										echo "Off";
									}
									?>
								</td>
								<td title="<?php echo $val->catLayout; ?>"  style="vertical-align: middle;"><?php echo $val->catLayout; ?></td>
								<td title="<?php echo $val->catPosition; ?>"  style="vertical-align: middle;"><?php echo $val->catPosition; ?></td>
                               <td style="vertical-align: middle;">
                                    <select class="selCatActions" id="<?php echo $val->catId; ?>" name="category">
                                        <option value="">Actions</option>   
                                        <option value="editCategory">Edit</option>
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
		$('.selCatActions').change(function () {
			var vale = $(this).val();
			if (vale.length != 0)
			{
				if (vale == 'delete' && !confirm('Are you sure you want to delete selected ' + $(this).attr('name') + '(s)? If yes then all the recipes and child categories will be deleted.')) {
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
		
		$('#cmbCatActions').on('change', function () {
			var vale = $(this).val();
			if ($(".selectRow:checked").length < 1) {
				switch (vale) {
					case 'delete':
					case 'active':
					case 'inactive':
						$("#cmbActions option[selected]").removeAttr("selected");
						$("#cmbActions option[value='']").attr("selected", "selected");
						appMessage('You have to select some  ' + $(this).attr('name') + '(s)' +' before any action can be performed on them.', 'neg');
						break;
				}
				return false;
			} else if (vale == 'delete' && !confirm('Are you sure you want to delete selected ' + $(this).attr('name') + '(s)? If yes then all the recipes and child categories will be deleted.')) {
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
