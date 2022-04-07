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
          if(err.response && err.response.status === 500){
            this.$toaster.error(err.response.data.message || "Terjadi Kesalahan")
          }else{
            this.$toaster.error("Terjadi Kesalahan")
          }       
        })
        .finally(() => {
          this.isLoadingRestore = false;
          this.idLoadingRestore = 0;
        })
    },

	}
}