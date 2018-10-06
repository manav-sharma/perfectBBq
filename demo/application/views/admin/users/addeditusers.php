<?php 
$data['scripts'][] = 'admin/addeditusers.js';
$data['validate']='frmUser';
$this->load->view('admin/includes/header.php', $data);
if (empty($detail)) {
    $attributes = array('id' => 'frmUser', 'name' => 'frmAddUser');
    echo form_open_multipart('admin/users/addUser', $attributes);
} else {
    $attributes = array('id' => 'frmUser', 'name' => 'frmEditUser');
    echo form_open_multipart('admin/users/editUser/' . $detail->id, $attributes); ?>
    <input type ="hidden" name="userid" value ="<?php echo $detail->id; ?>" /> <?php 
}  
?> 
 
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
                <!--LOGIN DETAIL -->
                <h3>Login Details</h3>
                <table cellspacing="0" cellpadding="0" border="0" class="form">
                    <colgroup>
                        <col width="170"/>
                        <col width="180"/>
                        <col width="50"/>
                        <col width="170"/>
                        <col width="180"/>
                    </colgroup>
                    <tbody>
                        <tr>
                           <td scope="col">
                                <label id="lb_username">Username</label>
                            </td>
                            <td scope="col">
                                <input type="text" data-errormessage-value-missing="User name is required!" class="validate[required,minSize[6]]" maxlength="100" size="26" name="username" id="username" value="<?php echo (isset($_POST['username']) && $_POST['username'] != "") ? $_POST['username'] : (isset($detail->usrUsername) ? $detail->usrUsername : ""); ?>" <?php echo (isset($detail) && isset($detail->usrUsername)) ? 'readonly="true"' : ""; ?> /> 
                            </td>
							 <td scope="col">&nbsp;</td>
                            <td scope="col"> <label id="lb_username">Group Name</label></td>
							 <td scope="col">
								<select name="groupname" id="" >
									<option value="">Select Group</option>
									 <?php if(!empty($groupName)) : ?> 
										<?php foreach($groupName as $key=>$value) :?>
											 <option <?php if(isset($detail->user_group_id) && $detail->user_group_id == $value->id ){?> selected="selected" <?php } ?> value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
										<?php endforeach; ?>
									 <?php endif; ?> 
								 </select> 
                            </td>
                        </tr>
                        <tr>
                            <td scope="col">
                                <label>Password:</label>
                            </td>
                            <td scope="col">
                                <input type="password" data-errormessage-value-missing="Password is required!"  <?php if (empty($detail)) { ?>class="validate[required]" <?php } ?>size="26" maxlength="30" name="password" id="password" value ="" />
                                <br />
                                <?php if (!empty($detail)) { ?><font size="1">Leave blank if you dont want to change the password.</font><?php } ?> 
                            </td>
                            <td scope="col">&nbsp;</td>
                            <td scope="col">
                                <label>Confirm Password:</label>
                            </td>
                            <td scope="col">
                                <input type="password" data-errormessage-value-missing="Confirm Password is required!"  class="validate[equals[password]]" maxlength="30" name="confirmPassword" id="confirmPassword" value ="" size="26"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                        <td scope="col"><label>First Name:</label></td>
                        <td scope="col"><input type="text" data-errormessage-value-missing="First Name is required!" class="validate[required,custom[onlyLetterSp]]" size="26" name="txtFirstName" id="txtFirstName" maxlength="60" value="<?php echo set_value('txtFirstName', (isset($shippingBillingData['txtFirstName']) ? $shippingBillingData['txtFirstName'] : '')); ?>" class="validate" /></td>
                        <td scope="col">&nbsp;</td>
                        <td scope="col"><label>Last Name:</label></td>
                        <td scope="col"><input type="text" size="26" data-errormessage-value-missing="Last Name is required!" class="validate[required,custom[onlyLetterSp]]" name="txtLastName" id="txtLastName" maxlength="60" value="<?php echo set_value('txtLastName', (isset($shippingBillingData['txtLastName']) ? $shippingBillingData['txtLastName'] : '')); ?>" class="validate" /></td>
                    </tr>
                    <tr>
                            <td scope="col">
                                <label>Gender</label>
                            </td>
                            <td scope="col">
                                <input name="txtGender" value="m" type="radio" checked="checked" <?php echo (isset($detail) && $detail->usrGender == 'm') ? "checked = checked" : ""; ?> class="radioBtn" <?php echo set_radio('txtGender', 'male'); ?> />
                                Male
                                <input name="txtGender" value="f" type="radio" <?php echo (isset($detail) && $detail->usrGender == 'f') ? "checked = checked" : ""; ?> class="radioBtn" <?php echo set_radio('txtGender', 'female'); ?> />
                                Female
                            </td>
                            <td scope="col">&nbsp;</td>
                            <td scope="col"><label> Date of Birth</label>
                            </td>
                            <?php if (!empty($detail->usrDOB)) {  $date = $detail->usrDOB; } ?>
                            <td scope="col"> 
                                <input type="text" onblur="datefillCheck();" class="validate[required]" data-errormessage-value-missing="Date of Birth is required!"  size="30"  name="txtDob" id="datepicker"  value="<?php echo (isset($_POST['txtDob']) && $_POST['txtDob'] != "") ? $_POST['txtDob'] : (isset($date) ? $date : ""); ?>" />
                                <!-- selector validate-->
								
								
								
								 
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
											if (isset($_POST['country']) && $_POST['country'] == $country->geoId) {
												$sel = 'selected="selected"';
											}
											elseif($detail->usrCountryId==$country->geoId)
												{
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
							<td scope="col"><label>State</label></td>
							<td scope="col"> 
							   <input type="text" size="26" name="cmbState" id="cmbState" value="<?php echo set_value('cmbState', (isset($detail->usrState) ? $detail->usrState : '')); ?>" class="">
							  <div id="tt_cmbState" class="hidden">State is <strong>required</strong>.</div>
							  <input type="hidden" name="ShipStateeway" id="ShipStateeway" value="" />
							</td>
						</tr>
						
						<tr>
							<td scope="col"><label id="lb_cmbCity">City</label></td>
							<td scope="col">
							  <input type="text" size="26" name="cmbCity" id="cmbCity" value="<?php echo set_value('cmbCity', (isset($detail->usrCity) ? $detail->usrCity : '')); ?>" class="">
							  <div id="tt_cmbCity" class="hidden">City is <strong>required.</strong></div>
							  <input type="hidden" name="ShipCityeway" id="ShipCityeway" value="" />
							</td>
							<td scope="col">&nbsp;</td>							
							<td scope="col"><label>Email</label></td>
							<td scope="col">
								<input type="text" size="26" class="validate[required,custom[email]]" data-errormessage-value-missing="Email is required!" name="email" id="email" maxlength="100" value="<?php echo set_value('email', (isset($shippingBillingData['usremail']) ? $shippingBillingData['usremail'] : '')); ?>" class="validate[required]" />
							</td>
						</tr>
						<tr>
							<td scope="col"><label>Address:</label></td>
							<td scope="col">
								<textarea name="usraddress" name="usraddress" cols="24" rows="3" class="twocolumninputsize" ><?php echo set_value('usraddress', (isset($shippingBillingData['usrAddress']) ? $shippingBillingData['usrAddress'] : '')); ?></textarea>
							</td>
							<td scope="col">&nbsp;</td>							
							<td scope="col"><label>Contact Number:</label></td>
							<td scope="col">
								<input type="text" size="26" class="validate[required,custom[onlyNumber]]" data-prompt-position="bottomRight:-60" name="usrContact" id="usrContact" maxlength="15" value="<?php echo set_value('usrContact', (isset($shippingBillingData['usrContact']) ? $shippingBillingData['usrContact'] : '')); ?>" class="validate[required]" />
							</td>
						</tr>
						 
						<tr>
							<td scope="col">
								<label id="lb_aboutyourself" >About Yourself</label>
							</td>
							<td scope="col" colspan="4">
								<textarea style="width:545px;" name="aboutyourself" id="aboutyourself" cols="50" rows="7" class="" ><?php echo set_value('aboutyourself', (isset($shippingBillingData['usrDescription']) ? $shippingBillingData['usrDescription'] : '')); ?></textarea> 
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