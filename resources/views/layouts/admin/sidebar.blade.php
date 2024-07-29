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
                    <li class="nav-item">
                        <a href="/" class="nav-link">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>
                                Master User
                            </p>
                        </a>
                    </li>
                @endsa
                @admin
                    <li class="nav-item">
                        <a href="{{ route('ebook.siap-publish') }}" class="nav-link">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>
                                Karya Siap Publish
                            </p>
                        </a>
                    </li>
                @endadmin
                <li class="nav-item">
                    <a href="{{ route('theme.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Manajemen Topik
                        </p>
                    </a>
                </li>
                @author
                <li class="nav-item">
                    <a href="{{ route('ebook.me') }}" class="nav-link">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Karya Saya
                        </p>
                    </a>
                </li>
                @endauthor
                @reviewer
                    <li class="nav-item">
                        <a href="{{ route('ebook.butuhreview') }}" class="nav-link">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>
                                Butuh Review
                            </p>
                        </a>
                    </li>
                @endreviewer
            </ul> <!--end::Sidebar Menu-->
        </nav>
    </div> <!--end::Sidebar Wrapper-->
</aside> <!--end::Sidebar-->
<!--begin::App Main-->
