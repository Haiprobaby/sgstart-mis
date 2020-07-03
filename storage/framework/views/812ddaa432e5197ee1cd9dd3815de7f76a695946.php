<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/backEnd/')); ?>/css/croppie.css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('mainContent'); ?>
<form method="POST" action="/middle-year-fees">
	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>"/>
	




	<h1>FEES</h1>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-4">

			<label>APPLICATION FEE</label>
			<input type="number" class="form-control" name="application_fee">
			<label>ENROLLMENT FEE</label>
			<input type="number" class="form-control" name="enroll_fee">
			<label>LUNCH FEE</label>
			<input type="number" class="form-control" name="lunch_fee">
	</div>
	<div class="col-md-1">		
	</div>
	<div class="col-md-4">
			<label>ENGLISH AS AN ADDITIONAL (EAL) FEE</label>
			<input type="number" class="form-control" name="eal_fee">
			<label>SUPPLEMENT FEE</label>
			<input type="number" class="form-control" name="supplement_fee">
			<label>BUS FEE</label>
			<div class="row">
				<div class="col-md-8">
					<label>Thanh My Loi Area</label>
					<input type="number" class="form-control" name="bus1">
					<label>Thao Dien & other areas in dist.2</label>
					<input type="number" class="form-control" name="bus2">
					<label>Dist.1, 3, 4, 7, 9, Thu Duc The Manor, Saigon Pearl,Vinhomes Central Park</label>
					<input type="number" class="form-control" name="bus3">
					<label>Other districts</label>
					<input type="number" class="form-control" name="bus4">					
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
		    <tr class="d-flex">
		      <td class="col-sm-1">Year 7</td>
		      <td class="col-sm-2"><input type="number" class="form-control" name="year7optionA"></td>
		      	<td class="col-sm-1"></td>
		      <td class="col-sm-2"><input type="number" class="form-control" name="year7optionB[]"></td>
		      <td class="col-sm-2"><input type="number" class="form-control" name="year7optionB[]"></td>
		      <td class="col-sm-2"><input type="number" class="form-control" name="year7optionB[]"></td>
		    </tr>
		    <tr class="d-flex">
		      <td class="col-sm-1">Year 8</td>
		      <td class="col-sm-2"><input type="number" class="form-control" name="year8optionA"></td>
		      	<td class="col-sm-1"></td>
		      <td class="col-sm-2"><input type="number" class="form-control" name="year8optionB[]"></td>
		      <td class="col-sm-2"><input type="number" class="form-control" name="year8optionB[]"></td>
		      <td class="col-sm-2"><input type="number" class="form-control" name="year8optionB[]"></td>
		    </tr>
		    <tr class="d-flex">
		      <td class="col-sm-1">Year 9</td>
		      <td class="col-sm-2"><input type="number" class="form-control" name="year9optionA"></td>
		      	<td class="col-sm-1"></td>
		      <td class="col-sm-2"><input type="number" class="form-control" name="year9optionB[]"></td>
		      <td class="col-sm-2"><input type="number" class="form-control" name="year9optionB[]"></td>
		      <td class="col-sm-2"><input type="number" class="form-control" name="year9optionB[]"></td>
		    </tr>
		  </tbody>
		</table>
		<button class="btn btn-primary">Save</button>
	</div>

</form>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('public/backEnd/')); ?>/js/croppie.js"></script>
<script src="<?php echo e(asset('public/backEnd/')); ?>/js/editStaff.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backEnd.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Mis-Saigonstar\resources\views/backEnd/humanResource/fee_policies/middle_year/add.blade.php ENDPATH**/ ?>