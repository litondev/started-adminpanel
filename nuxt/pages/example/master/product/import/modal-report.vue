<template>
    <portal to="modal">
      <div class="modal fade" 
        aria-hidden="true"  
        id="modal-1">
        <div class="modal-dialog modal-lg">    
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Report Import
                </h5>

                <button type="button" 
                    class="close" 
                    data-dismiss="modal" 
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                    aria-selected="true">
                        Success
                        <b class="text-success">{{self.data.success.length}}</b>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link text-danger" 
                    id="faileds-tab" 
                    data-toggle="tab" 
                    href="#faileds" 
                    role="tab" 
                    aria-controls="faileds" 
                    aria-selected="false">
                        Faileds
                        <b class="text-danger">{{self.data.faileds.length}}</b>
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
                                    <th>Nama</th>
                                </tr>                       
                            </thead>
                            <tbody>
                                <tr v-for="item,index in self.data.success"
                                    :key="index">
                                    <td><b class="text-success">{{item.row}}</b></td>
                                    <td>{{item.name}}</td>
                                </tr>     
                                <tr v-if="!self.data.success.length">
                                    <td colspan="2" class="text-center">
                                        Data Tidak ditemukan
                                    </td>
                                </tr>
                            </tbody>                    
                        </table>
                    </div>
                
                    <span class="btn btn-success cursor-pointer"
                        @click="self.onBackPage()"
                        v-if="!self.isLoadingForm && self.data.success.length">
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
                                    <th>Nama</th>
                                    <th>Detail</th>
                                </tr>                       
                            </thead>
                            
                            <tbody>
                            <tr v-for="item,index in self.data.faileds"
                                :key="index">
                                <td><b class="text-danger">{{item.row}}</b></td>
                                <td>{{item.name}}</td>
                                <td>
                                    <button class="btn btn-info"
                                        @click="self.onSelectedError(item)">
                                        Detail
                                    </button>                                    
                                </td>
                            </tr>                                                          
                
                            <tr v-if="!self.data.faileds.length">
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
export default {
    middleware : ["isNotAccessable"],
    props : ["self"]
}
</script>