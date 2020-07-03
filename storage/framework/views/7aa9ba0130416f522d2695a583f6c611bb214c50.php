<?php $__env->startSection('main_content'); ?>
<div class="row">
	<div class="col-md-5"></div>
	<div class="col-md-4">
		<?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<a href="/details/<?php echo e($list->id); ?>" class="btn btn-success">Fees Schedule 2020-2021 (<?php echo e($list->Name); ?>)</a>
		<br>
		<br>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</div>
	<div class="col-md-4"></div>
</div>
 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontEnd.home.front_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Mis-Saigonstar\resources\views/frontEnd/home/admissions.blade.php ENDPATH**/ ?>