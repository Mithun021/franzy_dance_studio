<!-- Left Sidebar Start -->
            <div class="app-sidebar-menu">
                <div class="h-100" data-simplebar>

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">

                        <div class="logo-box">
                            <a href="{{ route('admindashboard.get') }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('images/logo.png') }}" alt="" height="22">
                                    <span>FRANZY DANCE STUDIO</span>
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('images/logo.png') }}" alt="" height="24">
                                    <span>FRANZY DANCE STUDIO</span>
                                </span>
                            </a>
                            <a href="{{ route('admindashboard.get') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('images/logo.png') }}" alt="" height="22">
                                    <span>FRANZY DANCE STUDIO</span>
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('images/logo.png') }}" alt="" height="24">
                                    <span>FRANZY DANCE STUDIO</span>
                                </span>
                            </a>
                        </div>

                        <ul id="side-menu">

                            <li class="menu-title">Menu</li>

                            <li>
                                <a href="{{ route('admindashboard.get') }}" class="tp-link">
                                    <i data-feather="home"></i>
                                    <span> Dashboard </span>
                                </a>
                            </li>

                            <li class="menu-title">Pages</li>

                            <li>
                                <a href="#billing" data-bs-toggle="collapse">
                                    <i data-feather="dollar-sign"></i>
                                    <span> Billing </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="billing">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('billing.create') }}" class="tp-link">New Billing</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('billing.index') }}" class="tp-link">Billing List</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#users" data-bs-toggle="collapse">
                                    <i data-feather="users"></i>
                                    <span> Users </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="users">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('employee') }}" class="tp-link">Employee/Faculty List</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('student.list') }}" class="tp-link">Student List</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#holiday" data-bs-toggle="collapse">
                                    <i data-feather="calendar"></i>
                                    <span> Holiday </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="holiday">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('holidays.create') }}" class="tp-link">Create New</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('holidays.index') }}" class="tp-link">Holiday List</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#attendance" data-bs-toggle="collapse">
                                    <i data-feather="calendar"></i>
                                    <span> Attendance </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="attendance">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('attendance.create') }}" class="tp-link">Create New</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('attendance.index') }}" class="tp-link">Attendance List</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#certificate" data-bs-toggle="collapse">
                                    <i data-feather="calendar"></i>
                                    <span> Certificate </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="certificate">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('certificate.create') }}" class="tp-link">Create New</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('certificate.index') }}" class="tp-link">Certificate List</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#expense" data-bs-toggle="collapse">
                                    <i data-feather="calendar"></i>
                                    <span> Expense </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="expense">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('expense.create') }}" class="tp-link">Create New</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('expense.index') }}" class="tp-link">Expense List</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#salary" data-bs-toggle="collapse">
                                    <i data-feather="calendar"></i>
                                    <span> Salary Management </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="salary">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('salary-management.create') }}" class="tp-link">Create New</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('salary-management.index') }}" class="tp-link">Salary List</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#studeio" data-bs-toggle="collapse">
                                    <i data-feather="calendar"></i>
                                    <span> Studio Management </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="studeio">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('studio.create') }}" class="tp-link">Create New</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('studio.index') }}" class="tp-link">Studio List</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('studio-category.index') }}" class="tp-link">Studio Categories</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#studio-booking" data-bs-toggle="collapse">
                                    <i data-feather="calendar"></i>
                                    <span> Studio Booking </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="studio-booking">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('studio-booked.index') }}" class="tp-link">Booking List</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#syllabus" data-bs-toggle="collapse">
                                    <i data-feather="calendar"></i>
                                    <span> Syllabus </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="syllabus">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('syllabus.index') }}" class="tp-link">Syllabus List</a>
                                            <a href="{{ route('syllabus.create') }}" class="tp-link">Create Syllabus</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#paymentRecords" data-bs-toggle="collapse">
                                    <i data-feather="calendar"></i>
                                    <span> Payment Records </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="paymentRecords">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('course.payment.index') }}" class="tp-link">Course Payment</a>
                                            <a href="{{ route('studio-payment.history') }}" class="tp-link">Studio Payment</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#master" data-bs-toggle="collapse">
                                    <i data-feather="settings"></i>
                                    <span> Master </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="master">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('category.index') }}" class="tp-link">Categories</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('level.index') }}" class="tp-link">Levels</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('courses.index') }}" class="tp-link">Courses</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('batches.index') }}" class="tp-link">Batches</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('fee-structures.index') }}" class="tp-link">Fee Structures</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('late-fines.index') }}" class="tp-link">Late Fines</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('rules.index') }}" class="tp-link">Rules</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            {{-- <li>
                                <a href="widgets.html" class="tp-link">
                                    <i data-feather="aperture"></i>
                                    <span> Widgets </span>
                                </a>
                            </li> --}}

                            <li>
                                <a href="#sidebarMaps" data-bs-toggle="collapse">
                                    <i data-feather="settings"></i>
                                    <span> Settings </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarMaps">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{ route('roles') }}" class="tp-link">Roles</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('permission') }}" class="tp-link">Permissions</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('permission-categories.index') }}" class="tp-link">Permissions Category</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                        </ul>

                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
            </div>
            <!-- Left Sidebar End -->
