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
          this.$globalErrorToaster(err);
        })
        .finally(() => {
          this.isLoadingRestoreAll = false;
        })
    }
	}
}