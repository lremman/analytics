@extends('app.app')
@section('body')

@include('parts.top')

<div class="container">

  <div class="page-header" id="banner">
    <div class="row">
      <div class="col-lg-8 col-md-7 col-sm-6">
        <h1>Мені подобається</h1>
        <p class="lead">Побудувати рейтинг</p>
      </div>
    </div>
  </div>

  <div class="row">
  	<div class="col-md-6">
  		@include('likes.settings')
  	</div>
  	<div class="col-md-6">
  		@include('likes.rating')
  	</div>
  </div>
  <div class="row">
  	<div class="col-md-6">
  		<div class="" id="rating-graph">
			
		</div>
  	</div>
  </div>

@endsection