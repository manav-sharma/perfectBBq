<?php
$data['scripts'][] = 'admin/recipe/addEditTiming.js';
$data['validate']='frmTiming';
$this->load->view('admin/includes/header.php', $data);

$attributes = array('id' => 'frmTiming', 'name' => 'frmTiming');
echo form_open_multipart('admin/recipe/addTiming', $attributes);
?> 
 <input type="hidden" name="recipeId" value ="<?php echo $recipeId; ?>" />
 <input type="hidden" name="tempMode" value ="<?php echo $tempMode; ?>" />
 <input type="hidden" name="thickMode" value ="<?php echo $thickMode; ?>" />
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/recipe'); ?>">Recipe (<?=$recipeName?>)</a>
            <span class="pipe">&#187;</span><a href="<?php echo site_url('admin/recipe/timing/'.$recipeId); ?>"> Timing</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
	<div class="column2"> 
		<a class="btn" href="<?php echo site_url('admin/recipe/timing/'.$recipeId); ?>">Back</a>  
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
                <table border="2" cellspacing="0" cellpadding="0" style=" margin-top:18px" class="form  recipe-table" >
                    <colgroup>
                        <col width="170"/>
                        <col width="100"/>
                        <col width="100"/>
                        <col width="100"/>
                    </colgroup>
                    <tr>
						<th>Title</th>
						<th>Time(In Min)</th>
						<th>Time(In Sec)</th>
						<th>Temperature</th>
						<th>Bowl Temperature</th>
                    </tr>
                    
                    <?php if (trim($tempMode) == 'yes' && trim($thickMode) == 'yes') { ?>
						<!----Html if both temparature and thickness need to be added ends ----->
						<!--------------Rare ----------->
						<tr>
							<td scope="col"><input type="hidden" value="1cm-Rare" name="title[]"><label>1cm Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="1.5cm-Rare" name="title[]"><label>1.5cm Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="2cm-Rare" name="title[]"><label>2cm Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
					   </tr>
						<tr>
							<td scope="col"><input type="hidden" value="2.5cm-Rare" name="title[]"><label>2.5cm Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="3cm-Rare" name="title[]"><label>3cm Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="3.5cm-Rare" name="title[]"><label>3.5cm Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="4cm-Rare" name="title[]"><label>4cm Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="4.5cm-Rare" name="title[]"><label>4.5cm Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="5cm-Rare" name="title[]"><label>5cm Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						
						<!--------------Medium Rare ----------->
						<tr>
							<td scope="col"><input type="hidden" value="1cm-Medium rare" name="title[]"><label>1cm Medium Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="1.5cm-Medium rare" name="title[]"><label>1.5cm Medium Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="2cm-Medium rare" name="title[]"><label>2cm Medium Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
					   </tr>
						<tr>
							<td scope="col"><input type="hidden" value="2.5cm-Medium rare" name="title[]"><label>2.5cm Medium Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="3cm-Medium rare" name="title[]"><label>3cm Medium Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="3.5cm-Medium rare" name="title[]"><label>3.5cm Medium Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="4cm-Medium rare" name="title[]"><label>4cm Medium Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="4.5cm-Medium rare" name="title[]"><label>4.5cm Medium Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="5cm-Medium rare" name="title[]"><label>5cm Medium Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						
						<!--------------Medium ----------->
						<tr>
							<td scope="col"><input type="hidden" value="1cm-Medium" name="title[]"><label>1cm Medium:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="1.5cm-Medium" name="title[]"><label>1.5cm Medium:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="2cm-Medium" name="title[]"><label>2cm Medium:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
					   </tr>
						<tr>
							<td scope="col"><input type="hidden" value="2.5cm-Medium" name="title[]"><label>2.5cm Medium:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="3cm-Medium" name="title[]"><label>3cm Medium:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="3.5cm-Medium" name="title[]"><label>3.5cm Medium:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="4cm-Medium" name="title[]"><label>4cm Medium:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="4.5cm-Medium" name="title[]"><label>4.5cm Medium:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="5cm-Medium" name="title[]"><label>5cm Medium:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						
						<!--------------Medium Well ----------->
						<tr>
							<td scope="col"><input type="hidden" value="1cm-Medium Well" name="title[]"><label>1cm Medium Well:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="1.5cm-Medium Well" name="title[]"><label>1.5cm Medium Well:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="2cm-Medium Well" name="title[]"><label>2cm Medium Well:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
					   </tr>
						<tr>
							<td scope="col"><input type="hidden" value="2.5cm-Medium Well" name="title[]"><label>2.5cm Medium Well:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="3cm-Medium Well" name="title[]"><label>3cm Medium Well:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="3.5cm-Medium Well" name="title[]"><label>3.5cm Medium Well:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="4cm-Medium Well" name="title[]"><label>4cm Medium Well:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="4.5cm-Medium Well" name="title[]"><label>4.5cm Medium Well:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="5cm-Medium Well" name="title[]"><label>5cm Medium Well:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						
						<!--------------Well Done ----------->
						<tr>
							<td scope="col"><input type="hidden" value="1cm-Well Done" name="title[]"><label>1cm Well Done:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="1.5cm-Well Done" name="title[]"><label>1.5cm Well Done:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="2cm-Well Done" name="title[]"><label>2cm Well Done:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
					   </tr>
						<tr>
							<td scope="col"><input type="hidden" value="2.5cm-Well Done" name="title[]"><label>2.5cm Well Done:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="3cm-Well Done" name="title[]"><label>3cm Well Done:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="3.5cm-Well Done" name="title[]"><label>3.5cm Well Done:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="4cm-Well Done" name="title[]"><label>4cm Well Done:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="4.5cm-Well Done" name="title[]"><label>4.5cm Well Done:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="5cm-Well Done" name="title[]"><label>5cm Well Done:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<!----Html if both temparature and thickness need to be added ends ----->
					<?php } else if (trim($tempMode) == 'yes') { ?>	
						<tr>
							<td scope="col"><input type="hidden" value="Rare" name="title[]"><label>Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="Medium rare" name="title[]"><label>Medium Rare:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="Medium" name="title[]"><label>Medium:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="Medium Well" name="title[]"><label>Medium Well:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="Well Done" name="title[]"><label>Well Done:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
					<?php } else if (trim($thickMode) == 'yes') { ?>
						<tr>
							<td scope="col"><input type="hidden" value="1cm" name="title[]"><label>1cm:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="1.5cm" name="title[]"><label>1.5cm:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="2cm" name="title[]"><label>2cm:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="2.5cm" name="title[]"><label>2.5cm:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="3cm" name="title[]"><label>3cm:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="3.5cm" name="title[]"><label>3.5cm:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="4cm" name="title[]"><label>4cm:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="4.5cm" name="title[]"><label>4.5cm:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
						<tr>
							<td scope="col"><input type="hidden" value="5cm" name="title[]"><label>5cm:</label></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Minutes are required!" class="validate[required,custom[onlyNumber],min[0]] minData" size="10" name="min[]" value=""/></td>
							<td scope="col">
								<input type="text" data-errormessage-value-missing="Seconds are required!" class="validate[required,custom[onlyNumber],min[0],max[60]] secData" size="10" name="sec[]" value=""/>
								<input readonly class="rec_total_sec" type="hidden" name="totalSec[]" value=""/>
							</td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="temp[]" value=""/></td>
							<td scope="col"><input type="text" data-errormessage-value-missing="Bowl temperature is required!" class="validate[required,custom[onlyNumber],min[1]]" size="10" name="bowlTemp[]" value=""/></td>
						</tr>
					<?php } ?>
                </table>                                
            </dd>
            <div class="clear"></div>
        </dl>
        <div class="baseStrip">
            <a class="btn" href="<?php echo site_url('admin/recipe/timing/'.$recipeId); ?>">Back</a>
            <input type="submit" value="Save" class="btn" name="btnSubmit" id="btnSubmit" />    
        </div>    
	</div>
</div>
</form>
<?php $this->load->view("admin/includes/footer"); ?>

