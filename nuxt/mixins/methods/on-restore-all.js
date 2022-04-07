export default {
  data(){
    return {
      isLoadingRestoreAll : false
    }
  },
	methods : {
	   onRestoreAll(){
        this.isLoadingRestoreAll = true;      

        this.$axios.post(this.url + "/restore-all",{
          checkboxs : this.checkboxs          
        })
        .then(res => {
          this.$toaster.success("Berhasil merestore semua data");
          this.onLoad();
        })
        .catch(err => {
          if(err.response && err.response.status === 500){
            this.$toaster.error(err.response.data.message || "Terjadi Kesalahan")
          }else{
            this.$toaster.error("Terjadi Kesalahan")
          }       
        })
        .finally(() => {
          this.isLoadingRestoreAll = false;
        })
    }
	}
}