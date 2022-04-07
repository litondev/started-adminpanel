<template>
  <portal to="modal-report">
    <div class="container-modal-report"
      id="modal-report">
      <div class="row">
        <div class="col-md-8 left-modal-report" 
          @click="onHideReport"></div>
        <div class="col-md-4 right-modal-report">         
          <div class="d-flex flex-row justify-content-between p-3">
            <h3>Report : </h3>
            <i class="fa fa-times fa-2x cursor-pointer"
              @click="onHideReport"></i>
          </div>

          <div class="p-3">
            <slot name="top"></slot>

            <div class="pt-2 pb-2">
              <div class="p-2">Dengan Pagination </div>
              <select class="form-control"
                v-model="all">
                <option value="0">Ya</option>
                <option value="1">Tidak</option>
              </select>
            </div>

            <div class="pt-2 pb-2">          
              <button class="btn btn-primary w-100"
                @click="onPrint">
                <i class="fa fa-print"
                  style="margin-right: 5px"></i>
                Print
              </button>
            </div>

            <div class="btn-group pt-2 pb-2 w-100">            
              <button class="btn btn-danger btn-block"
                @click="onDownload('pdf')">
                <i class="fa fa-file"
                  style="margin-right: 5px"></i> Pdf
              </button>
              <button class="btn btn-success btn-block"
                @click="onDownload('excel')">
                <i class="fa fa-file"
                  style="margin-right: 5px"></i> Execl
              </button>
            </div>

            <slot name="bottom"></slot>
          </div>
        </div>
      </div>
    </div>
  </portal>
</template>

<script>
export default {  
  data(){
    return {
      all : 0
    }
  },

  methods : {
    onHideReport(){
      document.getElementById("modal-report").style.display = "none";
    },   

    onPrint(){
      let parameters = "?";

      Object.keys(this.$parent.parameters.params).forEach(item => {
          parameters += item + "=" + this.$parent.parameters.params[item] + "&";
      });
  
      parameters += parseInt(this.all) ? "all=true" : "all=";  

      window.open(
        process.env.API_URL + this.$parent.url + "/print" + parameters,
        "_blank"
      );
    },

    onDownload(type){
      let parameters = "?";

      Object.keys(this.$parent.parameters.params).forEach(item => {
          parameters += item + "=" + this.$parent.parameters.params[item] + "&";
      });
  
      parameters += parseInt(this.all) ? "all=true" : "all=";  

      window.open(
        process.env.API_URL + this.$parent.url + "/export/" + type + "/" + parameters,
        "_blank"
      );    
    }
  }
}
</script>

<style scoped>
.container-modal-report{
   position:fixed;
   top:0px;
   bottom:0px;
   left:0px;
   right:0px;
   display: none;
   z-index: 9999999
}

.left-modal-report{
  opacity:0.3;
  background:black;
  min-height:100vh;
}

.right-modal-report{
  background: white;
  min-height: 100vh;
}
</style>