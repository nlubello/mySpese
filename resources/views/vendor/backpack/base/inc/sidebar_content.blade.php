<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
<li><a href="{{ backpack_url('expense') }}"><i class="fa fa-money"></i> <span>Spese</span></a></li>
<li><a href="{{ backpack_url('periodic') }}"><i class="fa fa-refresh"></i> <span>Ricorrenze</span></a></li>
<li><a href="{{ backpack_url('debit') }}"><i class="fa fa-forward"></i> <span>Debiti e crediti</span></a></li>
<li><a href="{{ backpack_url('category') }}"><i class="fa fa-list-alt"></i> <span>Categorie</span></a></li>
<li><a href="{{ backpack_url('backup') }}"><i class="fa fa-hdd-o"></i> <span>Backups</span></a></li>
<li><a href="{{ backpack_url('elfinder') }}"><i class="fa fa-files-o"></i> <span>File Manager</span></a></li>
<!-- Users, Roles Permissions -->
  <li class="treeview">
    <a href="#"><i class="fa fa-group"></i> <span>Utenti, Ruoli, Permessi</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
      <li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/user') }}"><i class="fa fa-user"></i> <span>Utenti</span></a></li>
      <li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/role') }}"><i class="fa fa-group"></i> <span>Ruoli</span></a></li>
      <li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/permission') }}"><i class="fa fa-key"></i> <span>Permessi</span></a></li>
    </ul>
  </li>
