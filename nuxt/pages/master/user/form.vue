<template>
  <portal to="modal-form">
    <div class="modal fade" 
      aria-hidden="true" 
      id="modal-form">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">
                {{ $parent.isEditable ? 'Edit' : 'Tambah' }} Data
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
     
            <div class="modal-body">
              <ValidationObserver v-slot="{invalid,validate}" ref="form">

              <form @submit.prevent="validate().then($parent.onSubmit(invalid))"
                    autocomplete="off">

              <div class="row">
                  <div class="col-md-6">                  
                    <ValidationProvider
                        name="code"
                        rules="required">
                    <div class="form-group p-2" slot-scope="{errors,valid}">
                      <label for="code">Kode</label>
                      <div>                     
                        <input type="text"
                          class="form-control form-control-sm"
                          v-model="$parent.form.code"
                          :disabled="true"
                          name="code"
                          :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                        />

                        <div class="invalid-feedback" v-if="errors[0]">
                          {{ errors[0] }}
                        </div>
                      </div>
                    </div>                  
                    </ValidationProvider>
                  </div>

                  <div class="col-md-6">
                    <ValidationProvider
                        name="name"
                        rules="required">
                    <div class="form-group p-2" slot-scope="{errors,valid}">
                      <label for="name">Nama</label>
                      <div>
                        <input type="text"
                          class="form-control form-control-sm"
                          v-model="$parent.form.name"
                          name="name"
                          :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                        />

                        <div class="invalid-feedback" v-if="errors[0]">
                          {{ errors[0] }}
                        </div>
                      </div>
                    </div>
                    </ValidationProvider>
                  </div>                             
              </div>            

              <div class="row">
                  <div class="col-md-6">                  
                    <ValidationProvider
                        name="username"
                        rules="required">
                    <div class="form-group p-2" slot-scope="{errors,valid}">
                      <label for="username">Username</label>
                      <div>
                        <input type="text"
                          class="form-control form-control-sm"
                          v-model="$parent.form.username"                      
                          name="username"
                          :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                        />

                        <div class="invalid-feedback" v-if="errors[0]">
                          {{ errors[0] }}
                        </div>
                      </div>
                    </div>                  
                    </ValidationProvider>
                  </div>

                  <div class="col-md-6">
                    <ValidationProvider
                        name="email"
                        rules="required|email">
                    <div class="form-group p-2" slot-scope="{errors,valid}">
                      <label for="email">Email</label>
                      <div>
                        <input type="text"
                          class="form-control form-control-sm"
                          v-model="$parent.form.email"
                          name="email"
                          :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                        />

                        <div class="invalid-feedback" v-if="errors[0]">
                          {{ errors[0] }}
                        </div>
                      </div>
                    </div>
                    </ValidationProvider>
                  </div>                             
              </div>    

              <ValidationProvider
                name="password"
                :rules="!$parent.isEditable ? 'required' : ''">
                <div class="form-group p-2" slot-scope="{errors,valid}">
                  <label for="password">Password</label>
                  <input type="password"
                    class="form-control form-control-sm"
                    v-model="$parent.form.password"
                    :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                    />

                    <div class="invalid-feedback" v-if="errors[0]">
                      {{ errors[0] }}
                    </div>
                </div>
              </ValidationProvider>

              <div class="mt-2 mb-2 p-2">
                <button class="btn btn-primary btn-sm">
                 <span v-if="$parent.isLoadingForm">                 
                    <i class="fa fa-circle-notch fa-spin fa-1x"></i>
                  </span>
                  <span v-else>                                                
                    Kirim
                  </span>
                </button>
              </div>

              </form>
              </ValidationObserver>
            </div>            
        </div>
      </div>
    </div>
  </portal>
</template>