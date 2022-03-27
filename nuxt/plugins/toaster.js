import Vue from 'vue'
import Toaster from 'v-toaster'
import 'v-toaster/dist/v-toaster.css'

Vue.prototype.$globalErrorToaster = function($toaster,$err){
   if($err.response && $err.response.status === 422){
      $toaster.error($err.response.data.message || 'Terjadi Kesalahan');
    }else if($err.response && $err.response.status === 500){
      $toaster.error($err.response.data.message || 'Terjadi Kesalahan');
    }else if($err.response && $err.response.status === 503){
      $toaster.error("Maintenance");
    }else if($err.response && $err.response.status === 401){
      $toaster.error($err.response.data.message || 'Terjadi Kesalahan');
      if($err.response.data.message == "Unauthorized"){
        this.$auth.logout().then(() => {
          window.location = "/";
        })
      }
    }else {      
      $toaster.error('Terjadi Kesalahan');
    }
}

Vue.use(Toaster, { timeout: 10000 })