@extends(backpack_view('blank'))

@section('content')
  <div id="app">
    <budget-component
      url="{{ url('') }}"
    ></budget-component>
  </div>

@endsection

@section('before_styles')
<meta name="api-token" content="{{ backpack_auth()->user()->api_token }}" />

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
