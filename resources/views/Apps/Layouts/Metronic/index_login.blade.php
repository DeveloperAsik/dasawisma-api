<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo isset($title_for_layout) ? $title_for_layout : ''; ?></title>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        @include('Apps.Layouts.Metronic.Includes.index_login.include_meta') 
        @include('Apps.Layouts.Metronic.Includes.index_login.include_css') 
    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <body class="login">
        <div id="loading-lottie" ><div id="loading"></div></div>
        @include('Apps.Layouts.Metronic.Includes.index_login.logo') 
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="menu-toggler sidebar-toggler"></div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
        <?php if ($_path_content_app): ?>
            @include("{$_path_content_app}")
        <?php endif; ?>
        <!-- END LOGIN -->
        @include('Apps.Layouts.Metronic.Includes.index_login.footer') 
        @include('Apps.Layouts.Metronic.Includes.index_login.include_js') 
    </body>
    <!-- END BODY -->
</html>