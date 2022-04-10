export default {
	methods : {		
	    onEdit(item){
	      this.$refs["modal-form"].$refs["form"].reset();
	      
	      this.form = {
	        ...item
	      };

	      if(this.relasional_form && this.relasional_form.length){
	        this.relasional_form.forEach(itemRelasionalForm => {
	          this.form[itemRelasionalForm['form']] = item[itemRelasionalForm['item']] || '';
	        });
	      }
	  
	      // console.log(this.form);

	      this.isEditable = true;      

	      this.modal_form = new bootstrap.Modal(document.getElementById('modal-form'), {
	          keyboard: false
	      })

	      this.modal_form.show();
	    },		
	}
}