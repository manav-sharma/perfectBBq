<?php
$data['validate']='frmLogin';
$this->load->view('admin/includes/header',$data);
$attributes = array('id' => 'frmLogin', 'name' => 'frmlogin', 'class' => '','method'=>'post');
echo form_open('admin/users/login', $attributes);
?>  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center">
            <div id="content">
                <dl class="formBox">
                    <dt>Administrator Login</dt>
                    <dd>
                        <table width="85%" cellspacing="10" cellpadding="0" border="0">
                            <colgroup>
                                <col width="80">
                                <col>
                            </colgroup>
                            <tbody>
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
        						}
                                ?>
                                <tr>
                                    <td><label>Username</label></td>
                                    <td>
                                        <input id="txtUserName" type="text" data-errormessage-value-missing="User name is required!" class="validate[required]" value="<?php echo ((set_value('txtUserName')) ? set_value('txtUserName') : $this->input->cookie('admin_name')); ?>" name="txtUserName" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Password</label></td>
                                    <td>
                                        <input type="password" maxlength="20" data-errormessage-value-missing="Password is required!" class="validate[required]" value="<?php echo ((set_value('txtPassword')) ? set_value('txtPassword') : $this->input->cookie('admin_pass')); ?>" name="txtPassword" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td><input type="checkbox"  class="checkbox" value="y" name="chk_remember_me" <?php echo ((($this->input->cookie('admin_name') != "") && ($this->input->cookie('admin_pass') != "")) ? "checked='yes'" : "No"); ?> />
                                        Remember Me</td>
                                </tr>
                                
                                <tr>
									<td>&nbsp;</td>
                                    <td><a href="<?php echo site_url('admin/users/forgottenpassword'); ?>">Forgotten Password ?</a></td>
                                </tr>
                                
                                <tr>
                                    <td style="height:10px;" scope="col" colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="baseStrip">
                            <input type="submit" value="Login" class="btn" name="btnLogin" />
                        </div>
                    </dd>
                </dl>
                <?php echo form_close(); ?>
            </div>
        </td>
    </tr>
</table>



