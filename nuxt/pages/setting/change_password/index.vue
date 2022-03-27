<template>
  <section class="section">
    <div class="section-body">     
      <div class="row mt-sm-4">              
        <div class="col-12 col-lg-8">
          <div class="card">
            <div class="card-header">
              <h4>Ubah Password</h4>
            </div>        
            <div class="col-12 mt-3">
              <ValidationObserver v-slot="{invalid,validate}">  
                <form @submit.prevent="validate().then(onSubmit(invalid))"
                  autocomplete="off">             
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
                    name="old_password"
                    rules="required|min:8">
                    <div class="form-group" slot-scope="{errors,valid}">
                      <label for="old_password">Password Lama</label>
                      <input id="old_password" 
                        type="password" 
                        class="form-control" 
                        name="old_password"                         
                        v-model="form.old_password"
                        :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                      />

                      <div class="invalid-feedback" v-if="errors[0]">
                        {{ errors[0] }}
                      </div>   
                    </div>
                  </ValidationProvider>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary" tabindex="4"
                      :disabled="isLoadingForm">
                      <span v-if="isLoadingForm">
                        <i class="fas fa-circle-notch fa-spin"></i>
                      </span>
                      Ubah Password                    
                    </button>
                  </div>
                </form>
              </ValidationObserver>  
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>  
</template>

<script>
export default {
  head() {
    return {
      title: 'Ubah Password',
    }
  },

  data(){
    return {
      form : {      
        password : '',
        old_password : ''        
      },
      isLoadingForm : false
    }
  },

  methods : {
    onSubmit(isInvalid){
      if(isInvalid || this.isLoadingForm) return false;      

      this.isLoadingForm = true;

      this.$axios.put("/profil/password",this.form)
      .then(res => {              
        this.$toaster.success("Berhasil update data");
      })
      .catch(err => {
        console.log(err);
        this.$globalErrorToaster(this.$toaster,err);
      })
      .finally(() => {
        this.isLoadingForm = false;
      })
    }
  }
}
</script>