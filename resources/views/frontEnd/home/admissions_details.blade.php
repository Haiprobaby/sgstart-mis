@extends('frontEnd.home.front_master')
@section('main_content')
<br>
<style type="text/css">
	table th,td {
   text-align: center; 
}

.table {
   margin: auto;
   width: 100% !important; 
}
}
</style>
<div class="row">

	<div class="col-md-2">	</div>
	<div class="col-md-8">
		<a href="/details/3" class="btn btn-success">Middle year</a>
		<a href="/details/2" class="btn btn-success">Primary year</a>
		<a href="/details/1" class="btn btn-success">Early year</a>
		<h1 style="text-align: center;">{{$group->Name}}</h1>
		<h1>Tuition Fees</h1>
		<table class="table table-bordered">
		  <thead>
		    <tr class="table-info">
		      <th scope="col">Year Group</th>		     
		      <th scope="col">Option A</th>
		      
		      
		      <th scope="col" colspan="3">Option B</th>
		    </tr>

		  </thead>
		  <tbody>
		  	
		  	@foreach($years as $years)
		    <tr>
		      <th scope="row">{{$years->Name}}</th>
		      <td>{{number_format($years->paymentA)}}</td>
		      
		       
		       

		      <td>{{number_format($years->paymentB1)}}</td>
		      <td>{{number_format($years->paymentB2)}}</td>
		      <td>{{number_format($years->paymentB3)}}</td>
		    </tr>
		    @endforeach
		    <th scope="row"></th>
		  		<td>Due by 15th May</td>
		  		
		  		<td>Due by 15th May</td>
		  		<td>Before starting of new academic year</td>
		  		<td>Before of term 3</td>
		  </tbody>
		  <tfoot>
		  	
		  </tfoot>
		</table>
	</div>

</div>
<br>
<div class="row">
	<div class="col-md-2">	</div>
	<div class="col-md-8">
		<h1>Other Fees</h1>
		<table class="table table-bordered">
		  <thead>
		    <tr  class="table-success">
		      <th scope="col">Name</th>		     
		      <th scope="col">Description</th>
		       
		      <th scope="col">Price</th>
		    </tr>

		  </thead>
		  <tbody>
		  	
		  	@foreach($fees as $fees)
		    <tr >
		      <th scope="row">{{$fees->Name}}</th>
		      <td>{{$fees->description}}</td>
		      <td>{{number_format($fees->price)}}</td>
		      
		    </tr>
		    @endforeach
		  </tbody>
		</table>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-2">	</div>
	<div class="col-md-8">
		<h1>Bus Fee</h1>
		<table class="table table-bordered">
		  <thead>
		   	<tr class="table-info">
		      	<th scope="col">Location</th>
		      	@foreach($bus as $bus1)
		      	<th>{{$bus1->location}}</th>
		      	@endforeach		     		      
		    </tr>
		    <tr>
		  		<td scope="col">Price</td>
		  		@foreach($bus as $bus2)
		      	<td>{{number_format($bus2->price)}}/year</td>
		      	@endforeach	
		  	</tr>
		  </thead>
		  
		</table>
		- 	30% discount is applied for the second child of the same family.<br>
		-	50% discount is applied for the third and each subsequent child/children of the same family.<br>
		-	Requests for the bus must be registered termly or yearly.<br>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-2">	</div>
	<div class="col-md-8">
		<h1>Loyalty & Sibling Discount</h1>
		- The sibling discount is only applicable to children in the same family.<br>
		- The sibling discount is based on the tuition fee for the younger child.<br>
		- Loyalty & sibling discounts are not applied to the supplement fee.<br>
		- Loyalty discount will not be used in conjunction with sibling discount.

		<table class="table table-bordered">
		  <thead>
		   	<tr class="table-success">
		      	  <th scope="col">#</th>
			      <th scope="col">Name</th>
			      <th scope="col">1st Year</th>
			      <th scope="col">2nd Year</th>
			      <th scope="col">3rd Year</th>
			      <th scope="col">4th Year</th>
			      <th scope="col">5th Year +</th>
			       		      
		    </tr>
		   
		  </thead>
		  <tbody>
		  	@foreach($discount as $discount)
		    <tr >
		      <td>{{$discount->id}}</td>
		        <td>{{$discount->Discount_for}}</td>
		        <td>{{$discount->Year1st}}</td>
		        <td>{{$discount->Year2nd}}</td>
		        <td>{{$discount->Year3rd}}</td>
		        <td>{{$discount->Year4th}}</td>
		        <td>{{$discount->Year5th}}</td>
		      
		      
		    </tr>
		    @endforeach
		  </tbody>
		  
		</table>
		
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-2">	</div>
	<div class="col-md-8">
		<h1>Late Enrolment</h1>
		-The rate for late enrolment is applied for all age groups:
		<table class="table table-bordered">
		  <thead>
		   	<tr class="table-active">
		      	<th >Entry Date</th>
		      	@foreach($late_enrolment as $late1)
		      	<th>{{$late1->entry_date}}</th>
		      	@endforeach		     		      
		    </tr>
		    <tr>
		  		<th >% of annual tuition</th>
		  		@foreach($late_enrolment as $late2)
		      	<td>{{$late2->percent_of_annual_tuition}}</td>
		      	@endforeach	
		  	</tr>
		  </thead>
		  
		</table>
		<br>
		<b>Special Educational Needs (SEN)</b><br>
		-	Where possible, the school provides support for children who have physical or learning difculties at no extra cost.<br>
		-	Admission, in such cases, is determined by the school on a case by case basis. For children requiring intensive<br>
		-	support, either upon admission or identied at a later stage, any additional costs will be passed onto parents.
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-2">	</div>
	<div class="col-md-8">
		<h1>School Grades</h1>
		

		<table class="table table-bordered">
		  <thead>
		   	<tr class="table-success">
		      	  <th scope="col" rowspan="2">Child Born between</th>			      
			      <th scope="col">2019-2020</th>
			      <th scope="col">2020-2021</th>
			      <th scope="col">2021-2022</th>
		    </tr>
		   
		  </thead>
		  <tbody>
		  	@foreach($grades as $grades)
		    <tr >
		      	<td>{{$grades->born_from}} to {{$grades->born_to}}</td>
		        <td>{{$grades->year19to20}}</td>
		        <td>{{$grades->year20to21}}</td>
		        <td>{{$grades->year21to22}}</td>
		       
		      
		      
		    </tr>
		    @endforeach
		  </tbody>		
		</table>
		 <b> Late Payment</b><br>
		-	This fee is payable for the whole school year:<br>
		-	All fees must be paid in advance.<br>
		-	A daily late charge fee will be imposed for outstanding/overdue payments based on a monthly rate of 2%.<br>
		-	If payment is not made within 2 (two) weeks of the due date, the school reserves the right to offer the place to the next family on the waiting list.
	</div>
</div>


<br>
<div class="row">
	<div class="col-md-2">	</div>
	<div class="col-md-8">
		<h1>Early Withdrawal</h1>
		- The withdrawal policy will not be applied for instalment payments.<br>
		- Parents/carers must complete a Withdrawal Form and submit it 60 days prior to the child’s withdrawal from the
		school in order to obtain a refund. Saigon Star will retain the remainder of the term’s tuition fee, plus a 10% early
		withdrawal charge. The remainder will be refunded, as listed below:
		<table class="table table-bordered">
		  <thead>
		   	<tr class="table-info">
		      	<th scope="col">Withdrawal Date</th>
		      	@foreach($withdraw as $withdraw1)
		      	<th>{{$withdraw1->withdraw_date}}</th>
		      	@endforeach		     		      
		    </tr>
		    <tr>
		  		<td scope="col">Rate of refundable</td>
		  		@foreach($withdraw as $withdraw2)
		      	<td>{{$withdraw2->refund_rate}}</td>
		      	@endforeach	
		  	</tr>
		  </thead>
		  
		</table>
		- 	All fees are quoted in Vietnam Dong.<br>
		-	Please note that refunds cannot be made in the event of a force majeure. Force majeure event refers
			to or effect an which can not be reasonably anticipated or be under our control, e.g earthquakes,
			epidemics and other natural disasters.
	</div>
</div>
@endsection