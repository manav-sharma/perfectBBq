<footer class="defineFloat" id="footer">
  <div class="container">
    <div class="row">
		<?php  $url = base_url(); ?> 
	<?php if($url == base_url()) {  ?> 
      <div class="col-md-12 col-sm-12 col-xs-12 footerHeading text-center">
        <h1>Download the app</h1>
        <p>Our app is available to download so give it a try.</p>
        <button class="btn btn-default commonButton">
        <img src="<?php  echo FRONTEND_IMAGE;  ?>images/playButton.png" class="img-responsive" alt="" />
        <p><span>Get it on</span>
        <br/>
        <label>Google Play</label>
        </p>
      </div>
      <?php } else {  ?>
		  <?php }?>
  <!--      <div class="col-md-12 col-sm-12 col-xs-12 footerMenu text-center">
        <ul class="list-unstyled">
        <li><a href="<?php echo SITE_URL(); ?>" title="Home">home</a></li>
          <li><a href="javascript:void(0);" title="About">About</a></li>
          <li><a href="javascript:void(0);" title="Advertisers">Advertisers</a></li>
          <li><a href="javascript:void(0);" title="Content">Content</a></li>
          <li><a href="javascript:void(0);" title="Developers">Developers</a></li>

          <li><a href="javascript:void(0);" title="Support/Contact">Support/Contact</a></li>

          <li><a href="javascript:void(0);" title="Download">Download</a></li>
         
        </ul>
      </div> -->
    </div>
  </div>
</footer>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 

<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="<?php echo JS_PATH; ?>js/bootstrap.min.js"></script> 
<script src="<?php echo JS_PATH; ?>js/tabs.js"></script> 
<script src="<?php echo JS_PATH; ?>js/modernizr.js"></script> 
<script src="<?php echo JS_PATH; ?>js/jquery.mCustomScrollbar.concat.min.js"></script> 
<script src="<?php echo JS_PATH; ?>js/core.js"></script> 

<script src="<?php echo JS_PATH; ?>jquery.validationEngine.js"></script> 
<script src="<?php echo JS_PATH; ?>jquery.validationEngine-en.js"></script> 


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --> 
<!-- WARNING: Respond.js doesn't work if you view the page via file:// --> 
<!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->


</body>
</html>
