<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Browse <span class="caret"></span></a>

                    <ul class="dropdown-menu">
                        <li><a href="/threads">All Threads</a></li>

                        @if (auth()->check())
                            <li><a href="/threads?by={{ auth()->user()->name }}">My Threads</a></li>
                        @endif

                        <li><a href="/threads?popular=1">Popular Threads</a></li>
                        <li><a href="/threads?unanswered=1">Unanswered Threads</a></li>
                    </ul>
                </li>

                <li>
                    <a href="/threads/create">New Thread</a>
                </li>

                <channel-dropdown :channels="{{ $channels }}"></channel-dropdown>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @else
                    <user-notifications></user-notifications>

                    @if (Auth::user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard.index') }}"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a></li>
                    @endif

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ route('profile', Auth::user()) }}">My Profile</a>
                            </li>

                            <li>
                                <logout-button route="{{ route('logout') }}">Logout</logout-button>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
