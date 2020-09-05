<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="<?php echo $_path_templates ?>/metronic/assets/global/plugins/respond.min.js"></script>
<script src="<?php echo $_path_templates ?>/metronic/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="<?php echo $_path_templates ?>/metronic/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $_path_templates ?>/metronic/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="<?php echo $_path_templates ?>/metronic/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo $_path_templates ?>/metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo $_path_templates ?>/metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo $_path_templates ?>/metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo $_path_templates ?>/metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo $_path_templates ?>/metronic/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo $_path_templates ?>/metronic/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="<?php echo $_path_templates ?>/metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->

<script src="<?php echo $_path_js . '/base64.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $_path_js . '/dateFormat.min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $_path_libs . '/toastr/build/toastr.min.js'; ?>"></script>
<script src="<?php echo $_path_libs . '/bodymovin/5.6.5/lottie.js'; ?>"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!--render global variable from session's-->
<script>
<?php if (isset($_app_uuid) && !empty($_app_uuid)): ?>
        var _app_uuid = '<?php echo $_app_uuid; ?>';
<?php else: ?>
        var _app_uuid = '';
<?php endif; ?>
<?php if (isset($_config_base_url) && !empty($_config_base_url)): ?>
        var _config_base_url = '<?php echo $_config_base_url; ?>';
<?php else: ?>
        var _config_base_url = '';
<?php endif; ?>
<?php if (isset($_path_templates) && !empty($_path_templates)): ?>
        var _config_lib_url = '<?php echo $_path_templates; ?>';
<?php else: ?>
        var _config_lib_url = '';
<?php endif; ?>
<?php if (isset($_config_img_url) && !empty($_config_img_url)): ?>
        var _config_img_url = '<?php echo $_config_img_url; ?>';
<?php else: ?>
        var _config_img_url = '';
<?php endif; ?>

<?php if (isset($_token) && !empty($_token)) : ?>
        var _token = '<?php echo $_token; ?>';
<?php else: ?>
        var _token = '';
<?php endif; ?>
<?php if (isset($_is_logged_in) && !empty($_is_logged_in)) : ?>
        var _is_logged_in = '<?php echo $_is_logged_in; ?>';
<?php else: ?>
        var _is_logged_in = '';
<?php endif; ?>
<?php if (isset($_path_json) && !empty($_path_json)) : ?>
        var _path_json = '<?php echo $_path_json; ?>';
<?php else: ?>
        var _path_json = '';
<?php endif; ?>
</script>

<!-- load system variable to js function start here -->
<?php if (isset($js_var) && !empty($js_var)): ?>
    <script>
    <?php foreach ($js_var AS $key => $values): ?>
        <?php foreach ($values AS $k => $v): ?>
            <?php if ($k == 'app'): ?>
                <?php foreach ($v AS $j => $n): ?>
                        var <?php echo $j; ?> = '<?php echo $n ?>';
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ($k == 'config'): ?>
                <?php foreach ($v AS $j => $n): ?>
                        var <?php echo $j; ?> = '<?php echo $n ?>';
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ($k == 'path'): ?>
                <?php foreach ($v AS $j => $n): ?>
                        var <?php echo $j; ?> = '<?php echo $n ?>';
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ($k == 'options'): ?>
                <?php foreach ($v AS $j => $n): ?>
                        var <?php echo $j; ?> = '<?php echo $n ?>';
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ($k == 'appUri'): ?>
                <?php foreach ($v AS $j => $n): ?>
                        var <?php echo $j; ?> = '<?php echo $n ?>';
                <?php endforeach; ?>
            <?php endif; ?>

        <?php endforeach; ?>
    <?php endforeach; ?>
    </script>
<?php endif; ?>
<!-- load system variable to js function start here -->

<!-- load variable to js function from controller start here -->
<?php if (isset($load_ajax_var) && !empty($load_ajax_var)) : ?>
    <?php foreach ($load_ajax_var AS $key => $values): ?>
        <script> var <?php echo $values['key']; ?> = '<?php echo $values['val'] ?>';</script>
    <?php endforeach; ?>
<?php endif; ?>
<!-- load variable to js function from controller end here -->

<!-- load js lib / class / library from controller start here -->
<?php if (isset($load_js) && !empty($load_js)) : ?>
    <?php foreach ($load_js AS $key => $values) : ?>
        <script src="<?php echo $_path_templates . $values ?>" type="text/javascript"></script>
    <?php endforeach; ?>
<?php endif; ?>
<!-- load js lib / class / library from controller end here -->

<!-- load specified js lib for a view end here -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo $_path_templates ?>/metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo $_path_templates ?>/metronic/assets/admin/layout4/scripts/layout.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    jQuery(document).ready(function () {
        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
        //Demo.init(); // init demo features 
        //Index.init(); // init index page
        //Tasks.initDashboardWidget(); // init tash dashboard widget  
    });
</script>
<!-- END JAVASCRIPTS -->
<!-- load global js lib for every controller start here -->
<?php if ($_path_app_global_js): ?>
    @include("{$_path_app_global_js}")
<?php endif; ?>
<!-- load global js lib for every controller end here -->

<!-- load specified js lib for a view start here -->
<?php if ($_path_app_view_js): ?>
    @include("{$_path_app_view_js}")
<?php endif; ?>