export default {
	data(){
		return {
      		isLoadingForm : false,
      		isEditable : false,  
		}
	},

	methods : {
		onSubmit(isInvalid){
	      if(isInvalid || this.isLoadingForm) return;

	      this.isLoadingForm = true;

	      let formData = {
	        ...this.form,  
	      };

	      if(this.relasional_form && this.relasional_form.length){
	        this.relasional_form.forEach(itemRelasionalForm => {
	          formData[itemRelasionalForm['form']] = typeof formData[itemRelasionalForm['form']] == 'object' ? formData[itemRelasionalForm['form']].id : ''
	        });
	      }

	      // console.log(formData);

	      let url = this.url;

	      if(this.isEditable){
	        url += "/" + formData["id"];        
	        formData["_method"] = "PUT";
	      }

	      this.$axios.post(url,formData)
	      .then(res => {
	        this.onLoad();

	        this.$toaster.success("Berhasil " + (this.isEditable ? "Edit" : "Tambah") + " data");

	        this.modal_form.hide();
	      })
	      .catch(err => {
	       this.$globalErrorToaster(err);
	      })
	      .finally(() => {
	        this.isLoadingForm = false;
	      })
	    }
	}
}