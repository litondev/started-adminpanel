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
                    <label for="name">Nama</label>
                    <div>{{ parameters.form.name }}</div>
                  </div>
                </div>

                <div class="col">                  
                    <div class="form-group">
                      <label for="title">Judul</label>
                      <div>{{ parameters.form.title }}</div>
                    </div>                  
                </div>
              </div>
              
             <div class="form-group">
                <label for="icon">Icon</label>
                <div>
                  <i :class="'fas fa-' + parameters.form.icon + ' fa-2x'"></i>
                </div>
             </div>     

             <div class="form-group">
               <label for="">Module</label>
               <div>
                 <span v-if="parameters.form.parent_id"
                  class="badge badge-danger">
                  Tidak
                 </span>
                 <span v-else
                  class="badge badge-success"> 
                   Ya
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
