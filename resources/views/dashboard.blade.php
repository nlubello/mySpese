@extends(backpack_view('blank'))

@section('content')
  <div class="row d-none">
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
        <div class="col-lg-4 col-md-6 col-xs-12">
          <div class="card card-accent-primary mb-2">
            <div class="card-header">
              Entrate e uscite da registrare
              <a href="{{url('/admin/expense/create')}}" class="btn btn-ghost-primary p-1 float-right" type="button"><i class="las la-plus"></i> Nuova</a>
            </div>

            <div class="card-body p-0">
              <div class="table-responsive">
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
                    <td class="text-danger">{{Date::parse($p->prev_period)->format('d/m/yy')}}</td>
                    <td>
                      @if($p->type == 0)
                      <span class="badge bg-red text-light">
                      @elseif($p->type == 1)
                      <span class="badge bg-green">
                      @endif
                      {{$p->amount}} &euro;</span></td>
                      <td>
                        @php
                            $e = $p->getExpense();
                        @endphp
                        @if(is_null($e))
                          <a class="btn btn-sm btn-success" href="{{backpack_url('periodic').'/'.$p->id.'/register'}}"><i class="las la-plus-circle" aria-hidden="true"></i></a>
                        @else
                          <a class="btn btn-sm btn-primary" href="{{backpack_url('expense').'/'.$p->id.'/edit'}}"><i class="las la-edit"></i></a>
                        @endif
                      </td>
                  </tr>
                  @endforeach
                  @if(count($expPeriodics)==0)
                  <tr><td colspan="4" class="text-center">Nessun movimento da registrare</td></tr>
                  @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
            <div class="card card-accent-primary mb-2">
              <div class="card-header">
                Le ultime movimentazioni
              </div>

              <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Nome</th>
                      <th>Data</th>
                      <th style="width: 50px">Importo</th>
                      <th>Azioni</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($mov as $m)
                  <tr class="clickable" data-href="{{backpack_url('expense') . '/' . $m->id . '/edit' }}">
                    <td>{{$m->name}}</td>
                    <td>{{Date::parse($m->expensed_at)->format('d/m/yy')}}</td>
                    <td>
                      @if($m->type == 0)
                      <span class="badge bg-red text-light">
                      @elseif($m->type == 1)
                      <span class="badge bg-green">
                      @endif
                      {{$m->amount}} &euro;</span></td>
                      <td>
                        <a class="btn btn-sm btn-primary" href="{{backpack_url('expense').'/'.$m->id.'/edit'}}"><i class="las la-edit"></i></a>
                      </td>
                  </tr>
                  @endforeach
                  @if(count($mov)==0)
                  <tr><td colspan="4" class="text-center">Nessun movimento</td></tr>
                  @endif
                  </tbody>
                </table>
                </div>
              {{ $mov->links() }}
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="card card-accent-warning mb-2">
              <div class="card-header">Categorie di spesa</div>

              <div class="card-body p-0">
                <table class="table">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Nome</th>
                      <th style="width: 80px">Importo</th>
                      <th style="width: 80px">Andamento</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($catin as $c)
                  <tr class="clickable" data-href="{{backpack_url('category') . '/' . $c->id . '/show' }}">
                    <td>{!!$c->htmlIcon()!!}</td>
                    <td>{{$c->name}}</td>
                    <td><span class="badge bg-red text-light">
                      {{$c->sum}} &euro;</span></td>
                    <td>{!!$c->getPrevMonthsDifferenceHTML($now)!!}</td>
                  </tr>
                  @endforeach
                  @if(count($catin)==0)
                  <tr><td colspan="4" class="text-center">Nessuna categoria</td></tr>
                  @endif
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card card-accent-success mb-2">
              <div class="card-header">Categorie di incassi</div>

              <div class="card-body p-0">
                <table class="table">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Nome</th>
                      <th style="width: 80px">Importo</th>
                      <th style="width: 80px">Andamento</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($catout as $c)
                  <tr class="clickable" data-href="{{backpack_url('category') . '/' . $c->id . '/show' }}">
                    <td>{!!$c->htmlIcon()!!}</td>
                    <td>{{$c->name}}</td>
                    <td><span class="badge bg-green">
                      {{$c->sum}} &euro;</span></td>
                    <td>{!!$c->getPrevMonthsDifferenceHTML($now)!!}</td>
                  </tr>
                  @endforeach
                  @if(count($catout)==0)
                  <tr><td colspan="4" class="text-center">Nessuna categoria</td></tr>
                  @endif
                  </tbody>
                </table>
              </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-xs-12">
            
            <div class="card card-accent-primary mb-2">
              <div class="card-header">Le prossime entrate e uscite</div>

              <div class="card-body p-0">
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
                    <td>{{Date::parse($p->next_period)->format('d/m/yy')}}</td>
                    <td>
                      @if($p->type == 0)
                      <span class="badge bg-red text-light">
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

          <div class="card card-accent-danger mb-2">
            <div class="card-header">I debiti o crediti attivi</div>

            <div class="card-body p-0">
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
                  <td>{{Date::parse($d->due_at)->format('d/m/yy')}}</td>
                  <td>
                    @if($d->amount < 0)
                    <span class="badge bg-red text-light">
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
          <div class="card card-default">
              <div class="card-header with-border">
                  <div class="card-title">Statistiche ultimi 30 gg</div>
              </div>

              <div class="card-body"><div id="line-30"></div></div>
          </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-default">
            <div class="card-header with-border">
                <div class="card-title">Statistiche ultimi 12 mesi</div>
            </div>

            <div class="card-body">
              <div class="row">
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
