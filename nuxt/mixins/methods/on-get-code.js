export default {
	data(){
		return {
			isLoadingGetCode : false,
		}
	},
	
	methods : {
	    onGetCode(){
	      if(this.isLoadingGetCode) return;

	      this.isLoadingGetCode = true;

	      this.$axios.get(this.url + "/get-code")
	        .then(res => {
	          this.form.code = res.data.code;
	        })
	        .catch(err => {
	          this.$globalErrorToaster(err);
	        })
	        .finally(() => {
	          this.isLoadingGetCode = false;
	        });
	    },	
	}
}