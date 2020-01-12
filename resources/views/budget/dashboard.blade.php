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
  <div id="app">
    <example-component
      url="{{ url('') }}"
    ></example-component>
  </div>

@endsection

@section('before_styles')
<meta name="api-token" content="{{ \Auth::User()->api_token }}" />

<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<style>
</style>

@endsection

@section('after_scripts')
<script src="{{ asset('js/app.js') }}"></script>

<script>
$( document ).ready(function() {
  
});
</script>

@endsection
