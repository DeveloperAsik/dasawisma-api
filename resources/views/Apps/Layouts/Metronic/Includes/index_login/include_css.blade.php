<link rel="shortcut icon" href="favicon.ico"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/admin/pages/css/login-soft.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="<?php echo $_path_libs . '/jquery/jqueryui/jquery-ui.css'; ?>">
<link rel="stylesheet" href="<?php echo $_path_libs . '/jquery/jqueryui/jquery-ui.theme.css'; ?>">
<link href="<?php echo $_path_libs . '/toastr/build/toastr.min.css'; ?>" rel="stylesheet"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="<?php echo $_path_templates ?>/metronic/assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="<?php echo $_path_templates ?>/metronic/assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<!-- load css lib / class / library from controller start here -->
<?php if (isset($load_css) && !empty($load_css)) : ?>
    <?php foreach ($load_css AS $key => $values) : ?>
        <link href="<?php echo $_config_lib_url . $values ?>" rel="stylesheet" type="text/css"/>
    <?php endforeach; ?>
<?php endif; ?>
<!-- load css lib / class / library from controller end here -->
<style>
    #loading-lottie{
        position: fixed;
        margin:0px auto;
        background-color: #000;
        width:100%;
        height:100%;
        display: none;
        opacity: 0.6;
        z-index: 9999;
    }
    #loading{
        position: fixed;
        top:45%;
        left:45%;
        z-index: 99999;
    }
</style>