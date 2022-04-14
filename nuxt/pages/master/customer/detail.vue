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
                  <div class="col-md-6">                  
                    <div class="form-group p-2">
                      <label for="code">Kode</label>
                      <div>{{$parent.detail_form.code}}</div>
                    </div>                  
                  </div>

                  <div class="col-md-6">
                    <div class="form-group p-2">
                      <label for="name">Nama</label>
                      <div>{{$parent.detail_form.name}}</div>
                    </div>
                  </div>                             
              </div>              

              <div class="form-group p-2">
                <label for="payment_type">Type Pembayaran</label>
                <div>{{$parent.detail_form.payment_type}}</div>
              </div>

              <div class="form-group p-2">
                <label for="user_id">User</label>
                <div>{{$parent.detail_form.user ? $parent.detail_form.user.name : '-'}}</div>
              </div>

              <div class="form-group p-2">
                <label for="address">Alamat</label>
                <div>{{$parent.detail_form.address || '-'}}</div>
              </div>
            </div>            
        </div>
      </div>
    </div>
  </portal>
</template>