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
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/appusers/appuserlist'); ?>">Appusers</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
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
                <?php if (count($userListing)) { ?>
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
                    <col width="200">
                    <col width="100">	  
                    <col width="120">
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
                                value='advertiser_<?php echo ((isset($sortBy) && $sortBy == "advertiser") ? ($orderBy) : 'asc'); ?>' 
                                id="advertiser" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "advertiser") ? "checked='checked'" : ""); ?> />
                            <label for="advertiser" class="radioSubmitClass" >
                                <a class="sorting 
                                    <?php echo ((!empty($sortBy) && $sortBy == "advertiser") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Advertiser Name
                                </a>
                            </label>
                        </th>
                        
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='credit_points_<?php echo ((isset($sortBy) && $sortBy == "credit_points") ? ($orderBy) : 'asc'); ?>' 
                                id="credit_points" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "credit_points") ? "checked='checked'" : ""); ?> />
                            <label for="credit_points" class="radioSubmitClass" >
                                <a class="sorting 
                                    <?php echo ((!empty($sortBy) && $sortBy == "credit_points") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >	Credit Earned
                                </a>
                            </label>
                        </th>
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='created_at_<?php echo ((!empty($sortBy) && $sortBy == "created_at") ? ($orderBy) : 'asc'); ?>' 
                                id="created_at" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "created_at") ? "checked='checked'" : ""); ?>  />
                            <label for="created_at" class="radioSubmitClass" >
                                <a class="sorting <?php echo ((!empty($sortBy) && $sortBy == "created_at") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Viewed Date
                                </a>
                            </label>
                        </th>
                        <th scope="col">Actions</th>
                    </tr>
                    <tr class="filter">
                      
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtAdTitle']) ? $postData['txtAdTitle'] : ''; ?>"
                                class="" name="txtAdTitle" maxlength="100" />
                        </td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtAdvertiser']) ? $postData['txtAdvertiser'] : ''; ?>"
                                class="" name="txtAdvertiser" maxlength="100" />
                        </td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtAdCredits']) ? $postData['txtAdCredits'] : ''; ?>"
                                class="" name="txtAdCredits" maxlength="100" />
                        </td>

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
                    $total_users = count($userListing);
                    if ($total_users > 0) 
                    {
                        $i = 0;
                        foreach ($userListing as $val)
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
                                $advertiser = $val->advertiser;
                                ?>
                                <td title="<?php echo ucwords(isset($adName) ? $adName : "&nbsp;"); ?>" style="vertical-align: middle;">
                                    <?php echo ucwords(isset($adName) ? $adName : "&nbsp;"); ?>
                                </td>
                                <td title="<?php echo ucwords(isset($advertiser) ? $advertiser : "&nbsp;"); ?>" style="vertical-align: middle;">
                                    <?php echo ucwords(isset($advertiser) ? $advertiser : "&nbsp;"); ?>
                                </td>
                                <td style="vertical-align: middle;">
                                    <?php echo $val->credit_points; ?>
                                </td>
                                
                                <td style="vertical-align: middle;">
                                    <?php 
                                    if (isset($val->created_at)) {
                                        echo date(ADMIN_DATE_FORMAT, strtotime($val->created_at));
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
