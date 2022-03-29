export default {
	methods : {
		onSort(column,sort = 'asc'){
	      	this.parameters.params = {
        		...this.parameters.params,
    	    	order : column,
	        	sort : sort
	      	}

	      	this.onLoad()
    	},
	}
}