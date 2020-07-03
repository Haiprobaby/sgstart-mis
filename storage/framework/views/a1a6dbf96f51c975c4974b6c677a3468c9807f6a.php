<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/backEnd/')); ?>/css/croppie.css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('mainContent'); ?>
<form method="POST" action="/updatefees">
	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>"/>

<input type="hidden" name="id_group" value="<?php echo e($id); ?>">



	<h1>FEES</h1>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-4">
		<?php $__currentLoopData = $fees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fees): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<div>
			<label><b><?php echo e($fees->Name); ?></b></label>
			<input type="hidden" name="fee_id[]" value="<?php echo e($fees->id); ?>">
		</div>
		<div class="input-effect">
			<input type="text" class="primary-input" name="fees[]" value="<?php echo e(number_format($fees->price)); ?>">
			<span class="focus-border"></span>
		</div>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</div>
	<div class="col-md-1">		
	</div>
	<div class="col-md-4">
			
			<h1>BUS FEE</h1>
			<div class="row">
				<div class="col-md-8">
					<?php $__currentLoopData = $bus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<label><b><?php echo e($bus->location); ?></b></label>
					<input type="hidden" name="bus_id[]" value="<?php echo e($bus->id); ?>">
					<div class="input-effect">
					<input type="text" class="primary-input form-control" name="bus[]" value="<?php echo e(number_format($bus->price)); ?>">
					<span class="focus-border"></span>
					</div>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>			
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
		  	<?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $years): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		    <tr class="d-flex">
		      <td class="col-sm-1"><?php echo e($years->Name); ?></td>
		      <input type="hidden" name="year_id[]" value="<?php echo e($years->id); ?>">
		      <td class="col-sm-2"><input type="text" class="form-control" name="paymentA[]" value="<?php echo e(number_format($years->paymentA)); ?>"></td>
		      	<td class="col-sm-1"></td>
		      <td class="col-sm-2"><input type="text" class="form-control" name="paymentB1[]" value="<?php echo e(number_format($years->paymentB1)); ?>"></td>
		      <td class="col-sm-2"><input type="text" class="form-control" name="paymentB2[]" value="<?php echo e(number_format($years->paymentB2)); ?>"></td>
		      <td class="col-sm-2"><input type="text" class="form-control" name="paymentB3[]" value="<?php echo e(number_format($years->paymentB3)); ?>"></td>
		    </tr>
		    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php echo $__env->make('backEnd.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Mis-Saigonstar\resources\views/backEnd/humanResource/fee_policies/middle_year/edit.blade.php ENDPATH**/ ?>