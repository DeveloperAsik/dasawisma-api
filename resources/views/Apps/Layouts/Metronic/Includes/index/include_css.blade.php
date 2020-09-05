<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css">
<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="<?php echo $_path_templates ?>/metronic/assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE STYLES -->
<!-- BEGIN THEME STYLES -->
<!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
<link href="<?php echo $_path_templates ?>/metronic/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/admin/layout4/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/admin/layout4/css/themes/light.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="<?php echo $_path_templates ?>/metronic/assets/admin/layout4/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->

<!-- load css lib / class / library from controller start here -->
<?php if (isset($load_css) && !empty($load_css)) : ?>
    <?php foreach ($load_css AS $key => $values) : ?>
        <link href="<?php echo $_path_templates . $values ?>" rel="stylesheet" type="text/css"/>
    <?php endforeach; ?>
<?php endif; ?>
<!-- load css lib / class / library from controller end here -->

<link rel="shortcut icon" href="favicon.ico"/>

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

    .result_monitor{
        position: fixed;
        top:210px;
        right:40px;
        height:200px;
        background-color: #ccc;
        padding:20px;
        border:1px solid #000;
        border-radius:4px;
    }
    .td_bd{
        width:200px;
    }
    .tryit {
        width:80px;
    }
    .submit_tryit{
        width:80px;
    }
    .emp{
        width: 19%;
    }
    .btn-menu-act{
        position: fixed;
        top:40%;
        right:40%;
        z-index: 99999
    }
</style>