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
                    <label for="date">Tgl</label>
                    <div>{{ parameters.form.date }}</div>
                  </div>
                </div>             
              </div>  

              <div class="form-group">
                <label for="customer_id">Pelanggan</label>
                <div>
                  {{parameters.form.customer ? parameters.form.customer.name : '-'}}
                </div>
              </div>    

              <div class="form-group">
                <label for="user_id">Pengguna</label>
                <div>
                  {{parameters.form.user ? parameters.form.user.username : '-'}}
                </div>
              </div>

              <div class="form-group">
                <label for="description">Deskripsi</label>
                <div>{{parameters.form.description || '-'}}</div>
              </div>  

              <div class="form-group">
                <label for="progress">Progress</label>
                <div>
                  <span v-if="parameters.form.progress == 'Follow Up'"
                    class="badge badge-warning">
                    Follow Up
                  </span>
                  <span v-else-if="parameters.form.progress == 'Reject'"
                    class="badge badge-danger">
                    Reject
                  </span>
                  <span v-else-if="parameters.form.progress == 'Order'"
                    class="badge badge-success">
                    Order
                  </span>
                  <sapn v-else-if="parameters.form.progress == 'Quotation'" 
                    class="badge badge-primary">
                    Quotation 
                  </sapn>
                  <span class="badge badge-info"
                    v-else>
                    Prospect
                  </span>                         
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
