<template>
  <portal to="modal-detail">
    <div class="modal fade" 
      aria-hidden="true" 
      id="modal-detail">
      <div class="modal-dialog modal-lg">
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
                    <label for="fullname">Nama Lengkap</label>
                    <div>{{ parameters.form.fullname }}</div>
                  </div>
                </div>

                <div class="col">                  
                    <div class="form-group">
                      <label for="uername">Username</label>
                      <div>{{ parameters.form.username }}</div>
                    </div>                  
                </div>
              </div>
              
             <div class="form-group">
                <label for="email">Email</label>
                <div>{{ parameters.form.email }}</div>
             </div>     

             <div class="table table-responsive">
               <table class="table">
                 <tr>
                   <td>Hak Akses</td>
                   <td>Operator</td>
                 </tr>
                 <tr v-for="item in parameters.form.group_role.grants"
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
                 <tr v-if="!parameters.form.group_role.grants.length">
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
          group_role : { 
            grants : []
          }
        }
      }
    };
  }
};
</script>
