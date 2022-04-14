<template>
  <portal to="modal-other" :order="1">
    <div class="modal fade" 
      aria-hidden="true" 
      id="modal-user-data">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">
                  User Data
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
     
            <div class="modal-body">
              <div class="row">
                <div class="mb-2 col-md-4">
                  <input type="text" class="form-control form-control-sm" placeholder="Search . . ."
                    v-model="parameters.params.search">              
                </div>
                <div class="mb-2 col-md-4">
                  <select class="form-control form-control-sm"
                    v-model="parameters.params.per_page">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                  </select>
                </div>
                <div class="mb-2 col-md-4 d-flex justify-content-end">
                  <button class="btn btn-success btn-sm"
                    @click="onReset">Reset</button>
                </div>
              </div>

              <div class="table-responsive">
                <table class="table table-sm">
                  <tr>
                   <th @click="onSort('name',parameters.params.sort == 'asc' ? 'desc' : 'asc')"
                      class="cursor-pointer">             
                      <div class="d-flex flex-row justify-content-between align-items-baseline">
                        <div>Nama</div>
                        <div>
                          <i class="fas fa-caret-up"
                            :class="parameters.params.order == 'name' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                          <i class="fas fa-caret-down"
                            :class="parameters.params.order == 'name' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                        </div>
                      </div>
                    </th>
                        
                     <th @click="onSort('code',parameters.params.sort == 'asc' ? 'desc' : 'asc')"
                      class="cursor-pointer">             
                      <div class="d-flex flex-row justify-content-between align-items-baseline">
                        <div>Kode</div>
                        <div>
                          <i class="fas fa-caret-up"
                            :class="parameters.params.order == 'code' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                          <i class="fas fa-caret-down"
                            :class="parameters.params.order == 'code' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                        </div>
                      </div>
                    </th>
                  </tr>
                    <tbody v-if="items.data.length && !isLoadingPage">
                  <tr v-for="(item,index) in items.data"
                    :key="index">
                    <td>{{ item.name }}</td>
                    <td>{{ item.code }}</td>
                    <td>
                      <div class="btn-group">
                        <button class="btn btn-success btn-sm"
                          @click="onChoose(item)">Pilih</button>
                      </div>
                    </td>
                  </tr>
                    </tbody>

                  <tbody v-if="isLoadingPage">
                    <tr>
                      <td colspan="100"                  
                        style="height: 250px">
                        <div class="d-flex align-items-center justify-content-center w-100">
                          <i class="fa fa-circle-notch fa-spin fa-3x"></i>                       
                        </div>
                      </td>
                    </tr>            
                  </tbody>

                  <tbody v-if="!isLoadingPage && !items.data.length">
                    <tr>
                      <td colspan="100"
                        class="text-center">
                        <img
                            src="/img/data-not-found.svg"
                            style="height: 250px; object-fit: cover"
                        />
                        <div class="mt-3 mb-3">Data Tidak Ditemukan</div>
                      </td>
                    </tr>            
                  </tbody>
                </table>
              </div>

              <div class="text-center mt-3">
                <button class="btn btn-primary btn-sm"
                  v-if="items.current_page != 1"
                  @click="parameters.params.page -= 1;onLoad()">Next</button>
                <div class="d-inline mr-2 ml-2">{{items.current_page}}</div>
                <button class="btn btn-primary btn-sm"
                  v-if="items.current_page != items.last_page"
                  @click="parameters.params.page += 1;onLoad()">Next</button>
              </div>
            </div>            
        </div>
      </div>
    </div>
  </portal>
</template>

<script>
import MixinOnSort from "@/mixins/methods/on-sort";
import MixinsParameter from "@/mixins/datas/parameters";
import MixinItem from "@/mixins/datas/items";

export default {
  props: ['onChoose'],
  data(){
    return {
      isLoadingPage : false,
    }
  },

  mixins : [
    MixinOnSort,
    MixinsParameter,
    MixinItem
  ],
  methods : {
    onLoad(){
      if(this.isLoadingPage) return;

      this.isLoadingPage = true;

      this.$axios.get("/master/customer/get-user",{
        params : this.parameters.params
      })
      .then(res => {
        this.isLoadingPage = false;
        this.items = res.data;
      })
      .catch(err => {
        console.log(err);
      })
    },
    onReset(){
      this.parameters.params = {
        ...this.parameters.default_params,
      }

      this.onLoad();
    }
  }
}
</script>
