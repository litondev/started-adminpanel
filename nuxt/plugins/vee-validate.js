import Vue from 'vue'
import { extend } from "vee-validate";
import { required,email,min,max,confirmed,numeric} from "vee-validate/dist/rules";
import {ValidationProvider,ValidationObserver} from 'vee-validate';

extend("required",{
    ...required,
    message : field => 'Data harus diisi'
});

extend("email",{
    ...email,
    message : field => 'Email tidak valid'
});

extend("min",{
    ...min,
    message : (field,args) => 'Harus lebih dari ' + args.length + ' digit'
})

extend("max",{
    ...max,
    message : (field,args) => 'Harus kurang atau sama dengan  ' + args.length + ' digit'
})

extend("confirmed",{
    ...confirmed,
    message : (field) => "Data tidak sama"
});

extend("numeric",{
    ...numeric,
    message : () => "Data harus angka"
})

extend("decimal", {
    validate: (value,decimals = '*', separator = '.') => {
      if (value === null || value === undefined || value === '') {
        return {
          valid: false
        };
      }
      if (Number(decimals) === 0) {
        return {
          valid: /^-?\d*$/.test(value),
        };
      }
      const regexPart = decimals === '*' ? '+' : `{1,${decimals}}`;
      const regex = new RegExp(`^[-+]?\\d*(\\${separator}\\d${regexPart})?([eE]{1}[-]?\\d+)?$`);
  
      return {
        valid: regex.test(value),
      };
    },
    message: (fields,args) => {        
        return 'Data harus desimal dengan bilangan kurang dari '+args[0]+' bilangan';
    }
})

Vue.component("ValidationObserver",ValidationObserver);
Vue.component("ValidationProvider",ValidationProvider);
