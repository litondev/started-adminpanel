<template>
  <section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="card-title">
                <list-option-section
                  :self="this"
                  ref="form-option"
                />
              </div>

              <!-- start table -->
              <div class="table-responsive">
                <table class="table table-striped table-sm vld-parent"
                  ref="formContainer">
                  <thead>
                    <tr>                     
                      <th>No</th>
                      <th @click="onSort('username',parameters.params.sort == 'asc' ? 'desc' : 'asc')"
                        class="cursor-pointer">
                        <div class="d-flex flex-row justify-content-between align-items-baseline">
                          <div>Username</div>
                          <div>
                            <i class="fas fa-caret-up"
                              :class="parameters.params.order == 'username' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                            <i class="fas fa-caret-down"
                              :class="parameters.params.order == 'username' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                          </div>
                        </div>
                      </th>
                      <th @click="onSort('email',parameters.params.sort == 'asc' ? 'desc' : 'asc')"
                        class="cursor-pointer">
                        <div class="d-flex flex-row justify-content-between align-items-baseline">
                          <div>Email</div>
                          <div>
                            <i class="fas fa-caret-up"
                              :class="parameters.params.order == 'email' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                            <i class="fas fa-caret-down"
                              :class="parameters.params.order == 'email' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                          </div>
                        </div>
                      </th>
                      <th class="text-center">Options</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item,i) in data" :key="i">
                      <td>{{ i + 1 }}</td>
                      <td>{{ item.username }}</td>
                      <td>{{ item.email }}</td>
                    
                      <td class="text-center">
                        <div class="btn-group">
                          <button class="btn btn-sm btn-success" 
                            @click="onDetail(item)">
                            <i class="fas fa-info-circle"></i>
                          </button>
                          <button class="btn btn-sm btn-primary" 
                            @click="onEdit(item)">
                            <i class="fas fa-pen"></i>
                          </button>
                          <!--
                          <button class="btn btn-sm btn-danger" 
                            @click="onTrashed(item)" v-if="!item.deleted_at">
                            <i class="fas fa-trash"></i>
                          </button>
                          <button class="btn btn-sm btn-success" 
                            @click="onRestored(item)" v-if="item.deleted_at">
                            <i class="fas fa-redo"></i>
                          </button>
                          -->
                        </div>
                      </td>
                    </tr>
                  </tbody>

                  <table-data-loading-section
                    :self="this"
                  />

                  <table-data-not-found-section
                    :self="this"
                  />
                </table>
              </div>
              <!-- end table -->

              <div class="card-title border-top"
                style="padding-bottom: -100px !important">
                <pagination-section
                  :self="this"
                  ref="pagination"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <ModalDetail
      :self="this"
      ref="modal-detail"
    />

    <FormInput
      :self="this"
      ref="form-input"
    />

  </section>
</template>

<script>
import { mapActions,mapState,mapMutations } from 'vuex'
import FormInput from "./form";
import ModalDetail from "./detail";

export default {
  middleware({ store, redirect }) {
    let user = store.state.auth.user;

    if(user.parent_id){
      return redirect('/')
    }
  },

  head() {
    return {
      title: 'Akun',
    }
  },

  created() {
    this.set_data([]);
    this.onLoad();
  },

  mounted() {
    this.$refs["form-option"].isExport          = false;
    this.$refs['form-option'].isFilter          = false;
    this.$refs["form-option"].isMaintenancePage = false;
  },

  data() {
    return {
      title               : 'Akun',
      isLoadingData       : false,
      isPaginate          : true,
      parameters : {
        url : 'setting/user',
        type : 'pdf',
        params :{
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,
        },
        form : {
          checkboxs : []
        },
        loadings : {
          isDelete  : false,
          isRestore : false,
        }
      }
    }
  },

  components : {
     FormInput,
     ModalDetail
  },

  computed : {
    ...mapState('moduleApi',[
      'data',
      'error',
      'result'
    ]),
  },

  methods : {
    ...mapActions('moduleApi',[
      'getData',
      'deleteData',
      'restoreData',
      'deleteAllData',
      'restoreAllData'
    ]),

    ...mapMutations('moduleApi',[
      'set_data'
    ]),

    onFormShow(){
      this.$refs["form-input"].parameters.form = {
        fullname : '',
        username : '',
        password : '',
        email    : '',      
        group_role_id : '',
        parent_id : ''
      };
      this.$refs["form-input"].isEditable = false;
      window.$("#modal-form").modal("show")
      this.$refs["form-input"].$refs['form-validate'].reset();
    },

    onEdit(item){
      this.$refs["form-input"].isEditable = true;
      this.$refs["form-input"].parameters.form = {
        ...item,
        group_role_id : item.group_role 
          ? item.group_role 
          : {},
      };
      window.$("#modal-form").modal("show");
      this.$refs["form-input"].$refs['form-validate'].reset();
    },

    onDetail(item){
      this.$refs["modal-detail"].parameters.form = {
        ...item,
        group_role : item.group_role 
          ? item.group_role 
          : { grants : [] },
      };
      window.$("#modal-detail").modal("show");
    },

    async onLoad(page = 1){
      if(this.isLoadingData) return;

      this.isLoadingData            = true;
      this.parameters.params.page   = page

      this.parameters.form.checkboxs = [];
      if(document.getElementById("checkAll")){
        document.getElementById("checkAll").checked = false;
      }

      let loader = this.$loading.show({
        container: this.$refs.formContainer,
        canCancel: true,
        onCancel: this.onCancel,
      });

      await this.getData(this.parameters);

      if(this.result == true){
        loader.hide();

        if (page == 1) {
            this.$refs['pagination'].generatePage();
        }

        this.$refs['pagination'].active_page = this.parameters.params.page;  
      }else{
        this.$globalErrorToaster(this.$toaster,this.error);
      }

      this.isLoadingData = false;
    },

    onSort(column,sort = 'asc'){
      this.parameters.params = {
        ...this.parameters.params,
        order : column,
        sort : sort
      }

      this.onLoad(this.parameters.params.page)
    }
  }
}
</script>