<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark"> <!--begin::Sidebar Brand-->
    <div class="sidebar-brand"> <!--begin::Brand Link--> <a href="/" class="brand-link">
            <!--begin::Brand Image-->
            <img src="{{ asset('dist/assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow"> <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">AdminLTE 4</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand--> <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2"> <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/" class="nav-link">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @sa
                    @include('layouts.admin._sidebar.sa')
                @endsa

                @admin
                    @include('layouts.admin._sidebar.admin')
                @endadmin
                @author
                @include('layouts.admin._sidebar.author')
                @endauthor
                @reviewer
                    @include('layouts.admin._sidebar.reviewer')
                @endreviewer
                <li class="nav-item">
                    <a href="{{ route('profile.me') }}" class="nav-link">
                        <i class="nav-icon bi bi-person"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('feedback.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-file"></i>
                        <p>
                            Feedback
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('manual-book') }}" class="nav-link">
                        <i class="nav-icon bi bi-file"></i>
                        <p>
                            Manual Book
                        </p>
                    </a>
                </li>

            </ul> <!--end::Sidebar Menu-->
        </nav>
    </div> <!--end::Sidebar Wrapper-->
</aside> <!--end::Sidebar-->
<!--begin::App Main-->
