<?php
$data['scripts'][] = 'admin/recipe/addEditRecipe.js';
$data['scripts'][] = 'admin/jquery-te-1.4.0.min.js';
$data['validate']='frmRecipe';
$this->load->view('admin/includes/header.php', $data);

$attributes = array('id' => 'frmRecipe', 'name' => 'frmRecipe');
echo form_open_multipart('admin/recipe/addRecipe', $attributes);
?> 
 
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/recipe'); ?>">Recipies</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
	<div class="column2"> 
		<a class="btn" href="<?php echo site_url('admin/recipe'); ?>">Back</a>  
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
                        <td scope="col"><label>Short Description (In English):</label></td>
                        <td scope="col">
							<textarea name="txtDescEng" id="txtDescEng"></textarea>
						</td>
						<td>&nbsp;</td>
						<td scope="col"><label>Short Description (In German):</label></td>
                        <td scope="col">
							<textarea name="txtDescDutch" id="txtDescDutch"></textarea>
						</td>
                    </tr>
                    
                    <tr class="long_desc">
                        <td scope="col"><label>Long Description (In English):</label></td>
                        <td scope="col">
							<textarea name="txtLongDescEng" id="txtLongDescEng"></textarea>
						</td>
						<td>&nbsp;</td>
						<td scope="col"><label>Long Description (In German):</label></td>
                        <td scope="col">
							<textarea name="txtLongDescDutch" id="txtLongDescDutch"></textarea>
						</td>
                    </tr>
                    
                    <tr class="long_desc">
                        <td scope="col"><label>Temprature:</label></td>
                        <td scope="col">
							<input type="checkbox" name="txtTemprature" id="txtTemprature" value="yes"/>
						</td>
						<td>&nbsp;</td>
						<td scope="col"><label>Thickness:</label></td>
                        <td scope="col">
							<input type="checkbox" name="txtThickness" id="txtThickness" value="yes"/>
						</td>
                    </tr>
                    
                    <tr id="defaultTime">
                        <td scope="col"><label>Time (In Min):</label></td>
                        <td scope="col">
							<input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[custom[onlyNumber],min[0]]" size="10" id="recipeMin" name="recipeMin" value=""/>
						</td>
						<td>&nbsp;</td>
						<td scope="col"><label>Time (In Sec):</label></td>
                        <td scope="col">
							<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[custom[onlyNumber],min[0],max[60]]" size="10" id="recipeSec" name="recipeSec" value=""/>
						</td>
                    </tr>
                    
                    <tr id="defaultTemp">
                        <td scope="col"><label>Temperature:</label></td>
                        <td scope="col">
							<input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[custom[onlyNumber]]" size="10" id="recipeTemp" name="recipeTemp" value=""/>
						</td>
						<td>&nbsp;</td>
						<td scope="col"><label>Bowl Temperature:</label></td>
                        <td scope="col">
							<input type="text" data-errormessage-value-missing="Bowl temperature are required!" class="validate[custom[onlyNumber]]" size="10" id="recipeBowlTemp" name="recipeBowlTemp" value=""/>
						</td>
                    </tr>
                    
                    <tr>
						<td scope="col"><label>Category (English / German):</label></td>
                        <td scope="col">
							<select name="txtCatId" id="txtCatId" class="validate[required]">
								<option value="">Select Category</option>
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
							<input type="file" name="txtImage" id="txtImage" class="validate[required,funcCall[validateVideoType]]" />
						</td>
                    </tr>
                    
                    
                    <tr>
						<td scope="col"><label>Grilled:</label></td>
                        <td scope="col">
							<input type="checkbox" name="txtGrilled" id="txtGrilled" value="1" />
						</td>
                    </tr>
                    
                    <tr>
						<td scope="col"><label>Ingredients:</label></td>
                        <table id="allIng">
							<tr>
								<th class="heading">English</th>
								<th class="heading">German</th>
								<th></th>
							</tr>
							<tr>
								<td><input class="textIng" type="text" size="26" name="txtIngEng[]" maxlength="60" value="" /></td>
								<td><input class="textIng" type="text" size="26" name="txtIngDutch[]" maxlength="60" value="" /></td>
								<td><a id="addMoreIng" href="javascript:void(0);">Add More</a></td>
							</tr>
                        </table>
                    </tr>
                    
                </table>                                
            </dd>
            <div class="clear"></div>
        </dl>
        <div class="baseStrip">
            <a class="btn" href="<?php echo site_url('admin/recipe'); ?>">Back</a>
            <input type="submit" value="Save" class="btn" name="btnSubmit" id="btnSubmit" />    
        </div>    
	</div>
</div>
</form>
<?php $this->load->view("admin/includes/footer"); ?>
<link rel="stylesheet" href="<?php echo CSS_PATH . 'css/jquery-te-1.4.0.css'; ?>" type="text/css" />

<script>
	$(document).ready(function(){
		$("#txtDescEng").jqte(); 
		$("#txtDescDutch").jqte(); 
		$("#txtLongDescEng").jqte(); 
		$("#txtLongDescDutch").jqte();
		
		//custom removal of class start 
		$("#txtTemprature, #txtThickness").click(function(){
			var checkTempConfirm = $("#txtTemprature").is(":checked");
			var checkThickConfirm = $("#txtThickness").is(":checked");
			if (checkTempConfirm == true || checkThickConfirm == true) {
				$("#defaultTime").hide();
				$("#defaultTemp").hide();
				$("#recipeMin").val('');
				$("#recipeSec").val('');
				$("#recipeTemp").val('');
				$("#recipeBowlTemp").val('');
			} else {
				$("#defaultTime").show();
				$("#defaultTemp").show();
				$("#recipeMin").val('');
				$("#recipeSec").val('');
				$("#recipeTemp").val('');
				$("#recipeBowlTemp").val('');
			}
		});
		//custom removal of class end 
	});
</script>
