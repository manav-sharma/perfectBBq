 <script type="text/javascript">
	$("#savePage").on("click", function () {
		var divContents = $("#rightColumn").html();
		var printWindow = window.open('', '', 'menubar=yes,resizable=yes,scrollbars=yes');
		printWindow.document.write(divContents);
		printWindow.document.close();
		printWindow.save();
	}); 
</script> 
<div class="content">
    <div class="wrapper"> 
		    <?php
			$this->load->view('frontend/cms/cmsinit');
			echo $cmsContent; 
		   ?> 
		<?php if(isset($print)&& !empty($print)) { ?>
			<div class="dashedBorder" style="padding:0px;">&nbsp;</div>
			<div style="float:left;margin-right:10px;">
				<a class="yellowBtn fltLft" href="javascript:window.print();"><span>Print</span></a>
			</div>
			<div style="float:left;margin-right:10px;">
				<a class="yellowBtn fltLft" href="<?php echo site_url('users/savePDF/'.$pageId);?>"><span>Save as PDF</span></a>
			</div><?php 
		} ?>
      </div> 
    </div> <div class="clearFix"></div> 