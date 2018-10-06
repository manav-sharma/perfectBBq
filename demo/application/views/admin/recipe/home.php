<?php
$data['scripts'][] = 'admin/recipe/recipe.js';
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
        <a href="<?php echo site_url('admin/recipe/addRecipe'); ?>" class="btn">Add New Recipe</a>
    </div>
	 
    <div class="clear"></div>
</div>
<div id="pageContent">
    <?php
    $attributes = array('id' => 'listingForm', 'name' => 'recipe');
    echo form_open('admin/recipe/', $attributes);
    ?>
    <input type="hidden" value="filter" name="filterOrSort" id="filterOrSort" />
    <?php $value = $this->session->flashdata('item');
    if (!empty($value)) { ?>
        <?php echo $value; ?>
    <?php }else{
        echo '<div class="warning hidden"></div>';
    } ?>
    <div class="stdListingWrapper stdListingRecipeWrapper">
        <dl id="actionBar">
            <dt>&nbsp;</dt>
            <dd> Select: <a href="javascript:void(0)" id="selectAll">All</a> | <a href="javascript:void(0)" id="selectNone">None</a> &nbsp;&nbsp;<strong>Actions</strong>
                <select id="cmbActions" style="width:auto;" name="category">
                    <option value="">Select</option>
                    <option value="active">Activate</option>
                    <option value="inactive">Inactivate</option>
                    <!--option value="delete">Delete</option-->
                </select>
				<?php if(count($recipeListing)){ ?>
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
                    <col width="120">
                    <col width="125">	
                    <col width="125">	
                    <col width="80">	
                    <col width="80">	
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recTitleEng_<?php echo ((isset($sortBy) && $sortBy == "recTitleEng") ? ($orderBy) : 'asc'); ?>' id="recTitleEng" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recTitleEng") ? "checked='checked'" : ""); ?> />
                            <label for="recTitleEng" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recTitleEng") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Title (In English)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recTitleDutch_<?php echo ((isset($sortBy) && $sortBy == "recTitleDutch") ? ($orderBy) : 'asc'); ?>' id="recTitleDutch" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recTitleDutch") ? "checked='checked'" : ""); ?> />
                            <label for="recTitleDutch" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recTitleDutch") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Title (In German)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recShortDescEng_<?php echo ((isset($sortBy) && $sortBy == "recShortDescEng") ? ($orderBy) : 'asc'); ?>' id="recShortDescEng" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recShortDescEng") ? "checked='checked'" : ""); ?> />
                            <label for="recShortDescEng" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recShortDescEng") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Short Description (In English)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recShortDescDutch_<?php echo ((isset($sortBy) && $sortBy == "recShortDescDutch") ? ($orderBy) : 'asc'); ?>' id="recShortDescDutch" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recShortDescDutch") ? "checked='checked'" : ""); ?> />
                            <label for="recShortDescDutch" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recShortDescDutch") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Short Description (In German)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='catTitleEng_<?php echo ((isset($sortBy) && $sortBy == "cat.catTitleEng") ? ($orderBy) : 'asc'); ?>' id="catTitleEng" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "catTitleEng") ? "checked='checked'" : ""); ?> />
                            <label for="catTitleEng" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "cat.catTitleEng") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Category (In English)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='catTitleDutch_<?php echo ((isset($sortBy) && $sortBy == "cat.catTitleDutch") ? ($orderBy) : 'asc'); ?>' id="catTitleDutch" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "catTitleDutch") ? "checked='checked'" : ""); ?> />
                            <label for="catTitleDutch" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "cat.catTitleDutch") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Category (In German)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recDateCreated_<?php echo ((isset($sortBy) && $sortBy == "recDateCreated") ? ($orderBy) : 'asc'); ?>' id="recDateCreated" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recDateCreated") ? "checked='checked'" : ""); ?>  />
                            <label for="recDateCreated" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recDateCreated") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Date Created</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recStatus_<?php echo ((isset($sortBy) && $sortBy == "recStatus") ? ($orderBy) : 'asc'); ?>' id="recStatus" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recStatus") ? "checked='checked'" : ""); ?> />
                            <label for="recStatus" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recStatus") ? ("sort" . ucfirst($orderBy)) : ''); ?>"  >Status</a>
                            </label>
                        </th>
                        <th scope="col">Image</th>
                        <th scope="col">Actions</th>
                    </tr>
                    <tr class="filter">
                        <td>&nbsp;</td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['recTitleEng']) ? $postData['recTitleEng'] : ''; ?>" class="" name="recTitleEng" maxlength="100" />
                        </td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['recTitleDutch']) ? $postData['recTitleDutch'] : ''; ?>" class="" name="recTitleDutch" maxlength="100" />
                        </td>
                        <td></td>
                        <td></td>
                        <td>
							<input type="text" value="<?php echo isset($postData['catTitleEng']) ? $postData['catTitleEng'] : ''; ?>" class="" name="catTitleEng" maxlength="100" />
                        </td>
                        <td>
							<input type="text" value="<?php echo isset($postData['catTitleDutch']) ? $postData['catTitleDutch'] : ''; ?>" class="" name="catTitleDutch" maxlength="100" />
                        </td>

                        <?php
                        if (isset($postData['recDateFrom']) && !empty($postData['recDateFrom'])) {
                            $from = $postData['recDateFrom'];
                        } else {
                            $from = 'From';
                        }
                        if (isset($postData['recDateTo']) && !empty($postData['recDateTo'])) {
                            $to = $postData['recDateTo'];
                        } else {
                            $to = 'To';
                        }
                        ?>
                        <td>
                            <input type="text" name="recDateFrom"  id="dp1310711996720" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $from; ?>" onblur="if(this.value==''){this.value='From';}" autocomplete="off"/>&nbsp;&nbsp;
                            <input type="text" name="recDateTo"  id="dp1310711996721" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $to; ?>" onblur="if(this.value==''){this.value='To';}" autocomplete="off"/>
                        </td>
                        <td>
                            <select class="" name="recStatus">
                                <option value="">Select Status</option>
                                <option value="1" <?php echo (($postData['recStatus'] === "1") ? "selected='selected'" : ''); ?>>
                                    Active
                                </option>
                                <option value="0" <?php echo (($postData['recStatus'] === "0") ? "selected='selected'" : ''); ?> >
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
                    $total_recipe = count($recipeListing);
                    if ($total_recipe > 0) {
                        $i = 0;
                        foreach ($recipeListing as $val) {
                            if ($i % 2 == 1) {
                                $class = "alternateRow";
                            } else {
                                $class = "";
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <td ><input type="checkbox" value="<?php echo $val->recId; ?>" class="checkbox selectRow" name="chkBox[]"  id="check"/></td>
                                <td title="<?php echo ucwords($val->recTitleEng); ?>"  style="vertical-align: middle;"><?php echo ucwords($val->recTitleEng); ?></td>
                                <td title="<?php echo ucwords($val->recTitleDutch); ?>"  style="vertical-align: middle;"><?php echo ucwords($val->recTitleDutch); ?></td>
                                <td style="vertical-align: middle;"><?php echo $val->recShortDescEng; ?></td>
                                <td style="vertical-align: middle;"><?php echo $val->recShortDescDutch; ?></td>
                                <td title="<?php echo ucwords($val->catTitleEng); ?>"  style="vertical-align: middle;"><?php echo ucwords($val->catTitleEng); ?></td>
                                <td title="<?php echo ucwords($val->catTitleDutch); ?>"  style="vertical-align: middle;"><?php echo ucwords($val->catTitleDutch); ?></td>
                                <td style="vertical-align: middle;"><?php if(isset($val->recDateCreated)){echo date(ADMIN_DATE_FORMAT, strtotime($val->recDateCreated));} ?></td> 
                                <td style="vertical-align: middle;">
									<?php
									$status = $val->recStatus;
									if ($status == 1) {
										echo 'Active';
									} else {
										echo 'Inactive';
									}
									?>
								</td>
								<td style="vertical-align: middle;">
									<?php
									$recImage = $val->recImage;
									$fullRecImagePath = "";
									if (trim($recImage) != "" || $recImage != null) {
										$fullRecImagePath = SITE_URL."bbq_images/".$recImage;
									?>
										<img height="100" width="100" src="<?=$fullRecImagePath?>" />
									<?php
									} else {
										echo "";
									}
									?>
								</td>
								<td style="vertical-align: middle;">
                                    <select class="selActions" id="<?php echo $val->recId; ?>" name="recipe">
                                        <option value="">Actions</option>   
                                        <option value="editRecipe">Edit</option>
                                        <option value="active">Activate</option>
                                        <option value="inactive">Inactivate</option>
                                        <option value="delete">Delete</option>
                                        <?php
											$tempMode = trim($val->recTempMode);
											$thickMode = trim($val->recThickMode);
											if ($tempMode != "" || $thickMode != "") {
                                         ?>
                                        <option value="timing">View Timing</option>
                                        <?php } else if ($tempMode == "" && $thickMode == "") { ?>
											<option value="timing">View Timing</option>
										<?php } ?>
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
