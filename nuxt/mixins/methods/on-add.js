export default {
	methods : {	
		onAdd(){
		  this.$refs["modal-form"].$refs["form"].reset();

		  this.form = {...this.default_form}

		  this.isEditable = false;

		  this.onGetCode();

		  this.modal_form = new bootstrap.Modal(document.getElementById('modal-form'), {
		      keyboard: false
		  })

		  this.modal_form.show();
		},		
	}
}