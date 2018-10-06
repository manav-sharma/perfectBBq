<?php
$data['scripts'][] = 'admin/changepassword.js';
$data['validate'] = 'frmChangePassword';
$this->load->view('admin/includes/header.php', $data);
?>

<?php
if ($this->session->userdata('loggedInUserType') == 'advertiser') {
    $url = 'admin/ads';
} else {
    $url = 'admin/users/home';
}
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


<form class="validate" method="post" action="<?php echo site_url('admin/users/changePassword'); ?>" id="frmChangePassword" name="frmChangePassword">

    <div id="pageHeading">
        <div class="column1">
            <h1><?php echo $title; ?></h1>
            <div id="breadcrumb">
                <a href="<?php echo site_url('admin/users/home'); ?>">Home</a> 
                <span class="pipe"> &#187; </span><?php echo $title; ?>
            </div>
        </div>
        <div class="column2"> 
            <a class="btn" href="<?php echo site_url($url); ?>">Back</a>  
            <input type="submit" value="Save" class="btn" name="btnSubmit1" id="btnSubmit1" />                    
        </div>   
    </div>
    <div class="clear"></div>


<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
    <div style="width:796px;" id="pageContent">
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
        <dl class="formBox">
            <dt><?php echo $title; ?></dt>
            <dd>

                <table cellspacing="0" cellpadding="0" border="0" class="form">
                    <colgroup><col width="170">
                        <col>
                    </colgroup><tbody><tr>
                            <td scope="col"><label>Old Password</label></td>
                            <td scope="col"><input type="password" class="validate[required]" name="txtOldPassword" size="30" maxlength="20"/></td>
                        </tr>
                        <tr>
                            <td scope="col"><label>New Password</label></td>
                            <td scope="col"><input type="password" class="validate[required]" id="txtNewPassword" name="txtNewPassword" size="30"  maxlength="20" /></td>
                        </tr>
                        <tr>
                            <td scope="col"><label>Confirm New Password</label></td>
                            <td scope="col"><input type="password" class="validate[required,equals[txtNewPassword]]" name="txtConfirmNewPassword" size="30"  maxlength="20" /></td>
                        </tr>
                    </tbody></table>
            </dd>
        </dl>
        <div class="baseStrip"><a class="btn" href="<?php echo site_url($url); ?>">Back</a>
            <input type="submit" value="Save" class="btn" id="changePassForm" />
        </div>
    </div>
</form>
<?php
$this->load->view('admin/includes/footer.php');
?>