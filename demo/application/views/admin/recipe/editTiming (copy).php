<?php
$data['scripts'][] = 'admin/recipe/addEditTiming.js';
$data['validate']='frmTiming';
$this->load->view('admin/includes/header.php', $data);

$attributes = array('id' => 'frmTiming', 'name' => 'frmTiming');
echo form_open_multipart('admin/recipe/editTiming', $attributes);
?> 
 <input type="hidden" name="timeId" value ="<?php echo $detail->recTimeId; ?>" />
 <input type="hidden" name="recId" value ="<?php echo $detail->recId; ?>" />
 <input type="hidden" name="tempMode" value ="<?php echo $tempMode; ?>" />
 <input type="hidden" name="thickMode" value ="<?php echo $thickMode; ?>" />
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/recipe'); ?>">Recipe (<?=$recipeName?>)</a>
            <span class="pipe">&#187;</span><a href="<?php echo site_url('admin/recipe/timing/'.$detail->recId); ?>"> Timing</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
	<div class="column2"> 
		<a class="btn" href="<?php echo site_url('admin/recipe/timing/'.$detail->recId); ?>">Back</a>  
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
                <table border="2" cellspacing="0" cellpadding="0" style=" margin-top:18px" class="form" >
                    <colgroup>
                        <col width="170"/>
                        <col width="100"/>
                        <col width="100"/>
                        <col width="100"/>
                    </colgroup>
                    <tr>
						<?php if ($tempMode != "" || $thickMode != "") { ?>
						<th>Title</th>
						<?php } ?>
						<th>Time(In Min)</th>
						<th>Time(In Sec)</th>
						<th>Temperature</th>
						<th>Bowl Temperature</th>
                    </tr>
                    <tr>
						<?php
							if (trim($tempMode) == 'yes' && trim($thickMode) == 'yes') {
								$titleVal = $detail->recThickness."-".$detail->recCookingStyle;
							} else if (trim($tempMode) == 'yes') {
								$titleVal = $detail->recCookingStyle;
							} else if (trim($thickMode) == 'yes') {
								$titleVal = $detail->recThickness;
							}
						?>
						<?php if ($tempMode != "" || $thickMode != "") { ?>
                        <td scope="col"><input type="hidden" value="<?php echo $titleVal; ?>" name="title"><label><?php echo $titleVal.":"; ?></label></td>
                        <?php } ?>
                        <td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] minData" size="10" name="min" value="<?php echo $detail->recTimeMin; ?>"/></td>
                        <td scope="col">
							<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec" value="<?php echo $detail->recTimeSec; ?>"/>
							<input readonly class="rec_total_sec" type="hidden" name="totalSec" value="<?php echo $detail->recTotalSec; ?>"/>
						</td>
                        <td scope="col"><input type="text" data-errormessage-value-missing="Temprature are required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp" value="<?php echo $detail->recTemp; ?>"/></td>
                        <td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp" value="<?php echo $detail->recBowlTemp; ?>"/></td>
                    </tr>
                </table>                                
            </dd>
            <div class="clear"></div>
        </dl>
        <div class="baseStrip">
            <a class="btn" href="<?php echo site_url('admin/recipe/timing/'.$detail->recId); ?>">Back</a>
            <input type="submit" value="Save" class="btn" name="btnSubmit" id="btnSubmit" />    
        </div>    
	</div>
</div>
</form>
<?php $this->load->view("admin/includes/footer"); ?>

