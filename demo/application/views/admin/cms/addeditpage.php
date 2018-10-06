<?php 
$data['scripts'][] = 'admin/cmspages.js';
$data['scripts'][] = 'tiny_mce/tiny_mce.js';
$data['scripts'][] = 'tiny_mce/addediter.js';
$data['validate']	='addeditpage';
$this->load->view('admin/includes/header.php', $data);
if (empty($detail)) {
    $attributes = array('id' => 'addeditpage', 'name' => 'addeditpage');
    echo form_open_multipart('admin/pages/addpage', $attributes);
} else {
    $attributes = array('id' => 'addeditpage', 'name' => 'addeditpage');
    echo form_open_multipart('admin/pages/editpage/' . $detail['id'], $attributes); ?>
    <input type ="hidden" name="cmsid" value ="<?php echo $detail['id']; ?>" /> <?php 
}  
?>   
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/pages'); ?>">Page</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
	<div class="column2"> 
		<a class="btn" href="<?php echo site_url('admin/pages'); ?>">Back</a>  
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
    <div id="tab1" class="column2">   
        <dl class="formBox">
            <dt><?php echo ucwords(isset($title) ? $title : ''); ?></dt>
            <dd>  
                <table border="0" cellspacing="0" cellpadding="0" style=" margin-top:18px" class="form" >
                    <colgroup>
                        <col width="170"/>
                        <col width="180"/> 
                        <col width="180"/>
                    </colgroup>
                    <tr>
                        <td scope="col"><label>Page Title (In Eng):</label></td>
                        <td scope="col"><input type="text"  class="validate[required]" data-errormessage-value-missing="This field is required!" size="26" name="txttileEng" id="txttileEng" maxlength="60" value="<?php echo set_value('txttileEng', (isset($detail['cmsTitleEng']) ? $detail['cmsTitleEng'] : '')); ?>" /></td> 
                        <td>&nbsp;</td>
                        <td scope="col"><label>Page Title (In German):</label></td>
                        <td scope="col"><input type="text"  class="validate[required]" data-errormessage-value-missing="This field is required!" size="26" name="txttileDutch" id="txttileDutch" maxlength="60" value="<?php echo set_value('txttileDutch', (isset($detail['cmsTitleDutch']) ? $detail['cmsTitleDutch'] : '')); ?>" /></td> 
                        
                    </tr>
                    <tr>
						<td scope="col"><label>Status:</label></td>
						<td scope="col">
							<input name="txtcmsStatus" value="1" type="radio" checked="checked" <?php echo (isset($detail) && $detail['cmsStatus'] == '1') ? "checked = checked" : ""; ?> class="radioBtn" <?php echo set_radio('txtcmsStatus', '1'); ?> />
							Active
							<input name="txtcmsStatus" value="0" type="radio" <?php echo (isset($detail) && $detail['cmsStatus'] == '0') ? "checked = checked" : ""; ?> class="radioBtn" <?php echo set_radio('txtcmsStatus', '0'); ?> />
							Inactive
                        </td>
                    </tr>
                    
					<tr> 
					<td colspan="5">&nbsp;</td>
					</tr>
                    <tr>  
						<td scope="col"><label> Description (In Eng):</label>
						</td> 
						<td colspan="4" scope="col"> 
							<textarea style="width:445px;height:100%" name="cmsContentEng" id="cmsContentEng"  cols="100" rows="17" class="validate[required]"><?php echo set_value('cmsContentEng', (isset($detail['cmsContentEng']) ? $detail['cmsContentEng'] : '')); ?></textarea>
						</td>
                    </tr>
                    <tr>  
						<td scope="col"><label> Description (In German):</label>
						</td> 
						<td colspan="4" scope="col"> 
							<textarea style="width:445px;height:100%" name="cmsContentDutch" id="cmsContentDutch"  cols="100" rows="17" class="validate[required]"><?php echo set_value('cmsContentDutch', (isset($detail['cmsContentDutch']) ? $detail['cmsContentDutch'] : '')); ?></textarea>
						</td>
                    </tr>   
					
                </table>                                
            </dd>
            <div class="clear"></div>
        </dl>
        <div class="baseStrip">
            <a class="btn" href="<?php echo site_url('admin/users'); ?>">Back</a>
                <input type="submit" value="Save" class="btn" name="btnSubmit" id="btnSubmit" />    
        </div>    
	</div>
</div>
</form>
<?php $this->load->view("admin/includes/footer"); ?>
