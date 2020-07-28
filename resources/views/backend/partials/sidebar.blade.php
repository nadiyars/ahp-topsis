<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{route('admin.dashboard')}}">{{env('APP_NAME')}}</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{route('admin.dashboard')}}">Small Title</a>
    </div>
    <ul class="sidebar-menu">
      <li class="{{active("admin/dashboard*")}}"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
      <li class="{{active("admin/ahp*")}}"><a class="nav-link" href="{{ route('admin.ahp.index') }}"><i class="fas fa-fire"></i> <span>Ahp</span></a></li>
      <li><a class="nav-link" href="#"><i class="fas fa-fire"></i> <span>Topsis</span></a></li>
    </ul>

    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
      <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
        <i class="fas fa-rocket"></i> Documentation
      </a>
    </div>
  </aside>
</div>
