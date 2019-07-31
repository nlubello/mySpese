@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        {{ trans('backpack::base.dashboard') }}<small>{{ trans('backpack::base.first_page_you_see') }}</small>
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
      <nav aria-label="Month pagination">
        @php
        setlocale(LC_TIME, 'Italian');
        $dC = Carbon\Carbon::parse($now);
        @endphp

        <ul class="pagination">
          @php
            $d = (clone $dC)->subYear();
            $dTxt = $d->formatLocalized('%b %Y');
            $dUrl = $d->startOfMonth()->toDateString();
          @endphp
          <li class="page-item"><a class="page-link"
            href="{{ backpack_url('dashboard') . '?date=' . $dUrl }}">
            &laquo; {{ $dTxt }}
          </a></li>

          <li class="page-item disabled"><a class="page-link" href="#">...</a></li>

          @for ($i = 2; $i > 0; $i--)
            @php
              $d = (clone $dC)->subMonths($i);
              $dTxt = $d->formatLocalized('%b %Y');
              $dUrl = $d->startOfMonth()->toDateString();
            @endphp
              <li class="page-item"><a class="page-link" href="{{backpack_url('dashboard') . '?date=' . $dUrl}}">
                {{ $dTxt }}
              </a></li>
          @endfor

          <li class="page-item active"><a class="page-link" href="#">
            {{ $now->formatLocalized('%b %Y') }}
          </a></li>

          @for ($i = 1; $i < 3; $i++)
            @php
              $d = (clone $dC)->addMonths($i);
              $dTxt = $d->formatLocalized('%b %Y');
              $dUrl = $d->startOfMonth()->toDateString();
            @endphp
              <li class="page-item"><a class="page-link" href="{{backpack_url('dashboard') . '?date=' . $dUrl}}">
                {{ $dTxt }}
              </a></li>
          @endfor

          <li class="page-item disabled"><a class="page-link" href="#">...</a></li>

          @php
            $d = (clone $dC)->addYear();
            $dTxt = $d->formatLocalized('%b %Y');
            $dUrl = $d->startOfMonth()->toDateString();
          @endphp
          <li class="page-item"><a class="page-link"
            href="{{ backpack_url('dashboard') . '?date=' . $dUrl }}">
            &laquo; {{ $dTxt }}
          </a></li>

        </ul>
      </nav>
    </div>
  </div>

    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{$out}}<sup style="font-size: 20px">&euro;</sup></h3>

            <p>Spese extra questo mese</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="{{url('/admin/expense/create')}}" class="small-box-footer">Aggiungi spesa <i class="fa fa-plus-circle"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>{{$in}}<sup style="font-size: 20px">&euro;</sup></h3>

            <p>Guadagni extra del mese</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="{{url('/admin/expense/create')}}" class="small-box-footer">Aggiungi introito <i class="fa fa-plus-circle"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>{{$bal}}<sup style="font-size: 20px">&euro;</sup></h3>

            <p>Bilancio mensile</p>
          </div>
          <div class="icon">
            <i class="ion ion-cash"></i>
          </div>
          <a href="#line-30" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3>{{$rem}}<sup style="font-size: 20px">&euro;</sup></h3>

            <p>Budget questo mese</p>
          </div>
          <div class="icon">
            <i class="ion ion-arrow-graph-up-right"></i>
          </div>
          <a href="#line-30" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
    </div>


    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="box box-default">
              <div class="box-header with-border">
                  <div class="box-title">Le ultime movimentazioni</div>
              </div>

              <div class="box-body no-padding">
                <table class="table">
                  <tbody>
                  <tr>
                    <th>Nome</th>
                    <th>Data</th>
                    <th style="width: 50px">Importo</th>
                  </tr>
                  @foreach($mov as $m)
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
                <tfoot>
                  
                </tfoot>
              </table>
              {{ $mov->links() }}
            </div>
          </div>
        </div>

        <div class="col-md-4 col-xs-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <div class="box-title">Categorie di spesa</div>
                </div>

                <div class="box-body no-padding">
                  <table class="table">
                    <tbody>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Nome</th>
                      <th style="width: 80px">Importo</th>
                      <th style="width: 80px">Andamento</th>
                    </tr>
                    @foreach($catin as $c)
                    <tr class="clickable" data-href="{{backpack_url('category') . '/' . $c->id . '/show' }}">
                      <td>{!!$c->htmlIcon()!!}</td>
                      <td>{{$c->name}}</td>
                      <td><span class="badge bg-red">
                        {{$c->sum}} &euro;</span></td>
                      <td>{!!$c->getPrevMonthDifferenceHTML()!!}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                </div>
            </div>
            <div class="box box-success">
                <div class="box-header with-border">
                    <div class="box-title">Categorie di incassi</div>
                </div>

                <div class="box-body no-padding">
                  <table class="table">
                    <tbody>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Nome</th>
                      <th style="width: 80px">Importo</th>
                      <th style="width: 80px">Andamento</th>
                    </tr>
                    @foreach($catout as $c)
                    <tr class="clickable" data-href="{{backpack_url('category') . '/' . $c->id . '/show' }}">
                      <td>{!!$c->htmlIcon()!!}</td>
                      <td>{{$c->name}}</td>
                      <td><span class="badge bg-green">
                        {{$c->sum}} &euro;</span></td>
                      <td>{!!$c->getPrevMonthDifferenceHTML()!!}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xs-12">
            <div class="box box-default">
              <div class="box-header with-border">
                  <div class="box-title">Le prossime entrate e uscite</div>
              </div>

              <div class="box-body no-padding">
                <table class="table">
                  <tbody>
                  <tr>
                    <th>Nome</th>
                    <th>Scadenza</th>
                    <th style="width: 50px">Importo</th>
                  </tr>
                  @foreach($periodics as $p)
                  <tr>
                    <td>{{$p->name}}</td>
                    <td>{{Date::parse($p->next_period)->format(config('backpack.base.default_date_format'))}}</td>
                    <td>
                      @if($p->type == 0)
                      <span class="badge bg-red">
                      @elseif($p->type == 1)
                      <span class="badge bg-green">
                      @endif
                      {{$p->amount}} &euro;</span></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <div class="box box-danger">
            <div class="box-header with-border">
                <div class="box-title">I debiti o crediti attivi</div>
            </div>

            <div class="box-body no-padding">
              <table class="table">
                <tbody>
                <tr>
                  <th>Nome</th>
                  <th>Scadenza</th>
                  <th style="width: 50px">Importo</th>
                </tr>
                @foreach($debits as $d)
                <tr>
                  <td>{{$d->name}}</td>
                  <td>{{Date::parse($d->due_at)->format(config('backpack.base.default_date_format'))}}</td>
                  <td>
                    @if($d->amount < 0)
                    <span class="badge bg-red">
                    @else
                    <span class="badge bg-green">
                    @endif
                    {{$d->amount}} &euro;</span></td>
                </tr>
                @endforeach
              </tbody>
            </table>
            {{ $debits->links() }}
          </div>
        </div>
        </div>
    </div>

    <div class="row">
      <div class="col-md-6  col-xs-12">
          <div class="box box-default">
              <div class="box-header with-border">
                  <div class="box-title">Statistiche ultimi 30 gg</div>
              </div>

              <div class="box-body"><div id="line-30"></div></div>
          </div>
      </div>
      <div class="col-md-6  col-xs-12">
          <div class="box box-default">
              <div class="box-header with-border">
                  <div class="box-title">Statistiche ultimi 12 mesi</div>
              </div>

              <div class="box-body"><div id="line-yr"></div></div>
          </div>
      </div>
    </div>
@endsection

@section('after_styles')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/morris.js/morris.css">

<style>
tr.clickable:hover{
  cursor: pointer;
  background-color: #ccc;
}
</style>

@endsection

@section('after_scripts')
<script src="{{ asset('vendor/adminlte') }}/bower_components/raphael/raphael.min.js"></script>
<script src="{{ asset('vendor/adminlte') }}/bower_components/morris.js/morris.min.js"></script>

<script>
$( document ).ready(function() {
  Morris.Line({
    element: 'line-30',
    data: {!!json_encode($stat30)!!},
    xkey: 'y',
    ykeys: ['in', 'out'],
    labels: ['Entrate', 'Uscite'],
    xLabels: 'day',
    lineColors: ['#5cb85c', '#d9534f']
    });

  Morris.Line({
    element: 'line-yr',
    data: {!!json_encode($statYr)!!},
    xkey: 'y',
    ykeys: ['in', 'out'],
    labels: ['Entrate', 'Uscite'],
    xLabels: 'month',
    lineColors: ['#5cb85c', '#d9534f']
    });

  $(".clickable").click(function(){
    window.location = $(this).data("href");
  });
});
</script>

@endsection
