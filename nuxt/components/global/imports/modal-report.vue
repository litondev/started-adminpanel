<template>
  <portal to="modal-other" :order="1"> 
    <div class="modal fade" 
      aria-hidden="true"  
      id="modal-report">
      <div class="modal-dialog modal-lg">    
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">
                  Report Import
              </h5>

              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">                
              <ul class="nav nav-tabs" 
                  id="myTab" 
                  role="tablist">
                <li class="nav-item">
                  <a class="nav-link active text-success" 
                  id="success-tab"
                  data-toggle="tab" 
                  href="#success" 
                  role="tab" 
                  aria-controls="success" 
                  aria-selected="true"
                  data-bs-toggle="tab"
                  data-bs-target="#success">
                      Success
                      <b class="text-success"> {{$parent.data.success.length}}</b>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-danger" 
                  id="faileds-tab" 
                  data-toggle="tab" 
                  href="#faileds" 
                  role="tab" 
                  aria-controls="faileds" 
                  aria-selected="false"
                  data-bs-toggle="tab"
                  data-bs-target="#faileds">
                      Faileds
                      <b class="text-danger"> {{$parent.data.faileds.length}}</b>
                  </a>
                </li>
              </ul>

              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" 
                  id="success" 
                  role="tabpanel" 
                  aria-labelledby="success-tab">
                  <div style="overflow-y:auto;max-height:500px">
                      <table class="table table-hover table-bordered table-striped">
                          <thead>                           
                              <tr>
                                  <th>Baris</th>
                                  <th>Kode</th>
                              </tr>                       
                          </thead>
                          <tbody>
                              <tr v-for="item,index in $parent.data.success"
                                  :key="index">
                                  <td><b class="text-success">{{item.row}}</b></td>
                                  <td>{{item.code}}</td>
                              </tr>     
                              <tr v-if="!$parent.data.success.length">
                                  <td colspan="2" class="text-center">
                                      Data Tidak ditemukan
                                  </td>
                              </tr>
                          </tbody>                    
                      </table>
                  </div>
              
                  <span class="btn btn-success cursor-pointer"
                      @click="onBackPage()"
                      v-if="!$parent.isLoadingForm && $parent.data.success.length">
                      <i class="fas fa-eye"></i>
                      Lihat Hasil
                  </span>
                </div>
                <div class="tab-pane fade" 
                  id="faileds" 
                  role="tabpanel" 
                  aria-labelledby="faileds-tab">
                  <div style="overflow-y:auto;max-height:500px">
                      <table class="table table-hover table-bordered table-striped">
                          <thead>                
                              <tr>
                                  <th>Baris</th>
                                  <th>Kode</th>
                                  <th>Detail</th>
                              </tr>                       
                          </thead>
                          
                          <tbody>
                          <tr v-for="item,index in $parent.data.faileds"
                              :key="index">
                              <td><b class="text-danger">{{item.row}}</b></td>
                              <td>{{item.code}}</td>
                              <td>
                                  <button class="btn btn-info"
                                      @click="onSelectedError(item)">
                                      Detail
                                  </button>                                    
                              </td>
                          </tr>                                                          
              
                          <tr v-if="!$parent.data.faileds.length">
                              <td colspan="3" class="text-center">
                                  Data Tidak ditemukan
                              </td>
                          </tr>  
                          </tbody>                      
                      </table>
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
export default{
    methods : {     
      onSelectedError(item){
          this.$parent.errorSelected = item;

          this.$parent.modal_report.hide();
    
          setTimeout(() => {
              this.$parent.modal_error_report.show();
          },500);
      },

      onBackPage(){
          this.$parent.modal_report.hide();
          this.$parent.modal_error_report.hide();

          setTimeout(() => {
              this.$router.back();
          },1000);
      }   
  } 
}
</script>