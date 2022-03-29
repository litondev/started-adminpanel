export default {
	methods : {
		onLoad(){
      		this.isLoadingPage = true;

      		this.$axios.get(this.url,{
		        params : this.parameters.params 
      		})
      		.then(res => {
	        	this.isLoadingPage = false;

        		this.items = {
	          		...res.data
        	}

		        console.log(res.data);
      		})
      		.catch(err => {
	    	    console.log(err);
	        	this.$toaster.error("Terjadi kesalahan saat mengambil data");
      		})  
    	},
	}
}