<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
        <a href="#">Schedule Maker</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
        <a href="#">SM</a>
        </div>
        <ul class="sidebar-menu">
        <li class="menu-header">Dashboard</li>
        <li class="dropdown">
            @if(Auth::user()->role == 'superadmin')
                @foreach($administrator as $role)
                    <a href="{{ route($role->action) }}" class="nav-link"><i class="fas fa-th"></i><span>{{$role->title}}</span></a>
                @endforeach
            @endif
            @foreach($menus as $menu)
                <a href="{{ route($menu->action) }}" class="nav-link"><i class="fas fa-th"></i><span>{{$menu->title}}</span></a>
            @endforeach
            {{--<a href="{{ route('divisi.index') }}" class="nav-link"><i class="fas fa-th"></i><span>Divisi</span></a>
            <a href="{{ route('karyawan.index') }}" class="nav-link"><i class="fas fa-th"></i><span>Karyawan</span></a>
            <a href="{{ route('schedule.index') }}" class="nav-link"><i class="fas fa-th"></i><span>Schedule</span></a>--}}
        </li>
    </aside>
</div>