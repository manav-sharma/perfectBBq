<?php 
$data['scripts'][] = 'admin/cmsmenus.js';
$data['validate']	='addeditmenu';
$this->load->view('admin/includes/header.php', $data);
if (empty($detail)) {
    $attributes = array('id' => 'addeditmenu', 'name' => 'addeditmenu');
    echo form_open_multipart('admin/menus/addmenu', $attributes);
} else {
    $attributes = array('id' => 'addeditmenu', 'name' => 'addeditmenu');
    echo form_open_multipart('admin/menus/editmenu/' . $detail['mnuId'], $attributes); ?>
    <input type ="hidden" name="mnuId" value ="<?php echo $detail['mnuId']; ?>" /> <?php 
}  
?>   
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/menu'); ?>">Menu</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
	<div class="column2"> 
		<a class="btn" href="<?php echo site_url('admin/menus'); ?>">Back</a>  
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
                        <col width="180"/>
                    </colgroup>
					
										
                    <tr>
                        <td scope="col"><label>Name:</label></td>
                        <td scope="col"><input type="text"  class="validate[required]" data-errormessage-value-missing="Name is required!" size="26" name="txtName" id="txtName" maxlength="60" value="<?php echo set_value('txtName', (isset($detail['mnuName']) ? $detail['mnuName'] : '')); ?>" /></td>
                        <td scope="col">&nbsp;</td>                        
                    </tr>
					<tr>
						<td scope="col"><label>Status:</label></td>
						<td scope="col">
							<input name="txtmnuStatus" value="1" type="radio" checked="checked" <?php echo (isset($detail) && $detail['mnuStatus'] == '1') ? "checked = checked" : ""; ?> class="radioBtn" <?php echo set_radio('txtmnuStatus', '1'); ?> />
							Active
							<input name="txtmnuStatus" value="0" type="radio" <?php echo (isset($detail) && $detail['mnuStatus'] == '0') ? "checked = checked" : ""; ?> class="radioBtn" <?php echo set_radio('txtmnuStatus', '0'); ?> />
							Inactive
                        </td>
					</tr>
                </table>                                
            </dd>
            <div class="clear"></div>
        </dl>
        <div class="baseStrip">
            <a class="btn" href="<?php echo site_url('admin/menus'); ?>">Back</a>
                <input type="submit" value="Save" class="btn" name="btnSubmit" id="btnSubmit" />    
        </div>    
	</div>
</div>
</form>
<?php $this->load->view("admin/includes/footer"); ?>