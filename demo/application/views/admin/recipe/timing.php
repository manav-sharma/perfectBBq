<?php
$data['scripts'][] = 'admin/recipe/timing.js';
$data['validate']='listingForm'; 
$this->load->view('admin/includes/header.php', $data); ?>  
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ""); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span><a href="<?php echo site_url('admin/recipe'); ?>"> Recipe (<?=$recipeName?>)</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
    <?php if ($recTempMode == "yes" || $recThickMode == "yes") { ?>
    <div class="column2">
        <a href="<?php echo site_url('admin/recipe/addTiming/'.$recipeId); ?>" class="btn">Add Timing</a>
    </div>
	 <?php } ?>
    <div class="clear"></div>
</div>
<div id="pageContent">
    <?php
    $attributes = array('id' => 'listingForm', 'name' => 'recipe');
    echo form_open('admin/recipe/timing/'.$recipeId, $attributes);
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
                <select id="cmbActions" style="width:auto;" name="timing">
                    <option value="">Select</option>
                    <option value="timeActive">Activate</option>
                    <option value="timeInactive">Inactivate</option>
                </select>
				<?php if(count($recipeTimeListing)){ ?>
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
                    <?php if ($recTempMode == "yes" || $recThickMode == "yes") { ?>
                    <col width="125">
                    <?php } ?>
                    <col width="125">
                    <col width="125">
                    <col width="125">
                    <col width="125">
                    <col width="125">
                    <col width="125">
                    <col width="125">
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <?php if ($recTempMode == "yes" || $recThickMode == "yes") { ?>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recThickness_<?php echo ((isset($sortBy) && $sortBy == "recThickness") ? ($orderBy) : 'asc'); ?>' id="recThickness" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recThickness") ? "checked='checked'" : ""); ?> />
                            <label for="recThickness" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recThickness") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Title</a>
                            </label>
                        </th>
                        <?php } ?>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recTimeMin_<?php echo ((isset($sortBy) && $sortBy == "recTimeMin") ? ($orderBy) : 'asc'); ?>' id="recTimeMin" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recTimeMin") ? "checked='checked'" : ""); ?> />
                            <label for="recTimeMin" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recTimeMin") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Time (Min:Sec)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recTemp_<?php echo ((isset($sortBy) && $sortBy == "recTemp") ? ($orderBy) : 'asc'); ?>' id="recTemp" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recTemp") ? "checked='checked'" : ""); ?> />
                            <label for="recTemp" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recTemp") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Temperature</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recBowlTemp_<?php echo ((isset($sortBy) && $sortBy == "recBowlTemp") ? ($orderBy) : 'asc'); ?>' id="recBowlTemp" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recBowlTemp") ? "checked='checked'" : ""); ?> />
                            <label for="recBowlTemp" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recBowlTemp") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Bowl Temperature</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recTimeDateCreated_<?php echo ((isset($sortBy) && $sortBy == "recTimeDateCreated") ? ($orderBy) : 'asc'); ?>' id="recTimeDateCreated" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recTimeDateCreated") ? "checked='checked'" : ""); ?>  />
                            <label for="recTimeDateCreated" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recTimeDateCreated") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Date Created</a>
                            </label>
                        </th>
                        
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recTimeStatus_<?php echo ((isset($sortBy) && $sortBy == "recTimeStatus") ? ($orderBy) : 'asc'); ?>' id="recTimeStatus" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recTimeStatus") ? "checked='checked'" : ""); ?> />
                            <label for="recTimeStatus" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recTimeStatus") ? ("sort" . ucfirst($orderBy)) : ''); ?>"  >Status</a>
                            </label>
                        </th>
                        <th scope="col">Time Interval Added (Yes/No)</th>
                        <th scope="col">Actions</th>
                    </tr>
                    <tr class="filter">
                        <td>&nbsp;</td>
                        <?php if ($recTempMode == "yes" || $recThickMode == "yes") { ?>
                        <td>
                            <input type="text" value="<?php echo isset($postData['recTimeTitle']) ? $postData['recTimeTitle'] : ''; ?>" class="" name="recTimeTitle" maxlength="100" />
                        </td>
                        <?php } ?>
                        <td>
                            <input type="text" value="<?php echo isset($postData['recTimeMinSec']) ? $postData['recTimeMinSec'] : ''; ?>" class="" name="recTimeMinSec" maxlength="100" />
                        </td>
                        <td>
							<input type="text" value="<?php echo isset($postData['recTimeTemp']) ? $postData['recTimeTemp'] : ''; ?>" class="" name="recTimeTemp" maxlength="100" />
                        </td>
                        <td>
							<input type="text" value="<?php echo isset($postData['recTimeBowlTemp']) ? $postData['recTimeBowlTemp'] : ''; ?>" class="" name="recTimeBowlTemp" maxlength="100" />
                        </td>
                        <?php
                        if (isset($postData['recTimeDateFrom']) && !empty($postData['recTimeDateFrom'])) {
                            $from = $postData['recTimeDateFrom'];
                        } else {
                            $from = 'From';
                        }
                        if (isset($postData['recTimeDateTo']) && !empty($postData['recTimeDateTo'])) {
                            $to = $postData['recTimeDateTo'];
                        } else {
                            $to = 'To';
                        }
                        ?>
                        <td>
                            <input type="text" name="recTimeDateFrom"  id="dp1310711996720" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $from; ?>" onblur="if(this.value==''){this.value='From';}" autocomplete="off"/>&nbsp;&nbsp;
                            <input type="text" name="recTimeDateTo"  id="dp1310711996721" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $to; ?>" onblur="if(this.value==''){this.value='To';}" autocomplete="off"/>
                        </td>
                        <td>
                            <select class="" name="recTimeStatus">
                                <option value="">Select Status</option>
                                <option value="1" <?php echo (($postData['recTimeStatus'] === "1") ? "selected='selected'" : ''); ?>>
                                    Active
                                </option>
                                <option value="0" <?php echo (($postData['recTimeStatus'] === "0") ? "selected='selected'" : ''); ?> >
                                    Inactive 
                                </option>
							</select>
                        </td>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" title="Tip: Fill up one or more fields on the left to filter the record list below." value="Apply Filter" class="btn" id="btnFilter" name="btnFilter" />
                        </td>
                    </tr>
                    <?php
                    $total_timing = count($recipeTimeListing);
                    if ($total_timing > 0) {
                        $i = 0;
                        foreach ($recipeTimeListing as $val) {
                            if ($i % 2 == 1) {
                                $class = "alternateRow";
                            } else {
                                $class = "";
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <td ><input type="checkbox" value="<?php echo $val->recTimeId; ?>" class="checkbox selectRow" name="chkBox[]"  id="check"/></td>
                                <?php if ($recTempMode == "yes" || $recThickMode == "yes") { ?>
                                <td title="<?php echo $val->recThickness." ".$val->recCookingStyle; ?>"  style="vertical-align: middle;"><?php echo $val->recThickness." ".$val->recCookingStyle; ?></td>
                                <?php } ?>
                                <td title="<?php echo $val->recTimeMin.":".$val->recTimeSec; ?>"  style="vertical-align: middle;"><?php echo $val->recTimeMin.":".$val->recTimeSec; ?></td>
                                <td title="<?php echo $val->recTemp; ?>"  style="vertical-align: middle;"><?php echo $val->recTemp; ?></td>
                                <td title="<?php echo $val->recBowlTemp; ?>"  style="vertical-align: middle;"><?php echo $val->recBowlTemp; ?></td>
                                <td style="vertical-align: middle;"><?php if(isset($val->recTimeDateCreated)){echo date(ADMIN_DATE_FORMAT, strtotime($val->recTimeDateCreated));} ?></td> 
                                <td style="vertical-align: middle;">
									<?php
									$status = $val->recTimeStatus;
									if ($status == 1) {
										echo 'Active';
									} else {
										echo 'Inactive';
									}
									?>
								</td>
								<td>
									<?php
										$sql ="SELECT * FROM tbl_recipeTimeInterval where recTimeId = ".$val->recTimeId;
										$query = $this->db->query($sql);
										$resultData = $query->result();
										if (!empty($resultData)) {
											$have = 'Yes';
										} else {
											$have = 'No';
										}
										echo $have;
									?>
								</td>
								<td style="vertical-align: middle;">
                                    <select class="selActions" id="<?php echo $val->recTimeId; ?>" name="timing">
                                        <option value="">Actions</option>   
                                        <option value="editTiming">Edit</option>
                                        <option value="timeActive">Activate</option>
                                        <option value="timeInactive">Inactivate</option>
                                        <option value="interval">View Time Interval</option>
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
