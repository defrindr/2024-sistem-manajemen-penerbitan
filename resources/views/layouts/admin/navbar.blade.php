<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body"> <!--begin::Container-->
    <div class="container-fluid"> <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i> </a> </li>
            {{-- <li class="nav-item d-none d-md-block"> <a href="#" class="nav-link">Home</a> </li>
            <li class="nav-item d-none d-md-block"> <a href="#" class="nav-link">Contact</a> </li> --}}
        </ul> <!--end::Start Navbar Links--> <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto"> <!--begin::Navbar Search-->
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item"> <a class="nav-link" href="#" data-lte-toggle="fullscreen"> <i
                        data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i> <i data-lte-icon="minimize"
                        class="bi bi-fullscreen-exit" style="display: none;"></i>
                </a>
            </li> <!--end::Fullscreen Toggle-->

            <!--end::Messages Dropdown Menu-->
            @admin(true)
                <!--begin::Notifications Dropdown Menu-->
                <li class="nav-item dropdown"> <a class="nav-link" data-bs-toggle="dropdown" href="#"> <i
                            class="bi bi-bell-fill"></i> <span
                            class="navbar-badge badge text-bg-warning">{{ \App\Models\Notification::getUnReadFromAscCount() }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        @foreach (\App\Models\Notification::getUnReadFromDesc() as $item)
                            <a href="{{ route('notification.read', $item->id) }}" target="blank" class="dropdown-item">
                                {{ $item->descriptionMin }}
                                <span class="float-end text-secondary fs-7">{{ $item->timelapse }}</span>
                            </a>
                            <div class="dropdown-divider"></div>
                        @endforeach
                        <a href="{{ route('notification') }}" target="blank" class="dropdown-item">
                            Selengkapnya
                        </a>
                    </div>
                </li> <!--end::Notifications Dropdown Menu-->
            @endadmin
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu"> <a href="#" class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"> <img src="{{ asset('dist/assets/img/user2-160x160.jpg') }}"
                        class="user-image rounded-circle shadow" alt="User Image"> <span
                        class="d-none d-md-inline">{{ Illuminate\Support\Facades\Auth::user()->name }}</span> </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end"> <!--begin::User Image-->
                    <li class="user-header text-bg-primary"> <img src="{{ asset('dist/assets/img/user2-160x160.jpg') }}"
                            class="rounded-circle shadow" alt="User Image">
                        <p>
                            {{ Illuminate\Support\Facades\Auth::user()->name }}
                            <small>{{ Illuminate\Support\Facades\Auth::user()->role->name }}</small>
                        </p>
                    </li> <!--end::User Image--> <!--begin::Menu Body-->
                    <!--begin::Menu Footer-->
                    <li class="user-footer">
                        {{-- <a href="#" class="btn btn-default btn-flat">Profile</a> --}}
                        <form action="{{ route('logout.action') }}" method="post">
                            @csrf
                            <button class="btn btn-default btn-flat float-end">Sign out</button>
                        </form>
                    </li>
                    <!--end::Menu Footer-->
                </ul>
            </li> <!--end::User Menu Dropdown-->
        </ul> <!--end::End Navbar Links-->
    </div> <!--end::Container-->
</nav> <!--end::Header-->
