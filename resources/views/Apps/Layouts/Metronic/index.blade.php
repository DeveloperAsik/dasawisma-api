<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <title><?php echo isset($title_for_layout) ? $title_for_layout : ''; ?></title>
        @include('Apps.Layouts.Metronic.Includes.index.include_meta') 
        @include('Apps.Layouts.Metronic.Includes.index.include_css') 
    </head>
    <body class="page-sidebar-closed-hide-logo page-sidebar-closed-hide-logo page-header-fixed">
        <div id="loading-lottie" ><div id="loading"></div></div>
        <!-- BEGIN HEADER -->
        @include('Apps.Layouts.Metronic.Includes.index.header') 
        <!-- END HEADER -->
        <div class="clearfix"></div>
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            @include('Apps.Layouts.Metronic.Includes.index.sidebar') 
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <div class="page-content">
                    <!-- BEGIN PAGE HEAD -->
                    @include('Apps.Layouts.Metronic.Includes.index.toolbar') 
                    <!-- END PAGE HEAD -->
                    <!-- BEGIN PAGE BREADCRUMB -->
                    @include('Apps.Layouts.Metronic.Includes.index.breadcrumb') 
                    <!-- END PAGE BREADCRUMB -->
                    <?php if ($_path_content_app): ?>
                        @include("{$_path_content_app}")
                    <?php endif; ?>
                    <!-- END PAGE CONTENT INNER -->
                </div>
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->
        @include('Apps.Layouts.Metronic.Includes.index.footer') 
        @include('Apps.Layouts.Metronic.Includes.index.include_js') 
    </body>
    <!-- END BODY -->
</html>