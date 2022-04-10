export default {
	data(){
		return {
			detail_form : {},
		}
	},
	
	methods : {
	    onDetail(item){     
	      	this.detail_form = {
        	...item
	      	}
    	},		
	}
}