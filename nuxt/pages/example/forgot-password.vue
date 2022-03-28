<template>
 <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">

            <div class="login-brand">
              <img :src="logo_url" 
                alt="logo"               
                width="100" 
                class="shadow-light rounded-circle">
            </div>  

            <div class="card card-primary">
              <div class="card-header">
                <h4>Forgot Password</h4>
              </div>

              <div class="card-body">
                <p class="text-muted">
                  We will send a link to reset your password
                </p>

                <ValidationObserver v-slot="{invalid,validate}">  
                  <form @submit.prevent="validate().then(onSubmit(invalid))"
                    autocomplete="off">
                    <ValidationProvider 
                      name="email"
                      rules="required|email">
                      <div class="form-group" slot-scope="{errors,valid}">
                        <label for="email">Email</label>
                        <input id="email" 
                          type="email" 
                          class="form-control" 
                          name="email"
                          v-model="form.email"
                          :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                        />
                          <div class="invalid-feedback" v-if="errors[0]">
                            {{ errors[0] }}
                          </div>   
                      </div>
                    </ValidationProvider>

                    <div class="form-group">
                      <button type="submit" 
                        class="btn btn-primary btn-lg btn-block"
                        :disabled="isLoadingForm">
                        <span v-if="isLoadingForm">
                          <i class="fas fa-circle-notch fa-spin"></i>
                        </span>
                        <span v-else>                
                          Forgot Password
                        </span>
                      </button>
                    </div>

                    <div class="form-group">
                      <nuxt-link to="/login">Kembali</nuxt-link>
                    </div>
                  </form>
                </ValidationObserver>
              </div>
            </div>
            <div class="simple-footer">
              Copyright &copy; Stisla 2018
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default{ 
    layout : 'empty',

    middleware : "isNotLogin",  

    head() {
      return {
        title: 'Forgot Password',
      }
    },
        
    data(){
		  return {
        isLoadingForm : false,
			  form : {
				  email : null,
			  }
		  }
	  },

    computed : {
      logo_url(){
        return (process.env.LOGO_URL || '/img/') + 'default.jpeg';
      }
    },

    methods:{
      onSubmit(isInvalid){	 
        if(isInvalid || this.isLoadingForm) return;        

        this.isLoadingForm = true;

        this.$axios.post("/forgot-password",this.form)
        .then(() => {
          this.$toaster.success("Silahkan Cek Email Anda");
        })
        .catch(err => {
          console.log(err);          
          this.$globalErrorToaster(this.$toaster,err);
        })
        .finally(() => {
          this.isLoadingForm = false;
        });
      }
    }
}
</script>