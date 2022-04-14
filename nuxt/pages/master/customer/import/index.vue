<template>
 <div class="page-wrapper">
    <div class="page-body">
      <div class="container-fluid">
       <div class="card">              
        <div class="card-body col-12">           
            <div class="clearfix mt-4">
              <div class="float-left">
                <h3>{{title}}</h3>          
              </div>      
              <div class="float-right">
                <button class="btn btn-primary btn-sm"
                    @click="onOpenReport()">
                    <i class="fas fa-clipboard" 
                      style="margin-right: 5px"></i> Report
                </button>
              </div> 
            </div>

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
	            </table>

              <div class="mt-2">              
                <button class="btn btn-success btn-sm"
                  :disabled="isLoadingForm">
                  <span v-if="isLoadingForm">
                    <i class="fas fa-circle-notch fa-spin"></i> 
                      Import
                  </span>
                  <span v-else>
                    <i class="fas fa-upload" style="margin-right: 5px"></i> 
                      Import
                  </span>
                </button>

                <span class="btn btn-warning btn-sm cursor-pointer"
                  @click="$router.back()"
                  v-if="!isLoadingForm">
                  <i class="fas fa-arrow-left" style="margin-right: 5px"></i>
                    Kembali
                    </span>
              </div>
        	</form>
        </div>
      </div>
      </div>
    </div>
    <!-- BODY SECTION -->

    <!-- MODAL REPORT -->
    <modal-report /> 

    <!-- MODAL ERROR REPORT -->
    <modal-error-report />      
  </div>
</template>

<script>
import ModalReport from "@/components/global/imports/modal-report";
import ModalErrorReport from "@/components/global/imports/modal-error-report";

import PageHead from "@/mixins/heads/head";

import MixinOnSubmit from "@/mixins/methods/imports/on-submit";
import MixinOnOpenReport from "@/mixins/methods/imports/on-open-report";
import MixinOnDownloadTemplate from "@/mixins/methods/imports/on-download-template";

export default {       
  mixins : [
    PageHead,

    MixinOnSubmit,
    MixinOnOpenReport,
    MixinOnDownloadTemplate
  ],

  components: { 
    ModalReport,
    ModalErrorReport
  },

  data () {
    return {
      url : "/master/customer/import",

      title : 'Import data pelanggan',    

      errorSelected : {}, 

      modal_report : null,
      modal_error_report : null,

      data : {
          success : [],
          faileds : []
      }
    }
  }
}
</script>