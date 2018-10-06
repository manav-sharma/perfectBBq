
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title><?php echo $this->config->item('siteName'); ?> :: <?php echo ucwords($title); ?></title>
<!-- Bootstrap -->
<link rel='shortcut icon' href='<?php echo FRONTEND_IMAGE;  ?>images/fav.ico' type='image/x-icon'/ >
<link href="<?php echo CSS_PATH;;  ?>css/bootstrap.css" rel="stylesheet">
<link href="<?php echo CSS_PATH;;  ?>css/style.css" rel="stylesheet">
<link href="<?php echo CSS_PATH;;  ?>css/tabs.css" rel="stylesheet">
<link href="<?php echo CSS_PATH;;  ?>css/jquery.mCustomScrollbar.css" rel="stylesheet">
<link href="<?php echo CSS_PATH;;  ?>css/responsive.css" rel="stylesheet">

<link href="<?php echo CSS_PATH;;  ?>validationEngine.jquery.css" rel="stylesheet">

<script src="<?php echo JS_PATH; ?>js/jquery.min.js"></script>
<script> 
var siteUrl = "<?php echo SITE_URL(); ?>"; 
var  siteurl = "<?php echo SITE_URL(); ?>"; 
var baseUrl = "<?php echo base_url(); ?>";
</script>
<style>
.btn.submit-btn {
    margin-top: 20px;
}	

.form-control.inputBg.error , .form-control.textAreaBg.error {
    border: 1px solid red;
}
label.error {
    display:none!important;
} 
</style>
</head>
<body>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script>
	$(document).ready(function() {
		// validate signup form on keyup and submit
		$("#contactus").validate({
			rules: {
				txtName: "required",
				lstName: "required",
				txtphone: "required",
				txtEmail: {
					required: true,
					email: true
				},
				txtMessage: "required",
				
			},
			messages: {
				txtName: "Please enter your firstname",
				lstName: "Please enter your lastname",
				txtEmail: "Please enter a valid email address",
				
				
			}
		});
	});
	</script>
	
<header class="defineFloat" id="header">
  <div class="container">
    <div class="row">
      <div class="col-sm-3 col-xs-3 logo">
		   <a href="<?php echo base_url();?>"><img src="<?php  echo FRONTEND_IMAGE;  ?>images/logo.png" class="img-responsive" alt="" title="UREAP :: homepage"/></a>      
      </div>
      <div class="col-md-5 col-sm-7 col-xs-7 login">
        <ul class="list-unstyled">
<!--
		<?php // if( $this->session->userdata('logged_in') == True && $this->session->userdata('isAdmin') == FALSE ) { ?>
			<li><a href="<?php//  echo base_url(); ?>login/userlogout" title="" class="signIn">Log Out</a></li>
			<?php// }  else {?>
-->
          <li><a href="<?php  echo base_url(); ?>admin" title="login">login</a></li>
         
          <li><a href="<?php  echo base_url(); ?>user/addadvertiser" title="Signup">Sign up as Advertiser</a></li>
        </ul>
      </div>
    </div>
    <?php  $url = current_url();?> 
    <?php if($url == base_url()) {  ?>
    <div class="row">
      <div class="col-md-7 col-sm-8 col-xs-12 tabOuter">
        <div class="defineFloat tabIn">
          <div class="defineFloat mainBg">
            <p class="topText"><a href="#">Menu</a></p>
            <div id="main">
            <div class="topBg"></div>
            <?php $activevalue = $this->session->flashdata('active'); ?>
              <ul id="tabs">
                <li class="<?php if (!empty($activevalue)) { echo ''; } else { echo 'active'; }?>" title="About">About</li>
                <li title="Advertisers">Advertisers</li>
                <li title="Merchants">Merchants</li>
                <li class="<?php  if (!empty($activevalue)) { echo 'active'; }?>" title="Contact">Contact</li>
                <li title="USP’s">USP’s</li>
              </ul>
              <ul id="tab" class="content mCustomScrollbar">
                <li class="<?php if (!empty($activevalue)) { echo ''; } else { echo 'active'; }?>">
                 <?php echo $cmsAboutContent; ?>
                </li>
                <li>
                 <?php echo $cmsSimpleContent; ?>
                </li>
                <li>
                  <?php echo $cmsMerchantsContent; ?>
                </li>
                <li class="<?php if (!empty($activevalue)) { echo 'active'; }?>">
                  <?php echo $cmsContactContent; ?>
                  
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
               	  <div class="tabForm">
					  <?php
					
					if (empty($detail)) {
					$attributes = array('id' => 'contactus','class' => 'form-inline', 'name' => 'contactus');
					echo form_open_multipart('home/contactus', $attributes);
					} else {
					$attributes = array('id' => 'contactus', 'name' => 'contactus');
					echo form_open_multipart('admin/users/editAdvertiser/' . $detail->user_id, $attributes);
				
					?>
					<input type ="hidden" name="user_id" value ="<?php echo $detail->user_id; ?>" /> <?php
					}
					?>
                
                      <div class="form-group">
                      <label>First Name<sup class="redColor">*</sup></label>
                         <input type="text" class="form-control inputBg" name="txtName" id="txtName" value="<?= set_value('txtName', (isset($detail->txtName) ? $detail->txtName : '')); ?>" placeholder="First Name" >
                      </div>
                      <div class="form-group">
                        <label>Last Name <sup class="redColor">*</sup></label>
                        <input type="text" class="form-control inputBg" name="lstName" id="lstName" value="<?= set_value('lstName', (isset($detail->lstName) ? $detail->lstName : '')); ?>" placeholder="Last Name" >
                      </div>
                      <div class="form-group">
                        <label>Phone <sup class="redColor">*</sup></label>
                         <input type="text" class="form-control inputBg" name="txtphone" id="txtphone" value="<?= set_value('txtphone', (isset($detail->txtphone) ? $detail->txtphone : '')); ?>" placeholder="Phone" >
                      </div>
                      <div class="form-group">
                        <label>Email <sup class="redColor">*</sup></label>
                         <input type="text" class="form-control inputBg" name="txtEmail" id="txtEmail" value="<?= set_value('txtEmail', (isset($detail->txtEmail) ? $detail->txtEmail : '')); ?>" placeholder="Email" >
                      </div>
                      
                      <div class="form-group-full">
                        <label>Message<sup class="redColor">*</sup></label>
                       <textarea name="txtMessage" cols="3" rows="3" class="form-control textAreaBg" id="txtMessage" value="<?= set_value('txtMessage', (isset($detail->txtMessage) ? $detail->txtMessage : '')); ?>"></textarea>
                      </div>
                      <input type="submit" value="Submit" class="btn submit-btn" name="btnSubmit" id="btnSubmit" />
                    
                  </div>
                </li>
                <li>
                  <?php echo $cmsUSPContent; ?>
                </li>
              </ul>
            </div>
          </div>
        </div>
       
        <button class="btn btn-default commonButton">
        <img src="<?php  echo FRONTEND_IMAGE;  ?>images/playButton.png" class="img-responsive" alt="" />
        <p><span>Get it on</span><br/>
        <label>Google Play</label>
        </p>
        </button>
      </div>
    </div>
<?php } else {  ?>
	<?php }?>
  </div>

</header>
