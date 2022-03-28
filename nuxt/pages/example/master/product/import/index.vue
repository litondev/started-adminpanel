<template>
  <section class="section">
  
    <!-- BODY SECTION -->
    <div class="section-body">      
      <div class="card">
        <div class="card-title">
          <div class="clearfix col-12 mt-4">
            <div class="float-left">
              <h6>{{title}}</h6>           
            </div>	    
              <div class="float-right">
                <button class="btn btn-primary"
                    @click="onOpenReport()">
                    <i class="fas fa-clipboard"></i> Report
                </button>
              </div> 
          </div>
        </div>

        <div class="card-body col-12">           
        	<form name="form-import"
        		@submit.prevent="onSubmit">
	        	<table class="table table-striped table-sm mt-3 table-hover mt-3">	            
                <tr>
                    <td width="200px">Download Template Excel</td>
                    <td>
                        <a href="#" 
                            @click="onDownloadTemplate"
                            v-if="!isLoadingDownloadTemplate">
                            Download
                        </a>
                        <a href="#" v-else>
                            <i class="fas fa-circle-notch fa-spin"></i>
                        </a>
                    </td>
                </tr>                    
	            	<tr>
	            		<td>File (Excel Dan Csv)</td>
	            		<td>            			 
	            			<input type="file" 
	            				name="file"
	            				class="form-control"
	            				required>	        

                      <div class="text-small">
                        <span class="text-danger">*Note : </span> <br/>
                        <div>- Template(peletakan kolom di file) harus sesuai dengan Template yang kami sediakan</div>
                        <div>- Baris Ke 1 (<b>Header</b>) dan 2 (<b>Example/Contoh</b>) akan di abaikan/tidak di akan proses</div>
                        <div>- File harus berukuran di bawah 50mb</div>
                        <div>- Baris max 100 baris data</div>
                      </div>
	            		</td>
	            	</tr>	            	
	            	<tr>
	            		<td colspan="2">
	            			<button class="btn btn-success"
	            				:disabled="isLoadingForm">
	            				<span v-if="isLoadingForm">
                				<i class="fas fa-circle-notch fa-spin"></i> 
                          Import
            					</span>
            					<span v-else>
            						<i class="fas fa-upload"></i> 
                          Import
            					</span>
	            			</button>
                    <span class="btn btn-warning cursor-pointer"
                      @click="$router.back()"
                      v-if="!isLoadingForm">
                      <i class="fas fa-arrow-left"></i>
                        Kembali
                    </span>
	            		</td>
	            	</tr>
	            </table>
        	</form>
        </div>
      </div>
    </div>
    <!-- BODY SECTION -->

    <!-- MODAL REPORT -->
    <modal-report 
        :self="this"/>

    <!-- MODAL ERROR REPORT -->
    <modal-error-report
        :self="this"/>        

  </section>
</template>

<script>
import ModalReport from "./modal-report";
import ModalErrorReport from "./modal-error-report";

export default {   
    middleware({ store, redirect, route}) {
      let user = store.state.auth.user;

      if(!user.parent_id){
          return ;
      }
  
      // master-product-import

      // 0 => master (MODULE)
      // 1 => product (MENU)
      // 2 => import (SUB MENU)

      let route_name = route.name.split("-");

      let main_role = user.group_role.roles.find(item => item.name == "product");

      if(!main_role){
          return redirect("/");
      }

      if(JSON.parse(main_role.pivot.operators).includes("all")){
          return ;
      }
          
      if(            
        !(
          JSON.parse(main_role.pivot.operators).includes("import")
          &&
          JSON.parse(main_role.pivot.operators).includes("import-template")            
        )
      ){
        return redirect("/");  
      }    
  },
    
  head(){
    return {
      title : this.title
    }
  },

  components: { 
    ModalReport,
    ModalErrorReport
  },

  data () {
    return {
      url : "/master/product/import",     
      title : 'Import data produk',
      isLoadingDownloadTemplate : false,
      isLoadingForm : false, 
      errorSelected : {},        
      data : {
          success : [],
          faileds : []
      }
    }
  },

  methods : {
      onOpenReport(){
          window.$("#modal-1").modal("show");
      },

      onSelectedError(item){
          this.errorSelected = item;

          window.$("#modal-1").modal("hide");

          setTimeout(() => {
              window.$("#modal-2").modal("show");
          },500);
      },

      onBackOpenReport(){
          window.$("#modal-2").modal("hide");

          setTimeout(() => {
              window.$("#modal-1").modal("show");
          },500);
      },

      onBackPage(){
          window.$("#modal-1").modal("hide");
          window.$("#modal-2").modal("hide");

          setTimeout(() => {
              this.$router.back();
          },500);
      },

      onSubmit(){
        if(this.isLoadingForm) return;

        let formData = new FormData(document.querySelector("form[name=form-import]"));

        this.isLoadingForm = true;

        this.$axios.post(this.url,formData)
        .then(res => {
          document.querySelector("form[name=form-import]").reset(); 

          this.data = res.data;

          if(!res.data.faileds.length){
            if(res.data.success.length){
                window.$("#modal-1").modal("show");
                this.$toaster.success("Berhasil import data");  
            }else{
                this.$toaster.warning("Sepertinya data kosong");
            }
          }else{
            window.$("#modal-1").modal("show");

            if(res.data.success.length){
                this.$toaster.success("Berhasil Import : " + res.data.success.length + " Baris");
            }

            this.$toaster.warning("Gagal Import : " + res.data.faileds.length + " Baris");
          }
        })
        .catch(err => {
          console.log(err);
          this.$globalErrorToaster(this.$toaster,err);		    
        })
        .finally(() => {
          this.isLoadingForm = false;
        })
      },

      onDownloadTemplate(){
        if(this.isLoadingDownloadTemplate) return;
      
        this.isLoadingDownloadTemplate = true;
            
        this.$axios({
          url : this.url,
          responseType : "blob",        			
        })
        .then(res => {
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(new Blob([res.data]));
          link.setAttribute('download', 'template-' + this.$route.name + '.' + 'xlsx');
          document.body.appendChild(link);
          link.click();
        })
        .catch(err => {
          console.log(err);          
          this.$globalErrorToaster(this.$toaster,err);
        })
        .finally(() => {
          this.isLoadingDownloadTemplate = false;
        })
      }
  }	
}
</script>