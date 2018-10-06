<?php
//echo "<pre>";
//print_r($detail->countryId);
//exit;

$data['scripts'][] = 'admin/addeditusers.js';
$data['validate'] = 'frmInstance';
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
    $attributes = array('id' => 'frmInstance', 'name' => 'frmAddInstance');
    echo form_open_multipart('admin/instance/add', $attributes);
} else {
    $attributes = array('id' => 'frmInstance', 'name' => 'frmEditInstance');
    echo form_open_multipart('admin/instance/edit/' . $detail->instance_id, $attributes);
?>
    <input type ="hidden" name="user_id" value ="<?php echo $detail->instance_id; ?>" /> 
<?php
}
?>


<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>            
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
    <div class="column2"> 
        <a class="btn" href="<?php echo site_url('admin/instance'); ?>">Back</a>  
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
                <h3>Instance Details</h3>
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
                                <label id="lb_username">Instance Name</label>
                            </td>
                            <td scope="col">
                                <?= $detail->instance_name; ?>
                                    <!-- <input type="text" data-errormessage-value-missing="Instance name is required!"
                                    class="validate[required]" maxlength="100" size="26" name="instance_name" id="instance_name" 
                                    value="<?= set_value('instance_name', (isset($detail->instance_name) ? $detail->instance_name : '')); ?>"
                                    /> -->
                            </td>
                            <td scope="col">&nbsp;</td>
                        </tr>                        
                        
                        <tr>
                            <td scope="col"><label>Price</label></td>
                            <td scope="col">
                                <input type="text"
                                    class="validate[required,number]" size="26" name="instance_price" id="instance_price" 
                                    maxlength="10" 
                                    value="<?= set_value('instance_price', (isset($detail->instance_price) ? $detail->instance_price : '')); ?>"
                                    />
                            </td>
                            <td scope="col">&nbsp;</td>

                        </tr>   
                        
                        <tr>
                            <td scope="col"><label>Sort Order</label></td>
                            <td scope="col">
                                <input type="text"
                                    class="validate[custom[onlyNumber]]" size="26" name="sort_order" id="sort_order" 
                                    maxlength="10" 
                                    value="<?= set_value('sort_order', (isset($detail->sort_order) ? $detail->sort_order : '')); ?>"                                    
                                    />
                            </td>
                            <td scope="col">&nbsp;</td>

                        </tr>    
                        
                        <tr>
                            <td scope="col"><label>Status</label></td>
                            <td scope="col">
                                
                                <?php #echo "<Pre>"; print_r($detail);die;?>
                                <select name="status" class="validate[required]" data-errormessage-value-missing="Status is required!" >
                                    <option value="">Select Status</option>                                        
                                    <option 
                                        <?= set_select('status','1', ((isset($detail->status) && $detail->status == '1') ? true : false) ); ?>
                                        value="1">Active</option>                                    
                                    
                                    <option 
                                        <?= set_select('status','0', ((isset($detail->status) && $detail->status == '0') ? true : false) ); ?>
                                        value="0">Inactive</option>
                                 </select> 
                            </td>
                            <td scope="col">&nbsp;</td>

                        </tr>
                        
                        <tr>
                            
                        </tr>
                    </tbody>
                </table>
                                                
            </dd>
            <div class="clear"></div>
        </dl>
        <div class="baseStrip">
            <a class="btn" href="<?php echo site_url('admin/instance'); ?>">Back</a>
            <input type="submit" value="Save" class="btn" name="btnSubmit" id="btnSubmit" />    
        </div>    
    </div>
</div>
<?php echo form_close(); ?>

<?php $this->load->view("admin/includes/footer"); ?>
