<?php 
$data['scripts'][] = 'frontend/addeditusers.js';
$data['validate']	='coachsignup';
$this->load->view('frontend/includes/header.php', $data);
$url = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$Pmail = $this->session->userdata('Pemail');
?>
<link href="<?php echo base_url();?>common/styles/css/font-awesome.css" rel="stylesheet">
<!-- content -->
 <section id="content">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
    	<h1>Sign up</h1>
        
        <div class="socialform">
            <ul class="auth-clients">
                <li><a class="facebook auth-link" href="<?php echo $login_urls; ?>" title="Facebook"><span><i class="fa fa-facebook" aria-hidden="true"></i></span>Sign up with facebook</a></li>
                <li class="googleplus"><a class="google auth-link" href="<?php echo base_url(); ?>hauth/login/Google" title="Google"><span><i class="fa fa-google-plus" aria-hidden="true"></i></span>Sign up with google+</a></li>
            </ul>
        </div>
        
        <p>P-Highlighted. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit duis sed odio sit amet nibh vulputate.</p>
        
    <?php
    $errors = validation_errors();
    $err_mesg = strip_tags($errors);
    $value = $this->session->flashdata('item');
    if (!empty($value)) {
        echo '<div class="alert alert-green alert-dismissible">
        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
        <i class="glyphicon glyphicon-ok"></i> 
        '.$value.'</div>';
    } else {
        if (isset($msg) && isset($msgType) && !empty($msg) && !empty($msgType)) {
            $error = $this->utility->show_message($msg, $msgType);
            $err_mesg = strip_tags($error);
        } else {
            $error = $this->utility->show_message();
            $err_mesg = strip_tags($error);
        }
    }
    if(!empty($err_mesg)):
    ?>
        
    <div class="alert alert-red alert-dismissible">
        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
        <i class="glyphicon glyphicon-remove"></i> 
        <?php echo $err_mesg; ?>                 
    </div> 
        
    <?php
    endif;
    
if (empty($detail)) {
    $attributes = array('id' => 'frmAdvertiser','class' => 'standardForm', 'name' => 'frmAddAdvertiser');
    echo form_open_multipart('user/addadvertiser', $attributes);
} else {
    $attributes = array('id' => 'frmAdvertiser', 'name' => 'frmEditAdvertiser');
    echo form_open_multipart('admin/users/editAdvertiser/' . $detail->user_id, $attributes);
    ?>
    <input type ="hidden" name="user_id" value ="<?php echo $detail->user_id; ?>" /> <?php
}
?>                                         
       <div class="row">   
                   <div class="col-md-4 col-sm-4 col-xs-12">
                   <div class="fullwidth">
                      <label>First Name</label>
                           <input type="text" data-errormessage-value-missing="First Name is required!" 
                                class="validate[required,custom[onlyLetterSp],maxSize[60]]" size="26" name="fname" id="fname" 
                                maxlength="60" value="<?php if(isset($detail->fname)) { echo $detail->fname ;
				                } elseif (isset($user_details['fname'])) { echo trim($user_details['fname']);	} else { echo "";} ?>" 
                                class="validate" />
                      </div>
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-12">
						   <div class="fullwidth">
                       <label>Last Name</label>
                       <input type="text" data-errormessage-value-missing="Last Name is required!" 
                                class="validate[required,custom[onlyLetterSp],maxSize[60]]" size="26" name="lname" id="lname" 
                                maxlength="60" value="<?php if(isset($detail->lname)) { echo $detail->lname ;
				                } elseif (isset($user_details['lname'])) { echo trim($user_details['lname']);	} else { echo "";} ?>" 
                                class="validate" />
                     </div>
                     </div>
                     
                      
                     <div class="col-md-4 col-sm-4 col-xs-12">
						   <div class="fullwidth">
                   <label>Advertiser Contact Number</label>
                      <input type="text" data-errormessage-value-missing="Contact Number is required!" 
                                class="validate[required,custom[onlyNumber],minSize[5],maxSize[15]]" size="26" name="phone" id="phone" 
                                maxlength="15" value="<?= set_value('phone', (isset($detail->phone) ? $detail->phone : '')); ?>" 
                                class="validate" />
                     </div>
                     </div>   	
                       
                        <div class="col-md-4 col-sm-4 col-xs-12">
					  <div class="fullWidth"><label>Gender</label> 
                <div class="selectBg">
               <select class="validate[required]" data-errormessage-value-missing="Gender Type is required!"  name="genderType" id="genderType" >
               <option <?php echo set_select('genderType', '', TRUE); ?> value="">Select Gender Type</option>
                                    <option <?php if(isset($user_details['genderType']) &&
                                    ($user_details['genderType'] =="m")) { echo 'selected="selected"'; } ?> value="m">Male</option>
                                    <option <?php if(isset($user_details['genderType']) &&
                                    ($user_details['genderType'] =="f")) { echo 'selected="selected"'; } ?> value="f">Female</option>
                </select>
              </div>
        </div>
        </div>  
        
        
         
                       <div class="col-md-4 col-sm-4 col-xs-12">
						   <div class="fullwidth">
                         <label>Email</label>
                        
                            <input type="text" size="26" class="validate[required,custom[email]]" 
                                data-errormessage-value-missing="Email is required!" name="email" id="email" maxlength="100" 
                                value="<?php if(isset($detail->email)) { echo $detail->email ;
							} elseif (isset($user_details['email'])) { echo trim ($user_details['email']);	} else { echo "";} ?>" 
                       class="validate[required]" />
                        </div>
                     </div> 
                   <div class="col-md-4 col-sm-4 col-xs-12">
						   <div class="fullwidth">
                       <label>Company Name</label>
                     
                            <input type="text" data-errormessage-value-missing="Company Name is required!" 
                                class="validate[required]" size="26" name="cname" id="cname"  maxlength="60" value="<?= set_value('cname', (isset($detail->cmpName) ? $detail->cmpName : '')); ?>" />
                         </div>
                     </div> 
                  
                     <input type="hidden" name="social_id" value="<?php  if (isset($user_details['social_id'])) { echo $user_details['social_id'];	}?>"/>
                     <input type="hidden" name="social_media_type" value="<?php  if (isset($user_details['social_media_type'])) { echo $user_details['social_media_type'];	}?>"/>
                     <div class="col-md-4 col-sm-4 col-xs-12">
						   <div class="fullwidth">
                       <label>Company Website</label>
                         <input type="text" class="validate[custom[url]]" size="26" name="cwebsite" id="cwebsite" 
                                maxlength="60" value="<?= set_value('cwebsite', (isset($detail->cmpWebsite) ? $detail->cmpWebsite : '')); ?>" />
                         </div>
                     </div>                  
                       <div class="col-md-4 col-sm-4 col-xs-12">
						   <div class="fullwidth">
                       <label>Company Phone</label>
                            <input type="text" data-errormessage-value-missing="Company Phone Number is required!" class="validate[required,custom[onlyNumber],minSize[5],maxSize[15]]" size="26" name="cphone" id="cphone"  maxlength="15" value="<?= set_value('cphone', (isset($detail->cmpPhone) ? $detail->cmpPhone : '')); ?>" />
                         </div>
                     </div>                         
                    <div class="col-md-4 col-sm-4 col-xs-12">
						   <div class="fullwidth">
                        <label>Password</label>
                                <input type="password"
                                    size="26" name="password" id="password" 
                                    maxlength="20" value="" 
                                    class="validate[required,minSize[6],maxSize[20]]" />
                           </div>
                     </div>  
                    <div class="col-md-4 col-sm-4 col-xs-12">
						   <div class="fullwidth">
                         <label>Confirm Password</label>
                            <input type="password"
                                    size="26" name="confirmPassword" id="confirmPassword" 
                                  maxlength="20" value="" 
                               class="validate[required,equals[password]]" />
                         </div>
                     </div>  
                 
                   <div class="col-md-12">
				<div class="fullWidth">
				</div>
				</div>   
					<div class="col-xs-12">
					<input type="submit" value="Submit" class="submit" name="btnSubmit" id="btnSubmit" />     
					</div>
		      
			  </div>
           </div>
         </div>
     </div>
</div>
</section>

 <?php $this->load->view("frontend/includes/footer"); ?>
