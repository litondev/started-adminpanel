export default {
	methods : { 
    	onPrintDetail(item){
	      	window.open(process.env.API_URL + this.url + "/get-print-detail/" + item.id,"_blank");
    	},   
	}
}