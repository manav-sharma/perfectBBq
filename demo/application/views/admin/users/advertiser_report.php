<?php
$data['scripts'][] = 'admin/home.js';
$data['validate'] = 'listingForm';
$this->load->view('admin/includes/header.php', $data);
?>  
<div class="clear"></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ""); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin'); ?>">Users</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
    <div class="ads-run">
            <span>Campaigns Run:  <?php echo $adsDetail['total_ads']; ?></span>
            <span>Total Spending (USD):  <?php echo (!empty($adsDetail['total_spending']))?$adsDetail['total_spending']:'0'; ?></span>
    </div>
    <div class="clear"></div>
</div>
<div id="pageContent">
    <?php
    $attributes = array('id' => 'listingForm', 'name' => 'ads');
    echo form_open('', $attributes);
    ?>
    <input type="hidden" value="filter" name="filterOrSort" id="filterOrSort" />
    <?php 
    $value = $this->session->flashdata('item');
    if (!empty($value)) {
       echo $value; 
    } else {
        echo '<div class="warning hidden"></div>';
    }
    ?>
    <div class="stdListingWrapper">
        <dl id="actionBar">
            <dt>&nbsp;</dt>
            <dd> 
                <?php if (count($adsDetail['ads'])) { ?>
                    <input type="radio" name="exportCSV" value='exportCSV' id="exportCSV" class="hidden" />
                    <label for="exportCSV" style="display:inline;" class="radioCSVExport" >
                        <a  title="Export to CSV" class="actionIcn toolTip <?php echo ((isset($sortBy) && $sortBy == "ad_title") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                            <img src="<?php echo base_url(); ?>common/media/images/icons/icn.export.gif" alt="" width="18" height="18" />
                        </a>
                    </label>
                <?php } ?>

            </dd>
        </dl>
        
        <div class="stdListing">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <colgroup>
                    <col width="250">
                    <col width="150">
                    <col width="150">
                    <col width="100">
                    <col width="180">
                    <col width="150">
                    <col width="90">	
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='ad_title_<?php echo ((isset($sortBy) && $sortBy == "ad_title") ? ($orderBy) : 'asc'); ?>' 
                                id="ad_title" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "ad_title") ? "checked='checked'" : ""); ?> />
                            <label for="ad_title" class="radioSubmitClass" >
                                <a class="sorting 
                                    <?php echo ((!empty($sortBy) && $sortBy == "ad_title") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Campaign Title
                                </a>
                            </label>
                        </th>              
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='ad_price_<?php echo ((isset($sortBy) && $sortBy == "ad_price") ? ($orderBy) : 'asc'); ?>' 
                                id="ad_price" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "ad_price") ? "checked='checked'" : ""); ?> />
                            <label for="ad_price" class="radioSubmitClass" >
                                <a class="sorting 
                                    <?php echo ((!empty($sortBy) && $sortBy == "ad_price") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >	Campaign Price (USD)
                                </a>
                            </label>
                        </th>
                        
                        <th scope="col">
                            <label for="advertiser" class="radioSubmitClass" >
                                <a class="sorting" >
                                    Campaign Duration (days)
                                </a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='total_views_<?php echo ((isset($sortBy) && $sortBy == "total_views") ? ($orderBy) : 'asc'); ?>' 
                                id="total_views" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "total_views") ? "checked='checked'" : ""); ?> />
                            <label for="total_views" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((!empty($sortBy) && $sortBy == "total_views") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Views
                                </a>
                            </label>
                        </th>
		 	
                        <th scope="col">
                            <label for="points_accumulated" class="radioSubmitClass" >
                                <a class="sorting" >
                                   Points Accumulated
                                </a>
                            </label>
                        </th>
                        
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='expired_at_<?php echo ((!empty($sortBy) && $sortBy == "expired_at") ? ($orderBy) : 'asc'); ?>' 
                                id="expired_at" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "expired_at") ? "checked='checked'" : ""); ?>  />
                            <label for="expired_at" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((!empty($sortBy) && $sortBy == "expired_at") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Expiry Date
                                </a>
                            </label>
                        </th>
                        <th>Action</th>
                    </tr>
                    
                    <tr class="filter">
                        
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtAdTitle']) ? $postData['txtAdTitle'] : ''; ?>"
                                class="" name="txtAdTitle" maxlength="100" />
                        </td>
                       
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtadPrice']) ? $postData['txtadPrice'] : ''; ?>"
            class="" name="txtadPrice" maxlength="10" />
                        </td>
                        <td></td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['txttotal_views']) ? $postData['txttotal_views'] : ''; ?>"
                                class="" name="txttotal_views" maxlength="100" />
                        </td>
                        <td> </td>
                        <?php
                        if (isset($postData['txtDateFrom']) && !empty($postData['txtDateFrom'])) {
                            $from = $postData['txtDateFrom'];
                        } else {
                            $from = 'From';
                        }
                        if (isset($postData['txtDateTo']) && !empty($postData['txtDateTo'])) {
                            $to = $postData['txtDateTo'];
                        } else {
                            $to = 'To';
                        }
                        ?>
                        <td>
                            <input type="text" name="txtDateFrom" readonly id="dp1310711996720" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $from; ?>" onblur="if (this.value == '') {
                                        this.value = 'From';
                                    }" autocomplete="off"/>&nbsp;&nbsp;
                            <input type="text" name="txtDateTo" readonly id="dp1310711996721" style="width:62px;" class="datePicker defaultValue filtering" value="<?php echo $to; ?>" onblur="if (this.value == '') {
                                        this.value = 'To';}" autocomplete="off"/>
                        </td>
                        <td>
                            <input type="submit" title="Tip: Fill up one or more fields on the left to filter the record list below." value="Apply Filter" class="btn" id="btnFilter" name="btnFilter" />
                        </td>
                    </tr>
                    <?php
                    $adsListing = $adsDetail['ads'];
                    if (count($adsListing) > 0) 
                    {
                        $i = 0;
                        foreach ($adsListing as $val)
                        {
                            if ($i % 2 == 1) {
                                $class = "alternateRow";
                            } else {
                                $class = "";
                            }
                            ?>
                            <tr class="<?php echo $class; ?>">
                                <?php
                                $adName = $val->ad_title;
                                ?>
                                <td title="<?php echo ucwords(isset($adName) ? $adName : "&nbsp;"); ?>" style="vertical-align: middle;">
                                    <?php echo ucwords(isset($adName) ? $adName : "&nbsp;"); ?>
                                </td>
                               
                                <td style="vertical-align: middle;">
                                    <?php echo $val->ad_price; ?>
                                </td>
                                
                                <td style="vertical-align: middle;">
                                    <?php echo $val->ad_duration; ?>
                                </td>
                                
                                <td style="vertical-align: middle;">
                                    <?php echo $val->total_views; ?>
                                </td>
                                
                                <td style="vertical-align: middle;">
                                    <?php echo $val->points_accumulated; ?>
                                </td>
                                
                                <td style="vertical-align: middle;">
                                    <?php 
                                    if (isset($val->expired_at)) {
                                        echo date(ADMIN_DATE_FORMAT, strtotime($val->expired_at));
                                    } 
                                    ?>
                                </td> 
                                <td></td>
                            </tr>
                    <?php
                            $i++;
                        }
                    }
                    else 
                    {
                    ?>
                        <script type="text/javascript">
                            setWarningMessage('neg', 'No result found to display.');
                        </script>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <?php $this->activepagination->getAdminPagination(); ?>
        
    </div>
    <?php echo form_close(); ?>
</div>
<?php $this->load->view('admin/includes/footer.php'); ?>
