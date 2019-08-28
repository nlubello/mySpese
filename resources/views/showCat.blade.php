@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        {{ $crud->name }} <small>categoria</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li><a href="{{ backpack_url('category') }}">Categorie</a></li>
        <li class="active">{{ $crud->name }}</li>
      </ol>
    </section>
@endsection


@section('content')
<div class="row">
  <div class="col-xs-12" style="margin: 10px;">
      <a href="{{ backpack_url('category') }}"><i class="fa fa-angle-double-left"></i> Torna a tutte le  <span>Categorie</span></a>
  </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Report della categoria</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse">
            <i class="fa fa-minus"></i>
          </button>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
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
            <div class="description-block border-right">
              <span class="description-percentage text-green hidden"><i class="fa fa-caret-up"></i> 20%</span>
              <h5 class="description-header">{{number_format($mExp, 2, '.', '')}} &euro;</h5>
              <span class="description-text">SPESA MEDIA MENSILE</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block">
              <span class="description-percentage text-red hidden"><i class="fa fa-caret-down"></i> 18%</span>
              <h5 class="description-header">{{number_format($mProf, 2, '.', '')}} &euro;</h5>
              <span class="description-text">RICAVO MEDIO MENSILE</span>
            </div>
            <!-- /.description-block -->
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.box-footer -->
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-4 col-xs-12">
    <div class="box box-default">
      <div class="box-header with-border">
          <div class="box-title">Le movimentazioni</div>
      </div>

      <div class="box-body no-padding">
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
            <td>{{Date::parse($m->expensed_at)->format(config('backpack.base.default_date_format'))}}</td>
            <td>
              @if($m->type == 0)
              <span class="badge bg-red">
              @elseif($m->type == 1)
              <span class="badge bg-green">
              @endif
              {{$m->amount}} &euro;</span></td>
            <td>
              <a class="btn btn-xs btn-default" href="{{backpack_url('expense').'/'.$m->id.'/edit'}}"><i class="fa fa-edit"></i> Modifica</a>
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
    <div class="box box-default">
        <div class="box-header with-border">
            <div class="box-title">Statistiche ultimi 12 mesi</div>
        </div>

        <div class="box-body"><div id="line-m"></div></div>
    </div>
    <div class="box box-default">
        <div class="box-header with-border">
            <div class="box-title">Statistiche ultimi anni</div>
        </div>

        <div class="box-body"><div id="line-y"></div></div>
    </div>
  </div>
</div>
@endsection

@section('after_styles')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/morris.js/morris.css">


@endsection

@section('after_scripts')
<script src="{{ asset('vendor/adminlte') }}/bower_components/raphael/raphael.min.js"></script>
<script src="{{ asset('vendor/adminlte') }}/bower_components/morris.js/morris.min.js"></script>

<script>
$( document ).ready(function() {
  Morris.Line({
    element: 'line-m',
    data: {!!json_encode($statM)!!},
    xkey: 'y',
    ykeys: ['in', 'out'],
    labels: ['Entrate', 'Uscite'],
    xLabels: 'month',
    lineColors: ['#5cb85c', '#d9534f']
    });

  Morris.Bar({
    element: 'line-y',
    data: {!!json_encode($statY)!!},
    xkey: 'y',
    ykeys: ['in', 'out'],
    labels: ['Entrate', 'Uscite'],
    xLabels: 'year',
    barColors: ['#5cb85c', '#d9534f']
    });

});
</script>

@endsection
