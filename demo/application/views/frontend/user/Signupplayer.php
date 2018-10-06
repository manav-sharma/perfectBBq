<?php 
$data['scripts'][] = 'frontend/addeditusers.js';
$data['validate']	='playersignup';
$this->load->view('frontend/includes/header.php', $data);
$url = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$Pmail = $this->session->userdata('Pemail');
?> 
<!-- content -->
 	<div class="container">
    	<div class="form">
        	<div class="row">
            	<div class="col-lg-12 col-md-12 col-sm-12">
    				<h1>Player Signup Form</h1>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
    				<div id="tabbing">
                    	<ul class="nav nav-tabs">
    						<!--<li class="<?php if($url == "addUserCoach"){ echo 'active'; }?>"><a href="<?php echo base_url();?>signup/addUserCoach">Coach</a></li>-->
    						<li class="<?php if($url == "addUserPlayer"){ echo 'active'; }  ?>"><a href="<?php echo base_url();?>signup/addUserPlayer">Player</a></li>
  						</ul>
                      	<!-- Tab panels -->
                      	<div class="tab-content content">
                        	<!-- for player -->
                            <div role="tabpanel" class="tab-pane active" id="">
                          
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
                           
                            <!-- for player -->
                            
								<?php  $attributes = array('id' => 'playersignup', 'name' => 'playersignup');
                                    echo form_open_multipart('signup/addUserPlayer', $attributes); ?>
									<div class="row">
										 <?php $post = $this->input->post(); ?>
										<input type="hidden" value="3" name="clientType">
										<div class="col-lg-4 col-md-4 col-sm-4">
											<label>First Name</label>
											<input data-errormessage-value-missing ="Name is required!" type="text" data-prompt-position="topLeft:0,10" name="txtName" value="<?php echo ((isset($post['txtName']) && !empty($post['txtName'])) ? $post['txtName'] :'') ?>" class="form-control validate[required,custom[onlyLetterSp]" />
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<label>Last Name</label>
											<input data-prompt-position="topLeft:0,10" type="text" name="txtLName" value="<?php echo ((isset($post['txtLName']) && !empty($post['txtLName'])) ? $post['txtLName'] :'') ?>" class="form-control validate[custom[onlyLetterSp]" />
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<label>Email address</label>
											<input readonly data-errormessage-value-missing ="Email Address is required!" type="text" data-prompt-position="topLeft:0,10" name="txtEmail" value="<?php echo ((isset($post['txtEmail']) && !empty($post['txtEmail'])) ? $post['txtEmail'] :(isset($Pmail) && !empty($Pmail)) ? $Pmail :'') ?>" class="form-control validate[required,custom[email],ajax[ajaxEmailCall]]" />
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<label>Password</label>
											<input data-errormessage-value-missing ="Password is required!" data-prompt-position="topLeft:0,10" type="password" name="txtPassword" value="" class="form-control validate[required,minSize[6]]" />
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<label>Confirm Password</label>
											<input id="confrmPassword" data-errormessage-value-missing ="Confirm Password is required!" data-prompt-position="topLeft:0,10" type="password" name="confrmPassword" value="" class="form-control validate[required,minSize[6]]" />
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<label>Address1</label>
											<input data-errormessage-value-missing ="Address1 is required!" data-prompt-position="topLeft:0,10" type="text" name="txtAddress1" value="<?php echo ((isset($post['txtAddress1']) && !empty($post['txtAddress1'])) ? $post['txtAddress1'] :'') ?>" class="form-control " />
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<label>Address2</label>
											<input type="text" name="txtAddress2" value="<?php echo ((isset($post['txtAddress2']) && !empty($post['txtAddress2'])) ? $post['txtAddress2'] :'') ?>" class="form-control" />
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<label>Country</label>
											<select data-prompt-position="topLeft:0,10" data-errormessage-value-missing ="Country Name is required!" name="txtCountry" class="form-control">
												<option value="">Select </option>
											 <?php
												$countries = getCountries();
												foreach($countries  as $country):
												?>
												<option  value="<?php echo $country->geoId; ?>"><?php echo $country->geoName; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<label>Phone no.</label>
											<input data-errormessage-value-missing ="Phone Number should be numeric !" data-prompt-position="topLeft:0,10" type="text" name="txtContact" value="<?php echo ((isset($post['txtContact']) && !empty($post['txtContact'])) ? $post['txtContact'] :'') ?>" class="form-control validate[custom[onlyNumberSp],minSize[10],maxSize[12]]" />
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<label>Date of Birth</label>
											<input data-errormessage-value-missing ="Date Of Birth is required!" type="text" readonly data-prompt-position="topLeft:0,2"  id="datepicker" name="usrAge" class=" form-control">
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<label>Jersey Number</label>
											<input type="text" name="txtJersey" value="<?php echo ((isset($post['txtJersey']) && !empty($post['txtJersey'])) ? $post['txtJersey'] :'') ?>" class="form-control" />
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<label>Alternate Email ID</label>
											<input data-prompt-position="topLeft:0,10" type="text" name="txtAltEmail" value="<?php echo ((isset($post['txtAltEmail']) && !empty($post['txtAltEmail'])) ? $post['txtAltEmail'] :'') ?>" class="form-control validate[custom[email]]" />
										</div>
							
										<div class="col-lg-12 col-md-12 col-sm-12">
											<label>Upload photo</label>
											<div class="uploadImage"><img src="<?php echo base_url(); ?>common/images/uploadimage.jpg" alt="" /></div>
											<input data-errormessage-value-missing ="User Image is required!" data-prompt-position="topLeft:0,10" type="file" name="txtImage" value="" class="file validate[funcCall[checkpoint]]" />
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="separator">
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<label>Gender</label>
											<div class="radio-inline">
												<label><input type="radio" name="gender" value="Male" /checked>Male</label>
											</div>
											<div class="radio-inline">
												<label><input type="radio" name="gender" value="Female" />Female</label>
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="checkbox-inline"><input data-errormessage-value-missing="Accept terms and Condition !" data-prompt-position="topLeft:0,10" type="checkbox" name="agree" class="validate[required]" />I agree with the terms outlined in the <a href="<?php echo base_url(); ?>cms/page/terms" target="_blank" title="">Terms of Use *</a></div>
										</div>
										
										<div class="col-lg-12 col-md-12 col-sm-12">
											<input type="submit" name="" value="Submit" class="submitButton"/>
										</div>
									</div>
								 <?php echo form_close();?>
                            </div>
                      	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php $this->load->view("frontend/includes/footer"); ?>
