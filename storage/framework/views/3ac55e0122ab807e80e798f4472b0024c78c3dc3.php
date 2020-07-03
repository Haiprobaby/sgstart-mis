<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('public/backEnd/')); ?>/css/croppie.css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('mainContent'); ?>
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

<button id="btnGroup" class="btn btn-primary">Group</button>
<button id="btnDiscount" class="btn btn-primary">Discount</button>
<button id="btnEnrol" class="btn btn-primary">Late Enrol</button>
<button id="btnGrades" class="btn btn-primary">Grades</button>
<button id="btnWithdraw" class="btn btn-primary">Withdraw</button>
<div id=fees>
  <br>
  <table class="table table-bordered">
  <thead>
    <tr class="table-primary">
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Options</th>
    </tr>
  </thead>
  <h2>Fee Groups</h2>
  <tbody>
    <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    
    <tr>
      <th scope="row"><?php echo e($list->id); ?></th>
      <td><?php echo e($list->Name); ?></td>
      <td>
        <a href="edit-fees/<?php echo e($list->id); ?>" class="btn btn-success">Edit</a>
      </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
</table>
</div>


<div id=discount>
  <br>
  <h1>Discount</h1>
<table class="table table-bordered">
  <a href="" class="btn btn-success edit-discount" data-toggle="modal" data-target="#adddiscount" data-whatever="@mdo">Add</a>

  <thead>
    <tr class="table-info">
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">1st Year</th>
      <th scope="col">2nd Year</th>
      <th scope="col">3rd Year</th>
      <th scope="col">4th Year</th>
      <th scope="col">5th Year +</th>
      <th scope="col">Options</th>
    </tr>
  </thead>
  
  <tbody>
    <?php $__currentLoopData = $discount; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $discount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td><?php echo e($discount->id); ?></td>
        <td><?php echo e($discount->Discount_for); ?></td>
        <td><?php echo e($discount->Year1st); ?></td>
        <td><?php echo e($discount->Year2nd); ?></td>
        <td><?php echo e($discount->Year3rd); ?></td>
        <td><?php echo e($discount->Year4th); ?></td>
        <td><?php echo e($discount->Year5th); ?></td>
        <td>
          <a href="edit-discount/<?php echo e($discount->id); ?>" id="<?php echo e($discount->id); ?>" class="btn btn-success edit-discount" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Edit</a>
        </td>
        
      </tr>
    
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
</table>

</div>
<div id="enrol">
  <br>
  <h1>Late Enrolment</h1>
<table class="table table-bordered">
  <thead>
    <tr class="table-info">
      <th scope="col">#</th>
      <th scope="col">Term</th>
      <th scope="col">Rate</th>
      <th scope="col">Options</th>
      
    </tr>
  </thead>
  
  <tbody>
    <?php $__currentLoopData = $late; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $late): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td><?php echo e($late->id); ?></td>
        <td><?php echo e($late->entry_date); ?></td>
        <td><?php echo e($late->percent_of_annual_tuition); ?></td>
        
        <td>
          <a href="edit-late-enrol/<?php echo e($late->id); ?>" class="btn btn-success">Edit</a>

        </td>
        
      </tr>
    
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
</table>
</div>


<div id="grades">
  <br>
    <h1>School Grades</h1>
    <table class="table table-bordered">
      <thead>
        <tr class="table-success">
              <th scope="col" rowspan="2">Child Born between</th>           
            <th scope="col">2019-2020</th>
            <th scope="col">2020-2021</th>
            <th scope="col">2021-2022</th>
            <th scope="col">Options</th>
        </tr>
       
      </thead>
      <tbody>
        <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grades): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr >
            <td><?php echo e($grades->born_from); ?> to <?php echo e($grades->born_to); ?></td>
            <td><?php echo e($grades->year19to20); ?></td>
            <td><?php echo e($grades->year20to21); ?></td>
            <td><?php echo e($grades->year21to22); ?></td>
            <td>
              <a href="edit-grade/<?php echo e($grades->id); ?>" class="btn btn-success">Edit</a>
            </td>
          
          
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>    
    </table>
</div> 
<br>
<div id="withdraw">
  <br>
    <h1>Early Withdrawal</h1>
    
    <table class="table table-bordered">
      <thead>
        <tr class="table-info">
            <th scope="col">Withdrawal Date</th>
            <th scope="col">Rate of refundable</th>
            <th scope="1">Options</th>
        </tr>
        
      </thead>
      <tbody>
        <?php $__currentLoopData = $withdraw; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdraw2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($withdraw2->withdraw_date); ?></td>
            <td><?php echo e($withdraw2->refund_rate); ?></td>
            <td>
              <a href="edit-withdraw/<?php echo e($withdraw2->id); ?>" class="btn btn-success">Edit</a>
            </td>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
        </tr>
      </tbody>
      
    </table>
</div>
    
<?php echo $__env->make('backEnd.feesCollection.fee_policies.edit_discount', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('backEnd.feesCollection.fee_policies.add_discount', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<!-- Pham Trong Hai -->
<script type="text/javascript">

  $(document).ready(function() {
    $('#discount').hide();
    $('#withdraw').hide();
    $('#enrol').hide();
    $('#grades').hide();
    
  });
  $('#btnDiscount').click(function(){
      $('#discount').show();
      $('#withdraw').hide();
      $('#enrol').hide();
      $('#grades').hide();
      $('#fees').hide();
  });
   $('#btnGroup').click(function(){
      $('#discount').hide();
      $('#withdraw').hide();
      $('#enrol').hide();
      $('#grades').hide();
      $('#fees').show();
  });
    $('#btnEnrol').click(function(){
      $('#discount').hide();
      $('#withdraw').hide();
      $('#enrol').show();
      $('#grades').hide();
      $('#fees').hide();
  });
     $('#btnGrades').click(function(){
      $('#discount').hide();
      $('#withdraw').hide();
      $('#enrol').hide();
      $('#grades').show();
      $('#fees').hide();
  });
      $('#btnWithdraw').click(function(){
      $('#discount').hide();
      $('#withdraw').show();
      $('#enrol').hide();
      $('#grades').hide();
      $('#fees').hide();
  });
</script>
<!-- Pham Trong Hai -->
<!-- Pham Trong Hai -->
<script type="text/javascript">
  $('.edit-discount').click(function(){
    $('#id_discount').val($(this).attr("id"));
    var currentRow=$(this).closest("tr");
    $('#discount_for').val(currentRow.find("td:eq(1)").text());
    $('#1styear').val(currentRow.find("td:eq(2)").text());
    $('#2ndyear').val(currentRow.find("td:eq(3)").text());
    $('#3rdyear').val(currentRow.find("td:eq(4)").text());
    $('#4thyear').val(currentRow.find("td:eq(5)").text());
    $('#5thyear').val(currentRow.find("td:eq(6)").text());
   
    
  });
</script>
<!-- Pham Trong Hai -->
<script type="text/javascript">
  $('#edit_discount').click(function(e){
      e.preventDefault();
      var id_discount = $('#id_discount').val();
      var discount_for = $('#discount_for').val();
      var first_year = $('#1styear').val();
      var second_year = $('#2ndyear').val();
      var third_year = $('#3rdyear').val();
      var fourth_year = $('#4thyear').val();
      var fifth_year = $('#5thyear').val();
     
      $.ajax({
                url: 'edit-discount',
                type: 'POST',
                dataType: 'html',
                data: {
                    id_discount   : id_discount,
                    discount_for  : discount_for,
                    first_year    : first_year,
                    second_year   : second_year,
                    third_year    : third_year,
                    fourth_year   : fourth_year,
                    fifth_year    : fifth_year
                }
            }).done(function(data) {
                var currentRow = $('#'+id_discount).closest('tr');
                currentRow.find("td:eq(1)").text(discount_for);
                currentRow.find("td:eq(2)").text(first_year);
                currentRow.find("td:eq(3)").text(second_year);
                currentRow.find("td:eq(4)").text(third_year);
                currentRow.find("td:eq(5)").text(fourth_year);
                currentRow.find("td:eq(6)").text(fifth_year);
                $('.close').click();
                $('.toast').toast('show');

            });
  });
</script>

<script type="text/javascript">
  $('#add_discount').click(function(e){
      e.preventDefault();
      
      var discount_for = $('#discount_for_add').val();
      var first_year = $('#1styear_add').val();
      var second_year = $('#2ndyear_add').val();
      var third_year = $('#3rdyear_add').val();
      var fourth_year = $('#4thyear_add').val();
      var fifth_year = $('#5thyear_add').val();
       
      $.ajax({
                url: 'add-discount',
                type: 'POST',
                dataType: 'html',
                data: {
                       
                    discount_for  : discount_for,
                    first_year    : first_year,
                    second_year   : second_year,
                    third_year    : third_year,
                    fourth_year   : fourth_year,
                    fifth_year    : fifth_year
                }
            }).done(function(data) {
                alert ('success');
                $('.close').click();
                
            });
  });
</script>


<script src="<?php echo e(asset('public/backEnd/')); ?>/js/croppie.js"></script>
<script src="<?php echo e(asset('public/backEnd/')); ?>/js/editStaff.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backEnd.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Mis-Saigonstar\resources\views/backEnd/feesCollection/fee_policies/list_group.blade.php ENDPATH**/ ?>