export default {
	methods : {
	 onCheckAll(evt){
      	let tmpCheckboxs = [];

      	document.querySelectorAll("input[name='checkboxs[]']").forEach(item => {
        	item.checked = evt.target.checked;
        	if(evt.target.checked){
          	tmpCheckboxs.push(item.value);
	        }
      	})

      		this.checkboxs = tmpCheckboxs
    	}    
	}
}