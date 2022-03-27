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
              <div class="form-group">
                <label for="name">Nama</label>
                <div>{{ parameters.form.name }}</div>
              </div>

             <div class="table table-responsive">
               <table class="table">
                 <tr>
                   <td>Hak Akses</td>
                   <td>Operator</td>
                 </tr>
                 <tr v-for="item in parameters.form.grants"
                  :key="item.id">
                  <td>{{item.role ? item.role.title : '-'}}</td>
                  <td>
                    <span class="badge badge-success ml-1 mr-1 mb-2"
                      v-for="itemOperator in item.operators"
                      :key="itemOperator">
                      {{itemOperator}}
                    </span>                  
                  </td>
                 </tr>
                 <tr v-if="!parameters.form.grants.length">
                   <td colspan="2" class="text-center">
                     Data tidak ditemukan
                   </td>
                 </tr>
               </table>
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
        form : {
          grants : []
        }
      }
    };
  }
};
</script>
