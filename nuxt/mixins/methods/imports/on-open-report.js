export default {
	methods : {
	 onSetModalInstance(){
 	  	this.modal_error_report = new bootstrap.Modal(document.getElementById("modal-error-report"),{
 	  		keyboard : false
 	  	})

	 	this.modal_report = new bootstrap.Modal(document.getElementById('modal-report'), {
		    keyboard: false
		})
	 },

	 onOpenReport(){	
		this.onSetModalInstance();
		
        this.modal_report.show();
      }
	}
}