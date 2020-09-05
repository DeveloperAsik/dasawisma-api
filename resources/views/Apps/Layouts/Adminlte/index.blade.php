<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo isset($title_for_layout) ? $title_for_layout : ''; ?></title>
        @include('Layouts.Metronic.Includes.index.include_meta') 
        @include('Layouts.Metronic.Includes.index.include_css')
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            @include('Layouts.Metronic.Includes.index.navbar')
            @include('Layouts.Metronic.Includes.index.sidebar')
            <!-- Content Wrapper. Contains page content -->
            @include('Layouts.Metronic.Includes.index.content_header')
            <!-- Main content -->
            <div class="content">
                <?php if ($_path_contentPath): ?>
                    @include("{$_path_contentPath}")
                <?php endif; ?>
            </div>
            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
            @include('Layouts.Metronic.Includes.index.footer')
        </div>
        <!-- ./wrapper -->
        <!-- REQUIRED SCRIPTS -->
        @include('Layouts.Metronic.Includes.index.include_js')
    </body>
</html>