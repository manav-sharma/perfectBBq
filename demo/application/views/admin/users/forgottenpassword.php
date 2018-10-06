<?php
$data['validate']='frmLogin';
$data['scripts'][] = 'admin/forgottenpassword.js';
$this->load->view('admin/includes/header', $data);
$attributes = array('id' => 'frmLogin', 'name' => 'frmLogin', 'class' => 'validateLogin');
echo form_open('admin/users/forgottenpassword', $attributes);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center">
            <div id="content">
                <!-- Content Area Start -->
                <dl class="formBox">
                    <dt>Forgotten Password</dt>
                    <dd>
                        <?php
						$message = $this->session->flashdata('item');
						if(isset($message) && !empty($message)){
							echo $message;
						} else{
							if (isset($msg) && isset($msgType) && !empty($msg) && !empty($msgType)) {
								echo $this->utility->show_message($msg, $msgType);
							} else {
								echo $this->utility->show_message();
							}
						}?>
                        <table width="80%" border="0" cellspacing="10" cellpadding="0">
                            <col width="120" />
                            <col />
                            <tr>
                                <td scope="col"><label>Username</label></td>
                                <td scope="col">
                                    <input type="text" name="txtUserName" data-errormessage-value-missing="User name is required!" class="validate[required]" id="txtUserName" value="<?php echo set_value("txtUserName"); ?>" maxlength="100"  /> 
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" scope="col" ></td>
                            </tr> 
                        </table>
                        <div class="baseStrip">
                            <input name="Submit" type="button" class="btn" value="Back"  onclick="javascript:document.location.href='<?php echo site_url('admin/users/login'); ?>'" />
                            <input name="btnSubmit" type="submit" class="btn" value="Send" />
                        </div>
                    </dd>
                </dl>
                <!-- Content Area End -->
            </div>
        </td>
    </tr>
</table>
<?php echo form_close(); ?>
<div class="alertBox hidden">
    <h2>&nbsp;</h2>
    <p>&nbsp;</p>
    <div class="right"><a href="#" class="btn" id="okBtn">OK</a></div>
</div>