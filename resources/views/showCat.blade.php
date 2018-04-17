@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        {{ $crud->name }} <small>categoria</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section>
@endsection


@section('content')
<div class="row">
  <div class="col-xs-12 text-center">

  </div>
</div>

<div class="row">
  <div class="col-lg-4 col-xs-12">
    <div class="box box-default">
      <div class="box-header with-border">
          <div class="box-title">Le ultime 10 movimentazioni</div>
      </div>

      <div class="box-body no-padding">
        <table class="table">
          <tbody>
          <tr>
            <th>Nome</th>
            <th>Data</th>
            <th style="width: 50px">Importo</th>
          </tr>
          @foreach($crud->expenses as $m)
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
          </tr>
          @endforeach
          </tbody>
        </table>
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

  Morris.Line({
    element: 'line-y',
    data: {!!json_encode($statY)!!},
    xkey: 'y',
    ykeys: ['in', 'out'],
    labels: ['Entrate', 'Uscite'],
    xLabels: 'year',
    lineColors: ['#5cb85c', '#d9534f']
    });

});
</script>

@endsection
