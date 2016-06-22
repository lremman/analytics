@extends('app.app')
@section('body')

@include('parts.top')

<div class="container">

  <div class="page-header" id="banner">
    <div class="row">
      <div class="col-lg-8 col-md-7 col-sm-6">
        <h1>Аудіозаписи</h1>
        <p class="lead">Аналізувати аудіозаписи друзів</p>
      </div>
    </div>
  </div>

  <div class="row">
  	<div class="col-md-6">
      @include('audio.settings')
  	</div>
  	<div class="col-md-6">
      @include('audio.rating')
  	</div>
  </div>
  <div class="row">
    <div class="col-md-12" id="graph-width">
    </div>
  </div>
  <div class="row">
  	<div class="col-md-12">
  		<div class="" id="rating-graph">
			
		</div>
  	</div>
  </div>

@endsection