<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8">
            <title><?php echo $this->config->item('siteName'); ?> :: <?php echo ucwords($title); ?></title>
            <link rel="stylesheet" href="<?php echo base_url(); ?>common/styles/admin/cms.css" type="text/css" media="screen"/>
            <link rel="stylesheet" href="<?php echo base_url(); ?>common/styles/admin/core.css" type="text/css" media="screen"/>
            <link rel="stylesheet" href="<?php echo base_url(); ?>common/scripts/jgrowl/jquery.jgrowl.css" type="text/css" media="screen"/>
            <link rel="stylesheet" href="<?php echo JS_PATH .'fancybox/jquery.fancybox.css'; ?>" type="text/css" media="screen"/>
            <?php if ($this->session->userdata('id')) { ?>
                <link rel="stylesheet" href="<?php echo base_url(); ?>common/styles/admin/menu.css" type="text/css" media="screen"/>
            <?php } else { ?>
                <link rel="stylesheet" href="<?php echo base_url(); ?>common/styles/admin/login.css" type="text/css" media="screen"/>   
            <?php } ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>common/styles/jquery-ui/jquery-ui-1.7.2.custom.css" type="text/css" media="screen"/>
            <!-- JQuery Base Libraries-->
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/jquery-1.7.1.min.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/jquery-ui-1.7.2.custom.min.js"></script>
            <!-- JQuery Base Libraries Ends here -->
            <!-- Plugins -->
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/jquery.qtip-1.0.0-rc3.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/jquery.metadata.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/blockUI.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/form/jquery.form.js"></script>
            <script type="text/javascript" src="<?php echo JS_PATH . 'fancybox/jquery.fancybox.js';?>"></script>
            <script type="text/javascript" src="<?php echo JS_PATH . 'tiny_mce/tiny_mce.js'; ?>"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/form/validate.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/admin/form.validate.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/shell.function.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/admin/shell.init.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/preloadCssImages.jQuery.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/jquery.limit-1.2.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/jquery.imgpreload.min.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/jgrowl/jquery.jgrowl_minimized.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/textpandable.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/integra.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>common/scripts/swfobject.js"></script>
            <link rel="stylesheet" href="<?php echo base_url(); ?>common/styles/admin/form.css" type="text/css" media="screen"/>
            <script>
                var siteurl  = '<?php echo site_url('admin'); ?>';
            </script>
            <?php
            if (isset($scripts)) { 
                foreach ($scripts as $script) {
                    $filePath = 'common/scripts/' . $script;
                    if (file_exists($filePath)) {
                        ?>
                        <script type="text/javascript" src="<?php echo base_url() . $filePath; ?>"></script>             
                        <?php
                    }
                }
            }
            ?>
    </head>
	<body>
