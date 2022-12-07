<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.head')
</head>
@include('sweetalert::alert')
<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>
          <!-- <div class="search-element">
            <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="250">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
            <div class="search-backdrop"></div>
            <div class="search-result">
            </div>
          </div> -->
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="{{asset('assets/img/avatar/avatar-1.png')}}" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">{{Auth::user()->name}}</div></a>
            <div class="dropdown-menu dropdown-menu-right">
              {{--<a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>--}}
            <a class="dropdown-item has-icon text-danger" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>{{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            </div>
          </li>
        </ul>
      </nav>
      @include('template.sidebar')

      <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                @yield('content')
            </section>
        </div>
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; 2022 <div class="bullet"></div> Design By Safari
        </div>
        <div class="footer-right">
          
        </div>
      </footer>
    </div>
  </div>

  <!--Tempat JS-->
  @include('template.footer')
</body>
</html>