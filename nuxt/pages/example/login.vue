<template>
 <div id="app">
    <section class="section">
      <div class="container mt-1">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">

            <div class="login-brand p-3 m-0">
              <img :src="logo_url"
                alt="logo"                          
                class="rounded-circle"
                style="width:150px;height:150px;object-fit:contain">
            </div>

            <div class="card card-primary">
              <div class="card-header">
                <h4>Login</h4>
              </div>

              <div class="card-body">
                <ValidationObserver v-slot="{invalid,validate}">

                  <form @submit.prevent="validate().then(onSubmit(invalid))"
                    autocomplete="off">
                    <ValidationProvider
                      name="email"
                      rules="required">
                      <div class="form-group" slot-scope="{errors,valid}">
                        <label for="email">Email / Username </label>
                        <input id="email"
                          type="text"
                          class="form-control"
                          name="email"
                          v-model="form.email"
                          :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                          required
                        />
                        <div class="invalid-feedback" v-if="errors[0]">
                          {{ errors[0] }}
                        </div>
                        <!-- <div class="valid-feedback" v-if="valid">
                          Email Telah Tervalidasi
                        </div> -->
                      </div>
                    </ValidationProvider>

                    <ValidationProvider
                      name="password"
                      rules="required|min:8">
                      <div class="form-group" slot-scope="{errors,valid}">                        
                        <div class="d-block">
                          <label for="password" class="control-label">Password</label>
                            <div class="float-right">
                              <!--
                                <nuxt-link to="/forgot-password" class="text-small">
                                  Forgot Password?
                                </nuxt-link>
                              -->
                            </div>
                        </div>                        
                        <input
                          type="password"
                          class="form-control"
                          name="password"
                          v-model="form.password"
                          :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                          required
                        />
                          <div class="invalid-feedback" v-if="errors[0]">
                            {{ errors[0] }}
                          </div>
                          <!-- <div class="valid-feedback" v-if="valid">
                            Password Telah Tervalidasi
                          </div> -->
                      </div>
                    </ValidationProvider>

                    <!--
                      <div class="form-group">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox"
                            name="remember"
                            class="custom-control-input"
                            tabindex="3"
                            id="remember-me">
                          <label class="custom-control-label" for="remember-me">Remember Me</label>
                        </div>
                      </div>
                    -->

                    <div class="form-group">
                      <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4"
                        :disabled="isLoadingForm">
                        <span v-if="isLoadingForm">
                          <i class="fas fa-circle-notch fa-spin"></i>
                        </span>
                        <span v-else>
                          Login
                        </span>
                      </button>
                    </div>
                  </form>

                </ValidationObserver>
              </div>
            </div>

            <div class="simple-footer">
              Copyright &copy; {{company_name}} - {{year}}.<br>
              Stisla. Developed by <a href="http://anabasoftware.com">Anabasoftware</a>
            </div>
          </div>
        </div>
      </div>
    </section>
 </div>
</template>

<script>
export default {
    layout : 'empty',

    middleware : "isNotLogin",

    head() {
      return {
        title: 'Login',
      }
    },
    
    data(){
		  return {
        isLoadingForm : false,
			  form : {
				  email : null,
				  password : null
			  }
		  }
	  },

    computed : {
      logo_url(){
        return (process.env.LOGO_URL || '/img/') + 'default.jpeg';
      },
      
      year(){
        return new Date().getFullYear();
      },

      company_name(){
        let company_name = this.$store.state.setting.settings.find(item => item.name == "company_name");
        
        return company_name 
          ? company_name.value 
          : "Software Anak Bangsa";
      },
    },

    methods:{
      onSubmit(isInvalid){
        if(isInvalid || this.isLoadingForm) return;

        this.isLoadingForm = true;

        this.$auth.login({
          data: this.form,
        }).then(res => {                     
          this.$router.push("/");                              
        }).catch(err => {
          this.isLoadingForm = false;
          this.$globalErrorToaster(this.$toaster,err);
        })
      }
    }
}
</script>
