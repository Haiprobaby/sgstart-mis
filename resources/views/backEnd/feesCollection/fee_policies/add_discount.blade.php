<div class="modal fade" id="adddiscount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Discount</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" >
          @csrf
          
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Name:</label>
            <input type="text" class="form-control" id="discount_for_add">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">First year:</label>
            <input type="text" class="form-control" id="1styear_add">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Second year:</label>
            <input type="text" class="form-control" id="2ndyear_add">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Third year:</label>
            <input type="text" class="form-control" id="3rdyear_add">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Fourth year:</label>
            <input type="text" class="form-control" id="4thyear_add">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Fifth year+:</label>
            <input type="text" class="form-control" id="5thyear_add">
          </div>

          <div class="modal-footer">
        
            <button type="button" id="add_discount" class="btn btn-primary">Submit</button>
          </div>

        </form>
      </div>
      
    </div>
  </div>
</div>