<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <title><?php echo $this->config->item('siteName'); ?> :: <?php echo ucwords($title); ?></title>
        <link rel="stylesheet" href="<?php echo ADMIN_CSS_PATH . 'cms.css'; ?>" type="text/css" media="screen"/>
        <link rel="stylesheet" href="<?php echo ADMIN_CSS_PATH . 'core.css'; ?>" type="text/css" media="screen"/>
        <link rel="stylesheet" href="<?php echo ADMIN_JS_PATH . 'jgrowl/jquery.jgrowl.min.css'; ?>" type="text/css" media="screen"/>
        <?php 
        if ($this->session->userdata('user_id')) 
        { 
        ?>
            <link rel="stylesheet" href="<?php echo ADMIN_CSS_PATH . 'menu.css'; ?>" type="text/css" media="screen"/>
        <?php
        }
        else 
        {
        ?>
            <link rel="stylesheet" href="<?php echo ADMIN_CSS_PATH . 'login.css'; ?>" type="text/css" media="screen"/>   
        <?php
        }
        ?>
        <link rel="stylesheet" href="<?php echo CSS_PATH . 'jquery-ui/jquery-ui-1.7.2.custom.css'; ?>" type="text/css" media="screen"/>
        <link rel="stylesheet" href="<?php echo CSS_PATH . 'jquery-ui/jquery.ui.all.css'; ?>" type="text/css" media="screen"/>
        <link rel="stylesheet" href="<?php echo ADMIN_CSS_PATH . 'form.css'; ?>" type="text/css" media="screen"/>
        <link rel="stylesheet" href="<?php echo CSS_PATH . 'jquery-ui/jquery-ui-timepicker-addon.css'; ?>" type="text/css" media="screen"/>
        <link rel="stylesheet" href="<?php echo CSS_PATH . 'fancybox/jquery.fancybox.css'; ?>" type="text/css" media="screen"/>

        <!-- validation Libraries-->
        <script type="text/javascript" src="<?php echo JS_PATH . 'jquery/jquery.js'; ?>"></script> 
        <script type="text/javascript" src="<?php echo JS_PATH . 'validationEngine/jquery.validationEngine-en.js'; ?>"></script> 
        <script type="text/javascript" src="<?php echo JS_PATH . 'validationEngine/jquery.validationEngine.js'; ?>"></script> 
        <link rel="stylesheet" href="<?php echo CSS_PATH . 'validationEngine.jquery.css'; ?>"/>   
        <script type="text/javascript" src="<?php echo JS_PATH . 'jquery/jquery-ui.js'; ?>"></script> 
        <!-- validation Libraries Ends here --> 

        <script type="text/javascript" src="<?php echo JS_PATH . 'admin/jgrowl/jquery.jgrowl.js'; ?>"></script> 
        <script type="text/javascript" src="<?php echo JS_PATH . 'preloadCssImages.jQuery.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo JS_PATH . 'jquery.imgpreload.min.js'; ?>"></script>  
        <script type="text/javascript" src="<?php echo JS_PATH . 'fancybox/jquery.fancybox.js'; ?>"></script> 

        <!-- effect jquery for datepicker and tooltip--> 
        <script type="text/javascript" src="<?php echo JS_PATH . 'other/jquery.ui.core.js'; ?>"></script> 
        <script type="text/javascript" src="<?php echo JS_PATH . 'other/jquery.ui.widget.js'; ?>"></script> 
        <script type="text/javascript" src="<?php echo JS_PATH . 'other/jquery.ui.position.js'; ?>"></script>  
        <script type="text/javascript" src="<?php echo JS_PATH . 'other/jquery.ui.mouse.js'; ?>"></script>  
        <script type="text/javascript" src="<?php echo JS_PATH . 'other/jquery.ui.sortable.js'; ?>"></script>  
        <script type="text/javascript" src="<?php echo JS_PATH . 'other/jquery.ui.effect.js'; ?>"></script>   
        <script type="text/javascript" src="<?php echo JS_PATH . 'other/jquery.ui.effect-clip.js'; ?>"></script> 
        <script type="text/javascript" src="<?php echo JS_PATH . 'other/jquery.ui.datepicker.js'; ?>"></script> 
        <script type="text/javascript" src="<?php echo JS_PATH . 'other/jquery-ui-timepicker-addon.js'; ?>"></script>   
        <script type="text/javascript" src="<?php echo JS_PATH . 'admin/tooltip.js'; ?>"></script>   

        <!-- end effects jquery for datepicker and tooltip -->
        <script>
            var siteurl = '<?php echo ADMIN_SITE_URL; ?>';
            var baseurl = '<?php echo site_url(); ?>';
            var formId = '';
        </script> 
        <?php
        if (isset($scripts)) {
            foreach ($scripts as $script) {
                $filePath = $script;
                ?>
                <script type="text/javascript" src="<?php echo JS_PATH . $filePath; ?>"></script>
                <?php
            }
        }
        
        if (isset($validate) && $validate != '') 
        {
            $formId = '#' . $validate;        
        ?>

            <script type="text/javascript">
                $(document).ready(function () {
					formId = '<?php echo $formId; ?>';
                    $("input").on('click', '', function () {
                        var inputFieldFormError = "." + $(this).attr('id') + 'formError';
                        $(inputFieldFormError).fadeOut(150, function () {
                            $(this).remove();
                        });
                    });

                    $(formId).validationEngine('attach', {
                        autoHidePrompt: true,
                        autoHideDelay: 5000});
                });
            </script>     
        <?php 
        }
        
        ?> 

        <script type="text/javascript" src="<?php echo JS_PATH . 'admin/shell.init.js'; ?>"></script> 	
    </head>
    
    <body>
		
		
		<?php  
        if ($this->session->userdata('logged_in') == true 
                && $this->session->userdata('user_id') 
                && $this->session->userdata('loggedInUserType') == 'admin')
        {
            $user_name = $this->session->userdata('name');
        ?>
            <div id="pageHeader">
                <div class="title"><a href="<?php echo site_url('admin/users'); ?>"><?php echo $this->config->item('siteName'); ?></a></div>
                <div class="caption"><span class="highlight">Administration</span> <span class="pipe">&nbsp;l&nbsp;</span> Logged in as <?php //echo ucwords($user_name); ?> (Administrator)</div>
            </div>
            <!-- Web site Navigations -->
            <div id="mainMenuWrapper">
                <ul id="mainMenu">
                    <li>
                        <a href="<?php echo site_url('admin/category'); ?>" <?php echo ($this->router->class == 'category') ? 'class="active"' : ''; ?> >Categories</a>
                    </li>
                    
                    <li>
                        <a href="<?php echo site_url('admin/recipe'); ?>" <?php echo ($this->router->class == 'recipe') ? 'class="active"' : ''; ?> >Recipies</a>
                    </li>
                    
                    <li>
                        <a href="<?php echo site_url('admin/basic'); ?>" <?php echo ($this->router->class == 'basic') ? 'class="active"' : ''; ?> >BBQ Basic</a>
                    </li>
                    
                    <li>
                        <a href="<?php echo site_url('admin/advertisment'); ?>" <?php echo ($this->router->class == 'advertisment') ? 'class="active"' : ''; ?> >Advertisement</a>
                    </li>
                    
                    <li>
                        <a href="<?php echo site_url('admin/pages'); ?>" <?php echo ($this->router->class == 'pages') ? 'class="active"' : ''; ?> >Pages</a>
                    </li>
                    
                    <li>
                        <a href="<?php echo site_url('admin/screen'); ?>" <?php echo ($this->router->class == 'screen') ? 'class="active"' : ''; ?> >Manage Intro screen</a>
                    </li>
                   
                </ul>
                <ul id="quickLinks">
                    <li>
                        <a href="<?php echo site_url('admin/category'); ?>" title="Home" class="toolTip"><img src="<?php echo base_url(); ?>common/media/images/icons/icn.home.gif" alt="" /></a>
                        <a href="<?php echo site_url('admin/users/changeEmail'); ?>" title="Change Admin Email" class="toolTip"><img src="<?php echo base_url(); ?>common/media/images/icons/icon-Email.gif" alt="" /></a>
                        <a href="<?php echo site_url('admin/users/changePassword'); ?>" title="Change Password" class="toolTip"><img src="<?php echo base_url(); ?>common/media/images/icons/icn.change.password.gif" alt="" /></a>	
                        <a href="<?php echo site_url('admin/users/logout'); ?>" title="Logout" class="toolTip"><img src="<?php echo base_url(); ?>common/media/images/icons/icn.logout.gif" alt="" /></a> 
                    </li>
                </ul>            
            </div>
        <?php 
	}
