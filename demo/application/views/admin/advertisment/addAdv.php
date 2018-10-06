<?php 
$data['scripts'][] = 'admin/advertisment/addAdv.js';
$data['validate']='frmAdv';
$this->load->view('admin/includes/header.php', $data);

$attributes = array('id' => 'frmAdv', 'name' => 'frmAdv');
echo form_open_multipart('admin/advertisment/addAdv', $attributes);
?> 
 
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/advertisment'); ?>">Advertisement</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
	<div class="column2"> 
		<a class="btn" href="<?php echo site_url('admin/advertisment'); ?>">Back</a>  
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
                <table border="0" cellspacing="0" cellpadding="0" style=" margin-top:18px" class="form" >
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
							<input type="text" data-errormessage-value-missing="This field is required!" class="validate[required,ajax[checkTitleEngExist]]" size="26" name="txtTitleEng" id="txtTitleEng" maxlength="60" value=""/>
						</td>
						<td>&nbsp;</td>
						<td scope="col"><label>Title (In German):</label></td>
                        <td scope="col">
							<input type="text" data-errormessage-value-missing="This field is required!" class="validate[required,ajax[checkTitleDutchExist]]" size="26" name="txtTitleDutch" id="txtTitleDutch" maxlength="60" value="" />
						</td>
                    </tr>
                    
                    <tr>
						<td scope="col"><label>Category (English / German):</label></td>
                        <td scope="col">
							<select name="txtCatId[]" id="txtCatId" class="validate[required]" multiple>
								<?php if (!empty($allCat)) { ?>
								<?php foreach ($allCat as $catKey => $catVal) { $parentCat = $catVal->catTitleEng." / ".$catVal->catTitleDutch;?>
									<option value="<?php echo $catVal->catId; ?>"><?php echo $parentCat; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</td>
						<td>&nbsp;</td>
                        <td scope="col"><label>Image:</label></td>
                        <td scope="col">
							<input type="file" name="txtImage" id="txtImage" data-errormessage-value-missing="Image is required!" class="validate[required,funcCall[validateVideoType]]" />
						</td>
                    </tr>
                </table>                                
            </dd>
            <div class="clear"></div>
        </dl>
        <div class="baseStrip">
            <a class="btn" href="<?php echo site_url('admin/basic'); ?>">Back</a>
            <input type="submit" value="Save" class="btn" name="btnSubmit" id="btnSubmit" />    
        </div>    
	</div>
</div>
</form>
<?php $this->load->view("admin/includes/footer"); ?>
