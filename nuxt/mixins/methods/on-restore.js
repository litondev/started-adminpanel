export default {
  data(){
    return {
      isLoadingRestore : false,
      idLoadingRestore : 0,
    }
  },
	methods : {
	   onRestore(item){
      this.isLoadingRestore = true;  
      this.idLoadingRestore = item.id    

      this.$axios.post(this.url + "/restore/" + item.id)
        .then(res => {
          this.$toaster.success("Berhasil merestore data ini");
          this.onLoad();
        })
        .catch(err => {
          this.$globalErrorToaster(err);
        })
        .finally(() => {
          this.isLoadingRestore = false;
          this.idLoadingRestore = 0;
        })
    },

	}
}