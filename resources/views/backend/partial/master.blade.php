@include('backend.partial.header')
@include('backend.partial.topbar')
@include('backend.partial.sidebar')
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Dashboard</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashbard</a></li>
                        <li class="breadcrumb-item active">@yield('title', 'Dcode Materials')</li>
                    </ol>
                </div>
            </div>
       
                    @yield('backend-content')
        
            </div> <!-- container-fluid -->

    </div> <!-- content -->

    <!-- Footer Start -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col fs-13 text-muted text-center">
                    &copy; <script>document.write(new Date().getFullYear())</script> - Made with <span class="mdi mdi-heart text-danger"></span> by <a href="#!" class="text-reset fw-semibold">Dcode Materials</a> 
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->

</div>
@include('backend.partial.footer')
