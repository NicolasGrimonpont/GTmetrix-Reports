{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-{{ request()->is('/') ? 'light' : 'dark' }} navbar-stick-dark"
    data-navbar="sticky">
    <div class="container">

        <div class="navbar-left">
            <button class="navbar-toggler" type="button"><span class="navbar-toggler-icon"></span></button>
            <a class="navbar-brand text-white fw-600" href="{{ url('/') }}">
                GTMETRIX REPORTS
            </a>
        </div>

        <section class="navbar-mobile">
            <ul class="nav nav-navbar ml-auto">
                <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                        href="{{ url('/') }}">Homepage</a></li>
            </ul>
        </section>

    </div>
</nav>
{{-- /.navbar --}}
