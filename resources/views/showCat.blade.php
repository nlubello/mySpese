@extends(backpack_view('blank'))


@section('content')
<div class="row">
  <div class="col-xs-12" style="margin: 10px;">
      <a href="{{ backpack_url('category') }}"><i class="fa fa-angle-double-left"></i> Torna a tutte le  <span>Categorie</span></a>
  </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <div class="card">
      <div class="card-header">
        <h3>Report della categoria {{ $crud->name }}

          <a class="btn btn-ghost-primary p-1 float-right" href="{{backpack_url('category').'/'.$crud->id.'/edit'}}">
            <i class="fa fa-edit"></i>
          </a>
        </h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="row">
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              <span class="description-percentage text-red hidden"><i class="fa fa-caret-left"></i> N/A %</span>
              <h5 class="description-header">{{number_format($tExp, 2, '.', '')}} &euro;</h5>
              <span class="description-text">SPESA COMPLESSIVA</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              <span class="description-percentage text-green hidden"><i class="fa fa-caret-left"></i> N/A %</span>
              <h5 class="description-header">{{number_format($tProf, 2, '.', '')}} &euro;</h5>
              <span class="description-text">RICAVO COMPLESSIVO</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block">
              @php
              if($crud['budget_expense'] > 0){
                $value = intval($yExp / $crud['budget_expense'] * 100) - 100;
              } else {
                $value = 0;
              }

              if($value > 0){
                $class = 'fa-caret-up';
                $txtColor = 'text-red';
              } elseif ($value == 0) {
                $class = 'fa-caret-right';
                $txtColor = '';
              } else {
                $class = 'fa-caret-down';
                $txtColor = 'text-green';
              }
              @endphp
              <span class="description-percentage {{$txtColor}}"><i class="fa {{$class}}"></i> {{$value}}%</span>
              <h5 class="description-header">{{number_format($yExp, 2, '.', '')}} &euro;</h5>
              <span class="description-text">SPESA MEDIA ANNUALE</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block">
              @php
              if($crud['budget_income'] > 0){
                $value = intval($yProf / $crud['budget_income'] * 100) - 100;
              } else {
                $value = 0;
              }
              
              if($value > 0){
                $class = 'fa-caret-up';
                $txtColor = 'text-green';
              } elseif ($value == 0) {
                $class = 'fa-caret-right';
                $txtColor = '';
              } else {
                $class = 'fa-caret-down';
                $txtColor = 'text-red';
              }
              @endphp
              <span class="description-percentage {{$txtColor}}"><i class="fa {{$class}}"></i> {{$value}}%</span>
              <h5 class="description-header">{{number_format($yProf, 2, '.', '')}} &euro;</h5>
              <span class="description-text">RICAVO MEDIO ANNUALE</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              <span class="description-percentage text-green hidden"><i class="fa fa-caret-up"></i> 20%</span>
              <h5 class="description-header">{{number_format($crud['budget_expense'], 2, '.', '')}} &euro;</h5>
              <span class="description-text">BUDGET ANNUALE SPESA</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block">
              <span class="description-percentage text-red hidden"><i class="fa fa-caret-down"></i> 18%</span>
              <h5 class="description-header">{{number_format($crud['budget_income'], 2, '.', '')}} &euro;</h5>
              <span class="description-text">BUDGET ANNUALE INCASSI</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              @php
              if($crud['budget_expense'] > 0){
                $value = intval($mExp / ($crud['budget_expense'] / 12) * 100) - 100;
              } else {
                $value = 0;
              }
              
              if($value > 0){
                $class = 'fa-caret-up';
                $txtColor = 'text-red';
              } elseif ($value == 0) {
                $class = 'fa-caret-right';
                $txtColor = '';
              } else {
                $class = 'fa-caret-down';
                $txtColor = 'text-green';
              }
              @endphp
              <span class="description-percentage {{$txtColor}}"><i class="fa {{$class}}"></i> {{$value}}%</span>
              <h5 class="description-header">{{number_format($mExp, 2, '.', '')}} &euro;</h5>
              <span class="description-text">SPESA MEDIA MENSILE</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block">
              @php
              if($crud['budget_income'] > 0){
                $value = intval($mProf / ($crud['budget_income'] / 12) * 100) - 100;
              } else {
                $value = 0;
              }
              
              if($value > 0){
                $class = 'fa-caret-up';
                $txtColor = 'text-green';
              } elseif ($value == 0) {
                $class = 'fa-caret-right';
                $txtColor = '';
              } else {
                $class = 'fa-caret-down';
                $txtColor = 'text-red';
              }
              @endphp
              <span class="description-percentage {{$txtColor}}"><i class="fa {{$class}}"></i> {{$value}}%</span>
              <h5 class="description-header">{{number_format($mProf, 2, '.', '')}} &euro;</h5>
              <span class="description-text">RICAVO MEDIO MENSILE</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.card-footer -->
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-4 col-xs-12">
    <div class="card card-default">
      <div class="card-header with-border">
          <div class="card-title">Le movimentazioni</div>
      </div>

      <div class="card-body no-padding">
        <table class="table">
          <tbody>
          <tr>
            <th>Nome</th>
            <th>Data</th>
            <th style="width: 50px">Importo</th>
            <th>Azioni</th>
          </tr>
          @foreach($expenses as $m)
          <tr class="clickable" data-href="{{backpack_url('expense') . '/' . $m->id . '/edit' }}">
            <td>{{$m->name}}</td>
            <td>{{Date::parse($m->expensed_at)->format('d/m/yy')}}</td>
            <td>
              @if($m->type == 0)
              <span class="badge bg-red">
              @elseif($m->type == 1)
              <span class="badge bg-green">
              @endif
              {{$m->amount}} &euro;</span></td>
            <td>
              <a class="btn btn-xs btn-default" href="{{backpack_url('expense').'/'.$m->id.'/edit'}}"><i class="fa fa-edit"></i></a>
            </td>
          </tr>
          @endforeach
          </tbody>
        </table>
        {{ $expenses->links() }}
      </div>
    </div>
  </div>
  <div class="col-lg-8 col-xs-12">
    <div class="card card-default">
        <div class="card-header with-border">
            <div class="card-title">Statistiche ultimi 12 mesi</div>
        </div>

        <div class="card-body"><div id="line-m"></div></div>
    </div>
    <div class="card card-default">
        <div class="card-header with-border">
            <div class="card-title">Statistiche ultimi anni</div>
        </div>

        <div class="card-body"><div id="line-y"></div></div>
    </div>
  </div>
</div>
@endsection

@section('after_styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">


@endsection

@section('after_scripts')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<script>
$( document ).ready(function() {
  Morris.Line({
    element: 'line-m',
    data: {!!json_encode($statM)!!},
    xkey: 'y',
    ykeys: ['in', 'out'],
    labels: ['Entrate', 'Uscite'],
    xLabels: 'month',
    lineColors: ['#5cb85c', '#d9534f'],
    goals: [{{$mProf}}, {{$mExp}}, {{$crud['budget_income']/12}}, {{$crud['budget_expense']/12}}],
    goalLineColors: ['#5cb873', '#d9764f', '#ff4500', '#ff8500']
    });

  Morris.Bar({
    element: 'line-y',
    data: {!!json_encode($statY)!!},
    xkey: 'y',
    ykeys: ['in', 'out'],
    labels: ['Entrate', 'Uscite'],
    xLabels: 'year',
    barColors: ['#5cb85c', '#d9534f'],
    goals: [{{$yProf}}, {{$yExp}}],
    goalLineColors: ['#5cb873', '#d9764f']
    });

});
</script>

@endsection
