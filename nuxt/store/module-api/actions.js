export default {
    getData({commit,dispatch},payload) {
      return this.$axios.get('/' + payload.url,{
          params : payload.params
        })
        .then (response => {
          commit('set_data',response.data.data)
          commit('set_result',true);
          dispatch("pagination/setPagination",response.data, {
            root : true
          })
        }).catch(error => {
          commit('set_result',false)
          commit('set_error',error)
        });
    },
  
    addData({commit,dispatch},payload){
      return this.$axios.post('/' + payload.url,payload.form)
        .then(response => {
          commit('set_result',true);
        }).catch(error =>{
          commit('set_result',false)
          commit('set_error',error)
        })
    },
  
    updateData({commit,dispatch},payload){
      return this.$axios.put('/' + payload.url + '/' + payload.form.id,payload.form)
        .then(response => {
          commit('set_result',true);
        }).catch(error =>{
          commit('set_result',false)
          commit('set_error',error)
        })
    },
  
    deleteData({commit,dispatch},payload){
      return this.$axios.delete('/' + payload.url + '/' + payload.id)
        .then(response => {
          commit('set_result',true);
        }).catch(error =>{
          commit('set_result',false)
          commit('set_error',error)
        })
    },
  
    deleteAllData({commit,dispatch},payload){
      return this.$axios.delete("/" + payload.url + "/destroy-all",{
          data : {
            checkboxs : payload.checkboxs, 
          }
        }).then(response => {
          commit('set_result',true)
        }).catch(error => {
          commit('set_result',false)
          commit('set_error',error)
        })
    },
  
    restoreData({commit,dispatch},payload){
      return this.$axios.post("/" + payload.url + "/restore/" + payload.id)
      .then(response => {
        commit('set_result',true)
      }).catch(error => {
        commit('set_result',false)
        commit('set_error',error)
      })
    },
  
    restoreAllData({commit,dispatch},payload){
      return this.$axios.post("/" + payload.url + "/restore-all",{
        checkboxs : payload.checkboxs
      })
      .then(response => {
        commit('set_result',true)
      }).catch(error => {
        commit('set_result',false)
        commit('set_error',error)
      })
    },

    getCode({commit,dispatch},payload){
      return this.$axios.get("/" + payload.url + "/get-code" + (payload.query || ''))
        .then(response => {
          commit('set_result',true);
          commit('set_code',response.data.code);
        }).catch(error =>{
          commit('set_result',false)
          commit('set_error',error)
        })      
    },

    lookUp({commit},payload){    
      return this.$axios.get('/' + payload.url + (payload.query || ""))
      .then(response => {
        commit('set_result',true)
        switch(payload.lookup) {
          case 'roles':
            commit('set_lookup_roles',response.data)
            break;    
          case 'group_roles':
            commit('set_lookup_group_roles',response.data)
            break;
          case 'sellers':
            commit('set_lookup_sellers',response.data)
            break;
          case 'customer_groups':
            commit('set_lookup_customer_groups',response.data)
            break;
          case 'suppliers':
            commit('set_lookup_suppliers',response.data);
            break;
          case 'product_spesifications':
            commit('set_lookup_product_spesifications',response.data);
            break;
          case 'units':
            commit('set_lookup_units',response.data);
            break;
          case "customer_groups":
            commit("set_lookup_customer_groups",response.data);
            break;
          case 'customers':
            commit('set_lookup_customers',response.data)
            break;
          case 'products':
            commit('set_lookup_products',response.data)
            break;
          case 'quotations':
            commit('set_lookup_quotations',response.data);
            break;
          case 'po_customers':
            commit('set_lookup_po_customers',response.data);
            break;
          case 'po_suppliers':
            commit('set_lookup_po_suppliers',response.data);
            break;
          case 'purchaseings':
            commit('set_lookup_purchaseings',response.data);
            break;
          case 'warehouses':
            commit('set_lookup_warehouses',response.data);
            break;
        }
      }).catch(error => {
        commit('set_result',false)
        commit('set_error', error)
      })
    },
  }
  