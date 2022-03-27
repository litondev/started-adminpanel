<template>
  <portal to="modal-detail">
    <div class="modal fade" 
      aria-hidden="true" 
      id="modal-detail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Detail Data</h5>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
     
            <div class="modal-body">
              <div class="row">
                  <div class="col">                  
                    <div class="form-group">
                      <label for="code">Kode</label>
                      <div>{{ parameters.form.code }}</div>
                    </div>                  
                </div>

                <div class="col">
                  <div class="form-group">
                    <label for="name">Nama</label>
                    <div>{{ parameters.form.name }}</div>
                  </div>
                </div>             
              </div>              

              <div class="form-group">
                <label for="address">Alamat</label>
                <div>{{parameters.form.address || '-'}}</div>
              </div>  

              <div class="form-group">
                <label for="city">Kota</label>
                <div>{{parameters.form.city}}</div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <div>{{parameters.form.email}}</div>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="phone">No Telp</label>
                    <div>{{parameters.form.phone}}</div>
                  </div>
                </div>
              </div>
            </div>            
        </div>
      </div>
    </div>
  </portal>
</template>

<script>
export default {
  middleware : ["isNotAccessable"],
  
  props: ["self"],

  data() {
    return {    
      parameters : {    
        form : {}
      }
    };
  }
};
</script>
