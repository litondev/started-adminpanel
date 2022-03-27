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
              <div class="card-header"><h4>Reset Password</h4></div>

              <div class="card-body">
                <p class="text-muted">We will send a link to reset your password</p>
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
                          readonly
                        />
                          <div class="invalid-feedback" v-if="errors[0]">
                            {{ errors[0] }}
                          </div>   
                      </div>
                    </ValidationProvider>

                    <ValidationProvider 
                      name="password"
                      rules="required|min:8">
                      <div class="form-group" slot-scope="{errors,valid}">
                        <label for="password">Password Baru</label>
                        <input id="password" 
                          type="password" 
                          class="form-control"                      
                          name="password"                                                                       
                          v-model="form.password"
                          :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                        />
                          <div class="invalid-feedback" v-if="errors[0]">
                            {{ errors[0] }}
                          </div>   
                      </div>
                    </ValidationProvider>

                    <ValidationProvider 
                      name="password_confirmation "
                      rules="required|min:8|confirmed:password">
                      <div class="form-group" slot-scope="{errors,valid}">
                        <label for="password_confirmation">Password Konfirmasi</label>
                        <input id="password_confirmation" 
                          type="password" 
                          class="form-control" 
                          name="password_confirmation"                         
                          v-model="form.password_confirmation"
                          :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                        />
                          <div class="invalid-feedback" v-if="errors[0]">
                            {{ errors[0] }}
                          </div>   
                      </div>
                    </ValidationProvider>

                    <div class="form-group">
                      <button type="submit" class="btn btn-primary btn-lg btn-block"    
                        tabindex="4"
                        :disabled="isLoadingForm">
                        <span v-if="isLoadingForm">
                          <i class="fas fa-circle-notch fa-spin"></i>
                        </span>
                        <span v-else>                
                          Reset Password
                        </span>
                      </button>
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
    middleware : "isNotLogin",  

    layout : 'empty',
    
    created(){
      if(!this.$route.query.token || !this.$route.query.email){
        this.$router.push("/");
      }
    },

    head() {
        return {
            title: 'Reset Password',
        }
    },    

    data(){
      return {
        isLoadingForm :  false,
        form : {
          email : this.$route.query.email,
          token :  this.$route.query.token,
          password : '',
          password_confirmation : ''
        }
      }
    },

    computed : {
      logo_url(){
        return (process.env.LOGO_URL || '/img/') + 'default.jpeg';
      }
    },

    methods: {
      onSubmit(invalid){
        if(invalid || this.isLoadingForm) return;

        this.isLoadingForm = true;

        this.$axios.post("/reset-password",this.form)
        .then(res => {
          this.$toaster.success("Berhasil Reset Password");
          this.$router.push("/login");
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