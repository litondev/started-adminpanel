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
                      <th>Username</th>
                      <th>Deskripsi</th>
                      <th>Detail</th>
                      <th>Dibuat</th>
                      <th class="text-center">Options</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item,i) in data" :key="i">
                      <td>{{ i + 1 }}</td>
                      <td>{{ item.causer ? item.causer.username : '-' }}</td>
                      <td>{{ item.description }}</td>
                      <td>
                        Table : {{ item.properties ? item.properties.table || '-' : '-' }}   <br/>
                        Nama : {{ item.properties ? item.properties.name || '-': '-' }}
                      </td>
                      <td>
                        {{ onHumanReadAble(item.created_at) }}
                      </td>
                      <td class="text-center">
                        <div class="btn-group">
                          <button class="btn btn-sm btn-success" 
                            @click="onDetail(item)">
                            <i class="fas fa-info-circle"></i>
                          </button>
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

  <filter-section
    :self="this"
    ref="form-filter">
    <template>
      <div class="col-md-12">
        <div class="form-group">
          <label for="created_at">Dibuat Pada</label>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="start_date">Awal</label>
                <input type="date" 
                  class="form-control" 
                  v-model="parameters.params.start_date"
                />
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="end_date">Akhir</label>
                <input type="date" 
                  class="form-control" 
                  v-model="parameters.params.end_date"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </filter-section>

  </section>
</template>

<script>
import { mapActions,mapState,mapMutations } from 'vuex'
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
      title: 'Aktifitas',
    }
  },

  created() {
    this.set_data([]);
    this.onLoad();
  },

  mounted() {    
    this.$refs["form-option"].isExport          = false;
    this.$refs["form-option"].isAddData         = false;
    this.$refs["form-option"].isMaintenancePage = false;
    this.$refs['form-option'].isFilter          = true;
  },

  data() {
    return {
      title               : 'Log Aktifitas',
      isLoadingData       : false,
      isPaginate          : true,
      parameters : {
        url : 'setting/activity',
        params :{
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,
          start_date  : '',
          end_date    : ''
        },
        default_params : {
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,
          start_date  : '',
          end_date    : ''
        }      
      }
    }
  },

  components : {
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
      'getData'
    ]),

    ...mapMutations('moduleApi',[
      'set_data'
    ]),

    onDetail(item){
      this.$refs["modal-detail"].parameters.form = {
        ...item
      };

      window.$("#modal-detail").modal("show");
    },

    async onLoad(page = 1){
      if(this.isLoadingData) return;

      this.isLoadingData            = true;
      this.parameters.params.page   = page
  
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
    },

    onHumanReadAble(date){
      return moment(date).locale("id").format("LLLL")
    },

    onFormShow(){}
  }
}
</script>