<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/backEnd/')); ?>/css/croppie.css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('mainContent'); ?>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Options</th>
    </tr>
  </thead>
  <a href="middle-year-fees" class="btn btn-success">ADD</a>
  <tbody>
  	<?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  	
    <tr>
      <th scope="row"><?php echo e($list->id); ?></th>
      <td><?php echo e($list->Name); ?></td>
      <td>
      	<a href="edit-fees/<?php echo e($list->id); ?>" class="btn btn-success">Update</a>
      </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
</table>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('public/backEnd/')); ?>/js/croppie.js"></script>
<script src="<?php echo e(asset('public/backEnd/')); ?>/js/editStaff.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backEnd.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Mis-Saigonstar\resources\views/backEnd/humanResource/fee_policies/list_group.blade.php ENDPATH**/ ?>