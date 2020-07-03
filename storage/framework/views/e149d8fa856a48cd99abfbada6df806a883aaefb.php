<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Discount</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" >
          <?php echo csrf_field(); ?>
          <input type="hidden" name="id_discount" id="id_discount">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Name:</label>
            <input type="text" class="form-control" id="discount_for">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">First year:</label>
            <input type="text" class="form-control" id="1styear">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Second year:</label>
            <input type="text" class="form-control" id="2ndyear">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Third year:</label>
            <input type="text" class="form-control" id="3rdyear">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Fourth year:</label>
            <input type="text" class="form-control" id="4thyear">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Fifth year+:</label>
            <input type="text" class="form-control" id="5thyear">
          </div>

          <div class="modal-footer">
        
            <button type="button" id="edit_discount" class="btn btn-primary">Submit</button>
          </div>

        </form>
      </div>
      
    </div>
  </div>
</div><?php /**PATH C:\wamp64\www\Mis-Saigonstar\resources\views/backEnd/feesCollection/fee_policies/edit_discount.blade.php ENDPATH**/ ?>