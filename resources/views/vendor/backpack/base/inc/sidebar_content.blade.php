<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class='nav-item'><a class='nav-link' href="{{ backpack_url('dashboard') }}"><i class="nav-icon fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
<li class='nav-item'><a class='nav-link' href="{{ backpack_url('expense') }}"><i class="nav-icon fa fa-money"></i> <span>Spese</span></a></li>
<li class='nav-item'><a class='nav-link' href="{{ backpack_url('periodic') }}"><i class="nav-icon fa fa-refresh"></i> <span>Ricorrenze</span></a></li>
<li class='nav-item'><a class='nav-link' href="{{ backpack_url('debit') }}"><i class="nav-icon fa fa-forward"></i> <span>Debiti e crediti</span></a></li>
<li class='nav-item'><a class='nav-link' href="{{ backpack_url('budget') }}"><i class="nav-icon fa fa-pie-chart"></i> <span>Budget</span></a></li>
<li class='nav-item'><a class='nav-link' href="{{ backpack_url('category') }}"><i class="nav-icon fa fa-list-alt"></i> <span>Categorie</span></a></li>
<li class='nav-item'><a class='nav-link' href="{{ backpack_url('backup') }}"><i class="nav-icon fa fa-hdd-o"></i> <span>Backups</span></a></li>
<li class='nav-item'><a class='nav-link' href="{{ backpack_url('elfinder') }}"><i class="nav-icon fa fa-files-o"></i> <span>File Manager</span></a></li>
<!-- Users, Roles Permissions -->
  <li class="nav-item nav-dropdown">
    <a class='nav-link nav-dropdown-toggle' href="#"><i class="nav-icon fa fa-group"></i> <span>Utenti, Ruoli, Permessi</span></a>
    <ul class="nav-dropdown-items">
      <li class='nav-item'><a class='nav-link' href="{{ url(config('backpack.base.route_prefix', 'admin') . '/user') }}"><i class="nav-icon fa fa-user"></i> <span>Utenti</span></a></li>
      <li class='nav-item'><a class='nav-link' href="{{ url(config('backpack.base.route_prefix', 'admin') . '/role') }}"><i class="nav-icon fa fa-group"></i> <span>Ruoli</span></a></li>
      <li class='nav-item'><a class='nav-link' href="{{ url(config('backpack.base.route_prefix', 'admin') . '/permission') }}"><i class="nav-icon fa fa-key"></i> <span>Permessi</span></a></li>
    </ul>
  </li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}"><i class="nav-icon fa fa-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>