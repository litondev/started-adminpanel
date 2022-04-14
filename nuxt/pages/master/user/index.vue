<template>
 <div class="page-wrapper">
    <div class="page-body">
      <div class="container-fluid">
        <div class="card">        
          <list-option-section> 
            <template v-slot:left>
              <div class="mx-2 mt-1 mb-1">  
                <button class="btn btn-primary btn-sm"
                  @click="$router.push('/master/user/import')">
                  <i class="fa fa-download"
                   style="margin-right: 5px"></i> Import
                </button>
              </div>
            </template>
          </list-option-section>
          
          <list-all-option-section />        

          <div class="table-responsive"
            style="min-height: 100px">
            <table class="table card-table table-vcenter text-nowrap datatable table-sm">
              <thead>
                <tr>
                  <th class="w-1"
                    v-if="headers.showCheckboxs">
                    <input class="form-check-input m-0 align-middle" 
                      type="checkbox" 
                      aria-label="Select all invoices"
                      @click="onCheckAll">
                  </th>

                  <th>
                    No
                  </th>

                 <th-sort-table-section 
                    title="Kode"
                    field="code"/>
             
                 <th-sort-table-section 
                    title="Nama"
                    field="name"/>

                 <th-sort-table-section 
                    title="Username"
                    field="username"/>

                 <th-sort-table-section 
                    title="Email"
                    field="email"/>
                         
                  <th></th>
                </tr>
              </thead>

              <tbody v-if="items.data.length && !isLoadingPage">
                <tr v-for="(item,index) in items.data"
                  :key="index">
                  <td                    
                    v-if="headers.showCheckboxs">
                    <input class="form-check-input m-0 align-middle" 
                      type="checkbox" 
                      :value="item.id"
                      name="checkboxs[]"
                      aria-label="Select invoice"
                      v-model="checkboxs">
                  </td>
                  <td><span class="text-muted">{{ index++ + 1 }}</span></td>
                  <td><span class="text-muted">{{ item.code }}</span></td>
                  <td>
                    {{item.name}}
                  </td>
                  <td>
                    {{item.username}}
                  </td>
                  <td>
                    {{item.email}}
                  </td>           
                  <td class="text-end">
                    <div class="btn-group">
                      <button class="btn btn-danger btn-sm"
                        @click="onDestroy(item)"
                        v-if="!item.deleted_at">
                        <i class="fa fa-trash"></i>
                      </button>
                      <button class="btn btn-danger btn-sm"
                        @click="onPrintDetail(item)"
                        v-if="!item.deleted_at">
                        <i class="fa fa-print"></i>
                      </button>
                      <button class="btn btn-primary btn-sm"
                        @click="onDetail(item)"
                        v-if="!item.deleted_at"
                        data-bs-toggle="modal" 
                        data-bs-target="#modal-detail">
                        <i class="fa fa-info-circle"></i>
                      </button>
                      <button class="btn btn-success btn-sm"
                        @click="onEdit(item)"
                        v-if="!item.deleted_at">
                        <i class="fa fa-edit"></i>
                      </button>
                      <button class="btn btn-success btn-sm"
                        v-if="item.deleted_at"
                        @click="onRestore(item)">
                        <i class="fa fa-circle-notch fa-spin fa-1x"
                          v-if="isLoadingRestore && idLoadingRestore == item.id"></i>                  
                        <i class="fa fa-redo"
                          v-else></i>
                      </button>
                    </div>
                  </td>
                </tr>                  
              </tbody>
                     
              <table-data-not-found-section/>

              <table-data-loading-section/>

            </table>
          </div>

          <pagination-section />
        </div>    
      </div>
    </div> 

     <modal-form ref="modal-form" />

     <modal-detail />

     <report-section />

     <filter-section>
      <template>        
        <div class="row">
          <div class="col-12 mt-3">
            <div class="form-group">
              <label for="start_date">Awal</label>
              <input type="date" 
                class="form-control mt-2" 
                v-model="parameters.params.start_date"
              />
            </div>
          </div>
          <div class="col-12 mt-3">
            <div class="form-group">
              <label for="end_date">Akhir</label>
              <input type="date" 
                class="form-control mt-2" 
                v-model="parameters.params.end_date"
              />
            </div>
          </div>
        </div>            
      </template>
    </filter-section>
        
 </div>
</template>

<script>
import MixinHeader from "@/mixins/datas/headers";
import MixinsParameter from "@/mixins/datas/parameters";
import MixinItem from "@/mixins/datas/items";
import MixinCheckbox from "@/mixins/datas/checkbox";

import MixinOnLoad from "@/mixins/methods/on-load";
import MixinOnSort from "@/mixins/methods/on-sort";
import MixinOnCheckAll from "@/mixins/methods/on-check-all";
import MixinOnPrintDetail from "@/mixins/methods/on-print-detail";
import MixinOnDestroy from "@/mixins/methods/on-destroy";
import MixinOnDestroyAll from "@/mixins/methods/on-destroy-all";
import MixinOnRestore from "@/mixins/methods/on-restore";
import MixinOnRestoreAll from "@/mixins/methods/on-restore-all";

import MixinOnAdd from "@/mixins/methods/on-add";
import MixinOnEdit from "@/mixins/methods/on-edit";
import MixinOnDetail from "@/mixins/methods/on-detail";
import MixinOnGetCode from "@/mixins/methods/on-get-code";
import MixinOnSubmit from "@/mixins/methods/on-submit";

import PageHead from "@/mixins/heads/head";

import ModalDetail from "./detail";
import ModalForm from "./form";

export default {
  mixins : [
    MixinHeader,
    MixinsParameter,
    MixinItem,
    MixinCheckbox,

    MixinOnLoad,
    MixinOnSort,
    MixinOnCheckAll,
    MixinOnPrintDetail,
    MixinOnDestroy,
    MixinOnDestroyAll,  
    MixinOnRestore,
    MixinOnRestoreAll,

    MixinOnAdd,
    MixinOnEdit,
    MixinOnDetail,
    MixinOnGetCode,
    MixinOnSubmit,
    
    PageHead
  ],

  created(){
   this.onLoad();
  },

  data(){
    return {
      title : 'User',

      url : "/master/user",

      form : {
        code : '',
        name : '',
        username : '',
        email : '',
        password: ''        
      },

      default_form : {
        code : '',
        name : '',
        username : '',
        email : '',
        password: ''      
      },

      relasional_form : [
        {'form' : 'user_id','item' : 'user'}
      ]
    }
  },  

  components : {
    ModalDetail,
    ModalForm
  }  
}
</script>