<template>
 <div class="page-wrapper">
    <div class="page-body">
      <div class="container-fluid">
        <div class="card">        
          <list-option-section/>            
          
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
             
                  <th>
                    Pelanggan
                  </th>
 
                  <th-sort-table-section 
                    title="Total"
                    field="total"/>

                  <th-sort-table-section 
                    title="Grand Total"
                    field="grand_total"/>

                  <th-sort-table-section 
                    title="Jumlah"
                    field="quantity"/>
                         
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
                    Pelanggan : {{item.customer ? item.customer.name : '-'}}
                    <br/>
                    Pengguna : {{item.user ? item.user.name : '-'}}
                  </td>
                  <td>
                    {{item.total}}
                  </td>
                  <td>
                    {{item.grand_total}}
                  </td>  
                  <td>
                    {{item.quantity}}
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
import MixinsParameter from "@/mixins/datas/parameters";
import MixinItem from "@/mixins/datas/items";

import MixinOnLoad from "@/mixins/methods/on-load";
import MixinOnSort from "@/mixins/methods/on-sort";
import MixinOnPrintDetail from "@/mixins/methods/on-print-detail";
import MixinOnDestroy from "@/mixins/methods/on-destroy";
import MixinOnRestore from "@/mixins/methods/on-restore";

import MixinOnDetail from "@/mixins/methods/on-detail";

import PageHead from "@/mixins/heads/head";

import ModalDetail from "./detail";

export default {
  mixins : [
    MixinsParameter,
    MixinItem,

    MixinOnLoad,
    MixinOnSort,
    MixinOnPrintDetail,
    MixinOnDestroy,
    MixinOnRestore,

    MixinOnDetail,
    
    PageHead
  ],

  created(){
   this.onLoad();
  },

  data(){
    return {
      title : 'Transaksi Produk',

      url : "/transaction/product",

      form : {
        code : '',
        total : 0,
        grand_total : 0,
        quantity : 0,
        customer_id : '',
        user_id : ''            
      },

      default_form : {
        code : '',
        total : 0,
        grand_total : 0,
        quantity : 0,
        customer_id : '',
        user_id : ''          
      },

      relasional_form : [
        {'form' : 'customer_id','item' : 'customer'},
        {'form' : 'user_id','item' : 'user'}
      ],

       headers : {
          showPerPage : true,
          showSearch : true,
          showAdd : true,
          showFilter : true,
          showMaintenance : true,
          showReport : true,

          showCheckboxs : false,
          showDestroyAll : false,
          showRestoreAll : false,        
        },
    }
  },  

  methods: {
    onAdd(){
      this.$router.push("/transaction/product/add");
    },

    onEdit({ id }){
      this.$router.push("/transaction/product/"+id);
    }
  },

  components : {
    ModalDetail,
  }  
}
</script>