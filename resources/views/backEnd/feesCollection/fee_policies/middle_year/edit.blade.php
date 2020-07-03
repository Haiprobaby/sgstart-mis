@extends('backEnd.master')
@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('public/backEnd/')}}/css/croppie.css">
@endsection
@section('mainContent')
<form method="POST" action="/updatefees">
	<input type="hidden" name="_token" value="{{csrf_token()}}"/>

<input type="hidden" name="id_group" value="{{$id}}">



	<h1>FEES</h1>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-4">
		@foreach ($fees as $fees)
		<div>
			<label><b>{{$fees->Name}}</b></label>
			<input type="hidden" name="fee_id[]" value="{{$fees->id}}">
		</div>
		<div class="input-effect">
			<input type="text" class="primary-input" name="fees[]" value="{{number_format($fees->price)}}">
			<span class="focus-border"></span>
		</div>
		@endforeach
	</div>
	<div class="col-md-1">		
	</div>
	<div class="col-md-4">
			
			<h1>BUS FEE</h1>
			<div class="row">
				<div class="col-md-8">
					@foreach($bus as $bus)
					<label><b>{{$bus->location}}</b></label>
					<input type="hidden" name="bus_id[]" value="{{$bus->id}}">
					<div class="input-effect">
					<input type="text" class="primary-input form-control" name="bus[]" value="{{number_format($bus->price)}}">
					<span class="focus-border"></span>
					</div>
					@endforeach			
				</div>
				<div class="col-md-4">
					
					<br>
					
					
				</div>
			</div>
	</div>
</div>
<br>
<h1>PAYMENT OPTIONS</h1>
<div class="row">
	<div class="col-sm-1"></div>
	<div class="col-sm-10">
		<table class="table">
		  <thead>
		    <tr class="d-flex">
		      <th class="col-sm-1">Year Group</th>
		      <th class="col-sm-2">Option A</th>
		      <th class="col-sm-1"></th>
		      <th class="col-sm-2"></th>
		      <th class="col-sm-2">Option B</th>
		      <th class="col-sm-2"></th>
		    </tr>
		  </thead>
		  <tbody>
		  	@foreach($years as $years)
		    <tr class="d-flex">
		      <td class="col-sm-1">{{$years->Name}}</td>
		      <input type="hidden" name="year_id[]" value="{{$years->id}}">
		      <td class="col-sm-2"><input type="text" class="form-control" name="paymentA[]" value="{{number_format($years->paymentA)}}"></td>
		      	<td class="col-sm-1"></td>
		      <td class="col-sm-2"><input type="text" class="form-control" name="paymentB1[]" value="{{number_format($years->paymentB1)}}"></td>
		      <td class="col-sm-2"><input type="text" class="form-control" name="paymentB2[]" value="{{number_format($years->paymentB2)}}"></td>
		      <td class="col-sm-2"><input type="text" class="form-control" name="paymentB3[]" value="{{number_format($years->paymentB3)}}"></td>
		    </tr>
		    @endforeach
		  </tbody>
		</table>
		<button class="btn btn-primary">Save</button>
	</div>

</form>
</div>

@endsection
@section('script')
<script src="{{asset('public/backEnd/')}}/js/croppie.js"></script>
<script src="{{asset('public/backEnd/')}}/js/editStaff.js"></script>
@endsection