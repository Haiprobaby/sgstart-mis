@extends('frontEnd.home.front_master')
@section('main_content')
<div class="row">
	<div class="col-md-5"></div>
	<div class="col-md-4">
		@foreach($list as $list)
		<a href="/details/{{$list->id}}" class="btn btn-success">Fees Schedule 2020-2021 ({{$list->Name}})</a>
		<br>
		<br>
		@endforeach
	</div>
	<div class="col-md-4"></div>
</div>
 
@endsection