const state = () => ({
    settings    : [],
})

const mutations = {
     SET_SETTINGS: (state,settings) => {
        state.settings = settings;
     }
}

const actions = {
    async nuxtClientInit({commit},{app}) {
      try{   
        let response = await app.$axios.get("/get-setting");    
        commit("SET_SETTINGS",response.data)
      }catch(err){
        console.log(err);
        commit("SET_SETTINGS",[]);
      }
    },
    async setSetting({commit,dispatch},payload) {
      try{
        let response = await this.$axios.get("/get-setting");    
        commit("SET_SETTINGS",response.data)
      }catch(err){
        console.log(err);
        commit("SET_SETTINGS",[]);
      }
    }
}

export default {
  namespaced : true,
  state,
  mutations,
  actions,
}