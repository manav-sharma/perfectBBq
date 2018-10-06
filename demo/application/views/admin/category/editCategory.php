<?php

$data['scripts'][] = 'admin/category/addEditCatgeory.js';
$data['validate']='frmCategory';
$this->load->view('admin/includes/header.php', $data);
$attributes = array('id' => 'frmCategory', 'name' => 'frmCategory');
echo form_open_multipart('admin/category/editCategory/' . $detail['catId'], $attributes); ?>
<input type ="hidden" name="txtId" value ="<?php echo $detail['catId']; ?>" /> <?php 
?> 
 
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/category'); ?>">Categories</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
	<div class="column2"> 
		<a class="btn" href="<?php echo site_url('admin/category'); ?>">Back</a>  
		<input type="submit" value="Save" class="btn" name="btnSubmit1" id="btnSubmit1" />                    
	</div>   
</div>
<div class="clear"></div>
</div>   
<div id="pageContent">
    <?php
    $value = $this->session->flashdata('item');
    if (!empty($value)) {
        echo $value;
    } else {
        if (isset($msg) && isset($msgType) && !empty($msg) && !empty($msgType)) {
            echo $this->utility->show_message($msg, $msgType);
        } else {
            echo $this->utility->show_message();
        }
    }
    ?>
    <div style="width:796px;" id="tab1" class="column2">   
        <dl class="formBox">
            <dt><?php echo ucwords(isset($title) ? $title : ''); ?></dt>
            <dd>
                <table border="0" cellspacing="0" cellpadding="0" style="margin-top:18px" class="form" >
                    <colgroup>
                        <col width="170"/>
                        <col width="180"/>
                        <col width="50"/>
                        <col width="170"/>
                        <col width="180"/>
                    </colgroup>
                    
                    <tr>
                        <td scope="col"><label>Title (In Eng):</label></td>
                        <td scope="col">
							<input type="text" data-errormessage-value-missing="This field is required!" class="validate[required]" size="26" name="txtTitleEng" id="txtTitleEng" maxlength="60" value="<?=$detail['catTitleEng']?>" />
						</td>
						<td>&nbsp;</td>
						<td scope="col"><label>Title (In German):</label></td>
                        <td scope="col">
							<input type="text" data-errormessage-value-missing="This field is required!" class="validate[required]" size="26" name="txtTitleDutch" id="txtTitleDutch" maxlength="60" value="<?=$detail['catTitleDutch']?>" />
						</td>
                    </tr>
                    
                    <tr>
                        <td scope="col"><label>Parent Category (English / German):</label></td>
                        <td scope="col">
							<select name="txtCatParentId" id="txtCatParentId">
								<option value="0">Select Category</option>
								<?php if (!empty($allCat)) { ?>
								<?php foreach ($allCat as $catKey => $catVal) { $parentCat = $catVal->catTitleEng." / ".$catVal->catTitleDutch;?>
									<option <?=($detail['catParentId'] == $catVal->catId) ? "selected" : ""?> value="<?php echo $catVal->catId; ?>"><?php echo $parentCat; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</td>
						<td>&nbsp;</td>
						<td scope="col"><label>Image:</label></td>
                        <td scope="col">
							<input type="file" name="txtAdvImage" id="txtAdvImage" class="validate[funcCall[validateVideoType]]" />
							<img src="<?=SITE_URL."bbq_images/".$detail['catAdvImage'];?>">
						</td>
                    </tr>
                    
                    
                    <tr>
						<td scope="col">
							<label>Advertisement Mode:</label>
						</td>
						<td scope="col">
							<input name="txtAdvSwitch" value="1" type="radio" <?=($detail['catAdvSwitch'] == 1) ? 'checked="checked"' : ""?> class="radioBtn" />On
							<input name="txtAdvSwitch" value="0" type="radio" <?=($detail['catAdvSwitch'] == 0) ? 'checked="checked"' : ""?> class="radioBtn" />Off
						</td>
						<td>&nbsp;</td>
						<td scope="col">
							<label>Layout:</label>
						</td>
						<td scope="col">
							<select data-errormessage-value-missing="This field is required!" class="validate[required]" name="txtLayout" id="txtLayout">
								<option value="">Select Layout</option>
								<option <?=($detail['catLayout'] == "Grid") ? 'selected="selected"' : ""?> value="Grid">Grid</option>
								<option <?=($detail['catLayout'] == "List") ? 'selected="selected"' : ""?> value="List">List</option>
							</select>
						</td>
					</tr>
					
					<tr>
						<td scope="col">
							<label>Position:</label>
						</td>
						<td scope="col">
							<input type="text" data-errormessage-value-missing="This field is required!" class="validate[required,custom[onlyNumber]]" name="txtPosition" id="txtPosition" maxlength="60" value="<?=$detail['catPosition']?>" />
						</td>
					</tr>
                    
                </table>                                
            </dd>
            <div class="clear"></div>
        </dl>
        <div class="baseStrip">
            <a class="btn" href="<?php echo site_url('admin/category'); ?>">Back</a>
            <input type="submit" value="Save" class="btn" name="btnSubmit" id="btnSubmit" />    
        </div>    
	</div>
</div>
</form>
<?php $this->load->view("admin/includes/footer"); ?>
