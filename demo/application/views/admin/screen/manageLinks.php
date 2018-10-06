<?php 
$data['scripts'][] = 'admin/cmsmenus.js';
$data['validate']	='addeditmenu';
$this->load->view('admin/includes/header.php', $data);
$menuId= 0; 

if (empty($detail)) {
    $attributes = array('id' => 'addeditmenu', 'name' => 'addeditmenu');
    echo form_open_multipart('admin/menus/addmenu', $attributes);
} else {
    $attributes = array('id' => 'addeditmenu', 'name' => 'addeditmenu');
	$menuId=  $detail['mnuId'];
    echo form_open_multipart('#' . $detail['mnuId'], $attributes); ?>
    <input type ="hidden" name="mnuId" value ="<?php echo $detail['mnuId']; ?>" /> <?php 
}  
?>  
<div class="clear"></div>
<div class="saveMenuProcess hide"><img src="<?php echo CMS_UPLOADS_image.'cms/loading.gif';?>"/></div>
<div id="pageHeading"> 
    <div class="column1">
        <h1><?php echo ucwords(isset($title) ? $title : ''); ?></h1>
        <div id="breadcrumb">
            <a href="<?php echo site_url('admin'); ?>">Home</a>
            <span class="pipe">&#187;</span> <a href="<?php echo site_url('admin/menu'); ?>">Menu</a>
            <span class="pipe">&#187;</span> <?php echo ucwords(isset($title) ? $title : ""); ?>
        </div>
    </div>
	<div class="column2"> 
		<a class="btn" href="<?php echo site_url('admin/menuscontent'); ?>">Back</a>  
		<input type="button" value="Save" class="btn toHierarchy" name="btnSubmit1" id="btnSubmit1" />                    
	</div>   
</div>
<div class="clear"></div>
</div>   
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
	
    <div id="tab1" class="column2 changeWidth">   
        <dl class="formBox">
            <dt><?php echo set_value('txtName', (isset($detail['mnuName']) ? $detail['mnuName'] : '')); ?></dt> 
			<div> 
				<div class="menuLeft" >
					<div id="tab1" class="column3"> 
						<dl class="formBox">
							<dt>Pages</dt>	 
						</dl>
						<ul class="categorychecklist">
						<?php 
						$total_pages = count($pages);
						if ($total_pages > 0) {
						foreach($pages as $val) { ?> 
							<li>
								<label>
									<?php echo $val['cmsTitle']; ?> 
								</label> 
								<input type="checkbox" id="pageId" value="<?php echo $val['cmsid']; ?>"/> 
								<input type="checkbox" style="display:none;"id="<?php echo $val['cmsid']; ?>" value="<?php echo $val['cmsTitle']; ?> "/> 
							</li> 
						<?php } } ?> 
						</ul> 
					</div>
					<input id="admenuButton" type="button" value="Add menu" /> 
							<div id="tab2" class="column3">  
								<dl class="formBox"><dt>Custom Menu</dt></dl>
								<ul class="categorychecklist">
									<li>
										Menu Url:<input  class="validate[required]" type="url" name="custurl" id="custurl" value="http://">
									</li>
									<li>
										Menu Text:<input  class="validate[required]" type="text" id="custname" name="custname" >
									</li>
								</ul> 
							</div>
						<input id="admenuButton1" type="submit" value="Add menu" />  
				</div>
				<div class="menuRight">
						<?php if( empty($menus)):  ?>
								<div class="emptyDiv" >There are no menu links.</div>
						<?php endif; ?>
					<section id="menuList">
						<ol class="sortable ui-sortable" id="menuOL"> 
							<?php  if(!empty($menus)):
										foreach($menus as $key=>$value) : 
										$uniqid=uniqid();
										if(isset($value['menulink_url']) && !empty($value['menulink_url'])){
										?>
										<li class="mjs-nestedSortable-leaf <?php echo $uniqid; ?>"  id="list_<?php echo $value['page_id']; ?> ,<?php echo $value['menulink_name']; ?>,<?php echo $value['menulink_url']; ?>">
										<?php
										}else{
										?>
										<li class="mjs-nestedSortable-leaf <?php echo $uniqid; ?>"  id="list_<?php echo $value['page_id']; ?>">
										<?php
										}
										?>
												<div>
													<span class="disclose">
														<span> </span> </span><?php 
													
														if(isset($value['cmsTitle']) && !empty($value['cmsTitle'])){
														echo $value['cmsTitle'];
														}else{
														echo $value['menulink_name']; }	?> 
													<span class="floatRight" onclick="deleteLi('<?php echo $uniqid; ?>')">Delete</span>	<span class='customChanges'><a><?php if(isset($value['cmsTitle']) && !empty($value['cmsTitle'])){
													echo "Page";
													}else{
													echo "Custom Url";
													}?></a></span>
												</div>
												<?php if(!empty($value[0])) : echo '<ol>'.submenu($value[0],1).'</ol>'; endif; ?>
											</li>	 
										<?php endforeach; ?>
							<?php endif; ?> 	
						</ol> 
					</section> <!-- END #demo -->	 
				</div>
			</div>
			
        <div class="clear"></div>
        </dl>
        <div class="baseStrip">
            <a class="btn" href="<?php echo site_url('admin/menuscontent'); ?>">Back</a>
                <input type="button" value="Save" class="btn toHierarchy" name="toHierarchy" id="toHierarchy" value="To hierarchy"  />    
        </div>    
	</div>
</div>
</form> 

<script type="text/javascript" src="<?php echo JS_PATH . 'admin/menu/jquery-1.7.2.min.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo JS_PATH . 'admin/menu/custom.min.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo JS_PATH . 'admin/menu/touch-punch.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo JS_PATH . 'admin/menu/nestedSortable.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo JS_PATH . 'admin/menu/custom.js'; ?>"></script> 
<script> 
$(document).ready(function(){ 
	$('.toHierarchy').click(function(e){  
		$(".saveMenuProcess").removeClass("hide");
		$("#pageContent").addClass("addnegativeIndex"); 
			orderNew = $('ol.sortable').nestedSortable('serialize');  
			   $.ajax({
				url: siteurl+'menus/addmenulink/'+<?php echo $menuId; ?>,
				type: 'post',
				dataType: 'json',
				data: {lists:orderNew},  
				success: function(data) { 
					$(".saveMenuProcess").addClass("hide");
					$("#pageContent").removeClass("addnegativeIndex"); 
					location.reload();
				}
		});   
		 
	}) 
	 
}) 	
</script> 
<?php $this->load->view("admin/includes/footer"); ?>