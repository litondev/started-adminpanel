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

              <div class="row">
                <div class="col-md-6">
                  <div class='form-group p-2'>
                    <label for="username">Username</label>
                    <div>{{$parent.detail_form.username}}</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group p-2">
                    <label for="email">Email</label>
                    <div>{{$parent.detail_form.email}}</div>
                  </div>
                </div>
              </div>
            </div>            
        </div>
      </div>
    </div>
  </portal>
</template>