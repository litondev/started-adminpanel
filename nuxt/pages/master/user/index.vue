<template>
 <div class="page-wrapper">
    <div class="page-body">
      <div class="container-fluid">
        <div class="card">        
          <list-option-section />    
          
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
                      aria-label="Select all invoices">
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
                <tr v-for="(item,index) in items.data">
                  <td                    
                    v-if="headers.showCheckboxs">
                    <input class="form-check-input m-0 align-middle" 
                      type="checkbox" 
                      aria-label="Select invoice">
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
                      <button class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                      </button>
                      <button class="btn btn-danger btn-sm"> 
                        <i class="fa fa-print"></i>
                      </button>
                      <button class="btn btn-primary btn-sm">
                        <i class="fa fa-info-circle"></i>
                      </button>
                      <button class="btn btn-success btn-sm">
                        <i class="fa fa-edit"></i>
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
 </div>
</template>

<script>
import MixinHeader from "@/mixins/datas/headers";
import MixinsParameter from "@/mixins/datas/parameters";
import MixinItem from "@/mixins/datas/items";
import MixinCheckbox from "@/mixins/datas/checkbox";
import MixinLoadingPage from "@/mixins/datas/loading-page";

import MixinOnLoad from "@/mixins/methods/on-load";
import MixinOnSort from "@/mixins/methods/on-sort";

import PageHead from "@/mixins/heads/head";

export default {
  mixins : [
    MixinHeader,
    MixinsParameter,
    MixinItem,
    MixinLoadingPage,
    MixinCheckbox,

    MixinOnLoad,
    MixinOnSort,

    PageHead
  ],

  created(){
   this.onLoad();
  },

  data(){
    return {
      title : 'User',

      url : "/master/user",    
    }
  },  

  methods : {      
    onAdd(){

    },

    onEdit(){

    },

    onDetail(){

    },

    onSubmit(){

    },

    onDestroy(){

    },

    onRestore(){

    },

    onDestroyAll(){

    },

    onRestoreAll(){

    },

    onCheckAll(evt){
      let tmpCheckboxs = [];

      document.querySelectorAll("input[name='checkboxs[]']").forEach(item => {
        item.checked = evt.target.checked;
        if(evt.target.checked){
          tmpCheckboxs.push(item.value);
        }
      })

      this.parameters.form.checkboxs = tmpCheckboxs
    }    
  }
}
</script>