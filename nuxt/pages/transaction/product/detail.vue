<template>
  <portal to="modal-detail">
    <div class="modal fade" 
      aria-hidden="true" 
      id="modal-detail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Detail Data</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
     
            <div class="modal-body">
              <div class="row">
                  <div class="col-12">                  
                    <div class="form-group p-2">
                      <label for="code">Kode</label>
                      <div>{{$parent.detail_form.code}}</div>
                    </div>                  
                  </div>                                            
              </div>              

              <div class="row">
                <div class="col-md-6">
                  <div class='form-group p-2'>
                    <label for="customer_id">Pelanggan</label>
                    <div>{{$parent.detail_form.customer ? $parent.detail_form.customer.name : '-'}}</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group p-2">
                    <label for="user_id">Pengguna</label>
                    <div>{{$parent.detail_form.user ? $parent.detail_form.user.name : '-'}}</div>
                  </div>
                </div>
              </div>

              <div class="form-group p-2">
                <label for="total">Total</label>
                <div>{{$parent.detail_form.total}}</div>
              </div>

              <div class="form-group p-2">
                <label for="grand_total">Grand Total</label>
                <div>{{$parent.detail_form.grand_total}}</div>
              </div>

              <div class="form-group p-2">
                <label for="quantity">Jumlah</label>
                <div>{{$parent.detail_form.quantity}}</div>
              </div>
            </div>            
        </div>
      </div>
    </div>
  </portal>
</template>