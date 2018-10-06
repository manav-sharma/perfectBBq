<?php 
//echo "<pre>";
//print_r($detail->countryId);
//print_r($roomcatsarr);
//exit;

$data['scripts'][] = 'admin/addeditusers.js';
$data['validate']='frmHotel';
$this->load->view('admin/includes/header.php', $data);

$attributes = array('id' => 'frmHotel', 'name' => 'frmEditUser');
echo form_open_multipart('admin/users/myHotelEdit/' . $detail->id, $attributes); ?>
<input type ="hidden" name="userid" value ="<?php echo $detail->id; ?>" /> 
 
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/users'); ?>">Users</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
	<div class="column2"> 
		<a class="btn" href="<?php echo site_url('admin/users'); ?>">Back</a>  
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
                
                <h3 style="margin-top:20px;">Basic Information</h3>
                <table border="0" cellspacing="0" cellpadding="0" style=" margin-top:18px" class="form" >
                    <colgroup>
                        <col width="170"/>
                        <col width="180"/>
                        <col width="50"/>
                        <col width="170"/>
                        <col width="180"/>
                    </colgroup>
                    <tr>
                        <td scope="col"><label>Hotel Name:</label></td>
                        <td scope="col"><input type="text" data-errormessage-value-missing="Hotel Name is required!" class="validate[required,custom[onlyLetterSp]]" size="26" name="hotelName" id="hotelName" maxlength="60" value="<?php echo isset($detail->usrFirstName) ? $detail->usrFirstName : ''; ?>" class="validate" /></td>
                        <td scope="col">&nbsp;</td>
                        <td scope="col"><label>Hotel Name (Arabic):</label></td>
                        <td scope="col"><input type="text" data-errormessage-value-missing="Hotel Name Arabic is required!" class="validate[required]" size="26" name="hotelNamearabic" id="hotelNamearabic" maxlength="60" value="<?php echo isset($detail->hotelNameArabic) ? $detail->hotelNameArabic : ''; ?>" class="validate"/></td>
                     </tr>
                    <tr>
						<td scope="col">
                                <label>Room Type</label>
                            </td>
                            <td scope="col">
                                <select class="validate[required]" data-errormessage-value-missing="Room type is required!" style="width:155px;" name="roomtype[]" id="roomtype" multiple = "multiple">
									<!--<option <?php //echo set_select('roomtype', '', TRUE); ?> value="">Select Room Type</option>-->
									<?php
									if (isset($roomcatsarr) && !empty($roomcatsarr)) {
										foreach ($roomcatsarr as $roomcat) {?>
										<option value="<?php echo $roomcat->roomCatId;?>" <?php if(isset($detail->roomCategoryId)){if(in_array($roomcat->roomCatId,explode(',',$detail->roomCategoryId))){?>selected<?php }}?>> <?php echo ucfirst($roomcat->roomCategory);?> </option>
										<?php }
									}
									?>                                    
								</select>
                            </td>
                            <td scope="col">&nbsp;</td>
                            
                            <td scope="col">
                                <label>Hotel Type</label>
                            </td>
                            <td scope="col">
                                <select class="validate[required]" data-errormessage-value-missing="Hotel type is required!" style="width:155px;" name="hoteltype" id="hoteltype" >
									<option <?php echo set_select('hoteltype', '', TRUE); ?> value="">Select Hotel Type</option>
									<?php
									if (isset($hotelTypesarr) && !empty($hotelTypesarr)) {
										foreach ($hotelTypesarr as $hotelType) {?>
											
											
											<option value = "<?php echo $hotelType->hotelTypeId;?>" <?php if(isset($detail->hotelTypeId) && $detail->hotelTypeId==$hotelType->hotelTypeId){?> selected<?php }?>><?php echo ucfirst($hotelType->hotelType);?></option>
										<?php }
									}
									?>                                    
								</select>
                            </td>
                            
                        </tr>
					<tr>
							<td scope="col"><label>Country</label></td>
							<td scope="col">
								<select class="validate[required]" data-errormessage-value-missing="Country is required!" style="width:155px;" name="cmbCountry" id="cmbCountry" >
									<option <?php echo set_select('cmbCountry', '', TRUE); ?> value="">Select Country</option>
									<?php
									if (isset($countries) && !empty($countries)) {
										foreach ($countries as $country) {
											if (isset($detail->countryId) && $detail->countryId == $country->geoId) {
												$sel = 'selected="selected"';
											}
											else {
												$sel = '';
											}
											echo '<option value="' . $country->geoId . '" ' . $sel . ' '.set_select('cmbCountry',$country->geoId ).'>' . ucfirst($country->geoName) . '</option>';
										}
									}
									?>                                    
								</select>
							</td>
							<td scope="col">&nbsp;</td>							
							<td scope="col"><label>Contact Number:</label></td>
							<td scope="col">
								<input type="text" size="26" class="validate[required,custom[onlyNumber]]" data-prompt-position="bottomRight:-60" name="usrContact" id="usrContact" maxlength="15" value="<?php echo set_value('usrContact', (isset($detail->hotelContact) ? $detail->hotelContact : '')); ?>" class="validate[required]" />
							</td>
						</tr>
						
						<tr>
							<td scope="col"><label id="lb_cmbCity">City</label></td>
							<td scope="col">
							  <input type="text" size="26" name="cmbCity" id="cmbCity" value="<?php echo set_value('cmbCity', (isset($detail->city) ? $detail->city : '')); ?>" class="">
							  <div id="tt_cmbCity" class="hidden">City is <strong>required.</strong></div>
							 </td>
							<td scope="col">&nbsp;</td>							
							<td scope="col"><label>Hotel Email</label></td>
							<td scope="col">
								<input type="text" size="26" class="validate[required,custom[email]]" data-errormessage-value-missing="Email is required!" name="email" id="email" maxlength="100" value="<?php echo set_value('email', (isset($detail->usrEmail) ? $detail->usrEmail : '')); ?>" class="validate[required]" />
							</td>
						</tr>
						<tr>
							<td scope="col"><label>Address:</label></td>
							<td scope="col">
								<textarea name="usraddress" name="usraddress" cols="24" rows="3" class="twocolumninputsize" ><?php echo set_value('usraddress', (isset($detail->hotelAddress) ? $detail->hotelAddress : '')); ?></textarea>
							</td>
							
						</tr>
						
						<tr>
							<td scope="col">
								<label id="lb_aboutyourself" >Hotel Main Pic</label>
							</td>
							<td scope="col" colspan="4">
								<?php if($detail->hotelMainpic!=''){?>
								<img src="<?php echo base_url(); ?>hotelImages/<?php echo str_replace(' ','_',$detail->hotelMainpic)?>" alt="" width="100" height="100" />
								<?php }?>
								<!--<input type = 'file' name ="hotelprofilePic">-->
								<input type = 'hidden' name ="hotelpreviousprofilePic" value = "<?php echo $detail->hotelMainpic;?>">
								<input type = 'file' name ="hotelprofilePic">
							</td>
						</tr>
						 
						<tr>
							<td scope="col">
								<label id="lb_aboutyourself" >About Hotel</label>
							</td>
							<td scope="col" colspan="4">
								<textarea style="width:545px;" name="aboutyourself" id="aboutyourself" cols="50" rows="7" class="" ><?php echo set_value('aboutyourself', (isset($detail->aboutHotel) ? $detail->aboutHotel : '')); ?></textarea> 
							</td>
						</tr>
						
						<tr>
							<td scope="col">
								<label id="lb_aboutyourself" >About Hotel (Arabic)</label>
							</td>
							<td scope="col" colspan="4">
								<textarea style="width:545px;" name="aboutyourselfarabic" id="aboutyourselfarabic" cols="50" rows="7" class=""><?php echo set_value('aboutyourselfarabic', (isset($detail->aboutHotelArabic) ? $detail->aboutHotelArabic : '')); ?></textarea> 
							</td>
						</tr>
						<input type ="hidden" name = "user_group_id" value="1">
						
						
                </table>                                
            </dd>
            <div class="clear"></div>
        </dl>
        <div class="baseStrip">
			<input type="hidden" name = "usrId" value ="<?php echo $detail->id; ?>" >
		    <input type="hidden" name = "hotelId" value ="<?php echo $detail->hotelid; ?>">
            <a class="btn" href="<?php echo site_url('admin/users'); ?>">Back</a>
                <input type="submit" value="Save" class="btn" name="btnSubmit" id="btnSubmit" />    
        </div>    
	</div>
</div>
</form>
<?php $this->load->view("admin/includes/footer"); ?>
