export default {
	data(){
		return {
			isLoadingDownloadTemplate : false,
		}
	},
	
	methods : {
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
          this.$globalErrorToaster(err);
        })
        .finally(() => {
          this.isLoadingDownloadTemplate = false;
        })
      }
	}
}