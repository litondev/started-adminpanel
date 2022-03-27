const actions = {
  printFile({},payload) {    
    var token = this.$auth.ctx.$cookiz.get(('auth._token.local')).replace('Bearer ','');
    let tokenParam = "?token="+token+"&all=true"
    window.open(process.env.API_URL+'/'+payload.parameters.url+'/print'+tokenParam,'_blank');
  },

  exportFile({},payload){
    var token = this.$auth.ctx.$cookiz.get(('auth._token.local')).replace('Bearer ','');    
    let tokenParam = '?token='+token+'&all=true' 
  
    window.open(process.env.API_URL+'/'+payload.parameters.url+'/export/'+payload.type + tokenParam,'_blank');
  }
}

export default {
  namespaced : true,
  actions,
}
