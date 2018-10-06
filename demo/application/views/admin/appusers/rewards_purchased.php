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
                    <col width="90">
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
                                value='product_id_<?php echo ((isset($sortBy) && $sortBy == "product_id") ? ($orderBy) : 'asc'); ?>' 
                                id="product_id" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "product_id") ? "checked='checked'" : ""); ?> />
                            <label for="product_id" class="radioSubmitClass" >
                                <a class="sorting 
                                    <?php echo ((!empty($sortBy) && $sortBy == "product_id") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Product Id
                                </a>
                            </label>
                        </th>
                        
                        <th scope="col">
                            <input type="radio" name="sortBy" 
                                value='product_name_<?php echo ((isset($sortBy) && $sortBy == "product_name") ? ($orderBy) : 'asc'); ?>' 
                                id="product_name" class="hidden" 
                                <?php echo ((isset($sortBy) && $sortBy == "product_name") ? "checked='checked'" : ""); ?> />
                            <label for="product_name" class="radioSubmitClass" >
                                <a class="sorting 
                                    <?php echo ((!empty($sortBy) && $sortBy == "product_name") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >
                                    Product Name
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
                                    <?php echo ((!empty($sortBy) && $sortBy == "credit_points") ? ("sort" . ucfirst($orderBy)) : ''); ?>" >	Credit Points Spent
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
                                    Purchase Date
                                </a>
                            </label>
                        </th>
                        <th scope="col">Actions</th>
                    </tr>
                    <tr class="filter">
                        <td></td>
                        <td>
                            <input type="text" value="<?php echo isset($postData['txtProductName']) ? $postData['txtProductName'] : ''; ?>"
                                class="" name="txtProductName" maxlength="100" />
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
                                <td style="vertical-align: middle;">
                                    <?php echo $val->product_id; ?>
                                </td>
                                <td title="<?php echo ucwords(isset($val->product_name) ? $val->product_name : "&nbsp;"); ?>" style="vertical-align: middle;">
                                    <?php echo ucwords(isset($val->product_name) ? $val->product_name : "&nbsp;"); ?>
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
