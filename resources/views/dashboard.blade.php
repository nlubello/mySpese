@extends(backpack_view('blank'))

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
          <li class="page-item hidden-xs"><a class="page-link"
            href="{{ backpack_url('dashboard') . '?date=' . $dUrl }}">
            &laquo; {{ $dTxt }}
          </a></li>

          <li class="page-item disabled hidden-xs"><a class="page-link" href="#">...</a></li>

          @for ($i = 2; $i > 0; $i--)
            @php
              $d = (clone $dC)->subMonths($i);
              $dTxt = $d->formatLocalized('%b %Y');
              $dUrl = $d->startOfMonth()->toDateString();
            @endphp
              <li class="page-item {{$i==2 ? 'hidden-xs' : ''}}"><a class="page-link" href="{{backpack_url('dashboard') . '?date=' . $dUrl}}">
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
              <li class="page-item {{$i==2 ? 'hidden-xs' : ''}}"><a class="page-link" href="{{backpack_url('dashboard') . '?date=' . $dUrl}}">
                {{ $dTxt }}
              </a></li>
          @endfor

          <li class="page-item disabled hidden-xs"><a class="page-link" href="#">...</a></li>

          @php
            $d = (clone $dC)->addYear();
            $dTxt = $d->formatLocalized('%b %Y');
            $dUrl = $d->startOfMonth()->toDateString();
          @endphp
          <li class="page-item hidden-xs"><a class="page-link"
            href="{{ backpack_url('dashboard') . '?date=' . $dUrl }}">
            {{ $dTxt }} &raquo;
          </a></li>

        </ul>
      </nav>
    </div>
  </div>

    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <div class="card text-white bg-primary">
          <div class="card-body pb-0">
            <a href="{{url('/admin/expense/create')}}" class="btn btn-transparent p-0 float-right"><i class="icon-pencil"></i></a>
            <div class="text-value">{{$out}}<sup style="font-size: 20px">&euro;</sup></div>
            <div>Spese extra questo mese</div>
          </div>
          <div class="chart-wrapper mt-3 mx-3" style="height:70px;"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
            <canvas class="chart chartjs-render-monitor" id="card-chart1" height="70" style="display: block;" width="350"></canvas>
          <div id="card-chart1-tooltip" class="chartjs-tooltip top" style="opacity: 0; left: 191.89px; top: 101.244px;"><div class="tooltip-header"><div class="tooltip-header-item">April</div></div><div class="tooltip-body"><div class="tooltip-body-item"><span class="tooltip-body-item-color" style="background-color: rgba(255, 255, 255, 0.55);"></span><span class="tooltip-body-item-label">My First dataset</span><span class="tooltip-body-item-value">84</span></div></div></div></div>
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
                    <th>Azioni</th>
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
                      <td>
                        <a class="btn btn-xs btn-default" href="{{backpack_url('expense').'/'.$m->id.'/edit'}}"><i class="fa fa-edit"></i></a>
                      </td>
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
                      <td>{!!$c->getPrevMonthsDifferenceHTML($now)!!}</td>
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
                      <td>{!!$c->getPrevMonthsDifferenceHTML($now)!!}</td>
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
                  <div class="box-title">Entrate e uscite da registrare</div>
              </div>

              <div class="box-body no-padding">
                <table class="table">
                  <tbody>
                  <tr>
                    <th>Nome</th>
                    <th>Scaduta</th>
                    <th style="width: 50px">Importo</th>
                    <th>Azioni</th>
                  </tr>
                  @foreach($expPeriodics as $p)
                  <tr>
                    <td>{{$p->name}}</td>
                    <td class="text-danger">{{Date::parse($p->prev_period)->format(config('backpack.base.default_date_format'))}}</td>
                    <td>
                      @if($p->type == 0)
                      <span class="badge bg-red">
                      @elseif($p->type == 1)
                      <span class="badge bg-green">
                      @endif
                      {{$p->amount}} &euro;</span></td>
                      <td>
                        @php
                            $e = $p->getExpense();
                        @endphp
                        @if(is_null($e))
                          <a class="btn btn-xs btn-success" href="{{backpack_url('periodic').'/'.$p->id.'/register'}}"><i class="fa fa-plus-circle" aria-hidden="true"></i> Registra</a>
                        @else
                          <a class="btn btn-xs btn-default" href="{{backpack_url('expense').'/'.$p->id.'/edit'}}"><i class="fa fa-edit"></i> Modifica</a>
                        @endif
                      </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            </div>
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
      <div class="col-md-12">
          <div class="box box-default">
              <div class="box-header with-border">
                  <div class="box-title">Statistiche ultimi 30 gg</div>
              </div>

              <div class="box-body"><div id="line-30"></div></div>
          </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <div class="box-title">Statistiche ultimi 12 mesi</div>
            </div>

            <div class="box-body">
              <div class="col-md-6 col-xs-12">
                <div id="line-yr"></div>
              </div>
              <div class="col-md-3 col-xs-12">
                <div id="pie-yr-exp"></div>
              </div>
              <div class="col-md-3 col-xs-12">
                <div id="pie-yr-pro"></div>
              </div>
            </div>
        </div>
      </div>
    </div>
@endsection

@section('after_styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

<style>
tr.clickable:hover{
  cursor: pointer;
  background-color: #ccc;
}
</style>

@endsection

@section('after_scripts')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js" integrity="sha512-3PRVLmoBYuBDbCEojg5qdmd9UhkPiyoczSFYjnLhFb2KAFsWWEMlAPt0olX1Nv7zGhDfhGEVkXsu51a55nlYmw==" crossorigin="anonymous"></script>

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
  Morris.Donut({
    element: 'pie-yr-exp',
    data: {!!json_encode($statCatE)!!},
    colors: [
      '#0C9D01',
      '#24A702',
      '#3CB204',
      '#55BD05',
      '#6DC807',
      '#86D308',
      '#9EDE0A',
      '#B7E90B',
      '#CFF40D',
      '#E8FF0F'
    ],
    formatter : function (y, data) { return parseFloat(y).toFixed(2) + ' €' }
    });
  Morris.Donut({
    element: 'pie-yr-pro',
    data: {!!json_encode($statCatP)!!},
    colors: [
      '#DF0118',
      '#E21817',
      '#E52F16',
      '#E84715',
      '#EB5E14',
      '#EF7613',
      '#F28D12',
      '#F28D12',
      '#F5A411',
      '#F8BC10'
    ],
    formatter : function (y, data) { return parseFloat(y).toFixed(2) + ' €' }
    });


  $(".clickable").click(function(){
    window.location = $(this).data("href");
  });

  $('.sparkbar').each(function () {
    var $this = $(this);
    $this.sparkline('html', {
      type    : 'bar',
      height  : $this.data('height') ? $this.data('height') : '30',
      barColor: $this.data('color'),
      negBarColor: $this.data('negcolor'),
      zeroAxis: true
    });
  });
});
</script>

@endsection
