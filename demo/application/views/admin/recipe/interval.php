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
            <span class="pipe">&#187;</span><a href="<?php echo site_url('admin/recipe/timing/'.$recId); ?>"> Timing</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
    <div class="column2">
        <a href="<?php echo site_url('admin/recipe/addInterval/'.$timeIntervalId); ?>" class="btn">Add Interval</a>
    </div>
	 
    <div class="clear"></div>
</div>
<div id="pageContent">
    <?php
    $attributes = array('id' => 'listingForm', 'name' => 'recipe');
    echo form_open('admin/recipe/interval/'.$timeIntervalId, $attributes);
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
                <select id="cmbIntervalActions" style="width:auto;" name="interval">
                    <option value="">Select</option>
                    <option value="deleteInterval">Delete</option>
                </select>
				<?php if(count($recipeTimeIntervalListing)){ ?>
                <input type="radio" name="exportIntervalCSV" value='exportIntervalCSV' id="exportCSV" class="hidden" />
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
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recIntervalTitleEng_<?php echo ((isset($sortBy) && $sortBy == "recIntervalTitleEng") ? ($orderBy) : 'asc'); ?>' id="recIntervalTitleEng" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recIntervalTitleEng") ? "checked='checked'" : ""); ?> />
                            <label for="recIntervalTitleEng" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recIntervalTitleEng") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Title (In English)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recIntervalTitleDutch_<?php echo ((isset($sortBy) && $sortBy == "recIntervalTitleDutch") ? ($orderBy) : 'asc'); ?>' id="recIntervalTitleDutch" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recIntervalTitleDutch") ? "checked='checked'" : ""); ?> />
                            <label for="recIntervalTitleDutch" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recIntervalTitleDutch") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Title (In German)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recIntervalDescEng_<?php echo ((isset($sortBy) && $sortBy == "recIntervalDescEng") ? ($orderBy) : 'asc'); ?>' id="recIntervalDescEng" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recIntervalDescEng") ? "checked='checked'" : ""); ?> />
                            <label for="recIntervalDescEng" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recIntervalDescEng") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Description (In English)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recIntervalDescDutch_<?php echo ((isset($sortBy) && $sortBy == "recIntervalDescDutch") ? ($orderBy) : 'asc'); ?>' id="recIntervalDescDutch" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recIntervalDescDutch") ? "checked='checked'" : ""); ?> />
                            <label for="recIntervalDescDutch" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recIntervalDescDutch") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Description (In German)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recIntervalTimeMin_<?php echo ((isset($sortBy) && $sortBy == "recIntervalTimeMin") ? ($orderBy) : 'asc'); ?>' id="recIntervalTimeMin" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recIntervalTimeMin") ? "checked='checked'" : ""); ?> />
                            <label for="recIntervalTimeMin" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recIntervalTimeMin") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                Time (Min:Sec)</a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" value='recIntervalDateCreated_<?php echo ((isset($sortBy) && $sortBy == "recIntervalDateCreated") ? ($orderBy) : 'asc'); ?>' id="recIntervalDateCreated" class="hidden" <?php echo ((isset($sortBy) && $sortBy == "recIntervalDateCreated") ? "checked='checked'" : ""); ?>  />
                            <label for="recIntervalDateCreated" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((isset($sortBy) && $sortBy == "recIntervalDateCreated") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >Date Created</a>
                            </label>
                        </th>
                        <th scope="col">Actions</th>
                    </tr>
                    <tr class="filter">
                        <td>&nbsp;</td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['recIntTitleEng']) ? $postData['recIntTitleEng'] : ''; ?>" class="" name="recIntTitleEng" maxlength="100" />
                        </td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['recIntTitleDutch']) ? $postData['recIntTitleDutch'] : ''; ?>" class="" name="recIntTitleDutch" maxlength="100" />
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['recIntMinSec']) ? $postData['recIntMinSec'] : ''; ?>" class="" name="recIntMinSec" maxlength="100" />
                        </td>
                        <?php
                        if (isset($postData['recIntDateFrom']) && !empty($postData['recIntDateFrom'])) {
                            $from = $postData['recIntDateFrom'];
                        } else {
                            $from = 'From';
                        }
                        if (isset($postData['recIntDateTo']) && !empty($postData['recIntDateTo'])) {
                            $to = $postData['recIntDateTo'];
                        } else {
                            $to = 'To';
                        }
                        ?>
                        <td>
                            <input type="text" name="recIntDateFrom"  id="dp1310711996720" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $from; ?>" onblur="if(this.value==''){this.value='From';}" autocomplete="off"/>&nbsp;&nbsp;
                            <input type="text" name="recIntDateTo"  id="dp1310711996721" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $to; ?>" onblur="if(this.value==''){this.value='To';}" autocomplete="off"/>
                        </td>
                        <td>
                            <input type="submit" title="Tip: Fill up one or more fields on the left to filter the record list below." value="Apply Filter" class="btn" id="btnFilter" name="btnFilter" />
                        </td>
                    </tr>
                    <?php
                    $total_interval = count($recipeTimeIntervalListing);
                    if ($total_interval > 0) {
                        $i = 0;
                        foreach ($recipeTimeIntervalListing as $val) {
                            if ($i % 2 == 1) {
                                $class = "alternateRow";
                            } else {
                                $class = "";
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <td ><input type="checkbox" value="<?php echo $val->recIntervalId; ?>" class="checkbox selectRow" name="chkBox[]"  id="check"/></td>
                                <td title="<?php echo ucwords($val->recIntervalTitleEng); ?>"  style="vertical-align: middle;"><?php echo ucwords($val->recIntervalTitleEng); ?></td>
                                <td title="<?php echo ucwords($val->recIntervalTitleDutch); ?>"  style="vertical-align: middle;"><?php echo ucwords($val->recIntervalTitleDutch); ?></td>
                                <td style="vertical-align: middle;"><?php echo $val->recIntervalDescEng; ?></td>
                                <td style="vertical-align: middle;"><?php echo $val->recIntervalDescDutch; ?></td>
                                <td title="<?php echo $val->recIntervalTimeMin.":".$val->recIntervalTimeSec; ?>"  style="vertical-align: middle;"><?php echo $val->recIntervalTimeMin.":".$val->recIntervalTimeSec; ?></td>
                                <td style="vertical-align: middle;"><?php if(isset($val->recIntervalDateCreated)){echo date(ADMIN_DATE_FORMAT, strtotime($val->recIntervalDateCreated));} ?></td> 
                                <td style="vertical-align: middle;">
                                    <select class="selIntervalActions" id="<?php echo $val->recIntervalId; ?>" name="interval">
                                        <option value="">Actions</option>   
                                        <option value="editInterval">Edit</option>
                                        <option value="deleteInterval">Delete</option>
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
		$('#cmbIntervalActions').on('change', function () {
			var vale = $(this).val();
			if ($(".selectRow:checked").length < 1) {
				switch (vale) {
					case 'deleteInterval':
					case 'active':
					case 'inactive':
						$("#cmbActions option[selected]").removeAttr("selected");
						$("#cmbActions option[value='']").attr("selected", "selected");
						appMessage('You have to select some  ' + $(this).attr('name') + '(s)' +' before any action can be performed on them.', 'neg');
						break;
				}
				return false;
			} else if (vale == 'deleteInterval' && !confirm('Are you sure you want to delete selected ' + $(this).attr('name') + '(s)?')) {
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
		
		
		$('.selIntervalActions').change(function () {
			var vale = $(this).val();
			if (vale.length != 0)
			{
				if (vale == 'deleteInterval' && !confirm('Are you sure you want to delete selected interval?')) {
					$(".selIntervalActions option[selected]").removeAttr("selected");
					$(".selIntervalActions option[value='']").attr("selected", "selected");
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
	});
</script>
