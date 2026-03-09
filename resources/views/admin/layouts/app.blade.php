<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}">

        <title>Mirror Booth Dubai | Dashboard</title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <link rel="stylesheet" href="{{ asset('admin_assets/assets/plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin_assets/assets/css/adminlte.min.css?v=3.2.0') }}">
        <link rel="stylesheet" href="{{ asset('admin_assets/assets/plugins/summernote/summernote-bs4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin_assets/assets/plugins/dropzone/dropzone.css') }}">
        <link rel="stylesheet" href="{{ asset('admin_assets/assets/plugins/jquery-ui/jquery-ui.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin_assets/assets/css/custom.css') }}">
        <link rel="shortcut icon" href="{{ asset('assets/favicon.ico')}}" type="image/x-icon">
        <!-- Favicon for Desktop and Mobile -->
        <link rel="shortcut icon" href="{{ asset('assets/favicon.ico')}}" type="image/x-icon">

        <!-- Apple Touch Icon (for iOS and iPad) -->
        <link rel="apple-touch-icon" href="{{ asset('assets/apple-touch-icon.png') }}" sizes="180x180">

        <!-- Android Icon -->
        <link rel="icon" href="{{ asset('assets/android-chrome-192x192.png') }}" sizes="192x192" type="image/png">

        <!-- For higher resolution Android devices -->
        <link rel="icon" href="{{ asset('assets/android-chrome-512x512.png') }}" sizes="512x512" type="image/png">
    </head>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="{{ route('admin.dashboard') }}" class="brand-link bg-white" style="height: 57px;">
					<!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
					{{-- <img src="../images/logo.png" alt="" class="w-75 ml-3" style="margin-top:-20px"> --}}
                    <h4>Mirror Booth Dubai</h4>
					<!-- <span class="brand-text font-weight-light">AdminLTE 3</span> -->
				  </a>
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('categoryList') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Category</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('serviceList') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Services</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('bloglist') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Blog</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('faqList') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Faq</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reviewList') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Review</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('companyList') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Company</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('packageList') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Packages</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('brandingList') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Branding</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('addonList') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Addon</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('locationList') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Locations</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('advanceSetupList') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Advance Setup</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('hoursList') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manage Hours</p>
                                </a>
                            </li>
							<li class="nav-item">
                                <a href="{{ route('settings.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Settings</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('quote.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Quote Calculator</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.logout') }}" class="nav-link">
                                    <i class='fas fa-sign-out-alt nav-icon'></i>
                                    <p>Logout</p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @yield('content')
            </div>

            <!-- /.content-wrapper -->


            <footer class="main-footer">
                <strong>Copyright &copy; Mirror Booth Dubai 2024 </strong> <a href="https://www.linkedin.com/in/naimulalambd/">Develope By Naimul Alam</a>
            </footer>
            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->
        <script src="{{ asset('admin_assets/assets/plugins/jquery/jquery.min.js') }}"></script>

        <script src="{{ asset('admin_assets/assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

        <!-- Reassign jQuery UI tooltip to avoid conflict with Bootstrap -->
        <script>
            $.widget.bridge('uitooltip', $.ui.tooltip); // Move jQuery UI's tooltip
        </script>

        <script src="{{ asset('admin_assets/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <script src="{{ asset('admin_assets/assets/js/adminlte.min.js?v=3.2.0') }}"></script>

        <!-- Summernote -->
        <script src="{{ asset('admin_assets/assets/plugins/summernote/summernote-bs4.min.js') }}"></script>

        <script src="{{ asset('admin_assets/assets/plugins/dropzone/dropzone.js') }}"></script>

        <script type="text/javascript">
        $(document).ready(function(){
                $('.summernote').summernote({
                    height: 300,
                    callbacks: {
                        onImageUpload: function(files) {
                            uploadImage(files[0], $(this));
                        }
                    }
                });

                function uploadImage(file, editor) {
                    let data = new FormData();
                    data.append("image", file);

                    $.ajax({
                        url: "{{ route('summernote.image.upload') }}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        data: data,
                        contentType: false,
                        processData: false,
                        success: function (url) {
                            editor.summernote('insertImage', url);
                        },
                        error: function (xhr, status, error) {
                            alert("Image upload failed: " + error);
                        }
                    });
                }
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
        </script>

        @yield('extraJs')
    </body>
</html>
