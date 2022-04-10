export default {
  data(){
    return {
      isLoadingForm : false, 
    }
  },      
  
  methods : {  
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
                this.onOpenReport();

                this.$toaster.success("Berhasil import data");  
            }else{
                this.$toaster.warning("Sepertinya data kosong");
            }
          }else{
            this.onOpenReport();

            if(res.data.success.length){
                this.$toaster.success("Berhasil Import : " + res.data.success.length + " Baris");
            }

            this.$toaster.warning("Gagal Import : " + res.data.faileds.length + " Baris");
          }
        })
        .catch(err => {
          console.log(err);
          this.$globalErrorToaster(err);        
        })
        .finally(() => {
          this.isLoadingForm = false;
        })
      },
  }
}