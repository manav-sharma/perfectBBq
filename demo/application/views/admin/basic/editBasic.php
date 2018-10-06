<?php 
$data['scripts'][] = 'admin/basic/addEditBasic.js';
$data['scripts'][] = 'admin/jquery-te-1.4.0.min.js';
$data['validate']='frmBasic';
$this->load->view('admin/includes/header.php', $data);
$attributes = array('id' => 'frmBasic', 'name' => 'frmBasic');
echo form_open_multipart('admin/basic/editBasic/' . $detail['bbqId'], $attributes); ?>
<input type="hidden" name="txtId" value ="<?php echo $detail['bbqId']; ?>" /> <?php 
?>
 
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/basic'); ?>">BBQ Basic</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
	<div class="column2"> 
		<a class="btn" href="<?php echo site_url('admin/basic'); ?>">Back</a>  
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
							<input type="text" data-errormessage-value-missing="This field is required!" class="validate[required]" size="26" name="txtTitleEng" id="txtTitleEng" maxlength="60" value="<?=$detail['bbqTitleEng']?>"/>
						</td>
						<td>&nbsp;</td>
						<td scope="col"><label>Title (In German):</label></td>
                        <td scope="col">
							<input type="text" data-errormessage-value-missing="This field is required!" class="validate[required]" size="26" name="txtTitleDutch" id="txtTitleDutch" maxlength="60" value="<?=$detail['bbqTitleDutch']?>" />
						</td>
                    </tr>
                    
                    <tr>
                        <td scope="col"><label>Long Description (In English):</label></td>
                        <td scope="col">
							<textarea name="txtLongDescEng" id="txtLongDescEng"><?=$detail['bbqLongDescEng']?></textarea>
						</td>
						<td>&nbsp;</td>
						<td scope="col"><label>Long Description (In German):</label></td>
                        <td scope="col">
							<textarea name="txtLongDescDutch" id="txtLongDescDutch"><?=$detail['bbqLongDescDutch']?></textarea>
						</td>
                    </tr>
                    
                    <tr>
                        <td scope="col"><label>Short Description (In English):</label></td>
                        <td scope="col">
							<textarea name="txtShortDescEng" id="txtShortDescEng"><?=$detail['bbqShortDescEng']?></textarea>
						</td>
						<td>&nbsp;</td>
						<td scope="col"><label>Short Description (In German):</label></td>
                        <td scope="col">
							<textarea name="txtShortDescDutch" id="txtShortDescDutch"><?=$detail['bbqShortDescDutch']?></textarea>
						</td>
                    </tr>
                    
                    <tr>
                        <td scope="col"><label>Image:</label></td>
                        <td scope="col">
							<input type="file" name="txtImage" id="txtImage" class="validate[funcCall[validateVideoType]]" />
						</td>
                    </tr>
                    
                    
                    <tr>
						<td scope="col"><label>Ingredients:</label></td>
                        <table id="allIng">
							<tr>
								<th>English</th>
								<th>German</th>
								<th></th>
							</tr>
							<?php 
							$firstIngEng = "";
							$firstIngDutch = "";
							if (count($basicIng) > 0) {
								$firstIngEng = $basicIng[0]->bbqIngNameEng;
								$firstIngDutch = $basicIng[0]->bbqIngNameDutch;
							}
							?>
							<tr>
								<td><input class="textIng" type="text" size="26" name="txtIngEng[]" maxlength="60" value="<?=$firstIngEng?>" /></td>
								<td><input class="textIng" type="text" size="26" name="txtIngDutch[]" maxlength="60" value="<?=$firstIngDutch?>" /></td>
								<td><a id="addMoreIng" href="javascript:void(0);">Add More</a></td>
							</tr>
							<?php 
							if (count($basicIng) > 1) {
								foreach ($basicIng as $key => $val) {
									if ($key == 0) {
										continue;
									}
									?>
									<tr>
										<td><input class="textIng" type="text" size="26" name="txtIngEng[]" maxlength="60" value="<?=$val->bbqIngNameEng?>" /></td>
										<td><input class="textIng" type="text" size="26" name="txtIngDutch[]" maxlength="60" value="<?=$val->bbqIngNameDutch?>" /></td>
										<td><a class="deleteIng" href="javascript:void(0);">Delete</a></td>
									</tr>
									<?php
								}
							}
							?>
                        </table>
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
<link rel="stylesheet" href="<?php echo CSS_PATH . 'css/jquery-te-1.4.0.css'; ?>" type="text/css" />

<script>
	$(document).ready(function(){
		$("#txtLongDescEng").jqte(); 
		$("#txtShortDescEng").jqte(); 
		$("#txtLongDescDutch").jqte(); 
		$("#txtShortDescDutch").jqte(); 
	});
</script>
