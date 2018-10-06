<?php
$data['scripts'][] = 'admin/recipe/addEditInterval.js';
$data['scripts'][] = 'admin/jquery-te-1.4.0.min.js';
$data['validate']='frmInterval';
$this->load->view('admin/includes/header.php', $data);

$attributes = array('id' => 'frmInterval', 'name' => 'frmInterval');
echo form_open_multipart('admin/recipe/addInterval/'.$timeId, $attributes);
?> 
 <input type="hidden" value="<?=$recTotalSec?>" id="rec_total_time">
 <input type="hidden" value="<?=$timeId?>" id="timeId" name="timeId">
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/recipe'); ?>">Recipe (<?=$recipeName?>)</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/recipe/timing/'.$recId); ?>">Timing</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/recipe/interval/'.$timeId); ?>">Interval</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
	<div class="column2"> 
		<a class="btn" href="<?php echo site_url('admin/recipe/interval/'.$timeId); ?>">Back</a>  
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
							<input type="text" data-errormessage-value-missing="This field is required!" class="validate[required]" size="26" name="txtTitleEng" id="txtTitleEng" maxlength="60" value=""/>
						</td>
						<td>&nbsp;</td>
						<td scope="col"><label>Title (In German):</label></td>
                        <td scope="col">
							<input type="text" data-errormessage-value-missing="This field is required!" class="validate[required]" size="26" name="txtTitleDutch" id="txtTitleDutch" maxlength="60" value="" />
						</td>
                    </tr>
                    
                    <tr>
                        <td scope="col"><label>Description (In English):</label></td>
                        <td scope="col">
							<textarea name="txtDescEng" id="txtDescEng"></textarea>
						</td>
						<td>&nbsp;</td>
						<td scope="col"><label>Description (In German):</label></td>
                        <td scope="col">
							<textarea name="txtDescDutch" id="txtDescDutch"></textarea>
						</td>
                    </tr>
                    
                    <tr>
                        <td scope="col"><label>Recipe Time (Min:Sec):</label></td>
                        <td scope="col"><?=$actualRecipeTime?></td>
                    </tr>
                    
                    <tr>
						<td scope="col"><label>Time (In Min):</label></td>
                        <td scope="col">
							<input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min" value=""/>
						</td>
						<td>&nbsp;</td>
                        <td scope="col"><label>Time (In Sec):</label></td>
                        <td scope="col">
							<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec" value=""/>
						</td>
                    </tr>
                </table>
                <input type="hidden" value="" class="rec_total_sec" name="recTotalSec">                                
            </dd>
            <div class="clear"></div>
        </dl>
        <div class="baseStrip">
            <a class="btn" href="<?php echo site_url('admin/recipe/interval/'.$timeId); ?>">Back</a> 
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
	});
</script>
