<?php
$data['scripts'][] = '';
$this->load->view('admin/includes/header.php', $data);

?>  

<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/appusers/appuserlist'); ?>">Appusers</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
    <div class="column2"> 
        <a class="btn" href="<?php echo site_url('admin/appusers/appuserlist'); ?>">Back</a>  
    </div>
	
</div>
<div class="clear"></div>
</div>   
<div id="pageContent">
   
    <div style="width:796px;" id="tab1" class="column2">   
        <dl class="formBox">
            <dt><?php echo ucwords(isset($title) ? $title : ''); ?></dt>
            <dd>
                
                <table border="0" cellspacing="0" cellpadding="0" style=" margin-top:18px" class="form" >
                    <colgroup>
                        <col width="170"/>
                        <col width="180"/>
                        <col width="50"/>
                        <col width="170"/>
                        <col width="180"/>
                    </colgroup>
                    <tr>
                        <td scope="col"><label>Sign Up date:</label></td>
                        <td scope="col"><?php echo date(ADMIN_DATE_FORMAT, strtotime($detail['created_at'])); ?></td>
                    </tr>
                    
                    <tr>
                        <td scope="col"><label>Ad’s Viewed:</label></td>
                        <td scope="col"><?php echo $detail['ads_viewed']; ?></td>
                    </tr>
                   
                    <tr>
                        <td scope="col"><label>Credit Points in account:</label></td>  
                        <td scope="col">
                        <?php echo $detail['credits_left'];?>
                        </td>
                    </tr>
                    <tr>
                        <td scope="col"><label>Credit Points Redeemed:</label></td>  
                        <td scope="col">
                        <?php echo (!empty($detail['points_redeemed']))?$detail['points_redeemed']:'0';?>
                        </td>
                    </tr>
                  
                    <tr>
                        <td scope="col"><label>Rewards Purchased:</label></td>  
                        <td scope="col">
                        <?php echo $detail['rewards_purchased'];?>
                        </td>
                    </tr>
                  
                    <tr>
                        <td scope="col"><label>All advertisement’s viewed:</label></td>  
                        <td scope="col">
                            <a href='<?php echo base_url('admin/appusers/ads_viewed').'/'.$detail['user_id'];?>' target="_blank">View Details</a>
                        </td>
                    </tr>
                    <tr>
                        <td scope="col"><label>Credit Points History:</label></td>  
                        <td scope="col">
                            <a href='<?php echo base_url('admin/appusers/credit_history').'/'.$detail['user_id'];?>' target="_blank">View Details</a>
                        </td>
                    </tr>
                  
                    <tr>
                        <td scope="col"><label>Rewards Purchased:</label></td>  
                        <td scope="col">
                            <a href='<?php echo base_url('admin/appusers/rewards_purchased').'/'.$detail['user_id'];?>' target="_blank">View Details</a>
                        </td>
                    </tr>
                  
                </table>    
            </dd>
            <div class="clear"></div>
        </dl>
        <div class="baseStrip">
            <a class="btn" href="<?php echo site_url('admin/appusers/appuserlist'); ?>">Back</a>
        </div>    
	</div>
</div>

<?php $this->load->view('admin/includes/footer.php'); ?>
