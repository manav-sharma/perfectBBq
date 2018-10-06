<?php
//echo "<pre>";
//print_r($detail->countryId);
//exit;

$data['scripts'][] = 'admin/addeditusers.js';
$data['validate'] = 'frmAdvertiser';
$this->load->view('admin/includes/header.php', $data);
?> 
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    #map {
        height: 100%;
        width: 450px;
        right: 20px;
        top: 160px;
        position: absolute;
    }
    .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
    }

    #pac-input:focus {
        border-color: #4d90fe;
    }

    .pac-container {
        font-family: Roboto;
    }

    #type-selector {
        color: #fff;
        display: inline-flex;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
    }

    #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
    }
</style>
<div class="clear"></div>


<?php
if (empty($detail)) {
    $attributes = array('id' => 'frmAdvertiser', 'name' => 'frmAddAdvertiser');
    echo form_open_multipart('admin/users/addAdvertiser', $attributes);
} else {
    $attributes = array('id' => 'frmAdvertiser', 'name' => 'frmEditAdvertiser');
    echo form_open_multipart('admin/users/editAdvertiser/' . $detail->user_id, $attributes);
    ?>
    <input type ="hidden" name="user_id" value ="<?php echo $detail->user_id; ?>" /> <?php
}
?>


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
<!--</div>   -->

    
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
                        <td scope="col"><label>First Name</label></td>
                        <td scope="col">
                            <input type="text" data-errormessage-value-missing="First Name is required!" 
                                class="validate[required,custom[onlyLetterSp],maxSize[60]]" size="26" name="fname" id="fname" 
                                maxlength="60" value="<?= set_value('fname', (isset($detail->fname) ? $detail->fname : '')); ?>" 
                                class="validate" />
                        </td>
                        <td scope="col">&nbsp;</td>
                        <td scope="col"><label>Last Name</label></td>
                        <td scope="col">
                            <input type="text" data-errormessage-value-missing="Last Name is required!" 
                                class="validate[required,custom[onlyLetterSp],maxSize[60]]" size="26" name="lname" id="lname" 
                                maxlength="60" value="<?= set_value('lname', (isset($detail->lname) ? $detail->lname : '')); ?>" 
                                class="validate" />
                        </td>
                    </tr>

                    <tr>
                        <td scope="col"><label>Advertiser Contact Number</label></td>
                        <td scope="col">
                            <input type="text" data-errormessage-value-missing="Contact Number is required!" 
                                class="validate[required,custom[onlyNumber],minSize[5],maxSize[15]]" size="26" name="phone" id="phone" 
                                maxlength="15" value="<?= set_value('phone', (isset($detail->phone) ? $detail->phone : '')); ?>" 
                                class="validate" />
                        </td>
                        <td scope="col">&nbsp;</td>
                    <td scope="col"><label>User Type</label></td>
                        <td scope="col">
                             	<select class="validate[required]" data-errormessage-value-missing="User Type is required!" style="width:155px;" name="usrType" id="usrType" >
                                    <option <?php echo set_select('usrType', '', TRUE); ?> value="">Select User Type</option>
                                    <option <?php if(isset($detail->user_type) &&
                                    ($detail->user_type =="advertiser")) { echo 'selected="selected"'; } ?> value="advertiser">Advertiser</option>
                                    <option <?php if(isset($detail->user_type) &&
                                    ($detail->user_type =="media_agent")) { echo 'selected="selected"'; } ?> value="media agent">Media Agent</option>

				</select>
                        </td>
                    </tr>
                       
                    <tr>
                        <td scope="col"><label>Email</label></td>
                        <td scope="col">
                            <input type="text" size="26" class="validate[required,custom[email]]" 
                                data-errormessage-value-missing="Email is required!" name="email" id="email" maxlength="100" 
                                value="<?= set_value('email', (isset($detail->email) ? $detail->email : '')); ?>" 
                                class="validate[required]" />
                        </td>
                    </tr>
                    <tr><td></td></tr>
                    <tr>
                        <td scope="col"><label>Company Name</label></td>
                        <td scope="col">
                            <input type="text" data-errormessage-value-missing="Company Name is required!" 
                                class="validate[required]" size="26" name="cname" id="cname"  maxlength="60" value="<?= set_value('cname', (isset($detail->cmpName) ? $detail->cmpName : '')); ?>" />
                        </td>
                        <td scope="col">&nbsp;</td>
                        <td scope="col"><label>Company Website</label></td>
                        <td scope="col">
                            <input type="text" class="validate[custom[url]]" size="26" name="cwebsite" id="cwebsite" 
                                maxlength="60" value="<?= set_value('cwebsite', (isset($detail->cmpWebsite) ? $detail->cmpWebsite : '')); ?>" />
                        </td>
                    </tr>
                    
                        <tr>
                        <td scope="col"><label>Company Phone</label></td>
                        <td scope="col">
                            <input type="text" data-errormessage-value-missing="Company Phone Number is required!" class="validate[required,custom[onlyNumber],minSize[5],maxSize[15]]" size="26" name="cphone" id="cphone"  maxlength="15" value="<?= set_value('cphone', (isset($detail->cmpPhone) ? $detail->cmpPhone : '')); ?>" />
                        </td>                        
                        </tr>
                    
                    <tr>
                        <td scope="col">
                            <label id="lb_aboutyourself" >Company Address</label>
                        </td>
                        <td scope="col" colspan="4">
                            <textarea style="width:545px;" name="caddress" id="caddress" cols="50" rows="7" class="" ><?php echo set_value('caddress', (isset($detail->cmpAddress) ? $detail->cmpAddress : '')); ?></textarea> 
                        </td>
                    </tr>     

                </table>      
                
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
                        <!--<tr>
                            <td scope="col">
                                <label id="lb_username">Username</label>
                            </td>
                            <td scope="col">
                                <input type="text" data-errormessage-value-missing="User name is required!"
                                    class="validate[required,minSize[6],maxSize[60]]" maxlength="60" size="26" name="username" id="username" 
                                    value="<?= set_value('username', (isset($detail->username) ? $detail->username : '')); ?>"/> 
                            </td>
                            <td scope="col">&nbsp;</td>
                        </tr>-->
                        <tr>
                            <td scope="col"><label>Password</label></td>
                            <td scope="col">
                                <input type="password"
                                    size="26" name="password" id="password" 
                                    maxlength="20" value="" 
                                    class="validate[minSize[6],maxSize[20]]" />
                            </td>
                            <td scope="col">&nbsp;</td>
                            <td scope="col"><label>Confirm Password</label></td>
                            <td scope="col">
                                <input type="password"
                                    size="26" name="confirmPassword" id="confirmPassword" 
                                    maxlength="20" value="" 
                                    class="validate[equals[password]]" />
                            </td>
                        </tr>
                        <tr>

                        </tr>
                    </tbody>
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
<?php echo form_close(); ?>

<?php $this->load->view("admin/includes/footer"); ?>
