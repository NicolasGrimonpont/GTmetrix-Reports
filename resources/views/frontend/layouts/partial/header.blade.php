{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-dark navbar-stick-dark" data-navbar="sticky">
    <div class="container-fluid">

        <div class="navbar-left">
            <button class="navbar-toggler" type="button"><span class="navbar-toggler-icon"></span></button>
            <a class="navbar-brand text-muted fw-600" href="{{ url('/') }}">GTmetrix</a>
            {{-- <span class="navbar-divider"></span> --}}

        </div>

        <section class="navbar-mobile">

            <nav class="nav nav-navbar mr-auto">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/') }}"
                            class="nav-link {{ request()->is('reports') ? 'active' : '' }}">Reports</a>
                    @endauth
                @endif
            </nav>

            {{-- Login --}}
            @if (Route::has('login'))
                @auth
                    @if (request()->is('/'))
                        <a href="#" class="btn btn-sm btn-round btn-primary mr-4" data-toggle="modal"
                            data-target="#modal">Upload</a>
                    @endif
                @else
                    <div>
                        <a class="btn btn-sm btn-round btn-primary ml-lg-4 mr-4" href="{{ route('login') }}">Log
                            in</a>
                        @if (Route::has('register'))
                            <a class="btn btn-sm btn-round btn-outline-primary"
                                href="{{ route('register') }}">Register</a>
                        @endif
                    </div>
                @endauth
            @endif
            {{-- ./Login --}}

            <span class="navbar-divider"></span>

            {{-- Welcome --}}
            @if (Route::has('login'))
                @auth
                    <ul class="nav nav-navbar">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Welcome {{ Auth::user()->name }} <span class="arrow"></span></a>
                            <nav class="nav align-right">
                                <a href="{{ route('monitoring') }}" class="nav-link">Monitoring</a>
                                <a href="{{ route('settings') }}" class="nav-link">Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" class="nav-link"
                                        onclick="event.preventDefault(); this.closest('form').submit();">Log out</a>
                                </form>
                            </nav>
                        </li>
                    </ul>
                @endauth
            @endif
            {{-- ./Welcome --}}

        </section>



    </div>
</nav>
{{-- /.navbar --}}
