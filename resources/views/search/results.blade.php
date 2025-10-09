@extends('layouts.app')
@section('content')
<div class="container py-4">
  <h3>Search results for "{{ $query }}"</h3>
  <div class="row mt-3">
    <div class="col-12">
      @include('search.partials.results')
    </div>
  </div>
</div>
@endsection
