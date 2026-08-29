<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl mt-3 mx-3 bg-primary" id="navbarBlur"
        data-scroll="false">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="">Dashboard</a></li>
                <li class="breadcrumb-item text-sm text-white active" aria-current="page">{{ $title }}</li>
            </ol>
            <h6 class="font-weight-bolder text-white mb-0">{{ $title }}</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="input-group">
                </div>
            </div>
            <ul class="navbar-nav  justify-content-end">
                
                <li class="nav-item d-xl-none ps-1 d-flex align-items-center">
                    <a role="button" class="nav-link text-white p-2" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                        </div>
                    </a>
                </li>


                <li class="nav-item dropdown pe-3 d-flex align-items-center">
                    <a role="button" class="nav-link text-white p-0" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('storage/users/' . (Auth::user()->foto ?? 'user.png')) }}" class="avatar avatar-sm">
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4"
                        aria-labelledby="dropdownMenuButton">
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="{{ route('perfil') }}">
                                <div class="d-flex py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <i class="ni ni-circle-08 me-1"></i>
                                            <span class="font-weight-bold">
                                                {{ Auth::user()->name }}</span>
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="ni ni-email-83 me-1"></i>
                                            {{ Auth::user()->email }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <hr class="horizontal dark mt-0">
                        <li>
                            <form id="logoutForm" method="POST" action="/logout">
                                <a href="javascript:void(0);" class="dropdown-item border-radius-md" id="logoutBtn">
                                    <div class="d-flex py-1">
                                        <div class="avatar avatar-sm me-3 my-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.5" d="M15.9998 2L14.9998 2C12.1714 2 10.7576 2.00023 9.87891 2.87891C9.00023 3.75759 9.00023 5.1718 9.00023 8.00023L9.00023 16.0002C9.00023 18.8287 9.00023 20.2429 9.87891 21.1215C10.7574 22 12.1706 22 14.9976 22L14.9998 22L15.9998 22C18.8282 22 20.2424 22 21.1211 21.1213C21.9998 20.2426 21.9998 18.8284 21.9998 16L21.9998 8L21.9998 7.99998C21.9998 5.17157 21.9998 3.75736 21.1211 2.87868C20.2424 2 18.8282 2 15.9998 2Z" fill="#1C274C"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.75 12C15.75 11.5858 15.4142 11.25 15 11.25L4.02744 11.25L5.98809 9.56943C6.30259 9.29986 6.33901 8.82639 6.06944 8.51189C5.79988 8.1974 5.3264 8.16098 5.01191 8.43054L1.51191 11.4305C1.34567 11.573 1.25 11.781 1.25 12C1.25 12.2189 1.34567 12.4269 1.51191 12.5694L5.01191 15.5694C5.3264 15.839 5.79988 15.8026 6.06944 15.4881C6.33901 15.1736 6.30259 14.7001 5.98809 14.4305L4.02744 12.75L15 12.75C15.4142 12.75 15.75 12.4142 15.75 12Z" fill="#1C274C"/>
                                            </svg>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="text-sm font-weight-normal mb-1">Log out</h6>
                                        </div>
                                    </div>
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>

                
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
