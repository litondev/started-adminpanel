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

          
              <div class="form-group p-2">
                <label for="payment_type">Type Pembayaran</label>
                <div>
                   <select class="form-control"
                    v-model="$parent.form.payment_type">
                    <option value="CASH">Cash</option>
                    <option value="CREDIT">Credit</option>
                   </select>
                </div>
              </div>

              <div class="form-group p-2">
                <label for="address">Alamat</label>
                <div>
                  <textarea class="form-control" v-model="$parent.form.address"></textarea>
                </div>
              </div>

              <ValidationProvider
                name="user_id"
                rules="required">
                <div class="form-group p-2" slot-scope="{errors,valid}">
                  <label for="user_id">User</label>
                  <div>              
                    <input type="text" class="form-control" 
                      name="user_id"
                      :value="typeof $parent.form.user_id == 'object' ? $parent.form.user_id.name : ''" 
                      :class="errors[0] ? 'is-invalid' : (valid ? 'is-valid' : '')"
                      @focus="showModalUser">

                      <div class="invalid-feedback" v-if="errors[0]">
                        {{ errors[0] }}
                      </div>
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

    <modal-user 
      ref="modal-user-data" 
      :onChooseUser="onChooseUser"/>
  </portal>
</template>

<script>
import ModalUser from "./modal/user.vue";

export default {
  data(){
    return {
      modal_user : null
    }
  },

  components : {
    ModalUser
  },

  methods: {
    showModalUser(){
      this.modal_user = new bootstrap.Modal(document.getElementById('modal-user-data'), {
		    keyboard: false
		  })

      this.modal_user.show();
      this.$parent.modal_form.hide();
      this.$refs["modal-user-data"].onLoad();
    },

    onChooseUser(item){
      this.modal_user.hide();
      this.$parent.modal_form.show();
      this.$parent.form.user_id = item;
    }
  }
}
</script>
