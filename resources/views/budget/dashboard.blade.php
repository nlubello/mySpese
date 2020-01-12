@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Budget<small>Dashboard per la gestione del budget</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Budget</li>
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
<script src="{{ asset('vendor/adminlte') }}/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>

<script>
$( document ).ready(function() {
  
});
</script>

@endsection
