<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo isset($title_for_layout) ? $title_for_layout : ''; ?></title>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        @include('Apps.Layouts.Metronic.Includes.index_login.include_css') 
    </head>
    <!-- BEGIN BODY -->
    <body class="login">
        <?php if ($_path_content_app): ?>
            @include("{$_path_content_app}")
        <?php endif; ?>
        @include('Apps.Layouts.Metronic.Includes.index_login.include_js') 
    </body>
    <!-- END BODY -->
</html>